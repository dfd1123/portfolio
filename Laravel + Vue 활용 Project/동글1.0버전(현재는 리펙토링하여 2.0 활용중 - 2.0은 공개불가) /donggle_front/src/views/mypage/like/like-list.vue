<template>
  <div id="dg-mypage-like-list-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 찜한 상품 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-30">
            <h2 class="in-subject">
              찜한 상품
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- 2. 찜한 상품 있을 때 -->
            <ul
              v-if="itemLists.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="itemList in itemLists"
                :key="'like'+itemList.item_id"
                class="l-grid-list l-col-4"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="itemList"
                  kind="like"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <!-- 2. 찜한 상품 있을 때 E -->
            <!-- 1. 찜한 상품 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_like.svg"
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
        </article>
      </div>
      <!-- 찜한 상품 E -->
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import ItemThumb from '@/components/thumbnail/item-thumb.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
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
    async mounted () {
      this.InfiniteLoad()
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
          this.itemLists.concat(res.query.wishes)
          this.offset += res.query.wishes.length
        } catch (e) {
          console.log(e)
        }
      },
      async InfiniteLoad () {
        let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight

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
