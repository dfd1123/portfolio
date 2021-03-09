<template>
  <div
    id="dg-mypage-like-list-wrapper"
    class="mypage-like-list-wrapper"
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
          <!-- 1) 찜한 상품 -->
          <div class="l-con-area">
            <div class="l-con-article">
              <div class="l-contents-group">
                <ul
                  v-if="itemLists.length > 0"
                  class="l-grid-group"
                >
                  <li
                    v-for="itemList in itemLists"
                    :key="'like'+itemList.item_id"
                    class="l-grid-list l-col-2"
                  >
                    <!-- * 옷상품 카드 -->
                    <ItemThumb
                      :item-list="itemList"
                      kind="like"
                    />
                    <!-- * 옷상품 카드 E -->
                  </li>
                </ul>

                <!-- 1. 찜한 상품 없을 때 -->
                <div
                  v-else
                  class="nothing-history"
                >
                  <img
                    src="/images/icon/empty_recent.svg"
                    alt="icon empty"
                    class="in-empty-icon"
                  >
                  <span class="in-empty-ment">찜한 상품이 없습니다.</span>
                </div>
                <!-- 1. 찜한 상품 없을 때 E -->
                <div
                  class="loading_wrap"
                  v-show="bottomLoadingShow"
                >
                  <Loading />
                </div>
              </div>
            </div>
          </div>
          <!-- 찜한 상품 E -->
        </div>
      </article>
    </div>
  </div>
</template>

<script>
  import ItemThumb from '@/components/thumb/item-thumb.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      ItemThumb,
      Loading
    },
    data: function () {
      return {
        itemLists: [],
        limit: 20,
        offset: 0,
        count: 0,
        bottomLoadingShow: false
      }
    },
    async created () {
      window.addEventListener('scroll', this.InfiniteLoad)

      this.$store.commit('ProgressShow')
      await this.FetchData()
      this.$store.commit('ProgressHide')
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
    },
    methods: {
      async FetchData () {
        const params = {
          limit: this.limit
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'wish/list', { params })).data
          this.itemLists = res.query.wishes
          this.count = res.query.count
          this.offset += res.query.wishes.length
        } catch (e) {
          console.log(e)
        }
      },
      async LoadLikeList () {
        const params = {
          limit: this.limit,
          offset: this.offset
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'wish/list', { params })).data
          this.itemLists = this.itemLists.concat(res.query.wishes)
          this.offset += res.query.wishes.length
        } catch (e) {
          console.log(e)
        }
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.count !== this.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          await this.LoadLikeList()

          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
