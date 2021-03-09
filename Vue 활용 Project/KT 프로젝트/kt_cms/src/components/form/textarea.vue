<template>
  <div>
    <textarea
      :name="id"
      :id="id"
      :value="value"
      @input="$emit('input', $event.target.value)"
      :style="'width:' + width +'; height:' + height + ';'"
      :readonly="readonly"
    ></textarea>
  </div>
</template>

<script>
  export default {
    props: {
      id: {
        type: String,
        required: true,
        default: ''
      },
      width: {
        type: String,
        default: ''
      },
      height: {
        type: String,
        default: ''
      },
      value: {
        type: String,
        default: ''
      },
      readonly: {
        type: Boolean,
        default: false
      }
    }
  }
</script>

<style scoped>

</style>
