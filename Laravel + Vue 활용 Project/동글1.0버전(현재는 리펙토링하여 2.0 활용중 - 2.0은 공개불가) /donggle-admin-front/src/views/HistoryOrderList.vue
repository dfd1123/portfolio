<template>
  <div id="app">
    <layout-header title="주문내역 리스트" />

    <!-- contents -->
    <div id="admin-container">
      <div class="wrapper">
        <!-- 모바일에서 .fixed_top 를 주면 탭버튼과 셀렉트들이 탑에 고정됨 -->
        <div id="page-history-order-list-wrap" class="check-fixed-top-mobile">
          <div class="grid-line-group fix_type-02">
            <div class="panel-default">
              <div class="table-area">
                <table-search-bar
                  :select.sync="search.select"
                  :keyword.sync="search.keyword"
                  :options="search.options"
                  @click="searchButtonClick"
                />

                <div class="show-pc">
                  <h5 class="search-title">필터검색</h5>
                  <div class="search-option-bar">
                    <div class="in-divider">
                      <table-filter-status
                        title="주문상태"
                        :is-unselectable="true"
                        :select.sync="filter.status.select"
                        :options="filter.status.options"
                      />
                    </div>

                    <div class="in-divider">
                      <table-filter-status
                        title="주문일자"
                        :select.sync="filter.range.select"
                        :options="filter.range.options"
                        @change="filterRangeChange"
                      />

                      <table-filter-date
                        title="기간입력"
                        :start.sync="filter.date.start"
                        :end.sync="filter.date.end"
                        @change="filterDateChange"
                      />

                      <table-filter-button @click="searchButtonClick" />
                    </div>
                  </div>
                </div>

                <div class="show-mobile">
                  <div class="filter-search-wrapper">
                    <div class="dg-filter-search_wrap">
                      <h2>필터검색</h2>
                      <button class="dg-filter-search-btn" @click="popup.isVisible = true"></button>
                    </div>
                  </div>
                </div>

                <div div class="show-pc">
                  <table-data-grid
                    row-index="order_no"
                    :is-show-more-button-visible="pagination.hasNext"
                    :is-check-all-visible="false"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:status-bar>
                      <table-count-bar :count="pagination.count" />
                    </template>
                    <template v-slot:colgroups>
                      <col class="_order_num" />
                      <col class="_date" />
                      <col class="_stat" />
                      <col class="_num" />
                      <col class="_price" />
                      <col class="_cou_sale" />
                      <col class="_user_sale" />
                      <col class="_total" />
                      <col class="_view" />
                    </template>
                    <template v-slot:headers>
                      <tr class="in-tr">
                        <th>주문번호</th>
                        <th>주문일시</th>
                        <th>주문상태</th>
                        <th>수량</th>
                        <th>상품금액 합계</th>
                        <th>쿠폰 할인금액</th>
                        <th>회원등급 할인금액</th>
                        <th>최종 결제금액</th>
                        <th>상세보기</th>
                      </tr>
                    </template>
                    <template v-slot:rows="rows">
                      <tr v-for="row in rows.list" :key="row[rows.index]" class="in-tr">
                        <td>
                          <i>{{row.order_id}}</i>
                        </td>
                        <td>
                          <i>{{_get((row.receipt_time || '').split(','), 0) || '-'}}</i>
                        </td>
                        <td>{{obStatusList[row.od_status]}}</td>
                        <td>{{row.total_qty ? Number(row.total_qty).toLocaleString() : '-'}}</td>
                        <td class="ta-right">
                          <i>{{Number(row.total_price).toLocaleString()}}</i>원
                        </td>
                        <td class="ta-right">
                          <i>-{{Number(row.coupon_discount).toLocaleString()}}</i>원
                        </td>
                        <td class="ta-right">
                          <i>-{{Number(row.level_discount).toLocaleString()}}</i>원
                        </td>
                        <td class="ta-right">
                          <i>{{Number(row.receipt_price).toLocaleString()}}</i>원
                        </td>
                        <td>
                          <a
                            href="#"
                            class="rounded-square-xs-btn btn-gray"
                            @click.prevent="rowShowButtonClick({...row})"
                          >보기</a>
                        </td>
                      </tr>
                    </template>
                  </table-data-grid>
                </div>

                <div class="show-mobile">
                  <table-data-grid-mobile
                    row-index="order_no"
                    :is-show-more-button-visible="pagination.hasNext"
                    :is-check-all-visible="false"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:status-bar>
                      <table-count-bar :count="pagination.count" />
                    </template>
                    <template v-slot:rows="rows">
                      <ul
                        class="order-list-wrap"
                        :style="{display: rows.list === null ? 'none' : ''}"
                      >
                        <li v-for="row in rows.list" :key="row[rows.index]" class="order-pd-list">
                          <a href="#" @click.prevent="rowShowButtonClick(row)">
                            <div class="order-date clesarfix">
                              <span
                                class="_date"
                              >{{row.receipt_time ? $moment(row.receipt_time).format('MM-DD') : '-'}}</span>
                              <span class="_order_num">{{row.order_id}}</span>
                              <p class="_stat">{{obStatusList[row.od_status]}}</p>
                            </div>
                            <ul class="order-status">
                              <li class="clearfix">
                                <span class="in-tit">수량</span>
                                <span class="in-desc">
                                  <span>{{row.total_qty ? Number(row.total_qty).toLocaleString() : '-'}}</span> 개
                                </span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">상품금액 합계</span>
                                <span class="in-desc">
                                  <span>{{Number(row.total_price).toLocaleString()}}</span> 원
                                </span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">최종 결제금액</span>
                                <span class="in-desc">
                                  <span class="_pink">{{Number(row.receipt_price).toLocaleString()}}</span> 원
                                </span>
                              </li>
                            </ul>
                          </a>
                        </li>
                      </ul>
                    </template>
                  </table-data-grid-mobile>

                  <div
                    class="popup-container"
                    :style="{display: popup.isVisible ? 'block' : 'none'}"
                  >
                    <div class="popup-content-box">
                      <!-- popup header -->
                      <h2 class="filter-popup-title">
                        필터검색
                        <button
                          type="button"
                          class="icon-close-btn"
                          @click="popup.isVisible = false"
                        >닫기</button>
                      </h2>
                      <!-- popup header E -->

                      <!-- popup content -->
                      <div class="filter-popup-content">
                        <table-filter-status-mobile
                          title="주문상태"
                          :is-unselectable="true"
                          :select.sync="filter.status.select"
                          :options="filter.status.options"
                        />

                        <table-filter-status-mobile
                          title="주문일자"
                          :select.sync="filter.range.select"
                          :options="filter.range.options"
                          @change="filterRangeChange"
                        />

                        <table-filter-date-mobile
                          title="기간입력"
                          :start.sync="filter.date.start"
                          :end.sync="filter.date.end"
                          @change="filterDateChange"
                        />
                      </div>
                      <!-- popup content E -->

                      <!-- popup footer -->
                      <div class="icon-search-btn-wrap">
                        <table-filter-button
                          @click="searchButtonClick($event); popup.isVisible = false"
                        />
                      </div>
                      <!-- popup footer E -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <layout-footer />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
