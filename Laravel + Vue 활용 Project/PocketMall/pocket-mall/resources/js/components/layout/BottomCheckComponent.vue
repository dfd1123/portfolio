<template>
  <div class="main-check-container">
    <div class="inner">
      <div class="main-check-cart">
        <article class="in-cart-list">
          <div
            v-for="item in $store.state.carts"
            :key="'cart'+item.option.op_id"
            class="in-cart-detail"
          >
            <h5 class="in-tit">
              {{item.title}}
              <button class="_delete_btn" @click="deleteItem(item.option.op_id)">삭제</button>
            </h5>
            <dl class="in-detail">
              <dt>
                <b>{{item.option.op_type}} Type</b>
                {{item.option.op_concept}}
              </dt>
              <dd>
                <i>{{NumberFormat(item.total_price)}}</i>원
              </dd>
            </dl>
          </div>
        </article>
      </div>
      <div class="main-check-price">
        <h4 class="in-total">총 가격</h4>
        <strong class="in-final-price">{{NumberFormat($store.state.totalPrice)}}</strong>
        <input
          type="button"
          class="in-final-button"
          value="최종견적 접수하기"
          @click="$router.push('/check-cart')"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "bottom-check-component",
  data() {
    return {
      delItem: {},
      cartItem: []
    };
  },
  methods: {
    deleteItem(item) {
      this.$store.commit("CartDel", item);
    }
  }
};
</script>

<style scoped>
</style>