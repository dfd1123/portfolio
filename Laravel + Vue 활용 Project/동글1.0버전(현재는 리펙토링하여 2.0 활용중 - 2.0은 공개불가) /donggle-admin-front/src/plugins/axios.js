'use strict'

import Vue from 'vue'
import axios from 'axios'
import store from '../store'
import VueCookies from 'vue-cookies'

Vue.use(VueCookies)

// Full config:  https://github.com/axios/axios#request-config
axios.defaults.baseURL = process.env.VUE_APP_BASE_URL || ''
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const config = {
  // timeout: 60 * 1000, // Timeout
  // withCredentials: true, // Check cross-site Access-Control
}

const _axios = axios.create(config)

_axios.interceptors.request.use(
  function (config) {
    // Do something before request is sent
    if (Vue.$cookies.get('DonggleAcessToken')) {
      config.headers.Authorization = `Bearer ${Vue.$cookies.get('DonggleAcessToken')}`
    }
    return config
  },
  function (error) {
    // Do something with request error
    if (error.response && error.response.status === 401) {
      alert('세션이 만료되었습니다. 다시 로그인해주시기 바랍니다.')
    }

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
    if (error.response && error.response.status === 401) {
      if (Vue.$cookies.get('DonggleAcessToken') && !error.response.config.url.includes('/login')) {
        if (process.env.VUE_APP_ENV === 'LOCAL') {
          Vue.$cookies.remove('DonggleAcessToken')
        } else {
          Vue.$cookies.remove('DonggleAcessToken', '/', 'store.dong-gle.co.kr')
        }

        window.location.replace('/login')
      }
    } else if (error.code === 'ECONNABORTED') {
      alert('네트워크 접속 상태가 불안정합니다.')
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

_axios.get('/api/company/info').then(res => {
  const response = res.data
  if (response.state !== 1) {
    console.log(response.msg)
  } else {
    store.commit('CompanySet', response.query)
  }
}).catch(e => {
  console.log(e)
})

Vue.use(Plugin)

export default Plugin
