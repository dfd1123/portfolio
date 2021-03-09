<template>
  <div id="find_password_contents">
    <!-- header -->
    <header id="dg-find-hd">
      <h1 class="dg-find-hd-logo dg_blind">
        donggle
      </h1>
      <div class="dg-find-hd-step_wrap">
        <h2>
          비밀번호 찾기
          <router-link
            to="/find"
            class="_back_btn"
          >
            뒤로가기
          </router-link>
        </h2>
      </div>
    </header>
    <!-- header E -->

    <!-- content -->
    <section
      id="findPw1"
      class="step_common"
    >
      <h2 class="dg_blind">
        비밀번호 찾기
      </h2>
      <form
        action="#"
        method="post"
      >
        <fieldset class="dg-write_form">
          <legend class="dg_blind">
            비밀번호 찾기
          </legend>
          <div class="dg_write write_mail">
            <label for="email">이메일</label>
            <input
              type="email"
              id="email"
              class="input_text_box"
              placeholder="이메일 아이디 입력"
              v-model="form.email"
            >
          </div>
          <div class="dg_write write_name">
            <label for="name">이름</label>
            <input
              type="text"
              id="name"
              class="input_text_box"
              maxlength="20"
              placeholder="실명 입력"
              v-model="form.name"
            >
          </div>
          <div class="dg_write write_tel">
            <label for="mobile_number">휴대폰 번호</label>
            <div class="input_btn_with_textbox_wrap">
              <input
                type="tel"
                id="mobile_number"
                class="input_text_box input_text_box_with_btn"
                placeholder="본인 명의 휴대폰 번호"
                maxlength="13"
                v-model="form.mobile_number"
                @input="TimeStop()"
              >
              <input
                type="button"
                class="dg-btn input_btn_with_textbox_btn"
                value="인증번호 발송"
                v-if="!isRequestCertify"
                :disabled="mobileVerfied"
                @click="MobileCheck()"
              >
              <input
                type="button"
                class="dg-btn input_btn_with_textbox_btn"
                value="인증번호 재발송"
                v-else
                :disabled="mobileVerfied"
                @click="MobileCheck()"
              >
            </div>
            <label
              for="mobile_certify"
              class="dg_blind"
            >인증번호</label>
            <div class="input_btn_with_textbox_wrap">
              <input
                type="number"
                id="mobile_certify"
                class="input_text_box input_text_box_with_btn"
                placeholder="인증번호 입력"
                maxlength="6"
                v-model="mobile_certify"
              >
              <span class="dg-tel_countdown">{{ resTimeData }}</span>
              <input
                type="button"
                class="dg-btn input_btn_with_textbox_btn"
                value="인증완료"
                v-if="isResponseCertify && mobileVerfied"
                :disabled="mobileVerfied"
              >

              <input
                type="button"
                class="dg-btn input_btn_with_textbox_btn"
                value="인증하기"
                v-else
                :disabled="mobileVerfied"
                @click="ResMobileVerify()"
              >
            </div>
            <p
              v-if="isResponseCertify && !mobileVerfied"
              class="dg-write_wr dg-write_wr_nb"
            >
              인증번호가 일치하지 않습니다.
            </p>
          </div>
        </fieldset>
      </form>
      <div class="dg-dubble_btn_wrap clear_both">
        <button
          type="button"
          class="dg-btn_gra width l_width_100 dg-single_btn"
          @click="Submit()"
        >
          확인
        </button>
      </div>
    </section>
    <!-- content E -->
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        form: {
          email: '',
          name: '',
          mobile_number: ''
        }
      }
    },
    methods: {
      async MobileCheck () {
        if (this.form.mobile_number === '' || this.form.mobile_number === null) {
          await this.WarningAlert('휴대폰 번호를 입력하세요!')
          return false
        }

        const params = {
          mobile_number: this.form.mobile_number
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'users/phone_check', { params })).data

          if (res.state === 1) {
            if (res.query) {
              if (this.isRequestCertify) {
                this.SmsReset(this.form.mobile_number)
              } else {
                this.ReqMobileVerify(this.form.mobile_number)
              }

              this.SuccessAlert('인증번호가 SMS로 발송되었습니다.')
            } else {
              this.WarningAlert('가입 되지 않은 전화번호 입니다.')
            }
          } else {
            console.log(res.msg)
          }
        } catch (e) {

        }
      },
      async Validation () {
        if (this.form.email === '') {
          await this.WarningAlert('가입하신 이메일 주소를 입력하세요!')
          return false
        }

        if (this.form.name === '') {
          await this.WarningAlert('이름(실명)을 입력하세요!')
          return false
        }

        if (this.form.mobile_number === '') {
          await this.WarningAlert('휴대폰 번호를 입력하세요!')
          return false
        }

        if (!this.mobileVerfied) {
          await this.WarningAlert('휴대폰 인증이 되지 않으셨습니다.')
          return false
        }

        return true
      },
      async Submit () {
        if (this.Validation()) {
          const params = this.form

          if (this.isRequestCertify && this.isResponseCertify && this.mobileVerfied) {
            const res = (await this.$http.get(this.$APIURI + 'users/search_pw', { params })).data

            if (res.query) {
              this.$router.push({ name: 'find-pw-complete', query: params })
            } else {
              this.WarningAlert('가입된 유저가 아닙니다. ID찾기를 통해 ID를 다시 확인해주세요.')
            }
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
