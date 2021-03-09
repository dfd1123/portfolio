<template>
  <div>
    <PageTitle
      title="확장속성"
      info="서비스의 속성을 확장하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="서비스속성등록"
          style="vertical-align: top;"
          @click-event="CreateOpen"
        />
        <!--
        <ExcelButton kind="upload" />
        <ExcelButton kind="download" />
        -->
      </div>
      <div class="list-search">
        <div class="search-input">
          <Input
            type="text"
            id="searchText"
            place-holder="검색"
            style="width:100%;"
            width="100%; padding-right:50px;"
            v-model="keywordSmart"
            @click-event="OnSearchPage"
          />
          <button
            type="button"
            @click="OnSearchPage"
          >
            검색
          </button>
        </div>
        <FilterButton
          :visible="isFilterVisible"
          @filter-button-click="isFilterVisible = !isFilterVisible"
        />
      </div>

      <!-- 검색 필터 영역 -->
      <SearchWrap
        :search-lists="searchLists"
        :visible="isFilterVisible"
        @click-event="OnSearchPage"
        @click-away="isFilterVisible = false"
      />
      <!-- //검색 필터 영역 -->
    </div>
    <!-- list-top End -->

    <!-- 검색 결과 리스트 -->
    <div class="default-cell">
      <SearchList
        :th-datas="thDatas"
        :result-lists="resultLists"
        :result-index="1"
        :result-key="[0, 1, 4, 6]"
        @view-open="EditOpen"
      />

      <Pagination
        :items="resultLists"
        :item-cnt="records"
        :page-size="size"
        :initial-page="currentPage"
        ref="pagination"
        @changePage="OnChangePage"
        @changeSize="OnChangeSize"
      />
    </div>
    <!-- //검색 결과 리스트 -->

    <!-- side-view -->
    <EditSide
      title="서비스속성확장상세"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <CreateSide
      title="서비스속성등록"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.createSideOpen}"
      @create-event="Create"
    />
    <!-- //side-view -->
  </div>
</template>

