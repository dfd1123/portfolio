<template>
  <button class="btn-01 type-03 btn-excel">
    <span
      v-if="kind === 'upload'"
      class="icon-excel-upload"
    >엑셀 업로드</span>
    <span
      v-else-if="kind === 'download'"
      class="icon-excel-download"
    >엑셀 다운로드</span>
  </button>
</template>

<script>
  export default {
    props: {
      kind: {
        type: String,
        required: true
      }
    }
  }
</script>

<style scoped>
</style>
