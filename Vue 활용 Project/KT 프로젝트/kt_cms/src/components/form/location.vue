<template>
  <div class="input_wrap">
    <p class="label-input">
      <input
        type="number"
        id="lolatit"
        name="lolatit"
        class="auto-width"
        ref="loLatit"
        :value="value.loLatit"
        @input="UpdateLocation()"
        :readonly="readonly"
        :disabled="readonly"
      >
      <span class="char">{{ afterChar }} </span>
    </p>
    <p class="label-input">
      <input
        type="number"
        id="lolngit"
        name="lolngit"
        class="auto-width"
        ref="loLngit"
        :value="value.loLngit"
        @input="UpdateLocation()"
        :readonly="readonly"
        :disabled="readonly"
      >
    </p>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        vData: []
      }
    },
    props: {
      afterChar: {
        type: String,
        required: true,
        default: ','
      },
      readonly: {
        type: Boolean,
        default: false
      },
      value: {
        type: Object,
        default: () => {
          return {
            loLatit: '',
            loLngit: ''
          }
        }
      }
    },
    methods: {
      UpdateLocation () {
        this.$emit('input', {
          loLatit: this.$refs.loLatit.value,
          loLngit: this.$refs.loLngit.value
        })
      }
    }
  }
</script>

<style scoped>
  .input_wrap{
    display:inline-block;
  }
</style>
