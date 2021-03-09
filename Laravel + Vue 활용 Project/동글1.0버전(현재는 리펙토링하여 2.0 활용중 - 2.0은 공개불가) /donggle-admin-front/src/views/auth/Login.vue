<template>
  <div id="app">
    <div
      id="admin-container"
      class="auth-container"
    >
      <div class="wrapper">
        <!-- page content -->
        <div
          id="page-login-wrap"
          class="page-auth-wrap"
        >
          <div class="panel-default">
            <div class="form-wrapper">
              <div class="auth-logo-wrap">
                <h2 class="auth-hd-logo">donggle</h2>
                <p class="_sub_title">동글 입점스토어 관리자</p>
              </div>

              <!-- content -->
              <section class="auth-layout">
                <h2 class="hide-text">로그인</h2>

                <form class="form_wrap">
                  <fieldset class="form-container type-01">
                    <legend class="hide-text">로그인</legend>
                    <div class="login_info_ment">보유하신 동글 계정으로 입점스토어 관리자 로그인이 가능합니다.</div>
                    <div class="in-divider">
                      <label
                        for="email"
                        class="_label"
                      >이메일</label>
                      <input
                        type="email"
                        id="email"
                        v-model="email"
                        class="form-input-txt"
                        placeholder="이메일 입력"
                        @keyup.enter="login"
                      />
                    </div>
                    <div class="in-divider">
                      <label
                        for="password"
                        class="_label"
                      >비밀번호</label>
                      <input
                        type="password"
                        id="password"
                        v-model="password"
                        class="form-input-txt"
                        placeholder="비밀번호"
                        @keyup.enter="login"
                      />
                    </div>
                    <div class="in-divider clearfix _s-font">
                      <div class="_save_form">
                        <input
                          type="checkbox"
                          id="idRemember"
                          class="chck-box none"
                          v-model="idRemember"
                          @change="IdRemember"
                        />
                        <label
                          for="idRemember"
                          class="check-gradi-circle"
                        ></label>
                        <label
                          for="idRemember"
                          class="dg-input-checkbox_text"
                        >이메일 저장하기</label>
                      </div>
                      <a
                        href="find-choice.html"
                        class="_find_id_pw"
                        @click.prevent="findPw"
                      >아이디/비밀번호 찾기</a>
                    </div>
                  </fieldset>
                  <div class="dg-btn-wrap">
                    <button
                      type="button"
                      class="square-md-btn btn-gradient auth-btn _full"
                      @click.prevent="login"
                    >로그인</button>
                    <button
                      type="button"
                      class="square-md-btn auth-btn _full"
                      style="border: 1px solid;"
                      @click="$router.push('/register')"
                    >회원가입</button>
                  </div>
                </form>

                <div class="in-divider">
                  <div
                    v-if="false"
                    class="login_connect_wrap"
                  >
                    <h3>간편 로그인</h3>
                    <div class="clearfix">
                      <div
                        class="login_connect login_connect_naver"
                        @click="NaverLogin"
                      >
                        <img
                          src="/images/icon/icon_naver.svg"
                          alt="네이버 로고"
                        />
                        네이버로 로그인
                      </div>
                      <div
                        class="login_connect login_connect_kakao"
                        @click="KakaoLogin"
                      >
                        <img
                          src="/images/icon/icon_kakao.svg"
                          alt="카카오 로고"
                        />
                        카카오로 로그인
                      </div>
                      <div
                        class="login_connect login_connect_facebook"
                        @click="FacebookLogin"
                      >
                        <img
                          src="/images/icon/icon_face.svg"
                          alt="페이스북 로고"
                        />
                        페이스북으로 로그인
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>
        <!-- page content E -->
      </div>
      <layout-footer />
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'Login',
  data () {
    return {
      email: '',
      password: '',
      process: process,
      idRemember: false
    }
  },
  async created () {
    window.$EventBus.$on('native-login', this.SetToken)
    await window.callToken()
  },
  beforeDestroy () {
    window.$EventBus.$off('native-login')
  },
  methods: {
    ...mapActions([
      'loginUser'
    ]),
    Validation () {
      if (this.email === '' || this.password === '') {
        alert('아이디와 비밀번호를 정확히 입력하세요.')

        return false
      }

      return true
    },
    async login () {
      if (this.Validation()) {
        if (!this.email) {
          alert('이메일을 입력해 주세요')
          return
        }

        if (!this.password) {
          alert('비밀번호를 입력해 주세요')
          return
        }

        try {
          this.loading(true)
          const params = {
            email: this.email,
            password: this.password
          }

          await this.loginUser(params)

          if (this.nativeToken) {
            this.NativeTokenPut()
          }

          this.$router.push('/main')
        } catch (e) {
          if (e.response && e.response.status === 401) {
            alert('이메일이나 비밀번호가 잘못되었습니다.')
          }
        } finally {
          this.loading(false)
        }
      }
    },
    findPw () {
      this.$router.push('/find-choice')
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
    GotoUrl () {
      if (process.env.VUE_APP_ENV !== 'LOCAL') {
        this.NativePopup(process.env.VUE_APP_PRODC_URI.replace('store.', '') + '/register/kind')
      } else {
        this.NativePopup('http://localhost:8080/register/kind')
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
    GetMobileOperatingSystem () {
      var userAgent = navigator.userAgent || navigator.vendor || window.opera

      // Windows Phone must come first because its UA also contains "Android"
      if (/windows phone/i.test(userAgent)) {
        return 'Windows Phone'
      }

      if (/android/i.test(userAgent)) {
        return 'Android'
      }

      // iOS detection from: http://stackoverflow.com/a/9039885/177710
      if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
        return 'iOS'
      }
      return 'unknown'
    },
    SetCookie (name, value, expiredays) {
      const d = new Date()

      d.setDate(d.getDate() + expiredays)

      document.cookie = name + '=' + escape(value) + '; path=/; expires=' + d.toGMTString() + ';'
    },
    NaverLogin () {
      location.href = process.env.VUE_APP_BASE_URL + '/api/naver?url=' + window.location.protocol + '//' + window.location.host
    },
    KakaoLogin () {
      location.href = process.env.VUE_APP_BASE_URL + '/api/kakao?url=' + window.location.protocol + '//' + window.location.host
    },
    FacebookLogin () {
      location.href = process.env.VUE_APP_BASE_URL + '/api/facebook?url=' + window.location.protocol + '//' + window.location.host
    }
  }
}
</script>

<style>
</style>
