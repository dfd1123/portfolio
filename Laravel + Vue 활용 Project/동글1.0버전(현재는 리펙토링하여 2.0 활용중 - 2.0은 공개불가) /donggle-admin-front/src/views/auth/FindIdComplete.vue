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
                  아이디 찾기 완료
                  <a
                    href="#"
                    class="icon-back-btn"
                    @click.prevent="$router.push('/find-choice')"
                  >뒤로가기</a>
                </h2>
              </div>
              <!-- header E -->

              <!-- content -->
              <section class="auth-layout">
                <div class="_find-complete">
                  <h2>
                    안녕하세요,
                    <b>{{ form.name }}</b>님!
                    <br />
                  </h2>
                  <div>
                    찾으시려는 이메일 주소는
                    <br />
                    <b class="fw-b">{{ email }}</b>
                    <br />입니다.
                  </div>
                  <div
                    class="dg-btn-wrap clearfix"
                    style="margin-top: 20px;"
                  >
                    <router-link
                      to="/find/pw"
                      class="square-md-btn btn-outline-gray"
                      style="margin: 10px 0;"
                    >비밀번호 찾기</router-link>
                    <router-link
                      to="/login"
                      class="square-md-btn btn-gradient"
                    >로그인</router-link>
                  </div>
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
  name: 'FindIdComplete',
  beforeRouteEnter (to, from, next) {
    console.log(from)

    if (from.name === 'find-id') {
      next()
    } else {
      next('/find-choice')
    }
  },
  data: function () {
    return {
      form: {
        name: this.$route.query.name,
        mobile_number: this.$route.query.mobile_number
      },
      email: ''
    }
  },
  async created () {
    const params = this.form
    try {
      const res = (await this.$axios.get('/api/users/search_id', { params })).data
      if (res.state === 1) {
        const tempArr = res.query.email.split('@')
        this.email = tempArr[0].substring(0, tempArr[0].length - 3) + '***@' + tempArr[1]
      } else {
        if (!res.query) {
          alert(res.msg)
        }

        this.$router.go(-1)
      }
    } catch (e) {
      console.log(e)
    }
  }
}
</script>
