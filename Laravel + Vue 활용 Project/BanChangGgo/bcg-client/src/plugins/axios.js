'use strict'

import Vue from 'vue'
import axios from 'axios'

// Full config:  https://github.com/axios/axios#request-config
axios.defaults.headers.common.Accept = 'application/json'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const config = {
  baseURL: process.env.VUE_APP_BASE_URL || 'https://api.bahnchanggo.com',
  timeout: 15 * 1000 // Timeout
  // withCredentials: false // Check cross-site Access-Control
}

const _axios = axios.create(config)

_axios.interceptors.request.use(
  function (config) {
    // Do something before request is sent
    if (localStorage.token) {
      config.headers.Authorization = `Bearer ${localStorage.token}`
    }
    return config
  },
  function (error) {
    // Do something with request error
    return Promise.reject(error)
  }
)

// Add a response interceptor
window.isSwalUsing = false
_axios.interceptors.response.use(
  function (response) {
    // Do something with response data
    return response
  },
  async function (error) {
    // Do something with response error
    if (error.response && error.response.status === 401) {
      if (
        error.response.config.url.includes('/login') ||
        error.response.config.url.includes('/refresh')
      ) {
        return Promise.reject(error)
      }

      if (localStorage.token) {
        if (!window.isSwalUsing) {
          window.isSwalUsing = true
          await window.$EventBus.$swal({
            text: '세션이 만료되었습니다. 다시 로그인해주시기 바랍니다',
            type: 'warning',
            showConfirmButton: false,
            customClass: {
              container: 'bcg-swal__container',
              popup: 'bcg-swal__popup--btnfalse',
              content: 'bcg-swal__content',
              icon: 'bcg-swal__icon--btnfalse'
            },
            timer: 5000
          })
          window.isSwalUsing = false
        }

        localStorage.removeItem('token')
        axios.defaults.headers.common.Authorization = undefined

        window.location.replace('/login')
      }
    } else if (!error.response) {
      if (!window.isSwalUsing) {
        window.isSwalUsing = true
        await window.$EventBus.$swal({
          html: '네트워크 접속 상태가 불안정합니다.\n 잠시 후 다시 시도해주시기 바랍니다',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 5000
        })
        window.isSwalUsing = false
      }

      window.location.replace('/')
    }

    return Promise.reject(error)
  }
)

Plugin.install = function (Vue, options) {
  Vue.axios = _axios
  window.axios = _axios
  Object.defineProperties(Vue.prototype, {
    axios: {
      get () {
        return _axios
      }
    },
    $axios: {
      get () {
        return _axios
      }
    }
  })
}

Vue.use(Plugin)

export default Plugin
