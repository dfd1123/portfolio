<template>
  <b-row class="h-100">
    <b-colxx xxs="12" md="10" class="mx-auto my-auto">
      <b-card class="auth-card" no-body>
        <div class="position-relative image-side">
          <p class="text-white h2">역량평가 관리시스템</p>
          <p class="white mb-0">로그인이 필요합니다</p>
        </div>
        <div class="form-side">
          <router-link tag="a" to="/">
            <span class="logo-single" style="background: none;">
              <img src="/master/assets/img/header_logo.svg" />
            </span>
          </router-link>
          <h6 class="mb-4">{{ $t('user.login-title')}}</h6>
          <b-form @submit.prevent="formSubmit">
            <label class="form-group has-float-label mb-4">
              <input type="text" class="form-control" v-model="email" />
              <span>{{ $t('user.email') }}</span>
            </label>
            <label class="form-group has-float-label mb-4">
              <input type="password" class="form-control" v-model="password" />
              <span>{{ $t('user.password') }}</span>
            </label>
            <b-alert v-if="alertText" show variant="danger">{{alertText}}</b-alert>
            <div class="d-flex justify-content-between align-items-center">
              <a />
              <b-button
                type="submit"
                variant="primary"
                size="lg"
                class="btn-shadow"
                :disabled="processing"
              >{{ $t('user.login-button')}}</b-button>
            </div>
          </b-form>
        </div>
      </b-card>
    </b-colxx>
  </b-row>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  data () {
    return {
      email: '',
      password: '',
      alertText: ''
    }
  },
  computed: {
    ...mapGetters(['currentUser', 'processing', 'loginError'])
  },
  methods: {
    ...mapActions(['login']),
    formSubmit () {
      if (!this.email) {
        this.alertText = '이메일을 입력하세요'
        return
      }

      if (!this.password) {
        this.alertText = '비밀번호를 입력하세요'
        return
      }

      this.alertText = ''
      this.login({ email: this.email, password: this.password })
    }
  },
  watch: {
    currentUser (val) {
      if (val !== null) {
        this.$router.push('/')
      }
    },
    loginError (val) {
      if (val !== null) {
        this.$notify('error', val.error, val.message, { duration: 3000, permanent: false })
        this.password = ''
      }
    }
  }
}
</script>
