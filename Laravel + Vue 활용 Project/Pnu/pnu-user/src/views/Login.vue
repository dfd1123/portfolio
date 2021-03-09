<template>
  <section id="login">
    <h1 class="pusan--logo">부산대학교</h1>
    <div class="loginform-container">
      <fieldset class="pusan--loginform">
        <legend class="ps_blind">로그인</legend>
        <div class="pusan--login pusan--login__id">
          <label class="login--user login--user__id">학번</label>
          <input
            v-model="userNo"
            type="text"
            class="login_text_box login_text_box__id"
            placeholder="학번"
          />
        </div>
        <div class="pusan--login pusan--login__ps">
          <label class="login--user login--user_ps">비밀번호</label>
          <input
            v-model="password"
            type="password"
            class="login_text_box login_text_box__pw"
            placeholder="비밀번호"
          />
          <a href="#" @click.prevent></a>
        </div>
        <div>
          <input type="button" class="pusan--login__btn" value="로그인" @click="login" />
        </div>
        <!--
        <div class="pusan--join">
          <h2>
            아이디가 아직 없으신가요?
            <a href="#">회원가입 바로가기</a>
          </h2>
        </div>
        -->
      </fieldset>
    </div>
  </section>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'home',
  data () {
    return {
      userNo: '',
      password: ''
    }
  },
  methods: {
    ...mapActions(['userLogin']),
    async login () {
      if (!this.userNo) {
        alert('학번을 입력해 주세요')
        return
      }

      if (!this.password) {
        alert('비밀번호를 입력해 주세요')
        return
      }

      try {
        await this.userLogin({
          userNo: this.userNo,
          password: this.password
        })

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
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../styles/scss/index.scss";

.loginform-container {
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
}
</style>
