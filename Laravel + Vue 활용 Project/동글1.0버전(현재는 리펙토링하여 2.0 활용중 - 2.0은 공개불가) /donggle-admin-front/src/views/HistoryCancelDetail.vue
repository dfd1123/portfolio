<template>
  <div id="app">
    <layout-header
      title="취소/환불내역 상세보기"
      button-right="목록으로"
      :button-right-themes="['btn-mild']"
      @button-right-click="listButtonClick"
    />

    <!-- contents -->
    <div id="admin-container" class="detail-container">
      <div class="wrapper">
        <div id="page-history-cancel-detail-wrap">
          <div class="grid-line-group first">
            <div class="panel-default-container">
              <div class="panel-default-title">
                <h4 class="in-mainname">주문상품</h4>
              </div>
              <div class="panel-default gray-panel">
                <div class="inner-section">
                  <div class="inner-panel-title">
                    <span class="in-title">
                      현재 주문상태
                      <b class="_txt _deli">{{odStatus ? obStatusList[odStatus] : '-'}}</b>
                    </span>
                    <span class="in-title">
                      주문일시
                      <b
                        class="_txt"
                      >{{receiptTime ? $moment(receiptTime).format('ll HH:mm (dd)') : '-'}}</b>
                    </span>
                    <span class="in-title">
                      상품금액 합계
                      <b
                        class="_txt"
                      >{{receiptPrice ? Number(receiptPrice).toLocaleString() : '-'}}원(배송비 미포함)</b>
                    </span>
                  </div>
                  <div>
                    <div
                      v-for="order in orders"
                      :key="`${order.order_id}-${order.order_no}`"
                      class="main-mini-panel-card type-thumb"
                    >
                      <div class="inner">
                        <figure class="in-thumb">
                          <img
                            :src="storagePath(_get(JSON.parse(order.images), 0, null) || 'images/img/product.jpg')"
                            alt="product image"
                          />
                        </figure>
                        <div class="in-txt-box">
                          <span class="in-subtit">
                            <b class="_title">품번</b>
                            {{order.item_id || '-'}}
                          </span>
                          <p class="in-maintit">{{order.title || '-'}}</p>
                          <span class="in-subtit in-option">
                            <b class="_title">옵션</b>
                            {{order.option || '-'}}
                          </span>
                          <div class="in-total">
                            <p class="in-number">
                              수량
                              <b>{{order.qty ? Number(order.qty).toLocaleString() : '-'}} 개</b>
                            </p>
                            <p
                              class="in-price"
                            >{{order.item_price ? Number(order.item_price).toLocaleString() : '-'}}원</p>
                          </div>
                          <div
                            v-if="order.coupon_id"
                            class="in-coupon btn-theme"
                          >{{order.coupon_subject}}</div>
                          <div v-else class="in-coupon btn-outline-gray-md">적용된 쿠폰이 없습니다.</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="grid-line-group second">
            <div class="panel-default-container">
              <div class="panel-default-title">
                <h4 class="in-mainname">취소/환불 사유</h4>
              </div>
              <div class="panel-default">
                <div class="in-divider clearfix">
                  <span class="ex-input-label">사유</span>
                  <div class="form-textarea">
                    <p>{{this.refundReason || '-'}}</p>
                  </div>
                </div>

                <div class="in-divider in-detail clearfix">
                  <span class="ex-input-label">상세내용</span>
                  <div class="form-textarea">
                    <p>{{this.refundDetail || '-'}}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="grid-line-group third order-info-group">
            <div class="panel-default-container">
              <div class="panel-default-title">
                <h4 class="in-mainname">주문결제 내역</h4>
              </div>
              <div class="panel-default">
                <div class="show-pc">
                  <table-data-grid
                    row-index="order_no"
                    :is-check-all-visible="false"
                    :is-show-upper-status-bar="false"
                    :is-show-more-button-visible="false"
                    :rows="table.rows"
                  >
                    <template v-slot:headers>
                      <tr class="in-tr">
                        <th class="_num">주문번호</th>
                        <th class="_date">주문일시</th>
                        <th class="stat">주문상태</th>
                        <th class="_price">상품금액 합계</th>
                        <th class="_cou_sale">쿠폰 할인금액</th>
                        <th class="_user_sale">회원등급 할인금액</th>
                        <th class="_total">최종 결제금액</th>
                      </tr>
                    </template>
                    <template v-slot:rows="rows">
                      <tr v-for="row in rows.list" :key="row[rows.index]" class="in-tr">
                        <td class="_num">
                          <i>{{row.order_id}}</i>
                        </td>
                        <td class="_date">
                          <i>{{_get((row.receipt_time || '').split(','), 0) || '-'}}</i>
                        </td>
                        <td
                          class="_stat"
                        >{{row.settle_case ? settleCaseList[row.settle_case] : '-'}}</td>
                        <td class="_price">
                          <i>{{Number(row.total_price).toLocaleString()}}</i>원
                        </td>
                        <td class="_cou_sale">
                          -
                          <i>{{Number(row.coupon_discount).toLocaleString()}}</i>원
                        </td>
                        <td class="_user_sale">
                          -
                          <i>{{Number(row.level_discount).toLocaleString()}}</i>원
                        </td>
                        <td class="_total">
                          <i>{{Number(row.receipt_price).toLocaleString()}}</i>원
                        </td>
                      </tr>
                    </template>
                  </table-data-grid>
                </div>

                <div class="show-mobile">
                  <table-data-grid-mobile
                    row-index="order_no"
                    :is-check-all-visible="false"
                    :is-show-upper-status-bar="false"
                    :is-show-more-button-visible="false"
                    :rows="table.rows"
                  >
                    <template v-slot:rows="rows">
                      <ul v-for="row in rows.list" :key="row[rows.index]" class="mobile-wrapper">
                        <li class="line_desc clearfix">
                          <p class="_title _order_num">주문번호</p>
                          <p class="_txt _order_num">{{row.order_id}}</p>
                        </li>
                        <li class="line_desc clearfix">
                          <p class="_title">주문일시</p>
                          <p class="_txt">{{_get((row.receipt_time || '').split(','), 0) || '-'}}</p>
                        </li>
                        <li class="line_desc clearfix">
                          <p class="_title">주문상태</p>
                          <p
                            class="_txt"
                          >{{row.settle_case ? settleCaseList[row.settle_case] : '-'}}</p>
                        </li>
                        <li class="line_desc clearfix">
                          <p class="_title">상품금액합계</p>
                          <p class="_txt">{{Number(row.total_price).toLocaleString()}} 원</p>
                        </li>
                        <li class="line_desc clearfix">
                          <p class="_title">쿠폰 할인금액</p>
                          <p class="_txt">-{{Number(row.coupon_discount).toLocaleString()}} 원</p>
                        </li>
                        <li class="line_desc clearfix">
                          <p class="_title">회원등급 할인금액</p>
                          <p class="_txt">-{{Number(row.level_discount).toLocaleString()}} 원</p>
                        </li>
                        <li class="line_desc clearfix">
                          <p class="_title">총 결제금액</p>
                          <p class="_txt">
                            <b>{{Number(row.receipt_price).toLocaleString()}}</b> 원
                          </p>
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
import { obStatusList, settleCaseList } from '@/constants'

