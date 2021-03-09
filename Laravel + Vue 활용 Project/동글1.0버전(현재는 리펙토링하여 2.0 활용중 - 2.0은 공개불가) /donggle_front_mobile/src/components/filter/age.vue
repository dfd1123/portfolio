<template>
  <div class="in-section">
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="ageAll"
        class="_input_choice display_none"
        value=""
        v-model="dataAge"
        @change="AgeAll()"
      >
      <label
        for="ageAll"
        class="rounded-btn-dark _input_chck"
      >전체</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="age_10"
        class="_input_choice display_none"
        value="10"
        v-model="dataAge"
        :checked="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="age_10"
        class="rounded-btn-dark _input_chck"
      >10대</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="age_20"
        class="_input_choice display_none"
        value="20"
        v-model="dataAge"
        :checked="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="age_20"
        class="rounded-btn-dark _input_chck"
      >20대</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="age_30"
        class="_input_choice display_none"
        value="30"
        v-model="dataAge"
        :checked="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="age_30"
        class="rounded-btn-dark _input_chck"
      >30대</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="age_40"
        class="_input_choice display_none"
        value="40"
        v-model="dataAge"
        :checked="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="age_40"
        class="rounded-btn-dark _input_chck"
      >40대</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="age_50"
        class="_input_choice display_none"
        value="50"
        v-model="dataAge"
        :checked="dataAge"
        @change="ChangeEvent()"
      >
      <label
        for="age_50"
        class="rounded-btn-dark _input_chck"
      >50대 이상</label>
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
    watch: {
      age () {
        this.dataAge = this.age
        if (this.dataAge.length === 0) {
          document.getElementById('ageAll').checked = true
        }
      }
    },
    methods: {
      AgeAll () {
        this.dataAge = []
        this.$emit('input', this.dataAge)
      },
      ChangeEvent () {
        document.getElementById('ageAll').checked = false
        this.$emit('input', this.dataAge)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
