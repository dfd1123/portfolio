<template>
  <div :class="className">
    <button
      v-if="kind === 'button'"
      type="button"
      class="btn-01"
      :class="btnType"
      @click="excute_function"
      @keyup.enter="excute_function"
    >
      <span>{{ btnName }}</span>
    </button>
    <router-link
      v-if="kind === 'a'"
      class="btn-01"
      :class="btnType"
      :to="SrchParams"
    >
      {{ btnName }}
    </router-link>
  </div>
</template>

<script>
  export default {
    props: {
      className: {
        type: String,
        default: ''
      },
      kind: {
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
      to: {
        type: String,
        default: ''
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
