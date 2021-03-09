'use strict'

import Vue from 'vue'
import axios from 'axios'

const token = 'PnuToken'

// Full config:  https://github.com/axios/axios#request-config
axios.defaults.headers.common.Accept = 'application/json'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const config = {
  baseURL: process.env.VUE_APP_BASE_URL,
  timeout: 15 * 1000
}

const _axios = axios.create(config)

_axios.interceptors.request.use(
  function (config) {
    // Do something before request is sent
    if (localStorage.getItem(token)) {
      config.headers.Authorization = `Bearer ${localStorage.getItem(token)}`
    }
    return config
  },
  function (error) {
    // Do something with request error
    return Promise.reject(error)
  }
)

// Add a response interceptor
window.isAnyAlert = false
_axios.interceptors.response.use(
  function (response) {
    // Do something with response data
    return response
  },
  function (error) {
    // Do something with response error
    if (error.response && error.response.status === 401) {
      if (error.response.config.url.includes('/login')) {
        return Promise.reject(error)
      }

      if (localStorage.getItem(token)) {
        if (!window.isAnyAlert) {
          window.isAnyAlert = true
          alert('인증 시간이 만료되어 로그인이 해제되었습니다. 다시 로그인해주시기 바랍니다')
          window.isAnyAlert = false
        }

        localStorage.removeItem(token)
        delete axios.defaults.headers.common.Authorization

        window.location.replace('/login')
      }
    } else if (!error.response) {
      if (!window.isAnyAlert) {
        window.isAnyAlert = true
        alert('네트워크 접속 상태가 불안정합니다.\n 잠시 후 다시 시도해주시기 바랍니다')
        window.isAnyAlert = false
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
