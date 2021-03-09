<template>
  <div id="app">
    <layout-header title="상품문의" />

    <!-- contents -->
    <div id="admin-container">
      <div class="wrapper">
        <!-- 모바일에서 .fixed_top 를 주면 탭버튼과 셀렉트들이 탑에 고정됨 -->
        <div id="page-inquiry-prdt-list-wrap" class="check-fixed-top-mobile">
          <div class="grid-line-group">
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
                        title="문의일자"
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

                <table-count-bar :count="pagination.count" />

                <div class="show-pc">
                  <table-data-grid
                    row-index="id"
                    :is-show-more-button-visible="pagination.hasNext"
                    :table-classes="['inquiry-p-tbl']"
                    :selected-ready-button-list="['선택한 문의 삭제']"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    @selected-ready-button-click="selectedReadyButtonClick"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:colgroups>
                      <col class="_chck" />
                      <col class="_name" />
                      <col class="_title" />
                      <col class="_answer" />
                      <col class="_user" />
                      <col class="_date" />
                      <col class="_a_date" />
                      <col class="_view" />
                    </template>
                    <template v-slot:headers>
                      <tr class="in-tr">
                        <th>선택</th>
                        <th>상품명</th>
                        <th>문의제목</th>
                        <th>답변상태</th>
                        <th>문의자</th>
                        <th>문의 날짜</th>
                        <th>답변 날짜</th>
                        <th>보기</th>
                      </tr>
                    </template>
                    <template v-slot:rows="rows">
                      <tr v-for="(row, index) in rows.list" :key="row[rows.index]" class="in-tr">
                        <td>
                          <input
                            v-uniq-id="`td-checkbox-${index}`"
                            type="checkbox"
                            class="chck-box none"
                            v-model="rows.checkedList"
                            :value="row[rows.index]"
                            @change="rows.check"
                          />
                          <label v-uniq-for="`td-checkbox-${index}`" class="check-gradi-circle"></label>
                        </td>
                        <td>
                          <div class="date-product-md">
                            <figure class="in-thumb">
                              <img
                                :src="storagePath(_get(JSON.parse(row.images), 0, null) || 'images/img/product.jpg')"
                                alt="product image"
                              />
                            </figure>
                            <span class="in-word">{{row.title || '-'}}</span>
                          </div>
                        </td>
                        <td>{{row.subject || '-'}}</td>
                        <td>{{{0: '답변대기', 1:'답변완료'}[row.status] || '-'}}</td>
                        <td>{{row.q_name || '-'}}</td>
                        <td>
                          <i>{{_get((row.q_datetime || '').split(' '), 0) || '-'}}</i>
                        </td>
                        <td>
                          <i>{{_get((row.a_datetime || '').split(' '), 0) || '-'}}</i>
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
                    row-index="id"
                    :is-show-more-button-visible="pagination.hasNext"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    :selected-ready-button-list="['문의삭제']"
                    @selected-ready-button-click="selectedReadyButtonClick"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:rows="rows">
                      <ul
                        class="inquiry-list-wrap"
                        :style="{display: rows.list === null ? 'none' : ''}"
                      >
                        <li
                          v-for="(row, index) in rows.list"
                          :key="row[rows.index]"
                          class="in-inquiry"
                        >
                          <div class="inquiry-list-choice">
                            <input
                              v-uniq-id="`td-checkbox-mobile-${index}`"
                              type="checkbox"
                              class="chck-box none"
                              v-model="rows.checkedList"
                              :value="row[rows.index]"
                              @change="rows.check"
                            />
                            <label
                              v-uniq-for="`td-checkbox-mobile-${index}`"
                              class="check-gradi-circle"
                            ></label>
                          </div>

                          <!-- 링크영역 -->
                          <a
                            href="#"
                            class="inquiry-list-link"
                            @click.prevent="rowShowButtonClick({...row})"
                          >
                            <div class="clearfix">
                              <!-- 답변이 없으면 _badge 에 ._noanswer -->
                              <div
                                class="rounded-square-btn btn-theme _badge"
                                :class="{_noanswer: row.status === 0}"
                              >{{row.status === 0 ? '답변대기' : '답변완료'}}</div>
                              <div class="_pd_name">{{row.title}}</div>
                            </div>
                            <div class="_tit">{{row.subject}}</div>
                            <ul class="_inquiry_date">
                              <li class="_user_name">{{row.q_name}}</li>
                              <li class="_q_date">
                                문의
                                <span>{{_get((row.q_datetime || '').split(' '), 0) || '-'}}</span>
                              </li>
                              <li class="_a_date">
                                답변
                                <span>{{_get((row.a_datetime || '').split(' '), 0) || '-'}}</span>
                              </li>
                            </ul>
                          </a>
                          <!-- 링크영역 E -->
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
                          title="문의일자"
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
export default {
  name: 'InquiryProductList',
  data () {
    return {
      popup: {
        isVisible: false
      },
      search: {
        keyword: '',
        select: 'subject',
        options: [
          {
            label: '문의제목',
            value: 'subject'
          },
          {
            label: '문의자',
            value: 'q_name'
          },
          {
            label: '상품명',
            value: 'title'
          }
        ],
        savedQueryString: null
      },
      filter: {
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
  created () {
    this.fetchData()
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
          start_date: this.filter.date.start,
          end_date: this.filter.date.end,
          limit: this.pagination.limit,
          page: this.pagination.page
        }

        const data = await this.$axios
          .get('/api/store/item_qna/store_qa', {
            params: {
              ...params,
              searchSelect: this.search.keyword ? this.search.select : null
            }
          })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(this.updateCursor)

        this.table.rows = (this.table.rows || []).concat(data.item_qna)
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
      if (row.id) {
        this.$router.push({
          name: 'inquiry-product-detail',
          params: { id: row.id }
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
    },
    async selectedReadyButtonClick (rows, index) {
      try {
        this.loading(true)

        if (!rows || (Array.isArray(rows) && rows.length === 0)) {
          return
        }

        const { formData, headers } = this.formDatas({
          _method: 'delete',
          id: rows.map(x => x.id)
        })

        await this.$axios
          .post('/api/store/item_qna/delete', formData, { headers })
          .then(this.normalOrError)
          .then(this.alertIfMessage)

        this.table.rows = []
        this.resetCursor()
        await this.searchClick()
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    }
  }
}
</script>
