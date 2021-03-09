<template>
  <div class="choice_option _change_option">
    <div class="_name">
      <span>{{ OptionFullName() }}</span>
    </div>
    <div class="_amount clear_both">
      <div
        class="_minusbtn"
        @click="DownCnt"
      ></div>
      <input
        type="number"
        class="_number-input"
        maxlength="2"
        placeholder="1"
        v-model="qty"
        @input="ChangeQty"
      >
      <div
        class="_plusbtn"
        @click="UpCnt"
      ></div>
    </div>
    <span class="_total_price">{{ OptionTotalPrice }}</span>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        optionSub: this.option,
        qty: this.option.qty
      }
    },
    props: {
      option: {
        type: Object,
        required: true
      },
      optionSubject: {
        type: Array,
        required: true
      },
      itemList: {
        type: Object,
        required: true
      }
    },
    computed: {
      OptionTotalPrice () {
        const price = (this.itemList.price || 0 + this.option.price || 0) * Number(this.qty || 1)

        return this.NumberFormat(price) + '원'
      }
    },
    methods: {
      OptionFullName () {
        let optionFullName = ''
        const optionName = this.option.name.split(',')
        for (let i = 0; i < optionName.length; i++) {
          optionFullName += this.optionSubject[i] + ':' + optionName[i] + ', '
          if (i === optionName.length - 1) {
            optionFullName += '추가비용:' + this.NumberFormat(this.option.price) + '원'
          }
        }

        return optionFullName
      },
      UpCnt () {
        this.qty = Number(this.qty) + 1
        this.ChangeQty()
      },
      DownCnt () {
        if (Number(this.qty) > 1) {
          this.qty = Number(this.qty) - 1
          this.ChangeQty()
        }
      },
      ChangeQty () {
        if (this.qty === '0') {
          this.qty = 1
        }

        this.optionSub.qty = Number(this.qty)
        this.$emit('input', this.optionSub)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
