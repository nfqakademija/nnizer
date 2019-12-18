module.exports = {
    extends: [
        'eslint:recommended',
        'plugin:react/recommended',
        'airbnb-base',
        'airbnb/rules/react',
    ],
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
        "no-plusplus": [2, { allowForLoopAfterthoughts: true }],
        "react/jsx-filename-extension": [1, { "extensions": [".js", ".jsx"] }],
        "no-param-reassign": 0,
        "object-curly-newline": ["error", {
            "ObjectExpression": { "multiline": true, "minProperties": 5, "consistent": true},
            "ObjectPattern": { "multiline": true, "minProperties": 5, "consistent": true},
            "ImportDeclaration": { "multiline": true, "minProperties": 5, "consistent": true},
            "ExportDeclaration": { "multiline": true, "minProperties": 5, "consistent": true}
        }]
    }
};
