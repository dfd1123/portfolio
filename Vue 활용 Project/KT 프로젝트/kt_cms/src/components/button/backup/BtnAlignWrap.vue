<template>
  <div
    v-if="align === 'left'"
    class="btn-page-wrap al-l"
  >
  </div>
  <div
    v-else-if="align === 'right'"
    class="btn-page-wrap al-r"
  >
  </div>
  <div
    v-else-if="align === 'center'"
    class="btn-page-wrap al-c"
  >
  </div>
</template>

<script>
  export default {

  }
</script>

<style scoped>
</style>
