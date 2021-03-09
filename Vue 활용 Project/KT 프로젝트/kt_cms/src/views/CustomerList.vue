<template>
  <div>
    <PageTitle
      title="고객사"
      info="고객을 등록하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="고객사등록"
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
        @view-open="EditOpen"
        @row-button-click="rowButtonclick"
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
      :title="`고객상세`"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <CreateSide
      title="고객등록"
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
        isFilterVisible: false,
        currentPage: null,
        size: null,
        records: null,
        keywordSmart: null,
        editSideTitle: '',
        searchLists: [
          {
            label: '고객ID',
            name: 'custId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '고객명',
            name: 'custNm',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '고객상태',
            name: 'custSttusCd',
            type: 'select',
            grid: 'row-3',
            value: '',
            options: [
              {
                name: '전체',
                value: ''
              },
              {
                name: '정상',
                value: '01'
              },
              {
                name: '정지',
                value: '02'
              },
              {
                name: '탈퇴',
                value: '03'
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
          '고객ID',
          '고객명',
          '상태',
          '고객주소',
          '고객전화번호',
          '고객이메일',
          '등록자ID',
          '최근수정자ID',
          '등록일시',
          '최근수정일시'
        ],
        resultLists: [],
        viewDataModelId: null,
        viewDataModels: {
          'write': [
            {
              label: '고객ID',
              name: 'custId',
              type: 'text',
              required: true,
              readonly: true,
              onlyView: false,
              value: ''
            },
            {
              label: '고객명',
              name: 'custNm',
              type: 'text',
              readonly: false,
              onlyView: false,
              value: ''
            },
            {
              label: '고객주소',
              name: 'custAdr',
              type: 'text',
              wide: true,
              readonly: false,
              onlyView: false,
              value: ''
            },
            {
              label: '고객이메일',
              name: 'custEmail',
              type: 'text',
              readonly: false,
              onlyView: false,
              value: ''
            },
            {
              label: '고객전화번호',
              name: 'custTelNo',
              type: 'text',
              readonly: false,
              onlyView: false,
              value: ''
            },
            {
              label: '등록자ID',
              name: 'cretrId',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: true,
              value: ''
            },
            {
              label: '등록일시',
              name: 'cretDt',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: true,
              value: ''
            },
            {
              label: '최근수정자ID',
              name: 'amdrId',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: true,
              value: ''
            },
            {
              label: '최근수정일시',
              name: 'amdDt',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: true,
              value: ''
            }
          ]
        },
        viewDatas: []
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
              .get(this.$BASEURL + '/customer/smart', {
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
              .get(this.$BASEURL + '/customer', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            data.custId, // index
            data.custNm,
            { '01': '정상', '02': '정지', '03': '탈퇴' }[data.custSttusCd],
            `@v-html:<div class="table-list-ellipsis">${data.custAdr || '-'}</div>`,
            data.custTelNo,
            data.custEmail,
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
        this.viewDataModelId = 'write'
        this.viewDatas = this.viewDataModels[this.viewDataModelId]

        this.viewDatas.forEach(data => {
          data.value = ''
        })

        this.viewDatas[0].value = this.GenaratorId()

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id) {
        this.viewDataModelId = 'write'
        this.viewDatas = this.viewDataModels[this.viewDataModelId]

        try {
          const params = {
            custId: id
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
            .get(this.$BASEURL + '/customer', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.viewDatas.forEach(item => {
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else {
              item.value = value || (item.readonly ? '-' : '')
            }
          })

          this.editSideTitle = res.custNm
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
            .post(this.$BASEURL + '/customer', params, { headers })
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
            .put(this.$BASEURL + '/customer', params, { headers })
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
            .delete(this.$BASEURL + '/customer', {
              data: this.pick(params, [
                'custId'
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
      async rowButtonclick (info) {
        this.viewDataModelId = 'service'
        this.viewDatas = this.viewDataModels[this.viewDataModelId]

        const params = {
          custId: info.data.custId
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
          .get(this.$BASEURL + '/customer', { params, headers })
          .then(this.NormalOrError)
          .then(res => res.data.data)

        const dataGridSetting = {
          cols: [
            {
              label: '단위서비스ID',
              name: 'unitSvcId'
            },
            {
              label: '서비스명',
              name: 'svcNm'
            },
            {
              label: '서비스상태',
              name: 'svcSttusCd',
              type: 'select',
              readonly: true,
              options: [
                {
                  label: '활성',
                  value: '01'
                },
                {
                  label: '비활성',
                  value: '02'
                },
                {
                  label: '삭제',
                  value: '03'
                }
              ]
            },
            {
              label: '서비스버전',
              name: 'svcVer'
            },
            {
              label: '서비스설명',
              name: 'svcDesc'
            },
            {
              label: '서비스이동',
              type: 'button',
              text: '이동'
            }
          ],
          rows: res.map(row => ({
            ...row,
            ...{
              checked: false
            }
          })),
          index: 'svcId',
          onRowButtonClick: (row) => {
            console.log(row)
            alert('정보없음')
          }
        }

        this.viewDatas.forEach(data => {
          if (data.type === 'datagrid') {
            data.setting = dataGridSetting
          } else {
            data.value = ''
          }
        })

        this.editSideTitle = '가입 서비스'
        this.$store.commit('EditSideComponentOpen')
      }
    }
  }
</script>

<style scoped>
</style>