<script>
  import PageTitle from '@/components/common/PageTitle.vue'
  import BtagButton from '@/components/button/BtagButton.vue'
  // import ExcelButton from '@/components/button/ExcelButton.vue'
  import Input from '@/components/form/input.vue'
  import FilterButton from '@/components/button/FilterButton.vue'
  import SearchWrap from '@/components/searchBox/SearchWrap.vue'
  import SearchList from '@/components/search_list/SearchList.vue'
  import EditSide from '@/components/sideBox/EditSide.vue'
  import CreateSide from '@/components/sideBox/CreateSide.vue'
  import Pagination from '@/components/list_component/Pagination.vue'

  export default {
    name: 'UnitServiceList',
    components: {
      PageTitle,
      BtagButton,
      // ExcelButton,
      Input,
      FilterButton,
      SearchWrap,
      SearchList,
      EditSide,
      CreateSide,
      Pagination
    },
    data: function () {
      return {
        currentPage: null,
        size: null,
        records: null,
        keywordSmart: null,
        isFilterVisible: false,
        searchLists: [
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '속성ID',
            name: 'atribId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '속성명',
            name: 'atribNm',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '메뉴유형',
            name: 'entityType',
            type: 'select',
            grid: 'row-3',
            value: '',
            options: [
              {
                name: '전체',
                value: ''
              },
              {
                name: '사용자',
                value: '01'
              },
              {
                name: '작업구역',
                value: '02'
              },
              {
                name: '설비',
                value: '03'
              },
              {
                name: '작업',
                value: '04'
              }
            ]
          },
          {
            label: '등록자ID',
            name: 'cretrId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '최근수정자ID',
            name: 'amdrId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '등록일시',
            name: 'CretDt',
            type: 'date',
            grid: 'row-1',
            from_value: '',
            to_value: ''
          },
          {
            label: '최근수정일시',
            name: 'AmdDt',
            type: 'date',
            grid: 'row-1',
            from_value: '',
            to_value: ''
          }
        ],
        thDatas: [
          '서비스ID',
          '속성ID',
          '속성명',
          '메뉴유형',
          '등록자ID',
          '최근수정자ID',
          '등록일시',
          '최근수정일시'
        ],
        resultLists: [],
        viewDatas: [
          {
            label: '고객ID',
            name: 'custId',
            type: 'customer',
            ignore: true,
            readonly: false,
            isCreateReadOnly: false,
            onlyView: false,
            align: 'left',
            value: null,
            onChange: this.OnChangeCustId
          },
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'select',
            required: true,
            readonly: true,
            onlyView: false,
            value: '',
            options: []
          },
          {
            label: '속성ID(영문)',
            name: 'atribId',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: ''
          },
          {
            label: '속성ID(한글)',
            name: 'atribNm',
            type: 'text',
            required: true,
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '메뉴유형',
            name: 'entityType',
            type: 'select',
            readonly: false,
            onlyView: false,
            value: null,
            options: [
              {
                name: '사용자',
                value: '01'
              },
              {
                name: '작업구역',
                value: '02'
              },
              {
                name: '설비',
                value: '03'
              },
              {
                name: '작업',
                value: '04'
              }
            ]
          },
          {
            label: '속성값유형',
            name: 'atribValType',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '속성기본값',
            name: 'atribDefltVal',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '속성값범위',
            name: 'atribValScope',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '속성값설명',
            name: 'atribValDesc',
            type: 'textarea',
            min: 2,
            max: 1000,
            readonly: false,
            onlyView: false,
            value: null
          }
        ]
      }
    },
    created () {
      this.FetchData()
    },
    methods: {
      async FetchData () {
        await this.GetList()
        await this.$nextTick() // required for IE
        this.$refs.pagination.SetPage(this.currentPage, false)
      },
      GetParams () {
        const params = Object.fromEntries([
          ...this.searchLists.filter(x => !['date'].includes(x.type)).map(x => [x.name, x.value || null]),
          ...this.searchLists.filter(x => x.type === 'date').flatMap(x => [
            [`st${x.name[0].toUpperCase()}${x.name.slice(1)}`, x.from_value ? `${x.from_value}T00:00:00.000` : null],
            [`fns${x.name[0].toUpperCase()}${x.name.slice(1)}`, x.to_value ? `${x.to_value}T23:59:59.999` : null]
          ])
        ])

        params.size = this.size || 20
        params.page = this.currentPage || 1

        return params
      },
      GetDetails () {
        const list = this.viewDatas.filter(x => !x.ignore)

        const details = Object.fromEntries([
          ...list.filter(x => !['empty'].includes(x.type)).map(x => [x.name, (x.value === '-') ? null : x.value])
        ])

        return details
      },
      async GetList () {
        try {
          this.$store.commit('progressComponentShow')

          const params = this.GetParams()
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          let res = []
          if (this.keywordSmart) {
            res = await this.$http
              .get(this.$BASEURL + '/service/expansion/smart', {
                params: {
                  input: this.keywordSmart,
                  ...this.pick(params, ['page', 'size'])
                },
                headers
              })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          } else {
            res = await this.$http
              .get(this.$BASEURL + '/service/expansion', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            data.svcId,
            data.atribId, // index
            data.atribNm,
            { '01': '사용자관리', '02': '작업구역관리', '03': '설비관리', '04': '작업관리' }[data.entityType],
            data.cretrId,
            data.amdrId,
            data.cretDt ? this.FormatDate(data.cretDt) : null,
            data.amdDt ? this.FormatDate(data.amdDt) : null
          ].map(x => x || '-'))
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      },
      async CreateOpen () {
        this.viewDatas.forEach(data => {
          if (data.name === 'custId') {
            data.value = null
            setTimeout(() => {
              if (['03', '04'].includes(this.auth)) {
                data.readonly = true
                data.isCreateReadOnly = true
                data.value = this.user.cust_id
              }
            }, 0)
          } else if (data.type === 'select' && data.name === 'svcId') {
            data.options = []
            data.value = null
          } else {
            data.value = ''
          }
        })

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id, info) {
        try {
          const params = {
            atribId: id,
            svcId: this.user.svc_id || info[0]
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
            .get(this.$BASEURL + '/service/expansion', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.viewDatas.forEach(item => {
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else if (item.name === 'svcId') {
              item.options = [{ name: value, value }]
              item.value = value
            } else if (item.name === 'custId') {
              item.value = null
              setTimeout(() => {
                item.value = value
                if (['03', '04'].includes(this.auth)) {
                  item.readonly = true
                  item.isCreateReadOnly = true
                }
              }, 0)
            } else {
              item.value = value || (item.readonly ? '-' : '')
            }
          })

          this.$store.commit('EditSideComponentOpen')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      async Create () {
        try {
          if (!confirm('정말로 등록하시겠습니까?')) {
            return
          }

          if (!this.Requirement()) {
            return
          }

          const params = this.GetDetails()
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          params.cretrUuid = this.user.user_uuid

          await this.$http
            .post(this.$BASEURL + '/service/expansion', params, { headers })
            .then(this.NormalOrError)

          this.FetchData()
          alert('등록 완료')
          this.$store.commit('CreateSideComponentClose')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      async Edit () {
        try {
          if (!confirm('정말로 수정하시겠습니까?')) {
            return
          }

          if (!this.Requirement()) {
            return
          }

          const params = this.GetDetails()
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          params.amdrUuid = this.user.user_uuid

          await this.$http
            .put(this.$BASEURL + '/service/expansion', params, { headers })
            .then(this.NormalOrError)

          this.FetchData()
          alert('수정 완료')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      async Delete () {
        try {
          if (!confirm('정말 삭제하시겠습니까?')) {
            return
          }

          const params = this.GetDetails()
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          await this.$http
            .delete(this.$BASEURL + '/service/expansion', {
              data: this.pick(params, [
                'entityType', 'atribId'
              ]),
              headers
            })
            .then(this.NormalOrError)

          this.FetchData()
          alert('삭제 완료')
          this.$store.commit('EditSideComponentClose')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      async OnChangeCustId (custId) {
        try {
          this.$store.commit('progressComponentShow')

          const params = {
            custId
          }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          const res0 = await this.$http
            .get(this.$BASEURL + '/service', {
              params,
              headers
            })
            .then(this.NormalOrError)
            .then(res => res.data.data)

          const options = res0.map(x => ({ name: `${x.svcNm || ''}(${x.svcId})`, value: x.svcId }))
          const select = this.viewDatas.find(x => x.name === 'svcId')
          select.options = options
          select.value = (this.FirstData(options) || {}).value || null
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      }
    }
  }
</script>

<style scoped>
</style>
