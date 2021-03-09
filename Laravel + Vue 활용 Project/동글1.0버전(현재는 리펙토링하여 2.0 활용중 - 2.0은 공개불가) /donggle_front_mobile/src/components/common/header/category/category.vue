<template>
  <li class="dg-category_main">
    <input
      type="radio"
      name="kind"
      :id="'cate_'+JSON.parse(category.ca_first).id"
      class="category-checkbox display_none"
    >
    <label
      :for="'cate_'+JSON.parse(category.ca_first).id"
      class="category-label"
    >
      {{ CategoryName(JSON.parse(category.ca_first).ca_name) }}
    </label>
    <ul
      class="dg-category_sub"
    >
      <h2 class="dg-category_sub_title">
        {{ CategoryName(JSON.parse(category.ca_first).ca_name) }}
      </h2>
      <li>
        <div
          class="_submenu"
          @click="UrlChange('/product/list/' + JSON.parse(category.ca_first).id + '')"
        >
          전체
        </div>
      </li>
      <li
        v-for="secondCategory in JSON.parse(category.ca_second)"
        :key="secondCategory.id"
      >
        <div
          class="_submenu"
          @click="UrlChange('/product/list/' + secondCategory.id + '')"
        >
          {{ CategoryName(secondCategory.ca_name) }}
        </div>
      </li>
    </ul>
  </li>
</template>

<script>
  export default {
    data: function () {
      return {
        kind: null
      }
    },
    props: {
      category: {
        type: Object,
        required: true
      }
    },
    methods: {
      UrlChange (url) {
        this.$emit('popup-close')
        this.$router.push(url)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
