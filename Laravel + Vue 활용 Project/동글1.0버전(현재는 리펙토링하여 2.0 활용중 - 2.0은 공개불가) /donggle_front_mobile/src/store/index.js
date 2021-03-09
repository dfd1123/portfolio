import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from '../router'
import createPersistedState from 'vuex-persistedstate'
// import * as Cookies from 'js-cookie'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: {
      id: '',
      email: '',
      name: '',
      profile_img: '',
      nickname: '',
      mobile_number: '',
      level: ''
    },
    mypageInfo: {
      couponCount: 0,
      orderComplete: 0,
      orderCancel: 0
    },
    delivery: [],
    categorys: [],
    categoryPopup: false,
    company: {},
    selectItems: [],
    beforeUrl: '',
    selectOption: [],
    totalPrice: 0,
    offset: null,
    position: 0,
    progressShow: false,
    routerAlive: true
  },
  plugins: [createPersistedState()],
  mutations: {
    UserStoreInfor (state, user) {
      state.user = user
    },
    UserDeleteInfor (state) {
      state.user = {
        id: '',
        email: '',
        name: '',
        profile_img: '',
        nickname: '',
        mobile_number: '',
        level: ''
      }
    },
    DeliverySet (state, delivery) {
      state.delivery = delivery
    },
    DeliveryDelete (state) {
      state.delivery = {}
    },
    MypageInfoSet (state, mypageInfo) {
      state.mypageInfo = mypageInfo
    },
    MypageInfoDelete (state) {
      state.mypageInfo = {
        counponCnt: 0,
        orderComplete: 0,
        refundCnt: 0
      }
    },
    CategorySet (state, categorys) {
      state.categorys = categorys
    },
    CompanySet (state, company) {
      state.company = company
    },
    CategoryPopup (state, status) {
      state.categoryPopup = status
    },
    SelectItemsStore (state, selectItems) { // 카트에서 주문페이지로 넘어갈때 선택한 상품을 주문페이지로 넘기기 위해 사용됨
      state.selectItems = selectItems
    },
    SelectItemsDelete (state) { // 카트에서 주문페이지로 넘어갈때 선택한 상품을 주문페이지로 넘기고 리셋하기 위해 사용됨
      state.selectItems = []
    },
    SelectOptionStore (state, selectOption) { // 주문페이지에서 두개의 컴포넌트가 같은 동작을 해야하기 때문에 복잡도를 낮추기 위해 사용됨
      state.selectOption = selectOption
    },
    SelectOptionQtyChange (state, index, value, plmi, qty) { // 주문페이지에서 두개의 컴포넌트가 같은 동작을 해야하기 때문에 복잡도를 낮추기 위해 사용됨
      if (plmi === '+') {
        state.selectOption[index].qty = qty + Number(value)
      } else if (plmi === '-') {
        state.selectOption[index].qty = qty - Number(value)
      } else {
        state.selectOption[index].qty = Number(value)
      }
    },
    SelectOptionDelete (state, index, selectOption) { // 주문페이지에서 두개의 컴포넌트가 같은 동작을 해야하기 때문에 복잡도를 낮추기 위해 사용됨
      state.selectOption = selectOption.splice(index, 1)
    },
    SelectOptionReset (state) { // 주문페이지에서 두개의 컴포넌트가 같은 동작을 해야하기 때문에 복잡도를 낮추기 위해 사용됨
      state.selectOption = []
    },
    TotalPriceChange (state, totalPrice) { // 주문페이지에서 두개의 컴포넌트가 같은 동작을 해야하기 때문에 복잡도를 낮추기 위해 사용됨
      state.totalPrice = totalPrice
    },
    TotalPriceReset (state) { // 주문페이지에서 두개의 컴포넌트가 같은 동작을 해야하기 때문에 복잡도를 낮추기 위해 사용됨
      state.totalPrice = 0
    },
    MainHeaderShow (state) {
      state.mainHeader = true
    },
    MainHeaderHide (state) {
      state.mainHeader = false
    },
    ViewOffsetSet (state, offset) {
      state.offset = offset
    },
    ViewOffsetReset (state) {
      state.offset = null
    },
    SavePosition (state, position) {
      state.position = position
    },
    ResetPosition (state) {
      state.position = 0
    },
    ProgressShow (state) {
      state.progressShow = true
    },
    ProgressHide (state) {
      state.progressShow = false
    },
    RouterAlive (state, payload) {
      state.routerAlive = payload
    }
  },
  actions: {
    async Login ({ commit, state }, params) {
      commit('ProgressShow')
      /* 로그인은 백엔드를 다녀와야 하냐 비동기 처리를 한다 */
      try {
        // console.log(this.$http)
        const res = (await axios.post(process.env.VUE_APP_API_SERVER + 'login', params)).data

        if (res.user) {
          const token = res.access_token
          const user = res.user

          axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
          commit('UserStoreInfor', user)

          if (process.env.VUE_APP_ENV === 'LOCAL') {
            Vue.$cookies.set('access_token', token)
          } else {
            Vue.$cookies.set('access_token', token)
          }

          const res2 = (await axios.get(process.env.VUE_APP_API_SERVER + 'delivery/list')).data

          if (res2.state === 1) {
            commit('DeliverySet', res2.query)
          } else {
            console.log(res2.msg)
          }

          if (state.beforUrl === '/find') {
            router.push('/')
          } else {
            router.push(state.beforUrl)
          }
        } else {
          router.push('/login')
        }
      } catch (e) {
        console.log(e)
        alert('로그인에 실패하였습니다.')
        router.push('/login')
      } finally {
        commit('ProgressHide')
      }
    },
    async Logout ({ commit, state }) {
      commit('ProgressShow')
      try {
        const res = (await axios.get(process.env.VUE_APP_API_SERVER + 'logout')).data
        console.log(res.msg)
        if (process.env.VUE_APP_ENV === 'LOCAL') {
          Vue.$cookies.remove('access_token')
        } else {
          Vue.$cookies.remove('access_token')
        }

        axios.defaults.headers.common['Authorization'] = undefined
        commit('UserDeleteInfor')
        commit('MypageInfoDelete')

        router.push('/login')
      } catch (e) {
        console.log(e)
        this.ErrorAlert('로그아웃에 실패하였습니다.\n관리자에게 문의하세요.')
      } finally {
        commit('ProgressHide')
      }
    },
    async DeliveryLoad ({ commit, state }) {
      try {
        const res = (await axios.get(process.env.VUE_APP_API_SERVER + 'delivery/list')).data

        if (res.state === 1) {
          this.commit('DeliverySet', res.query)
        } else {
          console.log(res.msg)
        }
      } catch (e) {
        console.log(e)
      }
    },
    Refresh ({ commit, state }) {
      commit('RouterAlive', false)
      setTimeout(() => {
        commit('RouterAlive', true)
      })
    }
  },
  modules: {
  }
})
