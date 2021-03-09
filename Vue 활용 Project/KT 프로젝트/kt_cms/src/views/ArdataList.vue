<template>
  <div>
    <PageTitle
      title="증강컨텐츠"
      info="증강될 자료를 등록하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="증강자료등록"
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
        :result-key="[0, 1]"
        :result-index="1"
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
      title="증강자료상세"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      :use-edit="false"
      @delete-event="Delete"
      @download="OnDownloadFile"
    />
    <CreateSide
      title="증강자료등록"
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
  import { saveAs } from 'file-saver'
  import mime from 'mime-types'

  export default {
    name: 'ArdataList',
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
        searchLists: [
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '컨텐츠ID',
            name: 'contsId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '컨텐츠명',
            name: 'contsNm',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '컨텐츠분류코드',
            name: 'contsCtgCd',
            type: 'select',
            grid: 'row-3',
            value: '',
            options: [
              {
                name: 'AR리소스컨텐츠',
                value: '02'
              },
              {
                name: 'AR리소스원본컨텐츠',
                value: '04'
              }
            ]
          },
          {
            label: '파일타입',
            name: 'etsion',
            type: 'text',
            grid: 'row-3',
            value: ''
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
          '컨텐츠ID',
          '컨텐츠명',
          '컨텐츠분류',
          '변환상태',
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
            isCreateReadOnly: false,
            readonly: true,
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
            value: '',
            options: []
          },
          {
            label: '컨텐츠ID',
            name: 'contsId',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: ''
          },
          {
            label: '컨텐츠명',
            name: 'contsNm',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: ''
          },
          {
            label: '컨텐츠분류',
            name: 'contsCtgCd',
            type: 'select',
            required: true,
            readonly: true,
            onlyView: false,
            value: '',
            options: [
              {
                name: 'AR리소스컨텐츠',
                value: '02'
              },
              {
                name: 'AR리소스원본컨텐츠',
                value: '04'
              }
            ]
          },
          {
            label: '등록 파일',
            name: 'file',
            type: 'file',
            required: true,
            readonly: true,
            onlyView: false,
            fileName: '',
            value: '',
            download: true
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
            min: 2,
            max: 2000,
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
          },
          {
            label: '등록정보',
            name: 'table',
            type: 'table',
            ignore: true,
            readonly: true,
            onlyView: true,
            value: null,
            height: 'auto',
            thDatas: [
              '설비ID',
              '설비명'
            ],
            resultLists: [],
            viewDatas: [
              {
                label: '설비ID',
                name: 'facId',
                type: 'text',
                readonly: true,
                onlyView: false,
                value: ''
              },
              {
                label: '설비명',
                name: 'facNm',
                type: 'text',
                readonly: true,
                onlyView: false,
                value: ''
              }
            ],
            SetView: () => { }
          },
          {
            label: '증강자료 설명',
            name: 'contsDesc',
            type: 'textarea',
            min: 2,
            max: 2000,
            readonly: true,
            onlyView: false,
            value: ''
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

        const params = Object.fromEntries([
          ...list.filter(x => !['empty'].includes(x.type)).map(x => [x.name, (x.value === '-') ? null : x.value])
        ])

        return params
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

          params.contsCtgCd = '02'

          let res = []
          if (this.keywordSmart) {
            res = await this.$http
              .get(this.$BASEURL + '/contents/smart', {
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
              .get(this.$BASEURL + '/contents', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            data.svcId,
            data.contsId, // index
            data.contsNm,
            { '02': 'AR리소스컨텐츠', '04': 'AR리소스원본컨텐츠' }[data.contsCtgCd],
            { '01': '저장완료', '02': '저장진행중', '03': '저장오류' }[data.contsSttusCd],
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
      CreateOpen () {
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
          } else if (data.type === 'file') {
            data.fileName = ''
            data.value = ''
          } else {
            data.value = ''
          }
        })

        this.viewDatas[2].value = this.GenaratorId()

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id, info) {
        // 세부정보 로딩
        try {
          const params = {
            contsId: id
          }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          const req = this.$http
            .get(this.$BASEURL + '/contents', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          const req1 = this.$http
            .get(this.$BASEURL + '/facility/contents', { params, headers })
            .then(this.NormalOrError)
            .then(res => res.data.data)
            .catch(e => [])

          const [res, res1] = await Promise.all([req, req1])

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
            } else if (item.type === 'file') {
              item.fileName = `${res.contsNm || ''}.${res.etsion}`
              item.value = ''
            } else {
              item.value = value || (item.readonly ? '-' : '')
            }
          })

          const table = this.viewDatas.find(item => item.type === 'table' && item.name === 'table')
          if (table) {
            table.resultLists = res1.map(x => ({ facId: x.facId, facNm: x.facNm }))
          }

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
          params.contsCtgCd = '02'

          const formData = new FormData()
          for (const key in params) {
            if (key !== 'file' || params[key]) {
              formData.append(key, params[key])
            }
          }

          await this.$http
            .post(this.$BASEURL + '/content ', formData, { headers: { ...headers, 'Content-Type': 'multipart/form-data' } })
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
        //
      },
      async Delete () {
        //
      },
      async OnDownloadFile (data) {
        try {
          this.$store.commit('progressComponentShow')

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
            .get(this.$BASEURL + `/content/${headers['X-Svc-Id']}/${params.contsId}`, {
              responseType: 'blob',
              headers
            })
            .catch(e => e.response)
            .then(async res => {
              const extension = mime.extension(res.headers['content-type'])
              const tempName = `${params.contsNm}.${extension}`
              if (extension === 'json') {
                const result = JSON.parse(await new Response(new Blob([res.data])).text())
                throw this.set(result, 'response.data.message', result.message)
              } else {
                saveAs(new Blob([res.data]), tempName)
              }
            })
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
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
          if (select) {
            select.options = options
            select.value = (this.FirstData(options) || {}).value || null
          }
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
