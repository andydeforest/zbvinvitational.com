import vue from 'eslint-plugin-vue'
import typescript from '@typescript-eslint/eslint-plugin'
import parserTs from '@typescript-eslint/parser'
import parserVue from 'vue-eslint-parser'
import prettier from 'eslint-plugin-prettier'

/** @type {import("eslint").Linter.FlatConfig} */
export default [
  {
    ignores: ['node_modules', 'public', 'vendor']
  },
  {
    files: ['**/*.vue'],
    languageOptions: {
      parser: parserVue,
      parserOptions: {
        parser: parserTs,
        ecmaVersion: 'latest',
        sourceType: 'module',
        extraFileExtensions: ['.vue']
      }
    },
    plugins: {
      vue,
      prettier
    },
    rules: {
      'vue/multi-word-component-names': 'off',
      'prettier/prettier': ['error']
    }
  },
  {
    files: ['**/*.ts'],
    languageOptions: {
      parser: parserTs,
      ecmaVersion: 'latest',
      sourceType: 'module'
    },
    plugins: {
      '@typescript-eslint': typescript,
      prettier
    },
    rules: {
      '@typescript-eslint/explicit-module-boundary-types': 'off',
      'prettier/prettier': ['error']
    }
  }
]
