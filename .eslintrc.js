require('eslint-plugin-jsx-a11y').rules = 0; // Disable a11y because time is constrained in this project and accesibility is not required

module.exports = {
    extends: ['eslint:recommended', 'plugin:react/recommended', 'airbnb'],
    parser: 'babel-eslint',
    parserOptions: {
        ecmaVersion: 6,
        sourceType: 'module',
        ecmaFeatures: {
            jsx: true,
            experimentalObjectRestSpread: true
        }
    },
    env: {
        browser: true,
        es6: true,
        node: true
    },
    rules: {
        "no-console": 0,
        "no-unused-vars": 0,
        "no-plusplus": [2, { allowForLoopAfterthoughts: true }]
    }
};
