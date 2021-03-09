
import Vue from 'vue'
import './plugins/axios'
import './lib/componentLoader'
import './lib/javascriptInterface.js'
import App from './App.vue'
import router from './router'
import store from './store'
import './mixin'
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'
import './styles/scss/components/_bcg_swal.scss'
import moment from 'moment'
import 'moment/locale/ko'
import VueMomentJS from 'vue-momentjs'
import TimePicker from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import lang from 'element-ui/lib/locale/lang/ko'
import locale from 'element-ui/lib/locale'
locale.use(lang)
moment.locale('ko')

const options = {
  confirmButtonColor: '#FAA13C',
  cancelButtonColor: '#ffffff',
  customClass: {
    container: 'bcg-swal__container',
    popup: 'bcg-swal__popup',
    header: 'bcg-swal__header',
    title: 'bcg-swal__title',
    closeButton: 'bcg-swal__close-button',
    icon: 'bcg-swal__icon',
    image: 'bcg-swal__image',
    content: 'bcg-swal__content',
    input: 'bcg-swal__input',
    actions: 'bcg-swal__actions',
    confirmButton: 'bcg-swal__confirm-button',
    cancelButton: 'bcg-swal__cancel-button',
    footer: 'bcg-swal__footer'
  }
}
Vue.use(VueSweetalert2, options)
Vue.use(VueMomentJS, moment)
Vue.use(TimePicker)
Vue.config.productionTip = false

window.$EventBus = new Vue();

// 유저정보 로드
(async () => {
  if (localStorage.token) {
    await store.dispatch('getUser')
  }

  new Vue({
    router,
    store,
    render: h => h(App),
    beforeCreate () {
      window.$EventBus.$on('push-token-request', () => {
        window.CallPushToken()
      })
      window.$EventBus.$on('push-token-result', ({ token }) => {
        store.dispatch('updateUser', {
          usr_extra: JSON.stringify({ ...store.getters.user.usr_extra, ...{ push_token: token } })
        })
      })
      window.$EventBus.$on('set-alarm-request', ({ id, title, body, hour, min }) => {
        const userid = store.getters.user.usr_no
        if (userid) {
          window.SetAlarmNotify(userid, id, title, body, hour, min)
        }
      })
      window.$EventBus.$on('reset-alarm-request', ({ id }) => {
        const userid = store.getters.user.usr_no
        if (userid) {
          window.ResetAlarmNotify(userid, id)
        }
      })
    },
    beforeDestroy () {
      window.$EventBus.$off('push-token-request')
      window.$EventBus.$off('push-token-result')
      window.$EventBus.$off('set-alarm-request')
      window.$EventBus.$off('reset-alarm-request')
    }
  }).$mount('#app')
})()
