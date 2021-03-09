<template>
  <header v-show="isVisible" id="pusan--hd" :class="$route.path === '/report'? '_opa-015' : ''">
    <div
      class="hdwrap clear"
      style="display: flex; z-index: 100;"
      :style="{'justify-content': isMenuVisible ?'start' : 'center'}"
    >
      <h1 @click="!isLandingPage ? $router.push('/home') : goToPage(); isOpen = false">부산대학교</h1>
      <div
        v-if="!isLandingPage"
        v-on-clickaway="away"
        class="pusan--gnbwrap"
        @click="isOpen = true"
      >
        <nav v-show="isMenuVisible" id="pusan--gnb" :class="{active: isOpen}">
          <div class="_menu_wrap">
            <ul>
              <li class="_menu _back_menu">
                <a href="#" @click.prevent.stop="backButton">Back</a>
              </li>
              <li class="_menu _icon_menu _menu1">
                <a href="#" @click.prevent.stop="$router.push('/home'); isOpen = false">홈으로</a>
              </li>
              <li class="_menu _icon_menu _menu2">
                <a
                  href="#"
                  @click.prevent.stop="$router.push('/competency-start'); isOpen = false"
                >평가하기</a>
              </li>
              <li class="_menu _icon_menu _menu3">
                <a href="#" @click.prevent.stop="$router.push('/report'); isOpen = false">결과보기</a>
              </li>
              <li class="_menu _logout_menu">
                <a href="#" @click.prevent.stop="logout">로그아웃</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </header>
</template>

<script>
import { mapActions } from 'vuex'
import { mixin as clickaway } from 'vue-clickaway'

export default {
  name: 'layout-header',
  mixins: [clickaway],
  data () {
    return {
      isOpen: false,
      isVisible: true,
      isMenuVisible: true
    }
  },
  created () {
    this.toggleHeader()
  },
  computed: {
    isLandingPage () {
      return this.$route.path === '/policy/privacy'
    }
  },
  watch: {
    $route () {
      this.toggleHeader()
    }
  },
  methods: {
    ...mapActions(['userLogout']),
    away () {
      if (this.isOpen) {
        this.isOpen = false
      }
    },
    toggleHeader () {
      this.isVisible = ![
        '/login',
        '/competency-intro',
        '/competency-qna',
        '/finish'
      ].includes(this.$route.path)

      this.isMenuVisible = ![
        '/competency-start'
      ].includes(this.$route.path)
    },
    backButton () {
      if (!this.$route.path.includes('/home')) {
        this.$router.go(-1)
      }

      this.isOpen = false
    },
    async logout () {
      await this.userLogout()

      window.location.replace('/client/login')
    },
    goToPage () {
      window.location = 'http://www.pusan.ac.kr/'
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../styles/scss/start";
@import "../../styles/scss/layout/responsive";
</style>
