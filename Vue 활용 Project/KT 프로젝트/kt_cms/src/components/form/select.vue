<template>
  <select
    :name="id"
    :id="id"
    @input="handleInput"
    :value="value || initial"
    :disabled="readonly"
  >
    <option
      v-for="option in options"
      :key="option.value"
      :value="option.value"
      :selected="option.value === value"
    >
      {{ option.name }}
    </option>
  </select>
</template>

<script>
  export default {
    props: {
      inputData: {
        type: Object,
        default () {
          return {}
        }
      },
      id: {
        type: String,
        required: true
      },
      options: {
        type: Array,
        default () {
          return []
        }
      },
      value: {
        type: String,
        default: ''
      },
      initial: {
        type: String,
        default: ''
      },
      readonly: {
        type: Boolean,
        default: false
      }
    },
    created () {
      if (this.inputData.onChange) {
        this.inputData.onChange(this.value || null)
      }
    },
    watch: {
      value () {
        if (this.inputData.onChange) {
          this.inputData.onChange(this.value)
        }
      }
    },
    methods: {
      handleInput: function (e) {
        this.$emit('input', e.target.value)
        if (this.inputData.onChange) {
          this.inputData.onChange(e.target.value)
        }
      }
    }
  }
</script>

<style scoped>
</style>
