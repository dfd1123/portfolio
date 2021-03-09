<template>
  <div>
    <PageTitle
      title="작업구역관리"
      info="작업구역을 관리하는 페이지 입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="작업구역 등록"
          style="vertical-align: top;"
          @click-event="CreateOpen"
        />
        <ExcelButton kind="upload" />
        <ExcelButton kind="download" />
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
        :result-key="[0, 1]"
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
      title="작업구역 상세보기"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      :restore-count="restoreCount"
      @edit-event="Edit"
      @delete-event="Delete"
      @restoreButtonClick="restore"
    />
    <CreateSide
      title="작업구역 등록"
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
  import ExcelButton from '@/components/button/ExcelButton.vue'
  import Input from '@/components/form/input.vue'
  import FilterButton from '@/components/button/FilterButton.vue'
  import SearchWrap from '@/components/searchBox/SearchWrap.vue'
  import SearchList from '@/components/search_list/SearchList.vue'
  import EditSide from '@/components/sideBox/EditSide.vue'
  import CreateSide from '@/components/sideBox/CreateSide.vue'
  import Pagination from '@/components/list_component/Pagination.vue'

  export default {
    name: 'ZoneList',
    components: {
      PageTitle,
      BtagButton,
      ExcelButton,
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
        isFilterVisible: false,
        currentPage: null,
        size: null,
        records: null,
        keywordSmart: null,
        tempViewDatas: null,
        restoreViewDatas: null,
        restoreCount: 0,
        restoreTimer: null,
        serviceExpensionLists: [],
        searchLists: [
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '작업구역ID',
            name: 'wrkZoneId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '구역명',
            name: 'wrkZoneNm',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '',
            name: '',
            controlName: 'expension0',
            type: 'selectinput',
            grid: 'row-3',
            hide: true,
            selectOptions: [],
            selectValue: null,
            inputValue: null
          },
          {
            label: '',
            name: '',
            controlName: 'expension1',
            type: 'selectinput',
            grid: 'row-3',
            hide: true,
            selectOptions: [],
            selectValue: null,
            inputValue: null
          },
          {
            label: '',
            name: '',
            controlName: 'expension2',
            type: 'selectinput',
            grid: 'row-3',
            hide: true,
            selectOptions: [],
            selectValue: null,
            inputValue: null
          },
          {
            label: '상위작업구역ID',
            name: 'upWrkZoneId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '등록자ID',
            name: 'cretrId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '최근수정자ID',
            name: 'amdrId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '등록일시',
            name: 'CretDt',
            type: 'date',
            grid: 'row-1',
            from_value: null,
            to_value: null
          },
          {
            label: '최근수정일시',
            name: 'AmdDt',
            type: 'date',
            grid: 'row-1',
            from_value: null,
            to_value: null
          }
        ],
        thDatasA: [
          '서비스ID',
          '작업구역ID',
          '작업구역명'
        ],
        thDatasB: [
          '등록자ID',
          '최근수정자ID',
          '등록일시',
          '최근수정일시'
        ],
        thDatas: [
          '서비스ID',
          '작업구역ID',
          '작업구역명',
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
            isCreateReadOnly: false,
            readonly: false,
            onlyView: false,
            align: 'left',
            value: null,
            onChange: this.OnChangeCustId,
            preLoading: true
          },
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'select',
            required: true,
            readonly: true,
            onlyView: false,
            value: null,
            options: []
          },
          {
            label: '작업구역ID',
            name: 'wrkZoneId',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: null
          },
          {
            label: '작업구역명',
            name: 'wrkZoneNm',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '위도,경도',
            name: 'location',
            type: 'location',
            readonly: false,
            onlyView: false,
            value: {
              loLatit: null,
              loLngit: null
            }
          },
          {
            label: '작업구역 설명',
            name: 'wrkZoneDesc',
            type: 'textarea',
            min: 2,
            max: 1000,
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '',
            name: 'expnsnAtribVal',
            type: 'expension',
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '등록자ID',
            name: 'cretrId',
            type: 'text',
            ignore: true,
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            label: '등록일시',
            name: 'cretDt',
            type: 'text',
            ignore: true,
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            label: '최근수정자ID',
            name: 'amdrId',
            type: 'text',
            min: 2,
            max: 2000,
            ignore: true,
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            label: '최근수정일시',
            name: 'amdDt',
            type: 'text',
            min: 2,
            max: null,
            ignore: true,
            readonly: true,
            onlyView: true,
            value: null
          }
        ]
      }
    },
    created () {
      this.FetchData()
    },
    destroyed () {
      clearTimeout(this.restoreTimer)
    },
    watch: {
      isEditSideOpen () {
        setTimeout(() => {
          if (this.isEditSideOpen) {
            this.tempViewDatas = this.cloneDeep(this.viewDatas)
          } else {
            if (this.tempViewDatas !== null && !this.isEqual(this.tempViewDatas.map(x => x.value), this.viewDatas.map(x => x.value))) {
              this.restoreViewDatas = this.cloneDeep(this.viewDatas)
              clearTimeout(this.restoreTimer)
              this.restoreTimer = setTimeout(() => this.restoreCountDown(), 1000)
              this.restoreCount = 10
            }
          }
        }, 0)
      }
    },
    methods: {
      async FetchData () {
        await this.GetList()
        await this.$nextTick() // required for IE
        this.$refs.pagination.SetPage(this.currentPage, false)
      },
      restoreCountDown () {
        this.restoreCount -= 1
        if (this.restoreCount > 0) {
          this.restoreTimer = setTimeout(() => this.restoreCountDown(), 1000)
        } else {
          this.restoreTimer = null
          this.restoreViewDatas = null
        }
      },
      restore () {
        clearTimeout(this.restoreTimer)
        this.restoreTimer = null
        this.restoreCount = 0
        this.tempViewDatas = this.cloneDeep(this.restoreViewDatas)

        const a = Object.fromEntries(this.restoreViewDatas.map(x => [x.name, x.value]))
        this.viewDatas.forEach(item => {
          item.value = a[item.name]
        })

        this.restoreViewDatas = null
      },
      GetParams () {
        const params = Object.fromEntries([
          ...this.searchLists.filter(x => !['date'].includes(x.type)).map(x => [x.name, (x.value === '-') ? null : x.value]),
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
          ...list.filter(x => !['location', 'empty'].includes(x.type)).map(x => [x.name, (x.value === '-') ? null : x.value]),
          ...list.filter(x => x.type === 'location').flatMap(x => [
            ['wrkZoneLoLatit', x.value.loLatit ? x.value.loLatit : 0],
            ['wrkZoneLoLngit', x.value.loLngit ? x.value.loLngit : 0]
          ])
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

          this.searchLists.filter(x => x.controlName && x.controlName.startsWith('expension')).forEach(x => {
            if (x.selectValue && x.inputValue) {
              params['expnsnVal_' + x.selectValue] = x.inputValue
            }
          })

          let res0 = []
          if (headers['X-Svc-Id']) {
            res0 = await this.$http
              .get(this.$BASEURL + '/service/expansion', {
                params: {
                  ...params,
                  entityType: '02',
                  page: null,
                  size: null
                },
                headers
              })
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.serviceExpensionLists = res0

          const serviceCount = res0.length > 3 ? 3 : res0.length

          const e0 = this.searchLists.find(x => x.controlName === 'expension0')
          if (serviceCount > 0) {
            e0.selectOptions = [
              ...[{
                name: '전체',
                value: null
              }],
              ...res0.map(x => ({
                name: x.atribNm,
                value: x.atribId
              }))]
            e0.hide = false
          } else {
            e0.selectOptions = []
            e0.selectValue = null
            e0.inputValue = null
            e0.hide = true
          }

          const e1 = this.searchLists.find(x => x.controlName === 'expension1')
          if (serviceCount > 1) {
            e1.selectOptions = [
              ...[{
                name: '전체',
                value: null
              }],
              ...res0.map(x => ({
                name: x.atribNm,
                value: x.atribId
              }))]
            e1.hide = false
          } else {
            e1.selectOptions = []
            e1.selectValue = null
            e1.inputValue = null
            e1.hide = true
          }

          const e2 = this.searchLists.find(x => x.controlName === 'expension2')
          if (serviceCount > 2) {
            e2.selectOptions = [
              ...[{
                name: '전체',
                value: null
              }],
              ...res0.map(x => ({
                name: x.atribNm,
                value: x.atribId
              }))]
            e2.hide = false
          } else {
            e2.selectOptions = []
            e2.selectValue = null
            e2.inputValue = null
            e2.hide = true
          }

          this.thDatas = [
            ...this.thDatasA,
            ...res0.map(x => x.atribNm),
            ...this.thDatasB
          ]

          let res = []
          if (this.keywordSmart) {
            res = await this.$http
              .get(this.$BASEURL + '/zone/smart', {
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
              .get(this.$BASEURL + '/zone', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            /* A */
            data.svcId,
            data.wrkZoneId, // index
            data.wrkZoneNm,
            /* 확장속성 */
            ...res0.map(x => {
              if (!data.expnsnAtribVal) {
                return '-'
              }

              return (JSON.parse(data.expnsnAtribVal.value))[x.atribId] || '-'
            }),
            /* B */
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
          if (data.type === 'location') {
            data.value.loLatit = null
            data.value.loLngit = null
          } else if (data.type === 'expension') {
            data.value = this.serviceExpensionLists
          } else if (data.name === 'custId') {
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
            data.value = null
          }
        })

        this.viewDatas[2].value = this.GenaratorId()

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id, data) {
        const svcId = data[0]

        try {
          const params = {
            svcId: svcId,
            wrkZoneId: id
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
            .get(this.$BASEURL + '/zone', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.viewDatas.forEach(item => {
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else if (item.type === 'location') {
              item.value.loLatit = res['wrkZoneLoLatit'] || null
              item.value.loLngit = res['wrkZoneLoLngit'] || null
            } else if (item.type === 'expension') {
              item.value = null

              const values = !(value || {}).value ? [] : Object.entries(JSON.parse(value.value))
              item.value = this.serviceExpensionLists
                .map(item => {
                  const info = values.find(([key, value]) => key === item.atribId)
                  return {
                    atribId: item.atribId,
                    atribNm: item.atribNm ? item.atribNm : (info ? info[0] : null),
                    value: info ? info[1] : null
                  }
                })
                .filter((v, i, a) => a.findIndex(t => (t.atribId === v.atribId)) === i) // atribId 중복제거
            } else if (item.type === 'select' && item.name === 'svcId') {
              item.options = [
                {
                  name: value,
                  value: value
                }
              ]
              item.value = value
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

          if (params.expnsnAtribVal) {
            params.expnsnAtribVal = Object.fromEntries(params.expnsnAtribVal.map(x => [x.atribId, x.value]))
          }

          await this.$http
            .post(this.$BASEURL + '/zone', params, { headers })
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

          if (params.expnsnAtribVal) {
            params.expnsnAtribVal = Object.fromEntries(params.expnsnAtribVal.map(x => [x.atribId, x.value]))
          }

          await this.$http
            .put(this.$BASEURL + '/zone', params, { headers })
            .then(this.NormalOrError)

          this.tempViewDatas = null
          this.restoreViewDatas = null

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
            .delete(this.$BASEURL + '/zone', {
              data: this.pick(params, [
                'wrkZoneId'
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
            .get(this.$BASEURL + '/service', { params, headers })
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
