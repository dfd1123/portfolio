
import Vue from 'vue'
import './plugins/axios'
import './lib/componentLoader'
import './lib/javascriptInterface.js'
import App from './App.vue'
import '../src/styles/scss/layout/responsive.scss'
import router from './router'
import store from './store'
import './mixin'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
import moment from 'moment'
import 'moment/locale/ko'
import VueMomentJS from 'vue-momentjs'
import TimePicker from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import lang from 'element-ui/lib/locale/lang/ko'
import locale from 'element-ui/lib/locale'
locale.use(lang)
moment.locale('ko')

Vue.use(VueSweetalert2)
Vue.use(VueMomentJS, moment)
Vue.use(TimePicker)
Vue.config.productionTip = false

window.$EventBus = new Vue();

// 유저정보 로드
(async () => {
  const token = 'PnuToken'
  if (localStorage.getItem(token)) {
    await store.dispatch('userDetail')
  }

  new Vue({
    router,
    store,
    render: h => h(App)
  }).$mount('#app')
})()
