<template>
  <div
    id="dg-apply-cancel-wrapper"
    class="l-ordercancel-wrapper"
  >
    <!-- 1) 주문 취소신청 구역 -->
    <div class="l-con-area dg-cancel-product-group full">
      <article class="l-con-article">
        <div class="_page_title_wrap">
          <h2>
            환불 신청
            <button
              type="button"
              class="_back_btn"
              @click="$router.go(-1)"
            >
              뒤로가기
            </button>
          </h2>
        </div>

        <!-- 취소신청 리스트 -->
        <div>
          <ul class="l-grid-group">
            <li class="l-grid-list l-col-1">
              <!-- * 주문일시, 주문번호 정보라벨 -->
              <ul class="dg-about-odnum-label">
                <li>{{ orderLists[0]? orderLists[0].created_at : '-' }}</li>
                <li class="_num">
                  {{ orderLists[0]? orderLists[0].order_id : '-' }}
                </li>
              </ul>
              <!-- * 주문일시, 주문번호 정보라벨 E -->
            </li>
            <li class="l-grid-list l-col-1">
              <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) -->
              <div class="dg-clothes-history-card type-cart type-detail ">
                <div class="in-content">
                  <div
                    v-for="orderList in orderLists"
                    :key="'order'+orderList.order_no"
                    class="inner-wrap"
                  >
                    <div class="inner-box">
                      <h3 class="_name">
                        <router-link :to="'/product/view/'+orderList.item_id">
                          [{{ orderList.company_name }}] {{ orderList.item_name }}
                        </router-link>
                      </h3>
                      <div class="_options">
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
                        <div class="_in_col">
                          <dl class="_op_category">
                            <dd class="_op_list">
                              <span
                                v-for="(option,index) in OptionFormat(orderList)"
                                :key="'option'+index"
                              >{{ option }}</span>
                            </dd>
                          </dl>
                          <div class="_amount">
                            <span>수량 : {{ orderList.qty }}개</span>
                          </div>
                          <div class="_price mb-0">
                            <span class="_prdt_price">{{ NumberFormat((orderList.item_price + (orderList.item_option_price || 0)) * orderList.qty ) }} 원</span>
                            <span
                              v-if="orderList.coupon_id"
                              class="_sale_badge"
                            >쿠폰할인</span>
                          </div>
                        </div>
                      </div>
                      <div class="_price_wrap mb-0">
                        <div class="_sale_price">
                          <span>쿠폰 할인</span> <b>- {{ NumberFormat((orderList.coupon_price || 0)) }} 원</b>
                        </div>
                        <div class="_total_price">
                          <span>총 상품금액</span> <b>{{ NumberFormat(orderList.this_price || 0) }} 원</b>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) E -->
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
        <!-- 취소신청 리스트 E -->
      </article>
    </div>
    <!-- 1) 주문 취소신청 구역 E -->

    <!-- 2) 주문정보들 -->
    <div class="dg-order-info-group clear_both">
      <div class="in-detail-info">
        <!-- [1] 취소사유 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            <span>환불 사유</span>
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
          </div>
        </article>
        <!-- [1] E -->
      </div>
      <div class="in-pay-info">
        <!-- [2] 취소 정보 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            환불 정보
          </h4>

          <!-- * 전체금액 안내 카드 -->
          <div class="dg-total-price-card">
            <ul class="in-price-infos">
              <li class="_col _col--base">
                <span class="_label">상품금액 합계</span>
                <b class="_prc"><i>{{ NumberFormat(orderLists[0]?(requestCancelPrice - orderLists[0].send_cost) : 0) }}</i>원</b>
              </li>
              <li class="_col col--delivery">
                <span class="_label">배송비</span>
                <b class="_prc"><i>{{ NumberFormat(orderLists[0]?orderLists[0].send_cost : 0) }}</i>원</b>
              </li>
              <li class="_col col--sale">
                <span class="_label">보유쿠폰 할인</span>
                <b class="_prc"><i>-{{ NumberFormat(TotalCouponPrice || 0) }}</i>원</b>
              </li>
              <li class="_col _col--total">
                <span class="_label">최종 환불금액</span>
                <b class="_prc"><i>{{ NumberFormat(requestCancelPrice) }}</i>원</b>
              </li>
            </ul>
          </div>
          <!-- * 전체금액 안내 카드 E -->
          <button
            type="button"
            class="theme-btn-gradient btn-shadow"
            @click="Submit"
          >
            환불신청
          </button>
        </article>
        <!-- [2] E -->
      </div>
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
              this.$store.commit('ProgressShow')

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
            } finally {
              this.$store.commit('ProgressHide')
            }
          } else {
            try {
              this.$store.commit('ProgressShow')

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
            } finally {
              this.$store.commit('ProgressHide')
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
