<template>
  <div
    class="input_wrap"
    style="white-space: nowrap"
  >
    <label
      v-if="labelName !== ''"
      :for="id"
    >{{ labelName }}</label>
    <input
      :type="type"
      :id="id"
      :name="id"
      :placeholder="placeHolder"
      :style="[{width: (button && !readonly) ? '70%' : width}, {height: height}]"
      :value="value"
      :readonly="readonly"
      :disabled="readonly"
      @input="updateData($event.target.value)"
      @keyup.enter="ExcuteFunction"
    >
    <span
      v-if="afterChar !== ''"
      class="char"
    >{{ afterChar }}</span>
    <a
      v-if="button && !readonly"
      href="javascript:;"
      class="btn-01 type-03 round"
      :style="{'position': 'initial', 'margin-left': '10px', width: 'calc(30% - 10px)'}"
      @click="inputButtonClick({$event, value})"
    >{{ button }}</a>
  </div>
</template>

<script>
  export default {
    props: {
      labelName: {
        type: String,
        default: ''
      },
      afterChar: {
        type: String,
        default: ''
      },
      height: {
        type: String,
        default: null
      },
      width: {
        type: String,
        default: 'auto'
      },
      placeHolder: {
        type: String,
        default: ''
      },
      type: {
        type: String,
        required: true
      },
      id: {
        type: String,
        required: true
      },
      readonly: {
        type: Boolean,
        default: false
      },
      value: {
        type: String,
        default: ''
      },
      button: {
        type: String,
        default: ''
      }
    },
    methods: {
      updateData (val) {
        this.$emit('input', val)
      },
      ExcuteFunction: function () {
        this.$emit('click-event')
      },
      inputButtonClick (val) {
        this.$emit('inputButtonClick', val)
      }
    }
  }
</script>

<style scoped>
  .input_wrap {
    display: inline-block;
  }
</style>
