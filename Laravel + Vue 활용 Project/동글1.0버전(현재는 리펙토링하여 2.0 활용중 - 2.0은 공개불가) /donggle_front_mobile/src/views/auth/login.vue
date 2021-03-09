<template>
  <div
    id="login_content"
    class="l-auth-contents"
  >
    <!-- header -->
    <header id="dg-log-hd">
      <h1
        class="dg-log-hd-logo"
        @click="$router.push('/')"
      >
        donggle
      </h1>
    </header>
    <!-- header E -->

    <!-- content -->
    <section
      id="login_main"
      class="step_common"
    >
      <h2 class="dg_blind">
        로그인하기
      </h2>
      <form
        action="#"
        method="post"
      >
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
              @keyup.enter="Login()"
            >
          </div>
          <div class="dg_check check_save">
            <input
              type="checkbox"
              id="id_remember"
              class="dg-input-checkbox display_none"
              v-model="idRemember"
              @change="IdRemember"
            >
            <label
              for="id_remember"
              class="dg-input-checkbox_label"
            ></label>
            <label
              for="id_remember"
              class="dg-input-checkbox_text"
            >이메일 저장하기</label>
            <router-link
              to="/find"
              class="dg-find_id_pw"
            >
              아이디/비밀번호 찾기
            </router-link>
          </div>
        </fieldset>
      </form>
      <div class="dg-reg-end_btn_wrap clear_both">
        <button
          type="button"
          class="dg-btn_gra dg-dubble_btn l_width_100"
          @click="Login()"
        >
          로그인
        </button>
        <router-link
          to="/register"
          class="dg-btn_line dg-dubble_btn l_width_100"
        >
          회원가입
        </router-link>
      </div>
      <div
        v-if="false"
        class="login_connect_wrap"
      >
        <h3>소셜 간편 로그인</h3>
        <div class="clear_both login_connect_btn_box">
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
      </div>
    </section>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        mainPopup: false,
        categoryPopup: false,
        searchPopup: false,
        categorys: [],
        keywords: [],
        topTen: [],
        nextTen: [],
        searchKeyword: '',
        form: {
          email: '',
          password: ''
        },
        idRemember: false
      }
    },
    async created () {
      window.$EventBus.$on('native-login', this.SetToken)

      await window.callToken()

      this.CategoryLoad()

      this.RankLoad()

      if (this.$cookies.get('idRememberDonggle')) {
        this.idRemember = true
      }

      this.form.email = this.$cookies.get('idRememberDonggle')
    },
    beforeDestroy () {
      window.$EventBus.$off('native-login')
    },
    methods: {
      async CategoryLoad () {
        try {
          const res = (await this.$http.get(this.$APIURI + 'category/main_list'))
            .data

          if (res.state !== 1) {
            console.log(res.msg)
          } else {
            this.categorys = res.query
          }
        } catch (e) {
          console.log(e)
        }
      },
      async RankLoad () {
        const res = (await this.$http.get(this.$APIURI + 'popular/list')).data

        this.keywords = res.query

        this.topTen = []
        this.nextTen = []

        this.keywords.forEach((keyword, index) => {
          if (index < 10) {
            this.topTen.push(keyword)
          } else {
            this.nextTen.push(keyword)
          }
        })
      },
      async SearchKeywordStore () {
        const params = {
          pp_word: this.$route.query.searchKeyword
        }

        try {
          const res = (await this.$http.post(this.$APIURI + 'popular', params))
            .data

          if (res.state === 1) {
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      Submit () {
        if (this.searchPopup) {
          this.searchPopup = !this.searchPopup
        }
        this.SearchKeywordStore()
        this.$router.push({
          name: 'total-search',
          query: { searchKeyword: this.searchKeyword }
        })
      },
      CategoryPopup () {
        if (this.categoryPopup) {
          document.body.style.overflowY = 'auto'
        } else {
          document.body.style.overflowY = 'hidden'
        }

        this.categoryPopup = !this.categoryPopup
      },
      SearchPopup () {
        this.searchPopup = !this.searchPopup
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
      Validation () {
        if (this.form.email === '' || this.form.password === '') {
          this.WarningAlert('아이디와 비밀번호를 정확히 입력하세요.')

          return false
        }

        return true
      },
      async Login () {
        if (this.Validation()) {
          const params = this.form
          await this.$store.dispatch('Login', params)

          if (this.nativeToken) {
            this.NativeTokenPut()
          }
        }
      },
      CheckPopupCookie (cookieName) {
        const cookie = document.cookie

        if (cookie.length > 0) {
          // 현재 쿠키가 존재할 경우
          // 자식창에서 set해준 쿠키명이 존재하는지 검색

          const startIndex = cookie.indexOf(cookieName)

          if (startIndex !== -1) {
            // 존재 한다면
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

        document.cookie =
          name +
          '=' +
          escape(value) +
          '; path=/; expires=' +
          d.toGMTString() +
          ';'
      },
      NaverLogin () {
        location.href =
          this.$APIURI +
          'naver?url=' +
          (process.env.VUE_APP_ENV === 'LOCAL'
            ? process.env.VUE_APP_LOCAL_MOBILE_URI
            : process.env.VUE_APP_PRODC_MOBILE_URI)
      },
      KakaoLogin () {
        location.href =
          this.$APIURI +
          'kakao?url=' +
          (process.env.VUE_APP_ENV === 'LOCAL'
            ? process.env.VUE_APP_LOCAL_MOBILE_URI
            : process.env.VUE_APP_PRODC_MOBILE_URI)
      },
      FacebookLogin () {
        location.href =
          this.$APIURI +
          'facebook?url=' +
          (process.env.VUE_APP_ENV === 'LOCAL'
            ? process.env.VUE_APP_LOCAL_MOBILE_URI
            : process.env.VUE_APP_PRODC_MOBILE_URI)
      }
    }
  }
</script>

<style lang="scss" scoped>
  @media all and (max-width: 768px) {
    #login_main {
      padding-bottom: 150px;
    }
  }
</style>
