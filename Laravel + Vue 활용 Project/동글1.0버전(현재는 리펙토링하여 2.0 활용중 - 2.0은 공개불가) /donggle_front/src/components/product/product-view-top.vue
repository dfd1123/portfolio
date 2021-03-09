<template>
  <section
    id="product_view"
    class="clear_both"
  >
    <h2 class="dg_blind">
      상품 자세히보기
    </h2>
    <!-- con1 left -->
    <ProductImageBox :item="item" />
    <!-- con1 left E -->

    <!-- con1 right -->
    <ProductInfoBox
      :item="item"
      :options="options"
      :option-subject="optionSubject"
      :options-name="optionsName"
      :review-count="reviewCount"
      @submit="Submit"
    />
    <!-- con1 right E -->
  </section>
</template>

<script>
  import ProductImageBox from '@/components/product/product-image-box.vue'
  import ProductInfoBox from '@/components/product/product-info-box.vue'

  export default {
    components: {
      ProductImageBox,
      ProductInfoBox
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
