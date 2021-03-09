<template>
  <div class="dg-filter-search-list dg-filter-search-list--size">
    <h3 class="in-label">
      사이즈
    </h3>
    <div class="in-section">
      <vue-slider
        v-model="value"
        :enable-cross="false"
        dot-size="15"
        width="610px"
        height="4px"
        tooltip-placement="bottom"
        tooltip="always"
        :interval="10"
        :min="20"
        :max="150"
      />
      <div class="size-range-guide-bar">
        <span>20</span>
        <span>30</span>
        <span>40</span>
        <span>50</span>
        <span>60</span>
        <span>70</span>
        <span>80</span>
        <span class="turning">90</span>
        <span>100</span>
        <span>110</span>
        <span>120</span>
        <span>130</span>
        <span>140</span>
        <span>150</span>
      </div>
    </div>
    <input
      type="button"
      class="square-btn-outline"
      value="검색"
      style="position: absolute;top: 18px;right: 227px;"
      @click="SizeChange()"
    >
  </div>
</template>

<script>
  import VueSlider from 'vue-slider-component'
  import 'vue-slider-component/theme/default.css'
  export default {
    components: {
      VueSlider
    },
    data: function () {
      return {
        value: [this.minSize, this.maxSize],
        dataMinSize: this.minSize,
        dataMaxSize: this.maxSize
      }
    },
    props: {
      minSize: {
        type: Number,
        default: 20,
        required: true
      },
      maxSize: {
        type: Number,
        default: 150,
        required: true
      }
    },
    methods: {
      SizeChange () {
        console.log(typeof (this.value[0]))
        this.dataMinSize = Number(this.value[0])
        this.dataMaxSize = Number(this.value[1])

        this.$emit('input', this.dataMinSize, this.dataMaxSize)
        this.$emit('search-submit')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
