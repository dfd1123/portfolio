<template>
  <button
    type="button"
    class="btn-01"
    :class="[squreRound?'squre-round':'', btnType]"
    @click="excute_function"
  >
    {{ btnName }}
  </button>
</template>

<script>
  export default {
    props: {
      className: {
        type: String,
        default: ''
      },
      btnType: {
        type: String,
        default: ''
      },
      btnName: {
        type: String,
        default: ''
      },
      squreRound: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      excute_function: function () {
        this.$emit('click-event')
      }
    }
  }
</script>

<style lang="css" scoped>
</style>
