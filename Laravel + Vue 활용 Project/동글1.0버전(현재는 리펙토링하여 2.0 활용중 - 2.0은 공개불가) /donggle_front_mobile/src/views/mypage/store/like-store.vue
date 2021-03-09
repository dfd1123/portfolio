<template>
  <div
    id="dg-mypage-store-list-wrapper"
    class="mypage-store-list-wrapper"
  >
    <!-- * 마이페이지 헤더 -->
    <div class="l-con-area full">
      <article class="l-con-article dg-product-list-wrapper">
        <div class="_page_title_wrap">
          <h2>
            관심리스트
            <button
              type="button"
              class="_back_btn"
              @click="$router.go(-1)"
            >
              뒤로가기
            </button>
          </h2>
          <div class="second_title clear_both">
            <router-link to="/mypage/like/list">
              찜한 상품
            </router-link>
            <router-link to="/mypage/recent/list">
              최근 본 상품
            </router-link>
            <router-link to="/mypage/store/like">
              즐겨찾기 스토어
            </router-link>
          </div>
        </div>
        <!-- * 마이페이지 헤더 -->

        <div class="l-mypage-contents">
          <!-- 1) 즐겨찾기 스토어 -->
          <div>
            <div class="l-con-article">
              <div class="l-con-area">
                <!-- 2. 즐겨찾는 스토어가 있을 때 -->
                <ul
                  v-if="stores.length > 0"
                  class="l-grid-group"
                >
                  <li
                    v-for="store in stores"
                    :key="'store'+store.store_id"
                    class="l-grid-list"
                  >
                    <!-- * 즐찾 스토어 카드 -->
                    <StoreCard
                      :store="store"
                      @reload="Reload"
                    />
                    <!-- * 즐찾 스토어 카드 E -->
                  </li>
                </ul>
                <!-- 2. 즐겨찾는 스토어가 있을 때 E -->
                <!-- 1. 즐겨찾는 스토어가 없을 때 -->
                <div
                  v-else
                  class="nothing-history"
                >
                  <img
                    src="/images/icon/empty_bookmark.svg"
                    alt="icon empty"
                    class="in-empty-icon"
                  >
                  <span class="in-empty-ment">즐겨찾는 스토어가 없습니다.</span>
                </div>
                <!-- 1. 즐겨찾는 스토어가 없을 때 E -->
                <div
                  class="loading_wrap"
                  v-show="bottomLoadingShow"
                >
                  <Loading />
                </div>
              </div>
            </div>
          </div>
          <!-- 1) 즐겨찾기 스토어 E -->
        </div>
      </article>
    </div>
  </div>
</template>

<script>
  import StoreCard from '@/components/mypage/store/store-card.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      StoreCard,
      Loading
    },
    data: function () {
      return {
        bottomLoadingShow: false,
        stores: [],
        allCount: 0,
        limit: 8,
        offset: 0
      }
    },
    async created () {
      window.addEventListener('scroll', this.InfiniteLoad)

      this.$store.commit('ProgressShow')
      const res = await this.StoreLoad()
      this.stores = res.seller
      this.allCount = res.count
      this.offset = res.seller.length
      this.$store.commit('ProgressHide')
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
    },
    methods: {
      async StoreLoad () {
        const params = {
          limit: this.limit,
          offset: this.offset
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'sellerlike/list', { params })).data
          if (res.state === 1) {
            return res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async Reload () {
        this.limit = this.stores.length
        this.offset = 0

        const res = await this.StoreLoad()
        this.limit = 8
        this.stores = res.seller
        this.allCount = res.count
        this.offset = res.seller.length
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.allCount !== this.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true

          const res = await this.StoreLoad()
          this.stores = this.stores.concat(res.seller)
          this.offset += res.seller.length

          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
