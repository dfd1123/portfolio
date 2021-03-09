<template>
  <div class="search-default-bar">
    <select
      v-model="selectData"
      class="form-select"
      @change="$emit('update:select', $event.target.value)"
    >
      <option v-for="option in options" :key="option.value" :value="option.value">{{option.label}}</option>
    </select>
    <input
      v-model="keywordData"
      type="text"
      class="form-input-txt"
      placeholder="검색어 미입력시 필터로 연결됩니다."
      @input="$emit('update:keyword', $event.target.value)"
      @keyup.enter="$emit('click')"
    />
    <button
      type="button"
      class="rounded-square-btn btn-theme icon-search-btn"
      @click="$emit('click')"
    >
      <i class="icon-search">
        <img src="/images/icon/icon-search.svg" alt />
      </i>
    </button>
  </div>
</template>

<script>
export default {
  name: "TableSearchBar",
  props: {
    select: {
      type: String,
      default: ""
    },
    keyword: {
      type: String,
      default: ""
    },
    options: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      selectData: this.select,
      keywordData: this.keyword
    };
  },
  watch: {
    select() {
      this.selectData = this.select;
    },
    keyword() {
      this.keywordData = this.keyword;
    }
  }
};
</script>

<style>
</style>
