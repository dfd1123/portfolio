import Vue from 'vue'
import Compressor from 'compressorjs'

const mixin = {
  methods: {
    compressImage (file) {
      if (!file) {
        return
      }

      return new Promise((resolve, reject) => {
        // eslint-disable-next-line no-new
        new Compressor(file, {
          quality: 0.6,
          convertSize: 500000,
          success (result) {
            resolve(result)
          },
          error (err) {
            reject(err)
          }
        })
      })
    }
  }
}

Vue.mixin(mixin)

export default mixin
