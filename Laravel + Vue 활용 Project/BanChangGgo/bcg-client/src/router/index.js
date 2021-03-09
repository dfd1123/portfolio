import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index'
import Home from '../views/Home.vue'
import Login from '../views/auth/Login.vue'
import get from 'lodash/get'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: '/home'
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
    path: '/home',
    name: 'home',
    component: Home,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/register',
    name: 'register',
    // 회원가입
    component: () => import('../views/auth/Register.vue'),
    beforeEnter: (to, from, next) => {
      if (store.getters.isUser) {
        next('home')
      } else {
        next()
      }
    }
  },
  {
    path: '/findpw',
    name: 'findpw',
    // 비밀번호 찾기
    component: () => import('../views/auth/FindPw.vue')
  },
  {
    path: '/plan/question',
    name: 'PlanQuestion',
    // 플랜 - 건강사항 Question
    component: () => import('../views/plan/PlanQuestion.vue'),
    beforeEnter: (to, from, next) => {
      if (store.getters.isUser) {
        if (get(store.getters, 'user.usr_batch.qna_answered') || store.getters.userBatchOrder === null) {
          return next('/')
        }
      }

      next()
    },
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/plan/add',
    name: 'PlanAdd',
    // 플랜 - 플랜 추가
    component: () => import('../views/plan/PlanAdd.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/plan/info/:id',
    name: 'PlanInfo',
    // 플랜 - 플랜 보기
    component: () => import('../views/plan/PlanInfo.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/health/status',
    name: 'HealthStatus',
    // 건강 - 나의 건강상태
    component: () => import('../views/health/HealthStatus.vue'),
    meta: {
      requiresAuth: true,
      requiresDetail: true
    }
  },
  {
    path: '/health/report/:id',
    name: 'HealthReport',
    // 건강 - 건강리포트
    component: () => import('../views/health/HealthReport.vue'),
    meta: {
      requiresAuth: true,
      requiresScrollKeep: true
    }
  },
  {
    path: '/health/report-more',
    name: 'HealthReportMore',
    // 건강 - 건강리포트 더보기
    component: () => import('../views/health/HealthReportMore.vue'),
    meta: {
      requiresAuth: true,
      requiresScrollKeep: true
    }
  },
  {
    path: '/health/product',
    name: 'HealthReportProduct',
    // 건강 - 건강상품 구매
    component: () => import('../views/health/HealthReportProduct.vue'),
    meta: {
      requiresAuth: true,
      requiresScrollKeep: true
    }
  },
  {
    path: '/health/content',
    name: 'healthContent',
    // 건강 - 건강상품 구매
    component: () => import('../views/health/HealthContent.vue'),
    meta: {
      requiresAuth: true,
      requiresScrollKeep: true
    }
  },
  {
    path: '/mypage',
    name: 'MyPageMain',
    // 마이페이지
    component: () => import('../views/mypage/MyPageMain.vue'),
    meta: {
      requiresAuth: true
    }
  },
  /*
  {
    path: '/mypage/edit',
    name: 'MyPageEdit',
    // 마이페이지 회원정보 수정하기
    component: () => import('../views/mypage/MyPageEdit.vue'),
    meta: {
      requiresAuth: true
    }
  },
  */
  {
    path: '/mypage/notice',
    name: 'MyPageNotice',
    // 마이페이지 공지사항
    component: () => import('../views/mypage/MyPageNotice.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/mypage/secession',
    name: 'MyPageSecession',
    // 마이페이지 탈퇴하기
    component: () => import('../views/mypage/MyPageSecession.vue'),
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/mypage/terms-cs',
    name: 'MyPageCsTerms',
    // 마이페이지 서비스이용약관
    component: () => import('../views/mypage/MyPageCsTerms.vue')
  },
  {
    path: '/mypage/terms-info',
    name: 'MyPageInfoTerms',
    // 마이페이지 개인정보처리방침
    component: () => import('../views/mypage/MyPageInfoTerms.vue')
  },
  /*
  {
    path: '/temp',
    name: 'tempPage',
    component: () => import('../views/temp/Temp.vue')
  },
  */
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
      await store.dispatch('getUser')
    }

    // 참여한 차수에 대한 질문지 답변이 없으면 강제 작성
    if (get(store.getters, 'user.usr_batch.ubt_no', null)) {
      if (to.name !== 'PlanQuestion' && !get(store.getters, 'user.usr_batch.qna_answered')) {
        return next({
          name: 'PlanQuestion'
        })
      }
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
