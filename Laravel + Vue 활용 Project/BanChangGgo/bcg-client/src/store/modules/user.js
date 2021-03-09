import Vue from 'vue'
import get from 'lodash/get'

export default {
  state: {
    user: {}
  },
  getters: {
    isUser: (state) => Boolean(state.user.usr_no),
    user: state => state.user,
    userBatchOrder: state => get(state, 'user.usr_batch.bt_order', null),
    userBatchProgressPercent: (state, { user }) => {
      return Math.floor(user.usr_batch.pt_day / user.usr_batch.pt_term * 100) || 0
    }
  },
  mutations: {
    setUser (state, payload) {
      state.user = payload
    }
  },
  actions: {
    async getUser ({ commit }) {
      const data = await Vue.axios
        .get('/detail')
        .then(response => response.data)

      commit('setUser', data)
    },
    async updateUser ({ commit, dispatch }, payload) {
      const options = (payload instanceof FormData) ? {
        headers: { 'Content-Type': 'multipart/form-data' }
      } : {}

      await Vue.axios
        .post('/detail', payload, options)
        .then(response => { console.log(response); return response.data })

      await dispatch('getUser')
    }
  }
}
