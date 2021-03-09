<template>
  <div>
    <PageTitle
      title="서비스"
      info="새로운 서비스를 등록하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="서비스등록"
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
      title="서비스 상세"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <CreateSide
      title="서비스 등록"
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
    name: 'ServiceList',
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
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '단위서비스ID',
            name: 'unitSvcId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '서비스명',
            name: 'svcNm',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '서비스상태',
            name: 'svcSttusCd',
            type: 'select',
            grid: 'row-3',
            value: null,
            options: [
              {
                name: '전체',
                value: null
              },
              {
                name: '활성',
                value: '01'
              },
              {
                name: '비활성',
                value: '02'
              },
              {
                name: '삭제',
                value: '03'
              }
            ]
          },
          {
            label: '청약명',
            name: 'subsNm',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '청약상태',
            name: 'subsSttusCd',
            type: 'select',
            grid: 'row-3',
            value: null,
            options: [
              {
                name: '전체',
                value: null
              },
              {
                name: '활성',
                value: '01'
              },
              {
                name: '비활성',
                value: '02'
              },
              {
                name: '오류',
                value: '03'
              }
            ]
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
          },
          {
            label: '청약일시',
            name: 'subsDt',
            type: 'date',
            grid: 'row-1',
            from_value: null,
            to_value: null
          }
        ],
        thDatas: [
          '서비스ID',
          '서비스명',
          '고객ID',
          '단위서비스ID',
          '서비스상태',
          '서비스버전',
          '청약명',
          '청약일시',
          '청약상태',
          '등록자ID',
          '최근수정자ID',
          '등록일시',
          '최근수정일시'
        ],
        resultLists: [],
        viewDataModels: {
          'create': [
            {
              label: '관리자ID',
              name: 'admrUuid',
              indexName: 'userUuid',
              type: 'user',
              readonly: true,
              onlyView: false,
              align: 'left',
              onlyAdmin: true,
              value: null,
              preLoading: true
            },
            {
              label: '고객ID',
              name: 'custId',
              type: 'customer',
              readonly: false,
              onlyView: false,
              align: 'right',
              value: null,
              preLoading: true
            },
            {
              label: '서비스 정보',
              name: 'service',
              type: 'service',
              readonly: false,
              onlyNew: true,
              height: '30vmax',
              useAdd: false,
              setting: {
                template: [
                  {
                    label: '서비스ID',
                    name: 'svcId',
                    type: 'text',
                    required: true,
                    readonly: true
                  },
                  {
                    type: 'empty'
                  },
                  {
                    label: '단위서비스',
                    name: 'unitSvcId',
                    type: 'unitservice',
                    required: true,
                    readonly: false,
                    wide: true,
                    preLoading: true
                  },
                  {
                    label: '청약명',
                    name: 'subsNm',
                    type: 'text',
                    required: true,
                    readonly: true
                  },
                  {
                    label: '청약일시 (YYYY-MM-DD HH:mm:ss)',
                    name: 'subsDt',
                    type: 'text',
                    required: true,
                    readonly: true
                  },
                  {
                    label: '청약상태',
                    name: 'subsSttusCd',
                    type: 'select',
                    readonly: false,
                    options: [
                      {
                        name: '완료',
                        value: '01'
                      },
                      {
                        name: '진행중',
                        value: '02'
                      },
                      {
                        name: '오류',
                        value: '03'
                      }
                    ]
                  },
                  {
                    label: '서비스상태',
                    name: 'svcSttusCd',
                    type: 'select',
                    readonly: false,
                    options: [
                      {
                        name: '활성',
                        value: '01'
                      },
                      {
                        name: '비활성',
                        value: '02'
                      },
                      {
                        name: '삭제',
                        value: '03'
                      }
                    ]
                  },
                  {
                    label: '서비스명',
                    name: 'svcNm',
                    type: 'text',
                    readonly: false
                  },
                  {
                    label: '서비스버전',
                    name: 'svcVer',
                    type: 'text',
                    readonly: false
                  },
                  {
                    label: '서비스설명',
                    name: 'svcDesc',
                    type: 'text',
                    readonly: false,
                    wide: true
                  }
                ]
              },
              value: [{}]
            }
          ],
          'edit': [
            {
              label: '관리자ID',
              name: 'admrUuid',
              indexName: 'userUuid',
              type: 'user',
              readonly: true,
              onlyView: false,
              align: 'left',
              value: null
            },
            {
              label: '고객ID',
              name: 'custId',
              type: 'customer',
              readonly: true,
              onlyView: false,
              align: 'right',
              value: null
            },
            {
              label: '서비스ID',
              name: 'svcId',
              type: 'text',
              required: true,
              readonly: true,
              onlyView: true,
              align: 'left',
              value: null
            },
            {
              label: '단위서비스',
              name: 'unitSvcId',
              type: 'unitservice',
              required: true,
              readonly: false,
              wide: true,
              value: null
            },
            {
              label: '청약명',
              name: 'subsNm',
              type: 'text',
              required: true,
              readonly: true,
              onlyView: true,
              value: null
            },
            {
              label: '청약일시',
              name: 'subsDt',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: true,
              value: null
            },
            {
              label: '청약상태',
              name: 'subsSttusCd',
              type: 'select',
              readonly: false,
              onlyView: true,
              value: null,
              options: [
                {
                  name: '완료',
                  value: '01'
                },
                {
                  name: '진행중',
                  value: '02'
                },
                {
                  name: '오류',
                  value: '03'
                }
              ]
            },
            {
              label: '서비스상태',
              name: 'svcSttusCd',
              type: 'select',
              readonly: false,
              onlyView: true,
              value: null,
              options: [
                {
                  name: '활성',
                  value: '01'
                },
                {
                  name: '비활성',
                  value: '02'
                },
                {
                  name: '삭제',
                  value: '03'
                }
              ]
            },
            {
              label: '서비스명',
              name: 'svcNm',
              type: 'text',
              readonly: false,
              onlyView: true,
              value: null
            },
            {
              label: '서비스버전',
              name: 'svcVer',
              type: 'text',
              readonly: false,
              onlyView: true,
              value: null
            },
            {
              label: '서비스 설명',
              name: 'svcDesc',
              type: 'textarea',
              min: 2,
              max: 2000,
              readonly: false,
              onlyView: true,
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
        },
        viewDataModelId: null,
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
              .get(this.$BASEURL + '/service/smart', {
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
              .get(this.$BASEURL + '/service', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            data.svcId, // index
            data.svcNm,
            data.custId,
            data.unitSvcId,
            { '01': '활성', '02': '비활성', '03': '삭제' }[data.svcSttusCd],
            data.svcVer,
            data.subsNm,
            data.subsDt ? this.FormatDate(data.subsDt) : null,
            { '01': '완료', '02': '진행중', '03': '오류' }[data.subsSttusCd],
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
        this.viewDataModelId = 'create'
        this.viewDatas = [...this.viewDataModels[this.viewDataModelId]]

        this.viewDatas.forEach(data => {
          if (data.name === 'service') {
            data.value = [{}]
          } else {
            data.value = ''
          }
        })

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id) {
        try {
          this.viewDataModelId = 'edit'
          this.viewDatas = [...this.viewDataModels[this.viewDataModelId]]

          const params = {
            svcId: id
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
            .get(this.$BASEURL + '/service', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.viewDatas.forEach(item => {
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else if (item.type === 'admrUuid') {
              item.value = null
              setTimeout(() => {
                item.value = value
              }, 0)
            } else if (item.type === 'unitservice') {
              item.value = null
              setTimeout(() => {
                item.value = value
              }, 0)
            } else {
              item.value = value || (item.readonly ? '-' : null)
            }
          })

          this.editSideTitle = res.subsNm
          this.$store.commit('EditSideComponentOpen')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      async Create () {
        try {
          this.$store.commit('progressComponentShow')

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

          if (params.service.every(item => Object.values(item).every(x => !x))) {
            // 모든 폼이 빈값이면 무시
            alert('등록할 서비스 정보가 없습니다')
            return
          }

          for (const [index, service] of params.service.entries()) {
            // 특정 폼이 빈값이면 무시
            if (!Object.values(service).every(x => !x)) {
              if (!service.svcId) {
                alert(`${index + 1}번째 항목의 서비스ID를 입력해주세요!`)
                return
              }

              if (!service.unitSvcId) {
                alert(`${index + 1}번째 항목의 단위서비스ID를 입력해주세요!`)
                return
              }

              if (!service.subsNm) {
                alert(`${index + 1}번째 항목의 청약명을 입력해주세요!`)
                return
              }

              if (!service.subsDt && !this.CheckDate(service.subsDt)) {
                alert(`${index + 1}번째 항목의 청약일시를 'YYYY-MM-DD HH:mm:ss' 포멧으로 입력해주세요!`)
                return
              }

              const params1 = {
                ...service,
                ...{
                  subsDt: this.UnFormatDate(service.subsDt),
                  custId: params.custId || null,
                  admrUuid: params.admrUuid,
                  cretrUuid: params.cretrUuid
                }
              }
              const headers1 = {
                'X-Svc-Id': params1.svcId
              }

              delete params1.svcId

              await this.$http
                .post(this.$BASEURL + '/service', params1, {
                  headers: headers1
                })
                .then(this.NormalOrError)
            }
          }

          this.FetchData()
          alert('등록 완료')
          this.$store.commit('CreateSideComponentClose')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
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
            .put(this.$BASEURL + '/service', params, { headers })
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

          params.amdrUuid = this.user.user_uuid

          await this.$http
            .delete(this.$BASEURL + '/service', {
              data: {},
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
      }
    }
  }
</script>

<style scoped>
</style>
