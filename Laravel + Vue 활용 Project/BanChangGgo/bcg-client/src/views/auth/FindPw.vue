<template>
  <div id="findpw_page" class="page--hd_default">
    <header-component hd-title="비밀번호 찾기" @privButtonClick="$router.push('/login')" />
    <div class="page_container">
      <transition appear name="slide-fade">
        <div id="findpw_step_01" class="contents" v-if="findPwStep == 1">
          <fieldset class="page_field">
            <legend class="visually-hidden">비밀번호 찾기</legend>
            <div class="form_item">
              <label for="#" class="form_label">이메일</label>
              <div class="form_input_group">
                <input
                  v-model="findpwId"
                  type="text"
                  class="form_input mb-1"
                  placeholder="이메일을 입력해주세요."
                />
                <transition name="slide-fade">
                  <input
                    v-if="findpwId"
                    type="button"
                    :value="isEmailCertified ? '인증완료' : (isVerifyEmailSent ? '인증번호 다시받기' : '인증번호 받기')"
                    :disabled="isVerifyEmailSending"
                    class="wide_radius_5_btn btn_theme_to_gray"
                    :class="{active: !isVerifyEmailSending}"
                    @click="sendFindPasswordEmail"
                  />
                </transition>
              </div>
            </div>
            <transition name="slide-fade">
              <div class="form_item" v-if="isVerifyEmailSent">
                <label for="#" class="form_label">인증번호 입력</label>
                <div class="form_input_group">
                  <input
                    v-model="findPwCertifyCode"
                    type="number"
                    class="form_input form_input--none_num"
                    placeholder="인증번호를 입력하세요."
                  />
                </div>
              </div>
            </transition>
            <input
              :class="{ active: findPwCertifyCode }"
              :disabled="!findPwCertifyCode"
              type="submit"
              value="다음"
              class="wide_btn btn_clear_to_theme step_btn"
              @click="findPwStep02"
            />
          </fieldset>
        </div>
      </transition>
      <transition name="slide-fade">
        <div id="findpw_step_02" class="contents" v-if="findPwStep == 2">
          <fieldset class="page_field">
            <legend class="visually-hidden">비밀번호 재설정</legend>
            <p class="in_text ta_center">비밀번호를 재설정합니다.</p>
            <div class="form_item">
              <label for="#" class="form_label">비밀번호</label>
              <div class="form_input_group">
                <input
                  v-model="resetPw"
                  value="1234"
                  type="password"
                  class="form_input"
                  placeholder="비밀번호를 입력해주세요."
                />
              </div>
            </div>
            <transition name="slide-fade">
              <div class="form_item">
                <label for="#" class="form_label">비밀번호 확인</label>
                <div class="form_input_group">
                  <input
                    v-model="resetPwConfirm"
                    value="1234"
                    type="password"
                    class="form_input form_input--none_num"
                    placeholder="비밀번호를 확인해주세요."
                  />
                </div>
                <transition name="slide-fade">
                  <div v-if="resetPw && resetPwConfirm">
                    <span v-if="resetPw === resetPwConfirm" class="form_caution ta_right">
                      <i class="color_skyblue-01">비밀번호가 일치합니다.</i>
                    </span>
                    <span v-else class="form_caution ta_right">
                      <i class="color_red-01">비밀번호가 일치하지 않습니다.</i>
                    </span>
                  </div>
                </transition>
              </div>
            </transition>
            <input
              :class="{ active: resetPw && resetPwConfirm && resetPw === resetPwConfirm }"
              :disabled="resetPw && resetPwConfirm && resetPw === resetPwConfirm ? false : true"
              type="submit"
              value="확인"
              class="wide_btn btn_clear_to_theme step_btn"
              @click="changePw"
            />
          </fieldset>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'findpw',
  data () {
    return {
      isVerifyEmailSending: false,
      isVerifyEmailSent: false,
      isEmailCertified: false,
      findpwId: '',
      findPwCertifyCode: '',
      findPwStep: 1,
      token: '',
      resetPw: '',
      resetPwConfirm: ''
    }
  },
  methods: {
    ...mapActions(['getUser']),
    async sendFindPasswordEmail () {
      try {
        if (this.isVerifyEmailSending) {
          return
        }
        this.isVerifyEmailSending = true

        await this.$axios.post('/password/find/request', {
          usr_email: this.findpwId
        })

        this.findPwCertifyCode = ''
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

        console.log(e)
      } finally {
        // TODO 로딩 해제
        this.isVerifyEmailSending = false
      }
    },
    async findPwStep02 () {
      try {
        // TODO 로딩 적용

        this.token = await this.$axios.post('/password/find/certify', {
          usr_email: this.findpwId,
          verify_code: this.findPwCertifyCode
        }).then(response => response.data.access_token)

        this.findPwCertifyCode = ''
        this.isVerifyEmailSent = true
        this.findPwStep = 2
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
      }
    },
    async changePw () {
      try {
        // TODO 로딩 적용
        localStorage.token = this.token

        await this.$axios.post('/detail', {
          usr_pwd: this.resetPw
        })

        await this.getUser()

        this.$swal({
          text: '비밀번호 재설정이 \n완료되었습니다.',
          confirmButtonText: '확인',
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup',
            content: 'bcg-swal__content',
            confirmButton: 'bcg-swal__confirm-button bcg-swal__button--single'
          }
        }).then(() => {
          this.$router.replace('/')
        })
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

        localStorage.removeItem('token')
        console.log(e)
      } finally {
        // TODO 로딩 해제
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#findpw_page {
  .step_btn {
    @include btButton;
  }

  .page_container {
    overflow: hidden;
  }

  .contents {
    @include position($t: 0, $l: 0);
    background-color: white;
  }
}

#findpw_step_02 {
  .in_text {
    padding: 10px 0;
  }
}
</style>
