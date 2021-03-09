<template>
  <div
    id="dg-order-complete-wrapper"
    class="l-page-wrapper"
  >
    <!-- 2) 주문상세내역 구역 -->
    <div class="l-con-area full">
      <article class="l-con-article mb-25">
        <div class="l-con-title-group type02 mb-1">
          <h2 class="in-subject">
            주문 상세내역
          </h2>
        </div>

        <!-- * 주문상품 리스트 -->
        <div class="l-contents-group">
          <ul class="l-grid-group">
            <li class="l-grid-list l-col-1">
              <!-- * 상품내역 카드 (주문완료의 상품내역카드일 때는 클래스에 type-detail 추가) -->
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
              <!-- * 상품내역 카드 (주문완료의 상품내역카드일 때는 클래스에 type-detail 추가) E -->
            </li>

            <li class="l-grid-list l-col-1">
              <!-- * 최종 가격 안내 -->
              <div class="dg-final-price-label">
                <span class="_prdt_price">상품금액 합계 <b>{{ NumberFormat(orderInfo.total_price || 0) }} 원</b></span>
                <strong>+</strong>
                <span class="_delivry_price">배송비 <b>{{ NumberFormat((orderInfo.send_cost || 0) + (orderInfo.send_add_cost || 0) + (orderInfo.send_coupon || 0)) }} 원</b></span>
                <strong>-</strong>
                <span class="_sale_price">할인금액 <b>{{ NumberFormat((orderInfo.coupon_discount || 0) + (orderInfo.coupon_price || 0) + (orderInfo.level_discount || 0)) }} 원</b></span>
                <strong>=</strong>
                <span class="_final_price">최종결제금액 <b class="_point_size_pp">{{ NumberFormat(orderInfo.receipt_price || 0) }} 원</b></span>
              </div>
              <!-- * 최종 가격 안내 E -->
            </li>
          </ul>
        </div>
        <!-- * 주문상품 리스트 E -->
      </article>
    </div>
    <!-- 2) 주문상세내역 구역 E -->

    <!-- 3) 주문정보들 -->
    <div class="dg-order-info-group clear_both">
      <!-- 좌측 정보 -->
      <div class="in-detail-info">
        <!-- [1] 주문고객 정보 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            주문고객
          </h4>
          <ul class="in-information">
            <li class="_list">
              <span class="_label">이름</span>
              <p class="_txt">
                {{ orderInfo.s_name }}
              </p>
            </li>
            <li class="_list">
              <span class="_label">전화번호</span>
              <p class="_txt">
                {{ orderInfo.s_hp }}
              </p>
            </li>
            <li class="_list">
              <span class="_label">이메일</span>
              <p class="_txt">
                {{ orderInfo.s_id }}
              </p>
            </li>
          </ul>
        </article>
        <!-- [1] E -->

        <!-- [2] 배송지 정보 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            배송지
          </h4>
          <ul class="in-information">
            <li class="_list">
              <span class="_label">이름</span>
              <p class="_txt">
                {{ orderInfo.g_name }}
              </p>
            </li>
            <li class="_list">
              <span class="_label">전화번호</span>
              <p class="_txt">
                {{ orderInfo.g_hp }}
              </p>
            </li>
            <li class="_list">
              <span class="_label">우편번호</span>
              <p class="_txt">
                {{ orderInfo.g_post_num }}
              </p>
            </li>
            <li class="_list">
              <span class="_label">기본주소</span>
              <p class="_txt">
                {{ orderInfo.g_addr1 }}
              </p>
            </li>
            <li class="_list">
              <span class="_label">상세주소</span>
              <p class="_txt">
                {{ orderInfo.g_addr2 }}
              </p>
            </li>
          </ul>
        </article>
        <!-- [2] E -->

        <!-- [3] 배송정보 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            배송정보
          </h4>
          <ul class="in-information">
            <li class="_list">
              <span class="_label">배송준비기간</span>
              <p class="_txt">
                {{ orderInfo.hope_date }}까지 배송 준비 완료 예정
              </p>
            </li>
            <li class="_list">
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
              class="_list"
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
      </div>
      <!-- 좌측 정보 E -->

      <!-- 우측 결제 정보 -->
      <div class="in-pay-info">
        <!-- [1] 결제 정보 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            결제정보
          </h4>
          <ul class="in-information mb-30">
            <li class="_list">
              <span class="_label">주문번호</span>
              <p class="_txt">
                {{ orderInfo.order_id }}
              </p>
            </li>
            <li class="_list">
              <span class="_label">주문일시</span>
              <p class="_txt">
                {{ orderInfo.created_at }}
              </p>
            </li>
            <li class="_list">
              <span class="_label">결제방식</span>
              <p class="_txt">
                {{ SettleCase }}
              </p>
            </li>
            <li
              v-if="payInfo.pp_settle_case.includes('VBANK')"
              class="_list"
            >
              <span class="_label">입금계좌 정보</span>
              <p class="_txt">
                {{ payInfo.pp_account_number || '-' }} {{ payInfo.pp_bank_account || '-' }} {{ payInfo.pp_deposit_name || '-' }}
              </p>
            </li>
            <li class="_list">
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
      </div>
      <!-- 우측 결제 정보 E -->
    </div>
    <!-- 3) 주문정보들 E -->
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
        orderInfo: {},
        payInfo: {},
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
</style>
