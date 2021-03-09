<template>
  <div class="order-list-template">
    <div class="order-list-wrapper">
      <ul class="order-list-wrap">
        <!-- 카드 -->
        <li v-for="cart in carts" :key="'carts'+cart.option.op_id" class="order-list">
          <div class="order_name">
            <h3 class="_title">
              {{cart.title}}
              <button class="_delete_btn" @click="deleteItem(cart.option.op_id)">삭제</button>
            </h3>
            <div class="_desc">
              {{cart.simple_intro}}
              <!-- <span class="txt-en">{{cart.option.op_simple_intro}}</span> -->
            </div>
          </div>
          <div class="order_check_wrap">
            <dl class="order_check clearfix">
              <dt>type</dt>
              <dd>
                <span class="txt-en">{{cart.option.op_type}}</span>
                : {{cart.option.op_concept}}
              </dd>
            </dl>
            <dl class="order_check clearfix">
              <dt>가격</dt>
              <dd>
                <span class="txt-en">{{NumberFormat(cart.total_price)}}</span>원
              </dd>
            </dl>
          </div>
        </li>
        <!-- 카드 END -->
      </ul>
    </div>
    <div class="total-price">
      <ul class="price_wrap">
        <li class="_title">총 금액</li>
        <li class="_desc">
          <b>{{NumberFormat($store.state.totalPrice)}}</b> 원
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: "sub-cart-history",
  props: {
    carts: {
      type: Array,
      required: true
    }
  },
  methods: {
    deleteItem(item) {
      this.$store.commit("CartDel", item);
      if (this.$store.state.carts.length === 0) {
        Vue.$toast.clear();

        this.$swal({
          html: "장바구니가 비어있습니다.<br>메인 페이지로 이동합니다.",
          type: "warning",
          icon: "warning",
          confirmButtonText: "확인",
          customClass: {
            icon: "custom-warning-icon"
          }
        }).then(result => {
          this.$router.push("/home");
        });
      }
    }
  }
};
</script>

<style scoped>
</style>