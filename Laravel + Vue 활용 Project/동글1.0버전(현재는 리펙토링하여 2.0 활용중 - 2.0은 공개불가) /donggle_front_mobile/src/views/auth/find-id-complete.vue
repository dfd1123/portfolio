<template>
  <div id="find_choice_contents">
    <header id="dg-find-hd">
      <h1 class="dg-find-hd-logo dg_blind">
        donggle
      </h1>
      <div class="dg-find-hd-step_wrap">
        <h2>
          아이디 찾기 완료
          <router-link
            to="/find/choice"
            class="_back_btn"
          >
            뒤로가기
          </router-link>
        </h2>
      </div>
    </header>
    <section
      id="findID_end"
      class="step_common"
    >
      <router-link
        to="/"
        class="_home_btn"
      >
        홈으로
      </router-link>
      <div class="dg-popup_alert_desc">
        <h2>안녕하세요, <b>{{ form.name }}</b>님!<br></h2>
        <div>
          찾으시려는 이메일 주소는
          <b>{{ email }}</b><br>
          입니다.
        </div>
      </div>
      <div class="dg-dubble_btn_wrap clear_both">
        <router-link
          to="/find/pw"
          class="dg-btn_line dg-dubble_btn"
        >
          비밀번호 찾기
        </router-link>
        <router-link
          to="/login"
          class="dg-btn_gra dg-dubble_btn"
        >
          로그인
        </router-link>
      </div>
    </section>
  </div>
</template>

<script>
  export default {
    beforeRouteEnter (to, from, next) {
      console.log(from)

      if (from.name === 'find-id') {
        next()
      } else {
        next('/find')
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
        const res = (await this.$http.get(this.$APIURI + 'users/search_id', { params })).data
        if (res.state === 1) {
          let tempArr = res.query.email.split('@')
          this.email = tempArr[0].substring(0, tempArr[0].length - 3) + '***@' + tempArr[1]
        } else {
          await this.WarningAlert(res.msg)
          this.$router.go(-1)
        }
      } catch (e) {
        console.log(e)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
