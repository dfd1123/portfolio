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
          this.$cookies.set('access_token', token)
        } else {
          this.$cookies.set('access_token', token)
        }
        this.$http.defaults.headers.common['Authorization'] = `Bearer ${token}`

        const user = (await this.$http.get(this.$APIURI + 'users/user_info')).data.query

        if (user) {
          await this.$store.commit('UserStoreInfor', user)
        }

        if (this.nativeToken) {
          this.NativeTokenPut()
        }
      }

      this.$store.commit('ProgressShow')
      this.$router.push('/')
    },
    beforeDestroy () {
      window.$EventBus.$off('native-login')
    },
    destroyed () {
      this.$store.commit('ProgressHide')
    }
  }
</script>

<style lang="scss" scoped>
</style>
