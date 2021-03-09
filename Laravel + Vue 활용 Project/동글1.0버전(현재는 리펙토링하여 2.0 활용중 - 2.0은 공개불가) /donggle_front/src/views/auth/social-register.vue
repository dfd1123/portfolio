<template>
  <div>
    <RegHeader
      page-name="회원가입"
      :only-logo="false"
      :step-view="true"
      step="2"
    />
    <section
      id="step2"
      class="step_common"
    >
      <h2>회원정보를 입력해주세요.</h2>
      <form>
        <fieldset class="dg-write_form">
          <legend class="dg_blind">
            회원가입
          </legend>
          <div class="dg_write write_mail">
            <label for="email">이메일</label>
            <div class="input_btn_with_textbox_wrap">
              <input
                type="email"
                class="input_text_box input_text_box_with_btn"
                ref="email"
                placeholder="사용하실 이메일 입력"
                v-model="form.email"
                :readonly="this.$route.query.email"
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
            </div>
          </div>
          <div class="dg_write write_name">
            <label for="name">이름</label>
            <input
              type="text"
              id="name"
              class="input_text_box"
              maxlength="10"
              placeholder="실명 입력"
              ref="name"
              v-model="form.name"
            >
          </div>
          <div class="dg_write write_nickname">
            <label for="nickname">닉네임</label>
            <div class="input_btn_with_textbox_wrap">
              <input
                type="text"
                id="nickname"
                class="input_text_box input_text_box_with_btn"
                placeholder="사용하실 닉네임 입력"
                ref="nickname"
                @input="nicknameChecked = false"
                v-model="form.nickname"
              >
              <input
                type="button"
                class="dg-btn input_btn_with_textbox_btn"
                value="체크완료"
                v-if="nicknameChecked && nicknameAvailable"
              >
              <input
                type="button"
                class="dg-btn input_btn_with_textbox_btn"
                value="중복체크"
                v-else
                @click="NickNameDupleCheck()"
              >
            </div>
            <p
              v-if="nicknameChecked && nicknameAvailable"
              class="dg-write_eq dg-write_eq_nn"
            >
              사용가능한 닉네임입니다.
            </p>
            <p
              v-if="nicknameChecked && !nicknameAvailable"
              class="dg-write_wr dg-write_wr_nn"
            >
              이미 사용중인 닉네임입니다.
            </p>
          </div>
          <div class="dg_write write_tel">
            <label for="#">휴대폰 번호</label>
            <div class="input_btn_with_textbox_wrap">
              <input
                type="tel"
                id="#"
                class="input_text_box input_text_box_with_btn"
                placeholder="본인 명의 휴대폰 번호"
                maxlength="13"
                :disabled="mobileVerfied"
                @input="isRequestCertify = false"
                ref="mobile_number"
                v-model="form.mobile_number"
              >

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
            <label
              for="mobile_certify"
              class="dg_blind"
            >인증번호</label>
            <div
              v-if="isRequestCertify && PhoneCertifyStatus && !mobileVerfied"
              class="input_btn_with_textbox_wrap"
            >
              <input
                type="number"
                id="mobile_certify"
                class="input_text_box input_text_box_with_btn input_num_box"
                placeholder="인증번호 입력"
                maxlength="6"
                :disabled="mobileVerfied"
                @input="isResponseCertify = false"
                v-model="form.certify_num"
              >
              <span class="dg-tel_countdown">{{ resTimeData }}</span>
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
            </div>
            <p
              v-if="isResponseCertify && !mobileVerfied"
              class="dg-write_wr dg-write_wr_nb"
            >
              인증번호가 일치하지 않습니다.
            </p>
          </div>
          <!-- 추가된 사항 성별/생일 -->
          <div class="dg_write write_gender">
            <label for="#">성별</label>
            <div class="dg-reg_gender">
              <input
                type="radio"
                id="woman"
                name="gender"
                value="woman"
                class="dg-input-checkbox display_none"
                ref="gender"
                v-model="form.gender"
              >
              <label
                for="woman"
                class="dg-input-checkbox_label dg_write_agree_pr"
              ></label>
              <label
                for="woman"
                class="dg-input-checkbox_text dg_write_agree_pr"
              >여자</label>
            </div>
            <div class="dg-reg_gender">
              <input
                type="radio"
                id="man"
                name="gender"
                value="man"
                class="dg-input-checkbox display_none"
                ref="gender"
                v-model="form.gender"
              >
              <label
                for="man"
                class="dg-input-checkbox_label dg_write_agree_pr"
              ></label>
              <label
                for="man"
                class="dg-input-checkbox_text dg_write_agree_pr"
              >남자</label>
            </div>
          </div>
          <div class="dg_write write_birth">
            <span class="_label">생일</span>
            <Datepicker
              id="birthday"
              class="input_text_box"
              v-model="form.birthday"
              :format="'yyyy-MM-dd'"
              :disabled-dates="startDateDisableDates.disabledDates"
              maxlength="10"
              placeholder="2019-00-00"
              :language="lang"
            />
          </div>

          <div class="dg_write write_size">
            <label for="wear_size">평소 착용사이즈</label>
            <select
              v-if="form.gender === 'man'"
              v-model="form.wear_size"
              id="wear_size"
              class="input_text_box reg_select"
            >
              <option>
                설정해놓은 사이즈
              </option>
              <option value="90">
                90
              </option>
              <option value="95">
                95
              </option>
              <option value="100">
                100
              </option>
              <option value="105">
                105
              </option>
              <option value="110">
                110
              </option>
              <option value="115">
                115 이상
              </option>
            </select>
            <select
              v-else-if="form.gender === 'woman'"
              v-model="form.wear_size"
              id="wear_size"
              class="input_text_box reg_select"
            >
              <option>
                설정해놓은 사이즈
              </option>
              <option value="44">
                44
              </option>
              <option value="55">
                55
              </option>
              <option value="66">
                66
              </option>
              <option value="77">
                77
              </option>
              <option value="88">
                88
              </option>
              <option value="99">
                99 이상
              </option>
            </select>
            <select
              v-else
              id="wear_size"
              class="input_text_box reg_select"
            >
              <option>
                성별을 먼저 선택해주세요.
              </option>
            </select>
            <p class="dg-reg_ex">
              사이즈는 마이페이지 > 내 정보 관리 에서 변경 가능합니다.
            </p>
          </div>
        </fieldset>
        <fieldset class="dg-reg_check_form">
          <div class="dg_write write_check">
            <!-- test, test1, test2, test3를 #으로 변경 -->
            <input
              type="checkbox"
              id="all_agree"
              class="dg-input-checkbox display_none"
              v-model="all_agree"
              @change="AllAgree()"
            >
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
                  class="dg-input-checkbox display_none"
                  v-model="term_agree"
                  @change="AllCheckedConfirm()"
                >
                <label
                  for="term_agree"
                  class="dg-input-checkbox_label dg_write_agree_use"
                ></label>
                <label
                  for="term_agree"
                  class="dg-input-checkbox_text dg_write_agree_use"
                >이용약관 필수 동의</label>
                <a
                  href="#"
                  class="view_agree_desc"
                >보기</a>
              </div>
              <div class="dg-reg_agree">
                <input
                  type="checkbox"
                  id="privacy_agree"
                  class="dg-input-checkbox display_none"
                  v-model="privacy_agree"
                  @change="AllCheckedConfirm()"
                >
                <label
                  for="privacy_agree"
                  class="dg-input-checkbox_label dg_write_agree_pr"
                ></label>
                <label
                  for="privacy_agree"
                  class="dg-input-checkbox_text dg_write_agree_pr"
                >개인정보 처리방침 필수 동의</label>
                <a
                  href="#"
                  class="view_agree_desc"
                >보기</a>
              </div>
              <div class="dg-reg_agree">
                <input
                  type="checkbox"
                  id="ad_agree"
                  class="dg-input-checkbox display_none"
                  v-model="form.ad_agree"
                  @change="AllCheckedConfirm()"
                >
                <label
                  for="ad_agree"
                  class="dg-input-checkbox_label dg_write_agree_cp"
                ></label>
                <label
                  for="ad_agree"
                  class="dg-input-checkbox_text dg_write_agree_cp"
                >쿠폰 / 이벤트 알림 선택동의</label>
                <p>
                  SMS, 이메일을 통해 파격할인/이벤트/<br>
                  쿠폰 정보를 받아보실 수 있습니다.
                </p>
              </div>
            </div>
          </div>
          <div class="btn_write_complete_wrap">
            <button
              type="button"
              class="dg-btn_gra btn_write_complete"
              @click="Submit()"
            >
              회원가입하기
            </button>
          </div>
        </fieldset>
      </form>
    </section>
  </div>
