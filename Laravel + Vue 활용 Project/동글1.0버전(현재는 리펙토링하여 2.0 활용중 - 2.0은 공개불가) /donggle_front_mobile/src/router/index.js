import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'
import axios from 'axios'
import moment from 'moment'

import Login from '../views/auth/login.vue'
import RegisterKind from '../views/auth/register-kind.vue'
import Register from '../views/auth/register.vue'
import RegisterStyle from '../views/auth/register-style.vue'
import FindChoice from '../views/auth/find-choice.vue'
import FindId from '../views/auth/find-id.vue'
import FindIdComplete from '../views/auth/find-id-complete.vue'
import FindPw from '../views/auth/find-pw.vue'
import FindPwComplete from '../views/auth/find-pw-complete.vue'
import OauthLogin from '../components/common/oauth/oauth.vue'
import SocialRegister from '../views/auth/social-register.vue'
import Home from '../views/home/Home.vue'
import BestProductList from '../views/gnb/best-product-list.vue'
import WeeklyNewProductList from '../views/gnb/weeklyNew-product-list.vue'
import SelfProductList from '../views/gnb/self-product-list.vue'
import TotalSearch from '../views/total-search/total-search.vue'
import ProductList from '../views/product-list/product-list.vue'
import ProductView from '../views/product-view/product-view.vue'
import OrderProcess from '../views/order/order-process.vue'
import OrderPaying from '../views/order/order-paying.vue'
import OrderComplete from '../views/order/order-complete.vue'
import OrderDetail from '../views/order/order-detail.vue'
import Cart from '../views/cart/cart.vue'
import MypageMain from '../views/mypage/main/main.vue'
import MypageOrderHistory from '../views/mypage/order/order-history.vue'
import MypageCancelHistory from '../views/mypage/order/cancel-history.vue'
import MypageReviewWrite from '../views/mypage/review/review-write.vue'
import MypageReviewList from '../views/mypage/review/review-list.vue'
import MypageLikeList from '../views/mypage/like/like-list.vue'
import MypageRecentList from '../views/mypage/recent/recent-list.vue'
import MypageStoreLike from '../views/mypage/store/like-store.vue'
import MypageMyCoupon from '../views/mypage/coupon/my-coupon.vue'
import MypageCouponZone from '../views/mypage/coupon/coupon-zone.vue'
import MypageCouponUseHistory from '../views/mypage/coupon/coupon-history.vue'
import MypageMyInfo from '../views/mypage/my-info/info-edit.vue'
import MypagePaymentInfo from '../views/mypage/my-info/payment-edit.vue'
import MypageSecession from '../views/mypage/my-info/secession.vue'
import MypageLevelGuide from '../views/mypage/my-info/user-level-guide.vue'
import MypageCompanyInfo from '../views/mypage/company-info/company-info.vue'
import ReviewList from '../views/review/review-list.vue'
import ReviewView from '../views/review/review-view.vue'
import ItemQnaList from '../views/item_qna/list.vue'
import ItemQnaView from '../views/item_qna/view.vue'
import OrderCancelRequest from '../views/order-cancel/cancel-request.vue'
import OrderRefundRequest from '../views/order-cancel/refund-request.vue'
import DeliveryChange from '../views/order/delivery-change.vue'
import StoreHome from '../views/store/store-home.vue'
import StoreQna from '../views/store/store-qna.vue'
import StoreQnaView from '../views/store/store-qna-view.vue'
import NoticeList from '../views/cs/notice/notice-list.vue'
import NoticeView from '../views/cs/notice/notice-view.vue'
import FaqList from '../views/cs/faq/faq-list.vue'
import QnaList from '../views/cs/qna/qna-list.vue'
import EventList from '../views/cs/event/event-list.vue'
import EventView from '../views/cs/event/event-view.vue'
import Subscribe from '../views/subscribe/subscribe.vue'
import Privacy from '../views/snb/privacy.vue'
import Term from '../views/snb/term.vue'

Vue.use(VueRouter)

window.mobilecheck = function () {
  const UserAgent = navigator.userAgent
  if (UserAgent.match(/iPhone|iPod|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i) != null || UserAgent.match(/LG|SAMSUNG|Samsung/) != null) {
    return true
  } else {
    return false
  }
}

const routerPush = VueRouter.prototype.push
VueRouter.prototype.push = function push (location) {
  return routerPush.call(this, location).catch(error => error)
}

const routerReplace = VueRouter.prototype.replace
VueRouter.prototype.replace = function replace (location) {
  return routerReplace.call(this, location).catch(error => error)
}

let routes = []

