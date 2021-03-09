<template>
  <div :class="statusBarClasses">
    <span :class="wordResultClasses">
      총
      <b>{{count === null ? '-' : Number(count).toLocaleString()}}</b>건의 내역이 검색되었습니다.
    </span>
  </div>
</template>

<script>
export default {
  name: 'TableStatusBar',
  props: {
    count: {
      type: null,
      default: null
    },
    statusBarClasses: {
      type: Array,
      default: () => ['search-status-bar']
    },
    wordResultClasses: {
      type: Array,
      default: () => ['in-word-result']
    }
  }
}
</script>

<style>
</style>
