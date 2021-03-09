<template>
  <div
    id="dg-order-complete-wrapper"
    class="dg-order-wrapper"
  >
    <!-- 1) 주문완료창 -->
    <div class="l-con-area full">
      <div class="l-con-article">
        <div class="type02 not-process-first">
          <div class="_page_title_wrap">
            <h2>
              주문완료
              <router-link
                to="/"
                class="_home_btn"
              >
                홈으로
              </router-link>
            </h2>
          </div>
          <div class="in-breadcrumb-wrap">
            <ul class="in-breadcrumb">
              <li class="_list">
                <i class="_num">1</i>
                <span>장바구니</span>
                <img
                  src="/images/mobile/icon/icon_breadcrumb_arrow.svg"
                  alt="arrow"
                  class="_next"
                >
              </li>
              <li class="_list">
                <i class="_num">2</i>
                <span>주문/결제<span>
                  <img
                    src="/images/mobile/icon/icon_breadcrumb_arrow.svg"
                    alt="arrow"
                    class="_next"
                  >
                </span></span>
              </li>
              <li class="_list active">
                <i class="_num">3</i>
                <span>주문완료</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="l-contents-group">
          <!-- * 주문상태 안내 카드 -->
          <div class="dg-order-status-card">
            <div class="in-icon">
              <img
                src="/images/icon/icon_complete.svg"
                alt="complete icon"
              >
            </div>
            <h2 class="in-status-tit">
              주문이 완료되었습니다.
            </h2>
            <p class="in-status-desc">
              '{{ $store.state.user.name }}' 고객님 저희 동글에서 구매를 해주셔서 감사합니다.<br>아래 주문상세내역서는 마이페이지 > '주문내역 조회'에서 확인 가능합니다.
            </p>

            <!-- ※ 결제수단 무통장 입금으로 했을 시에 보여야함 -->
            <div
              v-if="payInfo.pp_settle_case.includes('VBANK')"
              class="in-paytype-bank"
            >
              <strong class="_tit">무통장 입금(가상계좌) 정보</strong>
              <ul class="_box">
                <li class="_bank">
                  <b>{{ payInfo.pp_account_number || '-' }}</b><span>{{ payInfo.pp_bank_account || '-' }}</span><span>{{ payInfo.pp_deposit_name || '-' }}</span>
                </li>
                <li class="_expire">
                  <span>계좌 유효 기간 :</span><b>{{ $moment(orderInfo.created_at).add(3, 'days').format('YYYY년 MM월 DD일 hh시 mm분') }}</b>
                </li>
              </ul>
            </div>
            <!-- ※ 결제수단 무통장 입금으로 했을 시에 보여야함 END -->
            <!-- * 싱글버튼 -->
            <div class="dg-button-wrap dg-button-wrap--single">
              <router-link
                to="/"
                class="theme-btn-dark"
              >
                쇼핑 계속하기
              </router-link>
            </div>
            <!-- * 싱글버튼 E -->
          </div>
          <!-- * 주문상태 안내 카드 E -->
        </div>
      </div>
    </div>
    <!-- 1) 주문완료창 E -->

    <!-- 2) 주문상세내역 구역 -->
    <div class="l-con-area full">
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
    </div>
    <!-- 2) 주문상세내역 구역 E -->

    <!-- 3) 주문정보들 -->
    <div
      v-if="created"
      class="dg-order-info-group not-input clear_both"
    >
      <!-- 배송 정보 -->
      <div class="in-detail-info">
        <div class="ordered-list-group_wrap">
          <input
            id="test-2"
            type="checkbox"
            class="order_check display_none"
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
                        {{ payInfo.order_id }}
                      </p>
                    </li>
                    <li class="_list clear_both">
                      <span class="_label">결제일시</span>
                      <p class="_txt">
                        {{ payInfo.pp_receipt_time || '미결제' }}
                      </p>
                    </li>
                    <li class="_list clear_both payMethodLi">
                      <span class="_label">결제방식</span>
                      <p
                        v-if="payInfo.pp_settle_case.includes('VBANK')"
                        class="_txt"
                      >
                        가상계좌
                      </p>
                      <p
                        v-if="payInfo.pp_settle_case.includes('CARD')"
                        class="_txt"
                      >
                        신용카드
                      </p>
                      <p
                        v-if="payInfo.pp_settle_case.includes('BANK')"
                        class="_txt"
                      >
                        계좌이체
                      </p>
                      <p
                        v-if="payInfo.pp_settle_case.includes('CELLPHONE')"
                        class="_txt"
                      >
                        휴대폰결제
                      </p>
                      <p
                        v-if="payInfo.pp_settle_case.includes('transfer')"
                        class="_txt"
                      >
                        페이플 계좌 결제
                      </p>
                      <p
                        v-if="payInfo.pp_settle_case.includes('card')"
                        class="_txt"
                      >
                        페이플 카드 결제
                      </p>
                    </li>
                    <li class="_list clear_both">
                      <span class="_label">최종 결제금액</span>
                      <p class="_txt">
                        {{ NumberFormat(payInfo.pp_receipt_price || 0) }}원
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
                  </div>
                </article>
                <!-- [1] E -->
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- 결제 정보 E -->
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
        orders: [],
        created: false
      }
    },
    beforeCreate () {
      if (!this.$route.query.orderId) {
        this.$router.go(-1)
      }
    },
    async created () {
      this.payInfo.pp_settle_case = '-'

      const params = {
        order_id: this.$route.query.orderId
      }
      try {
        this.$store.commit('ProgressShow')

        const res = (await this.$http.get(this.$APIURI + 'order/mypage_detail', { params })).data
        const res2 = (await this.$http.get(this.$APIURI + 'order/pay_info', { params })).data

        if (res.state === 1) {
          this.orders = res.query.orders
          this.orderInfo = this.orders[0] || {}
          this.created = true
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
      } finally {
        this.$store.commit('ProgressHide')
      }

      this.$store.dispatch('DeliveryLoad')
    },
    computed: {
      SettleCase () {
        // console.log(this.orderInfo.settle_case)
        if (this.orders.length === 0) {
          return '-'
        }

        if (this.orderInfo.settle_case === 'DEPOSIT') {
          return '무통장(가상계좌)입금'
        }

        return '-'
      }
    },
    methods: {
      PossibleOrderCancel () {
        const odStatus = this.orderInfo.od_status
        if (odStatus === 'delivery_wait' || odStatus === 'order_apply') {
          return true
        }

        return false
      },
      async OrderCancel () {
        const result = await this.Confirm('정말 주문을 취소하시겠습니까?')
        if (result) {
          const params = {
            order_id: this.orderInfo.order_id,
            _method: 'put'
          }
          try {
            const res = (await this.$http.get(this.$APIURI + 'order/order_all_cancel', params)).data

            if (res.state === 1) {
              this.SuccessAlert('주문이 취소 되었습니다.')
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  li.payMethodLi:first-child:nth-last-child(n) {
    p._txt::after {
      content: ", ";
    }
  }

  li.payMethodLi:last-child {
    p._txt::after {
      display: none;
    }
  }

  .dg-order-wrapper .not-process-first ._page_title_wrap{
    padding-top: calc(50px + 42px);
  }
</style>
