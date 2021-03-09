<template>
  <div>
    <PageTitle
      title="사용자서비스가입"
      info="사용자에게 서비스를 할당하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="사용자가입"
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
        :result-index="0"
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
      title="사용자 상세보기"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <CreateSide
      title="서비스 가입"
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
  import mime from 'mime-types'

  export default {
    name: 'UserList',
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
    data () {
      return {
        isFilterVisible: false,
        currentPage: null,
        size: null,
        records: null,
        keywordSmart: null,
        isIdDuplicated: true,
        custInfo: {},
        userInfo: {},
        serviceExpensionLists: [],
        searchLists: [
          {
            label: '사용자ID',
            name: 'userId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '사용자명',
            name: 'userNm',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '고객ID',
            name: 'custId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '권한',
            name: 'autCd',
            type: 'select',
            grid: 'row-3',
            value: null,
            options: [
              {
                name: '전체',
                value: null
              },
              {
                name: '플랫폼관리자',
                value: '01'
              },
              {
                name: 'KT관리자',
                value: '02'
              },
              {
                name: '고객관리자',
                value: '03'
              },
              {
                name: '일반사용자',
                value: '04'
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
          '서비스ID',
          '사용자ID',
          '사용자명',
          '고객ID',
          '계정구분',
          '권한',
          '등록일시',
          '최근수정일시'
        ],
        resultLists: [],
        viewDatas: [
          {
            label: '고객ID',
            name: 'custId',
            type: 'customer',
            align: 'left',
            isCreateReadOnly: false,
            readonly: true,
            onlyView: false,
            value: null,
            onChange: this.OnChangeCustId
          },
          {
            label: '사용자ID',
            name: 'userId',
            type: 'user',
            readonly: false,
            onlyView: false,
            onlyNew: true,
            value: null,
            svcId: null,
            custId: null,
            onChange: this.OnChangeUserId
          },
          {
            label: '사용자 정보',
            name: 'formtitle',
            type: 'formtitle',
            readonly: false,
            onlyView: false,
            onlyNew: true,
            ignore: true,
            value: null
          },
          {
            label: '사용자ID',
            name: 'userId',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            ignore: true,
            value: null
          },
          {
            label: '사용자명',
            name: 'userNm',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            ignore: true,
            value: null
          },
          {
            label: '소속',
            name: 'pos',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            ignore: true,
            value: null
          },
          {
            label: '직책',
            name: 'rspof',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            ignore: true,
            value: null
          },
          {
            label: '전화번호',
            name: 'userTelNo',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            ignore: true,
            value: null
          },
          {
            label: '이메일',
            name: 'userEmail',
            type: 'text',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            ignore: true,
            value: null
          },
          {
            label: '프로필 사진',
            name: 'userImgFile',
            type: 'imagebox',
            readonly: true,
            isCreateReadOnly: true,
            onlyView: false,
            ignore: true,
            value: null
          },
          {
            label: '서비스 가입 정보',
            name: 'datagrid',
            type: 'datagrid',
            ignore: true,
            setting: {}
          },
          {
            label: '',
            name: 'expnsnAtribVal',
            type: 'expension',
            onlyView: true,
            value: null
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
              .get(this.$BASEURL + '/user/service/smart', {
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
              .get(this.$BASEURL + '/user/service/info', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            data.svcId,
            data.userId, // index
            data.userNm,
            data.custId,
            { '01': '플랫폼관리자', '02': '고객관리자', '03': '서비스관리자', '04': '일반사용자' }[data.autCd],
            { '01': '작업관리자', '02': '전문가', '03': '현장관리자' }[data.unitSvcUserAutCd],
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
        try {
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
            } else if (data.name === 'userId') {
              data.value = null
              setTimeout(() => {
                data.svcId = this.user.svc_id
                data.value = null
              }, 0)
            } else {
              data.value = null
            }
          })

          this.$store.commit('CreateSideComponentOpen')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      async EditOpen (id, info) {
        try {
          this.$store.commit('progressComponentShow')

          this.userInfo = null

          const params0 = {
            userId: info[1]
          }
          const headers0 = {}

          if (['03', '04'].includes(this.auth)) {
            params0.svcId = this.user.svc_id
          }

          if (params0.svcId) {
            headers0['X-Svc-Id'] = params0.svcId
            delete params0.svcId
          }

          // 사용자 조회
          const res0 = await this.$http
            .get(this.$BASEURL + '/user', {
              params: params0,
              headers: headers0
            })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.userInfo = res0

          const params1 = {
            svcId: info[0],
            userId: info[1]
          }
          const headers1 = {}

          if (['03', '04'].includes(this.auth)) {
            params1.svcId = this.user.svc_id
          }

          if (params1.svcId) {
            headers1['X-Svc-Id'] = params1.svcId
            delete params1.svcId
          }

          // 가입 서비스 조회
          const res1 = await this.$http
            .get(this.$BASEURL + '/user/service', {
              params: params1,
              headers: headers1
            })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          const res1a = await this.$http
            .get(this.$BASEURL + '/service/ids', {
              headers: headers1
            })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.userInfo.svcId = info[0]

          const params2 = {}
          const headers2 = {}

          if (['03', '04'].includes(this.auth)) {
            params2.svcId = this.user.svc_id
          }

          if (params2.svcId) {
            headers2['X-Svc-Id'] = params2.svcId
            delete params2.svcId
          }

          // 사용자 이미지 조회
          const res2 = await this.$http
            .get(this.$BASEURL + `/user/${this.userInfo.userUuid}`, {
              responseType: 'blob',
              headers: headers2
            })
            .then(res => {
              const extension = mime.extension(res.headers['content-type'])
              return (extension !== 'json') ? URL.createObjectURL(new Blob([res.data])) : null
            })
            .catch(() => null)

          const params3 = {
            svcId: info[0],
            entitiType: '01'
          }
          const headers3 = {}

          if (['03', '04'].includes(this.auth)) {
            params3.svcId = this.user.svc_id
          }

          if (params3.svcId) {
            headers3['X-Svc-Id'] = params3.svcId
            delete params3.svcId
          }

          // 서비스 확장정보 조회
          let res3 = []
          if (headers3['X-Svc-Id']) {
            res3 = await this.$http
              .get(this.$BASEURL + '/service/expansion', {
                params: params3,
                headers: headers3
              })
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.serviceExpensionLists = res3

          // 등록가능한 서비스 표시
          const dataGridSetting = {
            cols: [
              {
                label: '서비스ID',
                name: 'svcId'
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
                label: '서비스버전',
                name: 'svcVer'
              },
              {
                label: '서비스설명',
                name: 'svcDesc'
              },
              {
                label: '권한',
                name: 'unitSvcUserAutCd',
                type: 'select',
                options: [
                  {
                    label: '선택',
                    value: ''
                  },
                  {
                    label: '작업관리자',
                    value: '01'
                  },
                  {
                    label: '전문가',
                    value: '02'
                  },
                  {
                    label: '현장관리자',
                    value: '03'
                  }
                ]
              }
            ],
            rows: [{ ...res1, ...res1a }].map(row => ({
              ...row,
              ...{
                unitSvcUserAutCd: row.unitSvcUserAutCd || null,
                checked: false
              }
            })),
            index: 'svcId',
            readonly: this.auth === '04'
          }

          this.viewDatas.forEach(item => {
            const value = res0[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else if (item.type === 'datagrid') {
              item.setting = dataGridSetting
            } else if (item.type === 'imagebox') {
              item.value = res2
            } else if (item.type === 'expension') {
              const list = this.serviceExpensionLists
                .map(item => {
                  const info = this.get(res1, 'expnsnAtribVal.value', null)
                  if (info) {
                    const items = Object.entries(JSON.parse(info))
                    if (items) {
                      const data = items.find(([key, value]) => { return key === item.atribId })
                      if (data) {
                        return {
                          atribId: item.atribId,
                          atribNm: item.atribNm,
                          value: data[1] || null
                        }
                      }
                    }
                  }

                  return {
                    atribId: item.atribId,
                    atribNm: item.atribNm ? item.atribNm : item.atribId,
                    value: null
                  }
                })
                .filter((v, i, a) => a.findIndex(t => (t.atribId === v.atribId)) === i) // atribId 중복제거

              const expension = this.viewDatas.find(x => x.type === 'expension')
              expension.value = list
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

          // 이용서비스 등록
          const datagrid = this.viewDatas.find(x => x.type === 'datagrid')
          if (!this.get(datagrid, 'setting.rows', null)) {
            alert('사용자를 선택해야 합니다')
            return
          }

          const services = this.get(datagrid, 'setting.rows', []).filter(x => x.checked)
          if (services.length === 0) {
            alert('선택된 서비스가 없습니다')
            return
          }

          for (const service of services) {
            const params = {
              svcId: service.svcId,
              userUuid: this.userInfo.userUuid,
              unitSvcUserAutCd: service.unitSvcUserAutCd || null,
              svcUserGroupId: service.svcUserGroupId || null,
              expnsnAtribVal: service.expnsnAtribVal || null,
              recntLoginSvcYn: service.recntLoginSvcYn || 'N' // 해당 값이 없으면 API 호출 실패함
            }

            for (const key in params) {
              if (params[key] === null) {
                delete params[key]
              }
            }

            await this.$http
              .post(this.$BASEURL + '/user/service',
                params,
                {
                  headers: {
                    'X-Svc-Id': service.svcId
                  }
                })
              .then(this.NormalOrError)
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
          params.custId = this.userInfo.custId
          params.userUuid = this.userInfo.userUuid
          params.amdrUuid = this.user.user_uuid

          if (params.expnsnAtribVal) {
            params.expnsnAtribVal = Object.fromEntries(params.expnsnAtribVal.filter(x => x.value).map(x => [x.atribId, x.value]))
          }

          const datagrid = this.viewDatas.find(x => x.type === 'datagrid')
          if (this.get(datagrid, 'setting.rows', null)) {
            const service = this.get(datagrid, 'setting.rows[0]')
            if (!service) {
              return
            }

            const datas = {
              svcId: service.svcId,
              userUuid: params.userUuid,
              unitSvcUserAutCd: service.unitSvcUserAutCd || null,
              svcUserGroupId: service.svcUserGroupId || null,
              expnsnAtribVal: params.expnsnAtribVal || null,
              recntLoginSvcYn: service.recntLoginSvcYn || 'N' // 해당 값이 없으면 API 호출 실패함
            }

            for (const key in datas) {
              if (datas[key] === null) {
                delete datas[key]
              }
            }

            await this.$http
              .put(this.$BASEURL + '/user/service',
                datas,
                {
                  headers: {
                    'X-Svc-Id': service.svcId
                  }
                })
              .then(this.NormalOrError)
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

          params.svcId = this.userInfo.svcId

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          const res = await this.$http
            .get(this.$BASEURL + '/user', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          await this.$http
            .delete(this.$BASEURL + '/user/service', {
              data: this.pick(res, [
                'userUuid'
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
      async OnChangeUserId (userId, userInfo) {
        try {
          this.$store.commit('progressComponentShow')

          this.userInfo = userInfo

          const params0 = {
            userUuid: userInfo.userUuid
          }
          const headers0 = {}

          if (['03', '04'].includes(this.auth)) {
            params0.svcId = this.user.svc_id
          }

          if (params0.svcId) {
            headers0['X-Svc-Id'] = params0.svcId
            delete params0.svcId
          }

          // 사용자 조회
          const res0 = await this.$http
            .get(this.$BASEURL + '/user', {
              params: params0,
              headers: headers0
            })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          const params1 = {}
          const headers1 = {}

          if (['03', '04'].includes(this.auth)) {
            params1.svcId = this.user.svc_id
          }

          if (params1.svcId) {
            headers1['X-Svc-Id'] = params1.svcId
            delete params1.svcId
          }

          // 사용자 이미지 조회
          const res1 = await this.$http
            .get(this.$BASEURL + `/user/${userInfo.userUuid}`, {
              responseType: 'blob',
              headers: headers1
            })
            .then(res => {
              const extension = mime.extension(res.headers['content-type'])
              return (extension !== 'json') ? URL.createObjectURL(new Blob([res.data])) : null
            })
            .catch(() => null)

          const params2 = {
            custId: userInfo.custId
          }
          const headers2 = {}

          if (['03', '04'].includes(this.auth)) {
            params2.svcId = this.user.svc_id
          }

          if (params2.svcId) {
            headers2['X-Svc-Id'] = params2.svcId
            delete params2.svcId
          }

          // 가입가능한 서비스 조회
          const res2 = await this.$http
            .get(this.$BASEURL + '/service', {
              params: params2,
              headers: headers2
            })
            .then(this.NormalOrError)
            .then(res => res.data.data)

          // 등록가능한 서비스 표시
          const dataGridSetting = {
            cols: [
              {
                label: '서비스ID',
                name: 'svcId'
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
                label: '권한',
                name: 'unitSvcUserAutCd',
                type: 'select',
                options: [
                  {
                    label: '선택',
                    value: ''
                  },
                  {
                    label: '작업관리자',
                    value: '01'
                  },
                  {
                    label: '전문가',
                    value: '02'
                  },
                  {
                    label: '현장관리자',
                    value: '03'
                  }
                ]
              }
            ],
            rows: res2.map(row => ({
              ...row,
              ...{
                checked: false
              }
            })),
            index: 'svcId',
            rowCheckType: 'multi', // null(default), single
            isEditableOnlyCheckedRows: true,
            readonly: this.auth === '04'
          }

          this.viewDatas
            .filter(x => [
              'userId',
              'userNm',
              'pos',
              'rspof',
              'userTelNo',
              'userEmail',
              'autCd',
              'userImgFile',
              'datagrid'
            ].includes(x.name) && x.ignore)
            .forEach(item => {
              const value = res0[item.name]
              if (this.CheckDate(value)) {
                item.value = this.FormatDate(value)
              } else if (item.type === 'datagrid') {
                item.setting = dataGridSetting
              } else if (item.type === 'imagebox') {
                item.value = res1
              } else {
                item.value = value || (item.readonly ? '-' : '')
              }
            })
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      },
      async OnChangeCustId (custId, custInfo) {
        setTimeout(() => {
          this.viewDatas[1].custId = null
          this.viewDatas[1].custId = custId
        }, 0)
      }
    }
  }
</script>

<style scoped>
</style>
