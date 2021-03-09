<template>
  <ul class="in-sorting">
    <li
      v-for="(ods,index) in status"
      :key="'status'+index"
      class="_type"
    >
      <input
        type="radio"
        class="sort_radio_input display_none"
        :id="ods.key"
        :value="ods.value"
        @change="InputHandler"
        v-model="od_status"
      >
      <label
        :for="ods.key"
        class="word"
      >
        {{ ods.name }}
      </label>
    </li>
  </ul>
</template>

<script>
  export default {
    data: function () {
      return {
        od_status: this.odStatus
      }
    },
    props: {
      odStatus: {
        type: String,
        required: true
      },
      status: {
        type: Array,
        required: true
      }
    },
    methods: {
      InputHandler () {
        this.$emit('input', this.od_status)
        this.$emit('submit')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