</template>

<script>
  import RegHeader from '@/components/common/reg-header.vue'
  import Datepicker from 'vuejs-datepicker'
  import { ko } from 'vuejs-datepicker/dist/locale'

  export default {
    components: {
      RegHeader,
      Datepicker
    },
    data: function () {
      return {
        form: {
          register_kind: 1,
          email: this.$route.query.email,
          name: this.$route.query.name,
          nickname: this.$route.query.nickname,
          mobile_number: this.$route.query.mobile_number,
          gender: '',
          birthday: '',
          wear_size: this.$route.query.wear_size,
          ad_agree: this.$route.query.ad_agree === 1,
          register_type: this.$route.query.register_type
        },
        isEmailVerify: !!this.$route.query.email,
        emailAvailable: !!this.$route.query.email,
        beforePhone: '',
        all_agree: false,
        term_agree: false,
        privacy_agree: false,
        nicknameAvailable: false,
        nicknameChecked: false,
        /* datepicker setting */
        startDateDisableDates: {
          disabledDates: {
            from: this.$moment().subtract(10, 'years')._d
          }
        },
        lang: ko
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      if (this.CheckDevice() === 'mobile') {
        location.href = 'http://localhost:8081/oauth/register?name=' + this.$route.query.name + '&email=' + this.$route.query.email + '&profile_img=' + this.$route.query.profile_img + '&nickname=' + this.$route.query.nickname + '&gender=' + this.$route.query.gender + '&register_type=' + this.$route.query.register_type
      }

      let result = false
      if (this.$route.query.register_type === 'already') {
        result = await this.WarningAlert('이미 다른 방식으로 가입된 계정입니다.')
        if (result) {
          this.$router.go(-1)
        }
      }

      if (!this.$route.query.email && !this.$route.query.name) {
        result = await this.WarningAlert('잘못된 접근입니다.')
        if (result) {
          this.$router.go(-1)
        }
      }
      this.$store.commit('ProgressHide')
    },
    computed: {
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
      },
      WearSize () {
        const man = [90, 95, 100, 105, 110, 115, 120, 125, 130]
        const woman = [44, 55, 66, 77, 88, 99, 110]
        if (this.form.gender === 'man') {
          return man
        } else {
          return woman
        }
      }
    },
    methods: {
      async NickNameDupleCheck () {
        // this.nicknameClassName
        if (this.form.nickname === '') {
          this.WarningAlert('닉네임을 입력해주세요!')
          this.nicknameAvailable = false

          return false
        }

        try {
          const params = {
            nickname: this.form.nickname
          }
          const nicknameExist = (await this.$http.get(this.$APIURI + 'users/nickname_check', { params })).data.query

          this.nicknameChecked = true

          if (!nicknameExist) {
            this.SuccessAlert('사용 가능한 닉네임 입니다.')
            this.nicknameAvailable = true
          } else {
            this.WarningAlert('이미 존재하거나 사용이 불가한 닉네임 입니다.')
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
          this.WarningAlert('이메일을 입력해주세요!')
          this.isEmailVerify = false

          return false
        }

        try {
          const params = {
            email: this.form.email
          }

          const emailExist = (await this.$http.get(this.$APIURI + 'users/email_check', { params })).data.query

          this.emailAvailable = !emailExist
          if (this.emailAvailable) {
            this.SuccessAlert('사용 가능한 이메일 주소 입니다.')
            this.isEmailVerify = true
          } else {
            this.WarningAlert('이미 존재하거나 사용이 불가한 이메일 주소입니다.')
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
          this.InfoAlert('이미 인증을 완료 하셨습니다')
          return false
        }

        if (this.form.mobile_number === '') {
          this.WarningAlert('휴대폰 번호를 입력하세요!')
          this.isRequestCertify = false

          return false
        }

        this.isRequestCertify = true
        this.beforePhone = this.form.mobile_number

        try {
          const params = {
            mobile_number: this.form.mobile_number
          }

          const phoneExist = (await this.$http.get(this.$APIURI + 'users/phone_check', { params })).data.query

          if (!phoneExist) {
            this.sms_certify_num = this.RandomCode()

            const params = {
              mobile_number: this.form.mobile_number,
              txt: '(주)동글 SMS 인증번호 [' + this.sms_certify_num + ']'
            }
            this.$http.get(this.$APIURI + 'sms/send', { params })
            this.isRequestCertify = true
            this.SuccessAlert('인증번호가 SMS로 발송되었습니다.')
          } else {
            this.isRequestCertify = false
            this.WarningAlert('이미 가입된 휴대폰 번호 입니다.')
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
          this.WarningAlert('인증번호를 입력하세요!')
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
          await this.WarningAlert('이메일 주소를 입력해주세요!')
          this.$refs.email.focus()
          return false
        }

        if (this.form.nickname === '') {
          await this.WarningAlert('닉네임을 입력하세요!')
          this.$refs.nickname.focus()
          return false
        }

        if (!this.nicknameChecked || !this.nicknameAvailable) {
          await this.WarningAlert('닉네임 중복검사를 진행하지 않으셨거나 이미 존재하는 닉네임 입니다!')
          this.$refs.nickname.focus()
          return false
        }

        if (this.form.mobile_number === '') {
          await this.WarningAlert('휴대폰 번호를 입력하세요!')
          this.$refs.mobile_number.focus()
          return false
        }

        if (!this.mobileVerfied) {
          await this.WarningAlert('휴대폰 인증을 하지 않으셨습니다!')
          this.$refs.mobile_number.focus()
          return false
        }

        if (this.form.gender === '') {
          await this.WarningAlert('성별을 선택하세요!')
          this.$refs.gender.focus()
          return false
        }

        if (this.form.birthday === '') {
          await this.WarningAlert('생년월일을 선택하세요!')
          this.$refs.birthday.focus()
          return false
        } else {
          this.form.birthday = this.$moment(this.form.birthday).format('YYYY-MM-DD')
        }

        if (this.form.wear_size === '') {
          await this.WarningAlert('평소 착용사이즈를 선택하세요!')
          this.$refs.wear_size.focus()
          return false
        }

        if (!this.privacy_agree || !this.term_agree) {
          await this.WarningAlert('필수항목에 동의하셔야 합니다.')
          return false
        }

        return true
      },
      async Submit () {
        if (this.Validation()) {
          try {
            this.$store.commit('ProgressShow')

            this.form.birthday = this.CustomFormatter(this.form.birthday)
            this.form.profile_img = this.$route.query.profile_img
            const response = await this.$http.post(this.$APIURI + 'social/register', this.form)

            const user = response.data.data

            if (process.env.VUE_APP_ENV === 'LOCAL') {
              this.$cookies.set('access_token', response.data.access_token)
            } else {
              this.$cookies.set('access_token', response.data.access_token)
            }

            await this.$store.commit('UserStoreInfor', user)

            if (response.status === 200) {
              this.$router.push('/subscribe')
            } else {
              if (response.query) {
                this.WarningAlert(response.query.msg)
              } else {
                this.ErrorAlert('회원가입에 실패했습니다. 관리자에게 문의하세요.')
              }
            }
          } catch (e) {
            console.log(e)
          } finally {
            this.$store.commit('ProgressHide')
          }
        }
      },
      CustomFormatter (date) {
        return this.$moment(date).format('YYYY-MM-DD')
      },
      CheckDevice () {
        let ua = window.navigator.userAgent

        ua = ua.toLowerCase()
        var platform = {}
        var matched = {}
        var userPlatform = 'pc'
        var platformMatch = /(ipad)/.exec(ua) || /(ipod)/.exec(ua) ||
          /(windows phone)/.exec(ua) || /(iphone)/.exec(ua) ||
          /(kindle)/.exec(ua) || /(silk)/.exec(ua) || /(android)/.exec(ua) ||
          /(win)/.exec(ua) || /(mac)/.exec(ua) || /(linux)/.exec(ua) ||
          /(cros)/.exec(ua) || /(playbook)/.exec(ua) ||
          /(bb)/.exec(ua) || /(blackberry)/.exec(ua) ||
          []

        matched.platform = platformMatch[0] || ''

        if (matched.platform) {
          platform[matched.platform] = true
        }

        if (platform.android || platform.bb || platform.blackberry ||
          platform.ipad || platform.iphone ||
          platform.ipod || platform.kindle ||
          platform.playbook || platform.silk ||
          platform['windows phone']) {
          userPlatform = 'mobile'
        }

        if (platform.cros || platform.mac || platform.linux || platform.win) {
          userPlatform = 'pc'
        }

        return userPlatform
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dg-write_eq_nn,
  .dg-write_wr_pw {
    display: block;
  }

  .write_birth .input_text_box {
    border: none;
    background: none;
    margin: 12px 0;
    padding: 0px;
  }

  .vdp-datepicker > div > input {
    width: 100%;
  }
</style>
