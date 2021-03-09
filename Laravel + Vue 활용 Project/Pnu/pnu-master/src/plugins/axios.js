'use strict'

import Vue from 'vue'
import axios from 'axios'
import { store } from '../store'

// Full config:  https://github.com/axios/axios#request-config
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

let config = {
  baseURL: process.env.VUE_APP_BASE_URL,
  timeout: 15 * 1000
}
let count = 0
let timeout = null

const _axios = axios.create(config)

_axios.interceptors.request.use(
  function (config) {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
      if (count > 0) {
        store.commit('setProcessing', false)
      }
    }, 30 * 1000)

    count = count + 1
    if (count > 0) {
      store.commit('setProcessing', true)
    }

    // Do something before request is sent
    if (localStorage.token) {
      config.headers['Authorization'] = `Bearer ${localStorage.token}`
    }
    return config
  },
  function (error) {
    count = count - 1
    if (count <= 0) {
      count = 0
      store.commit('setProcessing', false)
    }
    // Do something with request error
    return Promise.reject(error)
  }
)

// Add a response interceptor
_axios.interceptors.response.use(
  function (response) {
    count = count - 1
    if (count <= 0) {
      count = 0
      store.commit('setProcessing', false)
    }

    // Do something with response data
    return response
  },
  function (error) {
    count = count - 1
    if (count <= 0) {
      count = 0
      store.commit('setProcessing', false)
    }

    // Do something with response error
    if (error.response && error.response.status === 401) {
      if (
        error.response.config.url.includes('/master/user/login') ||
        error.response.config.url.includes('/refresh')
      ) {
        return Promise.reject(error)
      }

      if (localStorage.token) {
        alert('세션이 만료되었습니다. 다시 로그인해주시기 바랍니다.')

        localStorage.removeItem('token')
        axios.defaults.headers.common['Authorization'] = undefined

        window.location.replace('/master/user/login')
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
