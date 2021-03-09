// import Vue from 'vue'
// import mixin from '@/mixin'

export default {
  state: {
    info: {
      progressShow: false,
      fixedTop: false
    },
    company: {}
  },
  getters: {
    progressShow: state => state.info.progressShow,
    fixedTop: state => state.info.fixedTop,
    company: state => state.company
  },
  mutations: {
    progressShow (state) {
      state.info.progressShow = true
    },
    progressHide (state) {
      state.info.progressShow = false
    },
    loading (state, payload) {
      state.info.progressShow = Boolean(payload)
    },
    setFixedTop (state, payload) {
      state.info.fixedTop = Boolean(payload)
    },
    CompanySet (state, company) {
      state.company = company
    }
  },
  actions: {
  }
}
