import Compressor from 'compressorjs'

const utils = {
  formDatas (payloads = {}) {
    const headers = { 'Content-Type': 'multipart/form-data' }
    const formData = new FormData()

    Object.entries(payloads).forEach(([key, value]) => {
      if (Array.isArray(value)) {
        value.forEach(item => formData.append(`${key}[]`, item))
      } else {
        formData.append(key, value)
      }
    })

    return { formData, headers }
  },
  compressImage (file) {
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
  readAsDataURLAsync (file) {
    return new Promise((resolve, reject) => {
      const reader = new FileReader()

      reader.onload = () => {
        resolve(reader.result)
      }

      reader.onerror = reject

      reader.readAsDataURL(file)
    })
  },
  async imagefileChange (e) {
    const file = this._get(e, 'target.files[0]', null)
    if (file) {
      const dataURL = await this.readAsDataURLAsync(file)
      return { file, dataURL }
    }

    return { file: null, dataUrl: null }
  },
  baseUrl () {
    return process.env.VUE_APP_BASE_URL
  },
  storagePath (path = '') {
    return process.env.VUE_APP_BASE_URL + '/storage/' + path
  },
  getDongglePrice (taxMny) {
    const vatMny = Math.floor((Number(taxMny)) * 0.1)
    const feeMny = Math.floor((Number(taxMny) + vatMny) * ((this.company.broker_fee) / 100))
    const dongglePrice = Number(taxMny) + feeMny + vatMny

    return dongglePrice
  },
  async NotifyStore (params) {
    this.$axios.post('/api/notification', params)
  },
  GetMobileOperatingSystem () {
    var userAgent = navigator.userAgent || navigator.vendor || window.opera

    // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
      return 'Windows Phone'
    }

    if (/android/i.test(userAgent)) {
      return 'Android'
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
      return 'iOS'
    }
    return 'unknown'
  },
  NativePopup (url) {
    const params = {
      url: url
    }
    const loginOs = this.GetMobileOperatingSystem()
    if (loginOs === 'Android') {
      if (typeof window.myJs !== 'undefined') {
        window.myJs.openSafari(url)
      } else {
        window.open(url)
      }
    } else if (loginOs === 'iOS') {
      if (typeof webkit !== 'undefined') {
        window.webkit.messageHandlers.openSafari.postMessage(params)
      } else {
        window.open(url)
      }
    } else {
      window.open(url)
    }
  },
  SetToken (nativeToken = null) {
    this.nativeToken = nativeToken
  },
  NativeTokenPut () {
    try {
      const params = {
        native_token: this.nativeToken
      }

      this.$axios.put('/api/native_token', params)
    } catch (e) {
      alert(e)
    }
  }
}

export default utils
