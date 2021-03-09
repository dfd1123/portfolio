import 'babel-polyfill'

import Vue from 'vue'
import './plugins/axios'
import App from './App'

// BootstrapVue add
import BootstrapVue from 'bootstrap-vue'
// Router & Store add
import router from './router'
import { store } from './store'
// Multi Language Add
import ko from './locales/ko.json'
import en from './locales/en.json'
import VueI18n from 'vue-i18n'
import { defaultLocale, localeOptions } from './constants/config'
// Notification Component Add
import Notifications from './components/Common/Notification'
// Breadcrumb Component Add
import Breadcrumb from './components/Common/Breadcrumb'
// RefreshButton Component Add
import RefreshButton from './components/Common/RefreshButton'
// Colxx Component Add
import Colxx from './components/Common/Colxx'
// Perfect Scrollbar Add
import vuePerfectScrollbar from 'vue-perfect-scrollbar'
import contentmenu from 'v-contextmenu'
import lineClamp from 'vue-line-clamp'
import VCalendar from 'v-calendar'
import 'v-calendar/lib/v-calendar.min.css'
import VueScrollTo from 'vue-scrollto'
import moment from 'moment'
import VueMomentJS from 'vue-momentjs'

Vue.use(BootstrapVue)
Vue.use(VueI18n)
Vue.use(VueMomentJS, moment)

const messages = { ko: ko, en: en }
const locale = (localStorage.getItem('currentLanguage') && localeOptions.filter(x => x.id === localStorage.getItem('currentLanguage')).length > 0) ? localStorage.getItem('currentLanguage') : defaultLocale
const i18n = new VueI18n({
  locale: locale,
  fallbackLocale: 'en',
  messages
})

Vue.use(Notifications)
Vue.component('piaf-breadcrumb', Breadcrumb)
Vue.component('b-refresh-button', RefreshButton)
Vue.component('b-colxx', Colxx)
Vue.component('vue-perfect-scrollbar', vuePerfectScrollbar)
Vue.use(require('vue-shortkey'))
Vue.use(contentmenu)
Vue.use(lineClamp, { /* plugin options */ })
Vue.use(VCalendar, {
  firstDayOfWeek: 2, // ...other defaults,
  formats: {
    title: 'MMM YY',
    weekdays: 'WW',
    navMonths: 'MMMM',
    input: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD'],
    dayPopover: 'L'
  },
  datePickerShowDayPopover: false,
  popoverExpanded: true,
  popoverDirection: 'bottom'
})
Vue.use(VueScrollTo)

export default new Vue({
  el: '#app',
  i18n,
  router,
  store,
  render: h => h(App)
})
