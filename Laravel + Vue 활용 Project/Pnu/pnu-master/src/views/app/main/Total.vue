<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.total')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <div class="mb-2">
      <b-button
        variant="empty"
        class="pt-0 pl-0 d-inline-block d-md-none"
        v-b-toggle.displayOptions
      >
        {{ $t('todo.display-options') }}
        <i class="simple-icon-arrow-down align-middle" />
      </b-button>

      <div class="d-block d-md-inline-block pt-1 mr-3">
        <b-dropdown
          :text="`검색: ${findBy.label}`"
          variant="outline-dark"
          class="mr-1 float-md-left btn-group"
          size
        >
          <b-dropdown-item
            v-for="(find,index) in findByOptions"
            :key="`find${index}`"
            @click="changeCondition(find)"
          >{{ find.label }}</b-dropdown-item>
        </b-dropdown>
        <div class="d-inline-block float-md-left mr-1 align-top">
          <b-input :placeholder="$t('menu.search')" v-model="search" />
        </div>
      </div>

      <div class="d-inline-block mr-1 align-top" style="padding-top: 15px">
        <span>차수ID:</span>
      </div>
      <div class="d-inline-block mr-1 align-top" style="padding-top: 5px">
        <b-input id="input-small" v-model="batchId" />
      </div>

      <div class="d-block d-md-inline-block pt-1 align-top mr-3">
        <b-button variant="info" class="mr-1 btn-group" @click="$refs.vuetable.reload()">검색</b-button>
      </div>

      <b-dropdown
        :text="`정렬: ${orderBy.label}`"
        variant="outline-dark"
        class="mr-1 float-md-right btn-group"
        size
      >
        <b-dropdown-item
          v-for="(order,index) in orderByOptions"
          :key="`order${index}`"
          @click="changeOrderBy(order)"
        >{{ order.label }}</b-dropdown-item>
      </b-dropdown>
      <b-dropdown
        :text="`기준: ${sortBy.label}`"
        variant="outline-dark"
        class="mr-1 float-md-right btn-group"
        size
      >
        <b-dropdown-item
          v-for="(sort,index) in sortByOptions"
          :key="`sort${index}`"
          @click="changeSortBy(sort)"
        >{{ sort.label }}</b-dropdown-item>
      </b-dropdown>
    </div>
    <div class="separator mb-5" />

    <b-row>
      <b-colxx xxs="12">
        <b-card class="mb-4" :title="$t('menu.total')">
          <vuetable
            ref="vuetable"
            :load-on-start="false"
            :api-url="table.apiUrl"
            :fields="table.fields"
            :http-fetch="httpFetch"
            :append-params="appendData()"
            :row-class="onRowClass"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
          >
            <template v-slot:actions="{rowData}">
              <b-button variant="danger" size="xs" @click="deleteRecord(rowData)">기록초기화</b-button>
            </template>
          </vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
      </b-colxx>
    </b-row>

    <b-button variant="success" class="mb-2" @click="exportExcel">엑셀 다운로드</b-button>
  </div>
</template>

<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'
import { saveAs } from 'file-saver'

export default {
  name: 'Total',
  components: {
    Vuetable,
    VuetablePaginationBootstrap
  },
  data () {
    return {
      batchId: null,
      search: null,
      keyword: null,
      findBy: {
        label: '유저ID',
        value: 'user_no'
      },
      findByOptions: [
        {
          label: '유저ID',
          value: 'user_no'
        },
        {
          label: '유저명',
          value: 'user_name'
        }
      ],
      sortBy: {
        label: '전체',
        value: null
      },
      sortByOptions: [
        {
          label: '전체',
          value: null
        },
        {
          label: '유저ID',
          value: 'user_no'
        },
        {
          label: '유저명',
          value: 'user_name'
        },
        {
          label: '총점',
          value: 'total'
        }
      ],
      orderBy: {
        label: '내림차순',
        value: 'desc'
      },
      orderByOptions: [
        {
          label: '내림차순',
          value: 'desc'
        },
        {
          label: '오름차순',
          value: 'asc'
        }
      ],
      table: {
        apiUrl: '/user_cp_test_result_totals/paginate',
        fields: [
          {
            name: 'user_no',
            title: '유저ID'
          },
          {
            name: 'name',
            title: '유저명'
          },
          {
            name: 'sta',
            title: '학적상태명'
          },
          {
            name: 'stdyear',
            title: '학년'
          },
          {
            name: 'dept',
            title: '소속(학과)명'
          },
          {
            name: 'total',
            title: '총점'
          },
          { name: '__slot:actions', title: '작업' }
        ]
      }
    }
  },
  async mounted () {
    try {
      this.$store.commit('setProcessing', true)
      await this.$refs.vuetable.reload()
    } finally {
      this.$store.commit('setProcessing', false)
    }
  },
  methods: {
    httpFetch (apiUrl, httpOptions) {
      return this.$axios.get(apiUrl, httpOptions)
    },
    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    },
    showModal (refname) {
      this.$refs[refname].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    changeCondition (row) {
      this.findBy.label = row.label
      this.findBy.value = row.value
    },
    changeSortBy (row) {
      this.sortBy.label = row.label
      this.sortBy.value = row.value
    },
    changeOrderBy (row) {
      this.orderBy.label = row.label
      this.orderBy.value = row.value
    },
    appendData () {
      const params = {
        order_by: this.orderBy.value
      }

      if (this.findBy.value) {
        params[this.findBy.value] = this.search || null
      }

      if (this.sortBy.value) {
        params.sort_by = this.sortBy.value || null
      }

      if (this.batchId) {
        params.batch_id = this.batchId || null
      }

      return params
    },
    async exportExcel () {
      if (!this.batchId) {
        alert('차수ID를 입력해야 합니다.')
        return
      }

      try {
        this.$store.commit('setProcessing', true)

        await this.$axios
          .get(`/user_cp_test_results/raw_total`, {
            responseType: 'blob',
            params: {
              batch_id: this.batchId
            }
          })
          .then(res => {
            saveAs(new Blob([res.data]), 'raw_total.xlsx')
          })
      } catch (e) {
        alert('오류발생: 차수ID가 정확한지 확인해 주시기 바랍니다.')
        console.log(e)
      } finally {
        this.$store.commit('setProcessing', false)
      }
    },
    async deleteRecord (rowData) {
      if (!confirm('해당 기록을 초기화 하시겠습니까?')) {
        return
      }

      if (!this.batchId) {
        alert('차수ID를 입력해야 합니다. (입력한 해당 차수 기록 초기화)')
        return
      }

      try {
        this.$store.commit('setProcessing', true)

        await this.$axios.delete(`/user_cp_test_results/record`, {
          responseType: 'blob',
          params: {
            batch_id: this.batchId,
            user_id: rowData.user_id
          }
        })

        await this.$refs.vuetable.reload()

        this.$notify('success', '성공', '해당 기록이 초기화되었습니다', {
          duration: 3000,
          permanent: false
        })
      } catch (e) {
        alert('오류발생: 차수ID가 정확한지 확인해 주시기 바랍니다.')
        console.log(e)
      } finally {
        this.$store.commit('setProcessing', false)
      }
    },
    onRowClass (dataItem, index) {
      return Number(index) % 2 === 0 ? 'bg-semi-muted' : ''
    }
  }
}
</script>
