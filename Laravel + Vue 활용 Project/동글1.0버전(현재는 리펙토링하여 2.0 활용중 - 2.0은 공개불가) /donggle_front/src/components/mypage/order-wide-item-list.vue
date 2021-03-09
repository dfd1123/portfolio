<template>
  <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) -->
  <div class="dg-clothes-history-card">
    <div class="in-content">
      <div class="inner-box">
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
        <ul class="_informations">
          <li class="_sellername">
            <router-link :to="'/store/'+itemList.store_id">
              {{ itemList.company_name }}
            </router-link>
          </li>
          <li class="_prdtnum">
            <router-link :to="'/product/view/'+itemList.item_id">
              {{ itemList.item_id }}
            </router-link>
          </li>
        </ul>
        <h3 class="_name">
          <router-link :to="'/product/view/'+itemList.item_id">
            {{ itemList.title || itemList.item_name }}
          </router-link>
        </h3>
        <div class="_options">
          <div>
            <dl class="_op_category">
              <dt class="_op_tit">
                옵션
              </dt>
              <dd class="_op_list">
                <span
                  v-for="(option,index) in OptionFormat()"
                  :key="'option'+index"
                >{{ option }}</span>
              </dd>
            </dl>
            <div class="_amount">
              <span>수량 : <b>{{ itemList.qty }}</b>개</span>
            </div>
          </div>
        </div>
        <div class="_price mb-0">
          <span class="_prdt_price">상품금액 <b>{{ NumberFormat((itemList.item_price + itemList.item_option_price) * itemList.qty ) }} 원</b></span>
          <span class="_sale_price">할인금액 <b>- {{ NumberFormat(itemList.coupon_price || 0) }} 원</b></span>
          <span class="_total_price">총 상품금액 <b>{{ NumberFormat(itemList.this_price || 0) }} 원</b></span>
        </div>
        <div
          v-if="btnListShow"
          class="_btn_list"
        >
          <button
            v-if="itemList.od_status === 'deposit_wait' || itemList.od_status === 'order_apply'"
            class="rounded-btn-outline"
            @click="$router.push('/cancel/request?order_id='+itemList.order_id)"
          >
            전체 주문취소
          </button>
          <button
            v-if="itemList.od_status === 'deposit_wait' || itemList.od_status === 'order_apply'"
            class="rounded-btn-outline"
            @click="$router.push('/cancel/request?order_no='+itemList.order_no)"
          >
            부분 주문취소
          </button>
          <button
            v-if="itemList.settle_case === 'DEPOSIT'"
            class="rounded-btn-outline"
            @click="$router.push('/order/detail/'+itemList.order_id)"
          >
            계좌조회
          </button>
          <button
            v-if="itemList.od_status === 'deposit_wait' || itemList.od_status === 'order_apply' || itemList.od_status === 'delivery_wait'"
            class="rounded-btn-outline"
            @click="$router.push('/delivery/change/'+itemList.order_id)"
          >
            배송지 변경
          </button>
          <button
            v-if="itemList.od_status === 'order_cancel' || itemList.od_status === 'refund_apply' || itemList.od_status === 'refund_reject' || itemList.od_status === 'refund_complete'"
            class="rounded-btn-outline"
            @click="$emit('popup-event', itemList)"
          >
            신청내역 보기
          </button>
          <button
            v-if="itemList.od_status === 'delivery_complete' || itemList.od_status === 'delivery_wait'"
            class="rounded-btn-outline"
            @click="$router.push('/refund/request?order_no='+itemList.order_no)"
          >
            환불신청
          </button>
          <!-- <button class="rounded-btn-outline">교환신청</button> -->
        </div>
        <span
          v-if="itemList.od_status === 'deposit_wait'"
          class="rounded-btn-dark _order_status"
        >주문접수</span>
        <span
          v-else-if="itemList.od_status === 'order_apply'"
          class="rounded-btn-dark _order_status"
        >입금확인</span>
        <span
          v-else-if="itemList.od_status === 'order_cancel'"
          class="rounded-btn-dark _order_status"
        >주문취소</span>
        <span
          v-else-if="itemList.od_status === 'refund_apply'"
          class="rounded-btn-dark _order_status"
        >환불신청</span>
        <span
          v-else-if="itemList.od_status === 'refund_reject'"
          class="rounded-btn-dark _order_status"
        >환불거절</span>
        <span
          v-else-if="itemList.od_status === 'refund_complete'"
          class="rounded-btn-dark _order_status"
        >환불승인</span>
        <span
          v-else-if="itemList.od_status === 'delivery_wait'"
          class="rounded-btn-dark _order_status"
        >배송대기</span>
        <span
          v-else-if="itemList.od_status === 'shipping'"
          class="rounded-btn-dark _order_status"
        >배송중</span>
        <span
          v-else-if="itemList.od_status === 'delivery_complete'"
          class="rounded-btn-dark _order_status"
        >배송완료</span>
        <span
          v-else-if="itemList.od_status === 'order_complete'"
          class="rounded-btn-dark _order_status"
        >구매확정</span>
      </div>
    </div>
  </div>
  <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) E -->
</template>

<script>
  export default {
    props: {
      btnListShow: {
        type: Boolean,
        default: false
      },
      itemList: {
        type: Object,
        required: true
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
