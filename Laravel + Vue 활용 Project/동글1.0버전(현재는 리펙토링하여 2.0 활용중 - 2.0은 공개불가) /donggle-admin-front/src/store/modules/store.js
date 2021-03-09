import Vue from 'vue'

export default {
  state: {
    info: {}
  },
  getters: {
    isStore: state => Boolean(state.info.store_id),
    isConfirm: state => state.info.confirm === 1,
    store: state => state.info
  },
  mutations: {
    setStore (state, payload) {
      state.info = payload
    }
  },
  actions: {
    async getStore ({ commit }, payload) {
      const data = await Vue.axios
        .get('/api/store/store/view', { params: {} })
        .then(response => response.data)

      commit('setStore', data.query || {})
    }
  }
}
