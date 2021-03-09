<template>
  <div>
    <PageTitle
      title="뷰디자이너"
      info="새로운 AR컨텐츠를 저작하는 페이지입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="AR저작하기"
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
      :title="editSideTitle"
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
  import ExcelButton from '@/components/button/ExcelButton.vue'
  import Input from '@/components/form/input.vue'
  import FilterButton from '@/components/button/FilterButton.vue'
  import SearchWrap from '@/components/searchBox/SearchWrap.vue'
  import SearchList from '@/components/search_list/SearchList.vue'
  import EditSide from '@/components/sideBox/EditSide.vue'
  import CreateSide from '@/components/sideBox/CreateSide.vue'
  import Pagination from '@/components/list_component/Pagination.vue'

  export default {
    name: 'ViewList',
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
            label: '컨텐츠ID',
            name: 'unitSvcId',
            type: 'text',
            grid: 'row-3',
            value: null
          },
          {
            label: '설비명',
            name: 'unitSvcNm',
            type: 'text',
            grid: 'row-3',
            value: null
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
          '컨텐츠ID',
          '설비명',
          '자료개수',
          '등록자ID',
          '최근수정자ID',
          '등록일시',
          '최근수정일시'
        ],
        resultLists: [],
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
              .get(this.$BASEURL + '/content/smart', {
                params: {
                  input: this.keywordSmart,
                  ...this.pick(params, ['page', 'size'])
                },
                headers
              })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
              .catch(e => [])
          } else {
            res = await this.$http
              .get(this.$BASEURL + '/content', {
                params: { ...params, contsCtgCd: '03' },
                headers
              })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
              .catch(e => [])
          }

          this.resultLists = res.map(data => {
            return [
              data.svcId,
              data.contsId, // index
              data.facNm,
              data.contsCnt,
              data.cretrId,
              data.amdrId,
              data.cretDt ? this.FormatDate(data.cretDt) : null,
              data.amdDt ? this.FormatDate(data.amdDt) : null
            ].map(x => x || '-')
          })
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      },
      async CreateOpen () {
        alert('AR 저작도구는 준비중입니다')
      },
      async EditOpen (id) {
        alert('AR 저작도구는 준비중입니다')
      },
      async Create () {
        //
      },
      async Edit () {
        //
      },
      async Delete () {
        //
      }
    }
  }
</script>

<style scoped>
</style>
