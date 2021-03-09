import Vue from 'vue'

const token = 'PnuToken'

export default {
  state: {
    user: {}
  },
  getters: {
    isUser: (state) => Boolean(state.user.user_id),
    user: state => state.user
  },
  mutations: {
    setUser (state, payload) {
      state.user = payload
    }
  },
  actions: {
    async userLogin ({ commit, dispatch }, { userNo, password }) {
      const data = await Vue.axios
        .post('/login', {
          user_no: userNo,
          password
        })
        .then(response => response.data)

      localStorage.setItem(token, data.access_token)

      await this.dispatch('userDetail')
    },
    async userDetail ({ commit }) {
      const data = await Vue.axios
        .get('/detail')
        .then(response => response.data)

      commit('setUser', data)
    },
    async userLogout ({ commit }) {
      localStorage.removeItem(token)

      commit('setUser', {})
    }
  }
}
