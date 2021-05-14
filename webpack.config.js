const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
  ...defaultConfig,
  entry: {
    'plugin-settings': './assets/src/js/plugin-settings/index.js',
    'post-settings': './assets/src/js/post-settings/index.js'
  },
  output: {
    filename: '[name].js',
    path: path.resolve(__dirname, 'assets/dist')
  },
  externals: {
    "react": "React",
    "react-dom": "ReactDOM"
  }
}