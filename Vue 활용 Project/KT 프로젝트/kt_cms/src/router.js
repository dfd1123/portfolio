import Vue from 'vue'
import Router from 'vue-router'
import store from './store'

/* 공통 */
import Login from './views/Login.vue'
import Select from './views/Select.vue'
import AccountInfo from './views/AccountInfo.vue'

/* 서비스 */
import UnitServiceList from './views/UnitServiceList.vue'
import CustomerList from './views/CustomerList.vue'
import ServiceList from './views/ServiceList.vue'
import ExtensionList from './views/ExtensionList.vue'
import UseraccList from './views/UseraccList.vue'
import UserserviceList from './views/UserserviceList.vue'

/* 단말 */
import DeviceList from './views/DeviceList.vue'
import AppList from './views/AppList.vue'

/* AR영역 */
import ZoneList from './views/ZoneList.vue'

/* AR대상 */
import FacilityList from './views/FacilityList.vue'
import ArdataList from './views/ArdataList.vue'
import FacilityManagerList from './views/FacilityManagerList.vue'
import ViewList from './views/ViewList.vue'

/* AR업무 */
// 설비메모
import WorkList from './views/WorkList.vue'
import VideoCallList from './views/VideoCallList.vue'

/* 통계 */
import StatisticsList from './views/StatisticsList.vue'

/* 공통관리 */
import NoticeList from './views/NoticeList.vue'
import QnaList from './views/QnaList.vue'
// 코드
// 메뉴

const routerPush = Router.prototype.push
Router.prototype.push = function push (location) {
  return routerPush.call(this, location).catch(error => error)
}

Vue.use(Router)

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '/',
      redirect: '/arzone'
    },
    {
      path: '/login',
      name: 'login',
      component: Login,
      beforeEnter: (to, from, next) => {
        if (store.getters.isLogin) {
          next('/arzone')
        } else {
          next()
        }
      }
    },
    {
      path: '/select',
      name: 'select',
      component: Select,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['03', '04']
      }
    },
    {
      path: '/accountinfo',
      name: 'accountinfo',
      component: AccountInfo,
      meta: {
        requiresAuth: true
      }
    },
    /* 서비스 */
    {
      path: '/service',
      redirect: '/service/unit'
    },
    {
      path: '/service/unit',
      name: 'unit',
      component: UnitServiceList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ServiceManagement'
      },
      beforeEnter: (to, from, next) => {
        if (store.getters.auth === '03') {
          next('/service/useracc')
        } else {
          next()
        }
      }
    },
    {
      path: '/service/customer',
      name: 'customer',
      component: CustomerList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02'],
        page_category: 'ServiceManagement'
      }
    },
    {
      path: '/service/service',
      name: 'service',
      component: ServiceList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02'],
        page_category: 'ServiceManagement'
      }
    },
    {
      path: '/service/extention',
      name: 'extention',
      component: ExtensionList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ServiceManagement'
      }
    },
    {
      path: '/service/useracc',
      name: 'useracc',
      component: UseraccList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ServiceManagement'
      }
    },
    {
      path: '/service/userservice',
      name: 'userservice',
      component: UserserviceList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ServiceManagement'
      }
    },
    /* 단말 */
    {
      path: '/device',
      redirect: '/device/device'
    },
    {
      path: '/device/device',
      name: 'device',
      component: DeviceList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02'],
        page_category: 'DeviceManagement'
      }
    },
    {
      path: '/device/app',
      name: 'app',
      component: AppList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02'],
        page_category: 'DeviceManagement'
      }
    },
    /* AR영역 */
    {
      path: '/arzone',
      redirect: '/arzone/zone'
    },
    {
      path: '/arzone/zone',
      name: 'zone',
      component: ZoneList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ArZoneManagement'
      }
    },
    /* AR대상 */
    {
      path: '/arobject',
      redirect: '/arobject/facility'
    },
    {
      path: '/arobject/facility',
      name: 'facility',
      component: FacilityList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ArObjectManagement'
      }
    },
    {
      path: '/arobject/ardata',
      name: 'ardata',
      component: ArdataList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ArObjectManagement'
      }
    },
    {
      path: '/arobject/facility_manager',
      name: 'facility_manager',
      component: FacilityManagerList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ArObjectManagement'
      }
    },
    {
      path: '/arobject/view',
      name: 'view',
      component: ViewList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'ArObjectManagement'
      }
    },
    /* ar업무 */
    {
      path: '/artask',
      redirect: '/artask/work'
    },
    {
      path: '/artask/work',
      name: 'work',
      component: WorkList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03', '04'],
        page_category: 'ArTaskManagement'
      }
    },
    {
      path: '/artask/videocall',
      name: 'videocall',
      component: VideoCallList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03', '04'],
        page_category: 'ArTaskManagement'
      }
    },
    /* 통계 */
    {
      path: '/statistics',
      name: 'statistics',
      component: StatisticsList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03', '04'],
        page_category: 'ServiceStatistics'
      }
    },
    /* 공통관리 */
    {
      path: '/common',
      redirect: '/common/notice',
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03', '04'],
        page_category: 'CommonManagement'
      }
    },
    {
      path: '/common/notice',
      name: 'notice',
      component: NoticeList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03', '04'],
        page_category: 'CommonManagement'
      }
    },
    {
      path: '/common/qna',
      name: 'qna',
      component: QnaList,
      meta: {
        requiresAuth: true,
        requiresAdmin: ['01', '02', '03'],
        page_category: 'CommonManagement'
      }
    }
  ]
})

router.beforeEach(async (to, from, next) => {
  // 사용자 인증 정보 체크

  if (to.matched.some(record => record.meta.requiresAuth)) {
    // 로그인된 사용자가 아니면 로그인 화면으로 이동
    if (!store.getters.isLogin) {
      return next({
        name: 'login'
      })
    }
  }

  if (to.matched.some(record => record.meta.requiresAdmin)) {
    // 권한 체크
    if (Array.isArray(to.meta.requiresAdmin) && !to.meta.requiresAdmin.includes(store.getters.auth)) {
      alert('접근 가능한 권한이 없습니다')
      return next(false)
    }
  }

  return next()
})

router.afterEach(to => {
  store.commit('CreateSideComponentClose')
  store.commit('EditSideComponentClose')

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
