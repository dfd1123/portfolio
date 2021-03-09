window.GlPcGetImage = function (res) {
  window.$EventBus.$emit('pc-get-image', res)
}

window.GlMobileGetImage = function (res) {
  window.$EventBus.$emit('mobile-get-image', res)
}
