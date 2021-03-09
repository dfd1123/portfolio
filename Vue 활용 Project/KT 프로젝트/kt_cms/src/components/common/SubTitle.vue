<template>
  <div>
    <h3 class="sub-title">
      {{ title }}
    </h3>
  </div>
</template>

<script>
  export default {
    props: {
      title: {
        type: String,
        default: ''
      }
    }
  }
</script>

<style scoped>

</style>
