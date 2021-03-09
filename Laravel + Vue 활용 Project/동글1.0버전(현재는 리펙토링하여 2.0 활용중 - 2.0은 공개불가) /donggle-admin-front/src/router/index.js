import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'
import mixin from '@/mixin'

import AdminMain from '../views/AdminMain.vue'
import Login from '../views/auth/Login.vue'
import Register from '../views/auth/Register.vue'
import OauthRegister from '../views/auth/OauthRegister.vue'
import OauthLogin from '../components/oauth/oauth.vue'
import Privacy from '../views/snb/privacy.vue'
import Term from '../views/snb/term.vue'

/* NavigationDuplicated 에러 무시 처리 */
const routerPush = VueRouter.prototype.push
VueRouter.prototype.push = function push (location) {
  return routerPush.call(this, location).catch(error => error)
}

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: '/main'
  },
  {
    path: '/main',
    name: 'main',
    component: AdminMain,
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.get('DonggleAcessToken')) {
        next('/main')
      } else {
        next()
      }
    }
  },
  {
    path: '/oauth/register',
    name: 'oauth-register',
    component: OauthRegister,
    meta: {
      requiresAuth: false,
      requiresConfirm: false
    }
  },
  { // FIXME: 20-04-02 회원가입 페이지 추가
    path: '/register',
    name: 'register',
    component: Register
  },
  { // FIXME: 20-04-02 회원가입 페이지 추가
    path: '/register/complete',
    name: 'register-complete',
    component: () => import('../views/auth/RegisterComplete.vue')
  },
  {
    path: '/oauth/login',
    name: 'oauth-login',
    component: OauthLogin,
    meta: {
      requiresAuth: false,
      requiresConfirm: false
    }
  },
  {
    path: '/find-choice',
    name: 'find-choice',
    component: () => import('../views/auth/FindChoice.vue')
  },
  {
    path: '/find-id',
    name: 'find-id',
    component: () => import('../views/auth/FindId.vue')
  },
  {
    path: '/find-id/complete',
    name: 'find-id-complete',
    component: () => import('../views/auth/FindIdComplete.vue')
  },
  {
    path: '/find-pw',
    name: 'find-pw',
    component: () => import('../views/auth/FindPw.vue')
  },
  {
    path: '/find-pw/complete',
    name: 'find-pw-complete',
    component: () => import('../views/auth/FindPwComplete.vue')
  },
  {
    path: '/setting-store',
    name: 'setting-store',
    component: () => import('../views/SettingStore.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: false
    }
  },
  {
    path: '/check-order',
    name: 'check-order',
    component: () => import('../views/CheckOrder.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/history-order-list',
    name: 'history-order-list',
    component: () => import('../views/HistoryOrderList.vue'),
    meta: {
      requiresAuth: true,
      requiresQueryRefresh: true,
      requiresConfirm: true
    }
  },
  {
    path: '/history-order-detail/:id',
    name: 'history-order-detail',
    component: () => import('../views/HistoryOrderDetail.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/history-cancel-list',
    name: 'history-cancel-list',
    component: () => import('../views/HistoryCancelList.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/history-cancel-detail/:id',
    name: 'history-cancel-detail',
    component: () => import('../views/HistoryCancelDetail.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/manage-product-list',
    name: 'manage-product-list',
    component: () => import('../views/ManageProductList.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/manage-product-enroll/:id?',
    name: 'manage-product-enroll',
    component: () => import('../views/ManageProductEnroll.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/inquiry-product-list',
    name: 'inquiry-product-list',
    component: () => import('../views/InquiryProductList.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/inquiry-product-detail/:id?',
    name: 'inquiry-product-detail',
    component: () => import('../views/InquiryProductDetail.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/manage-review',
    name: 'manage-review',
    component: () => import('../views/ManageReview.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  { // 20-03-31 구매후기 상세보기 화면
    path: '/manage-review-detail/:reviewId',
    name: 'manage-review-detail',
    props: true,
    component: () => import('../views/ManageReviewDetail.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/sales-status',
    name: 'sales-status',
    component: () => import('../views/SalesStatus.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/alarm-view',
    name: 'alarm-view',
    component: () => import('../views/AlarmView.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/inquiry-store-list',
    name: 'inquiry-store-list',
    component: () => import('../views/InquiryStoreList.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/inquiry-store-detail/:id?',
    name: 'inquiry-store-detail',
    component: () => import('../views/InquiryStoreDetail.vue'),
    meta: {
      requiresAuth: true,
      requiresConfirm: true
    }
  },
  {
    path: '/cs',
    redirect: '/cs-notice-main'
  },
  {
    path: '/cs-notice-main',
    name: 'cs-notice-main',
    component: () => import('../views/CsNoticeMain.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/cs-notice-detail/:id',
    name: 'cs-notice-detail',
    component: () => import('../views/CsNoticeDetail.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/cs-faq',
    name: 'cs-faq',
    component: () => import('../views/CsFaq.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/cs-support',
    name: 'cs-support',
    component: () => import('../views/CsSupport.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/privacy',
    name: 'privacy',
    component: Privacy,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/term',
    name: 'term',
    component: Term,
    meta: {
      requiresAuth: false
    }
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
    if (!Vue.$cookies.get('DonggleAcessToken')) {
      return next({
        name: 'login'
      })
    }

    if (!from.name && to.name !== 'main') {
      store.dispatch('getNotification')
    }

    if (!from.name && to.name !== 'setting-store') {
      store.dispatch('getStore')
    }

    if (to.matched.some(record => record.meta.requiresQueryRefresh)) {
      if (from.name === null && !mixin.methods._isEmpty(to.query)) {
        return next({
          name: to.name,
          query: {}
        })
      }
    }

    if (to.matched.some(record => record.meta.requiresConfirm)) {
      const confirm = await Vue.$cookies.get('isConfirm')
      if (confirm === 'false') {
        return next({
          name: 'setting-store'
        })
      }
    }
  }

  return next()
})

router.afterEach(to => {
  if (to.matched.some(record => record.meta.requiresScrollKeep)) {
    return
  }

  setTimeout(() => {
    window.scrollTo(0, 0)
  })
})

router.onError(error => {
  // Vue Router에서 동적 로딩 실패 시 새로고침
  if (/Loading chunk .* failed./i.test(error.message)) {
    window.location.reload()
    return
  }

  throw error
})

export default router
