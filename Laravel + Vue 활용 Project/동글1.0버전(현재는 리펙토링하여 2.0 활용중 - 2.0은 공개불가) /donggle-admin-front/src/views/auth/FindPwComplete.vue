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
          id="page-find-pw-wrap"
          class="page-auth-wrap"
        >
          <div class="panel-default">
            <div class="form-wrapper">
              <div class="show-pc auth-logo-wrap">
                <h2 class="auth-hd-logo hide-text">donggle</h2>
                <p class="_sub_title">동글 입점스토어 관리자</p>
              </div>
              <div class="dg-find-title-wrap">
                <h2>
                  비밀번호 재설정
                  <a
                    href="#"
                    class="icon-back-btn"
                    @click.prevent="$router.push('/find-choice')"
                  >뒤로가기</a>
                </h2>
              </div>

              <!-- content -->
              <section class="auth-layout ">
                <div class="_find-complete">
                  <h2>
                    안녕하세요,
                    <b>{{ form.name }}</b>님!
                    <br />
                  </h2>
                  <div>
                    인증이 완료되어 비밀번호를 새로 설정합니다.
                    <br />비밀번호를 잊어버리지 않게 주의하세요!
                  </div>
                </div>
                <form>
                  <fieldset class="form-container type-01">
                    <legend class="hide-text">비밀번호 입력</legend>
                    <div class="in-divider">
                      <label for="password">새 비밀번호</label>
                      <input
                        type="password"
                        id="password"
                        class="form-input-txt"
                        placeholder="비밀번호"
                        v-model="form.password"
                      />
                    </div>
                    <div class="in-divider">
                      <label
                        for="password_confirm"
                        class="none"
                      >비밀번호 재입력</label>
                      <input
                        type="password"
                        id="password_confirm"
                        class="form-input-txt form-input-txt_rewrite"
                        placeholder="비밀번호 재입력"
                        v-model="form.password_confirm"
                      />
                      <!-- 인풋 입력값에 따라 하나만 보이게 -->
                      <p
                        v-if="PasswordMatched"
                        class="_write_eq"
                      >비밀번호가 일치합니다.</p>
                      <p
                        v-if="PasswordUnMatched"
                        class="_write_wr none"
                      >비밀번호가 일치하지 않습니다.</p>
                    </div>
                  </fieldset>
                </form>
                <div class="dg-btn-wrap clearfix">
                  <button
                    type="button"
                    class="square-md-btn btn-gradient auth-btn _full"
                    @click="Submit()"
                  >비밀번호 변경 후 로그인</button>
                </div>
              </section>
              <!-- content E -->
            </div>
          </div>
        </div>
        <!-- page content E -->
      </div>

      <layout-footer />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
export default {
  name: 'FindPwComplete',
  beforeRouteEnter (to, from, next) {
    console.log(from)

    if (from.name === 'find-pw' || from.name === 'find-pw-complete') {
      next()
    } else {
      next('/find-choice')
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
        const res = (await this.$axios.put('/api/users/password', params)).data

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

<style>
</style>
