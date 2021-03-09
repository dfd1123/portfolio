<template>
  <div class="dg-clothes-history-card type-cart type-detail ">
    <div class="in-content">
      <div class="inner-wrap">
        <div class="inner-box">
          <div :class="['status-title', {'pd_12': !cancelNrefund}]">
            <h3>{{ OderStatus }}</h3>
            <div
              class="view_more"
              v-if="cancelNrefund"
              @click="$emit('cancel-popup-open', itemList)"
            >
              신청내역 보기
            </div>
            <input
              v-if="itemList.od_status !== 'order_complete' && !cancelNrefund"
              type="checkbox"
              :id="'subBtns'+itemList.order_no"
              class="status_more_btn display_none"
              v-model="subBtnsShow"
            >
            <label
              v-if="itemList.od_status !== 'order_complete' && !cancelNrefund"
              :for="'subBtns'+itemList.order_no"
            >
              <img
                src="/images/mobile/btn/btn_etc.svg"
                alt="정보보기"
              >
            </label>
            <div
              v-if="itemList.od_status !== 'order_complete'"
              class="status_more_view"
            >
              <div
                class="_bgblack"
                @click="subBtnsShow = false"
              ></div>
              <div class="_btn_wrap">
                <router-link
                  v-if="itemList.od_status === 'deposit_wait' || itemList.od_status === 'order_apply'"
                  :to="'/cancel/request?order_id='+itemList.order_id"
                  class="_link_btn"
                >
                  전체 주문취소
                </router-link>
                <router-link
                  v-if="itemList.od_status === 'deposit_wait' || itemList.od_status === 'order_apply'"
                  :to="'/cancel/request?order_no='+itemList.order_no"
                  class="_link_btn"
                >
                  부분 주문취소
                </router-link>
                <router-link
                  v-if="itemList.settle_case === 'DEPOSIT'"
                  :to="'/order/detail/'+itemList.order_id"
                  class="_link_btn"
                >
                  계좌조회
                </router-link>
                <router-link
                  v-if="itemList.od_status === 'deposit_wait' || itemList.od_status === 'order_apply' || itemList.od_status === 'delivery_wait'"
                  :to="'/delivery/change/'+itemList.order_id"
                  class="_link_btn"
                >
                  배송지 변경
                </router-link>
                <router-link
                  :to="'/refund/request?order_no='+itemList.order_no"
                  v-if="itemList.od_status === 'delivery_complete' || itemList.od_status === 'delivery_wait'"
                  class="_link_btn"
                >
                  환불신청
                </router-link>
                <button
                  type="button"
                  class="_btn_close"
                  v-ripple
                  @click="subBtnsShow = false"
                >
                  닫기
                </button>
              </div>
            </div>
          </div>
          <h3 class="_name">
            <router-link :to="'/store/'+itemList.store_id">
              [{{ itemList.company_name || '-' }}]
            </router-link>
            <router-link :to="'/product/view/'+itemList.item_id">
              {{ itemList.item_name || '-' }}
            </router-link>
          </h3>
          <div class="_options">
            <figure class="dg-clothes-thumbnail">
              <router-link
                v-if="ConvertImage(itemList.images).length > 0"
                class="in-image"
                :to="'/product/view/'+itemList.item_id"
                :style="'background-image: url('+storageUrl+ConvertImage(itemList.images)[0]+');'"
              />
              <router-link
                v-else
                class="in-image"
                :to="'/product/view/'+itemList.item_id"
                style="background-image: url('/images/img/thumbnail.png');"
              />
            </figure>
            <div class="_in_col">
              <dl class="_op_category">
                <dd class="_op_list">
                  <span
                    v-for="(option,index) in OptionFormat()"
                    :key="'option'+index"
                  >{{ option }}</span>
                </dd>
              </dl>
              <div class="_amount">
                <span>수량 : {{ itemList.qty }}개</span>
              </div>
              <div class="_price mb-0">
                <span class="_prdt_price">{{ NumberFormat(itemList.this_price) }} 원</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        subBtnsShow: false
      }
    },
    props: {
      itemList: {
        type: Object,
        required: true
      },
      cancelNrefund: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      OderStatus () {
        const orderStatus = this.itemList.od_status
        if (orderStatus === 'deposit_wait') {
          return '주문접수'
        } else if (orderStatus === 'order_apply') {
          return '입금확인'
        } else if (orderStatus === 'order_cancel') {
          return '주문취소'
        } else if (orderStatus === 'refund_apply') {
          return '환불신청'
        } else if (orderStatus === 'refund_reject') {
          return '환불거절'
        } else if (orderStatus === 'refund_complete') {
          return '환불승인'
        } else if (orderStatus === 'delivery_wait') {
          return '배송대기'
        } else if (orderStatus === 'shipping') {
          return '배송중'
        } else if (orderStatus === 'delivery_complete') {
          return '배송완료'
        } else if (orderStatus === 'order_complete') {
          return '구매확정'
        }
        return ''
      }
    },
    methods: {
      OptionFormat () {
        if (this.itemList.option) {
          const options = this.itemList.option.split(',')
          const optionSubject = this.itemList.option_subject.split(',')
          const productOption = []

          for (let i = 0; i < optionSubject.length; i++) {
            productOption.push(optionSubject[i] + ' : ' + options[i])
          }

          productOption.push('추가비용 : +' + (this.itemList.item_option_price || 0) + '원')

          return productOption
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
