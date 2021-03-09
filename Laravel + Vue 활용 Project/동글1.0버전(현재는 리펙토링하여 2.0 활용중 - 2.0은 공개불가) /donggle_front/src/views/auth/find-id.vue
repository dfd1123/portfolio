<template>
  <div>
    <RegHeader
      page-name="아이디 찾기"
      :only-logo="false"
      :step-view="false"
    />
    <!-- content -->
    <section
      id="findID1"
      class="step_common"
    >
      <h2 class="dg_blind">
        아이디 찾기
      </h2>
      <form>
        <fieldset class="dg-write_form">
          <legend class="dg_blind">
            아이디 찾기
          </legend>
          <div class="dg_write write_name">
            <label for="name">이름</label>
            <input
              type="text"
              id="name"
              class="input_text_box"
              maxlength="10"
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
        <router-link
          to="/login"
          class="dg-btn_line dg-dubble_btn"
        >
          취소
        </router-link>
        <button
          type="button"
          class="dg-btn_gra dg-dubble_btn"
          @click="Submit()"
        >
          확인
        </button>
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

<style lang="scss" scoped>
  .dg-btn_gra {
    margin-right: 0 !important;
  }
</style>
