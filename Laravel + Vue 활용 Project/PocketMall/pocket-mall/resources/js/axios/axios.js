import Vue from "vue";
import axios from "axios";
import store from "../store";

Vue.prototype.$http = axios;

// axios 헤더 추가
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.baseURL = 'https://mall.포켓컴퍼니.com/api/'

axios.get('category').then(res => {
  const response = res.data

  if (response.state !== 1) {
    if (response.state === 0) {
      store.commit('CategorySet', [])
    }
    console.log(response.msg)
  } else {
    store.commit('CategorySet', response.query.categorys)
  }

}).catch(e => {
  console.log(e)
})
