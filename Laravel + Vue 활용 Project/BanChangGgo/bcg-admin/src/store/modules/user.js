import Vue from 'vue'

export default {
  state: {
    currentUser: null,
    loginError: null,
    processing: false
  },
  getters: {
    currentUser: state => state.currentUser,
    processing: state => state.processing,
    loginError: state => state.loginError
  },
  mutations: {
    setUser (state, payload) {
      state.currentUser = payload
      state.processing = false
      state.loginError = null
    },
    setLogout (state) {
      state.currentUser = null
      state.processing = false
      state.loginError = null
    },
    setProcessing (state, payload) {
      state.processing = payload
      state.loginError = null
    },
    setError (state, payload) {
      state.loginError = payload
      state.currentUser = null
      state.processing = false
    },
    clearError (state) {
      state.loginError = null
    }
  },
  actions: {
    async login ({ commit, dispatch }, payload) {
      try {
        commit('clearError')
        commit('setProcessing', true)

        localStorage.token = await Vue.axios
          .post('/login', {
            adm_email: payload.email,
            adm_pwd: payload.password
          })
          .then(response => response.data.access_token)

        const user = await dispatch('detail')
        commit('setUser', user)
      } catch (err) {
        const { status, data } = err.response
        if (status === 401) {
          commit('setError', data)
        }

        console.log(err)
      }
    },
    async detail ({ commit }, payload) {
      try {
        const user = await Vue.axios
          .get('/detail')
          .then(response => response.data)

        return user.adm_no ? user : null
      } catch (err) {
        const { status, data } = err.response
        if (status === 401) {
          commit('setError', data.message)
        }

        console.log(err)
      }
    },
    signOut ({ commit }) {
      localStorage.removeItem('token')
      setTimeout(() => {
        commit('setUser', null)
      }, 250)
    }
  }
}
