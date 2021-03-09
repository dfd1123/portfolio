<template>
  <li class="dg-category_main">
    <router-link :to="'/product/list/' + JSON.parse(category.ca_first).id + ''">
      {{ CategoryName(JSON.parse(category.ca_first).ca_name) }}
    </router-link>
    <ul class="dg-category_sub over-y_scroll">
      <li>
        <h2 class="dg-category_sub_title">
          {{ CategoryName(JSON.parse(category.ca_first).ca_name) }}
        </h2>
      </li>
      <li
        v-for="secondCategory in JSON.parse(category.ca_second)"
        :key="secondCategory.id"
      >
        <router-link :to="'/product/list/' + secondCategory.id + ''">
          {{ CategoryName(secondCategory.ca_name) }}
        </router-link>
      </li>
    </ul>
  </li>
</template>

<script>
  export default {
    props: {
      category: {
        type: Object,
        required: true
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
