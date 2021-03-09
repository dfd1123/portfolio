<template>
  <div
    id="dg-apply-refund-wrapper"
    class="l-page-wrapper"
  >
    <!-- 1) 주문 취소신청 구역 -->
    <div class="l-con-area full">
      <article class="l-con-article mb-25">
        <div class="l-con-title-group type02 mb-18">
          <h2 class="in-subject">
            환불 신청
          </h2>
        </div>

        <!-- 환불신청 리스트 -->
        <div class="l-contents-group">
          <ul class="l-grid-group">
            <li class="l-grid-list l-col-1">
              <!-- * 주문일시, 주문번호 정보라벨 -->
              <dl class="dg-about-odnum-label">
                <dt>주문일시</dt>
                <dd>{{ orderLists[0]? orderLists[0].created_at : '-' }}</dd>
                <dt>주문번호</dt>
                <dd>{{ orderLists[0]? orderLists[0].order_id : '-' }}</dd>
              </dl>
              <!-- * 주문일시, 주문번호 정보라벨 E -->
            </li>

            <li class="l-grid-list l-col-1">
              <!-- * 상품내역 카드 (취소일경우 type-cancel 클래스 추가) -->
              <div
                v-for="orderList in orderLists"
                :key="'order'+orderList.order_no"
                class="dg-clothes-history-card type-cancel"
              >
                <div class="in-content">
                  <div class="inner-box">
                    <figure class="dg-clothes-thumbnail">
                      <router-link
                        v-if="ConvertImage(orderList.images).length > 0"
                        class="in-image"
                        :to="'/product/view/'+orderList.item_id"
                        :style="'background-image: url('+storageUrl+ConvertImage(orderList.images)[0]+');'"
                      />
                      <router-link
                        v-else
                        class="in-image"
                        :to="'/product/view/'+orderList.item_id"
                        style="background-image: url('/images/img/thumbnail.png');"
                      />
                    </figure>
                    <ul class="_informations">
                      <li class="_sellername">
                        <router-link :to="'/store/'+orderList.store_id">
                          {{ orderList.company_name }}
                        </router-link>
                      </li>
                      <li class="_prdtnum">
                        <router-link :to="'/product/view/'+orderList.item_id">
                          {{ orderList.item_id }}
                        </router-link>
                      </li>
                    </ul>
                    <h3 class="_name">
                      <router-link :to="'/product/view/'+orderList.item_id">
                        {{ orderList.item_name }}
                      </router-link>
                    </h3>
                    <div class="_options">
                      <dl class="_op_category">
                        <dt class="_op_tit">
                          옵션
                        </dt>
                        <dd class="_op_list">
                          <span
                            v-for="(option,index) in OptionFormat(orderList)"
                            :key="'option'+index"
                          >{{ option }}</span>
                        </dd>
                      </dl>
                      <div class="_amount">
                        <span>수량 : <b>{{ NumberFormat(orderList.qty || 0) }}</b>개</span>
                      </div>
                    </div>
                    <div class="_price">
                      <span class="_prdt_price">상품금액 <b>{{ NumberFormat((orderList.item_price + (orderList.item_option_price || 0)) * orderList.qty ) }} 원</b></span>
                      <span class="_sale_price">할인금액 <b>- {{ NumberFormat((orderList.coupon_price || 0)) }} 원</b></span>
                      <span class="_total_price">총 상품금액 <b>{{ NumberFormat(orderList.this_price || 0) }} 원</b></span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- * 상품내역 카드 (취소일경우 type-cancel 클래스 추가) E -->
            </li>

            <li class="l-grid-list l-col-1">
              <!-- * 최종 가격 안내 -->
              <div class="dg-final-price-label">
                <span class="_delivry_price">배송비 <b class="_point_size">{{ NumberFormat(orderLists[0]?orderLists[0].send_cost:0) }} 원</b></span>
              </div>
              <!-- * 최종 가격 안내 E -->
            </li>
          </ul>
        </div>
        <!-- 환불신청 리스트 E -->
      </article>
    </div>
    <!-- 1) 주문 취소신청 구역 E -->

    <!-- 2) 주문정보들 -->
    <div class="dg-order-info-group clear_both">
      <!-- 좌측 정보 -->
      <div class="in-detail-info">
        <!-- [1] 환불사유 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            환불 사유
          </h4>
          <ul class="in-information">
            <li class="_list _list--reason">
              <input
                type="radio"
                id="refund_reason1"
                class="dg-input-checkbox display_none"
                value="주문에 이상은 없으나 구매의사 없음(변심)"
                v-model="form.refund_reason"
              >
              <label
                for="refund_reason1"
                class="dg-input-checkbox_label"
              ></label>
              <label
                for="refund_reason1"
                class="_txt"
              >주문에 이상은 없으나 구매의사 없음(변심)</label>
            </li>
            <li class="_list _list--reason">
              <input
                type="radio"
                id="refund_reason2"
                class="dg-input-checkbox display_none"
                value="사이즈 또는 색상, 수량 등을 잘못 선택함"
                v-model="form.refund_reason"
              >
              <label
                for="refund_reason2"
                class="dg-input-checkbox_label"
              ></label>
              <label
                for="refund_reason2"
                class="_txt"
              >사이즈 또는 색상, 수량 등을 잘못 선택함</label>
            </li>
            <li class="_list _list--reason">
              <input
                type="radio"
                id="refund_reason3"
                class="dg-input-checkbox display_none"
                value="다른 상품 구매 / 타 쇼핑몰에서 저렴하게 판매"
                v-model="form.refund_reason"
              >
              <label
                for="refund_reason3"
                class="dg-input-checkbox_label"
              ></label>
              <label
                for="refund_reason3"
                class="_txt"
              >다른 상품 구매 / 타 쇼핑몰에서 저렴하게 판매</label>
            </li>
            <li class="_list _list--reason">
              <input
                type="radio"
                id="refund_reason4"
                class="dg-input-checkbox display_none"
                value="상품이 오랫동안 도착하지 않음"
                v-model="form.refund_reason"
              >
              <label
                for="refund_reason4"
                class="dg-input-checkbox_label"
              ></label>
              <label
                for="refund_reason4"
                class="_txt"
              >상품이 오랫동안 도착하지 않음</label>
            </li>
            <li class="_list _list--reason">
              <input
                type="radio"
                id="refund_reason5"
                class="dg-input-checkbox display_none"
                value="상품등록 정보에 오류가 있음"
                v-model="form.refund_reason"
              >
              <label
                for="refund_reason5"
                class="dg-input-checkbox_label"
              ></label>
              <label
                for="refund_reason5"
                class="_txt"
              >상품등록 정보에 오류가 있음</label>
            </li>
          </ul>
          <div class="in-reason-detail">
            <h5 class="_rd_subject">
              상세내용
            </h5>
            <div class="_rd_textarea">
              <textarea
                placeholder="취소 사유를 상세히 입력해주세요"
                v-model="form.refund_detail"
              ></textarea>
              <span>{{ RequestMsgLength }} / 500</span>
            </div>
            <span class="_rd_etc_ment">- 취소/환불 사유를 정확히 적어주시면 빠른 처리 및 동글 스토어
              개선에 도움이 됩니다.</span>
          </div>
        </article>
        <!-- [1] E -->
      </div>
      <!-- 좌측 정보 E -->

      <!-- 우측 환불 정보 -->
      <div class="in-pay-info">
        <!-- [1] 환불 정보 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            환불 정보
          </h4>
          <ul class="in-information mb-30">
            <li class="_list">
              <span class="_label">상품금액 합계</span>
              <p class="_txt">
                {{ NumberFormat(orderLists[0]?(requestCancelPrice - orderLists[0].send_cost) : 0) }} 원
              </p>
            </li>
            <li class="_list">
              <span class="_label">배송비</span>
              <p class="_txt">
                {{ NumberFormat(orderLists[0]?orderLists[0].send_cost : 0) }} 원
              </p>
            </li>
            <li class="_list _list--sale">
              <span class="_label">보유쿠폰 할인</span>
              <p class="_txt">
                - {{ NumberFormat(TotalCouponPrice || 0) }} 원
              </p>
            </li>
            <li class="_list _list--final">
              <span class="_label">최종 환불 금액</span>
              <p class="_txt">
                <b>{{ NumberFormat(requestCancelPrice) }} 원</b>
              </p>
            </li>
          </ul>
          <button
            type="button"
            class="theme-btn-gradient btn-shadow"
            @click="Submit"
          >
            환불신청
          </button>
        </article>
        <!-- [1] E -->
      </div>
      <!-- 우측 환불 정보 E -->
    </div>
    <!-- 2) 주문정보들 E -->
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        orderLists: [],
        requestCancelPrice: 0,
        form: {
          order_id: this.$route.query.order_id,
          order_no: Number(this.$route.query.order_no),
          _method: 'put',
          refund_reason: null,
          refund_detail: null
        }
      }
    },
    beforeCreate () {
      if (!this.$route.query.order_id && !this.$route.query.order_no) {
        this.$router.go(-1)
      }
    },
    async created () {
      this.$store.commit('ProgressShow')

      /*
      if (!this.$store.state.user.account_number) {
        const result = await this.WarningAlert('환불 받을 계좌번호가 등록되어 있지 않습니다.\n환불계좌 등록 페이지로 이동합니다.')
        if (result) {
          this.$store.commit('ProgressHide')

          this.$router.push('/mypage/payment/info')
        }
      }
      */

      await this.OrderLoad()

      if (this.orderLists.length === 0) {
        const result = await this.WarningAlert('잘못된 요청입니다.')
        if (result) {
          this.$router.go(-1)
        }
      }

      this.$store.commit('ProgressHide')
    },
    computed: {
      TotalCouponPrice () {
        let couponPrice = this.orderLists[0] ? this.orderLists[0].coupon_discount : 0

        this.orderLists.forEach(orderList => {
          couponPrice += orderList.coupon_price
        })

        return couponPrice
      },
      RequestMsgLength () {
        if (this.form.refund_detail) {
          return this.form.refund_detail.length
        }
        return 0
      }
    },
    methods: {
      async OrderLoad () {
        const params = this.form

        if (this.$route.query.order_id) {
          try {
            const res = (await this.$http.get(this.$APIURI + 'order/mypage_detail', { params })).data

            if (res.state === 1) {
              res.query.orders.forEach(order => {
                if (order.od_status === 'delivery_complete' || order.od_status === 'delivery_wait') {
                  this.orderLists.push(order)
                }
              })
              this.requestCancelPrice = res.query.request_cancel_price
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        } else if (this.$route.query.order_no) {
          try {
            const res = (await this.$http.get(this.$APIURI + 'order/mypage_detail_part', { params })).data

            if (res.state === 1) {
              res.query.orders.forEach(order => {
                if (order.od_status === 'delivery_complete' || order.od_status === 'delivery_wait') {
                  this.orderLists.push(order)
                }
              })
              this.requestCancelPrice = res.query.request_cancel_price
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        }
      },
      Validation () {
        if (!this.form.refund_reason || this.form.refund_reason === '') {
          this.WarningAlert('환불 사유를 선택해주세요!')
          return false
        }

        if (!this.form.refund_detail || this.form.refund_detail === '') {
          this.WarningAlert('상세 사유를 입력해주세요!')
          return false
        }

        return true
      },
      async Submit () {
        if (!this.Validation()) {
          return false
        }
        const params = this.form
        const result = await this.Confirm('정말 주문을 환불 신청 하시겠습니까?')
        if (result) {
          if (this.$route.query.order_id) {
            try {
              const res = (await this.$http.post(this.$APIURI + 'order/order_all_refund', params)).data

              if (res.state === 1) {
                const reslt = await this.SuccessAlert('성공적으로 환불을 신청하였습니다.')
                if (reslt) {
                  this.$router.push('/mypage/cancel/history')
                }
              } else {
                if (res.msg === '2222 : 환불 계좌주 성명조회 실패') {
                  const result = await this.WarningAlert('환불 계좌주 성명조회에 실패하였습니다.\n환불 계좌 정보가 정확한지 다시 한번 확인해주세요.')
                  if (result) {
                    this.$router.push('/mypage/payment/info')
                  }
                }

                this.WarningAlert(res.msg)
              }
            } catch (e) {
              console.log(e)
            }
          } else {
            try {
              const res = (await this.$http.post(this.$APIURI + 'order/order_part_refund', params)).data

              if (res.state === 1) {
                const reslt = await this.SuccessAlert('성공적으로 환불을 신청하였습니다.')
                if (reslt) {
                  this.$router.push('/mypage/cancel/history')
                }
              } else {
                if (res.msg === '2222 : 환불 계좌주 성명조회 실패') {
                  const result = await this.WarningAlert('환불 계좌주 성명조회에 실패하였습니다.\n환불 계좌 정보가 정확한지 다시 한번 확인해주세요.')
                  if (result) {
                    this.$router.push('/mypage/payment/info')
                  }
                }

                this.WarningAlert(res.msg)
              }
            } catch (e) {
              console.log(e)
            }
          }
        }
      },
      OptionFormat (orderList) {
        if (orderList.option) {
          const options = orderList.option.split(',')
          const optionSubject = orderList.option_subject.split(',')
          const productOption = []

          for (let i = 0; i < optionSubject.length; i++) {
            productOption.push(optionSubject[i] + ' : ' + options[i])
          }

          productOption.push('추가비용: +' + (orderList.item_option_price || 0) + '원')

          return productOption
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
