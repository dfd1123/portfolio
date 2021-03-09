<template>
  <div class="dg-filter-search-list">
    <h3 class="in-label">
      가격대
    </h3>
    <div class="in-section">
      <input
        type="radio"
        id="priceAll"
        class="filter-checkbox display_none"
        name="priceRange"
        value=","
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="priceAll"
        class="rounded-btn-dark"
      >
        전체
      </label>
      <input
        type="radio"
        id="fiveTh"
        class="filter-checkbox display_none"
        name="priceRange"
        value="0,5000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="fiveTh"
        class="rounded-btn-dark"
      >
        5,000원 이하
      </label>
      <input
        type="radio"
        id="tenTh"
        class="filter-checkbox display_none"
        name="priceRange"
        value="5000,10000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="tenTh"
        class="rounded-btn-dark"
      >
        5,000원 ~ 10,000원
      </label>
      <input
        type="radio"
        id="twentyTh"
        class="filter-checkbox display_none"
        name="priceRange"
        value="10000,20000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="twentyTh"
        class="rounded-btn-dark"
      >
        10,000원 ~ 20,000원
      </label>
      <input
        type="radio"
        id="fourtyTh"
        class="filter-checkbox display_none"
        name="priceRange"
        value="20000,40000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="fourtyTh"
        class="rounded-btn-dark"
      >
        20,000원 ~ 40,000원
      </label>
      <input
        type="radio"
        id="sixtyTh"
        class="filter-checkbox display_none"
        name="priceRange"
        value="40000,60000"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="sixtyTh"
        class="rounded-btn-dark"
      >
        40,000원 ~ 60,000원
      </label>
      <input
        type="radio"
        id="onehundTh"
        class="filter-checkbox display_none"
        name="priceRange"
        value="100000,"
        v-model="price"
        @change="ChangePrice"
      >
      <label
        for="onehundTh"
        class="rounded-btn-dark"
      >
        100,000원 이상
      </label>
      <div class="_price_range">
        <span class="_start">
          <input
            type="text"
            class="init-input"
            v-model="min"
            @input="InputPrice()"
          >원</span>
        <span class="_end">
          <input
            type="text"
            class="init-input"
            v-model="max"
            @input="InputPrice()"
            @change="Validation()"
          >원</span>
        <input
          type="button"
          class="square-btn-outline"
          value="검색"
          @click="ChangePrice"
        >
      </div>
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
    methods: {
      ChangePrice () {
        const price = this.price.split(',')
        this.min = this.NumberFormat(price[0])
        this.max = this.NumberFormat(price[1])
        this.$emit('input', price)
        this.$emit('search-submit')
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
