<template>
  <div class="mypage-btn-wrap">
    <div class="clear_both">
      <div
        class="mypage-btn-input-box"
        v-for="(ods,index) in status"
        :key="'status'+index"
      >
        <input
          type="radio"
          class="display_none"
          :id="ods.key"
          :value="ods.value"
          @change="InputHandler"
          v-model="od_status"
        >
        <label
          :for="ods.key"
          class="mypage-btn"
        >{{ ods.name }}</label>
      </div>
    </div>
  </div>
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
