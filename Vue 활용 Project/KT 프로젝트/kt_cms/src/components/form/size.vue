<template>
  <div class="input_wrap">
    <p class="label-input">
      <input
        type="number"
        id="horizontal"
        name="horizontal"
        class="auto-width"
        ref="horizontal"
        :value="value.horizontal"
        @input="UpdateSize()"
        :readonly="readonly"
        :disabled="readonly"
      >
      <span class="char">{{ afterChar }}, </span>
    </p>
    <p class="label-input">
      <input
        type="number"
        id="vertical"
        name="vertical"
        class="auto-width"
        ref="vertical"
        :value="value.vertical"
        @input="UpdateSize()"
        :readonly="readonly"
        :disabled="readonly"
      >
      <span class="char">{{ afterChar }} </span>
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
            horizontal: '',
            vertical: ''
          }
        }
      }
    },
    methods: {
      UpdateSize () {
        this.$emit('input', {
          horizontal: this.$refs.horizontal.value,
          vertical: this.$refs.vertical.value
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
