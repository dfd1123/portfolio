module.exports = {
  css: {
    loaderOptions: {
      sass: {
        prependData: `
          @import "@/assets/scss/mobile_ui.scss";
        `
      }
    }
  }
}
