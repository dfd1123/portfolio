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
                    @click.prevent="$router.push('/login')"
                  >뒤로가기</a>
                </h2>
              </div>
              <!-- header E -->

              <!-- content -->
              <section class="auth-layout clearfix">
                <h2 class="hide-text">아이디 비밀번호 찾기</h2>
                <div class="_find_choice_btn _find_id_btn">
                  <h3>아이디 찾기</h3>
                  <p>휴대폰 인증을 통하여 아이디를 찾습니다.</p>
                  <a
                    href="#"
                    class="_next_page_btn"
                    @click.prevent="$router.push('/find-id')"
                  >바로가기</a>
                </div>
                <div class="_find_choice_btn _find_pw_btn">
                  <h3>비밀번호 찾기</h3>
                  <p>휴대폰 인증을 통하여 비밀번호를 찾습니다.</p>
                  <a
                    href="find-pw.html"
                    class="_next_page_btn"
                    @click.prevent="$router.push('/find-pw')"
                  >바로가기</a>
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
  </div>
</template>

<script>
export default {
  name: 'Login',
  data () {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    async login () {
      if (!this.email) {
        alert('이메일을 입력해 주세요')
        return
      }

      if (!this.password) {
        alert('비밀번호를 입력해 주세요')
        return
      }

      try {
        this.$router.push('/home')
      } catch (e) {
        console.log(e)

        if (e.response) {
          const { status, data } = e.response
          if (status === 401) {
            alert(data.message)
          }
        }
      }
    },
    findPw () {
      // this.$router.push('/home')
    }
  }
}
</script>

<style>
</style>
