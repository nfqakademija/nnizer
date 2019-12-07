module.exports = {
  extends: ['stylelint-config-standard'],
  ignoreFiles: ['**/*.js', '**/*.jsx'],
  plugins: ['stylelint-scss', 'stylelint-order'],
  rules: {
    'max-nesting-depth': 4,
    'block-closing-brace-newline-after': [
      'always',
      {
        ignoreAtRules: ['if', 'else']
      }
    ],
    'at-rule-empty-line-before': [
      'always',
      {
        except: ['blockless-after-same-name-blockless', 'first-nested'],
        ignore: ['after-comment'],
        ignoreAtRules: ['else']
      }
    ],
    'at-rule-no-unknown': null,
    'scss/at-rule-no-unknown': true,
    'string-quotes': 'single',
    'order/order': ['custom-properties', 'declarations'],
    'order/properties-alphabetical-order': true
  }
};
