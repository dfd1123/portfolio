<template>
  <!-- header -->
  <header
    id="dg-hd"
    :class="{'_scroll':HeadSmalled}"
  >
    <TopBanner />
    <div class="posi_change_wrap">
      <div class="dg-hd-con1_wrap">
        <div class="dg-content_center clear_both">
          <!-- content float -->
          <router-link
            to=""
            @click.native="ForcePush('/')"
          >
            <h1 class="dg-hd-logo">
              donggle
            </h1>
          </router-link>
          <SearchRanking />
          <MyMenu :head-small="HeadSmalled" />
        </div>
      </div>

      <HeadSearch />

      <MainMenu ref="mainmenu" />
    </div>
  </header>
  <!-- header E -->
</template>

<script>
  import MyMenu from '@/components/common/header/menu/my-menu.vue'
  import SearchRanking from '@/components/common/header/rank/search-ranking.vue'
  import MainMenu from '@/components/common/header/menu/main-menu.vue'
  import HeadSearch from '@/components/common/header/search/head-search.vue'
  import TopBanner from '@/components/banner/top-banner.vue'

  export default {
    components: {
      MyMenu,
      SearchRanking,
      MainMenu,
      HeadSearch,
      TopBanner
    },
    data: function () {
      return {
        headSmalled: 0
      }
    },
    computed: {
      HeadSmalled () {
        if (this.headSmalled > 283) {
          return true
        } else {
          return false
        }
      }
    },
    created () {
      window.addEventListener('scroll', this.HandleScroll)
    },
    destroyed () {
      window.removeEventListener('scroll', this.HandleScroll)
    },
    methods: {
      HandleScroll () {
        this.headSmalled = window.scrollY
      }
    }
  }
</script>

<style lang="scss" scoped>
  html {
  }
</style>
