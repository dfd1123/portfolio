<template>
  <div class="dg-snb-terms-policy-wrapper">
    <!-- 1) 개인정보 처리방침 -->
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
            개인정보처리방침<button
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
