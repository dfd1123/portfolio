<template>
  <!-- * 필터검색 -->
  <div class="dg-filter-search-group">
    <h2 class="in-subject">
      필터검색
    </h2>

    <input
      id="view-filter-group"
      type="checkbox"
      class="display_none"
    >
    <label
      for="view-filter-group"
      class="fold-btn"
    ></label>

    <div
      id="view-filter-view"
      class="l-contents-group"
    >
      <Gender
        :gender="form.gender"
        @input="form.gender = $event"
        @search-submit="SearchSubmit"
      />

      <Age
        :age="form.age"
        @input="form.age = $event"
        @search-submit="SearchSubmit"
      />

      <Color
        :color="form.color"
        :main-colors="mainColors"
        :sub-colors="subColors"
        @input="form.color = $event"
        @search-submit="SearchSubmit"
      />

      <Price
        :min-price="form.min_price"
        :max-price="form.max_price"
        @input="PriceChange"
        @search-submit="SearchSubmit"
      />

      <Size
        :min-size="this.form.min_size"
        :max-size="this.form.max_size"
        @input="SizeChange"
        @search-submit="SearchSubmit"
      />

      <HashTag
        :hash-tag="form.hash_tag"
        @input="form.hash_tag = $event"
        @search-submit="SearchSubmit"
      />
    </div>
  </div>
  <!-- * 필터검색 E -->
</template>

<script>
  import Gender from '@/components/search/filter/gender.vue'
  import Age from '@/components/search/filter/age.vue'
  import Color from '@/components/search/filter/color.vue'
  import Price from '@/components/search/filter/price.vue'
  import HashTag from '@/components/search/filter/hash-tag.vue'
  import Size from '@/components/search/filter/size.vue'

  export default {
    components: {
      Gender,
      Age,
      Color,
      Price,
      HashTag,
      Size
    },
    data: function () {
      return {
        form: this.propForm,
        price: '',
        categorys: [],
        searchConditions: []
      }
    },
    props: {
      propForm: {
        type: Object,
        required: true
      },
      mainColors: {
        type: Array,
        default: () => {
          return []
        }
      },
      subColors: {
        type: Array,
        default: () => {
          return []
        }
      }
    },
    methods: {
      PriceChange (price) {
        this.form.min_price = (price[0] === '') ? null : Number(price[0])
        this.form.max_price = (price[1] === '') ? null : Number(price[1])
      },
      SizeChange (minSize, maxSize) {
        this.form.min_size = Number(minSize)
        this.form.max_size = Number(maxSize)
        this.SearchSubmit()
      },
      SearchSubmit () {
        this.$emit('search-submit', this.form)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
