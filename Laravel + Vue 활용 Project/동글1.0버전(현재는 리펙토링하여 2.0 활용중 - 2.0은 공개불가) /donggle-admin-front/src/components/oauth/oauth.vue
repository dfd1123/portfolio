<template>
  <div>

  </div>
</template>

<script>
export default {
  async beforeCreate () {
    if (this.$route.query.register_type === 'already') {
      alert('이미 가입된 이메일 주소입니다.')
      this.$router.push('/login')
    }

    if (!this.$route.query.access_token) {
      alert('잘못된 접근입니다!')
      this.$router.go(-2)
    }
  },
  async created () {
    window.$EventBus.$on('native-login', this.SetToken)
    await window.callToken()

    if (this.$route.query.access_token) {
      const token = this.$route.query.access_token
      if (process.env.VUE_APP_ENV === 'LOCAL') {
        this.$cookies.set('DonggleAcessToken', token)
      } else {
        this.$cookies.set('DonggleAcessToken', token)
      }

      let data = await this.$axios
        .get('/api/users/user_info')
        .then(response => response.data)
      let user = {}
      if (data.state === 1) {
        user = data.query
      }

      if (user.register_kind === 1) {
        data = await this.$axios
          .put('/api/users/store_join_req')
          .then(response => response.data)

        if (data.state === 1) {
          user = data.query
        }
      }

      if (this.nativeToken) {
        this.NativeTokenPut()
      }
    }

    // this.$store.commit('ProgressShow')
    await this.$store.dispatch('getUser')
    await this.$store.dispatch('getStore')
    this.$cookies.set('isConfirm', this.store.confirm === 1)
    this.$router.push('/')
  },
  beforeDestroy () {
    window.$EventBus.$off('native-login')
  },
  destroyed () {
    // this.$store.commit('ProgressHide')
  }
}
</script>

<style lang="scss" scoped>
</style>
