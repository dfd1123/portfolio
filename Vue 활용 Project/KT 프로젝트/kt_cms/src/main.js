import './polyfills' /* 항상 최상위에 선언 */
import Vue from 'vue'
import moment from 'moment-timezone'
import VueMomentJs from 'vue-momentjs'
import App from './App.vue'
import axios from './axios'
import router from './router'
import store from './store'
import './mixin'

/* EventBus */
window.$EventBus = new Vue()

/* API 호스트 */
const APIGW = '116.122.157.151/api/v1'
Vue.prototype.$BASEURL = `http://${APIGW}`

/* config */
Vue.prototype.$http = axios
Vue.prototype.$EventBus = window.$EventBus
moment.suppressDeprecationWarnings = true
Vue.use(VueMomentJs, moment)

Vue.config.productionTip = false

/* 메모 */
// 시간포멧: yyyy-MM-dd'T'HH:mm:ss.SSS
// timezone은 +09:00(Asia/Seoul)으로 서버에 세팅

async function start () {
  try {
    store.commit('restoreUser')

    if (store.getters.isLogin) {
      // console.log('Request Token Refresh...')
      // await store.dispatch('refreshUser') // 확인바람...
    }
  } catch (e) {
    console.log(e)
  }

  window.$VueRoot = new Vue({
    router,
    store,
    render: h => h(App)
  }).$mount('#app')
}
start()
