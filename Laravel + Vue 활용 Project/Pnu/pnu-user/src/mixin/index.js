import Vue from 'vue'
import { mapGetters, mapActions } from 'vuex'
import Compressor from 'compressorjs'
import { get as _get } from 'lodash-es'

const mixin = {
  computed: {
    ...mapGetters([
      'isUser',
      'user'
    ])
  },
  methods: {
    ...{
      _get
    },
    ...mapActions(['userDetail']),
    __ (key, replace = {}) {
      window.__ = window.__ || {}

      let translation = key
        .split('.')
        .reduce((t, i) => t[i] || null, window.__)

      for (var placeholder in replace) {
        translation = translation.replace(
          `:${placeholder}`,
          replace[placeholder]
        )
      }

      return translation
    },
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
    },
    first (responseData, defaultValue = null) {
      if (responseData) {
        if (Array.isArray(responseData)) {
          if (responseData.length > 0) {
            return responseData[0]
          } else {
            return defaultValue
          }
        } else {
          return responseData
        }
      }

      return defaultValue
    }
  }
}

Vue.mixin(mixin)

export default mixin
