<template>
  <button
    type="button"
    class="rounded-square-xs-btn btn-theme icon-search-btn margin-left-5px"
    @click="$emit('click')"
  >
    <i class="icon-search">
      <img src="/images/icon/icon-search.svg" alt />
    </i>
  </button>
</template>

<script>
export default {
  name: 'TableFilterButton'
}
</script>

<style lang="scss" scoped>
.margin-left-5px {
  margin-left: 5px;
}
</style>
