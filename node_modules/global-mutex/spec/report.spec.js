'use strict';

//
const reporters = require('jasmine-reporters');
const junitReporter = new reporters.JUnitXmlReporter({
  savePath: './test-reports/',
  consolidateAll: false
});
jasmine.getEnv().addReporter(junitReporter);
//
