const CopyWebpackPlugin = require('copy-webpack-plugin')

module.exports = {
  transpileDependencies: ['strip-ansi', 'ansi-regex', 'vue-runtime-helpers', 'v-contextmenu'],
  pages: {
    index: {
      entry: 'src/index.js',
      template: 'public/index.html',
      filename: 'index.html'
    }
  },
  devServer: {
    clientLogLevel: 'warning',
    hot: true,
    contentBase: 'dist',
    compress: true,
    overlay: { warnings: false, errors: true },
    publicPath: '/master',
    quiet: true,
    watchOptions: {
      poll: false,
      ignored: /node_modules/
    }
  },
  chainWebpack: config => {
    config.module
      .rule('vue')
      .use('vue-loader')
      .loader('vue-loader')
      .tap(options => {
        options.compilerOptions.preserveWhitespace = true
        return options
      })
  },
  productionSourceMap: false,
  assetsDir: './assets/',
  configureWebpack: {
    plugins: [
      new CopyWebpackPlugin([
        { from: 'src/assets/img', to: 'assets/img' },
        { from: 'src/assets/fonts', to: 'assets/fonts' }
      ])
    ]
  },
  publicPath: '/master'
}
