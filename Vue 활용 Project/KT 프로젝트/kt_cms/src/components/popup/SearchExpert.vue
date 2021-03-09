<template>
  <div class="layer-pop layer-pop-parents expert-search">
    <div class="list-search-area">
      <div class="">
        <ul class="grid-layout">
          <li class="row-1">
            <div class="title-input-cell">
              <em>전문가ID 조회</em>
              <div class="search-input">
                <input
                  type="text"
                  class="auto-width-full"
                  v-model="keyword"
                  @keyup.enter="Search"
                >
                <button
                  type="button"
                  @click="Search"
                >
                  <span>검색</span>
                </button>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    <!-- 전문가조회 검색 결과 영역 -->
    <div
      class="overflow-y-scroll"
      style="max-height:209px"
    >
      <table class="list">
        <caption>리스트 테이블 표</caption>
        <colgroup>
          <col style="width:60px;">
          <col style="width:auto;">
          <col style="width:auto;">
          <col style="width:auto;">
        </colgroup>
        <thead>
          <tr>
            <th scope="col">
              <p
                v-show="!single"
                class="check-type01 single"
              >
                <input
                  type="checkbox"
                  :id="status + '_all_expert'"
                  :name="status + '_all_expert'"
                  v-model="allChecked"
                ><label
                  :for="status + '_all_expert'"
                  @click="checkAll()"
                ><span></span></label>
              </p>
            </th>
            <th scope="col">
              전문가 ID
            </th>
            <th scope="col">
              전문가명
            </th>
            <th scope="col">
              소속
            </th>
          </tr>
        </thead>
        <tbody v-if="list.length > 0">
          <tr
            v-for="item in list"
            :key="item.svcId + '_' + item.userUuid + '_' + status"
          >
            <td>
              <p class="check-type01 single">
                <input
                  type="checkbox"
                  :id="status + 'check_' + item.svcId + '_' + item.userUuid"
                  :name="status + 'check_' + item.svcId + '_' + item.userUuid"
                  class="single_expert_check"
                  :checked="item.checked"
                  @click="check(item)"
                ><label :for="status + 'check_' + item.svcId + '_' + item.userUuid"><span></span></label>
              </p>
            </td>
            <td>{{ item.userId }}</td>
            <td>{{ item.userNm }}</td>
            <td>{{ item.pos }}</td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr>
            <td
              colspan="4"
              style="height:185px;"
            >
              데이터가 존재하지 않습니다.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- //전문가조회 검색 결과 영역 -->
    <div class="btn-page-wrap big">
      <button
        type="button"
        @click="layerPopClose()"
        class="btn-01 type-02"
      >
        닫기
      </button>
      <button
        type="button"
        class="btn-01 type-01"
        @click="AddSubmit"
      >
        추가
      </button>
    </div>
  </div>
</template>

<script>
  import isEmpty from 'lodash/isEmpty'

  export default {
    data () {
      return {
        keyword: '',
        list: [],
        allChecked: false
      }
    },
    props: {
      xpertUuids: {
        type: Array,
        default () {
          return []
        }
      },
      svcId: {
        type: String,
        default: null
      },
      status: {
        type: String,
        required: true
      },
      single: {
        type: Boolean,
        default: false
      },
      preLoading: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      checkedList () {
        return this.list.filter(x => x.checked)
      }
    },
    async created () {
      setTimeout(() => {
        if (this.preLoading) {
          this.GetData()
        }
      }, 0)
    },
    watch: {
      async xpertUuids (newValue, oldValue) {
        if (this.status === 'edit') {
          this.list = this.list.map(x => ({ ...x, ...{ checked: this.xpertUuids.includes(x.userUuid) } }))
        }
      },
      async svcId (newValue, oldValue) {
        if (this.status === 'edit') {
          if (newValue) {
            await this.GetData()
            if (this.checkedList.length > 0) {
              this.$emit('value-found', [...this.checkedList])
            }
          } else {
            this.list = []
            this.$emit('value-found', [])
          }
        } else if (this.status === 'create') {
          if (newValue) {
            await this.GetData()
            this.allChecked = false
            this.list.forEach(x => { x.checked = false })
            this.$emit('value-found', [])
            this.$emit('value-set', [])
          } else {
            this.list = []
            this.allChecked = false
            this.$emit('value-found', [])
            this.$emit('value-set', [])
          }
        }
      },
      list () {
        if (!this.single) {
          this.allChecked = this.list.length > 0 ? this.list.every(x => x.checked) : false
        }
      }
    },
    methods: {
      ...{ isEmpty },
      Search () {
        this.GetData()
      },
      async GetData () {
        const params = {
          svcId: this.svcId,
          unitSvcUserAutCd: '02',
          userNm: this.keyword || null
        }
        const headers = {}

        if (['03', '04'].includes(this.auth)) {
          params.svcId = this.user.svc_id
        }

        if (params.svcId) {
          headers['X-Svc-Id'] = params.svcId
          delete params.svcId
        }

        const res = await this.$http
          .get(this.$BASEURL + '/user/service/info', { params, headers })
          .then(this.NormalOrError)
          .then(res => res.data.data)

        this.list = res.map(x => ({ ...x, ...{ checked: this.xpertUuids.includes(x.userUuid) } }))
      },
      check (item) {
        if (this.single) {
          this.list.forEach(x => { x.checked = false })
          item.checked = true
        } else {
          item.checked = !item.checked
          this.allChecked = this.list.every(x => x.checked)
        }
      },
      checkAll () {
        if (this.list.length === 0) {
          this.allChecked = false
          return
        }

        this.list.forEach(x => { x.checked = !this.allChecked })
      },
      AddSubmit () {
        this.$emit('value-set', [...this.checkedList])

        this.keyword = ''
        this.layerPopClose()
      },
      layerPopClose: function (_obj) {
        window.$('.expert-search').stop(true).fadeOut(350)
      }
    }
  }
</script>

<style scoped>
</style>
