<template>
  <div class="h-100">
    <router-view />
    <color-switcher />
  </div>
</template>

<script>
import ColorSwitcher from './components/Common/ColorSwitcher'
import {
  getDirection
} from './utils'

export default {
  components: {
    'color-switcher': ColorSwitcher
  },
  beforeMount () {
    const direction = getDirection()
    if (direction.isRtl) {
      document.body.classList.add('rtl')
      document.dir = 'rtl'
      document.body.classList.remove('ltr')
    } else {
      document.body.classList.add('ltr')
      document.dir = 'ltr'
      document.body.classList.remove('rtl')
    }
  },
  watch: {
    '$store.getters.processing': {
      handler () {
        if (this.$store.getters.processing) {
          document.body.style.opacity = 0.8
          document.getElementById('loading-overlay').style.display = ''
          document.getElementById('loading').style.display = ''
        } else {
          document.body.style.opacity = 1
          document.getElementById('loading-overlay').style.display = 'none'
          document.getElementById('loading').style.display = 'none'
        }
      }
    }
  }
}
</script>
