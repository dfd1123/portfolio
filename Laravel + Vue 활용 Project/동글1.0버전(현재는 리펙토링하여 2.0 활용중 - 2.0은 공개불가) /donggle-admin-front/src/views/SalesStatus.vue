<template>
  <div id="app">
    <layout-header :title="`${selectedTypeOption.label} 매출현황`">
      <template v-slot:title-message-before>
        <b
          style="margin-right: 10px"
        >{{`${filter.date.start}${filter.date.end ? ` ~ ${filter.date.end}` : ''} `}}</b>
      </template>
    </layout-header>

    <!-- contents -->
    <div id="admin-container">
      <div class="wrapper">
        <!-- 모바일에서 .fixed_top 를 주면 탭버튼과 셀렉트들이 탑에 고정됨 -->
        <div id="page-sales-status-wrap" class="check-fixed-top-mobile">
          <div class="grid-line-group fix_type-03">
            <div class="panel-default">
              <div class="table-area">
                <div class="show-pc">
                  <h5 class="search-title">필터검색</h5>
                  <div class="search-option-bar">
                    <select v-model="filter.search.select" class="form-select">
                      <option
                        v-for="option in filter.search.options"
                        :value="option.value"
                        :key="option.value"
                      >{{option.label}}</option>
                    </select>
                    <div class="search-option-period">
                      <table-filter-date
                        title="기간입력"
                        :is-range="filter.search.select !== 0"
                        :start.sync="filter.date.start"
                        :end.sync="filter.date.end"
                        :type="{0: 'date', 1: 'date', 2: 'month', 3: 'year'}[filter.search.select]"
                      />

                      <table-filter-button @click="searchButtonClick" />
                    </div>
                  </div>
                </div>

                <div class="show-pc">
                  <table-data-grid
                    row-index="order_id"
                    :is-check-all-visible="false"
                    :is-show-more-button-visible="filter.search.select !== 0 && pagination.hasNext"
                    :table-classes="['sale-stat-tbl']"
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
                      <col class="_same" />
                      <col class="_same" />
                      <col class="_same" />
                      <col class="_same" />
                      <col class="_same" />
                      <col class="_same" />
                      <col class="_same" />
                    </template>
                    <template v-slot:headers>
                      <tr class="in-tr">
                        <th>주문{{selectedTypeOption.tag1}}</th>
                        <template v-if="filter.search.select === 0">
                          <th>주문자</th>
                        </template>
                        <template v-else>
                          <th>주문수</th>
                        </template>
                        <!--<th>주문합계</th>-->
                        <th>취소/환불</th>
                        <!--<th>미수금</th>-->
                        <th>
                          <span>매출액</span>
                          <b class="etc">(단가+부가세+수수료)</b>
                        </th>
                        <th>중개 수수료</th>
                        <th>
                          <span>정산액</span>
                          <b class="etc">(단가+부가세)</b>
                        </th>
                      </tr>
                    </template>
                    <template v-slot:rows="rows">
                      <tr v-for="(row, index) in rows.list" :key="index" class="in-tr">
                        <template v-if="filter.search.select === 0">
                          <td>
                            <i>{{row.order_id || '-'}}</i>
                          </td>
                          <td>
                            <i>{{row.s_name || '-'}}</i>
                          </td>
                          <td class="ta-right">
                            <i>{{row.cancel_price ? Number(row.cancel_price).toLocaleString() : 0}}</i>
                          </td>
                          <td class="ta-right">
                            <i>{{row.this_price ? Number(row.this_price).toLocaleString() : 0}}</i>
                          </td>
                          <td class="ta-right">
                            <i>{{row.fee_price ? Number(row.fee_price).toLocaleString() : 0}}</i>
                          </td>
                          <td class="ta-right">
                            <i>{{Number(Number(row.this_price) + Number(row.fee_price)).toLocaleString()}}</i>
                          </td>
                        </template>
                        <template v-else>
                          <td>
                            <i>{{row.date || '-'}}</i>
                          </td>
                          <td>
                            <i>{{row.od_count || 0}}</i>
                          </td>
                          <td class="ta-right">
                            <i>{{row.cancel_price ? Number(row.cancel_price).toLocaleString() : 0}}</i>
                          </td>
                          <td class="ta-right">
                            <i>{{row.price ? Number(row.price).toLocaleString() : 0}}</i>
                          </td>
                          <td class="ta-right">
                            <i>{{row.fee_price ? Number(row.fee_price).toLocaleString() : 0}}</i>
                          </td>
                          <td class="ta-right">
                            <i>{{Number(Number(row.price) + Number(row.fee_price)).toLocaleString()}}</i>
                          </td>
                        </template>
                      </tr>
                    </template>
                    <template v-slot:footers="rows">
                      <template v-if="filter.search.select === 0">
                        <td>합계</td>
                        <td></td>
                        <td class="ta-right">
                          <i>{{Number(rows.list.map(x => Number(x.cancel_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</i>
                        </td>
                        <td class="ta-right">
                          <i>{{Number(rows.list.map(x => Number(x.this_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</i>
                        </td>
                        <td class="ta-right">
                          <i>{{Number(rows.list.map(x => Number(x.fee_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</i>
                        </td>
                        <td class="ta-right">
                          <i>{{Number(rows.list.map(x => Number(x.this_price) - Number(x.fee_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</i>
                        </td>
                      </template>

                      <template v-else>
                        <td>합계</td>
                        <td>{{rows.list.map(x => Number(x.od_count)).reduce((a, b) => a + b, 0)}}</td>
                        <td class="ta-right">
                          <i>{{Number(rows.list.map(x => Number(x.cancel_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</i>
                        </td>
                        <td class="ta-right">
                          <i>{{Number(rows.list.map(x => Number(x.price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</i>
                        </td>
                        <td class="ta-right">
                          <i>{{Number(rows.list.map(x => Number(x.fee_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</i>
                        </td>
                        <td class="ta-right">
                          <i>{{Number(rows.list.map(x => (Number(x.price) - Number(x.fee_price))).reduce((a, b) => a + b, 0)).toLocaleString()}}</i>
                        </td>
                      </template>
                    </template>
                  </table-data-grid>
                </div>

                <div class="show-mobile">
                  <div class="list-total-wrapper">
                    <div class="list-total-wrap">
                      <h2 class="list-total-title">
                        <span>합계</span>
                      </h2>
                      <div class="tbl-list-wrapper">
                        <template v-if="filter.search.select === 0">
                          <ul class="sales-status">
                            <li class="clearfix">
                              <span class="in-tit">취소/환불</span>
                              <span
                                class="in-desc"
                              >{{Number(table.rows.map(x => Number(x.cancel_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">
                                매출액
                                <small>(단가+부가세+수수료)</small>
                              </span>
                              <span
                                class="in-desc"
                              >{{Number(table.rows.map(x => Number(x.this_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">중개 수수료</span>
                              <span
                                class="in-desc"
                              >{{Number(table.rows.map(x => Number(x.fee_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">
                                정산액
                                <small>(단가+부가세)</small>
                              </span>
                              <span
                                class="in-desc"
                              >{{Number(table.rows.map(x => Number(x.this_price) - Number(x.fee_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                            </li>
                          </ul>
                        </template>
                        <template v-else>
                          <ul class="sales-status">
                            <li class="clearfix">
                              <span class="in-tit">주문수</span>
                              <span
                                class="in-desc"
                              >{{table.rows.map(x => Number(x.od_count)).reduce((a, b) => a + b, 0)}}</span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">취소/환불</span>
                              <span
                                class="in-desc"
                              >{{Number(table.rows.map(x => Number(x.cancel_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">
                                매출액
                                <small>(단가+부가세+수수료)</small>
                              </span>
                              <span
                                class="in-desc"
                              >{{Number(table.rows.map(x => Number(x.price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">중개 수수료</span>
                              <span
                                class="in-desc"
                              >{{Number(table.rows.map(x => Number(x.fee_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">
                                정산액
                                <small>(단가+부가세)</small>
                              </span>
                              <span
                                class="in-desc"
                              >{{Number(table.rows.map(x => Number(x.price) - Number(x.fee_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                            </li>
                          </ul>
                        </template>
                      </div>
                    </div>
                  </div>

                  <div class="filter-search-wrapper">
                    <div class="dg-filter-search_wrap">
                      <h2>필터검색</h2>
                      <button class="dg-filter-search-btn" @click="popup.isVisible = true"></button>
                    </div>
                  </div>
                </div>

                <div class="show-mobile">
                  <table-data-grid-mobile
                    row-index="order_id"
                    :is-check-all-visible="false"
                    :is-show-more-button-visible="filter.search.select !== 0 && pagination.hasNext"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:rows="rows">
                      <ul
                        class="sales-list-wrap"
                        :style="{display: rows.list === null ? 'none' : ''}"
                      >
                        <li v-for="(row, index) in rows.list" :key="index" class="sales-list">
                          <template v-if="filter.search.select === 0">
                            <ul class="sales-status">
                              <li class="clearfix">
                                <span class="in-tit in-od_num">{{row.order_id || '-'}}</span>
                                <span class="in-desc in-name">{{row.s_name || '-'}}</span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">취소/환불</span>
                                <span
                                  class="in-desc"
                                >{{row.cancel_price ? Number(row.cancel_price).toLocaleString() : 0}}</span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">매출액</span>
                                <span
                                  class="in-desc"
                                >{{row.this_price ? Number(row.this_price).toLocaleString() : 0}}</span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">중개 수수료</span>
                                <span
                                  class="in-desc"
                                >{{row.fee_price ? Number(row.fee_price).toLocaleString() : 0}}</span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">정산액</span>
                                <span class="in-desc">
                                  <span
                                    class="_pink"
                                  >{{Number(Number(row.this_price) + Number(row.fee_price)).toLocaleString()}}</span>
                                </span>
                              </li>
                            </ul>
                          </template>
                          <template v-else>
                            <ul class="sales-status">
                              <li class="clearfix">
                                <span class="in-tit in-od_num">주문{{selectedTypeOption.tag1}}</span>
                                <span class="in-desc in-name">{{row.date || '-'}}</span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">취소/환불</span>
                                <span
                                  class="in-desc"
                                >{{row.cancel_price ? Number(row.cancel_price).toLocaleString() : 0}}</span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">매출액</span>
                                <span
                                  class="in-desc"
                                >{{Number(rows.list.map(x => Number(x.price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">중개 수수료</span>
                                <span
                                  class="in-desc"
                                >{{Number(rows.list.map(x => Number(x.fee_price)).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                              </li>
                              <li class="clearfix">
                                <span class="in-tit">정산액</span>
                                <span class="in-desc">
                                  <span
                                    class="_pink"
                                  >{{Number(rows.list.map(x => (Number(x.price) - Number(x.fee_price))).reduce((a, b) => a + b, 0)).toLocaleString()}}</span>
                                </span>
                              </li>
                            </ul>
                          </template>
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
                        <section class="filter-select_date">
                          <h3 class="none">기간선택</h3>
                          <div class="filter_selec_wrap">
                            <select v-model="filter.search.select" class="form-select">
                              <option
                                v-for="option in filter.search.options"
                                :value="option.value"
                                :key="option.value"
                              >{{option.label}}</option>
                            </select>
                          </div>
                        </section>
                        <!-- 기간검색 E -->

                        <!-- 기간입력 : 공통 -->
                        <table-filter-date-mobile
                          title="기간입력"
                          :is-range="filter.search.select !== 0"
                          :start.sync="filter.date.start"
                          :end.sync="filter.date.end"
                          :type="{0: 'date', 1: 'date', 2: 'month', 3: 'year'}[filter.search.select]"
                        />
                        <!-- 기간입력 E -->
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
export default {
  name: 'SalesStatus',
  data () {
    return {
      popup: {
        isVisible: false
      },
      filter: {
        search: {
          select: 0,
          options: [
            {
              label: '일일',
              tag1: '번호',
              value: 0
            },
            {
              label: '일간',
              tag1: '일',
              value: 1
            },
            {
              label: '월간',
              tag1: '월',
              value: 2
            },
            {
              label: '연간',
              tag1: '년도',
              value: 3
            }
          ]
        },
        date: {
          start: '',
          end: ''
        }
      },
      table: {
        rows: [], // null,
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
  computed: {
    selectedTypeOption () {
      return this.filter.search.options[this.filter.search.select] || {}
    }
  },
  watch: {
    'filter.search.select': function () {
      this.table.rows = []
      this.resetCursor()
    }
  },
  methods: {
    async fetchData () {
    },
    async searchClick () {
      try {
        this.loading(true)

        switch (this.filter.search.select) {
          case 0:
            await this.searchDaily()
            break
          case 1:
            await this.searchDates()
            break
          case 2:
            await this.searchMonth()
            break
          case 3:
            await this.searchYear()
            break
        }
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async searchDaily () {
      const params = {
        date: this.filter.date.start
      }

      const data = await this.$axios
        .get('/api/store/calculate/daily', { params })
        .then(this.normalOrError)
        .then(this.resultOrError)

      this.table.rows = data
    },
    async searchDates () {
      const params = {
        orderBy: this.table.orderBySelect,
        start_date: this.filter.date.start,
        end_date: this.filter.date.end,
        limit: this.pagination.limit,
        page: this.pagination.page
      }

      const data = await this.$axios
        .get('/api/store/calculate/date', { params })
        .then(this.normalOrError)
        .then(this.resultOrError)
        .then(this.updateCursor)

      this.table.rows = (this.table.rows || []).concat(data.calculates)
    },
    async searchMonth () {
      const params = {
        orderBy: this.table.orderBySelect,
        start_month: this.filter.date.start,
        end_month: this.filter.date.end,
        limit: this.pagination.limit,
        page: this.pagination.page
      }

      const data = await this.$axios
        .get('/api/store/calculate/month', { params })
        .then(this.normalOrError)
        .then(this.resultOrError)
        .then(this.updateCursor)

      this.table.rows = (this.table.rows || []).concat(data.calculates)
    },
    async searchYear () {
      const params = {
        orderBy: this.table.orderBySelect,
        start_year: this.filter.date.start,
        end_year: this.filter.date.end,
        limit: this.pagination.limit,
        page: this.pagination.page
      }

      const data = await this.$axios
        .get('/api/store/calculate/year', { params })
        .then(this.normalOrError)
        .then(this.resultOrError)
        .then(this.updateCursor)

      this.table.rows = (this.table.rows || []).concat(data.calculates)
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
