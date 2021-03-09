<template>
  <button
    type="button"
    class="btn-search-filter"
    :class="{actived: visible}"
    @click.stop="$emit('filter-button-click', $event)"
  >
    <span><span class="icon"></span>필터</span>
  </button>
</template>

<script>
  export default {
    props: {
      visible: {
        type: Boolean,
        default: false
      }
    }
  }
</script>

<style scoped>
</style>
