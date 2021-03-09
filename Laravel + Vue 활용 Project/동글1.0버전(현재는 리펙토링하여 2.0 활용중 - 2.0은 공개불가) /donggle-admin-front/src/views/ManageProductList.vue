<template>
  <div id="app">
    <layout-header
      title="상품관리"
      button-right="상품등록"
      @button-right-click="registerItemButtonClick"
    />

    <!-- contents -->
    <div id="admin-container">
      <div class="wrapper">
        <!-- 모바일에서 .fixed_top 를 주면 탭버튼과 셀렉트들이 탑에 고정됨 -->
        <div
          id="page-manage-prdt-list-wrap"
          class="check-fixed-top-mobile"
        >
          <div class="grid-line-group fix_type-04">
            <div class="panel-default">
              <div class="table-area">
                <table-search-bar
                  :select.sync="search.select"
                  :keyword.sync="search.keyword"
                  :options="search.options"
                  @click="searchButtonClick"
                />

                <table-count-bar :count="pagination.count" />

                <div class="show-pc">
                  <table-data-grid
                    row-index="item_id"
                    :is-show-more-button-visible="pagination.hasNext"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    :table-classes="['manage-p-tbl']"
                    :table-container-classes="['type-02']"
                    :selected-ready-button-list="['품절처리', '판매진행']"
                    @selected-ready-button-click="selectedReadyButtonClick"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:status-button-group-upper>
                      <button
                        type="button"
                        class="rounded-square-xs-btn btn-outline-navy type-shadow"
                        @click="$emit('selected-ready-button-click')"
                      >준비 완료</button>
                    </template>
                    <template v-slot:colgroups>
                      <col class="_chck" />
                      <col class="_p_num" />
                      <col class="_category" />
                      <col class="_nm_price" />
                      <col class="_price" />
                      <col class="_sale" />
                      <col class="_dg_price" />
                      <col class="_click" />
                      <col class="_stat" />
                      <col class="_manage" />
                    </template>
                    <template v-slot:headers>
                      <tr class="in-tr">
                        <th rowspan="2">선택</th>
                        <th rowspan="2">품번</th>
                        <th>카테고리</th>
                        <th rowspan="2">시중가</th>
                        <th rowspan="2">판매가</th>
                        <th rowspan="2">할인가</th>
                        <th rowspan="2">동글가</th>
                        <th rowspan="2">조회수</th>
                        <th rowspan="2">판매상태</th>
                        <th rowspan="2">관리</th>
                      </tr>
                      <tr class="in-tr">
                        <th>상품명</th>
                      </tr>
                    </template>
                    <template v-slot:rows="rows">
                      <template v-for="(row, index) in rows.list">
                        <tr
                          class="in-first-tr"
                          :key="row[rows.index] + '-1'"
                        >
                          <td rowspan="2">
                            <input
                              v-uniq-id="`td-checkbox01-${index}`"
                              type="checkbox"
                              class="chck-box none"
                              v-model="rows.checkedList"
                              :value="row[rows.index]"
                              @change="rows.check"
                            />
                            <label
                              v-uniq-for="`td-checkbox01-${index}`"
                              class="check-gradi-circle"
                            ></label>
                          </td>
                          <td rowspan="2">{{row.item_id}}</td>
                          <td>{{row.ca_name || '-'}}</td>
                          <td rowspan="2">
                            <i>{{Number(row.cust_price).toLocaleString()}}</i>원
                          </td>
                          <td rowspan="2">
                            <i>{{Number(row.tax_mny).toLocaleString()}}</i>원
                          </td>
                          <td rowspan="2">
                            <!-- TODO 할인가 물어보기 -->
                            <i>-</i>원
                          </td>
                          <td rowspan="2">
                            <i>{{Number(row.price).toLocaleString()}}</i>원
                          </td>
                          <td rowspan="2">
                            <i class="pd_0">{{row.hit}}</i>
                          </td>
                          <td rowspan="2">
                            <div
                              v-if="['0', '1', '3'].includes(String(row.sell_yn))"
                              class="date-status-slct"
                            >
                              <select
                                v-model.number="row.sell_yn"
                                class="form-select none"
                              >
                                <option value="0">판매중지</option>
                                <option value="1">정상</option>
                                <option value="3">품절</option>
                              </select>
                            </div>
                            <!-- 노출 중지가 되었을 때만 보임 -->
                            <div
                              v-else-if="['2'].includes(String(row.sell_yn))"
                              class="date-states-suspend"
                            >
                              노출중지
                              <!-- 버튼 클릭시 노출중지 사유보기 팝업등장 -->
                              <button
                                class="rounded-square-xs-btn btn-gray suspend-btn"
                                @click="showRowPopup(row)"
                              >사유보기</button>
                            </div>
                          </td>
                          <td rowspan="2">
                            <div class="date-double-btn">
                              <input
                                v-if="!['2'].includes(String(row.sell_yn))"
                                type="button"
                                class="rounded-square-xs-btn btn-mint"
                                value="수정"
                                @click.prevent="rowEditButtonClick(row)"
                              />
                              <input
                                type="button"
                                class="rounded-square-xs-btn btn-gray"
                                value="보기"
                                @click.prevent="rowShowButtonClick(row)"
                              />
                              <input
                                type="button"
                                class="rounded-square-xs-btn btn-navy"
                                value="삭제"
                                @click.prevent="deleteButtonClick(row.item_id)"
                              />
                            </div>
                          </td>
                        </tr>
                        <tr
                          class="in-second-tr"
                          :key="row[rows.index] + '-2'"
                        >
                          <td class="ta-left">
                            <div class="date-product-xs">
                              <figure class="in-thumb">
                                <img
                                  :src="storagePath(_get(JSON.parse(row.images), 0, null) || 'images/img/product.jpg')"
                                  alt="product image"
                                />
                              </figure>
                              <span class="in-word">{{row.title || '-'}}</span>
                            </div>
                          </td>
                        </tr>
                      </template>
                    </template>
                    <template v-slot:status-button-group-lower>
                      <button
                        type="button"
                        class="rounded-square-xs-btn btn-outline-navy type-shadow"
                        @click="$emit('selected-ready-button-click')"
                      >준비 완료</button>
                    </template>
                  </table-data-grid>
                </div>

                <div class="show-mobile">
                  <table-data-grid-mobile
                    row-index="item_id"
                    :is-show-more-button-visible="pagination.hasNext"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    :selected-ready-button-list="['품절처리', '판매진행']"
                    @selected-ready-button-click="selectedReadyButtonClick"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:rows="rows">
                      <ul
                        class="manage-pd-list-wrap"
                        :style="{display: rows.list === null ? 'none' : ''}"
                      >
                        <li
                          v-for="(row, index) in rows.list"
                          :key="row[rows.index]"
                          class="in-product-order"
                        >
                          <ul>
                            <li class="_user-choice-pd">
                              <div class="pd-input-box">
                                <input
                                  v-uniq-id="`td-checkbox-mobile-${index}`"
                                  v-model="rows.checkedList"
                                  :value="row[rows.index]"
                                  type="checkbox"
                                  class="chck-box none"
                                  @change="rows.check"
                                />
                                <label
                                  v-uniq-for="`td-checkbox-mobile-${index}`"
                                  class="check-gradi-circle"
                                ></label>
                                <label
                                  v-uniq-for="`td-checkbox-mobile-${index}`"
                                  class="check-label"
                                >
                                  <div class="pd-input-label clearfix">
                                    <span class="_pd_numname">품번</span>
                                    <span class="_pd_num">{{row.item_id}}</span>
                                  </div>
                                </label>
                                <div class="pd-category-area">
                                  <span class="_pd_category">{{row.ca_name || '-'}}</span>
                                </div>
                              </div>
                              <div class="date-product-md clearfix">
                                <figure class="in-thumb">
                                  <img
                                    :src="storagePath(_get(JSON.parse(row.images), 0, null) || 'images/img/product.jpg')"
                                    alt="product image"
                                  />
                                </figure>
                                <div class="in-order-product">
                                  <span class="_pd_name">{{row.title || '-'}}</span>
                                  <div class="_user_click">
                                    조회수
                                    <span class="_num">{{row.hit}}</span>
                                  </div>
                                </div>
                              </div>
                            </li>
                          </ul>
                          <ul class="order-status">
                            <li class="clearfix">
                              <dl>
                                <dt class="in-tit">시중가</dt>
                                <dd class="in-desc">
                                  <span>{{Number(row.cust_price).toLocaleString()}}</span> 원
                                </dd>
                              </dl>
                            </li>
                            <li class="clearfix">
                              <dl>
                                <dt class="in-tit">판매가</dt>
                                <dd class="in-desc">
                                  <span>{{Number(row.tax_mny).toLocaleString()}}</span> 원
                                </dd>
                              </dl>
                            </li>
                            <li class="clearfix">
                              <dl>
                                <dt class="in-tit">할인가</dt>
                                <dd class="in-desc">
                                  <!-- TODO 할인가 물어보기 -->
                                  <span>-</span> 원
                                </dd>
                              </dl>
                            </li>
                            <li class="clearfix">
                              <dl>
                                <dt class="in-tit">동글가</dt>
                                <dd class="in-desc">
                                  <span class="_pink">{{Number(row.price).toLocaleString()}}</span> 원
                                </dd>
                              </dl>
                            </li>
                            <li class="selec_wrap clearfix">
                              <dl>
                                <dt class="in-tit">판매상태</dt>
                                <dd class="in-desc">
                                  <select
                                    v-if="['0', '1', '3'].includes(String(row.sell_yn))"
                                    v-model.number="row.sell_yn"
                                    class="form-select"
                                  >
                                    <option value="0">판매중지</option>
                                    <option value="1">정상</option>
                                    <option value="3">품절</option>
                                  </select>
                                  <!-- 노출 중지가 되었을 때만 보임 -->
                                  <div
                                    v-else-if="['2'].includes(String(row.sell_yn))"
                                    class="date-states-suspend"
                                  >
                                    <!-- 클릭시 노출중지 사유보기 팝업 등장 -->
                                    <button
                                      class="rounded-square-xs-btn btn-gray suspend-btn"
                                      @click="showRowPopup(row)"
                                    >노출중지 사유보기</button>
                                  </div>
                                </dd>
                              </dl>
                            </li>
                          </ul>
                          <div class="manage-pd-list-btn-wrap">
                            <button
                              v-if="!['2'].includes(String(row.sell_yn))"
                              type="button"
                              class="rounded-square-btn btn-mint"
                              @click.prevent="rowEditButtonClick(row)"
                            >수정</button>
                            <button
                              type="button"
                              class="rounded-square-btn btn-gray"
                              @click.prevent="rowShowButtonClick(row)"
                            >보기</button>
                            <input
                              type="button"
                              class="rounded-square-btn btn-navy"
                              value="삭제"
                              @click.prevent="deleteButtonClick(row.item_id)"
                            />
                          </div>
                        </li>
                      </ul>
                    </template>
                  </table-data-grid-mobile>
                </div>

                <!-- 노출중지 사유보기 팝업 -->
                <div
                  class="popup-container bgc-none"
                  :style="{display: popup.isVisible ? 'block' : 'none'}"
                >
                  <div class="popup-bg"></div>
                  <div class="popup-content-wrap">
                    <div class="popup-hd">
                      <h1 class="popup-tit">노출중지 사유보기</h1>
                      <button
                        class="icon-close-btn-wh"
                        @click="popup.isVisible = false"
                      >닫기</button>
                    </div>
                    <!-- content -->
                    <div class="popup-content popup-view-stop-con">
                      <section class="popup-content-reason">
                        <h2 class="in-tit">노출 중지 사유</h2>
                        <div class="in-desc">{{_get(popup, 'row.sell_n_reason') || '-'}}</div>
                      </section>
                      <section class="popup-content-detail">
                        <h2 class="in-tit">상세내용</h2>
                        <div class="in-desc y-scroll">-</div>
                      </section>
                      <div class="popup-btn-wrap">
                        <button
                          class="square-md-btn btn-outline-gray close-btn"
                          @click="popup.isVisible = false"
                        >닫기</button>
                        <button
                          class="square-md-btn btn-gradient"
                          @click="requestReopen"
                        >노출허가 요청</button>
                      </div>
                    </div>
                    <!-- content E -->
                  </div>
                </div>
                <!-- 노출중지 사유보기 팝업 E -->
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
  name: 'ManageProductList',
  data () {
    return {
      popup: {
        isVisible: false,
        reason: '',
        detail: ''
      },
      search: {
        keyword: '',
        select: 'item_id',
        options: [
          {
            label: '상품번호',
            value: 'item_id'
          },
          {
            label: '카테고리',
            value: 'category'
          },
          {
            label: '상품명',
            value: 'title'
          }
        ],
        savedQueryString: null
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
            label: '가격 순',
            value: 'price'
          },
          {
            label: '조회수 순',
            value: 'hit'
          },
          {
            label: '평점 순',
            value: 'rating'
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
          limit: this.pagination.limit,
          page: this.pagination.page
        }

        const data = await this.$axios
          .get('/api/store/items/list', {
            params: {
              ...params,
              searchSelect: this.search.keyword ? this.search.select : null
            }
          })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(this.updateCursor)

        this.table.rows = (this.table.rows || []).concat(data.items)

        return data.items
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
      if (row.item_id) {
        this.$router.push({
          name: 'manage-product-enroll',
          params: { id: row.item_id }
        })
      }
    },
    async deleteButtonClick (itemId) {
      if (confirm('정말 삭제 하시겠습니까?')) {
        const params = {
          item_id: itemId
        }

        try {
          this.loading(true)
          const res = (await this.$axios.put('/api/store/items/item_delete', params)).data

          if (res.state === 1) {
            this.table.rows = await this.searchClick()
            alert('삭제 완료')
          }
        } catch (e) {
          console.log(e)
        } finally {
          this.loading(false)
        }
      }
    },
    async selectedReadyButtonClick (rows, index) {
      try {
        this.loading(true)

        if (!rows) {
          return
        }

        const { formData, headers } = this.formDatas({
          _method: 'put',
          item_id: rows.filter(x => String(x.sell_yn) !== '2').map(x => x.item_id),
          sell_yn: { 0: 3 /* 품절 */, 1: 1 /* 판매 */ }[index] || null
        })

        await this.$axios
          .post('/api/store/items/change_state', formData, { headers })
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
    async rowEditButtonClick (row) {
      try {
        this.loading(true)

        if (!row) {
          return
        }

        const { formData, headers } = this.formDatas({
          _method: 'put',
          item_id: [row.item_id],
          sell_yn: this._get(row, 'sell_yn', null)
        })

        await this.$axios
          .post('/api/store/items/change_state', formData, { headers })
          .then(this.normalOrError)
          .then(this.alertIfMessage)
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
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
    registerItemButtonClick () {
      this.$router.push('/manage-product-enroll')
    },
    showRowPopup (row) {
      this.popup.row = { ...row }
      this.popup.isVisible = true
    },
    async requestReopen () {
      // TODO
      console.log(this.popup.row)
    }
  }
}
</script>
