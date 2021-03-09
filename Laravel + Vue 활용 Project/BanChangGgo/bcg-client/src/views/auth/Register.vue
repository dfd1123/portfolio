<template>
  <div id="register_page" class="page page--hd_default">
    <header-component hd-title="회원가입 (내 정보 입력)" @privButtonClick="privButtonClick" />
    <div class="page_container">
      <!-- step01. 기본정보 입력 -->
      <transition appear name="slide-fade">
        <div id="regi_step_01" class="contents" v-show="registerStep == 1">
          <fieldset class="page_field">
            <legend class="visually-hidden">기본정보 입력</legend>
            <div class="form-stretch">
              <div class="form_item ta_center">
                <input
                  ref="file1"
                  id="profile_pic"
                  type="file"
                  accept=".jpg, .jpeg, .png, .webp"
                  style="display: none"
                  @change="imageFileChange('file1')"
                />
                <label for="profile_pic" class="user_profile_picture">
                  <figure class="pic_circle">
                    <img ref="img_file1" />
                    <span class="text" v-html="files.file1 ? '' : '프로필<br />이미지'"></span>
                  </figure>
                  <i class="upload_btn">
                    <img src="/assets/images/btn/btn_profile.svg" />
                  </i>
                </label>
              </div>
              <div class="form_item">
                <label for="#" class="form_label">이름</label>
                <div class="form_input_group">
                  <input v-model="userName" type="text" class="form_input" placeholder="이름을 입력하세요" />
                </div>
              </div>
              <div class="form_item">
                <label for="#" class="form_label">이메일</label>
                <div class="form_input_group">
                  <input
                    v-model="emailId"
                    type="email"
                    class="form_input"
                    placeholder="사용하시는 이메일을 입력하세요."
                  />
                </div>
              </div>
              <div class="form_item">
                <label for="#" class="form_label">비밀번호</label>
                <div class="form_input_group">
                  <input
                    v-model="password"
                    type="password"
                    class="form_input"
                    placeholder="6자 이상 입력하세요."
                  />
                </div>
              </div>
              <div class="form_item">
                <label for="#" class="form_label">비밀번호 확인</label>
                <div class="form_input_group">
                  <input
                    v-model="passwordConfirm"
                    type="password"
                    class="form_input"
                    placeholder="6자 이상 입력하세요."
                  />
                </div>
                <transition name="slide-fade">
                  <div v-if="password && passwordConfirm">
                    <span v-if="password === passwordConfirm" class="form_caution ta_right">
                      <i class="color_skyblue-01">비밀번호가 일치합니다.</i>
                    </span>
                    <span v-else class="form_caution ta_right">
                      <i class="color_red-01">비밀번호가 일치하지 않습니다.</i>
                    </span>
                  </div>
                </transition>
              </div>
              <input
                :class="{ active: isReadyTo02 }"
                type="button"
                value="다음"
                class="wide_btn btn_clear_to_theme step_btn"
                @click="registerStep01"
              />
            </div>
          </fieldset>
        </div>
      </transition>
      <!-- end -->
      <!-- step02. 이메일 인증 -->
      <transition name="slide-fade">
        <div id="regi_step_02" class="contents" v-show="registerStep == 2">
          <fieldset class="page_field">
            <legend class="visually-hidden">이메일 인증</legend>
            <div class="form-stretch">
              <div class="form_item">
                <label for="#" class="form_label">이메일</label>
                <div class="form_input_group">
                  <input v-model="emailId" type="text" class="form_input bd_bt_0" readonly />
                  <input
                    type="button"
                    :value="isEmailCertified ? '인증완료' : (isVerifyEmailSent ? '인증번호 다시받기' : '인증번호 받기')"
                    class="wide_radius_5_btn btn_theme_to_gray"
                    :class="{active: !isEmailCertified && !isVerifyEmailSending}"
                    @click="sendVerifyEmail"
                    :disabled="isEmailCertified || isVerifyEmailSending"
                  />
                </div>
              </div>
              <div class="form_item" v-show="isVerifyEmailSent">
                <label for="#" class="form_label">인증번호 입력</label>
                <div class="form_input_group">
                  <input
                    v-model="emailCertifyCode"
                    type="number"
                    class="form_input form_input--none_num"
                    placeholder="인증번호를 입력하세요."
                    :readonly="isEmailCertified"
                  />
                </div>
                <transition name="slide-fade">
                  <span v-if="isVerifyEmailSent" class="form_caution">
                    <i class="color_theme-01">{{ countDownTime }}</i> 내에 인증번호를 입력해주세요.
                  </span>
                </transition>
              </div>
              <input
                :class="{ active: isReadyTo03 }"
                type="button"
                value="다음"
                class="wide_btn btn_clear_to_theme step_btn"
                @click="registerStep02"
              />
            </div>
          </fieldset>
        </div>
      </transition>
      <!-- end -->
      <!-- step03. 기타정보(성별,연령,해당사항) 입력 -->
      <transition name="slide-fade">
        <div id="regi_step_03" class="contents" v-show="registerStep == 3">
          <fieldset class="page_field">
            <legend class="visually-hidden">상세사항 입력</legend>
            <div class="form-stretch">
              <div class="form_item">
                <label for="#" class="form_label">성별을 선택해주세요.</label>
                <div class="form_input_group">
                  <ul class="grid_group">
                    <li class="row-2">
                      <figure
                        class="choice_box"
                        :class="{active: gender === 1}"
                        @click="gender = 1"
                      >
                        <span class="in_icon">
                          <img src="/assets/images/btn/btn_gender-male.svg" />
                        </span>
                        <figcaption class="in_caption">남자</figcaption>
                      </figure>
                    </li>
                    <li class="row-2">
                      <figure
                        class="choice_box"
                        :class="{active: gender === 2}"
                        @click="gender = 2"
                      >
                        <span class="in_icon">
                          <img src="/assets/images/btn/btn_gender-female.svg" />
                        </span>
                        <figcaption class="in_caption">여자</figcaption>
                      </figure>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="form_item">
                <label for="#" class="form_label">연령을 선택해주세요.</label>
                <div class="form_input_group">
                  <select v-model="birth" class="form_select form_select--not_bg">
                    <option value>선택</option>
                    <option v-for="birth in birthOptions" :key="birth" :value="birth">{{birth}}</option>
                  </select>
                  <i>년생</i>
                </div>
              </div>
              <div class="form_item">
                <label for="#" class="form_label">본인 해당 사항을 선택해주세요.</label>
                <div class="form_input_group">
                  <ul class="grid_group">
                    <li class="row-2">
                      <figure
                        class="choice_box choice_box--age"
                        :class="{active: type === '수험생/학부모'}"
                        @click="type = '수험생/학부모'"
                      >
                        <span class="in_icon">
                          <img src="/assets/images/btn/btn_type-2080.svg" />
                        </span>
                        <figcaption class="in_caption">
                          <br />수험생 / 학부모
                        </figcaption>
                      </figure>
                    </li>
                    <li class="row-2">
                      <figure
                        class="choice_box choice_box--age"
                        :class="{active: type === '1인가구'}"
                        @click="type = '1인가구'"
                      >
                        <span class="in_icon">
                          <img src="/assets/images/btn/btn_type-solo.svg" />
                        </span>
                        <figcaption class="in_caption">
                          <br />2030 세대
                        </figcaption>
                      </figure>
                    </li>
                    <li class="row-2">
                      <figure
                        class="choice_box choice_box--age"
                        :class="{active: type === '장년층'}"
                        @click="type = '장년층'"
                      >
                        <span class="in_icon">
                          <img src="/assets/images/btn/btn_type-5060.svg" />
                        </span>
                        <figcaption class="in_caption">
                          5060
                          <br />장년층
                        </figcaption>
                      </figure>
                    </li>
                    <li class="row-2">
                      <figure
                        class="choice_box choice_box--age"
                        :class="{active: type === '기타'}"
                        @click="type = '기타'"
                      >
                        <span class="in_icon">
                          <img src="/assets/images/btn/btn_type-etc.svg" />
                        </span>
                        <figcaption class="in_caption">
                          <br />기타
                        </figcaption>
                      </figure>
                    </li>
                  </ul>
                </div>
              </div>
              <input
                :class="{ active: isReadyTo04 }"
                type="button"
                value="다음"
                class="wide_btn btn_clear_to_theme step_btn"
                @click="registerStep03"
              />
            </div>
          </fieldset>
        </div>
      </transition>
      <!-- end -->
      <!-- step04. 기타정보(키,몸무게) 입력 -->
      <transition name="slide-fade">
        <div id="regi_step_04" class="contents" v-show="registerStep == 4">
          <fieldset class="page_field">
            <legend class="visually-hidden">상세사항 입력</legend>
            <div class="form-stretch">
              <div class="form_item">
                <label for="#" class="form_label">키를 입력해주세요.</label>
                <div class="form_input_group">
                  <input
                    v-model="height"
                    type="number"
                    class="form_input form_input--none_num"
                    placeholder="입력"
                  />
                  <i class="form_input_unit">CM</i>
                </div>
              </div>
              <div class="form_item">
                <label for="#" class="form_label">몸무게를 입력해주세요.</label>
                <div class="form_input_group">
                  <input
                    v-model="weight"
                    type="number"
                    class="form_input form_input--none_num"
                    placeholder="입력"
                  />
                  <i class="form_input_unit">KG</i>
                </div>
              </div>
              <input
                :class="{ active: isReadyTo05 }"
                type="button"
                value="완료"
                class="wide_btn btn_clear_to_theme step_btn"
                @click="registerStep04"
              />
            </div>
          </fieldset>
        </div>
      </transition>
      <!-- end -->
      <!-- step05. 회원가입 완료 -->
      <transition name="slide-fade">
        <div id="regi_step_05" class="page" v-show="registerStep == 5">
          <theme-bg-02></theme-bg-02>
          <div class="desc_contents contents">
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
            </div>
            <article class="form-stretch">
              <template v-if="user.usr_batch && user.usr_batch.bt_no">
                <h2>
                  <b>반창꼬 회원가입</b>이
                  <br />완료되었습니다!
                </h2>
                <p>
                  반창꼬에 로그인하여,
                  <br />나의 건강사항을 작성하고
                  <br />1:1 맞춤 건강설계
                  <br />[건강 리포트]를 받아보세요!
                </p>
                <input
                  type="button"
                  value="건강사항 작성하기"
                  class="wide_btn btn_clear_to_theme active step_btn"
                  @click="goButtonClick"
                />
              </template>
              <template v-else>
                <h2>
                  <b>반창꼬 회원가입</b>이
                  <br />완료되었습니다!
                </h2>
                <p>
                  반창꼬에 로그인하여,
                  <br />나의 건강사항을 작성하고
                  <br />1:1 맞춤 건강설계
                  <br />[건강 리포트]를 신청해보세요!
                </p>
                <input
                  type="button"
                  value="회원가입 완료하기"
                  class="wide_btn btn_clear_to_theme active step_btn"
                  @click="goButtonClick"
                />
              </template>
            </article>
          </div>
        </div>
      </transition>
      <!-- end -->
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import { mapGetters, mapActions } from 'vuex'
import range from 'lodash/range'

