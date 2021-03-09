<template>
  <div>
    <!-- 상품 사진 -->
    <ProductImages :item="item" />
    <!-- 상품 사진 E -->

    <!-- 상품 안내 -->
    <ProductInfo
      :item="item"
      :options="options"
      :option-subject="optionSubject"
      :options-name="optionsName"
      :review-count="reviewCount"
      @sns-popup-open="$emit('sns-popup-open')"
      @submit="Submit"
    />
    <!-- 상품 안내 E -->
  </div>
</template>

<script>
  import ProductImages from '@/components/product-view/product-images.vue'
  import ProductInfo from '@/components/product-view/product-info.vue'

  export default {
    components: {
      ProductImages,
      ProductInfo
    },
    data: function () {
      return {
        selectOption: this.$store.state.selectOption,
        totalPrice: this.$store.state.totalPrice
      }
    },
    props: {
      item: {
        type: Object,
        required: true
      },
      options: {
        type: Array,
        required: true
      },
      optionSubject: {
        type: Array,
        required: true
      },
      optionsName: {
        type: Array,
        required: true
      },
      reviewCount: {
        type: Number,
        required: true
      }
    },
    methods: {
      SelectOption (value) {
        this.selectOption = value
        this.$store.commit('SelectOptionStore', this.selectOption)
      },
      TotalPrice (value) {
        this.totalPrice = value
        this.$store.commit('TotalPriceChange', this.totalPrice)
      },
      Submit (kind) {
        console.log('awdawd')
        this.$emit('submit', kind)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
