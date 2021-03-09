<template>
  <div
    id="dg-mypage-coupon-history-wrapper"
    class="coupon-history-wrapper mypage-order-history-wrapper dg-product-list-wrapper"
  >
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
      <!-- 1) 쿠폰 사용내역 -->
      <div class="l-con-area">
        <div class="l-con-article">
          <div>
            <!-- 0. 검색옵션 설정영역 -->
            <!-- 조회기간 -->
            <div class="dg-filter-search_wrap">
              <h2>조회기간</h2>
              <!-- 조회기간 표시 조회기간설정해서 조회버튼 누르면 보임 -->
              <div class="dg-filter-search-list">
                <div class="in-section">
                  <button
                    type="button"
                    class="rounded-btn-outline"
                  >
                    <span>{{ $moment(form.startDate).format('YYYY-MM-DD') }} ~ {{ $moment(form.endDate).format('YYYY-MM-DD') }}</span>
                  </button>
                </div>
              </div>
              <!-- 조회기간 표시 조회기간설정해서 조회버튼 누르면 보임 E -->
              <!-- 조회기간 설정 -->
              <input
                id="test11"
                type="checkbox"
                class="filter-search-list_view display_none"
              >
              <label
                for="test11"
                class="dg-filter-search-btn_wrap"
              >
                <span class="dg-filter-search-btn">필터</span>
              </label>
              <div class="filter-search-list-wrap">
                <DateRangeComp
                  :date-range="dateRange"
                  :date-ranges="dateRanges"
                  @input="dateRange = $event"
                  @submit="DateRange"
                />
                <Datepicker
                  :start-date="$moment(form.startDate).format('YYYY-MM-DD')"
                  :end-date="$moment(form.endDate).format('YYYY-MM-DD')"
                  :date-range="dateRange"
                  @relay-start-date="form.startDate = $event"
                  @relay-end-date="form.endDate = $event"
                  @relay-date-range="dateRange = $event"
                  @submit="Submit"
                />
              </div>
              <!-- 조회기간 설정 E -->
            </div>
            <!-- 조회기간 E -->
            <!-- 0. 검색옵션 설정영역 E -->

            <!-- 1. 쿠폰 사용내역 테이블 -->
            <div class="dg-coupon-history-table">
              <div class="clear_both">
                <div v-if="couponLists.length > 0">
                  <ul>
                    <li
                      v-for="couponList in couponLists"
                      :key="'couponUse'+couponList.id"
                      class="l-grid-list"
                    >
                      <!-- 사용내역이 있을 때 -->
                      <div class="in-information">
                        <span class="_date">{{ couponList.cl_datetime }}</span>
                        <h5 class="_tit">
                          {{ couponList.cp_subject }}
                        </h5>
                        <dl class="_desc">
                          <dt>주문번호</dt>
                          <dd>{{ couponList.od_id }}</dd>
                        </dl>
                        <dl class="_desc">
                          <dt>할인금액</dt>
                          <dd><span>{{ NumberFormat(couponList.cp_price) }}원</span></dd>
                        </dl>
                      </div>
                      <!-- 사용내역이 있을 때 E -->
                    </li>
                  </ul>
                </div>
                <!-- 사용내역이 없을 때 -->
                <div v-else>
                  <div class="nothing-history">
                    <img
                      src="/images/icon/empty_coupon.svg"
                      alt="icon empty"
                      class="in-empty-icon"
                    >
                    <span class="in-empty-ment">사용내역이 없습니다.</span>
                  </div>
                </div>
                <!-- 사용내역이 없을 때 E -->
                <div
                  class="loading_wrap"
                  v-show="bottomLoadingShow"
                >
                  <Loading />
                </div>
              </div>
            </div>
            <!-- 1. 쿠폰 사용내역 테이블 E -->
          </div>
        </div>
      </div>
      <!-- 1) 쿠폰 사용내역 E -->
    </div>
  </div>
</template>

<script>
  import DateRangeComp from '@/components/mypage/search/date-range.vue'
  import Datepicker from '@/components/mypage/search/datepicker.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      DateRangeComp,
      Datepicker,
      Loading
    },
    data: function () {
      return {
        allCount: 0,
        limit: 10,
        currentPage: Number(this.$route.query.page) || 1,
        offset: 0,
        couponLists: [],
        form: {
          startDate: this.$route.query.startDate || this.$moment().subtract(1, 'w').format('YYYY-MM-DD'),
          endDate: this.$route.query.endDate || this.$moment().format('YYYY-MM-DD')
        },
        dateRange: this.$route.query.dateRange || '7',
        dateRanges: [
          {
            name: '1주일',
            key: 'oneWeek',
            value: '7'
          },
          {
            name: '1개월',
            key: 'oneMonth',
            value: '30'
          },
          {
            name: '3개월',
            key: 'threeMonth',
            value: '90'
          },
          {
            name: '6개월',
            key: 'sixMonth',
            value: '180'
          }
        ],
        bottomLoadingShow: false
      }
    },
    async created () {
      window.addEventListener('scroll', this.InfiniteLoad)

      this.$store.commit('ProgressShow')
      const res = await this.FetchData()
      this.allCount = res.count
      this.couponLists = res.coupon_use_log
      this.offset = res.coupon_use_log.length
      this.$store.commit('ProgressHide')
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
    },
    methods: {
      async FetchData () {
        const params = {
          limit: this.limit,
          offset: this.offset,
          start_date: this.form.startDate,
          end_date: this.form.endDate
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'couponuselog/list', { params })).data

          if (res.state === 1) {
            return res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async Submit () {
        this.offset = 0

        this.$store.commit('ProgressShow')
        const res = await this.FetchData()
        this.allCount = res.count
        this.couponLists = res.coupon_use_log
        this.offset = res.coupon_use_log.length
        this.$store.commit('ProgressHide')

        this.$router.replace({ name: 'mypage-use-history', query: this.form })
      },
      DateRange () {
        if (this.dateRange === '7') {
          this.form.startDate = this.$moment().subtract(1, 'week').format('YYYY-MM-DD')
        } else if (this.dateRange === '30') {
          this.form.startDate = this.$moment().subtract(1, 'month').format('YYYY-MM-DD')
        } else if (this.dateRange === '90') {
          this.form.startDate = this.$moment().subtract(3, 'month').format('YYYY-MM-DD')
        } else if (this.dateRange === '180') {
          this.form.startDate = this.$moment().subtract(6, 'month').format('YYYY-MM-DD')
        }

        this.form.endDate = this.$moment().format('YYYY-MM-DD')

        this.Submit()
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.form.allCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true

          const res = await this.FetchData()
          this.couponLists = this.couponLists.concat(res.coupon_use_log)
          this.offset += res.coupon_use_log.length

          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
