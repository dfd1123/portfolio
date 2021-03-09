<template>
  <div>
    <section v-if="isNotFinished" id="competency_start">
      <h2>역량평가를 시작합니다.</h2>
      <a href="#" class="btn_com_start" @click.prevent="start">&gt; 역량평가 시작하기</a>
    </section>
    <section v-else id="competency_start">
      <h2>모든 역량평가를 완료하였습니다.</h2>
      <a href="#" class="btn_com_start" @click.prevent="report">&gt; 분석결과 확인하기</a>
    </section>
  </div>
</template>

<script>

export default {
  name: 'competency-start',
  computed: {
    isNotFinished () {
      return this.user.available && (this.user.cpt_order < this.user.max_cpt_order)
    }
  },
  methods: {
    start () {
      this.$router.push('/competency-intro')
    },
    report () {
      this.$router.push('/report')
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../styles/scss/competency_start.scss";
@import "../styles/scss/layout/responsive.scss";
</style>
