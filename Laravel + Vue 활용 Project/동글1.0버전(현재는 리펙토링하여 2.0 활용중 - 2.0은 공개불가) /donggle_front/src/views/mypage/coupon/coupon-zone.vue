<template>
  <div id="dg-mypage-coupon-zone-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 쿠폰받기 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-25">
            <h2 class="in-subject">
              쿠폰받기
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- 0. 분류 -->
            <div class="dg-coupon-sorting-bar mb-40">
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
              <button
                type="button"
                class="square-btn-dark"
                @click="AllCouponDownload"
              >
                쿠폰 전체 받기
              </button>
            </div>
            <!-- 0. 분류 -->

            <!-- 2. 받을 쿠폰이 있을 때 -->
            <ul
              v-if="couponLists.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="couponList in couponLists"
                :key="couponList.id"
                class="l-grid-list l-col-2"
              >
                <!-- * 쿠폰컴퍼넌트 (클래스 type-download 추가) -->
                <CouponThumb
                  kind="download"
                  :coupon-list="couponList"
                  @popup-event="OpenPopup"
                />
                <!-- * 쿠폰컴퍼넌트 (클래스 type-download 추가) E -->
              </li>
            </ul>
            <!-- 2. 받을 쿠폰이 있을 때 E -->

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
      <!-- 1) 쿠폰받기 E -->
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
          const res = (await this.$http.get(this.$APIURI + 'couponzone/list', { params })).data

          this.couponLists = res.query
        } catch (e) {
          console.log(e)
        }
      },
      Submit () {
        this.$router.push({ name: 'mypage-couponzone', query: { orderBy: this.orderBy } })
        this.FetchData()
      },
      async AllCouponDownload () {
        const result = await this.Confirm('정말 모든 쿠폰을 다운로드 받으시겠습니까?')
        if (result) {
          const possible = await this.DownloadCoupon()
          if (possible) {
            this.couponLists.forEach(async (coupon, index) => {
              if (coupon.get_coupon === 0) {
                const params = {
                  cz_id: coupon.id
                }

                try {
                  const res = (await this.$http.post(this.$APIURI + 'coupon', params)).data
                  if (res.state === 1) {
                    if (res.query) {
                      this.couponLists[index].get_coupon = 1
                    }
                  } else {
                    console.log(res.msg)
                  }
                } catch (e) {
                  console.log(e)
                }
              }
            })
          } else {
            this.InfoAlert('다운로드 받을 쿠폰이 없습니다.')
          }
        }
      },
      DownloadCoupon () {
        let possible = false
        this.couponLists.forEach(coupon => {
          if (coupon.get_coupon === 0) {
            possible = true
          }
        })

        return possible
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
