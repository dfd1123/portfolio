<template>
  <div>
    <PageTitle
      title="사용자"
      info="사용자를 등록하는 페이지 입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="사용자등록"
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
      title="사용자 상세"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <CreateSide
      title="사용자 등록"
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
  import mime from 'mime-types'

  export default {
    name: 'UserList',
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
            label: '사용자ID',
            name: 'userId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '사용자명',
            name: 'userNm',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '고객ID',
            name: 'custId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '권한유형코드',
            name: 'autCd',
            type: 'select',
            grid: 'row-3',
            value: '',
            options: [
              {
                name: '전체',
                value: ''
              },
              {
                name: '플랫폼관리자',
                value: '01'
              },
              {
                name: '고객관리자',
                value: '02'
              },
              {
                name: '서비스관리자',
                value: '03'
              },
              {
                name: '일반사용자',
                value: '04'
              }
            ]
          },
          {
            label: '승인상태코드',
            name: 'apvSttusCd',
            type: 'select',
            grid: 'row-3',
            value: '',
            options: [
              {
                name: '전체',
                value: ''
              },
              {
                name: '승인요청',
                value: '01'
              },
              {
                name: '승인진행중',
                value: '02'
              },
              {
                name: '승인완료',
                value: '03'
              },
              {
                name: '승인거절',
                value: '04'
              },
              {
                name: '요청취소',
                value: '05'
              }
            ]
          },
          {
            label: '계정상태코드',
            name: 'accSttusCd',
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
          '사용자ID',
          '사용자명',
          '고객ID',
          '계정구분',
          '승인상태',
          '계정상태',
          '사용자이메일',
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
            readonly: false,
            isCreateReadOnly: false,
            onlyView: false,
            align: 'left',
            value: ''
          },
          {
            label: '권한유형코드',
            name: 'autCd',
            type: 'select',
            readonly: false,
            isCreateReadOnly: false,
            onlyView: false,
            value: '',
            options: [
              {
                name: '플랫폼관리자',
                value: '01'
              },
              {
                name: '고객관리자',
                value: '02'
              },
              {
                name: '서비스관리자',
                value: '03'
              },
              {
                name: '일반사용자',
                value: '04'
              }
            ]
          },
          {
            label: '아이디',
            name: 'userId',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: ''
          },
          {
            label: '성명',
            name: 'userNm',
            type: 'text',
            required: true,
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '비밀번호 확인',
            name: 'userPwdComfirm',
            type: 'password',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '비밀번호',
            name: 'userPwd',
            type: 'password',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '소속',
            name: 'pos',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '직책',
            name: 'rspof',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '이메일',
            name: 'userEmail',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '전화번호',
            name: 'userTelNo',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '프로필 사진',
            name: 'userImgFile',
            type: 'imagebox',
            readonly: false,
            onlyView: false,
            value: ''
          },
          {
            label: '서비스 가입 정보',
            name: 'datagrid',
            type: 'datagrid',
            ignore: true,
            onlyView: true,
            setting: {}
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
              .get(this.$BASEURL + '/user/smart', {
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
              .get(this.$BASEURL + '/user', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            data.userId, // index
            data.userNm,
            data.custId,
            { '01': '플랫폼관리자', '02': '고객관리자', '03': '서비스관리자', '04': '일반사용자' }[data.autCd],
            { '01': '승인요청', '02': '승인진행중', '03': '승인완료', '04': '승인거절', '05': '요청취소' }[data.apvSttusCd],
            { '01': ' 정상', '02': '정지', '03': '탈퇴' }[data.accSttusCd],
            data.userEmail,
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
            } else {
              data.value = ''
            }
          })

          this.$store.commit('CreateSideComponentOpen')
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        }
      },
      async EditOpen (id) {
        try {
          this.$store.commit('progressComponentShow')

          this.userInfo = null

          const params = {
            userId: id
          }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          // 사용자 조회
          const res = await this.$http
            .get(this.$BASEURL + '/user', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.userInfo = res

          // 가입한 서비스 조회
          const res1 = await this.$http
            .get(this.$BASEURL + '/user/service', {
              params: this.pick(res, ['userUuid']),
              headers
            })
            .then(this.NormalOrError)
            .then(res => res.data.data)
            .catch(e => [] /* 해당부분 일단 예외처리 */)

          // 사용자 이미지 조회
          const res2 = await this.$http
            .get(this.$BASEURL + `/user/${this.userInfo.userUuid}`, {
              responseType: 'blob',
              headers
            })
            .then(res => {
              const extension = mime.extension(res.headers['content-type'])
              return (extension !== 'json') ? URL.createObjectURL(new Blob([res.data])) : null
            })
            .catch(() => null)

          // 등록가능한 서비스 표시
          const dataGridSetting = {
            cols: [
              {
                label: '서비스ID',
                name: 'svcId'
              },
              {
                label: '단위서비스ID',
                name: 'unitSvcId',
                hide: true
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
                    label: '정상',
                    value: '01'
                  },
                  {
                    label: '02',
                    value: '02'
                  },
                  {
                    label: '03',
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
                label: '계정구분',
                name: '',
                type: 'select',
                options: [
                  {
                    label: '선택',
                    value: ''
                  },
                  {
                    label: '고객관리자',
                    value: '03'
                  },
                  {
                    label: '일반사용자',
                    value: '04'
                  }
                ]
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
            rows: res1.map(row => ({
              ...row,
              ...{
                unitSvcUserAutCd: row.unitSvcUserAutCd || null,
                checked: false // TODO 현재 사용자의 가입한 서비스 조회 후 체크표시
              }
            })),
            index: 'svcId',
            // rowCheckType: 'multi', // null(default), single
            // isEditableOnlyCheckedRows: true,
            readonly: true
          }

          this.viewDatas.forEach(item => {
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else if (item.type === 'datagrid') {
              item.setting = dataGridSetting
            } else if (item.type === 'imagebox') {
              item.value = res2
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
