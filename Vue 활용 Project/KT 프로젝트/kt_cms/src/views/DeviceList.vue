<template>
  <div>
    <PageTitle
      title="단말관리"
      info="새로운 단말기를 등록하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="단말등록"
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
      title="단말상세"
      :view-datas="this.$store.state.editSideOpen ? viewDatas : []"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <CreateSide
      title="단말등록"
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
  import ExcelButton from '@/components/button/ExcelButton.vue'
  import Input from '@/components/form/input.vue'
  import FilterButton from '@/components/button/FilterButton.vue'
  import SearchWrap from '@/components/searchBox/SearchWrap.vue'
  import SearchList from '@/components/search_list/SearchList.vue'
  import EditSide from '@/components/sideBox/EditSide.vue'
  import CreateSide from '@/components/sideBox/CreateSide.vue'
  import Pagination from '@/components/list_component/Pagination.vue'

  export default {
    name: 'DeviceList',
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
        searchLists: [
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '단말일련번호',
            name: 'hndsetSeq',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '단말고유번호',
            name: 'hndsetInntNo',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '단말명',
            name: 'handsetNm',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '사용자ID',
            name: 'userId',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '애플리케이션일련번호',
            name: 'applSeq',
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
          '단말일련번호',
          '단말고유번호',
          '단말명',
          '사용자ID',
          '애플리케이션일련번호',
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
            options: [],
            onChange: (value) => {
              setTimeout(() => {
                this.viewDatas.find(x => x.name === 'userId').svcId = value
              }, 0)
            }
          },
          {
            label: '단말일련번호',
            name: 'hndsetSeq',
            type: 'text',
            readonly: true,
            onlyView: false,
            isCreateReadOnly: true,
            value: ''
          },
          {
            label: '단말고유번호',
            name: 'hndsetInntNo',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: ''
          },
          {
            label: '단말명',
            name: 'hndsetNm',
            type: 'text',
            readonly: true,
            onlyView: false,
            value: ''
          },
          {
            label: '사용자ID',
            name: 'userId',
            type: 'user',
            readonly: false,
            onlyView: false,
            onlyAdmin: true,
            value: '',
            svcId: ''
          },
          {
            label: '어플리케이션일련번호',
            name: 'applSeq',
            type: 'app',
            readonly: true,
            onlyView: false,
            align: 'left',
            value: '',
            preLoading: true
          },
          {
            label: '단말사용여부',
            name: 'hndsetUseYn',
            type: 'select',
            readonly: true,
            onlyView: false,
            value: null,
            options: [
              {
                name: 'Y',
                value: 'Y'
              },
              {
                name: 'N',
                value: 'N'
              }
            ]
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
            min: 2,
            max: null,
            ignore: true,
            readonly: true,
            onlyView: true,
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
              .get(this.$BASEURL + '/device/arhandset/smart', {
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
              .get(this.$BASEURL + '/device/arhandset', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          const admrUuids = res.map(x => x.admrUuid).filter(x => x).join(',')
          const res1 = await this.$http
            .get(this.$BASEURL + '/user/ids', {
              params: {
                userUuid: admrUuids
              },
              headers
            })
            .then(this.NormalOrError)
            .then(res => res.data.data)

          this.resultLists = res.map(data => [
            data.svcId,
            data.hndsetSeq, // index
            data.hndsetInntNo,
            data.hndsetNm,
            data.admrUuid ? (res1.find(x => x.userUuid === data.admrUuid) || {}).userId || null : null,
            data.applSeq,
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
          if (data.type === 'user') {
            data.svcId = ''
            data.value = ''
          } else if (data.type === 'select' && data.name === 'svcId') {
            data.options = []
            data.value = null
          } else {
            data.value = ''
          }
        })

        this.viewDatas[0].value = null

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id, list) {
        try {
          const params = {
            hndsetSeq: id
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
            .get(this.$BASEURL + '/device/arhandset', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          if (res.admrUuid) {
            const params0 = {
              svcId: res.svcId,
              userUuid: res.admrUuid
            }
            const headers0 = {}

            if (['03', '04'].includes(this.auth)) {
              params0.svcId = this.user.svc_id
            }

            if (params0.svcId) {
              headers0['X-Svc-Id'] = params0.svcId
              delete params0.svcId
            }

            const res0 = await this.$http
              .get(this.$BASEURL + '/user', {
                params: params0,
                headers: headers0
              })
              .then(res => this.FirstData(res.data.data))

            if (res0) {
              res.userId = res0.userId
            }
          }

          this.viewDatas.forEach(item => {
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else if (item.name === 'svcId') {
              item.options = [{ name: value, value }]
              item.value = value
            } else if (item.name === 'customer') {
              item.value = null
              setTimeout(() => {
                item.value = value
              }, 0)
            } else if (item.type === 'user') {
              item.svcId = null
              item.value = null
              setTimeout(() => {
                item.svcId = res.svcId
                item.value = res.userId
              }, 0)
            } else if (item.type === 'app') {
              item.value = null
              setTimeout(() => {
                item.value = String(res.applSeq)
              }, 0)
            } else {
              item.value = String(value || (item.readonly ? '-' : ''))
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

          if (params.applSeq === '') {
            delete params.applSeq
          } else {
            params.applSeq = Number(params.applSeq)
          }

          delete params.hndsetSeq

          if (params.hndsetModelId === '') {
            delete params.hndsetModelId
          }

          if (params.userId) {
            const params0 = {
              userId: params.userId
            }
            const headers0 = {}

            if (['03', '04'].includes(this.auth)) {
              params0.svcId = this.user.svc_id
            }

            if (params0.svcId) {
              headers0['X-Svc-Id'] = params0.svcId
              delete params0.svcId
            }
            const res0 = await this.$http
              .get(this.$BASEURL + '/user', {
                params: params0,
                headers: headers0
              })
              .then(this.NormalOrError)
              .then(this.FirstOrError)

            params.admrUuid = res0.userUuid
          }

          delete params.userId

          await this.$http
            .post(this.$BASEURL + '/device/arhandset', params, { headers })
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
          params.hndsetSeq = Number(params.hndsetSeq)

          if (params.userId) {
            const params0 = this.pick(this.GetDetails(), ['userId', 'custId'])
            const headers0 = {}

            if (['03', '04'].includes(this.auth)) {
              params0.svcId = this.user.svc_id
            }

            if (params0.svcId) {
              headers0['X-Svc-Id'] = params0.svcId
              delete params0.svcId
            }

            const res0 = await this.$http
              .get(this.$BASEURL + '/user', {
                params: {
                  userId: params0.userId
                },
                headers: headers0
              })
              .then(this.NormalOrError)
              .then(this.FirstOrError)

            params.admrUuid = res0.userUuid
          }

          await this.$http
            .put(this.$BASEURL + '/device/arhandset', params, { headers })
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
            .delete(this.$BASEURL + '/device/arhandset', {
              data: {
                'hndsetSeq': params.hndsetSeq
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
