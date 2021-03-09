<template>
  <div>
    <div
      class="table-list-data"
      :style="{height: inputData.height || 'initial'}"
    >
      <table class="list">
        <caption>리스트 테이블 표</caption>
        <colgroup>
          <template v-for="(thData, index) in thDatas">
            <col
              v-if="typeof thData === 'string'"
              :key="'tdCol' + index"
              class="width-auto"
            >
            <col
              v-if="typeof thData === 'object'"
              :key="'tdCol' + index"
              class="width-auto"
              :style="thData.style || ''"
            >
          </template>
        </colgroup>
        <thead>
          <tr>
            <template v-for="(thData, index) in thDatas">
              <th
                v-if="typeof thData === 'string'"
                :key="'th' + index"
                scope="col"
              >
                {{ thData }}
              </th>
              <th
                v-if="typeof thData === 'object'"
                :key="'th' + index"
                scope="col"
                :style="thData.style || ''"
              >
                {{ thData.label || '' }}
              </th>
            </template>
          </tr>
        </thead>
        <tbody v-if="resultLists.length > 0">
          <tr
            v-for="resultList in resultLists"
            :key="resultKey ? resultKey.map(x => (typeof resultList[x]) === 'object' ? resultList[x].index || '' : resultList[x]).join('-') : resultList[resultIndex]"
            @click="SetView(resultList[resultIndex], resultList)"
          >
            <template v-for="(td, index) in resultList">
              <td
                :key="index+'td'"
                v-if="!Array.isArray(td)"
              >
                <span
                  v-if="String(td).startsWith('@v-html:')"
                  v-html="td.substring(8)"
                ></span>
                <span v-else-if="(typeof td) === 'object'">
                  <span v-if="td.type === 'button'">
                    <a
                      href="javascript:;"
                      :class="td.class || ['btn-01', 'type-02', 'squre-round']"
                      @click.prevent.stop="rowButtonClick(td)"
                    >{{ td.text }}</a>
                  </span>
                  <div
                    v-else-if="td.type === 'tag'"
                    style="white-space: nowrap; text-align: left;"
                    v-html="td.index + td.tag"
                  >
                  </div>
                </span>
                <template v-else>
                  {{ td }}
                </template>
              </td>
            </template>
          </tr>
        </tbody>
        <tbody v-else>
          <tr class="none">
            <td :colspan="thDatas.length">
              <p :class="[inputData.height ? '' : 'none-data']">
                등록된 게시물이 없습니다
              </p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      inputData: {
        type: Object,
        default: () => {
          return {}
        }
      },
      thDatas: {
        type: Array,
        required: true,
        default: () => {
          return {}
        }
      },
      resultLists: {
        type: Array,
        required: true,
        default: () => {
          return {}
        }
      },
      resultKey: {
        type: Array,
        default: null
      },
      resultIndex: {
        type: Number,
        default: 0
      }
    },
    methods: {
      SetView (id, list) {
        if ((typeof id) === 'object' && id.type === 'tag') {
          this.$emit('view-open', id.index, list)
          return
        }
        this.$emit('view-open', id, list)
      },
      rowButtonClick (td) {
        this.$emit('row-button-click', td)
      }
    }
  }
</script>

<style  scoped>
  .table-list-data {
    overflow-y: auto;
  }

  ::v-deep .table-list-data .table-list-ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
</style>
