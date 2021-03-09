<template>
  <div>
    <RegHeader
      page-name="회원가입"
      :only-logo="false"
      :step-view="true"
      step="1"
    />
    <!-- content -->
    <section
      id="step1"
      class="step_common"
    >
      <h2>원하시는 로그인 방법을 선택하세요</h2>
      <div class="login_connect_wrap">
        <div
          class="login_connect step1-login_connect login_connect_naver"
          @click="NaverSignup"
        >
          네이버로 가입하기
        </div>
        <div
          class="login_connect step1-login_connect login_connect_kakao"
          @click="KakaoSignup"
        >
          카카오로 가입하기
        </div>
        <div
          class="login_connect step1-login_connect login_connect_facebook"
          @click="FacebookSignup"
        >
          페이스북으로 가입하기
        </div>
        <div
          class="login_connect step1-login_connect login_connect_email"
          @click="EmailSignup"
        >
          이메일로 가입하기
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import RegHeader from '@/components/common/reg-header.vue'

  export default {
    components: {
      RegHeader
    },
    methods: {
      NaverSignup () {
        location.href = this.$APIURI + 'naver?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_PC_URI : process.env.VUE_APP_PRODC_PC_URI)
      },
      KakaoSignup () {
        location.href = this.$APIURI + 'kakao?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_PC_URI : process.env.VUE_APP_PRODC_PC_URI)
      },
      FacebookSignup () {
        location.href = this.$APIURI + 'facebook?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_PC_URI : process.env.VUE_APP_PRODC_PC_URI)
      },
      EmailSignup () {
        this.$router.push('/register')
      },
      getUserData () {

      },
      onLogout () {

      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
