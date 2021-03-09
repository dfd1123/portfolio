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
      }
    },
    created () {
      this.$store.commit('ProgressShow')

      if (this.CheckDevice() === 'mobile') {
        if (process.env.VUE_APP_ENV === 'LOCAL') {
          location.href = 'http://localhost:8081/oauth/login?email=' + this.$route.query.email + '&access_token=' + this.$route.query.access_token
        } else {
          location.href = 'https://m.dong-gle.co.kr/oauth/login?email=' + this.$route.query.email + '&access_token=' + this.$route.query.access_token
        }
      } else {
        this.$router.push('/')
      }
    },
    destroyed () {
      this.$store.commit('ProgressHide')
    },
    methods: {
      CheckDevice () {
        let ua = window.navigator.userAgent

        ua = ua.toLowerCase()
        var platform = {}
        var matched = {}
        var userPlatform = 'pc'
        var platformMatch = /(ipad)/.exec(ua) || /(ipod)/.exec(ua) ||
          /(windows phone)/.exec(ua) || /(iphone)/.exec(ua) ||
          /(kindle)/.exec(ua) || /(silk)/.exec(ua) || /(android)/.exec(ua) ||
          /(win)/.exec(ua) || /(mac)/.exec(ua) || /(linux)/.exec(ua) ||
          /(cros)/.exec(ua) || /(playbook)/.exec(ua) ||
          /(bb)/.exec(ua) || /(blackberry)/.exec(ua) ||
          []

        matched.platform = platformMatch[0] || ''

        if (matched.platform) {
          platform[matched.platform] = true
        }

        if (platform.android || platform.bb || platform.blackberry ||
          platform.ipad || platform.iphone ||
          platform.ipod || platform.kindle ||
          platform.playbook || platform.silk ||
          platform['windows phone']) {
          userPlatform = 'mobile'
        }

        if (platform.cros || platform.mac || platform.linux || platform.win) {
          userPlatform = 'pc'
        }

        return userPlatform
      }
    }
  }
</script>

<style>
</style>
