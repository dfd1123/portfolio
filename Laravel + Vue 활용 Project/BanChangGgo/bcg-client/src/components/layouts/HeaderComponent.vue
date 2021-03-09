<template>
  <header
    class="page_title"
    :class="{ hdthemeylw : hdThemeYlw, noneshadow : hdThemeBoxshadow, nonebg : hdThemeNoneBg }"
  >
    <button
      v-if="privButton"
      type="button"
      class="page_title_btn btn-left"
      @click="$emit('privButtonClick')"
    >
      <i class="icon_prev"></i>
    </button>
    <h1>{{ hdTitle }}</h1>
    <transition name="slide-fade">
      <button
        type="button"
        v-if="modifyButton"
        class="page_title_btn btn-right"
        @click="$emit('modifyButtonClick')"
      >
        <i class="icon_del_word">{{ hdModify }}</i>
      </button>
    </transition>
    <button
      type="button"
      v-if="closeButton"
      class="page_title_btn btn-right"
      @click="$emit('closeButtonClick')"
    >
      <i class="icon_close"></i>
    </button>
  </header>
</template>

<script>
export default {
  name: 'header-component',
  props: {
    hdTitle: {
      type: String,
      default: ''
    },
    hdModify: {
      type: String,
      default: '수정'
    },
    privButton: {
      type: Boolean,
      default: true
    },
    modifyButton: {
      type: Boolean,
      default: false
    },
    closeButton: {
      type: Boolean,
      default: false
    },
    hdThemeYlw: {
      type: Boolean,
      default: false
    },
    hdThemeBoxshadow: {
      type: Boolean,
      default: false
    },
    hdThemeNoneBg: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../styles/scss/layouts/_header.scss";
</style>
