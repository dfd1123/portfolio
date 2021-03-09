<template>
  <div>
    <div>
      <!-- * 주문일시, 주문번호 정보라벨 -->
      <dl class="dg-about-odnum-label">
        <dt>주문일시</dt>
        <dd>{{ orderList.created_at || '-' }}</dd>
        <dt>주문번호</dt>
        <dd>{{ orderList.order_id || '-' }}</dd>
        <button
          class="rounded-btn-outline"
          @click="$router.push('/order/detail/'+orderList.order_id)"
        >
          주문
          상세보기
        </button>
      </dl>
      <!-- * 주문일시, 주문번호 정보라벨 E -->
    </div>

    <div>
      <OrderWideItemList
        :item-list="orderList"
        :btn-list-show="true"
        @popup-event="CancelPopup"
      />
    </div>
  </div>
</template>

<script>
  import OrderWideItemList from '@/components/mypage/order-wide-item-list.vue'
  export default {
    components: {
      OrderWideItemList
    },
    props: {
      orderList: {
        type: Object,
        required: true
      }
    },
    methods: {
      CancelPopup (orderNo) {
        this.$emit('popup-event', orderNo)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
