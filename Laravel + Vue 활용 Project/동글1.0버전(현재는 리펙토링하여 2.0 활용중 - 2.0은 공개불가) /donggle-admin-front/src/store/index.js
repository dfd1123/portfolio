import Vue from 'vue'
import Vuex from 'vuex'

import common from './modules/common'
import user from './modules/user'
import store from './modules/store'
import notification from './modules/notification'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    common,
    user,
    store,
    notification
  }
})
