<template>
  <div
    id="infinite-scroll-detector"
    v-infinite-scroll="globalInfiniteScrollEvent"
    :infinite-scroll-immediate-check="false"
  >
    <div
      v-show="progressShow"
      class="progress_wrap"
      :class="[{'active':progressShow, 'cover': isAnyPopupVisible}]"
    >
      <div class="progress_con">
        <loading />
      </div>
    </div>
    <router-view />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import isVisible from 'is-element-visible'

export default {
  name: 'App',
  data () {
    return {
      isAnyPopupVisible: false,
      lastTouchEnd: 0
    }
  },
  created () {
    document.documentElement.addEventListener('touchstart', function (event) {
      if (event.touches.length > 1) {
        event.preventDefault()
      }
    }, false)
  },
  computed: {
    ...mapGetters(['progressShow'])
  },
  watch: {
    progressShow () {
      const popups = Array.from(document.getElementsByClassName('popup-container bgc-none'))
      if (popups.length > 0) {
        if (popups.some(elem => isVisible(elem))) {
          this.isAnyPopupVisible = true
          return
        }
      }

      this.isAnyPopupVisible = false
    }
  },
  methods: {
    ...mapActions([
      'getStore',
      'getNotification'
    ]),
    globalInfiniteScrollEvent () {
      // this.$bus.$emit('on-global-infinite-scroll')
    }
  }
}
</script>

<style lang="scss">
  @import "~@/assets/scss/dg-admin-ui.scss";

  .progress_wrap.active.cover {
    z-index: 1000;
  }
</style>
