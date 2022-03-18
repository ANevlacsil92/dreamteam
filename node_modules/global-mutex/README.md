# global-mutex
Global object and Mutex object implements.


## Global
```js
// file1.js
const global = require('global-mutex').GlobalObject;
//
global.param = 'a';
```
```js
// file2.js
const global = require('global-mutex').GlobalObject;
if(global.param === 'a'){
  // true
}
```

## Mutex
```js
  //
  const {InitMutex, Mutex} = require('global-mutex');
  //
  InitMutex({logger: logger});  //MEMORY STORE (only in process), logger > log4js
  //InitMutex({logger: logger, prefix: 'mutex:', expire: 60, redis: {host: '127.0.0.1', port: 6379}});  //if USE REDIS (mutex for multi process, multi servers)

  //
  function delayDo(msg, msec){
    return new Promise(function(resolve){
      console.log("delayDo START !", msg);
      setTimeout(function(){
        console.log("delayDo END !", msg);
        resolve(true);
      }, msec);
    });
  }

  let mutex = new Mutex('LOCK_A');
  mutex.sync(function(){
    console.log('START FUNC1');
    setTimeout(() => {
      console.log('END FUNC1');
      mutex.unlock();
    }, 2000);
  });

  let mutex = new Mutex('LOCK_A');
  mutex.sync(async function(){
    console.log('START FUNC2');
    await delayDo('B', 500);
    console.log('END FUNC2');
    mutex.unlock();
  });

```


## Singleton
```js
//
const {Singleton} = require('global-mutex');

//
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

} // End of SampleClass

//
let a = new SampleClass('P1', 'P2', 'KEY1');
let b = new SampleClass('P3', 'P4', 'KEY2');
let c = new SampleClass('P5', 'P6', 'KEY1');

if(a === c){  // 'KEY1' === 'KEY1'
  // true  
}   

if(a === b){  // 'KEY1' !== 'KEY2'
  // false  
}   

if(b !== c){  // 'KEY2' !== 'KEY1'
  // false  
}   

```