routes = [
  {
    path: '/auth/sync',
    name: 'auth-sync',
    component: {
      template: '<div></div>'
    },
    meta: { authRequired: false }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { authRequired: false }
  },
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { authRequired: false }
  },
  {
    path: '/register/kind',
    name: 'register-kind',
    component: RegisterKind,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        next()
      }
    }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        if (from.path !== '/register/kind') {
          // next('/register/kind')
        }
        next()
      }
    }
  },
  {
    path: '/register/style',
    name: 'register-style',
    component: RegisterStyle,
    meta: { authRequired: false }
  },
  {
    path: '/find',
    name: 'find-choice',
    component: FindChoice,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        next()
      }
    }
  },
  {
    path: '/find/id',
    name: 'find-id',
    component: FindId,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        if (from.path !== '/find') {
          next('/find')
        }
        next()
      }
    }
  },
  {
    path: '/find/id/complete',
    name: 'find-id-complete',
    component: FindIdComplete,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        if (from.path !== '/find/id') {
          next('/find')
        }
        next()
      }
    }
  },
  {
    path: '/find/pw',
    name: 'find-pw',
    component: FindPw,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        if (from.path !== '/find') {
          next('/find')
        }
        next()
      }
    }
  },
  {
    path: '/find/pw/complete',
    name: 'find-pw-complete',
    component: FindPwComplete,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        if (from.path !== '/find/pw') {
          next('/find')
        }
        next()
      }
    }
  },
  {
    path: '/oauth/login',
    name: 'oauth-login',
    component: OauthLogin,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        next()
      }
    }
  },
  {
    path: '/oauth/register',
    name: 'oauth-register',
    component: SocialRegister,
    meta: { authRequired: false },
    beforeEnter: (to, from, next) => {
      if (Vue.$cookies.isKey('access_token')) {
        next(store.state.beforUrl)
      } else {
        next()
      }
    }
  },
  {
    path: '/product/list/best',
    name: 'best-product-list',
    component: BestProductList,
    meta: { authRequired: false }
  },
  {
    path: '/product/list/weeklyNew',
    name: 'weeklyNew-product-list',
    component: WeeklyNewProductList,
    meta: { authRequired: false }
  },
  {
    path: '/product/list/self',
    name: 'self-product-list',
    component: SelfProductList,
    meta: { authRequired: false }
  },
  {
    path: '/total/search',
    name: 'total-search',
    component: TotalSearch,
    meta: { authRequired: false }
  },
  {
    path: '/product/list/:caId',
    name: 'product-list',
    props: true,
    component: ProductList,
    meta: { authRequired: false }
  },
  {
    path: '/product/view/:id',
    name: 'product-view',
    props: true,
    component: ProductView,
    meta: { authRequired: false }
  },
  {
    path: '/order',
    name: 'order-process',
    component: OrderProcess,
    meta: { authRequired: true }
  },
  {
    path: '/order/paying',
    name: 'order-paying',
    component: OrderPaying,
    meta: { authRequired: true }
  },
  {
    path: '/order/complete',
    name: 'order-complete',
    component: OrderComplete,
    meta: { authRequired: true }
  },
  {
    path: '/order/detail/:orderId',
    name: 'order-detail',
    props: true,
    component: OrderDetail,
    meta: { authRequired: true }
  },
  {
    path: '/cart',
    name: 'cart',
    component: Cart,
    meta: { authRequired: true }
  },
  {
    path: '/mypage',
    name: 'mypage-main',
    component: MypageMain,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/order/history',
    name: 'mypage-order-history',
    component: MypageOrderHistory,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/cancel/history',
    name: 'mypage-cancel-history',
    component: MypageCancelHistory,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/review/write',
    name: 'mypage-review-write',
    component: MypageReviewWrite,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/review/list',
    name: 'mypage-review-list',
    component: MypageReviewList,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/like/list',
    name: 'mypage-like-list',
    component: MypageLikeList,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/recent/list',
    name: 'mypage-recent-list',
    component: MypageRecentList,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/store/like',
    name: 'mypage-store-like',
    component: MypageStoreLike,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/mycoupon',
    name: 'mypage-mycoupon',
    component: MypageMyCoupon,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/couponzone',
    name: 'mypage-couponzone',
    component: MypageCouponZone,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/coupon/history',
    name: 'mypage-use-history',
    component: MypageCouponUseHistory,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/info',
    name: 'mypage-info',
    component: MypageMyInfo,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/payment/info',
    name: 'mypage-payment-info',
    component: MypagePaymentInfo,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/secession',
    name: 'mypage-secession',
    component: MypageSecession,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/level_guide',
    name: 'level-guide',
    component: MypageLevelGuide,
    meta: { authRequired: true }
  },
  {
    path: '/mypage/company_info',
    name: 'company-info',
    component: MypageCompanyInfo,
    meta: { authRequired: true }
  },
  {
    path: '/review/list/:itemId',
    name: 'review-list',
    props: true,
    component: ReviewList,
    meta: { authRequired: false }
  },
  {
    path: '/review/:itemId/view/:reviewId',
    name: 'review-view',
    props: true,
    component: ReviewView,
    meta: { authRequired: false }
  },
  {
    path: '/item_qna/list/:itemId',
    name: 'item-qna-list',
    props: true,
    component: ItemQnaList,
    meta: { authRequired: false }
  },
  {
    path: '/item_qna/view/:itemId/:qnaId',
    name: 'item-qna-view',
    props: true,
    component: ItemQnaView,
    meta: { authRequired: false }
  },
  {
    path: '/cancel/request',
    name: 'order-cancel-request',
    component: OrderCancelRequest,
    meta: { authRequired: true }
  },
  {
    path: '/refund/request',
    name: 'order-refund-request',
    component: OrderRefundRequest,
    meta: { authRequired: true }
  },
  {
    path: '/delivery/change/:orderId',
    name: 'delivery-change',
    props: true,
    component: DeliveryChange,
    meta: { authRequired: false }
  },
  {
    path: '/store/:storeId',
    name: 'store-home',
    props: true,
    component: StoreHome,
    meta: { authRequired: false }
  },
  {
    path: '/store/:storeId/qna',
    name: 'store-qna',
    props: true,
    component: StoreQna,
    meta: { authRequired: true }
  },
  {
    path: '/store/:storeId/qna/:qnaId',
    name: 'store-qna-view',
    props: true,
    component: StoreQnaView,
    meta: { authRequired: true }
  },
  {
    path: '/notices',
    name: 'notice-list',
    component: NoticeList,
    meta: { authRequired: false }
  },
  {
    path: '/notices/:id',
    name: 'notice-view',
    props: true,
    component: NoticeView,
    meta: { authRequired: false }
  },
  {
    path: '/faqs',
    name: 'faq-list',
    component: FaqList,
    meta: { authRequired: false }
  },
  {
    path: '/qnas',
    name: 'qna-list',
    component: QnaList,
    meta: { authRequired: true }
  },
  {
    path: '/events',
    name: 'event-list',
    component: EventList,
    meta: { authRequired: false }
  },
  {
    path: '/events/:id',
    name: 'event-view',
    props: true,
    component: EventView,
    meta: { authRequired: false }
  },
  {
    path: '/subscribe',
    name: 'subscribe',
    component: Subscribe,
    meta: { authRequired: true }
  },
  {
    path: '/privacy',
    name: 'privacy',
    component: Privacy,
    meta: { authRequired: false }
  },
  {
    path: '/term',
    name: 'term',
    component: Term,
    meta: { authRequired: false }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
  scrollBehavior: (to, from, savedPosition) => {
    if (savedPosition) {
      return savedPosition
    } else if (to.hash) {
      return {
        selector: to.hash
      }
    } else {
      return { x: 0, y: 0 }
    }
  }
})

