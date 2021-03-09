import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'

const routerPush = VueRouter.prototype.push
VueRouter.prototype.push = function push (location) {
  return routerPush.call(this, location).catch(error => error)
}

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: '/home'
  },
  {
    path: '/home',
    name: 'home',
    component: Home,
    meta: {
      requiresAuth: true
      // requiresDetail: true
      // requiresScrollKeep: true
    }
  },
  {
    path: '/login',
    name: 'login',
    // 로그인
    component: Login,
    beforeEnter: (to, from, next) => {
      if (store.getters.isUser) {
        next('home')
      } else {
        next()
      }
    }
  },
  {
    path: '/competency-start',
    name: 'competency-start',
    component: () => import('../views/CompetencyStart.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/competency-intro',
    name: 'competency-intro',
    component: () => import('../views/CompetencyIntro.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/competency-qna',
    name: 'competency-qna',
    component: () => import('../views/CompetencyQna.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/finish',
    name: 'finish',
    component: () => import('../views/Finish.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/report',
    name: 'report',
    component: () => import('../views/Report.vue'),
    meta: {
      requiresAuth: true
    },
    beforeEnter: (to, from, next) => {
      const user = store.getters.user
      if (user.available && (user.cpt_order < user.max_cpt_order)) {
        alert('진행중인 모든 평가를 완료해야 합니다')
        next('home')
      } else {
        next()
      }
    }
  },
  {
    path: '/policy/privacy',
    name: 'privacy',
    component: () => import('../views/Privacy.vue'),
    meta: {
    }
  },
  {
    path: '*',
    redirect: '/'
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach(async (to, from, next) => {
  // 사용자 인증 정보 체크
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // 로그인된 사용자가 아니면 로그인 화면으로 이동
    if (!store.getters.isUser) {
      return next({
        name: 'login'
      })
    }

    // 사용자 상세정보 최신화가 필요한 화면
    if (to.matched.some(record => record.meta.requiresDetail)) {
      await store.dispatch('userDetail')
    }

    return next()
  }

  next()
})

router.afterEach(to => {
  if (to.matched.some(record => record.meta.requiresScrollKeep)) {
    return
  }

  setTimeout(() => {
    window.scrollTo(0, 0)
  })
})

// Vue Router에서 동적 로딩 실패 시 새로고침
router.onError(error => {
  if (/Loading chunk .* failed./i.test(error.message)) {
    window.location.reload()
    return
  }

  throw error
})

export default router
