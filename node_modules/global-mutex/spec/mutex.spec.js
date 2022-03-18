/* eslint-disable no-console */
'use strict';

/*
expect(x).toEqual(y)	値が同じであること（==と同様）を評価
expect(x).toBe(y)	同じオブジェクトかどうか（===と同様）を評価
expect(x).toMatch(pattern)	xがpatternで指定した正規表現にマッチしているかを評価
expect(x).toBeDefined()	xに値が格納されているかを評価
expect(x).toBeUndefined()	xに値が格納されていないかを評価
expect(x).toBeNull()	xがnullかどうかを評価
expect(x).toBeTruthy()	xがtrueかどうかを評価
expect(x).toBeFalsy()	xがfalseかどうかを評価
expect(x).toContain(y)	配列や文字列xに、yが含まれているかどうかを評価
expect(x).toBeLessThan(y)	xがよりyが小さいかどうかを評価
expect(x).toBeGreaterThan(y)	xがよりyが大きいかどうかを評価
expect(function(){fn();}).toThrow(e);	function内でexceptionが発生するかどうかを評価
*/

//
jasmine.DEFAULT_TIMEOUT_INTERVAL = 30*1000;

//
const {Mutex, InitMutex, ResetMutex} = require('../lib/mutex.js');

//
describe('Mutex', function () {

  //
  beforeAll(function (done) {
    done();
  });
  //
  afterAll(function (done) {
    done();
  });
  beforeEach(function (done) {
    done();
  });
  //
  afterEach(function (done) {
    done();
  });

  it('create instance', function (done) {
    //
    InitMutex({expire: 60, redis: {host: '127.0.0.1', port: 6379}});  //USE REDIS (mutex for multi process, multi servers)
    ResetMutex(['LOCK_A']);
    //
    function delayDo(msg, msec){
      return new Promise(function(resolve){
        console.log('delayDo START !', msg);
        setTimeout(function(){
          console.log('delayDo END !', msg);
          resolve(true);
        }, msec);
      });
    }

    let mutex1 = new Mutex('LOCK_A');
    mutex1.sync(function(){
      //expect(runPosition++).toBe(0);
      console.log('START FUNC1');
      setTimeout(() => {
        console.log('END FUNC1');
        //expect(runPosition++).toBe(1);
        mutex1.unlock();
      }, 2000);
    });

    let mutex2 = new Mutex('LOCK_A');
    mutex2.sync(async function(){
      //expect(runPosition++).toBe(2);
      console.log('START FUNC2');
      await delayDo('B', 500);
      console.log('END FUNC2');
      //expect(runPosition++).toBe(3);
      mutex2.unlock();
      done();
    });

  });

  //
  it('create instance', function (done) {

    //
    InitMutex({});
    ResetMutex(['AAA']);

    //
    function delayDo(msg, msec){
      return new Promise(function(resolve){
        console.log('START !!', msg);
        setTimeout(function(){
          console.log('END !!', msg);
          resolve(true);
        }, msec);
      });
    }

    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(0);
        await delayDo('A', 500);
        //expect(runPosition++).toBe(1);
        mutex.unlock();
      });
    }
    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(2);
        await delayDo('B', 500);
        //expect(runPosition++).toBe(3);
        mutex.unlock();
      });
    }
    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(4);
        await delayDo('C', 500);
        //expect(runPosition++).toBe(5);
        mutex.unlock();
      });
    }
    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(6);
        await delayDo('D', 500);
        //expect(runPosition++).toBe(7);
        mutex.unlock();
      });
    }
    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(8);
        await delayDo('E', 500);
        //expect(runPosition++).toBe(9);
        mutex.unlock();
        //
        console.log('Mutex Done');
        done();
        //
      });
    }
    //
  });


  //
  it('create instance', function (done) {

    InitMutex({expire: 60, redis: {host: '127.0.0.1', port: 6379}});
    ResetMutex(['AAA']);

    //
    function delayDo(msg, msec){
      return new Promise(function(resolve){
        console.log('REDIS START !!', msg);
        setTimeout(function(){
          console.log('REDIS END !!', msg);
          resolve(true);
        }, msec);
      });
    }

    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(0);
        await delayDo('A', 500);
        //expect(runPosition++).toBe(1);
        mutex.unlock();
      });
    }
    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(2);
        await delayDo('B', 500);
        //expect(runPosition++).toBe(3);
        mutex.unlock();
      });
    }
    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(4);
        await delayDo('C', 500);
        //expect(runPosition++).toBe(5);
        mutex.unlock();
      });
    }
    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(6);
        await delayDo('D', 500);
        //expect(runPosition++).toBe(7);
        mutex.unlock();
      });
    }
    {
      let mutex = new Mutex('AAA');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(8);
        await delayDo('E', 500);
        //expect(runPosition++).toBe(9);
        mutex.unlock();
        //
        console.log('Mutex Done');
        done();
        //
      });
    }
    //
  });



  //
  it('mutex expire', function (done) {

    InitMutex({expire: 2, redis: {host: '127.0.0.1', port: 6379}});
    ResetMutex(['ZZZ']);

    //
    function delayDo(msg, msec){
      return new Promise(function(resolve){
        console.log('REDIS START !!', msg);
        setTimeout(function(){
          console.log('REDIS END !!', msg);
          resolve(true);
        }, msec);
      });
    }

    //let runPosition = 0;

    {
      let mutex = new Mutex('ZZZ');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(0);
        await delayDo('X', 500);
        //expect(runPosition++).toBe(1);
      });
    }
    {
      let mutex = new Mutex('ZZZ');
      mutex.sync(async function(){
        //expect(runPosition++).toBe(2);
        await delayDo('Z', 500);
        //expect(runPosition++).toBe(3);
        console.log('Mutex Done');
        done();
      });
    }
    //
  });

});
