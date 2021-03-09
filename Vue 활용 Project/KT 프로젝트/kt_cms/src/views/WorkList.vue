<template>
  <div>
    <PageTitle
      title="작업"
      info="새로운 작업을 등록하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="작업등록"
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
      title="작업상세"
      :view-datas="this.$store.state.editSideOpen ? viewDatas : []"
      :class="{'open': this.$store.state.editSideOpen}"
      :restore-count="restoreCount"
      @edit-event="Edit"
      @delete-event="Delete"
      @restoreButtonClick="restore"
    />
    <CreateSide
      title="작업등록"
      :view-datas="this.$store.state.createSideOpen ? viewDatas : []"
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
    name: 'WorkList',
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
            label: '작업ID',
            name: 'wrkId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '작업명',
            name: 'wrkNm',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '작업생성자ID',
            name: 'wrkCretrId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '설비ID',
            name: 'facId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '현장작업자ID',
            name: 'spotWrkrId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '전문가ID',
            name: 'xpertId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '작업진행도',
            name: 'wrkTrtSttusCd',
            type: 'select',
            grid: 'row-3',
            value: null,
            options: [
              {
                name: '전체',
                value: null
              },
              {
                name: '준비중',
                value: '01'
              },
              {
                name: '진행중',
                value: '02'
              },
              {
                name: '완료',
                value: '03'
              },
              {
                name: '지연',
                value: '04'
              }
            ]
          },
          {
            label: '작업중요도',
            name: 'wrkEmgMain',
            type: 'select',
            grid: 'row-3',
            value: null,
            options: [
              {
                name: '전체(일반,긴급,주요)',
                value: null
              },
              {
                name: '긴급/주요',
                value: '01'
              },
              {
                name: '긴급',
                value: '02'
              },
              {
                name: '주요',
                value: '03'
              },
              {
                name: '일반',
                value: '04'
              }
            ]
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
            label: '시작예정일시',
            name: 'date1',
            type: 'datetimes',
            grid: 'row-1',
            from_value: null,
            to_value: null
          },
          {
            label: '종료예정일시',
            name: 'date2',
            type: 'datetimes',
            grid: 'row-1',
            from_value: null,
            to_value: null
          },
          {
            label: '최근작업일시',
            name: 'date3',
            type: 'datetimes',
            grid: 'row-1',
            from_value: null,
            to_value: null
          }
        ],
        thDatasA: [
          '서비스ID',
          {
            label: '작업ID',
            style: {
              width: '11%'
            }
          },
          '작업명',
          '작업생성자ID',
          '설비ID',
          '현장작업자ID',
          '전문가ID',
          '작업진행도'
        ],
        thDatasB: [
          '시작예정일시',
          '종료예정일시',
          '최근작업일시'
        ],
        thDatas: [
          '서비스ID',
          '작업ID',
          '작업명',
          '작업생성자ID',
          '설비ID',
          '현장작업자ID',
          '전문가ID',
          '작업진행도',
          '시작예정일시',
          '종료예정일시',
          '최근작업일시'
        ],
        resultLists: [],
        viewDatas: [
          {
            label: '고객ID',
            name: 'custId',
            type: 'customer',
            readonly: true,
            isCreateReadOnly: false,
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
            options: [],
            onChange: (value) => {
              setTimeout(() => {
                this.viewDatas.find(x => x.name === 'facId').svcId = value
                this.viewDatas.find(x => x.name === 'spotWrkrUuid').svcId = value
                this.viewDatas.find(x => x.name === 'xpertUuid').svcId = value
              }, 0)
            }
          },
          {
            label: '작업ID',
            name: 'wrkId',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: null
          },
          {
            label: '작업명',
            name: 'wrkNm',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '작업중요도',
            name: 'wrkEmgMain',
            type: 'select',
            onlyView: false,
            required: false,
            value: '',
            options: [
              {
                name: '긴급/주요',
                value: '01'
              },
              {
                name: '긴급',
                value: '02'
              },
              {
                name: '주요',
                value: '03'
              },
              {
                name: '일반',
                value: '04'
              }
            ]
          },
          {
            label: '설비ID',
            name: 'facId',
            type: 'equipment',
            required: false,
            readonly: true,
            onlyView: false,
            value: '',
            svcId: ''
          },
          {
            label: '작업생성자ID',
            name: 'wrkCretrId',
            type: 'text',
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            type: 'empty',
            onlyView: true
          },
          {
            label: '작업시작예정일시',
            name: 'wrkStPamDt',
            type: 'datetime',
            required: false,
            readonly: false,
            onlyView: false,
            value: '',
            minDate: '',
            maxDate: '',
            onSelect: (value) => {
              const found = this.viewDatas.find(x => x.name === 'wrkFnsPamDt')
              if (found) {
                found.minDate = value
              }
            }
          },
          {
            label: '작업종료예정일시',
            name: 'wrkFnsPamDt',
            type: 'datetime',
            required: false,
            readonly: false,
            onlyView: false,
            value: '',
            minDate: '',
            maxDate: '',
            onSelect: (value) => {
              const found = this.viewDatas.find(x => x.name === 'wrkStPamDt')
              if (found) {
                found.maxDate = value
              }
            }
          },
          {
            label: '작업자배치',
            name: 'spotWrkrUuid',
            indexName: 'userUuid',
            indexValue: null,
            type: 'worker',
            align: 'left',
            readonly: true,
            onlyView: false,
            value: null,
            svcId: null
          },
          {
            label: '전문가배치',
            name: 'xpertUuid',
            type: 'expert',
            required: false,
            readonly: true,
            onlyView: false,
            single: true,
            value: null,
            svcId: null
          },
          {
            label: '작업 설명',
            name: 'wrkDesc',
            type: 'textarea',
            min: 2,
            max: 2000,
            required: false,
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '',
            name: 'expnsnAtribVal',
            type: 'expension',
            required: false,
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '최근작업처리일시',
            name: 'recntWrkTrtDt',
            type: 'text',
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
          ...this.searchLists.filter(x => !['date'].includes(x.type) && !['wrkEmgMain'].includes(x.name)).map(x => [x.name, x.value || null]),
          ...this.searchLists.filter(x => x.type === 'date').flatMap(x => [
            [`st${x.name[0].toUpperCase()}${x.name.slice(1)}`, x.from_value ? `${x.from_value}T00:00:00.000` : null],
            [`fns${x.name[0].toUpperCase()}${x.name.slice(1)}`, x.to_value ? `${x.to_value}T23:59:59.999` : null]
          ]),
          ...this.searchLists.filter(x => x.name === 'wrkEmgMain').flatMap(x => [
            ['emgWrkYn', x.value ? { '01': 'Y', '02': 'Y' }[x.value] || 'N' : null],
            ['mainWrkYn', x.value ? { '01': 'Y', '03': 'Y' }[x.value] || 'N' : null]
          ])
        ])

        params.size = this.size || 20
        params.page = this.currentPage || 1

        return params
      },
      GetDetails () {
        const list = this.viewDatas.filter(x => !x.ignore)

        const details = Object.fromEntries([
          ...list.filter(x => !['empty', 'datetime'].includes(x.type) && !['wrkEmgMain'].includes(x.name)).map(x => [x.name, (x.value === '-') ? null : x.value]),
          ...list.filter(x => x.name === 'wrkEmgMain').flatMap(x => [
            ['emgWrkYn', x.value ? { '01': 'Y', '02': 'Y' }[x.value] || 'N' : null],
            ['mainWrkYn', x.value ? { '01': 'Y', '03': 'Y' }[x.value] || 'N' : null]
          ]),
          ...list.filter(x => x.type === 'datetime').flatMap(x => [
            [x.name, `${x.value}:00.000`.split(' ').join('T')]
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
                  entityType: '04',
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
              .get(this.$BASEURL + '/work/smart', {
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
              .get(this.$BASEURL + '/work', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            /* A */
            data.svcId,
            {
              type: 'tag',
              index: data.wrkId, // index
              tag: (data.emgWrkYn === 'Y' ? `<span class="table-label red-label">긴급</span>` : '') +
                (data.mainWrkYn === 'Y' ? `<span class="table-label yellow-label">주요</span>` : '')
            },
            data.wrkNm,
            data.wrkCretrInfo && data.wrkCretrInfo.userId, // VO
            data.facId,
            data.spotWrkrInfo && data.spotWrkrInfo.userId, // VO
            data.xpertInfo && data.xpertInfo.userId, // VO
            { '01': '준비중', '02': '진행중', '03': '완료', '04': '지연' }[data.wrkTrtSttusCd],
            /* 확장속성 */
            ...res0.map(x => {
              if (!data.expnsnAtribVal) {
                return '-'
              }

              return (JSON.parse(data.expnsnAtribVal.value))[x.atribId] || '-'
            }),
            /* B */
            data.wrkStPamDt ? this.FormatDate(data.wrkStPamDt) : null,
            data.wrkFnsPamDt ? this.FormatDate(data.wrkFnsPamDt) : null,
            data.recntWrkTrtDt
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
          } else if (data.type === 'expension') {
            data.value = this.serviceExpensionLists
          } else if (data.type === 'worker') {
            data.value = null
          } else if (data.type === 'expert') {
            data.value = null
          } else {
            data.value = null
          }
        })

        this.viewDatas[2].value = this.GenaratorId()

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id, info) {
        const svcId = info[0]

        try {
          this.$store.commit('progressComponentShow')

          const params = {
            svcId: svcId,
            wrkId: id
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
            .get(this.$BASEURL + '/work', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          console.log(res)

          this.viewDatas.forEach(item => {
            console.log({ ...item })
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
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
            } else if (item.type === 'equipment') {
              item.svcId = null
              item.value = null
              setTimeout(() => {
                item.svcId = res.svcId
                item.value = value
              }, 0)
            } else if (item.type === 'worker') {
              item.svcId = null
              item.value = null
              setTimeout(() => {
                item.svcId = res.svcId
                item.value = res.spotWrkrUuid
              }, 0)
            } else if (item.type === 'expert') {
              item.svcId = null
              item.value = null
              setTimeout(() => {
                item.svcId = res.svcId
                item.value = res.xpertUuid
              }, 0)
            } else if (item.name === 'wrkCretrId') {
              item.value = (res.wrkCretrInfo ? res.wrkCretrInfo.userId : null) || null
            } else if (item.name === 'wrkEmgMain') {
              if (res.emgWrkYn === 'Y' && res.mainWrkYn === 'Y') {
                item.value = '01'
              } else if (res.emgWrkYn === 'Y') {
                item.value = '02'
              } else if (res.mainWrkYn === 'Y') {
                item.value = '03'
              } else {
                item.value = '04'
              }
            } else if (item.type === 'datetime') {
              item.value = null
              if (value) {
                item.value = this.$moment(value).format('YYYY-MM-DD HH:mm')
              }
            } else {
              item.value = value || (item.readonly ? '-' : '')
            }
          })

          this.$store.commit('EditSideComponentOpen')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
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

          params.wrkCretrUuid = this.user.user_uuid

          if (params.expnsnAtribVal) {
            params.expnsnAtribVal = Object.fromEntries(params.expnsnAtribVal.map(x => [x.atribId, x.value]))
          }

          await this.$http
            .post(this.$BASEURL + '/work', params, { headers })
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

          if (params.expnsnAtribVal) {
            params.expnsnAtribVal = Object.fromEntries(params.expnsnAtribVal.map(x => [x.atribId, x.value]))
          }

          await this.$http
            .put(this.$BASEURL + '/work', params, { headers })
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
            .delete(this.$BASEURL + '/work', {
              data: this.pick(params, [
                'wrkId'
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
