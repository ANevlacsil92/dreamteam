/**
 * @file Singleton class
 * @author Tomonori Itou <tomi@codeworks.co.jp>
 * @copyright CODEWORKS Corp.
 * @version 1.0.0
 */
'use strict';


/**
 * force new singleton flag
 * @typedef {Symbol}  Singleton~SINGLETON_FORCE_NEW
 * @private
 * @const
 */
const SINGLETON_FORCE_NEW = Symbol('SINGLETON_FORCE_NEW');


class Singleton {

  /**
   * 引数から、識別キー、生成クラス名、新規生成を所得する
   * @private
   * @param {*} argv 引数リスト
   * @return {Json} {key:識別キー, tgtClass:生成クラス名, forceNew:新規生成}
   */
  static parseParams_(...argv) {
    //
    let argMax = argv.length;
    let tgtClass = argv[argMax - 1];
    let key = argv[argMax - 2];
    let forceNewFlag = argv[argMax - 3];
    //
    let forceNew = false;
    if (forceNewFlag === SINGLETON_FORCE_NEW) {
      forceNew = true;
    }
    //
    return {
      key: key,
      tgtClass: tgtClass,
      forceNew: forceNew,
    };
    //
  }

  /**
   * キャッシュインスタンス返却/インスタンスの生成
   * @private
   * @param {*} argv 引数リスト
   * @return {Object} Singletonインスタンス
   */
  static getInstance_(...argv) {
    //
    const params = Singleton.parseParams_(...argv);
    //
    this.cache = this.cache || {};
    this.cache[params.tgtClass.name] = this.cache[params.tgtClass.name] || {};
    //
    let resultInstance = null;
    if (this.cache[params.tgtClass.name][params.key]) {
      resultInstance = this.cache[params.tgtClass.name][params.key];
      resultInstance.newInstance_ = false;
    } else {
      resultInstance = new (params.tgtClass)(...argv, SINGLETON_FORCE_NEW);
      this.cache[params.tgtClass.name][params.key] = resultInstance;
      resultInstance.newInstance_ = true;
    }
    //
    return resultInstance;
    //
  }

  /**
   * 新規生成されたインスタンスかどうか
   * @readonly
   * @return {boolean}  新規生成されたインスタンスの場合はtrue、それ以外はfalse
   */
  get isNewInstance() {
    return this.newInstance_;
  }

  /**
   * コンストラクタ
   * @param {*} argv 引数リスト
   * @example
class TestClass extends Singleton{
  constructor(param1, param2, key){
    super(...arguments, key, TestClass);
    if(this.isNewInstance){
      // nre instance
    }
  }
}
   */
  constructor(...argv) {
    const params = Singleton.parseParams_(...argv);
    if (params.forceNew !== true) {
      return Singleton.getInstance_(...argv);
    }
  }
  //

}

////////////////////////////////////////////////////////////////////////////////
// exports
////////////////////////////////////////////////////////////////////////////////

module.exports = Singleton;

////////////////////////////////////////////////////////////////////////////////
// EOF
////////////////////////////////////////////////////////////////////////////////