router.beforeEach(async function (to, from, next) {
  document.body.style.overflowY = 'auto'
  store.commit('CategoryPopup', false)
  store.state.beforUrl = from.path
  if (to.matched.some(function (routeInfo) {
    // console.log(routeInfo.meta.authRequired)
    return routeInfo.meta.authRequired
  })) {
    // ????????? ???????????? ?????? ????????? ???????????? ?????? ?????? ????????? ????????? ????????? ?????? ??????
    if (Vue.$cookies.isKey('access_token')) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${Vue.$cookies.get('access_token')}`
      if (!store.state.user.name) {
        const user = (await axios.get(process.env.VUE_APP_API_SERVER + 'users/user_info')).data.query
        if (user) {
          await store.commit('UserStoreInfor', user)
        }
      }

      if (to.path !== '/subscribe' && to.path !== '/mypage') {
        if (!store.state.user.regular_end) {
          router.push('/subscribe')
        } else {
          if (moment().isAfter(store.state.user.regular_end)) {
            router.push('/subscribe')
          } else {
            next()
          }
        }
      } else {
        next()
      }
    } else {
      alert('?????????????????? ?????????????????????.')
      store.commit('UserDeleteInfor')
      store.commit('MypageInfoDelete')
      router.push('/login')
    }
  } else {
    next() // ????????? ??????
  };
})

export default router
