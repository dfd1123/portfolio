<template>
  <div class="login-wrap">
    <div class="d-t">
      <div class="d-c">
        <div class="input-data">
          <div class="input-inner">
            <h1>KT 5G AR Makers</h1>
            <p class="input-cell">
              <input
                type="text"
                name=""
                v-model="username"
                placeholder="ID"
              >
            </p>
            <p class="input-cell">
              <input
                type="password"
                name=""
                v-model="password"
                placeholder="PASSWORD"
              >
            </p>
            <!--
            <div class="check-save">
              <p class="check-type01">
                <input
                  type="checkbox"
                  id="save"
                  name="save"
                ><label for="save"><span></span>자동 로그인</label>
              </p>
            </div>
            -->
            <p class="btn-login">
              <a
                href="#"
                @click.prevent="login"
              >로그인</a>
            </p>
            <!--
            <p class="btn-find">
              <a
                href="#"
                @click.prevent="findPassword"
              >비밀번호 찾기</a>
            </p>
            -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters, mapActions } from 'vuex'

  export default {
    name: 'Login',
    data () {
      return {
        username: '',
        password: ''
      }
    },
    computed: {
      ...mapGetters(['isLogin'])
    },
    methods: {
      ...mapActions(['loginUser']),
      async login () {
        if (!this.username) {
          alert('아이디를 입력해 주세요')
          return
        }

        if (!this.password) {
          alert('비밀번호를 입력해 주세요')
          return
        }

        try {
          this.$store.commit('progressComponentShow')

          await this.loginUser({
            username: this.username,
            password: this.password
          })

          if (['03', '04'].includes(this.auth)) {
            this.$router.push('/select')
          } else {
            this.$router.push('/')
          }
        } catch (e) {
          if (e.response && e.response.status === 401) {
            alert('아이디나 비밀번호가 잘못되었습니다')
          } else {
            alert(e.response ? e.response.data.message : e)
          }
        } finally {
          this.$store.commit('progressComponentHide')
        }
      },
      findPassword () {
        alert('비밀번호 찾기 준비중입니다')
      }
    }
  }
</script>

<style scoped>
</style>
