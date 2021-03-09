<template>
  <router-link
    class="btn-01"
    :class="{btnType, 'squre-round': squreRound}"
    :to="SrchParams"
  >
    {{ btnName }}
  </router-link>
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
    },
    computed: {
      SrchParams: function () {
        let str = []
        const obj = this.$route.query
        for (var p in obj) {
          if (obj.hasOwnProperty(p)) {
            if (obj[p] !== null) {
              str.push(encodeURIComponent(p) + '=' + encodeURIComponent(obj[p]))
            } else {
              str.push(encodeURIComponent(p))
            }
          }
        }

        str = str.toString().replace(/,/gi, '&')
        // str = str.replace('')
        return this.to + '?' + str
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
