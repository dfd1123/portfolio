<template>
  <div id="dg-mypage-order-history-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 주문내역 조회 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-18">
            <h2 class="in-subject">
              주문내역 조회
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- 0. 검색옵션 설정영역 -->
            <div class="dg-filter-inquiry-group mb-18">
              <SelectStatus
                :od-status="form.od_status"
                :status="status"
                @input="form.od_status = $event"
                @submit="Submit"
              />
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

            <!-- 2. 주문 내역 있을 때 -->
            <div
              v-if="orderLists.length > 0"
              class="l-grid-group"
            >
              <div
                v-for="orderList in orderLists"
                :key="'ordeList'+orderList.order_no"
                class="l-grid-list l-col-1"
              >
                <OrderList :order-list="orderList" />
              </div>
            </div>
            <!-- 2. 주문 내역 있을 때 E -->
            <!-- 1. 주문 내역 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_order.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">주문 내역이 없습니다.</span>
            </div>
            <!-- 1. 주문 내역 없을 때 E -->
          </div>
        </article>
      </div>
      <!-- 1) 주문내역 조회 E -->
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import SelectStatus from '@/components/mypage/search/select-status.vue'
  import DateRangeComp from '@/components/mypage/search/date-range.vue'
  import Datepicker from '@/components/mypage/search/datepicker.vue'
  import OrderList from '@/components/mypage/order-list.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
      SelectStatus,
      DateRangeComp,
      Datepicker,
      OrderList
    },
    data: function () {
      return {
        allCount: 0,
        bottomLoadingShow: false,
        orderLists: [],
        form: {
          startDate: this.$route.query.startDate || this.$moment().subtract(1, 'w').format('YYYY-MM-DD'),
          endDate: this.$route.query.endDate || this.$moment().format('YYYY-MM-DD'),
          od_status: '',
          limit: Number(this.$route.query.limit) || 10,
          offset: Number(this.$route.query.offset) || 0
        },
        dateRange: this.$route.query.dateRange || '7',
        status: [
          {
            name: '전체',
            key: 'all',
            value: ''
          },
          {
            name: '주문접수',
            key: 'deposit_wait',
            value: 'deposit_wait'
          },
          {
            name: '배송대기',
            key: 'delivery_wait',
            value: 'delivery_wait'
          },
          {
            name: '배송중',
            key: 'shipping',
            value: 'shipping'
          },
          {
            name: '배송완료',
            key: 'delivery_complete',
            value: 'delivery_complete'
          },
          {
            name: '주문완료',
            key: 'order_complete',
            value: 'order_complete'
          }
        ],
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
      this.form.offset = 0
      const res = await this.FetchData()
      this.orderLists = res.orders
      this.form.allCount = res.count
      this.form.offset = res.orders.length
      this.$store.commit('ProgressHide')
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
    },
    methods: {
      async FetchData () {
        const params = this.form
        params.start_date = params.startDate
        params.end_date = params.endDate
        try {
          const res = (await this.$http.get(this.$APIURI + 'order/mypage_list', { params })).data

          if (res.state === 1) {
            return res.query
          }
        } catch (e) {
          console.log(e)
          this.$store.commit('ProgressHide')
        }
      },
      SetRouteParams () {
        this.$router.push({ name: 'mypage-order-history', query: this.form })
      },
      async Submit () {
        this.$store.commit('ProgressShow')

        this.form.offset = 0
        const res = await this.FetchData()
        this.orderLists = res.orders
        this.form.allCount = res.count
        this.form.offset = res.orders.length

        this.SetRouteParams()
        this.$store.commit('ProgressHide')
      },
      SelectedStartDate () {
        this.dateRange = ''
        this.endDateDisableDates.disabledDates.to = new Date(this.form.startDate)
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
        let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight

        if (bottomOfWindow && this.form.allCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true

          const res = await this.FetchData()
          this.orderLists.concat(res.orders)
          this.form.offset += res.orders.length

          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
