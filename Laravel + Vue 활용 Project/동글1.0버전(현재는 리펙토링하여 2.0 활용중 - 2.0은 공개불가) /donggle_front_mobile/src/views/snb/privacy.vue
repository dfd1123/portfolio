<template>
  <div style="padding: 20px 10px;">
    <h2 style="font-size:17px; margin-bottom:20px;">
      개인정보처리방침
    </h2>
    <div v-html="$store.state.company.service_policy">
    </div>
  </div>
</template>

<script>
  export default {

  }
</script>

<style lang="scss" scoped>
</style>
