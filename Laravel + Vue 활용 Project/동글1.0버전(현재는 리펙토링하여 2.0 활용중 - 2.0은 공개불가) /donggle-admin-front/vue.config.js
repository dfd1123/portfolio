module.exports = {
  css: {
    loaderOptions: {
      sass: {
        prependData: `
          @import "@/assets/scss/abstracts/_variables.scss";
          @import "@/assets/scss/abstracts/_functions.scss";
          @import "@/assets/scss/abstracts/_mixin.scss";
        `
      }
    },
    sourceMap: true
  },
  productionSourceMap: false,
  chainWebpack: config => {
    config.module
      .rule('vue')
      .use('vue-loader')
      .loader('vue-loader')
      .tap(options => {
        options.compilerOptions.whitespace = 'preserve'
        return options
      })
  }
}
