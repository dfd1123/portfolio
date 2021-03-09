<template>
  <div
    v-if="gridData"
    class="table-list-data"
    :class="[gridData.readonly ? 'readonly' : '']"
    :style="{height: gridData.height || 'initial'}"
  >
    <table class="read-table">
      <caption>리스트 테이블 표</caption>
      <colgroup>
        <col
          v-if="gridData.rowCheckType"
          style="width:50px;"
        >
        <col
          v-for="(col, index) in cols"
          :key="index"
          class="width-auto"
        >
      </colgroup>
      <thead>
        <tr>
          <th
            v-if="gridData.rowCheckType"
            style="width:50px;"
          >
          </th>
          <th
            v-for="(col, index) in cols"
            :key="index"
            scope="col"
          >
            {{ col.label }}
          </th>
        </tr>
      </thead>
      <tbody v-if="rows.length > 0">
        <tr
          v-for="(row, index) in rows"
          :key="row[gridData.index] || index"
        >
          <td v-if="gridData.rowCheckType">
            <p class="check-type01 single">
              <input
                type="checkbox"
                :id="`userservice-${index}`"
                name="check"
                @click="check(row)"
                :checked="row.checked"
              ><label :for="`userservice-${index}`"><span></span></label>
            </p>
          </td>
          <td
            v-for="col in cols"
            :key="col.name"
          >
            <template>
              <select
                v-if="col.type === 'select'"
                v-model="row[col.name]"
                :readonly="gridData.isEditableOnlyCheckedRows ? (!row.checked || col.readonly) : col.readonly"
                :disabled="gridData.isEditableOnlyCheckedRows ? (!row.checked || col.readonly) : col.readonly"
                class="normal-select"
              >
                <option
                  v-for="option in col.options"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </option>
              </select>
              <span v-else-if="col.type === 'button'">
                <a
                  href="javascript:;"
                  :class="col.class || ['btn-01', 'type-02', 'squre-round']"
                  @click.prevent.stop="onRowButtonClick(col)"
                >{{ col.text }}</a>
              </span>
              <template v-else>
                {{ row[col.name] || '-' }}
              </template>
            </template>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr class="none">
          <td :colspan="cols.length + (gridData.rowCheckType ? 1 : 0)">
            <p :class="[gridData.height ? '' : 'none-data']">
              데이터가 존재하지 않습니다
            </p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  import Vue from 'vue'

  export default {
    components: {

    },
    data () {
      return {
        gridData: {}
      }
    },
    props: {
      setting: {
        type: Object,
        required: true,
        default: () => ({})
      }
    },
    watch: {
      setting () {
        this.gridData = this.setting
      }
    },
    created () {
      this.gridData = this.setting
    },
    computed: {
      cols () {
        if (!this.gridData.cols) {
          return []
        }

        return this.gridData.cols.filter(col => !col.hide)
      },
      rows () {
        if (!this.gridData.rows) {
          return []
        }

        return this.gridData.rows.map(row => {
          if (!('checked' in row)) {
            Vue.set(row, 'checked', false)
            return row
          }

          return row
        })
      }
    },
    methods: {
      check (row) {
        if (this.gridData.rowCheckType === 'multi') {
          row.checked = !row.checked
        } else if (this.gridData.rowCheckType === 'single') {
          const value = !row.checked
          this.gridData.rows.forEach(x => { x.checked = false })
          row.checked = value
        }
      },
      onRowButtonClick (row) {
        if ((typeof this.setting.onRowButtonClick) === 'function') {
          this.setting.onRowButtonClick(row)
        }
      }
    }
  }
</script>

<style scoped>
  .readonly {
    pointer-events: none;
    opacity: 0.8;
  }
  
  select.normal-select {
    max-width: 100%;
  }
</style>
