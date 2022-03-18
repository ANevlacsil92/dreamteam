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
const SharedClass = require('./sharedclass.js');
const global = require('../').GlobalObject;

//
describe('Singleton(2nd)', function () {
  //
  beforeAll(function (done) {
    setTimeout(()=>{
      done();
    }, 1000);
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

  //
  it('global instance', function (done) {
    //
    expect(global.test).toBe('GlobalTest');
    //
    done();
    //
  });

  //
  it('create instance', function (done) {
    //
    console.log('Start 2nd(delay) describe');
    //
    let a = new SharedClass('KEY1');
    expect(a.isNewInstance).toBeFalsy();
    let b = new SharedClass('KEY2');
    expect(b.isNewInstance).toBeFalsy();
    let c = new SharedClass('KEY1');
    expect(c.isNewInstance).toBeFalsy();
    //
    expect(a).not.toBe(b);
    expect(b).not.toBe(c);
    expect(a).toBe(c);
    //
    done();
    //
  });

});
