<template>
  <div>
    <h2 class="page-title">
      {{ title }}
    </h2>
    <p class="page-info">
      {{ info }}
    </p>
  </div>
</template>

<script>
  export default {
    props: {
      title: {
        type: String,
        default: ''
      },
      info: {
        type: String,
        default: ''
      }
    }
  }
</script>

<style scoped>
</style>
