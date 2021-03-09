<template>
  <div class="in-section">
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="priceAll"
        name="filter-price"
        class="_input_choice display_none"
        value=","
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="priceAll"
        class="rounded-btn-dark _input_chck"
      >전체</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="filter-price_5"
        name="filter-price"
        class="_input_choice display_none"
        value="0,5000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="filter-price_5"
        class="rounded-btn-dark _input_chck"
      >5,000원 이하</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="filter-price_10"
        name="filter-price"
        class="_input_choice display_none"
        value="5000,10000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="filter-price_10"
        class="rounded-btn-dark _input_chck"
      >5,000원 ~ 10,000원</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="filter-price_20"
        name="filter-price"
        class="_input_choice display_none"
        value="10000,20000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="filter-price_20"
        class="rounded-btn-dark _input_chck"
      >10,000원 ~ 20,000원</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="filter-price_40"
        name="filter-price"
        class="_input_choice display_none"
        value="20000,40000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="filter-price_40"
        class="rounded-btn-dark _input_chck"
      >20,000원 ~ 40,000원</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="filter-price_60"
        name="filter-price"
        class="_input_choice display_none"
        value="40000,60000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="filter-price_60"
        class="rounded-btn-dark _input_chck"
      >40,000원 ~ 60,000원</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="filter-price_80"
        name="filter-price"
        class="_input_choice display_none"
        value="60000,80000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="filter-price_80"
        class="rounded-btn-dark _input_chck"
      >60,000원 ~ 80,000원</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="radio"
        id="filter-price_100"
        name="filter-price"
        class="_input_choice display_none"
        value="100000,"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="filter-price_100"
        class="rounded-btn-dark _input_chck"
      >100,000원 이상</label>
    </div>
    <div class="_price_range">
      <p class="in-title">
        직접입력
      </p>
      <span class="_start"><input
        type="text"
        class="init-input"
        v-model="min"
        @input="InputPrice()"
      >원</span>
      <span class="_end"><input
        type="text"
        class="init-input"
        v-model="max"
        @input="InputPrice()"
        @change="Validation()"
      >원</span>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        price: (this.minPrice || '') + ',' + (this.maxPrice || ''),
        min: this.minPrice || '',
        max: this.maxPrice || ''
      }
    },
    props: {
      minPrice: {
        type: Number,
        default: null
      },
      maxPrice: {
        type: Number,
        default: null
      }
    },
    watch: {
      minPrice () {
        this.min = this.minPrice || ''
        if (this.min === '' && this.max === '') {
          this.price = ','
        }
      },
      maxPrice () {
        this.max = this.maxPrice || ''
        if (this.min === '' && this.max === '') {
          this.price = ','
        }
      }
    },
    methods: {
      ChangePrice () {
        const price = this.price.split(',')
        this.min = this.NumberFormat(price[0])
        this.max = this.NumberFormat(price[1])
        this.$emit('input', price)
      },
      InputPrice () {
        this.price = this.min.replace(/,/gi, '') + ',' + this.max.replace(/,/gi, '')
        const price = this.price.split(',')
        this.$emit('input', price)
        this.min = this.NumberFormat(this.min.replace(/,/gi, ''))
        this.max = this.NumberFormat(this.max.replace(/,/gi, ''))
      },
      Validation () {
        if (Number(this.min.replace(/,/gi, '')) > Number(this.max.replace(/,/gi, ''))) {
          this.max = '0'
          this.min = '0'
          this.InputPrice()
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
