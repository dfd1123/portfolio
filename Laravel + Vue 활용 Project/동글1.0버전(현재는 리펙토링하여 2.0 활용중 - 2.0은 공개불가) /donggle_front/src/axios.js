import Vue from 'vue'
import router from './router'
import store from './store'
import axios from 'axios'
import VueCookies from 'vue-cookies'
Vue.use(VueCookies)

Vue.prototype.$http = axios

const HostName = process.env.VUE_APP_API_SERVER /* eslint no-undef: "off" */

Vue.prototype.$APIURI = HostName

// axios.defaults.withCredentials = true

if (Vue.$cookies.isKey('access_token')) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${Vue.$cookies.get('access_token')}`
} else {
  store.commit('UserDeleteInfor')
  store.commit('MypageInfoDelete')
}

axios.interceptors.response.use(
  function (response) {
    return response
  },
  function (error) {
    if (error.response.status === 401) {
      if (
        Vue.$cookies.isKey('access_token') ||
        error.response.config.url !== '/api/login'
      ) {
        // mixin.InfoAlert('세션이 만료 되었습니다.')
      }

      if (process.env.VUE_APP_ENV === 'LOCAL') {
        Vue.$cookies.remove('access_token')
      } else {
        Vue.$cookies.remove('access_token')
      }

      axios.defaults.headers.common['Authorization'] = undefined
      store.commit('UserDeleteInfor')
      store.commit('MypageInfoDelete')
      store.commit('ProgressHide')
      router.push('/login')
    } else if (error.code === 'ECONNABORTED') {
      // mixin.InfoAlert('시간이 초과되었습니다.')

      if (process.env.VUE_APP_ENV === 'LOCAL') {
        Vue.$cookies.remove('access_token')
      } else {
        Vue.$cookies.remove('access_token')
      }

      axios.defaults.headers.common['Authorization'] = undefined
      store.commit('UserDeleteInfor')
      store.commit('MypageInfoDelete')
      store.commit('ProgressHide')
      router.push('/')
    }

    return Promise.reject(error)
  }
)

// 주기적으로 액세스 토큰 갱신
const refreshInterval = 3 * 60 * 60 * 1000
const Refresh = async () => {
  try {
    if (Vue.$cookies.isKey('access_token')) {
      // axios.defaults.headers.common['Authorization'] = `Bearer ${Vue.$cookies.get('access_token')}`
      const user = (await axios.get(HostName + 'users/user_info')).data.query
      if (user) {
        store.commit('UserStoreInfor', user)
      }
      const response = (await axios.get(HostName + 'refresh')).data
      const token = response.access_token

      if (process.env.VUE_APP_ENV === 'LOCAL') {
        Vue.$cookies.remove('access_token')
      } else {
        Vue.$cookies.remove('access_token')
      }

      if (process.env.VUE_APP_ENV === 'LOCAL') {
        Vue.$cookies.set('access_token', token)
      } else {
        Vue.$cookies.set('access_token', token)
      }

      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }
  } finally {
    setTimeout(() => Refresh(), refreshInterval)
  }
}
setTimeout(() => Refresh(), 5000)

axios.get(HostName + 'category/main_list').then(res => {
  const response = res.data
  if (response.state !== 1) {
    if (response.state === 0) {
      store.commit('CategorySet', [])
    }
    console.log(response.msg)
  } else {
    store.commit('CategorySet', response.query)
  }
}).catch(e => {
  console.log(e)
})

axios.get(HostName + 'company/info').then(res => {
  const response = res.data
  if (response.state !== 1) {
    console.log(response.msg)
  } else {
    store.commit('CompanySet', response.query)
  }
}).catch(e => {
  console.log(e)
})
