<template>
  <div
    id="dg-mypage-coupon-list-wrapper"
    class="mypage-coupon-list-wrapper"
  >
    <!-- 쿠폰적용상품 팝업 -->
    <CouponPossible
      :class="{'posFixed':popupShow}"
      :coupon="this.coupon"
      @close-popup="ClosePopup"
      @popup-open="HashTagPopupOpen"
    />
    <!-- 쿠폰적용상품 팝업 E -->
    <!-- * 마이페이지 헤더 -->
    <div class="_page_title_wrap">
      <h2>
        쿠폰함
        <button
          type="button"
          class="_back_btn"
          @click="$router.go(-1)"
        >
          뒤로가기
        </button>
      </h2>
      <div class="second_title clear_both">
        <router-link to="/mypage/mycoupon">
          내 쿠폰함
        </router-link>
        <router-link to="/mypage/couponzone">
          쿠폰받기
        </router-link>
        <router-link to="/mypage/coupon/history">
          쿠폰 사용내역
        </router-link>
      </div>
    </div>
    <!-- * 마이페이지 헤더 -->

    <div class="l-mypage-contents">
      <!-- 1) 내 쿠폰함 -->
      <div class="l-con-area">
        <div class="l-con-article">
          <div>
            <!-- 0. 분류 -->
            <div class="dg-coupon-sorting-bar mb-40">
              <ul class="in-sorting">
                <li :class="['_type', {'active':orderBy === 'latest'}]">
                  <input
                    type="radio"
                    id="latest"
                    class="sort_radio_input display_none"
                    value="latest"
                    v-model="orderBy"
                    @change="Submit"
                    checked
                  >
                  <label
                    for="latest"
                    class="word"
                  >
                    최신순
                  </label>
                </li>
                <li :class="['_type', {'active':orderBy === 'discount'}]">
                  <input
                    type="radio"
                    id="discount"
                    class="sort_radio_input display_none"
                    value="discount"
                    v-model="orderBy"
                    @change="Submit"
                  >
                  <label
                    for="discount"
                    class="word"
                  >
                    할인순
                  </label>
                </li>
              </ul>
            </div>
            <!-- 0. 분류 -->
            <!-- 1. 쿠폰코드 입력 -->
            <div class="dg-coupon-input-group">
              <h4 class="in-subject">
                쿠폰 코드 입력
              </h4>
              <div class="in-input-group">
                <input
                  type="text"
                  class="_input"
                  placeholder="쿠폰 코드를 입력해주세요."
                  v-model="couponCode"
                  @keyup.enter="AddCoupon"
                >
                <button
                  type="button"
                  class="square-btn-dark"
                  @click="AddCoupon"
                >
                  쿠폰등록
                </button>
              </div>
            </div>
            <!-- 1. 쿠폰코드 입력 E -->

            <!-- 2. 제공된 쿠폰이 있을 때 -->
            <ul
              v-if="couponLists.length > 0"
              class="l-grid-group coupon_layout"
            >
              <li
                v-for="couponList in couponLists"
                :key="couponList.id"
                class="l-grid-list"
              >
                <!-- * 쿠폰컴퍼넌트 -->
                <CouponThumb
                  :coupon-list="couponList"
                  @popup-event="OpenPopup"
                />
                <!-- * 쿠폰컴퍼넌트 E -->
              </li>
            </ul>
            <!-- 2. 제공된 쿠폰이 있을 때 E -->

            <!-- 1. 제공된 쿠폰이 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_coupon.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">제공된 쿠폰이 없습니다.</span>
            </div>
            <!-- 1. 제공된 쿠폰이 없을 때 E -->
          </div>
        </div>
      </div>
      <!-- 1) 내 쿠폰함 E -->
    </div>
    <HashtagPopup
      class="whenPopupOver"
      :hashtag-popup-open="hashtagPopupOpen"
      :show-item="showItem"
      @popup-close="HashTagPopupClose"
    />
  </div>
</template>

<script>
  import CouponThumb from '@/components/thumb/coupon-thumb.vue'
  import CouponPossible from '@/components/popup/coupon-possible.vue'
  import HashtagPopup from '@/components/popup/hashtag-popup.vue'

  export default {
    components: {
      CouponThumb,
      CouponPossible,
      HashtagPopup
    },
    data: function () {
      return {
        hashtagPopupOpen: false,
        showItem: {},
        couponCode: '',
        orderBy: this.$route.query.orderBy || 'latest',
        popupShow: false,
        coupon: {},
        couponLists: []
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.FetchData()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async FetchData () {
        const params = {
          orderBy: this.orderBy
        }
        try {
          const res = (await this.$http.get(this.$APIURI + 'coupon/mypage_coupon', { params })).data

          if (res.state === 1) {
            this.couponLists = res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      Submit () {
        this.$router.push({ name: 'mypage-mycoupon', query: { orderBy: this.orderBy } })
        this.FetchData()
      },
      async AddCoupon () {
        this.couponCode = this.couponCode.replace(/ /gi, '')
        if (this.couponCode && this.couponCode !== '') {
          const params = {
            cp_id: this.couponCode
          }
          try {
            this.$store.commit('ProgressShow')

            const res = (await this.$http.put(this.$APIURI + 'coupon/download', params)).data

            if (res.state === 1) {
              this.FetchData()
              this.SuccessAlert('쿠폰발급이 완료 되었습니다.')
            } else {
              this.WarningAlert(res.msg)
            }
          } catch (e) {
            console.log(e)
          } finally {
            this.couponCode = null
            this.$store.commit('ProgressHide')
          }
        }
      },
      OpenPopup (coupon) {
        document.body.style.overflowY = 'hidden'
        this.coupon = coupon
        this.popupShow = true
      },
      ClosePopup () {
        document.body.style.overflowY = 'auto'
        this.coupon = {}
        this.popupShow = false
      },
      HashTagPopupOpen (item) {
        document.body.style.overflowY = 'hidden'
        this.showItem = item
        this.hashtagPopupOpen = true
      },
      HashTagPopupClose () {
        document.body.style.overflowY = 'auto'
        this.hashtagPopupOpen = false
        this.showItem = {}
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
