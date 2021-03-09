<template>
  <div id="dg-mypage-coupon-history-wrapper">
    <MyPageHeader />
    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 쿠폰 사용내역 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-25">
            <h2 class="in-subject">
              쿠폰 사용내역
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- 0. 검색옵션 설정영역 -->
            <div class="dg-filter-inquiry-group mb-23">
              <div class="in-bottom-group">
                <DateRangeComp
                  :date-range="this.dateRange"
                  :date-ranges="this.dateRanges"
                  @input="dateRange = $event"
                  @submit="DateRange"
                />
                <Datepicker
                  :start-date="this.$moment(form.startDate).format('YYYY-MM-DD')"
                  :end-date="this.$moment(form.endDate).format('YYYY-MM-DD')"
                  :date-range="dateRange"
                  @relay-start-date="form.startDate = $event"
                  @relay-end-date="form.endDate = $event"
                  @relay-date-range="dateRange = $event"
                  @submit="Submit"
                />
              </div>
            </div>
            <!-- 0. 검색옵션 설정영역 E -->

            <!-- 1. 쿠폰 사용내역 테이블 -->
            <div class="dg-coupon-history-table">
              <!-- * 테이블영역 -->
              <table class="in-table">
                <caption class="dg_blind">
                  쿠폰 사용내역 현황
                </caption>
                <colgroup>
                  <col width="40%">
                  <col width="20%">
                  <col width="17%">
                  <col width="23%">
                </colgroup>
                <thead>
                  <tr>
                    <th>쿠폰이름</th>
                    <th>주문번호</th>
                    <th>할인금액</th>
                    <th>사용날짜</th>
                  </tr>
                </thead>
                <tbody v-if="couponLists.length > 0">
                  <!-- 2. 쿠폰 사용내역이 있을 때 (1페이지당 10개까지) -->
                  <tr
                    v-for="couponList in couponLists"
                    :key="'couponUse'+couponList.id"
                  >
                    <td>{{ couponList.cp_subject }}</td>
                    <td class="_td_light">
                      {{ couponList.od_id }}
                    </td>
                    <td class="_td_light">
                      {{ NumberFormat(couponList.cp_price) }}원
                    </td>
                    <td class="_td_light">
                      {{ couponList.cl_datetime }}
                    </td>
                  </tr>
                  <!-- 2. 쿠폰 사용내역이 있을 때 (1페이지당 10개까지) E -->
                </tbody>
                <tbody v-else>
                  <!-- 1. 쿠폰 사용내역이 없을 때 -->
                  <tr>
                    <td colspan="4">
                      <div class="nothing-history">
                        <img
                          src="/images/icon/empty_coupon.svg"
                          alt="icon empty"
                          class="in-empty-icon"
                        >
                        <span class="in-empty-ment">사용내역이 없습니다.</span>
                      </div>
                    </td>
                  </tr>
                  <!-- 1. 쿠폰 사용내역이 없을 때 E -->
                </tbody>
              </table>
              <!-- * 테이블영역 E -->

              <!-- * pagination 자리 -->
              <Pagination
                :items="couponLists"
                :item-cnt="allCount"
                :page-size="limit"
                :initial-page="currentPage"
                ref="pagination"
                @changePage="OnChangePage"
              />
              <!-- * pagination 자리 E -->
            </div>
            <!-- 1. 쿠폰 사용내역 테이블 E -->
          </div>
        </article>
      </div>
      <!-- 1) 쿠폰 사용내역 E -->
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import DateRangeComp from '@/components/mypage/search/date-range.vue'
  import Datepicker from '@/components/mypage/search/datepicker.vue'
  import Pagination from '@/components/pagination/pagination.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
      DateRangeComp,
      Datepicker,
      Pagination
    },
    data: function () {
      return {
        allCount: 0,
        limit: 10,
        currentPage: Number(this.$route.query.page) || 1,
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
        ]
      }
    },
    async created () {
      window.addEventListener('scroll', this.InfiniteLoad)

      this.$store.commit('ProgressShow')
      const res = await this.FetchData()
      this.allCount = res.count
      this.currentPage = res.page
      this.couponLists = res.coupon_use_log
      this.offset = res.coupon_use_log.length
      this.$store.commit('ProgressHide')
    },
    mounted () {
      if (this.allCount > this.limit) {
        console.log(this.$refs.pagination)
        this.$refs.pagination.SetPage(this.currentPage, false)
      }
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
        this.currentPage = res.page
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
      OnChangePage (currentPage) {
        this.currentPage = currentPage
        // this.fetchData()
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