import { obStatusList } from '@/constants'

export default {
  name: 'HistoryOrderList',
  data () {
    return {
      popup: {
        isVisible: false
      },
      search: {
        keyword: '',
        select: 'order_id',
        options: [
          {
            label: '주문번호',
            value: 'order_id'
          }
        ],
        savedQueryString: null
      },
      filter: {
        status: {
          select: '',
          options: [
            {
              label: '전체',
              value: ''
            },
            {
              label: '주문신청',
              value: 'order_apply'
            },
            {
              label: '배송대기',
              value: 'delivery_wait'
            },
            {
              label: '배송중',
              value: 'shipping'
            },
            {
              label: '배송완료',
              value: 'delivery_complete'
            },
            {
              label: '구매확정',
              value: 'order_complete'
            }
          ]
        },
        range: {
          select: null,
          options: [
            {
              label: '오늘',
              value: 'today'
            },
            {
              label: '어제',
              value: 'yesterday'
            },
            {
              label: '이번주',
              value: 'this_week'
            },
            {
              label: '지난주',
              value: 'last_week'
            },
            {
              label: '지난달',
              value: 'last_month'
            },
            {
              label: '이번달',
              value: 'this_month'
            }
          ]
        },
        date: {
          start: this.$moment().subtract(1, 'months').format('YYYY-MM-DD'),
          end: this.$moment().format('YYYY-MM-DD')
        }
      },
      table: {
        rows: null,
        rowsChecked: [],
        orderBySelect: 'desc',
        orderByOptions: [
          {
            label: '최신 순',
            value: 'desc'
          },
          {
            label: '등록 순',
            value: 'asc'
          },
          {
            label: '수량 순',
            value: 'qty'
          },
          {
            label: '가격 순',
            value: 'price'
          },
          {
            label: '잔여일 순',
            value: 'remaining'
          },
          {
            label: '결제금액 순',
            value: 'receipt'
          }
        ]
      },
      pagination: {
        hasNext: true,
        limit: 10,
        page: 1,
        count: null
      }
    }
  },
  async created () {
    await this.fetchData()
  },
  computed: {
    obStatusList () {
      return obStatusList
    }
  },
  methods: {
    async fetchData () {
      await this.searchClick()
    },
    async searchClick () {
      try {
        this.loading(true)

        const params = {
          searchSelect: this.search.select,
          searchKeyword: this.search.keyword,
          orderBy: this.table.orderBySelect,
          od_status: this.filter.status.select,
          start_date: this.filter.date.start,
          end_date: this.filter.date.end,
          limit: this.pagination.limit,
          page: this.pagination.page
        }

        const data = await this.$axios
          .get('/api/store/order/list', {
            params: {
              ...params,
              searchSelect: this.search.keyword ? this.search.select : null
            }
          })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(this.updateCursor)

        this.table.rows = (this.table.rows || []).concat(data.orders)
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    filterRangeChange (value) {
      const { start, end } = this.dateNameToDateFormat(value)

      this.filter.date.start = start
      this.filter.date.end = end
    },
    filterDateChange (value) {
      this.filter.range.select = null
    },
    rowShowButtonClick (row) {
      if (row.order_id) {
        this.$router.push({
          name: 'history-order-detail',
          params: { id: row.order_id }
        })
      }
    },
    async searchButtonClick () {
      this.table.rows = []
      this.resetCursor()
      await this.searchClick()
    },
    async orderBySelectChange () {
      this.table.rows = []
      this.resetCursor()
      await this.searchClick()
    },
    async showMoreButtonClick () {
      if (this.moveCursor()) {
        await this.searchClick()
      }
    }
  }
}
</script>
