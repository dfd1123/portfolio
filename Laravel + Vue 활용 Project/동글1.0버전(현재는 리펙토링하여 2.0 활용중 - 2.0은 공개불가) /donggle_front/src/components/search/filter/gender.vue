<template>
  <div class="dg-filter-search-list">
    <h3 class="in-label">
      성별
    </h3>
    <div class="in-section">
      <input
        type="radio"
        id="sexAll"
        class="filter-checkbox display_none"
        value=""
        v-model="dataSex"
        @change="HandleInput"
      >
      <label
        for="sexAll"
        class="rounded-btn-dark"
      >
        전체
      </label>
      <input
        type="radio"
        id="woman"
        class="filter-checkbox display_none"
        value="woman"
        v-model="dataSex"
        @change="HandleInput"
      >
      <label
        for="woman"
        class="rounded-btn-dark"
      >
        여자
      </label>
      <input
        type="radio"
        id="man"
        class="filter-checkbox display_none"
        value="man"
        v-model="dataSex"
        @change="HandleInput"
      >
      <label
        for="man"
        class="rounded-btn-dark"
      >
        남자
      </label>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        dataSex: this.gender
      }
    },
    props: {
      gender: {
        type: String,
        default: '',
        required: true
      }
    },
    methods: {
      HandleInput () {
        this.$emit('input', this.dataSex)
        this.$emit('search-submit')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
