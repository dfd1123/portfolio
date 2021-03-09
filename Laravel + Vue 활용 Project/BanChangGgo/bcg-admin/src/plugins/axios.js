'use strict'

import Vue from 'vue'
import axios from 'axios'

// Full config:  https://github.com/axios/axios#request-config
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.baseURL = process.env.VUE_APP_BASE_URL || 'https://api.bahnchanggo.com/admin'
axios.defaults.timeout = 15 * 1000

const _axios = axios

_axios.interceptors.request.use(
  function (config) {
    // Do something before request is sent
    if (localStorage.token) {
      config.headers['Authorization'] = `Bearer ${localStorage.token}`
    }
    return config
  },
  function (error) {
    // Do something with request error
    return Promise.reject(error)
  }
)

// Add a response interceptor
_axios.interceptors.response.use(
  function (response) {
    // Do something with response data
    return response
  },
  function (error) {
    // Do something with response error
    if (error.response && error.response.status === 401) {
      if (
        error.response.config.url.includes('/user/login') ||
        error.response.config.url.includes('/refresh')
      ) {
        return Promise.reject(error)
      }

      if (localStorage.token) {
        alert('세션이 만료되었습니다. 다시 로그인해주시기 바랍니다.')

        localStorage.removeItem('token')
        axios.defaults.headers.common['Authorization'] = undefined

        window.location.replace('/user/login')
      }
    } else if (error.code === 'ECONNABORTED') {
      alert('네트워크 접속 상태가 불안정합니다. 잠시 후 다시 시도해주시기 바랍니다')

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
