<template>
  <!-- main gnb -->
  <div class="dg-main_category_wrap">
    <div class="dg-content_center dg-main_category">
      <div class="dg-main_category_link">
        <router-link
          to="#"
          class="dg-main_category_a dg-category_label"
        >
          카테고리
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
        <router-link
          to="#"
          @click="InfoAlert('준비중인 서비스 입니다.')"
          class="dg-main_category_a"
        >
          오늘 출발(예정)
        </router-link>
        <router-link
          to="#"
          @click="InfoAlert('준비중인 서비스 입니다.')"
          class="dg-main_category_a dg-main_category_a_last"
        >
          실시간 퀵출발(예정)
        </router-link>
        <div :class="['dg-gradation_search_btn dg-btn_gra',{ 'active':fullSearchShow }]">
        </div>
        <div
          v-if="headSmalled > 283"
          class="dg-gradation_search_btn_icon"
          @click="FullSearch()"
        >
        </div>

        <TotalSearch
          @close-event="FullSearch()"
          :full-search-show="fullSearchShow"
        />

        <!-- category: 화면이 작거나 카테고리가 많으면 화면에 들어오지 않음 = scroll -->
        <div class="dg-category">
          <ul class="clear_both over-y_scroll">
            <HeadCategoryList
              v-for="(category,index) in $store.state.categorys"
              :key="index"
              :category="category"
            />
          </ul>
        </div>
        <!-- category E -->
      </div>
    </div>
  </div>
  <!-- main gnb E -->
</template>

<script>
  import TotalSearch from '@/components/common/header/search/total-search.vue'
  import HeadCategoryList from '@/components/common/header/menu/head-category-list.vue'

  export default {
    components: {
      TotalSearch,
      HeadCategoryList
    },
    data: function () {
      return {
        headSmalled: 0,
        fullSearchShow: false
      }
    },
    created () {
      this.headSmalled = window.scrollY
      window.addEventListener('scroll', this.HandleScroll)
    },
    destroyed () {
      window.removeEventListener('scroll', this.HandleScroll)
    },
    methods: {
      FullSearch () {
        if (this.fullSearchShow) {
          this.fullSearchShow = false
        } else {
          this.fullSearchShow = true
        }
      },
      HandleScroll () {
        this.headSmalled = window.scrollY

        if (this.headSmalled <= 283) {
          this.fullSearchShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
