import Vue from 'vue'
import mixin from '@/mixin'

export default {
  state: {
    info: {}
  },
  getters: {
    isNewNotification: state => {
      return mixin.methods._get(state, 'info.notification', []).some(x => x.not_read_datetime === null)
    },
    notificationList: state => mixin.methods._get(state, 'info.notification', []),
    notificationLength: state => mixin.methods._get(state, 'info.count', 0)
  },
  mutations: {
    setNotification (state, payload) {
      state.info = payload
    }
  },
  actions: {
    async getNotification ({ state, commit, dispatch }, payload) {
      const data = await Vue.axios
        .get('/api/notification/list')
        .then(response => response.data)

      commit('setNotification', data.query)
    },
    async readNotification ({ state, commit, dispatch }, payload) {
      const formData = new FormData()
      formData.append('_method', 'put')
      formData.append('not_id', payload.not_id)

      await Vue.axios
        .post('/api/notification/read', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
        .then(response => response.data)
    }
  }
}
