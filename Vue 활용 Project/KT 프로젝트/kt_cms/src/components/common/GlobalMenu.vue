<template>
  <!-- global-menu -->
  <ul class="global-menu">
    <li class="cms-global-item-03">
      <a
        href="#"
        @click.prevent="service"
      ><span>변경</span></a>
    </li>
    <li class="cms-global-item-01">
      <a
        href="#"
        @click.prevent="account"
      ><span>{{ userType }}</span></a>
    </li>
    <li class="cms-global-item-02">
      <a
        href="#"
        @click.prevent="logout"
      ><span>로그아웃</span></a>
    </li>
  </ul>
  <!-- //global-menu -->
</template>

<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'GlobalMenu',
    computed: {
      userType () {
        if (!this.isLogin) {
          return '손님'
        }

        return {
          '01': '플랫폼관리자',
          '02': '고객관리자',
          '03': '서비스관리자',
          '04': '일반사용자'
        }[this.auth] || '알수없음'
      }
    },
    methods: {
      ...mapActions(['logoutUser']),
      async logout () {
        await this.logoutUser()

        window.location.replace('/login')
      },
      service () {
        if (!this.isLogin) {
          window.location.replace('/login')
        } else {
          this.$router.push('/select')
        }
      },
      account () {
        if (!this.isLogin) {
          window.location.replace('/login')
        } else {
          this.$router.push('/accountinfo')
        }
      }
    }
  }
</script>

<style lang="css" scoped>
</style>
