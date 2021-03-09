<template>
  <div id="dg-mypage-my-review-wrapper">
    <MyPageHeader />
    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 나의 구매후기 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-35">
            <h2 class="in-subject">
              나의 구매후기
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- 0. 구매후기 작성 / 나의구매후기 버튼들 -->
            <div class="dg-write-review-btn-wrap">
              <router-link
                to="/mypage/review/write"
                class="square-btn-outline active"
              >
                구매후기 작성하기
              </router-link>
              <router-link
                to="/mypage/review/list"
                class="square-btn-outline"
              >
                나의 구매후기
              </router-link>
            </div>
            <!-- 0. 구매후기 작성 / 나의구매후기 버튼들 E-->

            <!-- 2. 후기 작성할 구매 내역 있을 때 -->
            <ul
              v-if="reviews.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="review in reviews"
                :key="'review'+review.id"
                class="l-grid-list l-col-1"
              >
                <ReviewList
                  :review="review"
                  @popup-event="OpenReviewPopup(review)"
                  @delete-event="DeleteReview(review.id)"
                />
              </li>
            </ul>
            <!-- 2. 후기 작성할 구매 내역 있을 때 E -->

            <!-- 1. 등록된 후기가 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_review.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">등록된 후기가 없습니다.</span>
            </div>
            <!-- 1. 등록된 후기가 없을 때 E -->
          </div>
        </article>
      </div>
      <!-- 1) 나의 구매후기 -->
      <ReviewWritePopup
        :item="item"
        kind="edit"
        @close-event="CloseReviewPopup"
        @reload="Reload"
        v-show="popupShow"
      />
      <div
        class="loading_wrap"
        v-show="bottomLoadingShow"
      >
        <Loading />
      </div>
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import ReviewList from '@/components/mypage/review/review-list.vue'
  import ReviewWritePopup from '@/components/popup/review-write-popup.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
      ReviewList,
      ReviewWritePopup,
      Loading
    },
    data: function () {
      return {
        item: {},
        reviews: [],
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
      this.reviews = res.reviews
      this.count = res.count
      this.offset += res.reviews.length
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
          const res = (await this.$http.get(this.$APIURI + 'review/my_review_list', { params })).data
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
        this.popupShow = false

        this.limit = this.reviews.length
        this.offset = 0

        this.$store.commit('ProgressShow')
        const res = await this.FetchData()
        this.limit = 20
        this.reviews = res.reviews
        this.count = res.count
        this.offset += res.reviews.length
        this.$store.commit('ProgressHide')
      },
      async DeleteReview (id) {
        const result = await this.Confirm('정말 이 구매후기를 삭제하시겠습니까?')
        if (result) {
          const params = {
            review_id: id,
            _method: 'put'
          }
          const res = (await this.$http.post(this.$APIURI + 'review/delete', params)).data

          if (res.state === 1) {
            this.SuccessAlert('삭제가 완료되었습니다.')
            this.limit = this.reviews.length
            this.offset = 0

            this.$store.commit('ProgressShow')
            const res = await this.FetchData()
            this.limit = 20
            this.reviews = res.reviews
            this.count = res.count
            this.offset = res.reviews.length
            this.$store.commit('ProgressHide')
          }
        }
      },
      OpenReviewPopup (itemList) {
        this.item = itemList
        this.popupShow = true
      },
      CloseReviewPopup () {
        this.popupShow = false
      },
      async InfiniteLoad () {
        let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight

        if (bottomOfWindow && this.count !== this.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true

          const res = await this.FetchData()
          this.itemLists.concat(res.orders)
          this.form.offset += res.orders.length

          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
