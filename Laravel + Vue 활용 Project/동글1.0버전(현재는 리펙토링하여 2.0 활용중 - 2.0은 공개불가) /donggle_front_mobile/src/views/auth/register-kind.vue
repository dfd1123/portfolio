<template>
  <div id="regiter_content">
    <RegHeader
      page-name="회원가입"
      :step="1"
    />
    <section
      id="step1"
      class="step_common"
    >
      <h2>원하시는 로그인 방법을 선택하세요</h2>
      <div class="login_connect_wrap">
        <div
          class="login_connect step1-login_connect login_connect_naver"
          @click="NaverRegister"
        >
          네이버로 가입하기
        </div>
        <div
          class="login_connect step1-login_connect login_connect_kakao"
          @click="KakaoRegister"
        >
          카카오로 가입하기
        </div>
        <div
          class="login_connect step1-login_connect login_connect_facebook"
          @click="FacebookRegister"
        >
          페이스북으로 가입하기
        </div>
        <div
          class="login_connect step1-login_connect login_connect_email"
          @click="EmailRegister"
        >
          이메일로 가입하기
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import RegHeader from '@/components/common/header/reg-header.vue'
  export default {
    components: {
      RegHeader
    },
    methods: {
      NaverRegister () {
        location.href = this.$APIURI + 'naver?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_MOBILE_URI : process.env.VUE_APP_PRODC_MOBILE_URI)
      },
      KakaoRegister () {
        location.href = this.$APIURI + 'kakao?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_MOBILE_URI : process.env.VUE_APP_PRODC_MOBILE_URI)
      },
      FacebookRegister () {
        location.href = this.$APIURI + 'facebook?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_MOBILE_URI : process.env.VUE_APP_PRODC_MOBILE_URI)
      },
      EmailRegister () {
        this.$router.push('/register')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
