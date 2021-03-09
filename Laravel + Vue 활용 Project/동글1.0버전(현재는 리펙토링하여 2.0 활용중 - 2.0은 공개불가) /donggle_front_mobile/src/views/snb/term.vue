<template>
  <div style="padding: 20px 10px;">
    <h2 style="font-size:17px; margin-bottom:20px;">
      이용약관
    </h2>
    <div v-html="$store.state.company.use_terms">
    </div>
  </div>
</template>

<script>
  export default {

  }
</script>

<style lang="scss" scoped>
</style>
