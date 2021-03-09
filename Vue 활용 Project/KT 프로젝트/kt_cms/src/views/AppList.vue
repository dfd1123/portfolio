<template>
  <div>
    <PageTitle
      title="애플리케이션"
      info="새로운 APP을 등록하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="애플리케이션등록"
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
      title="애플리케이션상세"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
    />
    <CreateSide
      title="애플리케이션등록"
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

  export default {
    name: 'AppList',
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
            label: '애플리케이션일련번호',
            name: 'applSeq',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '애플리케이션ID',
            name: 'applId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '애플리케이션버전',
            name: 'applVer',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '사용자명',
            name: 'userId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '애플리케이션일련번호',
            name: 'applSeq',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '최신버전여부',
            name: 'nwstVerYn',
            type: 'select',
            grid: 'row-3',
            value: null,
            options: [
              {
                name: '전체',
                value: null
              },
              {
                name: 'Yes',
                value: 'Y'
              },
              {
                name: 'No',
                value: 'N'
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
          '애플리케이션 일련번호',
          '애플리케이션ID',
          '애플리케이션 버전',
          '최신버전 여부',
          '애플리케이션설명',
          '등록자ID',
          '최근수정자ID',
          '등록일시',
          '최근수정일시'
        ],
        resultLists: [],
        viewDatas: [
          {
            label: '애플리케이션일련번호',
            name: 'applSeq',
            type: 'text',
            readonly: true,
            onlyView: false,
            value: null
          },
          {
            label: '애플리케이션ID',
            name: 'applId',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            value: null
          },
          {
            label: '애플리케이션 버전',
            name: 'applVer',
            type: 'text',
            required: true,
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '최신버전 여부',
            name: 'nwstVerYn',
            type: 'select',
            readonly: false,
            onlyView: false,
            value: null,
            options: [
              {
                name: 'Yes',
                value: 'Y'
              },
              {
                name: 'No',
                value: 'N'
              }
            ]
          },
          {
            label: '클라이언트ID',
            name: 'clntId',
            type: 'text',
            required: true,
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '클라이언트secret',
            name: 'clntScrtKeyVal',
            type: 'text',
            readonly: false,
            onlyView: false,
            value: null
          },
          {
            label: '어플리케이션 설명',
            name: 'applDesc',
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
              .get(this.$BASEURL + '/application/history/smart', {
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
            .get(this.$BASEURL + '/application/history', { params, headers })
            .then(this.UpdatePageInfo)
            .then(this.NormalOrError)
            .then(res => res.data.data)
          }

          this.resultLists = res.map(data => [
            data.applSeq, // index
            data.applId,
            data.applVer,
            data.nwstVerYn,
            data.applDesc,
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
          data.value = null
        })

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id, list) {
        try {
          const params = {
            applSeq: id
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
            .get(this.$BASEURL + '/application/history', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          this.viewDatas.forEach(item => {
            const value = res[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
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

          await this.$http
            .post(this.$BASEURL + '/application/history', params, { headers })
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
            .put(this.$BASEURL + '/application/history', params, { headers })
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
            .delete(this.$BASEURL + '/application/history', {
              data: {
                'applSeq': params.applSeq
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
