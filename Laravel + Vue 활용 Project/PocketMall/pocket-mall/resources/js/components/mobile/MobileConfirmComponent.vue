<template>
  <div class="main-popup alert-popup">
    <div class="popup_bg"></div>
    <div class="alert-wrapper">
      <article class="alert-wrap only-text-alert-wrap">
        <h2 class="_title">접수완료</h2>
        <div class="_text">
          최종 접수가 완료되었습니다
          <br />담당자가 신속히 연락드리겠습니다
        </div>
        <!-- 버튼이 그라디언트버튼 하나일 때 -->
        <div class="single-btn-wrap">
          <input
            type="button"
            class="rounded-button btn-bluegradi"
            value="확인했습니다"
            @click="ConfirmInvoice"
          />
        </div>
      </article>
    </div>
  </div>
</template>

<script>
export default {
  name: "mobile-confirm-component",
  methods: {
    ConfirmInvoice() {
      this.$store.commit("CartReset");
      this.$router.push("/home");
    }
  }
};
</script>

<style scoped>
</style>