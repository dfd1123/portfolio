<template>
  <div>
    <RegHeader
      page-name="회원가입 완료"
      :only-logo="false"
      :step-view="false"
    />
    <section
      id="step_end"
      class="step_common"
    >
      <div class="dg-popup_alert_desc">
        <h2>
          축하합니다, <b>{{ name }}</b>님!<br>
          동글 회원가입이 완료되었습니다.
        </h2>
        <div class="dg-popup_alert_text">
          이 계정으로 동글몰과 가맹점페이지 모두 사용 가능합니다.
        </div>
      </div>
      <div class="dg-dubble_btn_wrap clear_both">
        <router-link
          to="/"
          class="dg-btn_line dg-dubble_btn"
        >
          홈으로
        </router-link>
        <router-link
          to="/product/list/best"
          class="dg-btn_gra dg-dubble_btn"
        >
          인기상품 보기
        </router-link>
      </div>
      <br>
    </section>
  </div>
</template>

<script>
  import RegHeader from '@/components/common/reg-header.vue'

  export default {
    components: {
      RegHeader
    },
    data: function () {
      return {
        id: this.$route.query.id,
        name: this.$route.query.name
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
