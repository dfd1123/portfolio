import Vue from 'vue'

export default {
  state: {
    info: {}
  },
  getters: {
    isUser: state => Boolean(state.info.id),
    user: state => state.info
  },
  mutations: {
    setUser (state, payload) {
      state.info = payload
    }
  },
  actions: {
    async loginUser ({ commit }, payload) {
      let data = await Vue.axios
        .post('/api/login', {
          email: payload.email,
          password: payload.password
        })
        .then(response => response.data)

      if (process.env.VUE_APP_ENV === 'LOCAL') {
        Vue.$cookies.set('DonggleAcessToken', data.access_token)
      } else {
        Vue.$cookies.set('DonggleAcessToken', data.access_token)
      }

      let user = data.user

      if (user.register_kind === 1) {
        data = await Vue.axios
          .put('/api/users/store_join_req')
          .then(response => response.data)

        if (data.state === 1) {
          user = data.query
        }
      }

      commit('setUser', user)

      const store = await Vue.axios
        .get('/api/store/store/view', { params: {} })
        .then(response => response.data)

      commit('setStore', store.query || {})

      await Vue.$cookies.set('isConfirm', store.query.confirm === 1)
    },
    async getUser ({ commit }) {
      const data = await Vue.axios
        .get('/api/users/user_info')
        .then(response => response.data)

      if (data.state === 1) {
        commit('setUser', data.query)
      } else {
        throw new Error()
      }
    },
    async updateUser ({ commit, dispatch }, payload) {
      const options = (payload instanceof FormData) ? {
        headers: { 'Content-Type': 'multipart/form-data' }
      } : {}

      await Vue.axios
        .post('/detail', payload, options)
        .then(response => response.data)

      await dispatch('getUser')
    },
    async logoutUser ({ commit }) {
      if (process.env.VUE_APP_ENV === 'LOCAL') {
        Vue.$cookies.remove('DonggleAcessToken')
      } else {
        Vue.$cookies.remove('DonggleAcessToken')
      }

      commit('setUser', {})

      Vue.$cookies.remove('isConfirm')
    }
  }
}