export default {
  name: 'HistoryCancelDetail',
  data () {
    return {
      orders: [],
      obStatusList,
      settleCaseList,
      orderId: this._get(this, '$route.params.id', null),
      odStatus: null,
      receiptTime: null,
      settleCase: null,
      receiptPrice: null,
      couponDiscount: null,
      levelDiscount: null,
      totalPrice: null,
      refundReason: null,
      refundDetail: null,
      table: {
        rows: null
      }
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    async fetchData () {
      this.searchClick()
    },
    async searchClick () {
      try {
        this.loading(true)

        const params = {
          order_id: this.orderId
        }

        const data = await this.$axios
          .get('/api/store/order/cancel_view', {
            params
          })
          .then(this.normalOrError)
          .then(this.resultOrError)

        if (data.orders && data.orders.length === 0) {
          alert('잘못된 데이터입니다')
          return this.$router.push('/history-cancel-list')
        }

        this.orders = data.orders

        const item = this._head(data.orders)
        this.table.rows = [item]

        this.odStatus = item.od_status
        this.receiptTime = item.receipt_time
        this.settleCase = item.settle_case
        this.receiptPrice = item.receipt_price
        this.couponDiscount = item.coupon_discount
        this.levelDiscount = item.level_discount
        this.totalPrice = item.total_price
        this.refundReason = item.refund_reason
        this.refundDetail = item.refund_detail
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    listButtonClick () {
      this.$router.push('/history-cancel-list')
    }
  }
}
</script>
