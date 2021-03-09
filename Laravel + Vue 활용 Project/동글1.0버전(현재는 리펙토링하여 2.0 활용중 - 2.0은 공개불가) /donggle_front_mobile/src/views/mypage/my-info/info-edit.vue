<template>
  <div
    id="dg-mypage-set-info-wrapper"
    class="mypage-set-info-wrapper"
  >
    <!-- * 마이페이지 헤더 -->
    <div class="_page_title_wrap">
      <h2>
        내 정보 관리
        <button
          type="button"
          class="_back_btn"
          @click="$router.go(-1)"
        >
          뒤로가기
        </button>
      </h2>
      <div class="second_title clear_both">
        <router-link
          to="/mypage/info"
          style="width:50%;"
        >
          내 정보 관리
        </router-link>
        <router-link
          to="/mypage/level_guide"
          style="width:50%;"
        >
          회원등급
        </router-link>
      </div>
    </div>
    <!-- * 마이페이지 헤더 -->

    <div class="l-mypage-contents">
      <!-- 1) 내정보 관리 -->
      <div class="l-con-area">
        <div class="l-con-article">
          <div>
            <!-- * 내정보 관리 영역 -->
            <fieldset class="dg-set-info-group">
              <ul class="in-information">
                <!-- [1] 이름 -->
                <li class="_list">
                  <span class="_label">이름</span>
                  <div class="_input_group">
                    <span class="_readonly_text">{{ $store.state.user.name }}</span>
                  </div>
                </li>
                <!-- [1] E -->

                <!-- [2] 이메일 -->
                <li class="_list">
                  <span class="_label">이메일</span>
                  <div class="_input_group">
                    <span class="_readonly_text">{{ $store.state.user.email }}</span>
                  </div>
                </li>
                <!-- [2] E -->

                <!-- [3] 비밀번호 -->
                <li
                  v-if="!$store.state.user.register_type"
                  class="_list"
                >
                  <span class="_label _label--top">비밀번호</span>

                  <div class="_input_group">
                    <!-- ※ 변경하기 누르면 아래 dg-change-view 화면 펼쳐짐 -->
                    <div
                      v-if="!changePwView"
                      class="_in_desc"
                    >
                      <button
                        type="button"
                        class="square-btn-outline"
                        @click="ChangePwView()"
                      >
                        변경하기
                      </button>
                    </div>
                    <!-- ※ 변경하기 누르면 아래 dg-change-view 화면 펼쳐짐 E -->

                    <!-- dg-change-view 화면 (구분클래스: type-pw) -->
                    <div
                      v-if="changePwView"
                      class="dg-change-view type-pw"
                    >
                      <div class="in-input-area">
                        <input
                          type="password"
                          class="_input"
                          placeholder="비밀번호"
                          v-model="form.password"
                        >
                      </div>
                      <div class="in-input-area">
                        <input
                          type="password"
                          class="_input"
                          placeholder="비밀번호 재입력"
                          v-model="form.password_confirm"
                        >
                      </div>

                      <!-- ※ 비밀번호 일치할 경우 (클래스 able 추가, 비밀번호가 일치합니다. )
                           ※ 비밀번호 불일치할 경우 (클래스 disable 추가, 비밀번호가 일치하지 않습니다. )
                              가상선택자로 텍스트 설정해놔서 클래스만 변경하면 됩니다.
                      -->
                      <span :class="['_pw_state', {'able':PasswordMatched}, {'disable':PasswordUnMatched}]"></span>
                      <!-- ※ END -->
                      <!--
                      <div class="set-info-btn_wrap">
                        <button
                          type="button"
                          class="square-btn-outline w-80"
                          onclick="alert('변경폼이 접힙니다.')"
                        >
                          취소
                        </button>
                        <button
                          type="button"
                          class="square-btn-outline w-80"
                          onclick="alert('(스윗알럿)비밀번호 변경이 완료되었습니다.')"
                        >
                          완료
                        </button>
                      </div>
                      -->
                    </div>
                    <!-- dg-change-view 화면 (구분클래스: type-pw) E -->
                  </div>
                </li>
                <!-- [3] E -->

                <!-- [4] 휴대폰 번호 -->
                <li class="_list">
                  <span class="_label _label--top">휴대폰 번호</span>

                  <div class="_input_group">
                    <!-- ※ 변경하기 누르면 아래 dg-change-view 화면 펼쳐짐 -->
                    <div class="_in_desc clear_both">
                      <span class="_num">{{ $store.state.user.mobile_number }}</span>
                      <button
                        type="button"
                        class="square-btn-outline"
                        v-if="!changeMbView"
                        @click="ChangeMbView()"
                      >
                        변경하기
                      </button>
                    </div>
                    <!-- ※ 변경하기 누르면 아래 dg-change-view 화면 펼쳐짐 E -->

                    <!-- dg-change-view 화면 (구분클래스: type-phonenum) -->
                    <div
                      v-if="changeMbView"
                      class="dg-change-view type-phonenum"
                    >
                      <span class="_mini_label">변경할 휴대폰 번호</span>

                      <div class="in-input-area type-withbtn">
                        <input
                          type="number"
                          class="_input"
                          placeholder="본인 명의 휴대폰 번호"
                          maxlength="13"
                          v-model="form.mobile_number"
                          @input="TimeStop()"
                        >
                        <button
                          type="button"
                          class="square-btn-dark"
                          v-if="!isRequestCertify"
                          :disabled="mobileVerfied"
                          @click="ReqMobileVerify(form.mobile_number)"
                        >
                          인증요청
                        </button>
                        <button
                          type="button"
                          class="square-btn-dark"
                          v-else
                          :disabled="mobileVerfied"
                          @click="SmsReset()"
                        >
                          인증번호 재발송
                        </button>
                      </div>

                      <div class="in-input-area type-withbtn">
                        <input
                          type="number"
                          class="_input"
                          placeholder="인증번호 입력"
                          maxlength="6"
                          v-model="mobile_certify"
                        >
                        <span class="dg-tel_countdown">{{ resTimeData }}</span>
                        <button
                          type="button"
                          class="square-btn-dark"
                          v-if="isResponseCertify && mobileVerfied"
                          :disabled="mobileVerfied"
                        >
                          인증완료
                        </button>
                        <button
                          type="button"
                          class="square-btn-dark"
                          v-else
                          :disabled="mobileVerfied"
                          @click="ResMobileVerify()"
                        >
                          인증하기
                        </button>
                      </div>

                      <!--
                      <button
                        type="button"
                        class="square-btn-outline w-80"
                        @click="changeMbView = false"
                      >
                        취소
                      </button>
                      <button
                        type="button"
                        class="square-btn-outline w-80"
                        @click="MobileNumberChange"
                      >
                        완료
                      </button>
                      -->
                    </div>
                    <!-- dg-change-view 화면 (구분클래스: type-phonenum) E -->
                  </div>
                </li>
                <!-- [4] E -->

                <li class="_list">
                  <span class="_label _label--top">닉네임</span>

                  <div class="_input_group">
                    <!-- ※ 변경하기 누르면 아래 dg-change-view 화면 펼쳐짐 -->
                    <div
                      v-if="!changeNiView"
                      class="_in_desc clear_both"
                    >
                      <span class="_num">{{ $store.state.user.nickname }}</span>
                      <button
                        type="button"
                        class="square-btn-outline"
                        @click="ChangeNiView()"
                      >
                        변경하기
                      </button>
                    </div>
                    <!-- ※ 변경하기 누르면 아래 dg-change-view 화면 펼쳐짐 E -->

                    <!-- dg-change-view 화면 (구분클래스: type-pw) -->
                    <div
                      v-if="changeNiView"
                      class="dg-change-view type-pw"
                    >
                      <span class="_mini_label">변경할 닉네임</span>
                      <div class="in-input-area type-withbtn">
                        <input
                          type="text"
                          class="_input"
                          placeholder="닉네임"
                          @input="duplNickname = false"
                          v-model="form.nickname"
                        >
                        <button
                          type="button"
                          class="square-btn-dark"
                          v-if="!duplNickname"
                          @click="DuplNickname"
                        >
                          중복검사
                        </button>
                        <button
                          type="button"
                          class="square-btn-dark"
                          v-else
                        >
                          사용가능
                        </button>
                      </div>
                    </div>
                    <!-- dg-change-view 화면 (구분클래스: type-pw) E -->
                  </div>
                </li>

                <!-- [5] 성별 -->
                <li class="_list">
                  <span class="_label">성별</span>
                  <div class="_input_group">
                    <span class="_check_option">
                      <input
                        type="radio"
                        id="woman"
                        value="woman"
                        class="dg-input-checkbox display_none"
                        v-model="form.gender"
                      >
                      <label
                        for="woman"
                        class="dg-input-checkbox_label"
                      ></label>
                      <label for="woman">여자</label>
                    </span>
                    <span class="_check_option">
                      <input
                        type="radio"
                        id="man"
                        value="man"
                        class="dg-input-checkbox display_none"
                        v-model="form.gender"
                      >
                      <label
                        for="man"
                        class="dg-input-checkbox_label"
                      ></label>
                      <label for="man">남자</label>
                    </span>
                  </div>
                </li>
                <!-- [5] E -->

                <!-- [6] 생일 -->
                <li class="_list">
                  <span class="_label">생일</span>
                  <div class="_input_group">
                    <!-- ※ datepicker 적용해야함 -->
                    <Datepicker
                      class="my-info"
                      v-model="form.birthday"
                      :format="CustomFormatter"
                      :disabled-dates="startDateDisableDates.disabledDates"
                      placeholder="2000-00-00"
                      :language="lang"
                    />
                    <!-- ※ datepicker 적용해야함 END -->
                  </div>
                </li>
                <!-- [6] E -->

                <!-- [7] 평소 착용사이즈 -->
                <li class="_list">
                  <span class="_label">평소 착용사이즈</span>
                  <div class="_input_group type-pd-narrow">
                    <select
                      v-if="form.gender === 'man'"
                      v-model="form.wear_size"
                      class="_slct"
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
                      v-if="form.gender === 'woman'"
                      v-model="form.wear_size"
                      class="_slct"
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
                      v-if="form.gender === null || form.gender === undefined"
                      class="_slct"
                    >
                      <option>
                        성별을 먼저 선택해주세요.
                      </option>
                    </select>
                  </div>
                </li>
                <!-- [7] E -->

                <!-- [8] 알림설정 -->
                <li class="_list _list--alarm">
                  <span class="_label _label--top">알림설정</span>
                  <div class="_input_group type-pd-narrow">
                    <span class="_etc_ment">SMS, 이메일을 통해 파격할인/이벤트/쿠폰 정보를 받아보실 수 있습니다.</span>
                    <span class="_check_option">
                      <input
                        type="checkbox"
                        id="sms"
                        v-model="form.sms_notify"
                        class="dg-input-checkbox display_none"
                      >
                      <label
                        for="sms"
                        class="dg-input-checkbox_label"
                      ></label>
                      <label for="sms">SMS</label>
                    </span>
                    <span class="_check_option">
                      <input
                        type="checkbox"
                        id="email"
                        v-model="form.email_notify"
                        class="dg-input-checkbox display_none"
                      >
                      <label
                        for="email"
                        class="dg-input-checkbox_label"
                      ></label>
                      <label for="email">이메일</label>
                    </span>
                  </div>
                </li>
                <!-- [8] E -->
              </ul>

              <div class="dg-button-wrap dg-button-wrap--single">
                <button
                  type="button"
                  class="theme-btn-gradient btn-shadow"
                  @click="Submit"
                >
                  회원정보 수정하기
                </button>
              </div>
            </fieldset>
            <!-- * 내정보 관리 영역 E -->
            <div class="_leave-dg">
              <router-link to="/mypage/secession">
                회원탈퇴
              </router-link>
              <p>회원탈퇴 신청 화면으로 이동합니다.</p>
            </div>
          </div>
        </div>
      </div>
      <!-- 1) 내정보 관리 E -->
    </div>
  </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker'
  import { ko } from 'vuejs-datepicker/dist/locale'

  export default {
    components: {
      Datepicker
    },
    data: function () {
      return {
        form: {
          password: '',
          password_confirm: '',
          mobile_number: this.$store.state.user.mobile_number,
          gender: this.$store.state.user.gender,
          birthday: this.$store.state.user.birthday ? this.$moment(this.$store.state.user.birthday).format('YYYY-MM-DD') : this.$moment().subtract('years', 30).format('YYYY-MM-DD'),
          wear_size: this.$store.state.user.wear_size,
          sms_notify: this.$store.state.user.sms_notify === 1,
          email_notify: this.$store.state.user.email_notify === 1
        },
        changePwView: false,
        changeMbView: false,
        changeNiView: false,
        duplNickname: true,
        /* datepicker setting */
        startDateDisableDates: {
          disabledDates: {
            from: this.$moment().subtract('years', 20)._d
          }
        },
        lang: ko
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
      SmsNotify () {
        if (this.form.sms_notify === true) {
          return 1
        } else {
          return 0
        }
      },
      EmailNotify () {
        if (this.form.email_notify === true) {
          return 1
        } else {
          return 0
        }
      }
    },
    methods: {
      ChangePwView () {
        this.changePwView = true
      },
      ChangeMbView () {
        this.changeMbView = true
      },
      ChangeNiView () {
        this.changeNiView = true
      },
      PasswordChange () {

      },
      MobileNumberChange () {
        console.log('회원정보 수정 response 값은 바뀐 유저 정보 가져오기!')
        // this.$store.commit('UserStoreInfor', user)
      },
      async DuplNickname () {
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

          this.duplNickname = true

          if (!nicknameExist) {
            this.SuccessAlert('사용 가능한 닉네임 입니다.')
          } else {
            this.WarningAlert('이미 존재하거나 사용이 불가한 닉네임 입니다.')
          }

          return true
        } catch (e) {
          console.log(e)
        }
      },
      async Submit () {
        let params = this.form
        params.user = this.$store.state.user
        params.birthday = this.$moment(this.form.birthday).format('YYYY-MM-DD')
        params.sms_notify = this.form.sms_notify ? 1 : 0
        params.email_notify = this.form.email_notify ? 1 : 0

        if (this.changeMbView) {
          if (!this.mobileVerfied) {
            this.WarningAlert('모바일 인증을 하지 않으셨습니다!')
            return false
          }
        }

        if (this.changePwView) {
          if (this.form.password !== this.form.password_confirm) {
            this.WarningAlert('비밀번호가 서로 일치하지 않습니다!')
            return false
          }
        }

        if (!this.duplNickname) {
          this.WarningAlert('닉네임 중복검사를 하지 않으셨습니다!')
          return false
        }

        if (this.form.gender === undefined || this.form.gender === null) {
          this.WarningAlert('성별을 선택하여 주세요!')
          return false
        }

        if (this.form.wear_size === undefined || this.form.wear_size === null) {
          this.WarningAlert('평소 입는 옷 사이즈를 선택하여 주세요!')
          return false
        }

        const res = (await this.$http.put(this.$APIURI + 'info_change', params)).data

        if (res.state === 1) {
          if (res.query !== 0) {
            this.$store.commit('UserStoreInfor', res.query)

            this.form = {
              nickname: this.$store.state.user.nickname,
              password: '',
              password_confirm: '',
              mobile_number: this.$store.state.user.mobile_number,
              gender: this.$store.state.user.gender,
              birthday: this.$moment(this.$store.state.user.birthday).format('YYYY-MM-DD'),
              wear_size: this.$store.state.user.wear_size,
              sms_notify: this.$store.state.user.sms_notify === 1,
              email_notify: this.$store.state.user.email_notify === 1
            }

            this.SuccessAlert('변경이 완료 되었습니다!')
          } else {
            this.InfoAlert('바뀐 정보가 없습니다.')
          }
        } else {
          console.log(res.msg)
        }
      },
      CustomFormatter (date) {
        return this.$moment(date).format('YYYY-MM-DD')
      }
    }
  }
</script>

<style lang="scss">
  .vdp-datepicker > div > input {
    border: 1px solid #f0f0f0;
  }
</style>

<style lang="scss" scoped>
  .dg-tel_countdown {
    position: absolute;
    top: 14px;
    right: 101px;
  }
</style>
