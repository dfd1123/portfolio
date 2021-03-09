<template>
  <div class="_popup_wrapper _cancel_popup_wrapper _cancel_refund_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
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
        <div class="_popup_text_wrap">
          <div class="_cancel_info">
            <span class="_title">신청일시</span>
            <span class="_content _num">{{ itemList.updated_at }}</span>
            <span class="_title _order_number_title">주문번호</span>
            <span class="_content _num">{{ itemList.order_id }}</span>
            <span
              v-if="itemList.od_status === 'order_cancel'"
              class="rounded-btn-dark _cancel_status"
            >주문취소</span>
            <span
              v-else-if="itemList.od_status === 'refund_apply'"
              class="rounded-btn-dark _cancel_status"
            >환불신청</span>
            <span
              v-else-if="itemList.od_status === 'refund_reject'"
              class="rounded-btn-dark _cancel_status"
            >환불거절</span>
            <span
              v-else-if="itemList.od_status === 'refund_complete'"
              class="rounded-btn-dark _cancel_status"
            >환불완료</span>
          </div>
          <div class="_cancel_reason_wrap over-y_scroll">
            <div class="_cancel_reason _cancel_my_reason re">
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
          <div class="dg-dubble_btn_wrap clear_both">
            <button
              type="button"
              class="dg-btn_line dg-dubble_btn"
              @click="$emit('close-event')"
            >
              닫기
            </button>
            <button
              type="button"
              class="dg-btn_gra dg-dubble_btn"
              @click="$emit('close-event')"
            >
              확인
            </button>
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

    }
  }
</script>

<style lang="scss" scoped>
</style>
