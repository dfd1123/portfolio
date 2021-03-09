<template>
  <div id="app">
    <div
      id="admin-container"
      class="auth-container"
    >
      <div class="wrapper">
        <!-- page content -->
        <div
          id="page-register-wrap"
          class="page-auth-wrap"
        >
          <div class="panel-default">
            <div class="form-wrapper">
              <div class="auth-logo-wrap">
                <h2 class="auth-hd-logo">donggle</h2>
                <p class="_sub_title">동글 입점스토어 관리자</p>
              </div>
              <div class="dg-find-title-wrap">
                <h2>
                  회원가입
                  <a
                    href="#"
                    class="icon-back-btn"
                    @click.prevent="$router.push('/login')"
                  >뒤로가기</a>
                </h2>
              </div>

              <!-- content -->
              <section class="auth-layout">
                <h2 class="hide-text">회원가입</h2>

                <form class="form_wrap">
                  <fieldset class="form-container type-01">
                    <legend class="hide-text">회원가입</legend>

                    <div class="in-divider">
                      <label
                        for="email"
                        class="_label"
                      >이메일</label>
                      <div class="input_btn_with_textbox_wrap">
                        <input
                          type="email"
                          id="email"
                          class="form-input-txt"
                          placeholder="사용하실 이메일 입력"
                          ref="email"
                          v-model="form.email"
                          @input="isEmailVerify = false"
                        >
                        <input
                          type="button"
                          class="dg-btn input_btn_with_textbox_btn"
                          value="사용가능"
                          v-if="isEmailVerify && emailAvailable"
                        >
                        <input
                          type="button"
                          class="dg-btn input_btn_with_textbox_btn"
                          value="중복체크"
                          v-else
                          @click="EmailDupleCheck()"
                        >
                        <p
                          v-if="isEmailVerify && !emailAvailable"
                          class="dg-write_wr dg-write_wr_nn"
                        >
                          이미 사용중인 이메일 주소입니다.
                        </p>
                      </div>
                    </div>

                    <div class="in-divider">
                      <label
                        for="password"
                        class="_label"
                      >비밀번호</label>
                      <input
                        type="password"
                        id="password"
                        class="form-input-txt"
                        placeholder="비밀번호"
                        ref="password"
                        v-model="form.password"
                      />
                      <input
                        type="password"
                        class="form-input-txt"
                        placeholder="비밀번호 재입력"
                        id="password_confirm"
                        ref="password_confrim"
                        v-model="form.password_confirm"
                      />
                      <p
                        v-if="PasswordMatched"
                        class="dg-write_eq dg-write_eq_pw"
                      >
                        비밀번호가 일치합니다.
                      </p>
                      <p
                        v-if="PasswordUnMatched"
                        class="dg-write_wr dg-write_wr_pw"
                      >
                        비밀번호가 일치하지 않습니다.
                      </p>
                    </div>

                    <div class="in-divider">
                      <label
                        for="name"
                        class="_label"
                      >이름</label>
                      <input
                        type="text"
                        id="name"
                        class="form-input-txt"
                        placeholder="실명 입력"
                        v-model="form.name"
                      />
                    </div>

                    <div class="in-divider">
                      <label
                        for="mobile_number"
                        class="_label"
                      >휴대폰 번호</label>
                      <div class="input_btn_with_textbox_wrap">
                        <input
                          type="tel"
                          id="mobile_number"
                          class="form-input-txt"
                          placeholder="본인 명의 휴대폰 번호"
                          maxlength="13"
                          :disabled="mobileVerfied"
                          @input="isRequestCertify = false"
                          ref="mobile_number"
                          v-model="form.mobile_number"
                        />
                        <input
                          type="button"
                          class="dg-btn input_btn_with_textbox_btn"
                          value="인증 재요청"
                          v-if="isRequestCertify && !isResponseCertify && !mobileVerfied"
                          :disabled="mobileVerfied"
                          @click="SmsReset()"
                        >

                        <input
                          type="button"
                          class="dg-btn input_btn_with_textbox_btn"
                          value="인증요청"
                          v-else-if="!isRequestCertify && !isResponseCertify && !mobileVerfied"
                          :disabled="mobileVerfied"
                          @click="MobileReqVerify()"
                        >

                        <input
                          v-else
                          type="button"
                          class="dg-btn input_btn_with_textbox_btn"
                          value="인증완료"
                          :disabled="mobileVerfied"
                        >
                      </div>
                      <div
                        v-if="isRequestCertify && PhoneCertifyStatus && !mobileVerfied"
                        class="input_btn_with_textbox_wrap"
                      >
                        <input
                          type="number"
                          id="mobile_certify"
                          class="form-input-txt"
                          placeholder="인증번호 입력"
                          maxlength="6"
                          :disabled="mobileVerfied"
                          @input="isResponseCertify = false"
                          v-model="form.certify_num"
                        >
                        <input
                          v-if="!mobileVerfied"
                          type="button"
                          class="dg-btn input_btn_with_textbox_btn"
                          value="인증하기"
                          :disabled="mobileVerfied"
                          @click="MobileResVerify()"
                        >

                        <input
                          v-else
                          type="button"
                          class="dg-btn input_btn_with_textbox_btn"
                          value="인증완료"
                          :disabled="mobileVerfied"
                        >
                        <p
                          v-if="isResponseCertify && !mobileVerfied"
                          class="dg-write_wr dg-write_wr_nb"
                        >
                          인증번호가 일치하지 않습니다.
                        </p>
                      </div>
                    </div>
                  </fieldset>

                  <fieldset class="dg-reg_check_form">
                    <div class="dg_write write_check">
                      <input
                        type="checkbox"
                        id="all_agree"
                        class="dg-input-checkbox none"
                        v-model="all_agree"
                        @change="AllAgree()"
                      />
                      <label
                        for="all_agree"
                        class="dg-input-checkbox_label dg_write_agree_all"
                      ></label>
                      <label
                        for="all_agree"
                        class="dg-input-checkbox_text dg_write_agree_all"
                      >모두 동의합니다.</label>
                      <div class="reg_check_peice">
                        <div class="dg-reg_agree">
                          <input
                            type="checkbox"
                            id="term_agree"
                            class="dg-input-checkbox none"
                            v-model="term_agree"
                            @change="AllCheckedConfirm()"
                          />
                          <label
                            for="term_agree"
                            class="dg-input-checkbox_label dg_write_agree_use"
                          ></label>
                          <label
                            for="term_agree"
                            class="dg-input-checkbox_text dg_write_agree_use"
                          >이용약관 필수 동의</label>
                          <router-link
                            to="/term"
                            class="view_agree_desc"
                          >보기</router-link>
                        </div>
                        <div class="dg-reg_agree">
                          <input
                            type="checkbox"
                            id="privacy_agree"
                            class="dg-input-checkbox none"
                            v-model="privacy_agree"
                            @change="AllCheckedConfirm()"
                          />
                          <label
                            for="privacy_agree"
                            class="dg-input-checkbox_label dg_write_agree_pr"
                          ></label>
                          <label
                            for="privacy_agree"
                            class="dg-input-checkbox_text dg_write_agree_pr"
                          >개인정보 처리방침 필수 동의</label>
                          <router-link
                            to="/privacy"
                            class="view_agree_desc"
                          >보기</router-link>
                        </div>
                        <div class="dg-reg_agree">
                          <input
                            type="checkbox"
                            id="ad_agree"
                            class="dg-input-checkbox none"
                            v-model="form.ad_agree"
                            @change="AllCheckedConfirm()"
                          />
                          <label
                            for="ad_agree"
                            class="dg-input-checkbox_label dg_write_agree_cp"
                          ></label>
                          <label
                            for="ad_agree"
                            class="dg-input-checkbox_text dg_write_agree_cp"
                          >쿠폰 / 이벤트 알림 선택동의</label>
                          <p>
                            SMS, 이메일을 통해 파격할인/이벤트/쿠폰 정보를 받아보실 수
                            있습니다.
                          </p>
                        </div>
                      </div>
                    </div>
                    <div class="btn_write_complete_wrap">
                      <button
                        type="button"
                        class="square-md-btn btn-gradient auth-btn _full"
                        @click="Submit()"
                      >회원가입하기</button>
                    </div>
                  </fieldset>
                </form>
              </section>
              <!-- content E -->
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
export default {
  name: 'Register',
  data: function () {
    return {
      form: {
        email: '',
        password: '',
        password_confirm: '',
        name: '',
        nickname: '',
        mobile_number: '',
        ad_agree: false
      },
      isEmailVerify: false,
      emailAvailable: false,
      beforePhone: '',
      all_agree: false,
      term_agree: false,
      privacy_agree: false,
      nicknameAvailable: false,
      nicknameChecked: false,
      timeCounter: 180,
      mobile_certify: '',
      sms_certify_num: '',
      resTimeData: '',
      mobileVerfied: false,
      isRequestCertify: false,
      isResponseCertify: false
    }
  },
  computed: {
    PasswordMatched () {
      if (this.form.password !== '' && this.form.password_confirm !== '') {
        if (this.form.password === this.form.password_confirm) {
          return true
        } else {
          return false
        }
      }

      return false
    },
    PasswordUnMatched () {
      if (this.form.password !== '' && this.form.password_confirm !== '') {
        if (this.form.password !== this.form.password_confirm) {
          return true
        } else {
          return false
        }
      }

      return false
    },
    PhoneCertifyStatus () {
      if (this.beforePhone === '') {
        return false
      }

      if (this.beforePhone !== this.form.mobile_number) {
        this.TimeStop()
        return false
      } else {
        return true
      }
    }
  },
  methods: {
    async NickNameDupleCheck () {
      // this.nicknameClassName
      if (this.form.nickname === '') {
        alert('닉네임을 입력해주세요!')
        this.nicknameAvailable = false

        return false
      }

      try {
        const params = {
          nickname: this.form.nickname
        }
        const nicknameExist = (await this.$axios.get('/api/users/nickname_check', { params })).data.query

        this.nicknameChecked = true

        if (!nicknameExist) {
          alert('사용 가능한 닉네임 입니다.')
          this.nicknameAvailable = true
        } else {
          alert('이미 존재하거나 사용이 불가한 닉네임 입니다.')
          this.nicknameAvailable = false
        }

        return true
      } catch (e) {
        console.log(e)
      }
    },
    async EmailDupleCheck () {
      // this.nicknameClassName
      if (this.form.email === '' || this.form.email === null) {
        alert('이메일을 입력해주세요!')
        this.isEmailVerify = false

        return false
      }

      try {
        const params = {
          email: this.form.email
        }

        const emailExist = (await this.$axios.get('/api/users/email_check', { params })).data.query

        this.emailAvailable = !emailExist
        if (this.emailAvailable) {
          alert('사용 가능한 이메일 주소 입니다.')
          this.isEmailVerify = true
        } else {
          alert('이미 존재하거나 사용이 불가한 이메일 주소입니다.')
          this.isEmailVerify = false
          this.form.email = null
        }

        return true
      } catch (e) {
        console.log(e)
      }
    },
    async MobileReqVerify () {
      if (this.mobileVerfied) {
        alert('이미 인증을 완료 하셨습니다')
        return false
      }

      if (this.form.mobile_number === '') {
        alert('휴대폰 번호를 입력하세요!')
        this.isRequestCertify = false

        return false
      }

      this.isRequestCertify = true
      this.beforePhone = this.form.mobile_number

      try {
        const params = {
          mobile_number: this.form.mobile_number
        }

        const phoneExist = (await this.$axios.get('/api/users/phone_check', { params })).data.query

        if (!phoneExist) {
          this.sms_certify_num = this.RandomCode()

          const params = {
            mobile_number: this.form.mobile_number,
            txt: '(주)동글 SMS 인증번호 [' + this.sms_certify_num + ']'
          }
          this.$axios.get('/api/sms/send', { params })
          this.isRequestCertify = true
          alert('인증번호가 SMS로 발송되었습니다.')
        } else {
          this.isRequestCertify = false
          alert('이미 가입된 휴대폰 번호 입니다.')
          this.TimeStop()

          return false
        }

        // SMS 인증코드 발송 함수 호출

        this.TimerStart()

        return true
      } catch (e) {
        console.log(e)
      }
    },
    MobileResVerify () {
      if (this.form.certify_num === '') {
        alert('인증번호를 입력하세요!')
        this.mobileVerfied = false

        return false
      }

      if (this.form.certify_num === this.sms_certify_num) {
        this.mobileVerfied = true
        this.TimeStop()
        this.resTimeData = ''
      }

      this.isResponseCertify = true
    },
    AllAgree () {
      if (this.all_agree) {
        this.term_agree = true
        this.privacy_agree = true
        this.form.ad_agree = true
      } else {
        this.term_agree = false
        this.privacy_agree = false
        this.form.ad_agree = false
      }
    },
    AllCheckedConfirm () {
      if (!this.term_agree || !this.privacy_agree || !this.form.ad_agree) {
        this.all_agree = false
      }

      if (this.term_agree && this.privacy_agree && this.form.ad_agree) {
        this.all_agree = true
      }
    },
    async Validation () {
      if (this.form.email === '') {
        alert('이메일 주소를 입력해주세요!')
        this.$refs.email.focus()
        return false
      }

      if (this.form.password === '') {
        alert('비밀번호를 입력해주세요!')
        this.$refs.password.focus()
        return false
      } else {
        if (!this.ChkPwd(this.form.password)) {
          alert('비밀번호는 영문,숫자를 혼합하여 8~20자 이내로 입력하셔야 합니다!')
          this.$refs.password.focus()
          return this.ChkPwd(this.form.password)
        }
      }

      if (this.form.password_confirm === '') {
        alert('비밀번호 재확인을 입력해주세요!')
        this.$refs.password_confirm.focus()
        return false
      }

      if (this.form.mobile_number === '') {
        alert('휴대폰 번호를 입력하세요!')
        this.$refs.mobile_number.focus()
        return false
      }

      if (!this.mobileVerfied) {
        alert('휴대폰 인증을 하지 않으셨습니다!')
        this.$refs.mobile_number.focus()
        return false
      }

      if (!this.privacy_agree || !this.term_agree) {
        alert('필수항목에 동의하셔야 합니다.')
        return false
      }

      return true
    },
    async Submit () {
      const result = await this.Validation()
      if (result) {
        try {
          this.loading(true)

          this.form.nickname = this.form.name + new Date().getTime()
          const response = await this.$axios.post('/api/register', this.form)

          let user = response.data.data

          if (process.env.VUE_APP_ENV === 'LOCAL') {
            this.$cookies.set('DonggleAcessToken', response.data.access_token)
          } else {
            this.$cookies.set('DonggleAcessToken', response.data.access_token)
          }

          const data = await this.$axios
            .put('/api/users/store_join_req')
            .then(response => response.data)

          if (data.state === 1) {
            user = data.query
          }

          this.$store.commit('setUser', user)

          const store = await this.$axios
            .get('/api/store/store/view', { params: {} })
            .then(response => response.data)

          this.$store.commit('setStore', store.query || {})

          await this.$cookies.set('isConfirm', store.query.confirm === 1)

          if (response.status === 200) {
            this.$router.push('/register/complete')
          } else {
            alert('회원가입에 실패했습니다. 관리자에게 문의하세요.')
          }
        } catch (e) {
          console.log(e)
        } finally {
          this.loading(false)
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  #page-register-wrap {
    @media all and (max-width: 576px) {
      .panel-default {
        padding-top: 70px;
      }
    }

    .form-container .in-divider {
      margin: 30px 0px;

      &:first-of-type {
        margin-top: 15px;
      }
    }
  }

  .input_btn_with_textbox_wrap {
    position: relative;
    margin-bottom: 7px;

    &:last-child {
      margin-bottom: 0;
    }

    .input_btn_with_textbox_btn {
      position: absolute;
      right: 0px;
      bottom: 0px;
      width: 28%;
      padding: 12px 0px;
      text-align: center;
      letter-spacing: -1.2px;
      cursor: pointer;
      font-size: 13px;
    }

    .dg-btn {
      display: block;
      background: #333;
      color: white;
    }
  }

  .dg-reg_check_form {
    position: relative;
    margin: 30px 0px 0px;
    &:before {
      content: "";
      display: block;
      height: 2px;
      width: 2.5em;
      margin: 13px 0px;
      background-color: #707070;
    }
  }

  .reg_check {
    color: #787878;
  }

  .reg_check_peice {
    background: #fafafa;
    margin: 20px 0px 35px;
    padding: 20px 15px;
    .dg-reg_agree {
      position: relative;
      margin: 8px 0px;
      > p {
        font-size: 0.75em;
        padding: 10px 2em 5px;
        width: 100%;
        color: #c8c8c8;
        user-select: auto;
      }
    }
    .view_agree_desc {
      position: absolute;
      right: 4%;
      top: 34%;
      color: #c8c8c8;
      font-size: 0.625em;
      vertical-align: middle;
      &:before {
        content: "";
        display: block;
        position: absolute;
        top: 0.5em;
        right: -5px;
        width: 5px;
        height: 5px;
        border-top: 1px solid #c8c8c8;
        border-left: 1px solid #c8c8c8;
        transform: rotate(135deg);
      }
    }
  }

  .dg-input-checkbox_label {
    position: relative;
    cursor: pointer;
    display: inline-block;
    top: 9px;
    width: 21px;
    height: 21px;
    margin: 3px 5px 3px 0px;
    border-radius: 50%;
    border: 1px solid #c8c8c8;
    background-color: white;
  }

  .dg-input-checkbox:checked {
    & + .dg-input-checkbox_label {
      background: linear-gradient(
        to bottom,
        rgba(161, 52, 232, 1) 0%,
        rgba(252, 78, 173, 1) 100%
      );
      border: 1px solid transparent;
    }

    & + .dg-input-checkbox_label:after {
      content: "";
      display: block;
      position: absolute;
      top: 28.3%;
      left: 26.4%;
      width: 9px;
      height: 9px;
      border-radius: 50%;
      background: white;
    }
  }

  .dg-input-checkbox_text {
    display: inline-block;
    font-size: 13px;
    color: #787878;
    cursor: pointer;
  }

  .dg-write_eq, .dg-write_wr {
    font-size: 11px;
    padding: 10px 0;
    height: 35px;
  }
  .dg-write_eq {
    color: #ff5656;
  }
  .dg-write_wr {
    color: #3482e8;
  }
</style>
