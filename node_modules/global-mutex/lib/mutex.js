'use strict';

//
const debug = require('debug')('mutex:core');
const debug_error = require('debug')('mutex:error');
const redis = require('redis');
const global = require('./global.js');

/**
 * @private
 * @typedef {Object} MutexStore~MutexStoreKind
 * @prop {Number} MEMORY - use memory
 * @prop {Number} REDIS - use redis
*/
const MutexStoreKind = {
  MEMORY : 0,
  REDIS : 1,
};

class MutexStore {
  //
  /**
   * @constructor
   * @private
   * @param  {MutexStore~MutexStoreKind} kind  - MutexStore種別
   * @param  {Object} [option = {host: '127.0.0.1', port: 6379}]  - Redis接続オプション
   * @param  {String} [prefix = globalmutex]  - キー文字列prefix
   * @param  {Number} [expire = 60]  - Redisキーexpire
   */
  constructor(kind, redisOption, prefix, expire){
    //
    redisOption = redisOption || {host: '127.0.0.1', port: 6379};
    //
    this.prefix = prefix || 'globalmutex:';
    this.expire = expire || 60;
    debug('MutexStore prefox ' + this.prefix);
    //
    if(kind === MutexStoreKind.MEMORY){
      debug('MutexStore Start MEMORY');
      this.kind = MutexStoreKind.MEMORY;  //memstore
      this.memstore = {};
    }else
    if(kind === MutexStoreKind.REDIS){
      debug('MutexStore Start Redis');
      this.kind = MutexStoreKind.REDIS;  //redis
      this.redisClient = redis.createClient(redisOption);
      this.redisClient.on('connect', () => {
        debug('MutexStore Connect Redis', redisOption);
      });
      this.redisClient.on('error', (err) => {
        debug('MutexStore Redis Error ', err);
      });
      //
    }
    //
    global.mutexStore_ = this;
    //
  }

  /**
   * キーの設定 & Mutex状態の取得
   * @param  {String} key_  - Mutexキー文字列
   * @param  {MutexStore~mutexSetCallback} callback  - キー設定完了コールバック
   */
  set(key_, callback){
    let key = this.prefix + key_;
    switch(this.kind){
      case MutexStoreKind.MEMORY:
        if(this.memstore[key]){
          if(callback)callback(null, 0);
        }else{
          this.memstore[key] = 1;
          if(callback)callback(null, 1);
        }
        return;
      case MutexStoreKind.REDIS:
        this.redisClient.setnx(key, 1, (err, result)=> {
          if(err){
            debug_error('MutexStore Redis SETNX Error ', err);
          }
          if(result == 0){
            if(callback)callback(null, 0);
          }else{
            this.redisClient.expire(key, this.expire, (err/*, expireresult*/)=> {
              if(err){
                debug_error('MutexStore Redis EXPIRE Error ', err);
              }
              if(callback)callback(null, 1);
            });
          }
        });
        return;
    }
    if(callback)callback(true);
  }
  /**
   * @callback MutexStore~mutexSetCallback
   * @param {Object}  err - エラーオブジェクト
   * @param {Number}  result  - キーがセットできた場合（新規Mutexが作成できた場合) = 1, そうで内場合は = 0
   */


  /**
   * Mutex解放
   * @param  {String} key_  - Mutexキー文字列
   * @param  {MutexStore~mutexReleaseCallback} callback  - キーリリース完了コールバック
   */
  release(key_, callback){
    let key = this.prefix + key_;
    switch(this.kind){
      case MutexStoreKind.MEMORY:
        delete this.memstore[key];
        if(callback)callback(null);
        return;
      case MutexStoreKind.REDIS:
        this.redisClient.del(key, (err)=> {
          if(err){
            debug_error('MutexStore Redis DEL Error ', err);
          }
          if(callback)callback(null);
        });
        return;
    }
    if(callback)callback(true);
  }

  /**
   * @callback MutexStore~mutexReleaseCallback
   * @param {Object}  err - エラーオブジェクト
   */

}

/**
 * Mutexストアを初期化し、Mutexの利用を開始する
 * @param  {MutexOption} option - オプション
 */
function InitMutex(option){
  //
  option = option || {};
  //
  if(option.redis){
    new MutexStore(MutexStoreKind.REDIS, option.redis, option.prefix, option.expire);
  }else{
    new MutexStore(MutexStoreKind.MEMORY, null, option.prefix, null);
  }
  //
}
/**
 * @typedef MutexOption
 * @prop {Object} [redis] - redis接続オプション(https://github.com/NodeRedis/node_redis)
 * @prop {String} [prefix = globalmutex]  - Mutexキー文字列prefix
 * @prop {Number} [expire = 60] - expire - キーexpire for redis
 */

/**
 * Mutexの強制リリース
 * @param  {(String|String[])} mutexID - 解放するMutexキー(s)
 */
function ResetMutex(mutexID){
  //
  if(!global.mutexStore_){
    debug_error('First need call InitMutex()');
    return;
  }
  //
  if(typeof mutexID === 'string'){
    mutexID = [mutexID];
  }
  //
  for(let i in mutexID){
    global.mutexStore_.release(mutexID[i], ()=>{});
  }
  //
}

//
class Mutex {

  /**
   * @constructor
   * @param  {String} key - Mutexキー文字列
   * @example

const {InitMutex, Mutex} = require('./global');

//MEMORY STORE (同一プロセス内mutex ~~criticalsection)
InitMutex({});

//USE REDIS (mutex for multi process, multi servers)
//InitMutex({prefix: 'mutex:', expire: 60, redis: {host: '127.0.0.1', port: 6379}});

let mutex = new Mutex('LOCK_A');  // create mutex
mutex.sync(function(){            // lock mutex
  console.log('START FUNC1');
  setTimeout(() => {
    console.log('END FUNC1');
    mutex.unlock();               // unlock mutex
  }, 2000);
});

   */
  constructor(key){
    if(!global.mutexStore_){
      debug_error('First need call InitMutex()');
    }
    this.key = key;
  }

  /**
   * Mutexロック開始
   * @param  {function} func  Mutex内で実行する関数
   */
  sync(func){
    if(!global.mutexStore_){
      return func();
    }
    global.mutexStore_.set(this.key, (err, value)=>{
      if(err){
        return;
      }
      if(value !== 0){
        func();
      }else{
        setImmediate(() => {
          this.sync(func);
        });
      }
    });
  }

  /**
   * Mutexロック解除
  */
  unlock(){
    if(!global.mutexStore_){
      return;
    }
    global.mutexStore_.release(this.key, ()=>{});
  }

}

////////////////////////////////////////////////////////////////////////////////
// EXPORTS
////////////////////////////////////////////////////////////////////////////////

module.exports = {
  Mutex: Mutex,
  ResetMutex: ResetMutex,
  InitMutex: InitMutex,
};

////////////////////////////////////////////////////////////////////////////////
// EOF
////////////////////////////////////////////////////////////////////////////////
