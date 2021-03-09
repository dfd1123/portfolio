<template>
  <div class="main-popup alert-popup">
    <div class="popup_bg" @click="NextShopping"></div>
    <div class="alert-wrapper">
      <button type="button" class="alert_close_btn" @click="NextShopping">close</button>
      <article class="alert-wrap">
        <div class="_icon"></div>
        <h2 class="_title">장바구니</h2>
        <div class="_text">
          장바구니 담기가 완료되었습니다
          <br />확인하시겠습니까?
        </div>
        <!-- 버튼이 회색, 그래디언트 두개일 때 -->
        <div class="double-btn-wrap">
          <input
            type="button"
            class="rounded-button btn-gray"
            value="쇼핑 계속하기"
            @click="NextShopping"
          />
          <input
            type="button"
            class="rounded-button btn-bluegradi"
            value="장바구니로 이동"
            @click="$router.push('/check-cart')"
          />
        </div>
      </article>
    </div>
  </div>
</template>

<script>
export default {
  name: "mobile-check-component",
  props: {
    popup: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    NextShopping() {
      this.$emit("update:popup", false);
    }
  }
};
</script>

<style scoped>
</style>