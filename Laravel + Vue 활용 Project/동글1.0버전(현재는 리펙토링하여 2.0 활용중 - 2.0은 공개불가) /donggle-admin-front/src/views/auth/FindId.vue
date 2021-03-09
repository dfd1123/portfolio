<template>
  <div id="app">
    <!-- contents -->
    <div
      id="admin-container"
      class="auth-container"
    >
      <div class="wrapper">
        <!-- page content -->
        <div
          id="page-find-choice-wrap"
          class="page-auth-wrap"
        >
          <div class="panel-default">
            <div class="form-wrapper">
              <!-- header -->
              <div class="show-pc auth-logo-wrap">
                <h2 class="auth-hd-logo hide-text">donggle</h2>
                <p class="_sub_title">동글 입점스토어 관리자</p>
              </div>
              <div class="dg-find-title-wrap">
                <h2>
                  아이디/비밀번호 찾기
                  <a
                    href="#"
                    class="icon-back-btn"
                    @click.prevent="$router.push('/find-choice')"
                  >뒤로가기</a>
                </h2>
              </div>
              <!-- header E -->

              <!-- content -->
              <!-- 섹션으로 단계구분 -->
              <section class="auth-layout">
                <h2 class="hide-text">아이디 찾기</h2>
                <form class="form_wrap">
                  <fieldset class="form-container type-01">
                    <legend class="hide-text">아이디 찾기</legend>
                    <div class="in-divider">
                      <label
                        for="name"
                        class="_label"
                      >이름</label>
                      <input
                        type="text"
                        id="name"
                        class="form-input-txt"
                        maxlength="10"
                        placeholder="실명 입력"
                        v-model="form.name"
                      />
                    </div>
                    <div class="in-divider">
                      <label
                        for="mobile_number"
                        class="_label"
                      >휴대폰 번호</label>
                      <div class="_btn_with_textbox_wrap">
                        <input
                          type="tel"
                          id="mobile_number"
                          class="form-input-txt"
                          placeholder="본인 명의 휴대폰 번호"
                          maxlength="13"
                          v-model="form.mobile_number"
                          @input="TimeStop()"
                        />
                        <input
                          type="button"
                          class="square-md-btn btn-black _with_input-txt"
                          value="인증번호 발송"
                          v-if="!isRequestCertify"
                          :disabled="mobileVerfied"
                          @click="MobileCheck()"
                        />
                        <input
                          type="button"
                          class="square-md-btn btn-black _with_input-txt"
                          value="인증번호 재발송"
                          v-else
                          :disabled="mobileVerfied"
                          @click="MobileCheck()"
                        >
                      </div>
                    </div>
                    <div class="in-divider">
                      <div class="_btn_with_textbox_wrap">
                        <label
                          for="mobile_certify"
                          class="none"
                        >인증번호</label>
                        <input
                          type="number"
                          id="mobile_certify"
                          class="form-input-txt"
                          placeholder="인증번호 입력"
                          maxlength="6"
                          v-model="mobile_certify"
                        />
                        <span class="dg-tel_countdown">{{ resTimeData }}</span>
                        <input
                          type="button"
                          class="square-md-btn btn-black _with_input-txt"
                          value="인증완료"
                          v-if="isResponseCertify && mobileVerfied"
                          :disabled="mobileVerfied"
                        />
                        <input
                          type="button"
                          class="square-md-btn btn-black _with_input-txt"
                          value="인증하기"
                          v-else
                          :disabled="mobileVerfied"
                          @click="ResMobileVerify()"
                        >
                      </div>
                      <p
                        v-if="isResponseCertify && !mobileVerfied"
                        class="_write_wr"
                      >인증번호가 일치하지 않습니다.</p>
                    </div>
                  </fieldset>
                  <div class="dg-btn-wrap dg-dubblebtn-wrap clearfix">
                    <a
                      href="#"
                      class="square-md-btn btn-outline-gray auth-btn"
                      @click.prevent="$router.push('/find-choice')"
                    >취소</a>
                    <button
                      type="button"
                      class="square-md-btn btn-gradient auth-btn"
                      @click="Submit()"
                    >확인</button>
                  </div>
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
  name: 'FindId',
  data: function () {
    return {
      form: {
        name: '',
        mobile_number: ''
      },
      timeCounter: 180,
      mobile_certify: '',
      sms_certify_num: '',
      resTimeData: '',
      mobileVerfied: false,
      isRequestCertify: false,
      isResponseCertify: false
    }
  },
  methods: {
    async MobileCheck () {
      if (this.form.mobile_number === '' || this.form.mobile_number === null) {
        await alert('휴대폰 번호를 입력하세요!')
        return false
      }

      const params = {
        mobile_number: this.form.mobile_number
      }

      try {
        const res = (await this.$axios.get('/api/users/phone_check', { params })).data

        if (res.state === 1) {
          if (res.query) {
            if (this.isRequestCertify) {
              this.SmsReset(this.form.mobile_number)
            } else {
              this.ReqMobileVerify(this.form.mobile_number)
            }

            this.SuccessAlert('인증번호가 SMS로 발송되었습니다.')
          } else {
            alert('가입 되지 않은 전화번호 입니다.')
          }
        } else {
          console.log(res.msg)
        }
      } catch (e) {

      }
    },
    async Validation () {
      if (this.form.name === '') {
        await alert('이름(실명)을 입력하세요!')
        return false
      }

      if (this.form.mobile_number === '') {
        await alert('휴대폰 번호를 입력하세요!')
        return false
      }

      if (!this.mobileVerfied) {
        await alert('휴대폰 인증이 되지 않으셨습니다.')
        return false
      }

      return true
    },
    async Submit () {
      const validation = await this.Validation()
      if (validation) {
        const params = this.form

        if (this.isRequestCertify && this.isResponseCertify && this.mobileVerfied) {
          this.$router.push({ name: 'find-id-complete', query: params })
        }
      }
    }
  }
}
</script>

<style>
</style>
