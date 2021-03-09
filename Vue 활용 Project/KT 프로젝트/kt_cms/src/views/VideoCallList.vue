<template>
  <div>
    <PageTitle
      title="AR협업통화"
      info="AR협업통화 이력을 볼 수 있는 페이지 입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <!--
        <BtagButton
          btn-type="type-02"
          btn-name="등록"
          style="vertical-align: top;"
          @click-event="CreateOpen"
        />
        -->
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
      title="AR 협업통화 상세"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      :use-delete="false"
      :use-edit="false"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <!--
    <CreateSide
      title="등록"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.createSideOpen}"
      @create-event="Create"
    />
    -->
    <!-- //side-view -->
  </div>
</template>

<script>
  import PageTitle from '@/components/common/PageTitle.vue'
  // import BtagButton from '@/components/button/BtagButton.vue'
  import ExcelButton from '@/components/button/ExcelButton.vue'
  import Input from '@/components/form/input.vue'
  import FilterButton from '@/components/button/FilterButton.vue'
  import SearchWrap from '@/components/searchBox/SearchWrap.vue'
  import SearchList from '@/components/search_list/SearchList.vue'
  import EditSide from '@/components/sideBox/EditSide.vue'
  // import CreateSide from '@/components/sideBox/CreateSide.vue'
  import Pagination from '@/components/list_component/Pagination.vue'

  export default {
    name: 'VideoCallList',
    components: {
      PageTitle,
      // BtagButton,
      ExcelButton,
      Input,
      FilterButton,
      SearchWrap,
      SearchList,
      EditSide,
      // CreateSide,
      Pagination
    },
    data () {
      return {
        isFilterVisible: false,
        currentPage: null,
        size: null,
        records: null,
        keywordSmart: null,
        custInfo: {},
        userInfo: {},
        searchLists: [
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '세션ID',
            name: 'sessnId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '현장작업자ID',
            name: 'spotWrkrUuid',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '설비ID',
            name: 'facId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '작업ID',
            name: 'wrkId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '전문가ID',
            name: 'xpertUuid',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '전문가영상통화 생성일시',
            name: 'CretDt',
            type: 'datetimes',
            grid: 'row-1',
            from_value: '',
            to_value: ''
          }
        ],
        thDatas: [
          '서비스ID',
          '세션ID',
          '통화시작일시',
          '현장작업자ID',
          '설비ID',
          '작업ID',
          '전문가ID'
        ],
        resultLists: [],
        viewDatas: [
          {
            label: '통화시작일시',
            name: 'videTlkCretDt',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            value: null
          },
          {
            label: '통화종료일시',
            name: 'videTlkFnsDt',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            value: null
          },
          {
            label: '작업ID',
            name: 'wrkId',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            value: null
          },
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            value: null
          },
          {
            label: '설비Id',
            name: 'facId',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            value: null
          },
          {
            label: '작업자ID',
            name: 'spotWrkrId',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            value: null
          },
          {
            label: '채팅 내용 보기',
            name: 'chatinfo',
            type: 'chatinfo',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: true,
            setting: {
              height: null
            }
          },
          {
            label: '전문가별 자료',
            name: 'expertdata',
            type: 'expertdata',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: true,
            setting: {
              height: null
            }
          }
        ]
      }
    },
    async created () {
      await this.FetchData()
    },
    methods: {
      async FetchData () {
        await this.GetList()
        await this.$nextTick() // required for IE
        this.$refs.pagination.SetPage(this.currentPage, false)
      },
      GetParams () {
        const params = Object.fromEntries([
          ...this.searchLists.filter(x => !['date', 'datetimes'].includes(x.type)).map(x => [x.name, x.value || null]),
          ...this.searchLists.filter(x => x.type === 'date').flatMap(x => [
            [`st${x.name[0].toUpperCase()}${x.name.slice(1)}`, x.from_value ? `${x.from_value}T00:00:00.000` : null],
            [`fns${x.name[0].toUpperCase()}${x.name.slice(1)}`, x.to_value ? `${x.to_value}T23:59:59.999` : null]
          ]),
          ...this.searchLists.filter(x => x.type === 'datetimes').flatMap(x => [
            [`st${x.name[0].toUpperCase()}${x.name.slice(1)}`, x.from_value ? `${x.from_value}:00.000`.split(' ').join('T') : null],
            [`fns${x.name[0].toUpperCase()}${x.name.slice(1)}`, x.to_value ? `${x.to_value}:59.000`.split(' ').join('T') : null]
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
              .get(this.$BASEURL + '/videocall/worker/history/smart', {
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
            if (params.xpertUuid) {
              res = await this.$http
                .get(this.$BASEURL + '/videocall/specialist/history', {
                  params: this.pick(params, [
                    'xpertUuid',
                    'stCretDt',
                    'fnsCretDt',
                    'page',
                    'size'
                  ]),
                  headers
                })
                .then(this.UpdatePageInfo)
                .then(this.NormalOrError)
                .then(res => res.data.data)
            } else if (params.wrkId) {
              res = await this.$http
                .get(this.$BASEURL + '/videocall/work/history', {
                  params: this.pick(params, [
                    'wrkId',
                    'page',
                    'size'
                  ]),
                  headers
                })
                .then(this.UpdatePageInfo)
                .then(this.NormalOrError)
                .then(res => res.data.data)
            } else {
              res = await this.$http
                .get(this.$BASEURL + '/videocall/worker/history', {
                  params: this.pick(params, [
                    'spotWrkrUuid',
                    'stCretDt',
                    'fnsCretDt',
                    'page',
                    'size'
                  ]),
                  headers
                })
                .then(this.UpdatePageInfo)
                .then(this.NormalOrError)
                .then(res => res.data.data)
            }
          }

          this.resultLists = res.map(data => [
            data.svcId,
            data.sessnId, // index
            data.videTlkCretDt,
            this.get(data, 'userInfo.userId', null),
            data.facId,
            data.wrkId,
            data.experts.map(expert => this.get(expert, 'userInfo.userId', null)).filter(x => x).join(',')
          ].map(x => x || '-'))
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      },
      async CreateOpen () {
        //
      },
      async EditOpen (id, info) {
        try {
          this.$store.commit('progressComponentShow')

          const params = {
            svcId: info[0],
            sessnId: id
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
            .get(this.$BASEURL + '/videocall/history/info', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          let res1 = []
          if (res.chttRecYn === 'Y') {
            res1 = await this.$http
              .get(this.$BASEURL + '/videocall/history/chat', {
                params: this.pick(params, [
                  'sessnId'
                ]),
                headers
              })
              .then(this.NormalOrError)
              .then(this.FirstOrError)
          }

          const contentDataLoadingList = []
          if (this.get(res, 'experts.length', 0) > 0) {
            res.experts.forEach(x => {
              if (x.videTlkDataUuid) {
                x.parsedDataUuid = { id: x.videTlkDataUuid, data: {} }
                contentDataLoadingList.push(x.parsedDataUuid)
              }

              x.parsedSharedData = this.get(x, 'videTlkSharData', '').split(',').filter(x => x).map(x => ({ id: x, data: {} }))
              x.parsedSharedData.forEach(x => {
                contentDataLoadingList.push(x)
              })

              return x
            })
          }

          let res2 = []
          if (contentDataLoadingList.length > 0) {
            res2 = await this.$http
              .get(this.$BASEURL + '/content/ids', {
                params: {
                  ids: contentDataLoadingList.map(x => x.id).join(',')
                },
                headers
              })
              .then(this.NormalOrError)
              .then(res => res.data.data)

            res2.forEach(content => {
              const contentData = contentDataLoadingList.find(x => x.id === content.contsId)
              if (contentData) {
                contentData.data = content
              }
            })
          }

          this.viewDatas.forEach(item => {
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else if (item.name === 'spotWrkrId') {
              item.value = res.experts.map(expert => this.get(expert, 'userInfo.userId', null)).filter(x => x).join(',')
            } else if (item.name === 'chatinfo') {
              item.setting = {
                list: res1,
                height: '200px'
              }
            } else if (item.name === 'expertdata') {
              item.setting = {
                list: res.experts,
                height: '500px'
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

          params.cretrUuid = this.user.user_uuid

          if (Number(params.autCd) < Number(this.auth || 0)) {
            params.autCd = this.auth
          }
          params.apvSttusCd = this.auth === '04' ? '01' : '03'
          params.accSttusCd = '01'

          if (!params.userPwd) {
            alert('비밀번호를 입력해 주시기 바랍니다')
            return
          }

          if (!params.userPwdComfirm) {
            alert('비밀번호 확인을 입력해 주시기 바랍니다')
            return
          }

          if (params.userPwd !== params.userPwdComfirm) {
            alert('비밀번호가 일치하지 않습니다. 다시 확인해주세요')
            return
          }
          delete params.userPwdComfirm

          if (!(params.userImgFile instanceof File)) {
            delete params.userImgFile
          }

          const formData = new FormData()
          for (const key in params) {
            formData.append(key, params[key])
          }

          await this.$http
            .post(this.$BASEURL + '/user', formData, {
              headers: {
                ...headers,
                'Content-Type': 'multipart/form-data'
              }
            })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

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

          params.userUuid = this.userInfo.userUuid
          params.amdrUuid = this.user.user_uuid

          if (this.auth === '03') {
            if (this.user.cust_id !== params.custId) {
              alert('동일한 고객ID에 포함된 사용자에 한해서 수정 가능합니다')
              return
            }
          }

          if (!params.custId) {
            // 해당 값은 빈값으로 호출하면 에러
            delete params.custId
          }

          if (params.userPwd && params.userPwdComfirm) {
            if (params.userPwd !== params.userPwdComfirm) {
              alert('비밀번호가 일치하지 않습니다. 다시 확인해주세요')
              return
            }
          }
          delete params.userPwd
          delete params.userPwdComfirm

          if (!(params.userImgFile instanceof File)) {
            delete params.userImgFile
          }

          const formData = new FormData()
          for (const key in params) {
            formData.append(key, params[key])
          }

          const res = await this.$http
            .put(this.$BASEURL + '/user ', formData, { headers: { ...headers, 'Content-Type': 'multipart/form-data' } })
            .then(this.NormalOrError)
            .then(this.FirstOrError)
            .catch(e => null)

          if (!res) {
            this.FetchData()
            alert('수정 완료')
            return
          }

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
            .delete(this.$BASEURL + '/user', {
              data: {
                userUuid: this.userInfo.userUuid
              },
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
