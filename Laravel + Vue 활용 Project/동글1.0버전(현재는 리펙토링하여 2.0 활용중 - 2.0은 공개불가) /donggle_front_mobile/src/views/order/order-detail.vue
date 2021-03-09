<template>
  <div
    id="dg-order-complete-wrapper"
    class="l-ordercancel-wrapper"
  >
    <!-- 2) 주문상세내역 구역 -->
    <div class="l-con-area full">
      <article class="l-con-article">
        <div class="_page_title_wrap">
          <h2>
            주문 상세보기
            <button
              type="button"
              class="_back_btn"
              @click="$router.go(-1)"
            >
              뒤로가기
            </button>
          </h2>
        </div>
        <div>
          <!-- 주문상품 리스트 -->
          <div class="ordered-list-group_wrap ">
            <input
              id="test-1"
              type="checkbox"
              class="order_check display_none"
            >
            <label
              for="test-1"
              class="order_check_label"
            >주문 상세내역</label>
            <ul class="l-grid-group ordered-list-group">
              <li class="l-grid-list l-col-1">
                <!-- * 소제목 -->
                <div class="_title">
                  <span>주문상품</span>
                </div>
                <!-- * 소제목 E -->
              </li>

              <li class="l-grid-list l-col-1">
                <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) -->
                <div class="dg-clothes-history-card type-cart type-detail ">
                  <div class="in-content">
                    <ProductList
                      v-for="order in orders"
                      :key="'order'+order.order_no"
                      :item-list="order"
                      :show-check-box="false"
                      :show-delete-btn="false"
                      :show-option-edit-btn="false"
                      :show-coupon="true"
                      :show-request-msg="false"
                    />
                  </div>
                </div>
                <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) E -->
              </li>

              <li class="l-grid-list l-col-1">
                <!-- * 최종 가격 안내 -->
                <div class="in-pay-info">
                  <div class="dg-final-price-label">
                    <h4 class="in-subject">
                      결제금액
                    </h4>
                    <div class="dg-total-price-card">
                      <ul class="in-price-infos">
                        <li class="_col _col--base">
                          <span class="_label _black">상품금액 합계</span>
                          <b class="_prc"><i>{{ NumberFormat(orderInfo.total_price || 0) }}</i> 원</b>
                        </li>
                        <li class="_col col--delivery">
                          <span class="_label">배송비</span>
                          <b class="_prc _gray"><i>{{ NumberFormat((orderInfo.send_cost || 0) + (orderInfo.send_add_cost || 0) + (orderInfo.send_coupon || 0)) }}</i> 원</b>
                        </li>
                        <li class="_col col--sale">
                          <span class="_label">총 할인금액</span>
                          <b class="_prc"><i>- {{ NumberFormat((orderInfo.coupon_discount || 0) + (orderInfo.coupon_price || 0) + (orderInfo.level_discount || 0)) }}</i> 원</b>
                        </li>
                        <li class="_col _col--total">
                          <span class="_label _black _total">최종 결제금액</span>
                          <b class="_prc"><i>{{ NumberFormat(orderInfo.receipt_price || 0) }}</i> 원</b>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!-- * 최종 가격 안내 E -->
              </li>
            </ul>
          </div>
          <!-- 주문상품 리스트 E -->
        </div>
      </article>
    </div>
    <!-- 2) 주문상세내역 구역 E -->

    <!-- 3) 주문정보들 -->
    <div class="dg-order-info-group clear_both">
      <!-- 배송 정보 -->
      <div class="in-detail-info">
        <div class="ordered-list-group_wrap">
          <input
            id="test-2"
            type="checkbox"
            class="order_check display_none"
            :checked="true"
          >
          <label
            for="test-2"
            class="order_check_label"
          >배송정보</label>
          <ul class="l-grid-group ordered-list-group">
            <li class="l-grid-list l-col-1">
              <!-- [1] 주문고객 정보 -->
              <article class="l-con-article">
                <h4 class="in-subject">
                  주문고객
                </h4>
                <ul class="in-information">
                  <li class="_list _info">
                    <p class="_txt _name">
                      {{ orderInfo.s_name }}
                    </p>
                  </li>
                  <li class="_list _info">
                    <p class="_txt _gray">
                      {{ orderInfo.s_hp }}
                    </p>
                  </li>
                  <li class="_list _info">
                    <p class="_txt _gray">
                      {{ orderInfo.s_id }}
                    </p>
                  </li>
                </ul>
              </article>
              <!-- [1] E -->
            </li>
            <li class="l-grid-list l-col-1">
              <!-- [2] 배송지 정보 -->
              <article class="l-con-article">
                <h4 class="in-subject">
                  배송지
                </h4>
                <ul class="in-information">
                  <li class="_list _info">
                    <p class="_txt _name">
                      {{ orderInfo.g_name }}
                    </p>
                  </li>
                  <li class="_list _info _address">
                    <p class="_txt  _add">
                      {{ orderInfo.g_addr1 }}
                    </p>
                  </li>
                  <li class="_list _info _address">
                    <p class="_txt _add">
                      {{ orderInfo.g_addr2 }}
                    </p>
                  </li>
                  <li class="_list _info _address">
                    <p class="_txt _add">
                      {{ orderInfo.g_post_num }}
                    </p>
                  </li>
                  <li class="_list _info">
                    <p class="_txt _gray">
                      {{ orderInfo.g_hp }}
                    </p>
                  </li>
                </ul>
              </article>
              <!-- [2] E -->
            </li>
            <li class="l-grid-list l-col-1 delivery-list">
              <!-- [3] 배송정보 -->
              <article class="l-con-article">
                <h4 class="in-subject">
                  배송정보
                </h4>
                <ul class="in-information">
                  <li class="_list _deli clear_both">
                    <span class="_label">배송준비기간</span>
                    <p class="_txt">
                      {{ orderInfo.hope_date }}까지 배송준비완료 예정
                    </p>
                  </li>
                  <li class="_list _deli clear_both">
                    <span class="_label">배송상태</span>
                    <p
                      v-if="orderInfo.od_status === 'delivery_wait'"
                      class="_txt"
                    >
                      배송대기
                    </p>
                    <p
                      v-else-if="orderInfo.od_status === 'shipping'"
                      class="_txt"
                    >
                      배송중
                    </p>
                    <p
                      v-else-if="orderInfo.od_status === 'delivery_complete'"
                      class="_txt"
                    >
                      배송완료
                    </p>
                    <p
                      v-else-if="orderInfo.od_status === 'order_complete'"
                      class="_txt"
                    >
                      주문완료
                    </p>
                    <p
                      v-else
                      class="_txt"
                    >
                      상품준비
                    </p>
                  </li>
                  <li
                    v-if="orderInfo.delivery_memo"
                    class="_list _deli clear_both"
                  >
                    <span class="_label">배송요청사항</span>
                    <p
                      class="_txt"
                      v-html="orderInfo.delivery_memo"
                    >
                    </p>
                  </li>
                </ul>
              </article>
              <!-- [3] E -->
            </li>
          </ul>
        </div>
      </div>
      <!-- 배송 정보 E -->

      <!-- 결제 정보 -->
      <div class="in-pay-info">
        <div class="in-detail-info">
          <div class="ordered-list-group_wrap">
            <input
              id="test-3"
              type="checkbox"
              class="order_check display_none"
              :checked="true"
            >
            <label
              for="test-3"
              class="order_check_label"
            >결제정보</label>
            <ul class="l-grid-group ordered-list-group">
              <li class="l-grid-list l-col-1">
                <!-- [1] 결제 정보 -->
                <article class="l-con-article">
                  <h4 class="dg_blind">
                    결제정보
                  </h4>
                  <ul class="in-information">
                    <li class="_list clear_both">
                      <span class="_label">주문번호</span>
                      <p class="_txt">
                        {{ orderInfo.order_id }}
                      </p>
                    </li>
                    <li class="_list clear_both">
                      <span class="_label">주문일시</span>
                      <p class="_txt">
                        {{ orderInfo.created_at }}
                      </p>
                    </li>
                    <li class="_list clear_both">
                      <span class="_label">결제방식</span>
                      <p class="_txt">
                        {{ SettleCase }}
                      </p>
                    </li>
                    <li
                      v-if="payInfo.pp_settle_case.includes('VBANK')"
                      class="_list clear_both"
                    >
                      <span class="_label">입금계좌 정보</span>
                      <p class="_txt">
                        {{ payInfo.pp_account_number || '-' }} {{ payInfo.pp_bank_account || '-' }} {{ payInfo.pp_deposit_name || '-' }}
                      </p>
                    </li>
                    <li class="_list clear_both">
                      <span class="_label">최종 결제금액</span>
                      <p class="_txt">
                        {{ NumberFormat(orderInfo.receipt_price || 0) }}원
                      </p>
                    </li>
                  </ul>
                  <div class="dg-button-wrap">
                    <router-link
                      v-if="orderInfo.od_status === 'deposit_wait' || orderInfo.od_status === 'order_apply'"
                      class="theme-btn-gradient btn-shadow"
                      :to="'/cancel/request?order_id='+orderInfo.order_id"
                    >
                      주문
                      취소하기
                    </router-link>
                    <router-link
                      v-else-if="orderInfo.od_status === 'shipping' || orderInfo.od_status === 'delivery_complete'"
                      class="theme-btn-gradient btn-shadow"
                      :to="'/refund/request?order_id='+orderInfo.order_id"
                    >
                      환불 신청하기
                    </router-link>
                  </div>
                </article>
                <!-- [1] E -->
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- 결제 정보 E -->

      <!-- 3) 주문정보들 E -->
    </div>
  </div>
