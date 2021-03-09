<template>
  <div class="dg-snb-terms-policy-wrapper">
    <!-- 1) 동글 이용약관 -->
    <div
      class="l-con-area full"
      style="height: 100%;"
    >
      <article
        class="l-con-article"
        style="height: 100%;"
      >
        <div class="_popup_title">
          <h2>
            동글 이용약관<button
              type="button"
              class="_close_btn"
              @click="$emit('popup-close')"
            ></button>
          </h2>
        </div>

        <div
          class="l-contents-group"
          style="height: 100%;overflow-y: scroll;"
        >
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
