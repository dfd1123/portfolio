<template>
  <select
    class="select-option"
    @change="$emit('option-change', index, $event.target.value)"
  >
    <option value="">
      [{{ optionSubject[index] }}]를 선택하세요
    </option>
    <option
      v-for="option in optionNm"
      :key="option"
      :value="option"
    >
      {{ option }}
    </option>
  </select>
</template>

<script>
  export default {
    props: {
      optionNm: {
        type: Array,
        required: true
      },
      optionSubject: {
        type: Array,
        required: true
      },
      index: {
        type: Number,
        required: true
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
