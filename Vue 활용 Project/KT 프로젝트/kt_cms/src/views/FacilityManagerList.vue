<template>
  <div>
    <PageTitle
      title="인식데이터"
      info="설비를 인식할 데이터를 등록하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="인식데이터등록"
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
      title="인식데이터 상세보기"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      :use-delete="useDelete"
      @edit-event="Edit"
      @delete-event="Delete"
      @download="OnDownloadFile"
    />
    <CreateSide
      title="인식데이터등록"
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
    name: 'FacilityManagerList',
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
        useDelete: true,
        searchLists: [
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: 'AR마커데이터ID',
            name: 'arMrkrDataId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: 'AR마커데이터명',
            name: 'arMrkrDataNm',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: 'AR마커데이터유형',
            name: 'arMrkrDataTypeCd',
            type: 'select',
            grid: 'row-3',
            value: '',
            options: [
              {
                name: '전체',
                value: ''
              },
              {
                name: 'QR코드',
                value: '01'
              },
              {
                name: '2D이미지',
                value: '02'
              },
              {
                name: '3D오브젝트',
                value: '03'
              }
            ]
          },
          {
            label: 'AR마커데이터상태코드',
            name: '',
            type: 'select',
            grid: 'row-3',
            value: '',
            options: [
              {
                name: '전체',
                value: ''
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
          'AR인식데이터ID',
          'AR인식데이터명',
          'AR인식데이터유형',
          '상태',
          '등록자ID',
          '최근수정자ID',
          '등록일시',
          '최근수정일시'
        ],
        resultLists: [],
        viewDataModels: {
          '01': [ // QR
            {
              label: '고객ID',
              name: 'custId',
              type: 'customer',
              ignore: true,
              readonly: true,
              onlyView: false,
              align: 'left',
              value: null,
              isCreateReadOnly: false,
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
              label: 'AR인식데이터ID',
              name: 'arMrkrDataId',
              type: 'text',
              required: true,
              readonly: true,
              onlyView: true,
              value: ''
            },
            {
              label: 'AR인식데이터명',
              name: 'arMrkrDataNm',
              type: 'text',
              required: true,
              readonly: false,
              onlyView: false,
              value: ''
            },
            {
              label: '설비명',
              name: 'facNm',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: false,
              value: ''
            },
            {
              label: '설비ID',
              name: 'facId',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: false,
              value: ''
            },
            {
              label: 'QR코드이미지',
              name: 'image',
              type: 'image',
              ignore: true,
              readonly: true,
              onlyView: false,
              value: null
            },
            {
              label: 'AR인식데이터 설명',
              name: 'arMrkrDataDesc',
              type: 'textarea',
              min: 2,
              max: 2000,
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
              onlyView: false,
              value: null
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
            }
          ],
          '02': [ // 2D
            {
              label: '고객ID',
              name: 'custId',
              type: 'customer',
              ignore: true,
              readonly: true,
              onlyView: false,
              align: 'left',
              value: null,
              isCreateReadOnly: false,
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
              label: 'AR인식데이터ID',
              name: 'arMrkrDataId',
              type: 'text',
              required: true,
              readonly: true,
              onlyView: true,
              value: ''
            },
            {
              label: 'AR인식데이터명',
              name: 'arMrkrDataNm',
              type: 'text',
              required: true,
              readonly: false,
              onlyView: false,
              value: ''
            },
            {
              label: '설비명',
              name: 'facNm',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: false,
              value: ''
            },
            {
              label: '설비ID',
              name: 'facId',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: false,
              value: ''
            },
            {
              label: '학습결과',
              name: 'arMrkrDataQat',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: false,
              value: null
            },
            {
              label: '2D이미지파일',
              name: 'file',
              type: 'file',
              readonly: false,
              onlyView: false,
              value: '',
              download: true
            },
            {
              label: 'AR인식데이터 설명',
              name: 'arMrkrDataDesc',
              type: 'textarea',
              min: 2,
              max: 2000,
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
            }
          ],
          '03': [ // 3D
            {
              label: '고객ID',
              name: 'custId',
              type: 'customer',
              ignore: true,
              readonly: true,
              onlyView: false,
              align: 'left',
              value: null,
              isCreateReadOnly: false,
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
              label: 'AR인식데이터ID',
              name: 'arMrkrDataId',
              type: 'text',
              required: true,
              readonly: true,
              onlyView: true,
              value: ''
            },
            {
              label: 'AR인식데이터명',
              name: 'arMrkrDataNm',
              type: 'text',
              required: true,
              readonly: false,
              onlyView: false,
              value: ''
            },
            {
              label: '설비명',
              name: 'facNm',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: false,
              value: ''
            },
            {
              label: '설비ID',
              name: 'facId',
              type: 'text',
              ignore: true,
              readonly: true,
              onlyView: false,
              value: ''
            },
            {
              label: '3D오브젝트파일',
              name: 'file',
              type: 'file',
              readonly: true,
              onlyView: false,
              value: '',
              download: true
            },
            {
              type: 'empty'
            },
            {
              label: 'AR인식데이터 설명',
              name: 'arMrkrDataDesc',
              type: 'textarea',
              min: 2,
              max: 2000,
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
            }
          ],
          'create': [
            {
              label: '고객ID',
              name: 'custId',
              type: 'customer',
              ignore: true,
              readonly: true,
              onlyView: false,
              align: 'left',
              value: null,
              isCreateReadOnly: false,
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
              options: [],
              onChange: (value) => {
                setTimeout(() => {
                  this.viewDatas.find(x => x.name === 'facId').svcId = value
                }, 0)
              }
            },
            {
              label: 'AR인식데이터명',
              name: 'arMrkrDataNm',
              type: 'text',
              required: true,
              readonly: true,
              onlyView: false,
              value: ''
            },
            {
              label: '설비명',
              name: 'facId',
              type: 'equipment',
              required: true,
              readonly: false,
              onlyView: false,
              value: '',
              svcId: ''
            },
            {
              label: 'AR인식데이터유형',
              name: 'arMrkrDataTypeCd',
              type: 'select',
              required: true,
              readonly: true,
              onlyView: false,
              value: '',
              options: [
                {
                  name: '2D이미지',
                  value: '02'
                },
                {
                  name: '3D오브젝트',
                  value: '03'
                }
              ],
              onChange: (value) => {
                const found = this.viewDatas.find(x => x.name === 'size')
                if (found) {
                  found.isCreateReadOnly = value !== '02'
                }
              }
            },
            {
              label: 'AR인식데이터파일',
              name: 'file',
              type: 'file',
              required: true,
              readonly: false,
              onlyView: false,
              value: ''
            },
            {
              label: '2D 이미지 사이즈',
              name: 'size',
              type: 'size',
              required: false,
              readonly: false,
              onlyView: false,
              isCreateReadOnly: true,
              value: {
                horizontal: '',
                vertical: ''
              }
            },
            {
              label: 'AR인식데이터 설명',
              name: 'arMrkrDataDesc',
              type: 'textarea',
              min: 2,
              max: 2000,
              readonly: false,
              onlyView: false,
              value: ''
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
        // 모든 파라미터 값 가져오기
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

          let res = []
          if (this.keywordSmart) {
            res = await this.$http
              .get(this.$BASEURL + '/armarker/search/smart', {
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
              .get(this.$BASEURL + '/armarker/search', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
              .catch(e => [])
          }

          this.resultLists = res.map(data => [
            data.svcId,
            data.arMrkrDataId, // index
            data.arMrkrDataNm,
            { '01': 'QR코드', '02': '2D이미지', '03': '3D오브젝트' }[data.arMrkrDataTypeCd],
            (() => {
              if (data.arMrkrDataTypeCd === '02') {
                let str = ''
                str += '★'.repeat(Math.floor(data.arMrkrDataQat)) + '☆'.repeat(5 - Math.floor(data.arMrkrDataQat))
                if (Number(data.arMrkrDataQat) <= 2) {
                  str = '@v-html:' + str
                  str += `<br><a href="javascript:;" class="btn-01 type-03 round">재등록</a>`
                }
                return str
              }

              return data.arMrkrDataQat
            })(),
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
        this.viewDatas = [...this.viewDataModels.create]

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
          } else if (data.type === 'size') {
            data.value.horizontal = 0
            data.value.vertical = 0
            data.isCreateReadOnly = true
          } else {
            data.value = ''
          }
        })

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id, info) {
        try {
          const svcId = info[0]
          const params = {
            svcId
          }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          const res1 = await this.$http
            .get(this.$BASEURL + '/armarker/search', {
              params: {
                arMrkrDataId: id
              },
              headers
            })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.viewDatas = [...this.viewDataModels[res1.arMrkrDataTypeCd]]
          this.viewDataModelId = res1.arMrkrDataTypeCd
          this.useDelete = res1.arMrkrDataTypeCd !== '01'

          const res3 = await this.$http
            .get(this.$BASEURL + `/armarker/${svcId}/${id}`, {
              responseType: 'blob',
              headers
            })
            .then(res => {
              const extension = mime.extension(res.headers['content-type'])
              return (extension !== 'json') ? URL.createObjectURL(new Blob([res.data])) : null
            })
            .catch(() => null)

          const params4 = {}
          if (res1.arMrkrDataTypeCd === '01') {
            params4.qrCdId = id
          } else if (res1.arMrkrDataTypeCd === '02') {
            params4.traingFeatrDataId = id
          } else if (res1.arMrkrDataTypeCd === '03') {
            params4.bin3dDataId = id
          }

          const res4 = await this.$http
            .get(this.$BASEURL + '/facility', {
              params: params4,
              headers
            })
            .then(this.FirstOrError)
            .catch(e => ({}))

          res1.facId = res4.facId
          res1.facNm = res4.facNm

          this.viewDatas.forEach(item => {
            const value = res1[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else if (item.name === 'svcId') {
              item.options = [{ name: value, value }]
              item.value = value
            } else if (item.name === 'customer') {
              setTimeout(() => {
                item.value = value
              }, 0)
            } else if (item.name === 'arMrkrDataQat' && res1.arMrkrDataTypeCd === '02') {
              item.value = '★'.repeat(Math.floor(res1.arMrkrDataQat)) + '☆'.repeat(5 - Math.floor(res1.arMrkrDataQat))
            } else if (item.type === 'image') {
              item.value = res3
            } else if (item.type === 'expert') {
              item.value = res1.map(x => x.userUuid)
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

          // 마커 등록
          const formData = new FormData()
          for (const key in params) {
            formData.append(key, params[key])
          }

          const res = await this.$http
            .post(this.$BASEURL + '/armarker ', formData, { headers: { ...headers, 'Content-Type': 'multipart/form-data' } })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          // 설비 데이터 수정
          const params1 = {
            svcId: res.svcId,
            facId: params.facId
          }
          const headers1 = {}

          if (['03', '04'].includes(this.auth)) {
            params1.svcId = this.user.svc_id
          }

          if (params1.svcId) {
            headers1['X-Svc-Id'] = params1.svcId
            delete params.svcId
          }

          if (params.arMrkrDataTypeCd === '02') {
            params1.traingFeatrDataId = res.arMrkrDataId
          } else if (params.arMrkrDataTypeCd === '03') {
            params1.bin3dDataId = res.arMrkrDataId
          }

          await this.$http
            .put(this.$BASEURL + '/facility', params1, {
              headers: headers1
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
          params.arMrkrDataTypeCd = this.viewDataModelId

          const formData = new FormData()
          for (const key in params) {
            if (key !== 'file' || params[key]) {
              formData.append(key, params[key])
            }
          }

          await this.$http
            .put(this.$BASEURL + '/armarker', formData, { headers: { ...headers, 'Content-Type': 'multipart/form-data' } })
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
            .delete(this.$BASEURL + '/armarker', {
              data: this.pick(params, [
                'arMrkrDataId'
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
      async OnDownloadFile (data) {
        try {
          this.$store.commit('progressComponentShow')

          const params = this.GetDetails()
          const headers = {}
          const { svcId } = params

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          await this.$http
            .get(this.$BASEURL + `/armarker/${svcId}/${params.arMrkrDataId}/src`, {
              responseType: 'blob',
              headers
            })
            .catch(e => e.response)
            .then(async res => {
              const extension = mime.extension(res.headers['content-type'])
              const tempName = `${params.arMrkrDataNm}.${extension}`
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
