<template>
  <div>
    <PageTitle
      title="Q&A"
      info="문의하기 게시판입니다."
    />
    <!-- list-top Start -->
    <div class="list-top">
      <div class="list-top-btns">
        <BtagButton
          btn-type="type-02"
          btn-name="문의등록"
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
      title="문의상세"
      :view-datas="viewDatas"
      :class="{'open': this.$store.state.editSideOpen}"
      @edit-event="Edit"
      @delete-event="Delete"
      @use-edit="!['03', '04'].includes(auth)"
      @use-delete="!['03', '04'].includes(auth)"
    />
    <CreateSide
      title="문의등록"
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
    name: 'QnaList',
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
        postInfo: {},
        searchLists: [
          {
            label: '서비스ID',
            name: 'svcId',
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
            label: '서비스명',
            name: 'svcNm',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '답변자ID',
            name: 'handsetNm',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '작성자ID',
            name: 'replPrsnUuid',
            type: 'text',
            grid: 'row-3',
            value: ''
          },
          {
            label: '생성일시',
            name: 'CretDt',
            type: 'date',
            grid: 'row-1',
            from_value: '',
            to_value: ''
          },
          {
            label: '답변일시',
            name: 'AmdDt',
            type: 'date',
            grid: 'row-1',
            from_value: '',
            to_value: ''
          }
        ],
        thDatas: [
          '서비스ID',
          '번호',
          '고객ID',
          '서비스명',
          '제목',
          '답변자ID',
          '작성자ID',
          '생성일시',
          '답변생성일시'
        ],
        resultLists: [],
        viewDatas: [
          {
            label: '제목',
            name: 'pstngTitle',
            type: 'text',
            required: true,
            readonly: true,
            onlyView: false,
            wide: true,
            value: null
          },
          {
            label: '서비스ID',
            name: 'svcId',
            type: 'text',
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            label: '서비스명',
            name: 'svcNm',
            type: 'text',
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            label: '고객ID',
            name: 'custId',
            type: 'text',
            ignore: true,
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            label: '작성자ID',
            name: 'cretrId',
            type: 'text',
            ignore: true,
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            label: '작성일시',
            name: 'pstngDt',
            type: 'text',
            ignore: true,
            readonly: true,
            onlyView: true,
            value: null
          },
          {
            label: '내용',
            name: 'pstngSbst',
            type: 'textarea',
            min: null,
            max: null,
            required: true,
            readonly: true,
            onlyView: false,
            value: null
          },
          {
            label: '답변',
            name: 'replSbst',
            type: 'textarea',
            min: null,
            max: null,
            readonly: false,
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

          params.bbsId = '0000000000' // ??? 일단 UI 설계서 시퀀스에 있는 값으로 설정

          let res = []
          if (this.keywordSmart) {
            res = await this.$http
              .get(this.$BASEURL + '/board/post/search/smart', {
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
              .get(this.$BASEURL + '/board/post/search', { params, headers })
              .then(this.UpdatePageInfo)
              .then(this.NormalOrError)
              .then(res => res.data.data)
          }

          this.resultLists = res.map(data => {
            const row = [
              data.svcId,
              data.pstngSeq, // index
              data.custId,
              data.svcNm,
              data.pstngTitle,
              null, // 답변자 ID
              data.pstngPrsnUuid,
              data.pstngDt ? this.FormatDate(data.pstngDt) : null,
              null // 답변생성일시
            ].map(x => x || '-')

            return row
          })
        } catch (e) {
          console.log(e)
          alert(e.response ? e.response.data.message : e)
        } finally {
          this.$store.commit('progressComponentHide')
        }
      },
      CreateOpen () {
        this.viewDatas.forEach(data => {
          data.value = ''
        })

        this.viewDatas[0].value = null

        this.$store.commit('CreateSideComponentOpen')
      },
      async EditOpen (id, list) {
        try {
          const params = {
            bbsId: '0000000000', // ??? 일단 UI 설계서 시퀀스에 있는 값으로 설정
            pstngSeq: id,
            svcId: list[0]
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
            .get(this.$BASEURL + '/board/post', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)

          const res1 = await this.$http
            .get(this.$BASEURL + '/board/reply/search', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)
            .catch(e => null)

          const merged = { ...res, ...res1 }
          this.viewDatas.forEach(item => {
            const value = merged[item.name]
            if (this.CheckDate(value)) {
              item.value = this.FormatDate(value)
            } else {
              item.value = String(value || (item.readonly ? '-' : ''))
            }
          })

          this.postInfo = merged

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

          // params.svcId = '0000000001'

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          params.bbsId = '0000000000' // ??? 일단 UI 설계서 시퀀스에 있는 값으로 설정
          params.pstngPrsnUuid = this.user.user_uuid

          await this.$http
            .post(this.$BASEURL + '/board/post',
              this.pick(params, [
                'bbsId', 'pstngTitle', 'pstngSbst', 'pstngPrsnUuid', 'popupYn'
              ]),
              {
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
          if (!confirm('정말로 수정하시겠습니까?')) {
            return
          }

          if (!this.Requirement()) {
            return
          }

          const params = { ...this.GetDetails(), ...this.postInfo }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          const res0 = await this.$http
            .get(this.$BASEURL + '/board/reply/search', { params, headers })
            .then(this.NormalOrError)
            .then(this.FirstOrError)
            .catch(e => null)

          if (!res0) {
            await this.$http
              .post(this.$BASEURL + '/board/reply',
                this.pick(params,
                  'bbsId', 'pstngSeq', 'replPrsnUuid', 'replPrsnIp', 'replSbst'
                ),
                {
                  headers
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

          const params = { ...this.GetDetails(), ...this.postInfo }
          const headers = {}

          if (['03', '04'].includes(this.auth)) {
            params.svcId = this.user.svc_id
          }

          if (params.svcId) {
            headers['X-Svc-Id'] = params.svcId
            delete params.svcId
          }

          await this.$http
            .delete(this.$BASEURL + '/board/post', {
              data: this.pick(params, ['bbsId', 'pstngSeq']),
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
