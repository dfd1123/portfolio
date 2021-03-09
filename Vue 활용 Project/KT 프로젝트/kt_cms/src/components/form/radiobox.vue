<template>
  <p class="label-radio">
    <input
      type="radio"
      :name="id"
      :id="id"
      :value="radioValue"
      @input="updateDate($event.target.value)"
    ><label :for="id">{{ radioboxLabel }}</label>
  </p>
</template>

<script>

  export default {
    props: {
      id: {
        type: String,
        required: true
      },
      radioboxLabel: {
        type: String,
        required: true
      },
      radioValue: {
        type: String,
        required: true
      }
    },
    methods: {
      updateDate (val) {
        this.$emit('input', val)
      }
    }
  }
</script>

<style scoped>
  .label-radio input[type=radio] + label{
    margin: 0 2px;
  }
</style>
