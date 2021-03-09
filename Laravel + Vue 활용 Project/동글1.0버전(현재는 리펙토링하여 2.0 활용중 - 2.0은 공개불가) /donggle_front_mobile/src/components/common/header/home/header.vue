<template>
  <!-- header -->
  <header id="dg-hd">
    <!-- main top popup -->
    <TopBanner />
    <!-- main top popup E -->

    <div class="dg-hd-con1_wrap">
      <h1
        class="dg-hd-logo"
        @click="ForcePush('/')"
      >
        donggle
      </h1>
    </div>

    <!-- search -->
    <div class="dg-black_bar_search_wrap">
      <label class="dg_blind">검색</label>
      <input
        class="dg-black_bar-search"
        type="search"
        v-model="searchKeyword"
        @keyup.enter="Submit()"
        placeholder="검색어 미입력시 필터로 연결됩니다."
      >
      <div class="dg-black_bar-search-bar"></div>
      <button
        class="dg-black_bar-search_btn"
        @click="Submit()"
      ></button>
    </div>
    <!-- search E -->

    <!-- main gnb 스크롤시 탑에 고정됨(.scroll) -->
    <div
      id="gnb_con"
      class="dg-main_category_wrap"
    >
      <div :class="['dg-main_category', {'posFixed': posFixed}]">
        <div class="dg-main_category_link">
          <router-link
            to="/"
            class="dg-main_category_a"
          >
            홈
          </router-link>
          <router-link
            to="/product/list/best"
            class="dg-main_category_a"
          >
            인기상품(BEST)
          </router-link>
          <router-link
            to="/product/list/weeklyNew"
            class="dg-main_category_a"
          >
            주간신상(Weekly new)
          </router-link>
          <router-link
            to="/product/list/self"
            class="dg-main_category_a"
          >
            국내자체제작
          </router-link>
        </div>
      </div>
    </div>
    <!-- main gnb E -->
  </header>
  <!-- header E -->
</template>

<script>
  import TopBanner from '@/components/banner/top-banner.vue'
  export default {
    components: {
      TopBanner
    },
    data: function () {
      return {
        mainPopup: false,
        topTen: [],
        nextTen: [],
        searchKeyword: '',
        posFixed: false
      }
    },
    created () {
      window.addEventListener('scroll', this.GnbScroll)
      if (!this.CheckPopupCookie('mainTopBannerClose')) {
        this.mainPopup = true
      }
    },
    destroyed () {
      window.removeEventListener('scroll', this.GnbScroll)
    },
    methods: {
      async SearchKeywordStore () {
        const params = {
          pp_word: this.$route.query.searchKeyword
        }

        try {
          this.$http.post(this.$APIURI + 'popular', params)
        } catch (e) {
          console.log(e)
        }
      },
      Submit () {
        if (this.searchPopup) {
          this.searchPopup = !this.searchPopup
        }
        this.SearchKeywordStore()
        this.$router.push({
          name: 'total-search',
          query: { searchKeyword: this.searchKeyword }
        })
      },
      GnbScroll () {
        if (window.pageYOffset > document.getElementById('gnb_con').offsetTop) {
          this.posFixed = true
        } else {
          this.posFixed = false
        }
      },
      CheckPopupCookie (cookieName) {
        const cookie = document.cookie

        if (cookie.length > 0) {
          // 현재 쿠키가 존재할 경우
          // 자식창에서 set해준 쿠키명이 존재하는지 검색

          const startIndex = cookie.indexOf(cookieName)

          if (startIndex !== -1) {
            // 존재 한다면
            return true
          } else {
            // 쿠키 내에 해당 쿠키가 존재하지 않을 경우
            return false
          }
        } else {
          // 쿠키 자체가 없을 경우
          return false
        }
      },
      SetCookie (name, value, expiredays) {
        let d = new Date()

        d.setDate(d.getDate() + expiredays)

        document.cookie =
          name +
          '=' +
          escape(value) +
          '; path=/; expires=' +
          d.toGMTString() +
          ';'
      },
      CloseMainTopBannerPop () {
        // 하루 동안 mainTopBannerClose 라는 쿠키 유지

        this.SetCookie('mainTopBannerClose', 'mainTopBannerClose', 1)

        this.mainPopup = false
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dg-main_category {
    &.posFixed {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 3;
      background: #fff;
      border-bottom: 1px solid #f0f0f0;
    }
  }
</style>
