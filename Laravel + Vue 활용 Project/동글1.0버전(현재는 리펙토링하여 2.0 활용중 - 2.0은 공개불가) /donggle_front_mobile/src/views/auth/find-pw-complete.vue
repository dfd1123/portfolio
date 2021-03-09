<template>
  <div id="find_password_contents">
    <!-- header -->
    <header id="dg-find-hd">
      <h1 class="dg-find-hd-logo dg_blind">
        donggle
      </h1>
      <div class="dg-find-hd-step_wrap">
        <h2>
          비밀번호 찾기 완료
          <router-link
            to="/find/choice"
            class="_back_btn"
          >
            뒤로가기
          </router-link>
        </h2>
      </div>
    </header>
    <!-- header E -->
    <section
      id="findPW_end"
      class="step_common"
    >
      <a
        href="#"
        class="_home_btn"
      >홈으로</a>
      <div class="dg-popup_alert_desc">
        <h2>안녕하세요, <b>{{ form.name }}</b>님!<br></h2>
        <div>
          인증이 완료되어 비밀번호를 새로 설정합니다.<br>
          비밀번호를 잊어버리지 않게 주의하세요!
        </div>
      </div>
      <form
        action="#"
        method="post"
      >
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
  </div>
</template>

<script>
  export default {
    beforeRouteEnter (to, from, next) {
      console.log(from)

      if (from.name === 'find-pw' || from.name === 'find-pw-complete') {
        next()
      } else {
        next('/find')
      }
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
