<template>
  <div id="dg-mypage-cancel-history-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 주문내역 조회 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-18">
            <h2 class="in-subject">
              취소/환불내역
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- 0. 검색옵션 설정영역 -->
            <div class="dg-filter-inquiry-group mb-23">
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

            <!-- 2. 환불 내역 있을 때 -->
            <div
              v-if="orderLists.length > 0"
              class="l-grid-group"
            >
              <div
                v-for="orderList in orderLists"
                :key="'ordeList'+orderList.order_no"
                class="l-grid-list l-col-1"
              >
                <OrderList
                  :order-list="orderList"
                  @popup-event="CancelPopup"
                />
              </div>
            </div>
            <!-- 2. 환불 내역 있을 떄 E -->
            <!-- 1. 환불 내역 없을 때 -->
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
            <!-- 1. 환불 내역 없을 때 E -->
            <CancelPopup
              v-show="popupShow"
              :item-list="itemList"
              @close-event="CloseCancelPopup"
            />
          </div>
        </article>
      </div>
      <!-- 1) E -->
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
  import CancelPopup from '@/components/popup/cancel-popup.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
      SelectStatus,
      DateRangeComp,
      Datepicker,
      OrderList,
      CancelPopup
    },
    data: function () {
      return {
        allCount: 0,
        bottomLoadingShow: false,
        orderLists: [],
        popupShow: false,
        itemList: {
          order_no: 12,
          order_id: '0012547884',
          item_id: 1234567,
          store_id: 12,
          seller_name: '디어러버윤',
          title: '[모델추천] 팝콘체크 롱코트 (2color)_디어러버윤',
          images: [],
          option_subject: '색상,사이즈',
          option: '빨강,XL',
          qty: 2,
          total_price: 19800,
          coupon_price: 1000,
          level_discount: 1000,
          receipt_price: 17800,
          refund_reason: '그냥 단순 변심!',
          refund_detail: '그냥입니다.',
          reject_reason: '<p>고객님 단순 변심은 환불 사유가 되지 않습니다.<br>죄송합니다~</p>',
          od_status: 'refund_reject',
          created_at: '2019-11-11 00:00:00',
          updated_at: '2019-11-30 00:00:00'
        },
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
            name: '주문취소',
            key: 'order_cancel',
            value: 'order_cancel'
          },
          {
            name: '환불 신청',
            key: 'refund_apply',
            value: 'refund_apply'
          },
          {
            name: '환불 거절',
            key: 'refund_reject',
            value: 'refund_reject'
          },
          {
            name: '환불 완료',
            key: 'refund_complete',
            value: 'refund_complete'
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
          const res = (await this.$http.get(this.$APIURI + 'order/mypage_cancel', { params })).data

          if (res.state === 1) {
            return res.query
          }
        } catch (e) {
          console.log(e)
          this.$store.commit('ProgressHide')
        }
      },
      SetRouteParams () {
        this.$router.push({ name: 'mypage-cancel-history', query: this.form })
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
      CancelPopup (itemList) {
        this.itemList = itemList
        this.popupShow = true
      },
      CloseCancelPopup () {
        this.popupShow = false
        // this.itemList = {}
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