export default {
  name: 'register',
  data () {
    return {
      isEmailCertified: false,
      isVerifyEmailSent: false,
      isVerifyEmailSending: false,
      isRegistering: false,
      userName: '',
      emailId: '',
      password: '',
      passwordConfirm: '',
      emailCertifyCode: '',
      registerStep: 1,
      countDownTimer: null,
      countDownTime: '03:00',
      gender: '',
      type: '',
      birth: '',
      birthOptions: range(1910, Number(this.$moment().add(-5, 'year').year()) + 1).reverse(),
      height: '',
      weight: '',
      files: {}
    }
  },
  mounted () {
    if (!this.$route.hash) {
      this.$router.push({ name: 'register', hash: `#${this.registerStep}` })
    }
  },
  destroyed () {
    window.window.clearInterval(this.countDownTimer)
  },
  computed: {
    ...mapGetters(['user']),
    isPasswordValid () {
      return Boolean(this.password && this.password.length >= 6 && this.passwordConfirm && this.password === this.passwordConfirm)
    },
    isReadyTo02 () {
      return Boolean(this.userName && this.emailId && this.isPasswordValid)
    },
    isReadyTo03 () {
      return Boolean(this.isVerifyEmailSent && this.emailCertifyCode)
    },
    isReadyTo04 () {
      return Boolean(this.gender && this.type && this.birth)
    },
    isReadyTo05 () {
      return Boolean(this.height && this.weight)
    }
  },
  watch: {
    $route () {
      if (Number(this.$route.hash.substring(1)) < this.registerStep) {
        this.privButtonClick()
      }
    }
  },
  methods: {
    ...mapActions(['getUser']),
    async registerStep01 () {
      if (!this.userName) {
        this.$swal({
          text: '이름을 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      if (!this.emailId) {
        this.$swal({
          text: '이메일을 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      if (!this.password) {
        this.$swal({
          text: '비밀번호를 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      if (!this.passwordConfirm) {
        this.$swal({
          text: '비밀번호 확인을 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      if (!this.isPasswordValid) {
        this.$swal({
          text: '비밀번호를 6자 이상 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      try {
        // TODO 로딩 적용

        await this.$axios.post('/email/duplicate', {
          usr_email: this.emailId
        })

        this.registerStep = 2

        this.$router.push({ name: 'register', hash: `#${this.registerStep}` })
      } catch (e) {
        if (e.response) {
          const { status, data } = e.response
          if (status === 422) {
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
              timer: 1300
            })
          }
        }

        console.log(e)
      } finally {
        // TODO 로딩 해제
      }
    },
    async registerStep02 () {
      if (!this.emailCertifyCode) {
        this.$swal({
          text: '인증번호를 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      if (this.isEmailCertified) {
        this.registerStep = 3
        return
      }

      try {
        // TODO 로딩 적용

        await this.$axios.post('/email/certify', {
          usr_email: this.emailId,
          verify_code: this.emailCertifyCode
        })

        this.countDownReset(3 * 60 * 1000)

        this.$swal({
          text: '이메일 인증이 완료되었습니다.',
          type: 'success',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1700
        })

        this.isEmailCertified = true
        this.registerStep = 3

        this.$router.push({ name: 'register', hash: `#${this.registerStep}` })
      } catch (e) {
        if (e.response) {
          const { status, data } = e.response
          if (status === 422) {
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
              timer: 1300
            })
          }
        }

        console.log(e)
        this.isEmailCertified = false
      } finally {
        // TODO 로딩 해제
      }
    },
    async registerStep03 () {
      if (!this.gender) {
        this.$swal({
          text: '성별을 선택하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      if (!this.birth) {
        this.$swal({
          text: '연령을 선택하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      if (!this.type) {
        this.$swal({
          text: '본인 해당 사항을 선택하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      this.registerStep = 4
    },
    async registerStep04 () {
      if (!this.height || !Number(this.height)) {
        this.$swal({
          text: '키를 정확히 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      if (!this.weight || !Number(this.weight)) {
        this.$swal({
          text: '몸무게를 정확히 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1000
        })
        return
      }

      try {
        // TODO 로딩 적용
        if (this.isRegistering) {
          return
        }
        this.isRegistering = true

        const formData = new FormData()
        if (this.files.file1) {
          formData.append('file1', await this.compressImage(this.files.file1.files[0]))
        }
        formData.append('usr_name', this.userName)
        formData.append('usr_email', this.emailId)
        formData.append('usr_pwd', this.password)
        formData.append('usr_reg_type', 'app')
        formData.append('usr_extra', JSON.stringify({
          gender: this.gender === 1 ? 'M' : 'F',
          born_year: this.birth,
          user_type: this.type,
          unreg_info: null,
          height: this.height,
          weight: this.weight
        }))
        formData.append('verify_code', this.emailCertifyCode)

        localStorage.token = await this.$axios
          .post('/register', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          }).then(response => response.data.access_token)

        await this.getUser()

        localStorage.removeItem('planQuestionData')

        this.registerStep = 5

        this.$router.push({ name: 'register', hash: `#${this.registerStep}` })
      } catch (e) {
        if (e.response) {
          const { status, data } = e.response
          if (status === 409 || status === 422) {
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
              timer: 1300
            })
          }
        }

        console.log(e)
      } finally {
        // TODO 로딩 해제
        this.isRegistering = false
      }
    },
    async sendVerifyEmail () {
      try {
        // TODO 로딩 적용
        if (this.isVerifyEmailSending) {
          return
        }
        this.isVerifyEmailSending = true

        await this.$axios.post('/email/verify', {
          usr_email: this.emailId
        })

        this.countDownStart(3 * 60 * 1000, () => {
          this.isVerifyEmailSent = false
        })

        this.emailCertifyCode = ''
        this.isVerifyEmailSent = true
      } catch (e) {
        if (e.response) {
          const { status, data } = e.response
          if (status === 422) {
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
              timer: 1300
            })
          }
        }

        this.isVerifyEmailSent = false
        console.log(e)
      } finally {
        // TODO 로딩 해제
        this.isVerifyEmailSending = false
      }
    },
    countDownStart (time, callback) {
      if (this.countDownTimer !== null) {
        window.clearInterval(this.countDownTimer)
      }

      let duration = this.$moment.duration(time, 'milliseconds')
      this.countDownTime = this.$moment
        .utc(duration.asMilliseconds())
        .format('mm:ss')

      this.countDownTimer = window.setInterval(() => {
        duration = this.$moment.duration(duration - 1000, 'milliseconds')
        if (duration.asMilliseconds() === 0) {
          window.clearInterval(this.countDownTimer)
          callback()
        }
        this.countDownTime = this.$moment
          .utc(duration.asMilliseconds())
          .format('mm:ss')
      }, 1000)
    },
    countDownReset (time) {
      window.clearInterval(this.countDownTimer)
      const duration = this.$moment.duration(time, 'milliseconds')
      this.countDownTime = this.$moment
        .utc(duration.asMilliseconds())
        .format('mm:ss')
      this.countDownTimer = null
    },
    privButtonClick () {
      switch (this.registerStep) {
        case 1:
          this.$router.push('/login')
          break
        case 2:
          this.countDownReset(3 * 60 * 1000)
          this.isEmailCertified = false
          this.isVerifyEmailSent = false
          this.emailCertifyCode = ''
          this.registerStep = 1
          break
        case 3:
          this.registerStep = 2
          break
        case 4:
          this.registerStep = 3
          break
      }
    },
    imageFileChange (name) {
      const fileInput = this.$refs[name]
      Vue.set(this.files, name, fileInput)

      const { files } = fileInput
      if (FileReader && files && files.length) {
        const fr = new FileReader()
        fr.onload = () => {
          const tag = this.$refs[`img_${name}`]
          if (tag.tagName.toLowerCase() === 'img') {
            tag.src = fr.result
          } else {
            tag.style['background-image'] = `url(${fr.result})`
          }
        }
        fr.readAsDataURL(files[0])
      }
    },
    goButtonClick () {
      this.$router.replace('/plan/question')
    },
    popState (e) {
      alert()
      this.$router.replace('/register')
    }
  }
}
</script>

<style lang="scss" scoped>
#register_page {
  .step_btn {
    margin-top: auto;
    min-height: 53px;
  }

  .form-stretch {
    display: flex;
    flex-direction: column;
    min-height: 100%;
    padding-bottom: 2rem;
  }

  .page_container {
    overflow: scroll;
    -webkit-overflow-scrolling: touch;
  }

  .contents {
    position: absolute;
    top: 0;
    left: 0;
    background-color: white;
    -webkit-overflow-scrolling: touch;
  }
}

#regi_step_01 {
  .user_profile_picture .pic_circle {
    img {
      @include position($t: 0, $l: 0);
    }
  }
}

#regi_step_05 {
  @include position(fixed, $t: 0, $l: 0);
  @include zindex("popup");
  background-color: #fabb3c;

  .logo_bcg {
    @include zindex("default");
    @include position($t: -145px, $l: 50%);
    @include translate($x: -50%, $y: 0);
  }

  .desc_contents {
    height: 65vh;
    background-color: white;
    text-align: center;
    position: relative;
    opacity: 0;
    transform: translateY(30px);
    animation: finishOpenUp 0.8s linear 1;
    animation-fill-mode: forwards;
    animation-timing-function: cubic-bezier(0.51, 0.92, 0.24, 1.15);
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
        top: -85px;
      }
    }

    & > article {
      @include zindex("default");
      position: relative;
      padding-top: 20px;
    }
    & > article h2 {
      @include remFont("26px", $weight: 400);
      line-height: 1.3;
      padding: 20px 0 15px;
    }
    & > article h2 b {
      font-weight: bold;
    }
    & > p {
      font-weight: 400;
    }
  }
}
@keyframes finishOpenUp {
  100% {
    opacity: 1;
    transform: translate(0);
  }
}
</style>
