<template>
  <div class="dg-filter-search-list">
    <h3 class="in-label">
      나이
    </h3>
    <div class="in-section">
      <input
        type="checkbox"
        id="ageAll"
        class="filter-checkbox display_none"
        value=""
        v-model="dataAge"
        @change="AgeAll()"
      >
      <label
        for="ageAll"
        class="rounded-btn-dark "
      >
        전체
      </label>
      <input
        type="checkbox"
        id="tenRange"
        class="filter-checkbox display_none"
        value="10"
        v-model="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="tenRange"
        class="rounded-btn-dark "
      >
        10대
      </label>
      <input
        type="checkbox"
        id="twentyRange"
        class="filter-checkbox display_none"
        value="20"
        v-model="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="twentyRange"
        class="rounded-btn-dark"
      >
        20대
      </label>
      <input
        type="checkbox"
        id="thirtyRange"
        class="filter-checkbox display_none"
        value="30"
        v-model="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="thirtyRange"
        class="rounded-btn-dark"
      >
        30대
      </label>
      <input
        type="checkbox"
        id="fourtyRange"
        class="filter-checkbox display_none"
        value="40"
        v-model="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="fourtyRange"
        class="rounded-btn-dark"
      >
        40대
      </label>
      <input
        type="checkbox"
        id="fiftyRange"
        class="filter-checkbox display_none"
        value="50"
        v-model="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="fiftyRange"
        class="rounded-btn-dark"
      >
        50대 이상
      </label>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        dataAge: this.age
      }
    },
    props: {
      age: {
        type: Array,
        default: () => {
          return []
        },
        required: true
      }
    },
    mounted () {
      if (this.age.length === 0) {
        document.getElementById('ageAll').checked = true
      }
    },
    methods: {
      AgeAll () {
        this.dataAge = []
        this.$emit('input', this.dataAge)
        this.$emit('search-submit')
      },
      ChangeEvent () {
        document.getElementById('ageAll').checked = false
        this.$emit('input', this.dataAge)
        this.$emit('search-submit')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
