<template>
  <div id="app">
    <layout-header title="구매후기" />

    <!-- contents -->
    <div id="admin-container">
      <div class="wrapper">
        <!-- 모바일에서 .fixed_top 를 주면 탭버튼과 셀렉트들이 탑에 고정됨 -->
        <div
          id="page-manage-review-wrap"
          class="check-fixed-top-mobile"
        >
          <div class="grid-line-group fix_type-01">
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
                        title="작성기간"
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
                      <button
                        class="dg-filter-search-btn"
                        @click="popup.isVisible = true"
                      ></button>
                    </div>
                  </div>
                </div>

                <div class="show-pc">
                  <table-data-grid
                    row-index="id"
                    :is-show-more-button-visible="pagination.hasNext"
                    :is-check-all-visible="false"
                    :table-classes="['review-tbl']"
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
                      <col class="_num" />
                      <col class="_name" />
                      <col class="_desc" />
                      <col class="_score" />
                      <col class="_user" />
                      <col class="_ip" />
                      <col class="_date" />
                      <col class="_view" />
                    </template>
                    <template v-slot:headers>
                      <tr class="in-tr">
                        <th>No.</th>
                        <th>상품명</th>
                        <th>내용</th>
                        <th>평점</th>
                        <th>작성자</th>
                        <th>작성 IP</th>
                        <th>작성 날짜</th>
                        <th>보기</th>
                      </tr>
                    </template>
                    <template v-slot:rows="rows">
                      <tr
                        v-for="row in rows.list"
                        :key="row[rows.index]"
                        class="in-tr"
                      >
                        <td>
                          <i>{{row.id}}</i>
                        </td>
                        <td class="ta-left">
                          <div class="date-product-md">
                            <figure class="in-thumb">
                              <img
                                :src="_get(JSON.parse(row.image), 0, null)?storagePath(_get(JSON.parse(row.image), 0, null)):'images/img/product.jpg'"
                                alt="product image"
                              />
                            </figure>
                            <span class="in-word">{{row.title || '-'}}</span>
                          </div>
                        </td>
                        <td class="ta-left">
                          <span class="_ellipse">{{row.review_body || '-'}}</span>
                        </td>
                        <td>{{Number(row.rating).toFixed(1) || '-'}}</td>
                        <td>{{row.writer_name || '-'}}</td>
                        <td>
                          <i>{{row.writer_ip || '-'}}</i>
                        </td>
                        <td>
                          <i>{{_get((row.created_at || '').split(' '), 0) || '-'}}</i>
                        </td>
                        <td>
                          <div class="date-double-btn">
                            <a
                              href="#"
                              class="rounded-square-xs-btn btn-gray"
                              @click.prevent="editRequestPopup(row)"
                            >{{{0: '삭제요청', 1: '삭제완료', 2: '처리중'}[row.deleted]}}</a>
                            <router-link
                              :to="'/manage-review-detail/'+row.id"
                              class="rounded-square-xs-btn btn-mint"
                            >보기</router-link>
                          </div>
                        </td>
                      </tr>
                    </template>
                  </table-data-grid>
                </div>

                <div class="show-mobile">
                  <table-data-grid-mobile
                    row-index="id"
                    :is-show-more-button-visible="pagination.hasNext"
                    :is-check-all-visible="false"
                    :is-show-upper-status-bar="false"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    :table-wrapper-classes="['manage-review-wrapper']"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:status-bar>
                      <table-count-bar :count="pagination.count" />
                    </template>
                    <template v-slot:rows="rows">
                      <ul
                        class="manage-review-list-wrap"
                        :style="{display: rows.list === null ? 'none' : ''}"
                      >
                        <li v-for="row in rows.list" :key="row[rows.index]" class="in-review">
                          <a href="#" class="review-list-link" @click.prevent>
                            <div class="clearfix">
                              <div class="_review_num">{{row.id}}</div>
                              <div class="_pd_name">{{row.title || '-'}}</div>
                              <div class="_star_score">
                                <img src="/images/icon/only-one-star.svg" alt="star icon" />
                                <span>{{Number(row.rating).toFixed(1) || '-'}}</span>
                              </div>
                            </div>
                            <div class="_tit">{{row.review_title || '-'}}</div>
                            <ul class="_review_data">
                              <li class="_user_name">{{row.writer_name || '-'}}</li>
                              <li class="_ip_address">
                                IP
                                <span>{{row.writer_ip || '-'}}</span>
                              </li>
                              <li class="_date">
                                작성
                                <span>{{_get((row.created_at || '').split(' '), 0) || '-'}}</span>
                              </li>
                              <div class="manage-pd-list-btn-wrap">
                                <button
                                  type="button"
                                  class="rounded-square-btn btn-gray"
                                  @click="editRequestPopup(row)"
                                >{{{0: '삭제요청', 1: '삭제완료', 2: '삭제요청중'}[row.deleted]}}</button>
                                <button
                                  type="button"
                                  class="rounded-square-btn btn-mint"
                                  @click.prevent="goToPage(row)"
                                >보기</button>
                              </div>
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
                          title="작성기간"
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

                <!-- 구매후기 삭제요청 팝업 -->
                <div
                  class="popup-container bgc-none"
                  :style="{display: popupRequest.isVisible ? 'block' : 'none'}"
                >
                  <div class="popup-bg"></div>
                  <div class="popup-content-wrap">
                    <div class="popup-hd">
                      <h1 class="popup-tit">구매후기 삭제요청</h1>
                      <button class="icon-close-btn-wh" @click="popupRequest.isVisible = false">닫기</button>
                    </div>
                    <!-- content -->
                    <div class="popup-content popup-delete-con" style="margin-top: 65px;">
                      <section class="popup-content-number">
                        <h2 class="in-tit">후기 No.</h2>
                        <div class="in-desc">
                          <input
                            type="number"
                            placeholder="게시글 번호"
                            v-model="popupRequest.id"
                            readonly="readonly"
                          />
                        </div>
                      </section>
                      <section class="popup-content-reason">
                        <h2 class="in-tit">삭제 요청 사유</h2>
                        <div class="in-desc">
                          <select
                            class="form-select"
                            v-model="popupRequest.reason"
                            :disabled="popupRequest.deleted !== 0"
                          >
                            <option value="폭언 및 부적절한 언행">폭언 및 부적절한 언행</option>
                            <option value="근거 없는 비난/사실 무근">근거 없는 비난/사실 무근</option>
                          </select>
                        </div>
                      </section>
                      <section class="popup-content-detail">
                        <h2 class="in-tit">상세내용</h2>
                        <div class="in-desc">
                          <textarea
                            v-model="popupRequest.detail"
                            class="_txt"
                            :disabled="popupRequest.deleted !== 0"
                          ></textarea>
                        </div>
                      </section>
                      <div class="popup-btn-wrap">
                        <button
                          class="square-md-btn btn-outline-gray close-btn"
                          @click="popupRequest.isVisible = false"
                        >닫기</button>
                        <button
                          v-show="popupRequest.deleted === 0"
                          class="square-md-btn btn-gradient"
                          @click="requestDelete"
                          :disabled="popupRequest.deleted !== 0"
                        >삭제요청</button>
                      </div>
                    </div>
                    <!-- content E -->
                  </div>
                </div>
                <!-- E 구매후기 삭제요청 팝업 -->
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
  name: 'ManageReview',
  data () {
    return {
      popup: {
        isVisible: false
      },
      popupRequest: {
        isVisible: false,
        id: null,
        reason: '',
        detail: '',
        deleted: 0
      },
      search: {
        keyword: '',
        select: 'subject',
        options: [
          {
            label: '리뷰제목',
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
          start: this.$moment()
            .subtract(1, 'months')
            .format('YYYY-MM-DD'),
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
          .get('/api/store/review/list', {
            params: {
              ...params,
              searchSelect: this.search.keyword ? this.search.select : null
            }
          })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(this.updateCursor)

        this.table.rows = (this.table.rows || []).concat(data.review)
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
    async requestDelete () {
      try {
        this.loading(true)

        const { formData, headers } = this.formDatas({
          _method: 'put',
          id: this.popupRequest.id,
          deleted_reason:
              { 0: '폭언 및 부적절한 언행' }[this.popupRequest.reason] || null,
          deleted_detail: this.popupRequest.detail
        })

        await this.$axios
          .post('/api/store/review/deleted_request', formData, { headers })
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
    },
    goToPage (row) {
      if (process.env.VUE_APP_ENV !== 'LOCAL') {
        this.NativePopup(
          process.env.VUE_APP_PRODC_URI.replace('store.', '') +
            '/review/' +
            row.item_id +
            '/view/' +
            row.id)
      } else {
        this.NativePopup(
          'http://localhost:8080/review/' + row.item_id + '/view/' + row.id
        )
      }
    },
    editRequestPopup (row) {
      this.popupRequest.id = row.id
      this.popupRequest.deleted = row.deleted

      if (row.deleted !== 0) {
        this.popupRequest.reason = row.deleted_reason || null
        this.popupRequest.detail = row.deleted_detail || '-'
      } else {
        this.popupRequest.reason = null
        this.popupRequest.detail = ''
      }

      this.popupRequest.isVisible = true
    }
  }
}
</script>

<style>
  @media all and (max-width: 350px) {
    .manage-pd-list-btn-wrap .rounded-square-btn {
      height: 35px;
      line-height: 33px;
      font-size: 14px;
    }
  }

  @media all and (max-width: 576px) {
    .manage-pd-list-btn-wrap {
      margin: 13px 0 0;
    }
  }

  .review-list-link ._star_score img,
  .review-list-link ._star_score span {
    vertical-align: middle;
    display: inline-block;
  }
</style>
