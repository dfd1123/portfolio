<template>
  <b-row class="h-100">
    <b-colxx xxs="12" md="10" class="mx-auto my-auto">
      <b-card class="auth-card">
        <b-card-body>
          <b-row>
            <b-colxx xxs="12" md="12" lg="6" class="my-auto mx-auto">
              <h6 class="mb-4">Change Password</h6>
              <b-form @submit.prevent="formSubmit">
                <label class="form-group has-float-label mb-4">
                  <input type="password" class="form-control" v-model="password" autocomplete="off" />
                  <span>변경할 비밀번호</span>
                </label>
                <label class="form-group has-float-label mb-4">
                  <input
                    type="password"
                    class="form-control"
                    v-model="passwordConfirm"
                    autocomplete="off"
                  />
                  <span>비밀번호 확인</span>
                </label>
                <b-alert v-if="alertText" show variant="danger">{{alertText}}</b-alert>
                <div class="d-flex justify-content-between align-items-center">
                  <b-button
                    type="button"
                    variant="info"
                    size="lg"
                    class="btn-shadow"
                    :disabled="processing"
                    @click.prevent="$router.push('/master/app/dashboards')"
                  >홈으로</b-button>
                  <b-button
                    type="submit"
                    variant="primary"
                    size="lg"
                    class="btn-shadow"
                    :disabled="processing"
                    @click="changePassword"
                  >변경하기</b-button>
                </div>
              </b-form>
            </b-colxx>
          </b-row>
        </b-card-body>
      </b-card>
    </b-colxx>
  </b-row>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  data () {
    return {
      password: '',
      passwordConfirm: '',
      alertText: ''
    }
  },
  computed: {
    ...mapGetters(['currentUser', 'processing', 'loginError'])
  },
  methods: {
    ...mapActions(['detailUpdate']),
    async formSubmit () {
      if (!this.password) {
        this.alertText = '비밀번호를 입력하세요'
        return
      }

      if (!this.passwordConfirm) {
        this.alertText = '비밀번호 확인을 입력하세요'
        return
      }

      if (this.password !== this.passwordConfirm) {
        this.alertText = '비밀번호와 비밀번호 확인이 일치하지 않습니다'
        return
      }

      this.alertText = ''
      await this.detailUpdate({ password: this.password })
      this.$router.push('/master/app/dashboards')
      this.$notify('success', '성공', '비밀번호가 변경되었습니다', { duration: 3000, permanent: false })
    },
    watch: {
      loginError (val) {
        if (val !== null) {
          this.$notify('error', val.error, val.message, { duration: 3000, permanent: false })
          this.password = ''
        }
      }
    }
  }
}
</script>
