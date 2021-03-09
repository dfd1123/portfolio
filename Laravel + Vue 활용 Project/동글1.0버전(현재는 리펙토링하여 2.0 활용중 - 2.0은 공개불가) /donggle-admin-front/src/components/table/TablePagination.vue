<template>
  <!-- pagination -->
  <div>
    <aside class="pager-wrap clearfix">
      <div class="_pager_box _pager_left">
        <button class="_pager _left_db" @click="firstPageClick"></button>
        <button class="_pager _left_sg" @click="prevPageClick"></button>
      </div>
      <div v-if="pageCount > 0" class="_pager_box _pager_number">
        <button
          v-for="n in Math.min(pageRange, pageCount)"
          :key="n + start"
          class="_pager"
          :class="{active: (n + start) === page}"
          @click="pageClick(n + start)"
        >{{padding(n + start)}}</button>
      </div>
      <div class="_pager_box _pager_right">
        <button class="_pager _right_sg" @click="nextPageClick"></button>
        <button class="_pager _right_db" @click="lastPageClick"></button>
      </div>
    </aside>
  </div>
  <!-- pagination E -->
</template>

<script>
export default {
  name: 'TablePagination',
  props: {
    limit: {
      type: Number,
      required: true,
      default: 10
    },
    page: {
      type: Number,
      required: true,
      default: 1

    },
    count: {
      type: null,
      required: true,
      default: 0
    },
    pageRange: {
      type: Number,
      default: 9
    }
  },
  computed: {
    pageCount () {
      if (!this.count || !this.limit) {
        return 0
      }

      return Math.ceil(this.count / this.limit)
    },
    middle () { return Math.ceil(this.pageRange / 2) },
    start () {
      return Math.min(Math.max(this.page - this.middle, 0), Math.max(this.pageCount - this.pageRange, 0))
    }
  },
  methods: {
    pageClick (page) {
      this.$emit('update:page', page)
      this.$emit('page-change', page)
    },
    firstPageClick () {
      if (this.pageCount > 0 && this.page !== 1) {
        this.$emit('update:page', 1)
        this.$emit('page-change', 1)
      }
    },
    lastPageClick () {
      if (this.pageCount > 0 && this.page < this.pageCount) {
        this.$emit('update:page', this.pageCount)
        this.$emit('page-change', this.pageCount)
      }
    },
    prevPageClick () {
      if (this.pageCount > 0 && this.page - 1 > 0) {
        this.$emit('update:page', this.page - 1)
        this.$emit('page-change', this.page - 1)
      }
    },
    nextPageClick () {
      if (this.pageCount > 0 && this.page + 1 <= this.pageCount) {
        this.$emit('update:page', this.page + 1)
        this.$emit('page-change', this.page + 1)
      }
    },
    padding (number) {
      return String(number).padStart(String(this.pageCount).length, '0')
    }
  }
}
</script>

<style>
</style>
