module.exports = {
  css: {
    loaderOptions: {
      sass: {
        prependData: `
                    @import "@/styles/scss/util/_variables.scss";
                    @import "@/styles/scss/util/_functions.scss";
                    @import "@/styles/scss/util/_mixin.scss";
                `
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
  productionSourceMap: false
}
