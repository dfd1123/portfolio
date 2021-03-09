<template>
  <div
    id="dg-mypage-write-review-wrapper"
    class="mypage-write-review-wrapper"
  >
    <!-- * 마이페이지 헤더 -->
    <div class="_page_title_wrap">
      <h2>
        나의 구매후기
        <button
          type="button"
          class="_back_btn"
          @click="$router.go(-1)"
        >
          뒤로가기
        </button>
      </h2>
      <div class="second_title clear_both">
        <router-link
          to="/mypage/review/write"
          class="active"
        >
          구매후기 작성하기
        </router-link>
        <router-link to="/mypage/review/list">
          나의 구매후기
        </router-link>
      </div>
    </div>
    <!-- * 마이페이지 헤더 -->

    <div class="l-mypage-contents">
      <!-- 1) 나의 구매후기 -->
      <div class="l-con-area">
        <article class="l-con-article dg-product-list-wrapper">
          <div class="l-contents-group">
            <!-- 2. 후기 작성할 구매 내역 있을 때 -->
            <ul
              v-if="itemLists.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="itemList in itemLists"
                :key="'itemList'+itemList.order_no"
                class="l-grid-list l-col-2"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="itemList"
                  kind="review"
                  @popup-event="OpenReviewPopup"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <!-- 2. 후기 작성할 구매 내역 있을 때 E -->
            <!-- 1. 후기 작성할 구매 내역 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_order.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">후기를 작성할 수 있는<br>구매 내역이 없습니다.</span>
            </div>
            <!-- 1. 후기 작성할 구매 내역 없을 때 E -->
          </div>
        </article>
      </div>
      <!-- 1) 나의 구매후기 E -->
    </div>
    <ReviewWritePopup
      :class="{'posFixed': popupShow}"
      :item="item"
      kind="create"
      @close-event="CloseReviewPopup"
      @reload="Reload"
    />
    <div
      class="loading_wrap"
      v-show="bottomLoadingShow"
    >
      <Loading />
    </div>
  </div>
</template>

<script>
  import ItemThumb from '@/components/thumb/item-thumb.vue'
  import ReviewWritePopup from '@/components/popup/review-write-popup.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      ItemThumb,
      ReviewWritePopup,
      Loading
    },
    data: function () {
      return {
        item: {},
        itemLists: [],
        limit: 20,
        offset: 0,
        count: 0,
        popupShow: false,
        bottomLoadingShow: false
      }
    },
    async created () {
      window.addEventListener('scroll', this.InfiniteLoad)

      this.$store.commit('ProgressShow')
      const res = await this.FetchData()
      this.itemLists = res.orders
      this.count = res.count
      this.offset += res.orders.length
      this.$store.commit('ProgressHide')
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
    },
    methods: {
      async FetchData () {
        const params = {
          limit: this.limit,
          offset: this.offset
        }
        try {
          const res = (await this.$http.get(this.$APIURI + 'review/possible_review_list', { params })).data

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
        document.body.style.overflowY = 'auto'
        this.popupShow = false

        this.limit = this.itemLists.length
        this.offset = 0

        this.$store.commit('ProgressShow')
        const res = await this.FetchData()
        this.limit = 20
        this.itemLists = res.orders
        this.count = res.count
        this.offset += res.orders.length
        this.$store.commit('ProgressHide')
      },
      OpenReviewPopup (itemList) {
        document.body.style.overflowY = 'hidden'
        this.item = itemList
        this.popupShow = true
      },
      CloseReviewPopup () {
        document.body.style.overflowY = 'auto'
        this.popupShow = false
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.count !== this.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true

          const res = await this.FetchData()
          this.itemLists = this.itemLists.concat(res.orders)
          this.form.offset += res.orders.length

          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
