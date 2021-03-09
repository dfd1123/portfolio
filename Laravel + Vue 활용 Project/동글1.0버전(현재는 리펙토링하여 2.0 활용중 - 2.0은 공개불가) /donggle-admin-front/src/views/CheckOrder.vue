<template>
  <div id="app">
    <layout-header title="주문상품 확인" />

    <!-- contents -->
    <div id="admin-container">
      <div class="wrapper">
        <!-- 모바일에서 .fixed_top 를 주면 탭버튼과 셀렉트들이 탑에 고정됨 -->
        <div id="page-check-order-wrap" :class="{ fixed_top : mobileActive }">
          <div class="grid-line-group type-info">
            <div class="check-order-info">
              <div class="check-order-info-cards">
                <div class="in-card">
                  <span class="in-subtit">
                    <i class="show-pc">출고 준비할 상품</i>
                    <i class="show-mobile">출고 준비</i>
                  </span>
                  <div class="in-subdesc">
                    <p class="_tit">출고 준비할 상품이란?</p>
                    <p class="_desc">
                      -주문 신청이 들어왔지만 상품 출고 준비를 하지 않은
                      상품들을 말하며, 상품 납품 마감일이 초과될 시 주문이
                      자동으로 취소가 됩니다.
                    </p>
                  </div>
                  <h2 class="in-count">
                    <b>{{orderApply !== null ? Number(orderApply).toLocaleString() : '-'}}</b>
                    <b>개</b>
                  </h2>
                </div>
                <div class="in-card">
                  <span class="in-subtit">
                    <i class="show-pc">출고 대기 상품</i>
                    <i class="show-mobile">출고 대기</i>
                  </span>
                  <div class="in-subdesc">
                    <p class="_tit">출고 대기 상품이란?</p>
                    <p class="_desc">
                      -동글 스토어가 출고된 상품에 불량 유무 등 상품을
                      검수하는 상태나 주문 취소/환불 시 재고로 쌓여있는
                      상품을 말합니다.
                    </p>
                  </div>
                  <h2 class="in-count">
                    <b>{{deliveryWait !== null ? Number(deliveryWait).toLocaleString() : '-'}}</b>
                    <b>개</b>
                  </h2>
                </div>
                <div class="in-card">
                  <span class="in-subtit">
                    <i class="show-pc">출고 완료 상품</i>
                    <i class="show-mobile">출고 완료</i>
                  </span>
                  <div class="in-subdesc">
                    <p class="_tit">출고 완료 상품이란?</p>
                    <p class="_desc">
                      - 상품 검수가 끝나고 구매자에게 배송 이 되기 바로 직전
                      및 배송이 완료 된 상품, 구매가 확정된 상품들 입니다.
                    </p>
                  </div>
                  <h2 class="in-count">
                    <b>{{shipping !== null ? Number(shipping).toLocaleString() : '-'}}</b>
                    <b>개</b>
                  </h2>
                </div>
              </div>
              <span class="in-caution">※ 상품 납품 마감일이 초과될 시 주문이 자동 취소 됩니다.</span>
            </div>
          </div>

          <div class="grid-line-group">
            <div class="panel-default">
              <div class="table-area">
                <table-search-bar
                  :select.sync="search.select"
                  :keyword.sync="search.keyword"
                  :options="search.options"
                  @click="searchButtonClick"
                />

                <div class="sorting-tab-bar">
                  <input
                    v-model="selectedTab"
                    type="radio"
                    v-uniq-id="'tab01'"
                    v-uniq-name="'tab-bar'"
                    value="order_confirm"
                    class="chck-box none"
                  />
                  <label v-uniq-for="'tab01'" class="in-tab first-tab">
                    <div class="inner">
                      <span>주문별</span>
                    </div>
                  </label>
                  <input
                    v-model="selectedTab"
                    type="radio"
                    v-uniq-id="'tab02'"
                    v-uniq-name="'tab-bar'"
                    value="order_confirm_group"
                    class="chck-box none"
                  />
                  <label v-uniq-for="'tab02'" class="in-tab">
                    <div class="inner">
                      <span>납품마감일 별</span>
                    </div>
                  </label>
                </div>

                <table-count-bar :count="pagination.count" />

                <div class="show-pc">
                  <table-data-grid
                    row-index="order_no"
                    :is-show-more-button-visible="pagination.hasNext"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    :table-classes="['order-ck-tbl']"
                    @selected-ready-button-click="selectedReadyButtonClick"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:colgroups>
                      <col class="_chck" />
                      <col class="_name" />
                      <col class="_color" />
                      <col class="_size" />
                      <col class="_num" />
                      <col v-if="selectedTab === 'order_confirm'" class="_date" />
                      <col class="_d-day" />
                      <col class="_stat" />
                      <col class="_ready" />
                    </template>
                    <template v-slot:headers>
                      <tr class="in-tr">
                        <th>선택</th>
                        <th>상품명</th>
                        <th>컬러</th>
                        <th>사이즈</th>
                        <th>수량</th>
                        <th v-if="selectedTab === 'order_confirm'">주문일</th>
                        <th>납품마감 잔여일</th>
                        <th>출고상태</th>
                        <th>준비상태</th>
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
                        <td class="ta-left">
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
                        <td>{{_get((row.option || '').split(','), 0) || '-'}}</td>
                        <td>{{_get((row.option || '').split(','), 1) || '-'}}</td>
                        <td>
                          <template v-if="selectedTab === 'order_confirm'">
                            <i>{{row.qty ? Number(row.qty).toLocaleString() : 0}}</i>
                          </template>
                          <template v-else-if="selectedTab === 'order_confirm_group'">
                            <i>{{(Number(row.no_ready) + Number(row.ready)).toLocaleString()}}</i>
                          </template>
                        </td>
                        <td v-if="selectedTab === 'order_confirm'">
                          <i>{{_get((row.created_at || '').split(' '), 0) || '-'}}</i>
                        </td>
                        <td>{{$moment(row.hope_date).isValid() && $moment(row.hope_date).diff($moment().format('YYYY-MM-DD'), 'days')}}일 남음</td>
                        <td>
                          <template v-if="selectedTab === 'order_confirm'">
                            <span v-if="row.od_status === 'order_apply'">출고준비</span>
                            <span v-else-if="row.od_status === 'delivery_wait'">출고대기</span>
                          </template>
                          <template v-else-if="selectedTab === 'order_confirm_group'">
                            <span
                              v-if="Number(row.ready) !== Number(row.no_ready) + Number(row.ready)"
                            >출고준비</span>
                            <span v-else>출고대기</span>
                          </template>
                        </td>
                        <td>
                          <template v-if="selectedTab === 'order_confirm'">
                            <div class="date-status">
                              <template v-if="row.od_status === 'order_apply'">
                                <span>준비 중</span>
                                <input
                                  type="button"
                                  class="rounded-square-xs-btn btn-gray"
                                  value="준비 완료"
                                  @click="rowReadyButtonClick({...row})"
                                />
                              </template>
                              <template v-else-if="row.od_status === 'delivery_wait'">
                                <span>준비 완료</span>
                              </template>
                            </div>
                          </template>
                          <template v-else-if="selectedTab === 'order_confirm_group'">
                            <i>{{Number(row.ready)}}/{{Number(row.no_ready) + Number(row.ready)}}</i>
                          </template>
                        </td>
                      </tr>
                    </template>
                  </table-data-grid>
                </div>

                <div class="show-mobile">
                  <table-data-grid-mobile
                    row-index="order_no"
                    :is-show-more-button-visible="pagination.hasNext"
                    :rows="table.rows"
                    :rows-checked.sync="table.rowsChecked"
                    :order-by-select.sync="table.orderBySelect"
                    :order-by-options="table.orderByOptions"
                    @selected-ready-button-click="selectedReadyButtonClick"
                    @order-by-select-change="orderBySelectChange"
                    @show-more-button-click="showMoreButtonClick"
                  >
                    <template v-slot:rows="rows">
                      <ul
                        class="order-list-wrap"
                        :style="{display: rows.list === null ? 'none' : ''}"
                      >
                        <li
                          v-for="(row, index) in rows.list"
                          :key="row[rows.index]"
                          class="in-product-order"
                        >
                          <ul>
                            <li class="_user-choice-pd">
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
                              <label v-uniq-for="`td-checkbox-mobile-${index}`">
                                <span class="in-order-product">{{row.title || '-'}}</span>
                              </label>
                              <div class="date-product-md clearfix">
                                <figure class="in-thumb">
                                  <img
                                    :src="storagePath(_get(JSON.parse(row.images), 0, null) || 'images/img/product.jpg')"
                                    alt="product image"
                                  />
                                </figure>
                                <ul class="order-option">
                                  <li class="in-desc">{{row.option}}</li>
                                  <template v-if="selectedTab === 'order_confirm'">
                                    <li
                                      class="in-desc"
                                    >수량 {{row.qty ? Number(row.qty).toLocaleString() : 0}}개</li>
                                  </template>
                                  <template v-if="selectedTab === 'order_confirm_group'">
                                    <li
                                      class="in-desc"
                                    >수량 {{(Number(row.no_ready) + Number(row.ready)).toLocaleString()}}개</li>
                                  </template>
                                </ul>
                                <dl class="order-ready">
                                  <dt class="in-tit">준비상태</dt>
                                  <!-- 주문 상품이 1개인 경우 -->
                                  <template v-if="selectedTab === 'order_confirm'">
                                    <dd
                                      v-if="row.od_status === 'order_apply'"
                                      class="in-desc _purple"
                                    >준비중</dd>
                                    <dd
                                      v-else-if="row.od_status === 'delivery_wait'"
                                      class="in-desc _pink"
                                    >준비완료</dd>
                                  </template>
                                  <template v-if="selectedTab === 'order_confirm_group'">
                                    <dd
                                      class="in-desc _pink"
                                    >{{ Number(row.ready)}}/{{Number(row.no_ready) + Number(row.ready)}}</dd>
                                  </template>
                                </dl>
                              </div>
                            </li>
                          </ul>
                          <ul class="order-status">
                            <li class="clearfix">
                              <span class="in-tit">주문일</span>
                              <span class="in-desc">
                                <i>{{_get((row.created_at || '').split(' '), 0) || '-'}}</i>
                              </span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">납품기간 잔여일</span>
                              <span
                                class="in-desc"
                              >{{$moment(row.hope_date).isValid() && $moment(row.hope_date).diff($moment().format('YYYY-MM-DD'), 'days')}}일 남음</span>
                            </li>
                            <li class="clearfix">
                              <span class="in-tit">출고상태</span>
                              <template v-if="selectedTab === 'order_confirm'">
                                <span class="in-desc">
                                  <span v-if="row.od_status === 'order_apply'">출고준비</span>
                                  <span v-else-if="row.od_status === 'delivery_wait'">출고대기</span>
                                </span>
                              </template>
                              <template v-if="selectedTab === 'order_confirm_group'">
                                <span
                                  v-if="Number(row.ready) !== Number(row.no_ready) + Number(row.ready)"
                                  class="in-desc"
                                >출고준비</span>
                                <span v-else class="in-desc">출고대기</span>
                              </template>
                            </li>
                          </ul>
                          <div class="order-stat-btn-wrap">
                            <template v-if="row.od_status === 'order_apply'">
                              <button type="button" class="rounded-square-btn btn-gray">준비 완료</button>
                            </template>
                          </div>
                        </li>
                      </ul>
                    </template>
                  </table-data-grid-mobile>
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
import { mapActions } from 'vuex'

