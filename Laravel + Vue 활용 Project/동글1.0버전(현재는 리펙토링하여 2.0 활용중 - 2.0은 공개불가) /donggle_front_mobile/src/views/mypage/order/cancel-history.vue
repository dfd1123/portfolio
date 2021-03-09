<template>
  <div
    id="dg-mypage-cancel-history-wrapper"
    class="l-ordercancel-wrapper mypage-order-history-wrapper"
  >
    <!-- 1) 주문배송 -->
    <div class="l-con-area full">
      <article class="l-con-article dg-product-list-wrapper">
        <div class="_page_title_wrap">
          <h2>
            주문배송
            <a
              href="#"
              class="_back_btn"
              @click.prevent="$router.go(-1)"
            >뒤로가기</a>
          </h2>
          <div class="second_title clear_both">
            <router-link to="/mypage/order/history">
              주문내역
            </router-link>
            <router-link to="/mypage/cancel/history">
              취소/환불내역
            </router-link>
          </div>
        </div>

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
            id="filter"
            type="checkbox"
            class="filter-search-list_view display_none"
          >
          <label
            for="filter"
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
        <div class="dg-filter-btn-wrap">
          <SelectStatus
            :od-status="form.od_status"
            :status="status"
            @input="form.od_status = $event"
            @submit="Submit"
          />
        </div>
        <!-- 주문 리스트 -->
        <div class="dg-order-list-wrap">
          <ul
            v-for="orderList in orderLists"
            :key="'ordeList'+orderList.order_no"
            class="l-grid-group"
          >
            <li class="l-grid-list l-col-1">
              <!-- * 주문일시, 주문번호 정보라벨 -->
              <ul class="dg-about-odnum-label">
                <li>{{ orderList.created_at || '-' }}</li>
                <li class="_num">
                  {{ orderList.order_id || '-' }}
                </li>
                <li class="_next">
                  <router-link :to="'/order/detail/'+orderList.order_id">
                    주문 상세보기
                  </router-link>
                </li>
              </ul>
              <!-- * 주문일시, 주문번호 정보라벨 E -->
            </li>
            <li class="l-grid-list l-col-1">
              <OrderList
                :item-list="orderList"
                :cancel-nrefund="true"
                @cancel-popup-open="CancelPopup"
              />
            </li>
          </ul>
        </div>
        <!-- 주문 리스트 E -->
        <div
          class="loading_wrap"
          v-show="bottomLoadingShow"
        >
          <Loading />
        </div>
      </article>
    </div>
    <!-- 1) 주문배송 E -->
    <CancelPopup
      :class="{'posFixed':popupShow}"
      :item-list="itemList"
      @close-event="CloseCancelPopup"
    />
  </div>
</template>

<script>
  import SelectStatus from '@/components/mypage/search/select-status.vue'
  import DateRangeComp from '@/components/mypage/search/date-range.vue'
  import Datepicker from '@/components/mypage/search/datepicker.vue'
  import OrderList from '@/components/mypage/order/order-list.vue'
  import CancelPopup from '@/components/popup/cancel-popup.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      SelectStatus,
      DateRangeComp,
      Datepicker,
      OrderList,
      CancelPopup,
      Loading
    },
    data: function () {
      return {
        allCount: 0,
        bottomLoadingShow: false,
        orderLists: [],
        popupShow: false,
        itemList: {},
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
            name: '취소',
            key: 'order_cancel',
            value: 'order_cancel'
          },
          {
            name: '환불',
            key: 'refund',
            value: 'refund'
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
        document.body.style.overflowY = 'hidden'
      },
      CloseCancelPopup () {
        this.popupShow = false
        document.body.style.overflowY = 'auto'
        // this.itemList = {}
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.form.allCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true

          const res = await this.FetchData()
          this.orderLists = this.orderLists.concat(res.orders)
          this.form.offset += res.orders.length

          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
