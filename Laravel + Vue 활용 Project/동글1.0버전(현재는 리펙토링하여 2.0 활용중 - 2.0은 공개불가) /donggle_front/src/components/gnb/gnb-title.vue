<template>
  <!-- * 대메뉴 제목 -->
  <div class="dg-gnb-title-group">
    <h2 class="in-title">
      {{ gnbTitle }}
    </h2>
    <span class="in-text">{{ gnbText }}</span>
  </div>
  <!-- * 대메뉴 제목 E -->
</template>

<script>
  export default {
    props: {
      gnbTitle: {
        type: String,
        required: true
      },
      gnbText: {
        type: String,
        required: true
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
