module.exports = {
  css: {
    loaderOptions: {
      sass: {
      }
    },
    sourceMap: true
  },
  pluginOptions: {
    moment: {
      locales: [
        'kr'
      ]
    }
  },
  productionSourceMap: false,
  publicPath: '/client'
}
