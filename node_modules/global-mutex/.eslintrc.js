module.exports = {
    "parserOptions": {
        "ecmaVersion": 2017,
        "sourceType": "module"
    },
    "env": {
        "es6": true,
        "node": true
    },
    "extends": "eslint:recommended",
    "rules": {
        "indent": [
            "error",
            2,
            {"SwitchCase": 1}
        ],
        "linebreak-style": [
            "error",
            "unix"
        ],
        "quotes": [
            "error",
            "single"
        ],
        "semi": [
            "error",
            "always"
        ],
        // const or let を強制
        "no-var": 2,
    },
    "globals": {
        "jasmine": false,
        "describe": false,
        "beforeAll": false,
        "afterAll": false,
        "beforeEach": false,
        "afterEach": false,
        "it": false,
        "expect": false
    }
};
