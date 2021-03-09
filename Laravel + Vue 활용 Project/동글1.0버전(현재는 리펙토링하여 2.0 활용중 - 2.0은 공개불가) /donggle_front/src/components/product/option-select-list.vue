<template>
  <div class="choice_option">
    <div class="_name">
      <span>{{ OptionFullName }}</span>
    </div>
    <div class="_amount clear_both">
      <div
        class="_minusbtn"
        @click="DownCnt"
      ></div>
      <input
        type="number"
        class="_number-input"
        min="1"
        maxlength="2"
        placeholder="1"
        :value="$store.state.selectOption[index].qty"
        @input="ChangeQty($event.target.value)"
      >
      <div
        class="_plusbtn"
        @click="UpCnt"
      ></div>
    </div>
    <span class="_total_price">{{ NumberFormat(price) }} 원</span>
    <div
      class="_close_btn"
      @click="DeleteList"
    ></div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        optionSub: this.option
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
      },
      index: {
        type: Number,
        required: true
      },
      price: {
        type: Number,
        required: true
      },
      qty: {
        type: Number,
        required: true
      }
    },
    computed: {
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
      }
    },
    methods: {
      UpCnt () {
        let qty = this.qty + 1
        this.ChangeQty(qty)
      },
      DownCnt () {
        if (this.qty > 1) {
          let qty = this.qty - 1
          this.ChangeQty(qty)
        }
      },
      ChangeQty (value) {
        let qty = Number(value)

        if (qty < 1) {
          this.optionSub.qty = 1
        } else {
          this.optionSub.qty = qty
        }
        this.$emit('input', this.optionSub)
      },
      DeleteList () {
        this.$emit('delete-option')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
