<template>
  <div id="login_page" class="page--default">
    <div class="login_intro">
      <theme-bg-01 />
      <div class="tbl_cell_middle">
        <div class="logo_bcg">
          <span class="in_circle">
            <img
              src="/assets/images/logos/logo_band_left.svg"
              alt="logo band left"
              class="band band--left band--left_on"
            />
            <img
              src="/assets/images/logos/logo_band_right.svg"
              alt="logo band right"
              class="band band--right band--right_on"
            />
          </span>
          <h2 class="tit">login</h2>
        </div>
      </div>
    </div>
    <div class="login_box">
      <transition appear name="slide-fadeup">
        <fieldset class="login_field">
          <div class="form_item type_eft" :class="{active: isEmailActive || email}">
            <label for="#" class="form_label">이메일 아이디</label>
            <div class="form_input_group">
              <input
                type="text"
                class="form_input id_input"
                v-model="email"
                @focus="isEmailActive = true"
                @blur="isEmailActive = email ? true : false"
              />
            </div>
          </div>

          <div class="form_item type_eft" :class="{active: isPasswordActive}">
            <label for="#" class="form_label">비밀번호</label>
            <div class="form_input_group">
              <input
                type="password"
                v-model="password"
                class="form_input pw_input"
                @focus="isPasswordActive = true"
                @blur="isPasswordActive = password ? true : false"
                @keyup.enter="login"
              />
            </div>
          </div>

          <router-link
            class="option option--findpw color_theme-02"
            role="button"
            to="/findpw"
          >비밀번호를 잃어버리셨나요?</router-link>

          <input
            type="submit"
            value="로그인"
            class="wide_btn btn_clear_to_theme active login_btn"
            @click="login"
          />

          <div class="option option--register">
            <span class="color_gray-03">아이디가 아직 없으신가요?&nbsp;</span>
            <router-link role="button" class="color_theme-02" to="/register">회원가입 바로가기</router-link>
          </div>

          <!--
          <div class="option option--sns">
            <span class="color_gray-03">SNS 로그인하기</span>
            <button type="button">
              <img src="/assets/images/btn/btn_sns_naver.svg" alt="naver login btn" />
            </button>
            <button type="button">
              <img src="/assets/images/btn/btn_sns_kakao.svg" alt="kakao login btn" />
            </button>
          </div>
          -->
        </fieldset>
      </transition>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'login',
  data () {
    return {
      isEmailActive: false,
      isPasswordActive: false,
      email: '',
      password: ''
    }
  },
  methods: {
    ...mapActions(['getUser']),
    async login () {
      if (!this.email) {
        this.$swal({
          text: '이메일을 입력해주시기 바랍니다!',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1500
        })
        return
      }

      if (!this.password) {
        this.$swal({
          text: '비밀번호를 입력해주시기 바랍니다!',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1500
        })
      }

      try {
        // TODO 로딩 적용

        localStorage.token = await this.$axios
          .post('/login', {
            usr_email: this.email,
            usr_pwd: this.password
          })
          .then(response => response.data.access_token)

        await this.getUser()

        // 푸시 토큰 요청
        window.$EventBus.$emit('push-token-request')

        this.$router.push('/home')
      } catch (e) {
        if (e.response) {
          const { status, data } = e.response
          if (status === 401) {
            this.$swal({
              text: data.message,
              type: 'warning',
              showConfirmButton: false,
              customClass: {
                container: 'bcg-swal__container',
                popup: 'bcg-swal__popup--btnfalse',
                content: 'bcg-swal__content',
                icon: 'bcg-swal__icon--btnfalse'
              },
              timer: 1400
            })
          }
        }

        this.password = ''
        console.log(e)
      } finally {
        // TODO 로딩 해제
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#login_page {
  overflow: hidden;
  background-color: $theme-03;

  .login_intro {
    display: table;
    width: 100%;
    height: 45vh;
    position: relative;
  }

  .theme_bg_box_01 {
    @include position($t: 0, $l: 0);
    width: 100%;
    height: 100%;
  }

  .logo_bcg {
    width: 100%;
    position: relative;
    @include responsive("galaxy5") {
      font-size: 12px;
    }

    .tit {
      @include setFont(22px, $weight: bold, $color: rgba(white, 0.7));
      text-transform: capitalize;
      padding-bottom: 40px;
    }
  }

  .option {
    @include remFont("13px");
    display: inline-block;
    width: 100%;

    &--findpw {
      text-align: right;
    }

    &--register {
      text-align: center;
      margin-bottom: 2rem;
    }

    &--register a {
      font-weight: 400;
    }

    &--sns {
      text-align: left;
    }

    &--sns span,
    button {
      @include initButton;
      display: inline-block;
      vertical-align: middle;
    }
  }

  .type_eft:nth-of-type(2) {
    padding-bottom: 0;
  }

  .login_btn {
    font-weight: bold;
    margin: 1rem 0 1.2rem;
  }
}

.login_box {
  background-color: white;
  height: 55vh;
  position: relative;
  @include responsive("galaxy5") {
    height: 56vh;
  }

  &:before {
    @include position($t: -80px, $l: 0);
    @include translate($x: -20.8%, $y: 0);
    @include zindex("zero");
    content: "";
    width: 180%;
    height: 100%;
    border-radius: 50%;
    background-color: white;
    @include responsive("tablet_tab") {
      @include translate($x: -16.8%, $y: 0);
      width: 150%;
      top: -70px;
    }
    @include responsive("mobile") {
      top: -60px;
    }
  }

  .login_field {
    @include zindex("default");
    @include pushAuto;
    max-width: 360px;
    padding: 0 2rem;
    @include responsive("mobile") {
      max-width: 100%;
    }
  }
}
</style>
