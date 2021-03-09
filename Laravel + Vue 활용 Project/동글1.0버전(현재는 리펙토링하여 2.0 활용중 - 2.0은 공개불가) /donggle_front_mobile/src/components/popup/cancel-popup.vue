<template>
  <div class="_popup_wrapper _cancel_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <!-- 환불시에는 "환불 신청 내역"으로 바뀌어야 함 -->
        <h4>
          취소/환불 신청 내역
          <button
            type="button"
            class="_close_btn"
            @click="$emit('close-event')"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <div class="cancel_title">
          <!-- 경우에 따라 "환불 신청중", "환불 완료", "환불 거절", "취소 신청중", "취소 완료", "취소 거절" -->
          <h5 class="_title">
            {{ OderStatus }}
          </h5>
          <span class="_date">{{ itemList.created_at }}</span>
          <span class="_num">{{ itemList.order_id }}</span>
        </div>

        <div class="_cancel_reason _cancel_my_reason">
          <h5 class="_title">
            취소/환불 사유
          </h5>
          <p class="_reason">
            {{ itemList.refund_reason }}
          </p>
          <div
            class="_reason_detail"
            v-if="itemList.refund_detail"
            v-html="itemList.refund_detail"
          >
          </div>
        </div>
        <!-- 거절시에만 생김. 거절사유가 없을 때에는 이 레이아웃자체가 보이지 않음 -->
        <div
          v-if="itemList.reject_reason"
          class="_cancel_reason _cancel_store_reason"
        >
          <h5 class="_title">
            거절사유
          </h5>
          <div
            class="_reason_detail"
            v-html="itemList.reject_reason"
          >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      itemList: {
        type: Object,
        required: true
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
    }
  }
</script>

<style lang="scss" scoped>
</style>
