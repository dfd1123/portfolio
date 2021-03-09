<template>
  <div>
    <div v-show="isShowUpperStatusBar" class="search-status-bar choice-status-bar clearfix">
      <slot name="status-bar" />
      <table-select-all-bar
        :is-select-all-visible="isCheckAllVisible"
        :is-select-all.sync="isSelectAll"
        :order-by-select.sync="orderBySelect"
        :order-by-options="orderByOptions"
        :selected-ready-button-list="selectedReadyButtonList"
        @selected-ready-button-click="selectedReadyButtonClick"
        @order-by-select-change="orderBySelectChange"
      />
    </div>

    <div ref="tableShowMobile">
      <!-- visibiity -->
    </div>

    <div :class="tableWrapperClasses" :style="{display: rows === null ? 'none' : ''}">
      <!-- 있을 때 -->
      <slot
        v-if="rowList.length > 0"
        name="rows"
        :list="rowList"
        :index="rowIndex"
        :checked-list="rowsChecked"
        :check="rowCheck"
      />
      <!-- END 있을 때 -->

      <!-- 없을 때 -->
      <ul v-else>
        <li>
          <div class="nothing-history">
            <img src="/images/icon/empty_board_m.svg" alt="icon empty" class="in-empty-icon" />
            <span class="in-empty-ment">내역이 없습니다.</span>
          </div>
        </li>
      </ul>
      <!-- END 없을 때 -->
    </div>

    <button
      v-show="rowList.length !== 0 && isShowMoreButtonVisible"
      type="button"
      class="more-view-btn"
      @click="$emit('show-more-button-click')"
    >
      <img src="/images/icon/arrow_bt_navy.svg" alt="아래 화살표" class="in-arrow-icon" />
      <span>더보기</span>
    </button>

    <!-- 스크롤하면 fixed로 바뀜 -->
    <div class="go-top-btn" @click="scrollTop">top</div>
  </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'

export default {
  name: 'TableDataGrid',
  props: {
    isCheckAllVisible: {
      type: Boolean,
      default: true
    },
    isShowUpperStatusBar: {
      type: Boolean,
      default: true
    },
    isShowMoreButtonVisible: {
      type: Boolean,
      default: true
    },
    rows: {
      type: null,
      required: true,
      default: () => []
    },
    rowsChecked: {
      type: Array,
      default: () => []
    },
    rowIndex: {
      type: String,
      required: true
    },
    orderBySelect: {
      type: String,
      default: null
    },
    orderByOptions: {
      type: Array,
      default: () => []
    },
    selectedReadyButtonList: {
      type: Array,
      default: () => ['준비 완료']
    },
    tableWrapperClasses: {
      type: Array,
      default: () => ['tbl-list-wrapper']
    }
  },
  data () {
    return {
      rowList: []
    }
  },
  mounted () {
    this.$bus.$on('on-global-infinite-scroll', this.checkInfiniteScroll)
    // window.addEventListener('scroll', this.handleScroll)
  },
  beforeDestroy () {
    this.$bus.$off('on-global-infinite-scroll', this.checkInfiniteScroll)
    // window.removeEventListener('scroll', this.handleScroll)
  },
  watch: {
    rows () {
      this.rowList = this.rows
      this.$emit('update:rows-checked', [])
    }
  },
  computed: {
    isSelectAll: {
      get () {
        return this.rowList.length > 0
          ? this.rowsChecked.length === this.rowList.length
          : false
      },
      set (value) {
        const selected = []

        if (value) {
          this.rowList.forEach(row => {
            selected.push(String(row[this.rowIndex]))
          })
        }

        this.$emit('update:rows-checked', selected)
      }
    }
  },
  methods: {
    ...mapGetters(['fixedTop']),
    ...mapMutations(['setFixedTop']),
    rowCheck (e) {
      const value = String(e.target.value)

      const list = this.rowsChecked
        .filter(x => x !== value)
        .concat(e.target.checked ? [value] : [])

      this.$emit('update:rows-checked', list)
    },
    selectedReadyButtonClick (index) {
      const checkedList = this.rowList
        .filter(row => this.rowsChecked.includes(String(row[this.rowIndex])))
        .map(x => ({ ...x }))

      this.$emit('selected-ready-button-click', checkedList, index)
    },
    orderBySelectChange (e) {
      const value = e.target.value

      this.$emit('update:order-by-select', value)
      this.$emit('order-by-select-change', value)
    },
    checkInfiniteScroll () {
      const style = window.getComputedStyle(this.$refs.tableShowMobile)

      if (style.display !== 'none') {
        // this.$emit('infinite-scroll')
      }
    },
    handleScroll () {
      setTimeout(() => {
        const style = window.getComputedStyle(this.$refs.tableShowMobile)

        if (style.display !== 'none') {
          const el = this.$refs.tableShowMobile
          const { top, bottom } = el.getBoundingClientRect()

          const el2 = document.getElementsByClassName(
            'check-fixed-top-mobile'
          )[0]
          if (el2) {
            if (top >= 0 && bottom <= window.innerHeight) {
              el2.classList.remove('fixed_top')
            } else {
              el2.classList.add('fixed_top')
            }
          }
        }
      })
    },
    scrollTop () {
      setTimeout(() => {
        window.scrollTo(0, 0)
      })
    }
  }
}
</script>

<style>
</style>
