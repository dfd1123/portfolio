import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import qs from 'qs'

Vue.use(Vuex)

function initialState () {
  return {
    isProgressComponentVisible: false,
    editSideOpen: false,
    createSideOpen: false,
    user: {}
  }
}

const clientUsername = 'com-ui-client'
const clientPassword = 'com-ui-client!@#'

export default new Vuex.Store({
  state: initialState(),
  getters: {
    isEditSideOpen: state => state.editSideOpen,
    isLogin: state => Boolean(state.user.user_uuid),
    user: state => state.user,
    auth: state => state.user.aut_type_cd
    /* auth
      플랫폼관리자 '01'
      고객관리자 '02'
      서비스관리자 '03'
      일반사용자 '04'
    */
  },
  mutations: {
    progressComponentShow (state) {
      state.isProgressComponentVisible = true
    },
    progressComponentHide (state) {
      state.isProgressComponentVisible = false
    },
    EditSideComponentOpen (state) {
      state.editSideOpen = true
    },
    CreateSideComponentOpen (state) {
      state.createSideOpen = true
    },
    EditSideComponentClose (state) {
      state.editSideOpen = false
    },
    CreateSideComponentClose (state) {
      state.createSideOpen = false
    },
    setUser (state, payload) {
      state.user = payload
    },
    setSvcId (state, payload) {
      state.user.svc_id = payload
    },
    restoreUser (state) {
      if (localStorage.CmsUserState) {
        const userState = JSON.parse(localStorage.CmsUserState)

        localStorage.CmsAccessToken = userState.access_token
        localStorage.CmsRefreshToken = userState.refresh_token
        state.user = userState
      }
    }
  },
  actions: {
    async loginUser ({ commit, state }, payload) {
      const res = await axios
        .post(Vue.prototype.$BASEURL + '/auth',
          qs.stringify({
            grant_type: 'password',
            username: payload.username,
            password: payload.password,
            scope: payload.scope || ''
          }),
          {
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Accept': '*/*',
              'Cache-Control': 'no-cache'
            },
            auth: {
              username: clientUsername,
              password: clientPassword
            }
          })
        .then(res => res.data)

      if (['03', '04'].includes(res.aut_type_cd)) {
        res.svc_id_list = res.svc_id

        if (res.svc_id.length === 1) {
          res.svc_id = res.svc_id[0]
        }

        let list = []
        for (const index in res.svc_id_list) {
          const res1 = await axios
            .get(Vue.prototype.$BASEURL + '/user/service/info', {
              params: {
                userUuid: res.user_uuid
              },
              headers: {
                'Authorization': `Bearer ${res.access_token}`,
                'X-Svc-Id': res.svc_id_list[index]
              }
            })
            .then(res => res.data.data)
            .then(res => res.length > 0 ? res[0] : null)
            .catch(null)

          if (res1) {
            res1.svcId = res.svc_id_list[index]
            list.push(res1)
          }
        }

        const moments = list.map(x => Vue.prototype.$moment(x.recntLoginDt))
        const max = Vue.prototype.$moment.max(moments)
        res.svc_id = list.find(x => max._i === x.recntLoginDt).svcId
      } else {
        res.svc_id = null
        res.svc_id_list = []
      }

      localStorage.CmsUserState = JSON.stringify(res)

      commit('restoreUser')
    },
    async refreshUser ({ commit, state }) {
      const res = await axios
        .post(Vue.prototype.$BASEURL + '/auth',
          qs.stringify({
            grant_type: 'refresh_token',
            refresh_token: localStorage.CmsRefreshToken
          }),
          {
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'Accept': '*/*',
              'Cache-Control': 'no-cache'
            },
            auth: {
              username: clientUsername,
              password: clientPassword
            }
          })
        .then(res => res.data)
      
      const userState = {
        ...state.user,
        ...res
      }

      localStorage.CmsAccessToken = res.access_token
      localStorage.CmsRefreshToken = res.refresh_token
      localStorage.CmsUserState = JSON.stringify(userState)

      commit('setUser', userState)
    },
    async logoutUser ({ commit }) {
      localStorage.removeItem('CmsAccessToken')
      localStorage.removeItem('CmsRefreshToken')
      localStorage.removeItem('CmsUserState')

      commit('setUser', {})
    }
  }
})
