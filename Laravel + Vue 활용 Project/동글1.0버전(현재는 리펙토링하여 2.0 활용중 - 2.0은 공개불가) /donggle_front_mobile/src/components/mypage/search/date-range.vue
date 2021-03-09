<template>
  <ul class="filter-search-list">
    <li
      v-for="(range, index) in dateRanges"
      :key="'range'+index"
    >
      <input
        type="radio"
        class="filter-date-input display_none"
        :id="range.key"
        :value="range.value"
        @change="DateRange"
        v-model="date_range"
      >
      <label
        :for="range.key"
        class="_search_date_btn"
      >
        {{ range.name }}
      </label>
    </li>
  </ul>
</template>

<script>
  export default {
    data: function () {
      return {
        date_range: this.dateRange
      }
    },
    props: {
      dateRanges: {
        type: Array,
        required: true
      },
      dateRange: {
        type: String,
        required: true
      }
    },
    watch: {
      dateRange () {
        this.date_range = this.dateRange
      }
    },
    methods: {
      DateRange () {
        this.$emit('input', this.date_range.toString())
        this.$emit('submit')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
