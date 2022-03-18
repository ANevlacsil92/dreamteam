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
const {Singleton} = require('../');

////////////////////////////////////////////////////////////////////////////////
// doc sample test
////////////////////////////////////////////////////////////////////////////////

class SampleClass extends Singleton{

  constructor(param1, param2, key){
    //
    // param1, param2 is not dependent to create Singleton
    //
    super(...arguments, key, SampleClass);
    if(this.isNewInstance){
      //
      // new instance (first time only)
      //
    }
  }

}
//
let a = new SampleClass('P1', 'P2', 'KEY1');  // eslint-disable-line
let b = new SampleClass('P3', 'P4', 'KEY2');  // eslint-disable-line
let c = new SampleClass('P5', 'P6', 'KEY1');  // eslint-disable-line

////////////////////////////////////////////////////////////////////////////////
//
////////////////////////////////////////////////////////////////////////////////



class TestClass extends Singleton {
  constructor(option, key) {
    super(...arguments, key, TestClass);
    if (this.isNewInstance) {
      //console.log(arguments);
    }
  }
}

class TestClass2 extends Singleton {
  constructor(a, b, key) {
    //console.log('TestClass2', key, arguments);
    super(...arguments, key, TestClass2);
    if (this.isNewInstance) {
      //console.log(arguments);
    }
  }
}

class TestClass3 extends Singleton {
  constructor() {
    super(...arguments, 'dummy', TestClass3);
    if (this.isNewInstance) {
      //console.log(arguments);
    }
  }
}

//
describe('Singleton', function () {

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

  //
  it('create instance', function (done) {
    //
    let a = new TestClass({ a: 'a', b: 'b' }, 'KEY1');
    expect(a.isNewInstance).toBeTruthy();
    let b = new TestClass({ a: 'a', b: 'b' }, 'KEY2');
    expect(b.isNewInstance).toBeTruthy();
    let c = new TestClass({ a: 'A', b: 'B' }, 'KEY1');
    expect(c.isNewInstance).toBeFalsy();
    //
    expect(a).not.toBe(b);
    expect(b).not.toBe(c);
    expect(a).toBe(c);
    //
    done();
    //
  });

  //
  it('create instance 2', function (done) {
    //
    let a = new TestClass2('a', 'b', 'KEY1');
    expect(a.isNewInstance).toBeTruthy();
    let b = new TestClass2('c', 'd', 'KEY2');
    expect(b.isNewInstance).toBeTruthy();
    let c = new TestClass2('e', 'f', 'KEY1');
    expect(c.isNewInstance).toBeFalsy();
    //
    expect(a).not.toBe(b);
    expect(b).not.toBe(c);
    expect(a).toBe(c);
    //
    done();
    //
  });

  //
  it('create instance 3', function (done) {
    //
    let a = new TestClass3();
    expect(a.isNewInstance).toBeTruthy();
    let b = new TestClass3();
    expect(b.isNewInstance).toBeFalsy();
    let c = new TestClass3();
    expect(c.isNewInstance).toBeFalsy();
    //
    expect(a).toBe(b);
    expect(b).toBe(c);
    expect(a).toBe(c);
    //
    done();
    //
  });

});
