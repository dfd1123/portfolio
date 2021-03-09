module.exports = {
  transpileDependencies: ['strip-ansi', 'ansi-regex', 'vue-daum-postcode'],
  css: {
    loaderOptions: {
      sass: {
        prependData: `
          @import "@/assets/scss/ui.scss";
        `
      }
    }
  },
  chainWebpack: config => {
    config.module.rules.delete('eslint');
  }
}
