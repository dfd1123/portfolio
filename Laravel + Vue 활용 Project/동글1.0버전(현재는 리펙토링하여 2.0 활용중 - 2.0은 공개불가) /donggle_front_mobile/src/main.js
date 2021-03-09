import './polyfills' /* 항상 최상위에 선언 */
import Vue from 'vue'
import './lib/native.js'
import App from './App.vue'
import store from './store'
import router from './router'
import jquery from 'jquery'
import vueDebounce from 'vue-debounce'
import JwPagination from 'jw-vue-pagination'
import moment from 'moment'
import VueMomentJS from 'vue-momentjs'
import VueSweetalert2 from 'vue-sweetalert2'
import VueScrollTo from 'vue-scrollto'
import VueCookies from 'vue-cookies'
import VueDaumPostcode from 'vue-daum-postcode'
import gallery from 'img-vuer'
import VueClipboards from 'vue-clipboards'
import Ripple from 'vue-ripple-directive'
import VuePageTransition from 'vue-page-transition'
import 'sweetalert2/dist/sweetalert2.min.css'
import './assets/scss/components/_dg-swal.scss'
import './mixin'
import './axios'
// import './auth'

Ripple.color = 'rgba(0, 0, 0, 0.1)'

Vue.directive('ripple', Ripple)

const options = {
  confirmButtonColor: '#A134E8',
  cancelButtonColor: '#ffffff',
  confirmButtonText: '확인',
  cancelButtonText: '닫기',
  showCancelButton: true,
  reverseButtons: true,
  customClass: {
    container: 'dg-sal_container',
    popup: 'dg-sal_popup',
    header: 'dg-sal_header',
    title: 'dg-sal_title',
    icon: 'dg-sal_icon',
    image: 'dg-sal_image',
    content: 'dg-sal_content',
    input: 'dg-sal_input',
    actions: 'dg-sal_actions',
    closeButton: 'dg-sal_close_button',
    confirmButton: 'dg-sal_confirm_button',
    cancelButton: 'dg-sal_cancel_button',
    footer: 'dg-sal_footer'
  }
}

Vue.use(VueSweetalert2, options)

Vue.use(VueMomentJS, moment)

/* vueDebounce Setting */
Vue.use(vueDebounce)

Vue.use(vueDebounce, {
  listenTo: ['input', 'keyup']
})

/* vueDebounce Setting E */

Vue.use(VueScrollTo)

Vue.use(VueCookies)

Vue.use(VueDaumPostcode)

Vue.use(gallery)

Vue.use(VueClipboards)

Vue.use(VuePageTransition)

Vue.component('jw-pagination', JwPagination)

window.$EventBus = new Vue()

Vue.prototype.$ = jquery

Vue.config.productionTip = false

window.mobilecheck = function () {
  const UserAgent = navigator.userAgent
  if (UserAgent.match(/iPhone|iPod|iPad|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i) != null || UserAgent.match(/LG|SAMSUNG|Samsung/) != null) {
    return true
  } else {
    return false
  }
}

function GetPara () {
  const para = document.location.href.split('?'); console.log(para)

  return para
}

if (window.mobilecheck()) {
  new Vue({
    store,
    router,
    render: h => h(App)
  }).$mount('#app')
} else {
  let param = GetPara()

  if (process.env.VUE_APP_ENV === 'LOCAL') {
    window.location.href = process.env.VUE_APP_LOCAL_PC_URI + window.location.pathname + (param[1] ? '?' + param[1] : '')
  } else {
    window.location.href = process.env.VUE_APP_PRODC_PC_URI + window.location.pathname + (param[1] ? '?' + param[1] : '')
  }
}
