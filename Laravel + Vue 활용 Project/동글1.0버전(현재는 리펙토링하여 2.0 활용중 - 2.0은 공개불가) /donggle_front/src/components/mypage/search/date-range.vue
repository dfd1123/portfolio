<template>
  <div class="_btns_group">
    <div
      class="date-range-wrap"
      v-for="(range, index) in dateRanges"
      :key="'range'+index"
    >
      <input
        type="radio"
        :id="range.key"
        class="date-change-input display_none"
        :value="range.value"
        @change="DateRange"
        v-model="date_range"
      >
      <label
        :for="range.key"
        class="square-btn-outline"
      >
        {{ range.name }}
      </label>
    </div>
  </div>
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
  label.square-btn-outline {
    padding-top: 10px;
  }
  ._btns_group .square-btn-outline {
    min-width: 80px;
  }
  .date-change-input:checked {
    background-color: #333;
    color: #fff;
  }
</style>
