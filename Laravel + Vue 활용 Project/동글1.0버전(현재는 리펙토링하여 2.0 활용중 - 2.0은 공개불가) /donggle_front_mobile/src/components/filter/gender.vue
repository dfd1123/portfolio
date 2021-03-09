<template>
  <div class="in-section">
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="_gender_all"
        name="_gender"
        class="_input_choice display_none"
        value=""
        v-model="dataSex"
        @change="HandleInput"
      >
      <label
        for="_gender_all"
        class="rounded-btn-dark _input_chck"
      >전체</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="_gender_m"
        name="_gender"
        class="_input_choice display_none"
        value="man"
        v-model="dataSex"
        @change="HandleInput"
      >
      <label
        for="_gender_m"
        class="rounded-btn-dark _input_chck"
      >남자</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="_gender_fe"
        name="_gender"
        class="_input_choice display_none"
        value="woman"
        v-model="dataSex"
        @change="HandleInput"
      >
      <label
        for="_gender_fe"
        class="rounded-btn-dark _input_chck"
      >여자</label>
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
    watch: {
      gender () {
        this.dataSex = this.gender
      }
    },
    methods: {
      HandleInput () {
        this.$emit('input', this.dataSex)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
