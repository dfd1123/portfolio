<template>
  <div id="mypage_term--info" class="page page--hd_default">
    <header-component hd-title="개인정보 처리방침" :hd-theme-ylw="true" @privButtonClick="privButtonClick" />
    <div class="page_container pd_bt_nav">
      <transition appear name="slide-fade">
        <div class="contents">
          <span v-html="terms.privacy_policy"></span>
        </div>
      </transition>
    </div>
    <footer-navigation :mypage-on="true"></footer-navigation>
  </div>
</template>

<script>
export default {
  name: 'MyPageInfoTerms',
  data () {
    return {
      terms: {}
    }
  },
  async created () {
    try {
      this.terms = await this.$axios.get('/terms').then(response => response.data[0])
    } catch (e) {
      console.log(e)
    }
  },
  methods: {
    privButtonClick () {
      window.history.back()
    }
  }
}
</script>

<style lang="scss" scoped>
#mypage_term--info {
  .page_container {
    overflow-x: hidden;
  }
  .contents {
    padding: 1rem 1.5rem;
    overflow-y: scroll;
  }
}
</style>
