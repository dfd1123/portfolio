<template>
  <div>
    <PageTitle
      title="상품"
      info="새로운 단위서비스를 등록하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="상품 등록"
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
            v-model="searchLists[0].value"
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
      title="단위서비스 상세"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <CreateSide
      title="단위서비스 등록"
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
            label: '단위서비스ID',
            name: 'unitSvcId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '단위서비스명',
            name: 'unitSvcNm',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '서비스상태',
            name: 'oprtSttusCd',
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
        thDatas: [
          '단위서비스ID',
          '단위서비스명',
          '서비스상태',
          '버전',
          '등록자ID',
          '최근수정자ID',
          '등록일시',
          '최근수정일시'
        ],
        resultLists: [],
        viewDatas: [
          {
            label: '단위서비스ID',
            name: 'unitSvcId',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: null
          },
          {
            label: '단위서비스명',
            name: 'unitSvcNm',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '서비스상태',
            name: 'oprtSttusCd',
            type: 'select',
            readonly: false,
            onlyView: false,
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
            label: '버전',
            name: 'nwstSvcVer',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '서비스 설명',
            name: 'unitSvcDesc',
            type: 'textarea',
            min: 2,
            max: 2000,
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
              .get(this.$BASEURL + '/service/unit/smart', {
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
              .get(this.$BASEURL + '/service/unit', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            data.unitSvcId, // index
            data.unitSvcNm,
            Object.fromEntries([
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
            ].map(x => [x.value, x.name]))[data.oprtSttusCd],
            data.nwstSvcVer,
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
          if (data.name === 'admrId') {
            data.value = this.user.user_name
          } else {
            data.value = ''
          }
        })
        this.editSideTitle = '단위서비스등록'
        this.viewDatas[0].value = this.GenaratorId()

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id) {
        try {
          const params = {
            unitSvcId: id
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
            .get(this.$BASEURL + '/service/unit', { params, headers })
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

          this.editSideTitle = res.unitSvcNm
          this.$store.commit('EditSideComponentOpen')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      async Create () {
        try {
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
            .post(this.$BASEURL + '/service/unit', params, { headers })
            .then(this.NormalOrError)

          await this.$http
            .post(this.$BASEURL + '/board/bbs',
              {
                unitSvcId: params.unitSvcId,
                bbsId: params.unitSvcId, // 단위서비스 아이디와 동일하게 생성
                bbsNm: params.unitSvcNm, // 단위서비스 명과 동일하게 생성
                ntfBbsYn: 'Y',
                popupYn: 'N',
                replYn: 'N'
              }, {
              headers
            })
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
            .put(this.$BASEURL + '/service/unit', params, { headers })
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
            .delete(this.$BASEURL + '/service/unit', {
              data: this.pick(params, [
                'unitSvcId'
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
      }
    }
  }
</script>

<style scoped>
</style>
