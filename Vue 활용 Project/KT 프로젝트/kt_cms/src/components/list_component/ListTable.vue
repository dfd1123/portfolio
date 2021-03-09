<template>
  <!-- //리스트 검색 영역 -->

  <!-- 검색 결과 리스트 -->
  <div class="table-list-data">
    <h3 class="sub-title">
      Zone관리 목록
    </h3>
    <p class="table-top-btns">
      <button class="btn-01 type-03 btn-excel">
        <span class="icon-excel-upload">엑셀 업로드</span>
      </button>
      <button class="btn-01 type-03 btn-excel">
        <span class="icon-excel-download">엑셀 다운로드</span>
      </button>
      <AtagButton
        btn-type="type-01"
        btn-name="등록"
        :to="defaultUrl + '/create'"
      />
    </p>
    <!--
        <div class="table-sort">
          전체 : <span>{{ records }}</span>건
        </div>
        -->
    <div class="overflow-x-scroll">
      <table class="list">
        <caption>리스트 테이블 표</caption>
        <colgroup>
          <col
            v-for="(thData, index) in thDatas"
            :key="index"
            class="width-auto"
          >
        </colgroup>
        <thead>
          <tr>
            <th
              scope="col"
              v-show="check"
            >
              <p class="check-type01 single">
                <input
                  type="checkbox"
                  id="all_data"
                  @click="AllDataSelect"
                ><label for="all_data"><span></span></label>
              </p>
            </th>
            <th
              v-for="(thData, index) in thDatas"
              :key="index"
              scope="col"
            >
              {{ thData }}
            </th>
          </tr>
        </thead>
        <tbody v-if="records > 0">
          <tr
            v-for="tdData in tdDatas"
            :key="tdData[0]"
            @click="ViewPageMove(tdData[0])"
          >
            <td
              v-for="(td, index) in tdData"
              :key="index+'td'"
              v-show="index !== 0 || check"
            >
              <p
                class="check-type01 single"
                v-if="index === 0"
                v-show="check"
              >
                <input
                  type="checkbox"
                  :id="'checkId'+tdData[0]"
                  name="checkId"
                  class="single_checkbox"
                  :value="tdData[0]"
                  v-model="checkId"
                ><label :for="'checkId'+tdData[0]"><span></span></label>
              </p>
              <router-link
                v-else-if="LinkOrder(linkOrder) === index"
                to=""
                @click="ViewPageMove(tdData[0])"
              >
                {{ td }}
              </router-link>
              <span v-else>{{ td }}</span>
            </td>
          </tr>
        </tbody>
        <tbody v-else-if="records === 0">
          <tr>
            <td :colspan="!check ? thDatas.length : thDatas.length + 1">
              <p class="none-data">
                등록된 게시물이 없습니다
              </p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- //검색 결과 리스트 -->
</template>

<script>
  import AtagButton from '@/components/button/AtagButton.vue'

  export default {
    props: {
      records: {
        type: Number,
        default: 0
      },
      thDatas: {
        type: Array,
        default: function () {
          return {}
        }
      },
      tdDatas: {
        type: Array,
        default: function () {
          return {}
        }
      },
      size: {
        type: Number,
        default: 1
      },
      currentPage: {
        type: Number,
        default: 10
      },
      linkOrder: {
        type: Number,
        default: 2
      },
      check: {
        type: Boolean,
        default: true
      },
      defaultUrl: {
        type: String,
        required: true
      }
    },
    data: function () {
      return {
        checkId: []
      }
    },
    components: {
      AtagButton
    },
    async created () {
      
    },
    watch: {
      checkId: function () {
        if (this.checkId.length !== this.tdDatas.length) {
          document.getElementById('all_data').checked = false
        }

        this.passCheckId()
      }
    },
    methods: {
      LinkOrder: function (linkOrder) {
        return linkOrder - 1
      },
      AllDataSelect: function () {
        let i, n
        let checkedValue = []
        let selectAllCheckbox = document.getElementById('all_data')
        let checkboxes = document.getElementsByClassName('single_checkbox')
        if (selectAllCheckbox.checked === true) {
          for (i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = true
            checkedValue.push(checkboxes[i].value)
          }
        } else {
          for (i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = false
            checkedValue = []
          }
        }
        this.checkId = checkedValue
      },
      passCheckId: function () {
        this.$emit('selectCheckId', this.checkId)
      },
      deleteList: function () {
        console.log(this.checkId)

        /* checkId에 들어가있는 리스트ID값을 참조하여 DELETE API 실행하고 다시 데이터 받아오기 START */

        /* checkId에 들어가있는 리스트ID값을 참조하여 DELETE API 실행하고 다시 데이터 받아오기 END */
      },
      ViewPageMove: function (id) {
        this.$emit('set-view', id)
      }
    }
  }
</script>

<style lang="css" scoped>
  table.list > thead > tr > th:first-child{
    display:none;
  }

  table.list > tbody > tr > td:first-child{
    display:none;
  }
</style>
