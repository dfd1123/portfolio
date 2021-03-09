import Vue from 'vue'
import './plugins/axios'
import './lib/componentLoader'
import './lib/javascriptInterface.js'
import './lib/native.js'
import moment from 'moment'
import VueMomentJS from 'vue-momentjs'
import App from './App.vue'
import router from './router'
import store from './store'
import './mixin'
import infiniteScroll from 'vue-infinite-scroll'
import VueCookies from 'vue-cookies'

window.$EventBus = new Vue()

Vue.use({
  install (V) {
    const bus = new Vue()
    V.prototype.$bus = bus
    V.bus = bus
  }
})

moment.locale('ko')
window.$moment = moment

Vue.use(VueMomentJS, moment)
Vue.use(infiniteScroll)
Vue.use(VueCookies)
Vue.config.productionTip = false;

(async () => {
  // 새로고침 시 토큰이 있으면 계정정보 업데이트
  if (Vue.$cookies.get('DonggleAcessToken')) {
    store.dispatch('getUser')
    store.dispatch('getStore')
  }

  new Vue({
    router,
    store,
    render: h => h(App)
  }).$mount('#app')
})()
