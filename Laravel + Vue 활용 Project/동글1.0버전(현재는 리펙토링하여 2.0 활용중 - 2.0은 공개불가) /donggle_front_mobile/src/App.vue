<template>
  <div id="app">
    <div
      v-show="$store.state.progressShow"
      :class="['progress_wrap', {'active':$store.state.progressShow}]"
    >
      <div class="progress_con">
        <Loading />
      </div>
    </div>
    <vue-page-transition name="fade-in-right">
      <template v-if="this.$store.state.routerAlive">
        <router-view />
      </template>
    </vue-page-transition>
    <!--<Footer v-if="mainHeader" />-->
    <Nav v-if="nav" />
  </div>
</template>
<script>
  // import Footer from '@/components/common/footer/footer.vue'
  import Nav from '@/components/common/nav/nav.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      // Footer,
      Nav,
      Loading
    },
    data: function () {
      return {
        mainHeader: true,
        nav: true,
        transitionName: '',
        lastTouchEnd: 0
      }
    },
    created () {
      document.documentElement.addEventListener('touchstart', function (event) {
        if (event.touches.length > 1) {
          event.preventDefault()
        }
      }, false)

      this.HeaderSetting()
      this.NavSetting()
    },
    updated () {
      this.HeaderSetting()
      this.NavSetting()
    },
    methods: {
      HeaderSetting () {
        let exist = false
        const subHdUrls = [
          'register',
          'find',
          '/total/search',
          '/product/list',
          'product/view',
          'cart',
          '/order',
          '/mypage/cancel/history',
          '/mypage/review',
          '/mypage/like/list',
          '/mypage/mycoupon',
          '/mypage/couponzone',
          '/mypage/coupon/history',
          '/mypage/info',
          '/mypage/secession',
          '/mypage/level_guide',
          '/mypage/payment/info',
          '/review/list',
          '/review/view',
          '/cancel/request',
          '/delivery/change',
          '/store',
          '/notices',
          '/qnas',
          '/faqs',
          '/events',
          '/subscribe',
          '/item_qna/',
          '/privacy',
          '/term'
        ]
        subHdUrls.forEach(url => {
          if (this.$route.fullPath.includes(url)) {
            exist = true
          }
        })

        if (exist) {
          this.mainHeader = false
        } else {
          this.mainHeader = true
        }
      },
      NavSetting () {
        let exist = false
        const exceptUrl = [
          'product/view',
          '/privacy',
          '/term',
          '/register'
        ]
        exceptUrl.forEach(url => {
          if (this.$route.fullPath.includes(url)) {
            exist = true
          }
        })

        if (exist) {
          this.nav = false
        } else {
          this.nav = true
        }
      }
    }
  }
</script>

<style lang="scss">
  html {
    width: 100%;
  }
  #app {
    overflow-x: hidden;
  }
</style>
