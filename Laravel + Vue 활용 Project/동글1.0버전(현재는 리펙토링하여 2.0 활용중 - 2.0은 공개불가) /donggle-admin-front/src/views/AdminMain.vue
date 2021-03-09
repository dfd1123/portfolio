<template>
  <div id="app">
    <layout-header
      title="스토어 관리"
      :title-mobile="null"
    />

    <!-- contents -->
    <div
      id="admin-container"
      class="main-container"
    >
      <div class="wrapper">
        <div id="page-main-wrap">
          <main-menu-mobile />

          <div class="grid-line-group first">
            <div class="grid-col grid-col-01">
              <!-- 모바일 타이틀 -->
              <h3 class="mobile-page-title show-mobile">스토어 관리</h3>
              <!-- END 모바일 타이틀 -->

              <!-- 주문 현황 -->
              <div class="panel-default-container panel-con-01">
                <div class="panel-default-title">
                  <h4 class="in-mainname">주문 현황</h4>
                </div>
                <div class="panel-default">
                  <div class="main-graph-area">
                    <line-chart
                      ref="lineChart"
                      :chart-data="chartData"
                      :options="chartOptions"
                    />
                  </div>
                </div>
              </div>
              <!-- END 주문 현황 -->
            </div>

            <!-- 금일 주문 현황 -->
            <div class="grid-col grid-col-02">
              <div class="panel-default-container panel-con-02">
                <div class="panel-default-title">
                  <h4 class="in-mainname">금일 주문 현황</h4>
                </div>
                <div class="panel-default-content">
                  <div class="main-info-panel-card">
                    <ul class="inner">
                      <li class="info-list">
                        <div class="in-title">
                          <img
                            src="/images/icon/icon_02.svg"
                            alt="icon"
                          />
                          <span>주문수</span>
                        </div>
                        <div
                          v-if="isOverviewLoaded"
                          class="in-content"
                        >
                          <p>
                            <b>{{Number(todayOrderCount).toLocaleString()}}</b>
                            <span>건</span>
                          </p>
                        </div>
                      </li>
                      <li class="info-list">
                        <div class="in-title">
                          <img
                            src="/images/icon/icon_03.svg"
                            alt="icon"
                          />
                          <span>취소/환불</span>
                        </div>
                        <div
                          v-if="isOverviewLoaded"
                          class="in-content"
                        >
                          <p>
                            <b>{{Number(todayCancelCount).toLocaleString()}}</b>
                            <span>건</span>
                          </p>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="panel-default-containe panel-con-03">
                <div class="panel-default-title">
                  <h4 class="in-mainname">이번달 매출현황</h4>
                </div>
                <div class="panel-default-content">
                  <div class="main-info-panel-card">
                    <ul class="inner">
                      <li class="info-list">
                        <div class="in-title">
                          <img
                            src="/images/icon/icon_02.svg"
                            alt="icon"
                          />
                          <span>주문수</span>
                        </div>
                        <div
                          v-if="isOverviewLoaded"
                          class="in-content"
                        >
                          <p>
                            <b>{{Number(monthOrderCount).toLocaleString()}}</b>
                            <span>건</span>
                          </p>
                          <p class="in-text-right plus">
                            <b>{{Number(monthOrderPrice).toLocaleString()}}</b>
                            <span>원</span>
                          </p>
                        </div>
                      </li>
                      <li class="info-list">
                        <div class="in-title">
                          <img
                            src="/images/icon/icon_03.svg"
                            alt="icon"
                          />
                          <span>취소/환불</span>
                        </div>
                        <div
                          v-if="isOverviewLoaded"
                          class="in-content"
                        >
                          <p>
                            <b>{{Number(monthCancelCount).toLocaleString()}}</b>
                            <span>건</span>
                          </p>
                          <p class="in-text-right minus">
                            <b>{{Number(monthCancelPrice).toLocaleString()}}</b>
                            <span>원</span>
                          </p>
                        </div>
                      </li>
                      <li class="info-list">
                        <div class="in-title">
                          <img
                            src="/images/icon/icon_profit.svg"
                            alt="icon"
                          />
                          <span>예상매출</span>
                        </div>
                        <div
                          v-if="isOverviewLoaded"
                          class="in-content"
                        >
                          <p>
                            <b>{{Number(monthEstimateCount).toLocaleString()}}</b>
                            <span>건</span>
                          </p>
                          <p class="in-text-right">
                            <b>{{Number(monthEstimatePrice).toLocaleString()}}</b>
                            <span>원</span>
                          </p>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <!-- END 금일 주문 현황 -->
          </div>

          <div class="grid-line-group second">
            <!-- 스토어 문의 -->
            <div class="grid-col grid-col-03">
              <div class="panel-default-container panel-con-04">
                <div class="panel-default-title">
                  <h4 class="in-mainname">스토어 문의</h4>

                  <a
                    href="#"
                    class="in-more"
                    @click.prevent="$router.push('/inquiry-store-list')"
                  >
                    <span>더보기</span>
                    <img
                      src="/images/btn/btn_more.svg"
                      alt="btn"
                    />
                  </a>
                </div>
                <div
                  v-show="isInquiryStoreLoaded"
                  class="panel-default-content"
                >
                  <template v-if="(storeQnaList || []).length > 0">
                    <div
                      v-for="storeQna in storeQnaList"
                      :key="storeQna.id"
                      class="main-mini-panel-card"
                      style="cursor:pointer;"
                      @click="$router.push(`/inquiry-store-detail/${storeQna.id}`)"
                    >
                      <div class="inner">
                        <span class="in-subtit">{{storeQna.subject}}</span>
                        <p class="in-maintit">{{storeQna.question}}</p>
                        <template v-if="Boolean(storeQna.status)">
                          <span class="in-status complt">답변완료</span>
                          <span class="in-datetime">{{getDateFromNowLocalString(storeQna.a_datetime)}}</span>
                        </template>
                        <template v-else>
                          <span class="in-status">답변대기</span>
                          <span class="in-datetime">{{getDateFromNowLocalString(storeQna.q_datetime)}}</span>
                        </template>
                      </div>
                    </div>
                  </template>
                  <template v-else>
                    <div class="main-mini-panel-card">
                      <div class="nothing-history">
                        <img
                          src="/images/icon/empty_board_m.svg"
                          alt="icon empty"
                          class="in-empty-icon"
                        />
                        <span class="in-empty-ment">내역이 없습니다.</span>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
            <!-- END 스토어 문의 -->

            <!-- 상품 문의 -->
            <div class="grid-col grid-col-04">
              <div class="panel-default-container panel-con-05">
                <div class="panel-default-title">
                  <h4 class="in-mainname">상품 문의</h4>

                  <a
                    href="#"
                    class="in-more"
                    @click.prevent="$router.push('/inquiry-product-list')"
                  >
                    <span>더보기</span>
                    <img
                      src="/images/btn/btn_more.svg"
                      alt="btn"
                    />
                  </a>
                </div>
                <div
                  v-show="isInquiryProductLoaded"
                  class="panel-default-content"
                >
                  <template v-if="(itemQnaList || []).length > 0">
                    <div
                      v-for="itemQna in itemQnaList"
                      :key="itemQna.id"
                      class="main-mini-panel-card type-thumb"
                      style="cursor:pointer;"
                      @click="$router.push(`/inquiry-product-detail/${itemQna.id}`)"
                    >
                      <div class="inner">
                        <figure class="in-thumb">
                          <img
                            :src="storagePath(_get(JSON.parse(itemQna.images), 0, null) || 'images/img/product.jpg')"
                            alt="product image"
                          />
                        </figure>
                        <span class="in-subtit">{{itemQna.subject}}</span>
                        <p class="in-maintit">{{itemQna.title}}</p>
                        <template v-if="Boolean(itemQna.status)">
                          <span class="in-status complt">답변완료</span>
                          <span class="in-datetime">{{getDateFromNowLocalString(itemQna.a_datetime)}}</span>
                        </template>
                        <template v-else>
                          <span class="in-status">답변대기</span>
                          <span class="in-datetime">{{getDateFromNowLocalString(itemQna.q_datetime)}}</span>
                        </template>
                      </div>
                    </div>
                  </template>
                  <template v-else>
                    <div class="main-mini-panel-card">
                      <div class="nothing-history">
                        <img
                          src="/images/icon/empty_board_m.svg"
                          alt="icon empty"
                          class="in-empty-icon"
                        />
                        <span class="in-empty-ment">내역이 없습니다.</span>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
            <!-- END 상품 문의 -->

            <!-- 구매후기 -->
            <div class="grid-col grid-col-05">
              <div class="panel-default-container panel-con-06">
                <div class="panel-default-title">
                  <h4 class="in-mainname">구매후기</h4>

                  <a
                    href="#"
                    class="in-more"
                    @click.prevent="$router.push('/manage-review')"
                  >
                    <span>더보기</span>
                    <img
                      src="/images/btn/btn_more.svg"
                      alt="btn"
                    />
                  </a>
                </div>
                <div
                  v-show="isManageReviewLoaded"
                  class="panel-default-content"
                >
                  <template v-if="(reviewList || []).length > 0">
                    <div
                      v-for="review in reviewList"
                      :key="review.id"
                      class="main-mini-panel-card type-thumb type-review"
                      style="cursor:pointer;"
                      @click="goToPage(review)"
                    >
                      <div class="inner">
                        <figure class="in-thumb">
                          <img
                            :src="storagePath(_get(JSON.parse(review.images), 0, null) || 'images/img/product.jpg')"
                            alt="product image"
                          />
                        </figure>
                        <!-- <span class="in-subtit">{{review.review_title}}</span> -->
                        <p class="in-maintit">{{review.review_body.slice(0, 50)}}</p>
                        <div
                          class="star-group"
                          :class="`star-${String(Math.floor(Number(review.rating))).padStart(2, '0')}`"
                        >
                          <i class="_img"></i>
                          <span class="_rate">{{Number(review.rating).toFixed(1)}}</span>
                        </div>
                        <span class="in-datetime">{{getDateFromNowLocalString(review.created_at)}}</span>
                      </div>
                    </div>
                  </template>
                  <template v-else>
                    <div class="main-mini-panel-card">
                      <div class="nothing-history">
                        <img
                          src="/images/icon/empty_board_m.svg"
                          alt="icon empty"
                          class="in-empty-icon"
                        />
                        <span class="in-empty-ment">내역이 없습니다.</span>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
            <!-- END 구매후기 -->
          </div>
        </div>
      </div>

      <layout-footer />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'AdminMain',
  data () {
    return {
      isChartLoaded: false,
      isOverviewLoaded: false,
      isInquiryStoreLoaded: false,
      isInquiryProductLoaded: false,
      isManageReviewLoaded: false,
      chartData: {
        labels: this.getDateRange().map(m => m.format('MM.DD(dd)')),
        datasets: [{
          label: '주문',
          data: [],
          backgroundColor: 'rgba(161, 52, 232, 0.2)',
          pointBorderColor: 'rgba(161, 52, 232, 1)',
          pointBackgroundColor: 'rgba(255, 255, 255, 1)',
          borderColor: 'rgba(161, 52, 232, 1)',
          borderWidth: 1
        }, {
          label: '취소/환불',
          data: [],
          backgroundColor: 'rgba(252, 78, 173, 0.2)',
          pointBorderColor: 'rgba(252, 78, 173, 1)',
          pointBackgroundColor: 'rgba(255, 255, 255, 1)',
          borderColor: 'rgba(252, 78, 173, 1)',
          borderWidth: 1
        }]
      },
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
          mode: 'index',
          intersect: false,
          titleAlign: 'center',
          backgroundColor: 'rgba(0, 0, 0, 0.6)',
          titleMarginBottom: 18,
          xPadding: 10,
          yPadding: 16,
          bodyFontSize: 11
        },
        elements: {
          line: {
            tension: 0
          }
        },
        scales: {
          xAxis: [{
          }],
          yAxes: [{
            ticks: {
              beginAtZero: true,
              stepSize: 3,
              userCallback: function (label, index, labels) {
                if (Math.floor(label) === label) {
                  return label
                }
              }
            }
          }]
        },
        legend: {
          align: 'end',
          labels: {
            usePointStyle: true,
            boxWidth: 8
          }
        }
      },
      todayOrderCount: 0,
      todayCancelCount: 0,
      monthOrderCount: 0,
      monthOrderPrice: 0,
      monthCancelCount: 0,
      monthCancelPrice: 0,
      monthEstimatePrice: 0,
      monthEstimateCount: 0,
      storeQnaList: null,
      itemQnaList: null,
      reviewList: null
    }
  },
  created () {
    this.fetchData()
  },
  computed: {
    ...mapGetters([
      'store'
    ])
  },
  methods: {
    ...mapActions([
      'getStore',
      'getNotification'
    ]),
    async fetchData () {
      try {
        this.getNotification()

        this.$axios
          .get('/api/store/order/main', { params: {} })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(data => {
            this.updateChart(data)
            this.updateOverview(data)
          })

        this.$axios
          .get('/api/store/store_qna/list_main', { params: { limit: 5 } })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(data => {
            this.storeQnaList = data.store_qna
            this.isInquiryStoreLoaded = true
          })

        this.$axios
          .get('/api/store/item_qna/list_main', { params: { limit: 5 } })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(data => {
            this.itemQnaList = data.item_qna
            this.isInquiryProductLoaded = true
          })

        this.$axios
          .get('/api/store/review/list_main', { params: { limit: 5 } })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(data => {
            this.reviewList = data.review
            this.isManageReviewLoaded = true
          })
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      }
    },
    updateChart (data) {
      this.$nextTick(() => {
        const range = this.getDateRange()

        // 기간
        this._set(this, 'chart.data.labels', range.map(m => m.format('MM-DD (dd)')))

        // 주문 데이터
        this._set(this, 'chartData.datasets[0].data', range
          .map(m => m.format('YYYY.MM.DD(dd)'))
          .map(x => {
            const found = data.graph_order.find(t => t.date === x)
            return found ? found.order_count || 0 : 0
          }))

        // 취소/환불 데이터
        this._set(this, 'chartData.datasets[1].data', range
          .map(m => m.format('YYYY.MM.DD(dd)'))
          .map(x => {
            const found = data.graph_cancel.find(t => t.date === x)
            return found ? found.order_count || 0 : 0
          }))

        this.isChartLoaded = true

        // 차트 업데이트
        if (this._get(this, '$refs.lineChart.renderChart', null)) {
          this.$refs.lineChart.renderChart(this.chartData, this.chartOptions)
        }
      })
    },
    updateOverview (data) {
      // 금일 주문 현황
      this.todayOrderCount = this._get(this._last(data.graph_order), 'order_count', 0)
      this.todayCancelCount = this._get(this._last(data.graph_cancel), 'cancel_count', 0)

      // 월 주문 현황
      this.monthOrderCount = data.month_order_count
      this.monthOrderPrice = data.month_order_price

      // 월 취소 현황
      this.monthCancelCount = data.month_cancel_count
      this.monthCancelPrice = data.month_cancel_price

      // 월 예상 현황
      this.monthEstimateCount = data.month_order_count - data.month_cancel_count
      this.monthEstimatePrice = Math.round(Number(data.month_order_price) - Number(data.month_cancel_price))

      this.isOverviewLoaded = true
    },
    getDateRange () {
      const start = this.$moment().subtract(7, 'days')
      const end = this.$moment()
      return Array.from(this.$momentRange(start, end).by('days'))
    },
    getDateFromNowLocalString (dateString) {
      if (this.$moment(dateString).isValid()) {
        return this.$moment(dateString).fromNow()
      }

      return ''
    },
    goToPage (row) {
      if (process.env.VUE_APP_ENV !== 'LOCAL') {
        this.NativePopup(process.env.VUE_APP_PRODC_URI.replace('store.', '') + '/review/' + row.item_id + '/view/' + row.id)
      } else {
        this.NativePopup('http://localhost:8080/review/' + row.item_id + '/view/' + row.id)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  .main-graph-area {
    background-color: initial;
  }

  .main-mini-panel-card.type-review {
    height: 80px;

    .star-group {
      top: unset;
      bottom: 0;
    }

    .in-datetime {
      top: 0;
      right: 0;
    }
  }
</style>
