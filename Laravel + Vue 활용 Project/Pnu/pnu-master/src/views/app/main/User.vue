<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.user')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <div class="mb-2">
      <div class="d-block d-md-inline-block pt-1">
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
      <div class="d-block d-md-inline-block pt-1 align-top">
        <b-button variant="info" class="mr-1 btn-group" @click="$refs.vuetable.reload()">검색</b-button>
      </div>
    </div>
    <b-row>
      <b-colxx xxs="12">
        <b-card class="mb-4" :title="$t('menu.user')">
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
            @vuetable:row-clicked="rowClicked"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
      </b-colxx>
    </b-row>

    <b-modal id="modal-edit" ref="modal-edit" title="차수 상세페이지">
      <b-form>
        <b-form-group label="유저 ID (내부식별용)">
          <p>{{modalEdit.userId}}</p>
        </b-form-group>
        <b-form-group label="성명">
          <p>{{modalEdit.name}}</p>
        </b-form-group>
        <b-form-group label="학번">
          <p>{{modalEdit.userNo}}</p>
        </b-form-group>
        <b-form-group label="학년">
          <p>{{modalEdit.stdyear}}</p>
        </b-form-group>
        <b-form-group label="전공">
          <p>{{modalEdit.major}}</p>
        </b-form-group>
        <b-form-group label="학과">
          <p>{{modalEdit.dept}}</p>
        </b-form-group>
        <b-form-group label="단과대학">
          <p>{{modalEdit.coll}}</p>
        </b-form-group>
        <b-form-group label="학적상태">
          <p>{{modalEdit.sta}}</p>
        </b-form-group>
        <b-form-group label="생성일">
          <p>{{modalEdit.createdAt}}</p>
        </b-form-group>
        <b-form-group label="변경일">
          <p>{{modalEdit.updatedAt}}</p>
        </b-form-group>
      </b-form>
      <template slot="modal-footer">
        <b-button variant="outline-secondary" @click="hideModal('modal-edit')">닫기</b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'

export default {
  name: 'User',
  components: {
    Vuetable,
    VuetablePaginationBootstrap
  },
  data () {
    return {
      search: null,
      table: {
        apiUrl: '/users/paginate',
        fields: [
          {
            name: 'user_id',
            title: '유저 ID'
          },
          {
            name: 'name',
            title: '성명'
          },
          {
            name: 'user_no',
            title: '학번'
          },
          {
            name: 'stdyear',
            title: '학년'
          },
          {
            name: 'major',
            title: '전공'
          },
          {
            name: 'dept',
            title: '학과'
          },
          {
            name: 'coll',
            title: '단과대학'
          },
          {
            name: 'sta',
            title: '학적상태'
          },
          {
            name: 'created_at',
            title: '생성일'
          },
          {
            name: 'updated_at',
            title: '변경일'
          }
        ]
      },
      findBy: {
        label: '유저ID',
        value: 'user_id'
      },
      findByOptions: [
        {
          label: '유저ID',
          value: 'user_id'
        },
        {
          label: '학번',
          value: 'user_no'
        },
        {
          label: '성명',
          value: 'user_name'
        },
        {
          label: '학년',
          value: 'stdyear'
        }
      ],
      modalEdit: {
        userId: null,
        userNo: null,
        name: null,
        gbn: null,
        sta: null,
        deptcd: null,
        dept: null,
        collcd: null,
        coll: null,
        majorcd: null,
        major: null,
        stdyear: null,
        batchDate: null
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
    rowClicked (row) {
      this.openEdit(row)
    },
    openEdit (row) {
      this.modalEdit = {
        userId: row.user_id,
        userNo: row.user_no,
        name: row.name,
        gbn: row.gbn,
        sta: row.sta,
        deptcd: row.deptcd,
        dept: row.dept,
        collcd: row.collcd,
        coll: row.coll,
        majorcd: row.majorcd,
        major: row.major,
        stdyear: row.stdyear,
        createdAt: row.created_at,
        updatedAt: row.updated_at
      }
      this.showModal('modal-edit')
    },
    changeCondition (row) {
      this.findBy.label = row.label
      this.findBy.value = row.value
    },
    appendData () {
      const params = {}

      if (this.findBy.value) {
        params[this.findBy.value] = this.search || null
      }

      return params
    },
    onRowClass (dataItem, index) {
      return Number(index) % 2 === 0 ? 'bg-semi-muted' : ''
    }
  }
}
</script>

<style scoped>
</style>
