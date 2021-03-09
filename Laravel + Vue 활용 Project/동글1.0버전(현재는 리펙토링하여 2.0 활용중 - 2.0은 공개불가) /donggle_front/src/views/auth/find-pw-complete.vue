<template>
  <div>
    <RegHeader
      page-name="비밀번호 찾기 완료"
      :only-logo="false"
      :step-view="false"
    />
    <section
      id="findPW_end"
      class="step_common"
    >
      <div class="dg-popup_alert_desc">
        <h2>안녕하세요, <b>{{ form.name }}</b>님!<br></h2>
        <div>
          인증이 완료되어 비밀번호를 새로 설정합니다.<br>
          비밀번호를 잊어버리지 않게 주의하세요!
        </div>
      </div>
      <form>
        <fieldset class="dg-write_form">
          <legend class="dg_blind">
            비밀번호 입력
          </legend>
          <div class="dg_write write_pw">
            <label for="password">새 비밀번호</label>
            <input
              type="password"
              id="password"
              class="input_text_box"
              placeholder="비밀번호"
              v-model="form.password"
            >
            <label
              for="password_confirm"
              class="dg_blind"
            >비밀번호 재입력</label>
            <input
              type="password"
              id="password_confirm"
              class="input_text_box input_text_box_rewrite"
              placeholder="비밀번호 재입력"
              v-model="form.password_confirm"
            >
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
        </fieldset>
      </form>
      <div class="btn_write_complete_wrap">
        <button
          type="button"
          class="dg-btn_gra btn_write_complete"
          @click="Submit()"
        >
          비밀번호 변경 후 로그인
        </button>
      </div>
    </section>
    <!-- content E -->
  </div>
</template>

<script>
  import RegHeader from '@/components/common/reg-header.vue'

  export default {
    beforeRouteEnter (to, from, next) {
      console.log(from)

      if (from.name === 'find-pw' || from.name === 'find-pw-complete') {
        next()
      } else {
        next('/find')
      }
    },
    components: {
      RegHeader
    },
    data: function () {
      return {
        form: {
          email: this.$route.query.email,
          name: this.$route.query.name,
          mobile_number: this.$route.query.mobile_number,
          password: '',
          password_confirm: ''
        }
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
      }
    },
    methods: {
      async Submit () {
        const params = this.form
        try {
          const res = (await this.$http.put(this.$APIURI + 'users/password', params)).data

          if (res.query) {
            this.$router.push('/login')
          } else {
            this.ErrorAlert(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
