import { isDemo } from '../constants/config'
import { store } from '../store'

export default async (to, from, next) => {
  if (isDemo) {
    return next()
  }

  if (localStorage.token) {
    try {
      const user = await store.dispatch('detail')
      store.commit('setUser', user)

      return next()
    } catch (err) {
      console.log(err)
    }
  }

  return next('/master/user/login')
}
