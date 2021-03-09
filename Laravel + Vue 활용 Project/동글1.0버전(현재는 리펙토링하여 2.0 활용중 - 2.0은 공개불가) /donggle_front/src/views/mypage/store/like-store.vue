<template>
  <div id="dg-mypage-store-list-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 즐겨찾기 스토어 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-35">
            <h2 class="in-subject">
              즐겨찾기 스토어
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- 2. 즐겨찾는 스토어가 있을 때 -->
            <ul
              v-if="stores.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="store in stores"
                :key="'store'+store.store_id"
                class="l-grid-list l-col-2"
              >
                <!-- * 즐찾 스토어 카드 -->
                <StoreThumb
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
          </div>
          <div
            class="loading_wrap"
            v-show="bottomLoadingShow"
          >
            <Loading />
          </div>
        </article>
      </div>
      <!-- 1) 즐겨찾기 스토어 E -->
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import StoreThumb from '@/components/thumbnail/store-thumb.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
      StoreThumb,
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
        let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight

        if (bottomOfWindow && this.allCount !== this.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true

          const res = await this.StoreLoad()
          this.stores.concat(res.seller)
          this.offset += res.seller.length

          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
