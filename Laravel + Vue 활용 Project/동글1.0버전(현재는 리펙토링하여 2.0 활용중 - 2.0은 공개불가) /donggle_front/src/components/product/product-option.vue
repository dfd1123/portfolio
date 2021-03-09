<template>
  <div>
    <!-- 옵션선택 -->
    <div class="choice_product">
      <h4>옵션선택</h4>
      <select @change="$emit('option-change', index, $event.target.value)">
        <option value>
          [{{ optionSubject[index] }}]를 선택하세요
        </option>
        <option
          v-for="option in optionNm"
          :key="option"
          :value="option"
        >
          {{ optionName }}
        </option>
      </select>
      <select
        class="select-option"
        @change="$emit('option-change', index, $event.target.value)"
      >
        <option value="">
          [{{ optionSubject[index] }}]를 선택하세요
        </option>
        <option
          v-for="option in optionNm"
          :key="option"
          :value="option"
        >
          {{ option }}
        </option>
      </select>
    </div>
    <!-- 옵션선택 E -->

    <!-- 선택한 옵션보기 -->
    <div class="choice_option">
      <div class="_name">
        <span>커플시계 구매 유무 : 아라빅2 여성시계추가 (15%씩 할인) + 122, 750 원, 시계앞면 각인 : 각인 + 10,000원</span>
      </div>
      <div class="_amount clear_both">
        <div class="_plusbtn"></div>
        <input
          type="number"
          class="_number-input"
          maxlength="2"
          placeholder="1"
        >
        <div class="_minusbtn"></div>
      </div>
      <span class="_total_price">37,800원</span>
      <div class="_close_btn"></div>
    </div>
    <!-- 선택한 옵션보기 E -->
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        optionSub: this.optionSubject,
        qty: 0
      }
    },
    props: {
      optionSubject: {
        type: Array,
        required: true
      },
      options: {
        type: Array,
        required: true
      }
    },
    methods: {
      OptionFullName () {
        let optionFullName = ''
        const optionName = this.option.ot_name.split(',')
        for (let i = 0; i < optionName.length; i++) {
          optionFullName += this.optionSubject[i] + ':' + optionName[i] + ', '
          if (i === optionName.length - 1) {
            optionFullName += '추가비용:' + this.NumberFormat(this.option.ot_price) + '원'
          }
        }

        return optionFullName
      },
      OptionTotalPrice () {
        const price = (this.qty * this.itemList.price) + this.option.ot_price

        return this.NumberFormat(price) + '원'
      },
      UpCnt () {
        this.qty = Number(this.qty) + 1
        this.ChangeQty()
      },
      DownCnt () {
        if (Number(this.qty) > 0) {
          this.qty = Number(this.qty) - 1
          this.ChangeQty()
        }
      },
      ChangeQty () {
        this.optionSub.qty = Number(this.qty)
        this.$emit('input', this.optionSub)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
