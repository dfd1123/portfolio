<template>
  <div class="dg-gradi_search_wrap">
    <!-- gradiant search popup -->
    <div>
      <label class="dg_blind">검색</label>
      <input
        class="dg-black_bar-search"
        type="text"
        placeholder="검색어 미입력시 필터로 연결됩니다."
        v-model="searchKeyword"
        @keyup.enter="Submit()"
      />
      <div class="#"></div>
      <button type="button" @click="Submit()"></button>
    </div>
    <a @click="FullSearch()" class="dg-search_gra_close_btn" style="cursor:pointer;">close</a>
  </div>
  <!-- gradiant search popup E -->
</template>

<script>
export default {
  data: function() {
    return {
      searchKeyword: ""
    };
  },
  props: {
    fullSearchShow: {
      type: Boolean,
      required: true
    }
  },
  methods: {
    FullSearch() {
      this.$emit("close-event");
    },
    Submit() {
      // this.$router.push({ name: 'total-search', query: { searchKeyword: this.searchKeyword } })
      location.href = "/total/search?searchKeyword=" + this.searchKeyword + "";
    }
  }
};
</script>

<style lang="scss" scoped>
</style>
