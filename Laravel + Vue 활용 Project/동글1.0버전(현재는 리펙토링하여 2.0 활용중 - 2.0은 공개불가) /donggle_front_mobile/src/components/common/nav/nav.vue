<template>
  <!-- 모바일 하단 내비 -->
  <div style="position: relative; z-index: 20;">
    <nav class="dg-mo_navi clear_both">
      <button
        v-ripple
        class="nav_link nav_link_home"
        @click="ForcePush('/')"
      >
        home
      </button>
      <button
        type="button"
        v-ripple
        class="nav_link nav_link_cate"
        @click="CategoryPopup"
      >
        category
      </button>
      <!--
          클릭시 .dg-category에 .active
          메뉴클릭시 .dg-category_main > a 에 .active
          서브메뉴 클릭시 .dg-category_sub ._submenu 에 .active
      -->
      <router-link
        to="/cart"
        v-ripple
        class="nav_link nav_link_cart"
      >
        cart
      </router-link>
      <router-link
        to="/mypage"
        v-ripple
        class="nav_link nav_link_my"
      >
        mypage
      </router-link>
      <button
        type="button"
        :class="['nav_link nav_link_search', {'active':searchPopup}]"
        @click="SearchPopup"
      >
        <span>search</span>
      </button>
      <!-- gradiant search popup -->
      <div class="dg-gradi_search_wrap">
        <button
          type="button"
          class="_close_btn"
          @click="searchPopup = false"
        >
          close
        </button>
        <label class="dg_blind">검색</label>
        <input
          class="dg-search"
          type="search"
          v-model="searchKeyword"
          @keyup.enter="Submit()"
          placeholder="검색어 미입력시 필터로 연결됩니다."
        >
        <button
          class="_search_btn"
          @click="Submit()"
        ></button>
        <div class="best_search_wrap clear_both">
          <h2>인기검색어</h2>
          <span
            v-for="(keyword, index) in keywords"
            :key="index"
            :to="'/total/search?searchKeyword='+keyword.pp_word"
            @click="ToSearch(keyword.pp_word)"
            class="_best_search"
          >{{ (index + 1) +'. '+keyword.pp_word }}</span>
        </div>
      </div>
      <!-- gradiant search popup E -->
    </nav>
    <!-- category -->
    <ul :class="['dg-category', {'active': $store.state.categoryPopup}]">
      <h2>카테고리</h2>
      <div class="clear_both over-y_scroll">
        <CategoryList
          v-for="(category,index) in $store.state.categorys"
          :key="index"
          :category="category"
          @popup-close="CategoryPopup"
        />
      </div>
    </ul>
    <!-- category E -->
  </div>
  <!-- 모바일 하단 내비 E -->
</template>

<script>
  import CategoryList from '@/components/common/header/category/category.vue'

  export default {
    components: {
      CategoryList
    },
    data: function () {
      return {
        keywords: [],
        searchPopup: false,
        searchKeyword: ''
      }
    },
    created () {
      this.RankLoad()
      setInterval(() => this.RankLoad(), 1000 * 60 * 3)
    },
    methods: {
      async RankLoad () {
        const res = (await this.$http.get(this.$APIURI + 'popular/list')).data

        this.keywords = res.query

        this.topTen = []
        this.nextTen = []

        this.keywords.forEach((keyword, index) => {
          if (index < 10) {
            this.topTen.push(keyword)
          } else {
            this.nextTen.push(keyword)
          }
        })
      },
      async SearchKeywordStore () {
        const params = {
          pp_word: this.$route.query.searchKeyword
        }

        try {
          const res = (await this.$http.post(this.$APIURI + 'popular', params)).data

          if (res.state === 1) {
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      ToSearch (word) {
        const query = {
          searchKeyword: word
        }
        this.$router.push({ name: 'total-search', query: query })
        this.searchPopup = false
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
      CategoryPopup () {
        if (this.$store.state.categoryPopup) {
          document.body.style.overflowY = 'auto'
        } else {
          document.body.style.overflowY = 'hidden'
        }

        this.$store.commit('CategoryPopup', !this.$store.state.categoryPopup)
      },
      SearchPopup () {
        this.searchPopup = !this.searchPopup
      }
    }
  }
</script>

<style lang="css" scoped>
  /* Safari 6.1+ (9.0 is the latest version of Safari at this time) */
  @media screen and (min-color-index: 0) and(-webkit-min-device-pixel-ratio:0) {
    @media {
      .dg-mo_navi {
        height: 60px;
        padding-bottom: 10px;
      }
    }
  }

  /* Safari 9.0+ */

  _::-webkit-:not(:root:root),
  .dg-mo_navi {
    height: 60px;
    padding-bottom: 10px;
  }

  /* Safari 9 */

  @supports (overflow: -webkit-marquee) and (justify-content: inherit) {
    .dg-mo_navi {
      height: 60px;
      padding-bottom: 10px;
    }
  }

  /* Safari 9.0+ (iOS Only) */

  @supports (-webkit-text-size-adjust: none) and (not (-ms-accelerator: true)) and
    (not (-moz-appearance: none)) {
    .dg-mo_navi {
      height: 60px;
      padding-bottom: 10px;
    }
  }

  /* Safari 6.1-7.0 */

  @media screen and (-webkit-min-device-pixel-ratio: 0) and (min-color-index: 0) {
    .dg-mo_navi {
      height: 60px;
      padding-bottom: 10px;
    }
  }

  /* Safari 7.1+ */

  _::-webkit-full-page-media,
  _:future,
  :root .dg-mo_navi {
    height: 60px;
    padding-bottom: 10px;
  }

  /* Safari 6.1+ (non-iOS) */

  @media screen and (min-color-index: 0) and(-webkit-min-device-pixel-ratio:0) {
    @media {
      _:-webkit-full-screen,
      .dg-mo_navi {
        height: 60px;
        padding-bottom: 10px;
      }
    }
  }
</style>
