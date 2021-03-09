<template>
  <div class="dg-snb-terms-policy-wrapper l-page-wrapper">
    <!-- 1) 개인정보 처리방침 -->
    <div class="l-con-area full">
      <article class="l-con-article">
        <div class="l-con-title-group">
          <h2 class="in-subject">
            개인정보처리방침
          </h2>
        </div>

        <div class="l-contents-group">
          <!-- * 개인정보처리방침 구역 -->
          <div
            class="dg-snb-terms-policy-contents"
            v-html="$store.state.company.service_policy"
          >
          </div>
          <!-- * 개인정보처리방침 구역 E -->
        </div>
      </article>
    </div>
    <!-- 1) 개인정보 처리방침 E -->
  </div>
</template>

<script>
  export default {
    created () {
      this.$store.commit('ProgressShow')
      // await this.NotifyLoad()
      this.$store.commit('ProgressHide')
    }
  }
</script>

<style lang="scss" scoped>
</style>
