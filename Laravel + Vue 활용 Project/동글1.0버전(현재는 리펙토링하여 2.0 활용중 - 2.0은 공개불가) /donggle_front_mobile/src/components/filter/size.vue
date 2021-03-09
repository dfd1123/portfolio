<template>
  <div class="in-section">
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_10"
        class="_input_choice display_none"
        value="10,20"
        :checked="minSize <= 10 && maxSize >= 20"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_10"
        class="rounded-btn-dark _input_chck"
      >10~20</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_20"
        class="_input_choice display_none"
        value="20,30"
        :checked="minSize <= 20 && maxSize >= 30"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_20"
        class="rounded-btn-dark _input_chck"
      >20~30</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_30"
        class="_input_choice display_none"
        value="30,40"
        :checked="minSize <= 30 && maxSize >= 40"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_30"
        class="rounded-btn-dark _input_chck"
      >30~40</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_40"
        class="_input_choice display_none"
        value="40,50"
        :checked="minSize <= 40 && maxSize >= 50"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_40"
        class="rounded-btn-dark _input_chck"
      >40~50</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_50"
        class="_input_choice display_none"
        value="50,60"
        :checked="minSize <= 50 && maxSize >= 60"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_50"
        class="rounded-btn-dark _input_chck"
      >50~60</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_60"
        class="_input_choice display_none"
        value="60,70"
        :checked="minSize <= 60 && maxSize >= 70"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_60"
        class="rounded-btn-dark _input_chck"
      >60~70</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_70"
        class="_input_choice display_none"
        value="70,80"
        :checked="minSize <= 70 && maxSize >= 80"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_70"
        class="rounded-btn-dark _input_chck"
      >70~80</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_80"
        class="_input_choice display_none"
        value="80,90"
        :checked="minSize <= 80 && maxSize >= 90"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_80"
        class="rounded-btn-dark _input_chck"
      >80~90</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_90"
        class="_input_choice display_none"
        value="90,100"
        :checked="minSize <= 90 && maxSize >= 100"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_90"
        class="rounded-btn-dark _input_chck"
      >90~100</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_100"
        class="_input_choice display_none"
        value="100,110"
        :checked="minSize <= 100 && maxSize >= 110"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_100"
        class="rounded-btn-dark _input_chck"
      >100~110</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="size_110"
        class="_input_choice display_none"
        value="110,150"
        :checked="minSize <= 110 && maxSize >= 150"
        @change="ChangeSize($event.target)"
      >
      <label
        for="size_110"
        class="rounded-btn-dark _input_chck"
      >110 이상</label>
    </div>
  </div>
</template>

<script>
  export default {
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
        default: 10,
        required: true
      },
      maxSize: {
        type: Number,
        default: 150,
        required: true
      }
    },
    methods: {
      ChangeSize (target) {
        // const arr = ['10,20', '20,30', '30,40', '40,50', '50,60', '60,70', '70,80', '80,90', '90,100', '100,110', '110,150']
        const tempArr = target.value.split(',')
        target.checked = true
        /*
        let min = this.minStr.split(',')
        min = Number(min[0])
        let max = this.maxStr.split(',')
        max = Number(max[1])
*/
        if (Number(tempArr[0]) < this.dataMaxSize || Number(tempArr[0]) < this.dataMinSize) {
          this.dataMinSize = Number(tempArr[0])
        }

        if (Number(tempArr[1]) > this.dataMaxSize || Number(tempArr[1]) > this.dataMinSize) {
          this.dataMaxSize = Number(tempArr[1])
        }

        this.$emit('input', this.dataMinSize, this.dataMaxSize)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
