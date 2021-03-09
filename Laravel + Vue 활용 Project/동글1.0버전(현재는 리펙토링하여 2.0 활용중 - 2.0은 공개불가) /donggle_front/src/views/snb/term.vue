<template>
  <div class="dg-snb-terms-policy-wrapper l-page-wrapper">
    <!-- 1) 동글 이용약관 -->
    <div class="l-con-area full">
      <article class="l-con-article">
        <div class="l-con-title-group">
          <h2 class="in-subject">
            동글 이용약관
          </h2>
        </div>

        <div class="l-contents-group">
          <!-- * 동글 이용약관 구역 -->
          <div
            class="dg-snb-terms-policy-contents"
            v-html="$store.state.company.use_terms"
          >
          </div>
          <!-- * 동글 이용약관 구역 E -->
        </div>
      </article>
    </div>
    <!-- 1) 동글 이용약관 E -->
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
