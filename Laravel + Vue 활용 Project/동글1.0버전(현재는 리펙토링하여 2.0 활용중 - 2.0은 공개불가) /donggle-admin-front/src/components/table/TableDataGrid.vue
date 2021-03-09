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

    <div class="tbl-wrapper" :style="{display: rows === null ? 'none' : ''}">
      <div class="tbl-container" :class="tableContainerClasses">
        <table id="chech-order-tbl" class="tbl-default" :class="tableClasses">
          <colgroup>
            <slot name="colgroups" />
          </colgroup>
          <thead class="tbl-default-head">
            <slot name="headers" />
          </thead>
          <tfoot class="tbl-default-foot">
            <slot name="footers" :list="rowList" />
          </tfoot>
          <tbody class="tbl-default-body">
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
            <tr v-else class="in-tr in-nothing-tr">
              <td :colspan="colLength">
                <div class="nothing-history">
                  <img src="/images/icon/empty_board_m.svg" alt="icon empty" class="in-empty-icon" />
                  <span class="in-empty-ment">내역이 없습니다.</span>
                </div>
              </td>
            </tr>
            <!-- END 없을 때 -->
          </tbody>
        </table>
      </div>
    </div>

    <button
      v-if="rowList.length !== 0 && isShowMoreButtonVisible"
      type="button"
      class="more-view-btn"
      @click="$emit('show-more-button-click')"
    >
      <img src="/images/icon/arrow_bt_navy.svg" alt="아래 화살표" class="in-arrow-icon" />
      <span>더보기</span>
    </button>

    <div v-show="isCheckAllVisible" class="search-status-bar choice-status-bar clearfix">
      <table-select-all-bar
        class="type-last"
        :is-select-all-visible="isCheckAllVisible"
        :style="{display: rows === null ? 'none' : ''}"
        :is-select-all.sync="isSelectAll"
        :selected-ready-button-list="selectedReadyButtonList"
        @selected-ready-button-click="selectedReadyButtonClick"
        @order-by-select-change="orderBySelectChange"
      />
    </div>
  </div>
</template>

<script>
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
    tableContainerClasses: {
      type: Array,
      default: () => []
    },
    tableClasses: {
      type: Array,
      default: () => ['detail_tbl']
    },
    selectedReadyButtonList: {
      type: Array,
      default: () => ['준비 완료']
    }
  },
  data () {
    return {
      colLength: 0,
      rowList: []
    }
  },
  mounted () {
    this.colLength = this._flatten(this._get(this, '$slots.headers', []).filter(x => x.tag).map(x => x.children.filter(y => y.tag))).length
  },
  watch: {
    rows () {
      this.rowList = this.rows
      this.$emit('update:rows-checked', [])
      this.colLength = this._flatten(this._get(this, '$slots.headers', []).filter(x => x.tag).map(x => x.children.filter(y => y.tag))).length
    }
  },
  computed: {
    isSelectAll: {
      get () {
        return this.rowList.length > 0 ? this.rowsChecked.length === this.rowList.length : false
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
    }
  }
}
</script>

<style>
</style>
