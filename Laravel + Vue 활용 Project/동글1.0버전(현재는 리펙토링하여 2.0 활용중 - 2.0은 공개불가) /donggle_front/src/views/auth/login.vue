<template>
  <div>
    <RegHeader
      page-name=""
      :only-logo="true"
      :step-view="false"
    />
    <!-- content -->
    <section
      id="login_main"
      class="step_common"
    >
      <h2 class="dg_blind">
        로그인하기
      </h2>
      <form>
        <fieldset class="dg-write_form">
          <legend class="dg_blind">
            로그인
          </legend>
          <div class="dg_write write_mail">
            <label for="email">이메일</label>
            <input
              type="email"
              id="email"
              class="input_text_box"
              placeholder="이메일 입력"
              v-model="form.email"
            >
          </div>
          <div class="dg_write write_pw">
            <label for="password">비밀번호</label>
            <input
              type="password"
              id="password"
              class="input_text_box"
              placeholder="비밀번호"
              v-model="form.password"
              @keyup.enter="Submit()"
            >
          </div>
          <input
            type="checkbox"
            id="email-store"
            class="dg-input-checkbox display_none"
            v-model="idRemember"
            @change="IdRemember"
          >
          <label
            for="email-store"
            class="dg-input-checkbox_label"
          ></label>
          <label
            for="email-store"
            class="dg-input-checkbox_text"
          >이메일 저장하기</label>
          <router-link
            to="/find"
            class="dg-find_id_pw"
          >
            아이디/비밀번호 찾기
          </router-link>
        </fieldset>
      </form>
      <div class="dg-reg-end_btn_wrap clear_both">
        <button
          type="button"
          class="dg-btn_gra dg-dubble_btn dg-btn_width100"
          @click="Submit()"
        >
          로그인
        </button>
        <router-link
          to="/register"
          class="dg-btn_line dg-dubble_btn dg-btn_width100"
        >
          회원가입
        </router-link>
      </div>
      <div
        v-if="false"
        class="login_connect_wrap"
      >
        <h3>간편 로그인</h3>
        <div
          class="login_connect login-login_connect login_connect_naver"
          @click="NaverLogin"
        >
          네이버로 로그인
        </div>
        <div
          class="login_connect login-login_connect login_connect_kakao"
          @click="KakaoLogin"
        >
          카카오로 로그인
        </div>
        <div
          class="login_connect login-login_connect login_connect_facebook"
          @click="FacebookLogin"
        >
          페이스북으로 로그인
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
    data: function () {
      return {
        form: {
          register_kind: 1,
          email: '',
          password: ''
        },
        idRemember: false
      }
    },
    methods: {
      async Submit () {
        if (this.Validation()) {
          const params = this.form
          this.$store.dispatch('Login', params)
        }
      },
      Validation () {
        if (this.form.email === '' || this.form.password === '') {
          this.WarningAlert('아이디와 비밀번호를 정확히 입력하세요.')

          return false
        }

        return true
      },
      IdRemember () {
        if (this.idRemember) {
          if (!this.CheckPopupCookie('idRememberDonggle')) {
            this.SetCookie('idRememberDonggle', this.form.email, 365)
          }
        } else {
          if (this.CheckPopupCookie('idRememberDonggle')) {
            this.$cookies.remove('idRememberDonggle')
          }
        }
      },
      CheckPopupCookie (cookieName) {
        const cookie = document.cookie

        if (cookie.length > 0) { // 현재 쿠키가 존재할 경우
          // 자식창에서 set해준 쿠키명이 존재하는지 검색

          const startIndex = cookie.indexOf(cookieName)

          if (startIndex !== -1) { // 존재 한다면
            return true
          } else {
            // 쿠키 내에 해당 쿠키가 존재하지 않을 경우
            return false
          }
        } else {
          // 쿠키 자체가 없을 경우
          return false
        }
      },
      SetCookie (name, value, expiredays) {
        let d = new Date()

        d.setDate(d.getDate() + expiredays)

        document.cookie = name + '=' + escape(value) + '; path=/; expires=' + d.toGMTString() + ';'
      },
      NaverLogin () {
        location.href = this.$APIURI + 'naver?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_PC_URI : process.env.VUE_APP_PRODC_PC_URI)
      },
      KakaoLogin () {
        location.href = this.$APIURI + 'kakao?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_PC_URI : process.env.VUE_APP_PRODC_PC_URI)
      },
      FacebookLogin () {
        location.href = this.$APIURI + 'facebook?url=' + (process.env.VUE_APP_ENV === 'LOCAL' ? process.env.VUE_APP_LOCAL_PC_URI : process.env.VUE_APP_PRODC_PC_URI)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