</template>

<script>
  import ProductList from '@/components/order/order-list.vue'

  export default {
    components: {
      ProductList
    },
    data: function () {
      return {
        payInfo: {},
        orderInfo: {},
        orders: []
      }
    },
    props: {
      orderId: {
        type: String,
        required: true
      }
    },
    async created () {
      this.$store.commit('ProgressShow')

      const params = {
        order_id: this.orderId
      }
      try {
        const res = (await this.$http.get(this.$APIURI + 'order/mypage_detail', { params })).data
        const res2 = (await this.$http.get(this.$APIURI + 'order/pay_info', { params })).data

        if (res.state === 1) {
          this.orders = res.query.orders
          this.orderInfo = this.orders[0] || {}
        } else {
          console.log(res.msg)
        }

        if (res2.state === 1) {
          this.payInfo = res2.query
        } else {
          console.log(res2.msg)
        }
      } catch (e) {
        console.log(e)
      }

      this.$store.commit('ProgressHide')
    },
    computed: {
      SettleCase () {
        // console.log(this.orderInfo.settle_case)
        let settleCase = ''
        if (this.orders.length === 0) {
          settleCase = '-'
        }

        if (this.payInfo.pp_settle_case.includes('VBANK')) {
          if (settleCase === '') {
            settleCase = '가상계좌'
          } else {
            settleCase = settleCase + ',' + '가상계좌'
          }
        }

        if (this.payInfo.pp_settle_case.includes('CARD')) {
          if (settleCase === '') {
            settleCase = '신용카드'
          } else {
            settleCase = settleCase + ',' + '신용카드'
          }
        }

        if (this.payInfo.pp_settle_case.includes('BANK')) {
          if (settleCase === '') {
            settleCase = '계좌이체'
          } else {
            settleCase = settleCase + ',' + '계좌이체'
          }
        }

        if (this.payInfo.pp_settle_case.includes('CELLPHONE')) {
          if (settleCase === '') {
            settleCase = '휴대폰결제'
          } else {
            settleCase = settleCase + ',' + '휴대폰결제'
          }
        }

        return settleCase
      }
    }
  }
</script>

<style lang="scss" scoped>
  .order_check_label {
    background-image: none !important;
  }
  ul.ordered-list-group li.l-grid-list {
    display: block;
  }
</style>