export default {
  name: 'CheckOrder',
  data () {
    return {
      selectedTab: 'order_confirm',
      search: {
        keyword: '',
        select: 'title',
        options: [
          {
            label: '상품명',
            value: 'title'
          },
          {
            label: '상품번호',
            value: 'item_id'
          }
        ]
      },
      table: {
        rows: null,
        rowsChecked: [],
        orderBySelect: 'remaining',
        orderByOptions: [
          {
            label: '잔여일 순',
            value: 'remaining'
          },
          {
            label: '수량 순',
            value: 'itemCounting'
          }
        ]
      },
      pagination: {
        hasNext: true,
        limit: 10,
        page: 1,
        count: null
      },
      orderApply: null,
      deliveryWait: null,
      shipping: null,
      mobileActive: null
    }
  },
  created () {
    this.fetchData()
  },
  watch: {
    selectedTab () {
      this.orderApply = null
      this.deliveryWait = null
      this.shipping = null

      this.table.rows = []
      this.resetCursor()
      this.searchClick()
    }
  },
  mounted () {
    window.addEventListener('scroll', this.mobileScrollEvent)
  },
  methods: {
    ...mapActions(['getStore', 'getNotification']),
    async fetchData () {
      await this.searchClick()
    },
    async searchClick () {
      try {
        this.loading(true)

        let data = []
        if (this.selectedTab === 'order_confirm') {
          data = await this.$axios
            .get('/api/store/order/order_confirm', {
              params: {
                searchSelect: this.search.keyword ? this.search.select : null,
                searchKeyword: this.search.keyword,
                orderBy: this.table.orderBySelect,
                limit: this.pagination.limit,
                page: this.pagination.page
              }
            })
            .then(this.normalOrError)
            .then(this.resultOrError)
            .then(this.updateCursor)
        } else if (this.selectedTab === 'order_confirm_group') {
          data = await this.$axios
            .get('/api/store/order/order_confirm_group', {
              params: {
                searchSelect: this.search.keyword ? this.search.select : null,
                searchKeyword: this.search.keyword,
                orderBy: this.table.orderBySelect,
                limit: this.pagination.limit,
                page: this.pagination.page
              }
            })
            .then(this.normalOrError)
            .then(this.resultOrError)
            .then(this.updateCursor)

          data.orders = data.orders.map(x => ({
            ...x,
            order_no: `${x.hope_date}-${x.item_id}-${x.option}`
          }))
        } else {
          return
        }

        data.order_counting.forEach(x => {
          switch (x.od_status) {
            case 'order_apply':
              this.orderApply = x.order_counting
              break
            case 'delivery_wait':
              this.deliveryWait = x.order_counting
              break
            case 'shipping':
              this.shipping = x.order_counting
              break
          }
        })

        this.orderApply = this.orderApply || 0
        this.deliveryWait = this.deliveryWait || 0
        this.shipping = this.shipping || 0

        this.table.rows = (this.table.rows || []).concat(data.orders)
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async updateReadyStates (rows) {
      try {
        this.loading(true)

        if (!rows) {
          return
        }

        const { formData, headers } = this.formDatas({
          _method: 'put',
          order_no: rows.map(x => x.order_no || null)
        })

        await this.$axios
          .post('/api/store/order/order_confirm', formData, { headers })
          .then(this.normalOrError)
          .then(this.alertIfMessage)

        await this.orderBySelectChange()
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async updateReadyStatesGroup (rows) {
      try {
        this.loading(true)

        if (!rows) {
          return
        }

        const objectGroup = rows.map(x => ({
          hope_date: x.hope_date,
          item_id: x.item_id,
          option: x.option
        }))

        const { formData, headers } = this.formDatas({
          _method: 'put',
          object_group: JSON.stringify(objectGroup)
        })

        await this.$axios
          .post('/api/store/order/order_confirm_group', formData, { headers })
          .then(this.normalOrError)
          .then(this.alertIfMessage)

        await this.orderBySelectChange()
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async selectedReadyButtonClick (rows) {
      if (this.selectedTab === 'order_confirm') {
        await this.updateReadyStates(rows)
      } else if (this.selectedTab === 'order_confirm_group') {
        await this.updateReadyStatesGroup(rows)
      }
    },
    async rowReadyButtonClick (row) {
      if (this.selectedTab === 'order_confirm') {
        await this.updateReadyStates([row])
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
    mobileScrollEvent () {
      const nowScroll = window.scrollY

      nowScroll > 315
        ? (this.mobileActive = true)
        : (this.mobileActive = false)
    }
  }
}
</script>

<style lang="scss">
@media all and (max-width: 576px) {
  #page-check-order-wrap.fixed_top {
    padding-top: 300px;
    background-color: white;

    .table-area {
      margin-top: 0;
    }
    .check-order-info {
      opacity: 0;
    }
    .table-area .search-status-bar {
      display: none;
    }
    .table-area .search-default-bar {
      margin-bottom: 0;
    }
    .grid-line-group {
      padding-top: 0;
    }
  }
}
</style>
