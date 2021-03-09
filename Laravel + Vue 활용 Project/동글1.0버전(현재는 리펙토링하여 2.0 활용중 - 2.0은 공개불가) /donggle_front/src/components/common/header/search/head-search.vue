<template>
  <!-- black bar search -->
  <div class="dg-black_bar_search_wrap">
    <label class="dg_blind">검색</label>
    <input
      class="dg-black_bar-search"
      type="text"
      placeholder="검색어 미입력시 필터로 연결됩니다."
      v-model="searchKeyword"
      @keyup.enter="Submit()"
    />
    <div class="dg-black_bar-search-bar"></div>
    <button type="button" class="dg-black_bar-search_btn" @click="Submit()"></button>
  </div>
  <!-- black bar search E -->
</template>

<script>
export default {
  data: function() {
    return {
      searchKeyword: ""
    };
  },
  methods: {
    Submit() {
      // this.$router.push({ name: 'total-search', query: { searchKeyword: this.searchKeyword } })
      location.href = "/total/search?searchKeyword=" + this.searchKeyword + "";
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
