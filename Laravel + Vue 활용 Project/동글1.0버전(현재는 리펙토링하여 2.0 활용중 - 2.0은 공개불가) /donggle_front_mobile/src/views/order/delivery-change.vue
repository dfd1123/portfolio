<template>
  <div
    id="dg-change-address-wrapper"
    class="l-ordercancel-wrapper"
  >
    <!-- 1) 주문내역들 -->
    <div class="l-con-area dg-cancel-product-group full">
      <article class="l-con-article">
        <div class="_page_title_wrap">
          <h2>
            배송지 변경
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
                <li>{{ orderLists[0]?orderLists[0].created_at : '-' }}</li>
                <li class="_num">
                  {{ orderLists[0]?orderLists[0].order_id : '-' }}
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
                            v-if="ConvertImage(orderList.image).length > 0"
                            class="in-image"
                            :to="'/product/view/'+orderList.item_id"
                            :style="'background-image: url('+storageUrl+ConvertImage(orderList.image)[0]+');'"
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
                            <span>수량 : {{ NumberFormat(orderList.qty || 0) }}개</span>
                          </div>
                          <div class="_price mb-0">
                            <span class="_prdt_price">{{ NumberFormat((orderList.item_price + (orderList.item_option_price || 0)) * orderList.qty ) }} 원</span>
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
                <span class="_delivry_price">배송비 <b class="_point_size">{{ orderLists[0]?NumberFormat(orderLists[0].send_cost):0 }} 원</b></span>
              </div>
              <!-- * 최종 가격 안내 E -->
            </li>
          </ul>
        </div>
        <!-- 취소신청 리스트 E -->
      </article>
    </div>
    <!-- 1) 주문내역들 구역 E -->

    <!-- 2) 주문정보들 -->
    <div class="dg-order-info-group clear_both">
      <!-- 좌측 정보 -->
      <div class="in-detail-info">
        <!-- [1] 현재 배송지 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            현재 배송지
          </h4>
          <ul class="in-information">
            <li class="_list _info">
              <p class="_txt _name">
                {{ orderLists[0]?orderLists[0].g_name : '-' }}
              </p>
            </li>
            <li class="_list _info _address">
              <p class="_txt _add">
                {{ orderLists[0]?orderLists[0].g_addr1 : '-' }}
              </p>
            </li>
            <li class="_list _info _address">
              <p class="_txt _add">
                {{ orderLists[0]?orderLists[0].g_addr2 : '-' }}
              </p>
            </li>
            <li class="_list _info _address">
              <p class="_txt _add">
                {{ orderLists[0]?orderLists[0].g_post_num : '-' }}
              </p>
            </li>
            <li class="_list _info">
              <p class="_txt _gray">
                {{ orderLists[0]?orderLists[0].g_hp : '-' }}
              </p>
            </li>
          </ul>
        </article>
        <!-- [1] E -->

        <!-- [2] 배송지 정보 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            <span>변경할 배송지 정보</span>
          </h4>
          <ul class="in-information">
            <li class="_list">
              <span class="_label">이름</span>
              <div class="_input_group">
                <input
                  type="text"
                  class="_input"
                  placeholder="이름을 입력하세요."
                  v-model="form.g_name"
                >
              </div>
            </li>
            <li class="_list">
              <span class="_label">전화번호</span>
              <div class="_input_group">
                <input
                  type="tel"
                  class="_input"
                  placeholder="전화번호를 입력하세요."
                  v-model="form.g_hp"
                >
              </div>
            </li>
            <li class="_list _list--post">
              <span class="_label">우편번호</span>
              <div class="_input_group">
                <input
                  type="text"
                  class="_input"
                  placeholder="우편번호"
                  :readonly="true"
                  v-model="form.g_post_num"
                >
                <button
                  type="button"
                  class="square-btn-outline"
                  @click="daumAdressPopup = true"
                >
                  주소찾기
                </button>
              </div>
            </li>
            <li class="_list">
              <span class="_label">기본주소</span>
              <div class="_input_group">
                <input
                  type="text"
                  class="_input"
                  placeholder="기본주소를 입력하세요."
                  :readonly="true"
                  v-model="form.g_addr1"
                >
              </div>
            </li>
            <li class="_list">
              <span class="_label">상세주소</span>
              <div class="_input_group">
                <input
                  type="text"
                  class="_input"
                  placeholder="상세주소를 입력하세요."
                  v-model="form.g_addr2"
                >
              </div>
            </li>
            <li class="_list _list--textarea">
              <span class="_label">배송 요청사항</span>
              <div class="_input_group">
                <textarea
                  class="_textarea"
                  placeholder="배송시 요청사항을 작성해주세요"
                  v-model="form.delivery_memo"
                ></textarea>
              </div>
            </li>
          </ul>
        </article>
        <!-- [4] E -->
      </div>
      <!-- 좌측 정보 E -->
    </div>
    <!-- 우측 배송지 변경카드 -->
    <div class="in-pay-info">
      <!-- [1] 배송지 변경 -->
      <article class="l-con-article change-address_end">
        <h4 class="in-subject dg_blind">
          배송지 변경
        </h4>
        <div class="in-text-group">
          <p>
            [변경할 배송지 정보]에 입력하신 정보로<br>배송지를
            변경하시겠습니까?
          </p>
        </div>
        <button
          type="button"
          class="theme-btn-gradient btn-shadow"
          @click="Submit"
        >
          변경하기
        </button>
      </article>
      <!-- [1] E -->
    </div>
    <div :class="['vue-daum-postcode-wrapper', {'fix-active':daumAdressPopup}]">
      <div class="_popup_title">
        <h4>
          주소찾기
          <button
            type="button"
            class="_close_btn"
            @click="daumAdressPopup = false"
          >
          </button>
        </h4>
      </div>
      <vue-daum-postcode @complete="AddressSelect" />
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        orderLists: [],
        form: {
          _method: 'put',
          g_name: null,
          g_hp: null,
          g_addr1: null,
          g_addr2: null,
          g_extra_addr: null,
          g_addr_jibeon: null,
          g_post_num: null,
          delivery_memo: null
        },
        daumAdressPopup: false
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

      this.form.order_id = this.orderId

      await this.OrderLoad()

      if (this.orderLists[0].od_status === 'shipping' || this.orderLists[0].od_status === 'delivery_complete' || this.orderLists[0].od_status === 'order_complete') {
        const result = await this.WarningAlert('배송지 변경이 불가한 주문입니다! 이전 페이지로 돌아갑니다.')
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
        const params = {
          order_id: this.orderId
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'order/mypage_detail', { params })).data

          if (res.state === 1) {
            this.orderLists = res.query.orders
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      Validation () {
        if (!this.form.g_name) {
          this.WarningAlert('배송자명을 입력하세요!')
          return false
        }

        if (!this.form.g_hp) {
          this.WarningAlert('배송받을 분의 전화번호를 입력하세요!')
          return false
        }

        if (!this.form.g_addr1 || !this.form.g_addr2 || !this.form.g_post_num) {
          this.WarningAlert('배송지를 정확히 입력하세요!')
          return false
        }

        return true
      },
      async Submit () {
        if (!this.Validation()) {
          return false
        }
        const params = this.form
        const result = await this.Confirm('정말 배송지를 변경 하시겠습니까?')
        if (result) {
          try {
            const res = (await this.$http.post(this.$APIURI + 'order/delivery_change', params)).data

            if (res.state === 1) {
              const reslt = await this.SuccessAlert('성공적으로 배송지를 변경 하였습니다.')
              if (reslt) {
                this.$router.push('/mypage/order/history')
              }
            } else {
              this.WarningAlert(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        }
      },
      AddressSelect (address) {
        this.daumAdressPopup = false
        this.form.g_post_num = address.zonecode
        this.form.g_addr1 = address.roadAddress
        this.form.g_addr_jibeon = address.jibunAddress
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
