<template>
  <div id="dg-mypage-coupon-list-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 내 쿠폰함 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-25">
            <h2 class="in-subject">
              내 쿠폰함
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- 0. 쿠폰코드 입력 + 분류 -->
            <div class="dg-coupon-input-group">
              <h4 class="in-subject">
                쿠폰 코드 입력
              </h4>
              <div class="in-input-group">
                <input
                  type="text"
                  class="_input"
                  v-model="couponCode"
                  @keyup.enter="AddCoupon"
                  placeholder="쿠폰 코드를 입력해주세요."
                >
                <button
                  type="button"
                  class="square-btn-outline"
                  @click="AddCoupon"
                >
                  쿠폰등록
                </button>
              </div>
            </div>

            <div class="dg-coupon-sorting-bar">
              <ul class="in-sorting">
                <li class="_type">
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
                <li class="_type">
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
            <!-- 0. 쿠폰코드 입력 + 분류 E -->

            <!-- 2. 제공된 쿠폰이 있을 때 -->
            <ul
              v-if="couponLists.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="couponList in couponLists"
                :key="couponList.id"
                class="l-grid-list l-col-2"
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

            <!-- 쿠폰 적용 상품 보기 팝업 -->
            <CouponPossible
              v-show="popupShow"
              :coupon="this.coupon"
              @close-popup="ClosePopup"
            />
            <!-- 쿠폰 적용 상품 보기 팝업 E -->
          </div>
        </article>
      </div>
      <!-- 1) 내 쿠폰함 E -->
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import CouponThumb from '@/components/thumbnail/coupon-thumb.vue'
  import CouponPossible from '@/components/popup/coupon-possible.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
      CouponThumb,
      CouponPossible
    },
    data: function () {
      return {
        couponCode: null,
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
        this.coupon = coupon
        this.popupShow = true
      },
      ClosePopup () {
        this.coupon = {}
        this.popupShow = false
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
