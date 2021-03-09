<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.batch')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card class="mb-4" :title="$t('menu.batch')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="openCreate"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :load-on-start="false"
            :api-url="table.apiUrl"
            :fields="table.fields"
            :http-fetch="httpFetch"
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

    <b-modal
      id="modal-create"
      ref="modal-create"
      title="차수 등록"
      modal-class="modal-right"
      :no-close-on-backdrop="true"
    >
      <b-form>
        <b-form-group label="차수">
          <b-form-input v-model="modalCreate.batchName" />
        </b-form-group>
        <b-form-group label="기간">
          <v-date-picker
            mode="range"
            v-model="modalCreate.batchDate"
            :input-props="{ class:'form-control', placeholder: $t('form-components.date') }"
          ></v-date-picker>
        </b-form-group>
      </b-form>
      <template slot="modal-footer">
        <b-button variant="success" @click="create" class="mr-1">등록</b-button>
        <b-button variant="secondary" @click="hideModal('modal-create')">취소</b-button>
      </template>
    </b-modal>

    <b-modal id="modal-edit" ref="modal-edit" title="차수 상세페이지">
      <b-form>
        <b-form-group label="차수ID">
          <p>{{modalEdit.batchId}}</p>
        </b-form-group>
        <b-form-group label="차수">
          <b-form-input v-model="modalEdit.batchName" />
        </b-form-group>
        <b-form-group label="기간">
          <v-date-picker
            mode="range"
            v-model="modalEdit.batchDate"
            :input-props="{ class:'form-control', placeholder: $t('form-components.date') }"
          ></v-date-picker>
        </b-form-group>
        <b-form-group label="생성일">
          <p>{{modalEdit.createdAt}}</p>
        </b-form-group>
        <b-form-group label="변경일">
          <p>{{modalEdit.updatedAt}}</p>
        </b-form-group>
      </b-form>
      <template slot="modal-footer">
        <b-button variant="outline-info" @click="edit">수정</b-button>
        <b-button variant="outline-secondary" @click="hideModal('modal-edit')">닫기</b-button>
      </template>
    </b-modal>
  </div>
</template>

<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'

export default {
  name: 'Batch',
  components: {
    Vuetable,
    VuetablePaginationBootstrap
  },
  data () {
    return {
      table: {
        apiUrl: '/batches/paginate',
        fields: [
          {
            name: 'batch_id',
            title: '차수ID'
          },
          {
            name: 'batch_name',
            title: '차수'
          },
          {
            name: 'batch_start',
            title: '시작일'
          },
          {
            name: 'batch_end',
            title: '종료일'
          },
          {
            name: 'batch_count',
            title: '응시인원수'
          },
          {
            name: 'admin_id',
            title: '관리자ID'
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
      modalCreate: {
        batchName: null,
        batchDate: null
      },
      modalEdit: {
        batchName: null,
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
    openCreate () {
      this.modalCreate = {
        batchName: null,
        batchDate: null
      }
      this.showModal('modal-create')
    },
    openEdit (row) {
      this.modalEdit = {
        batchId: row.batch_id,
        batchName: row.batch_name,
        batchDate: {
          start: new Date(row.batch_start),
          end: new Date(row.batch_end)
        },
        createdAt: row.created_at,
        updatedAt: row.updated_at
      }
      this.showModal('modal-edit')
    },
    async create () {
      try {
        this.$store.commit('setProcessing', true)

        const data = {
          batch_name: this.modalCreate.batchName,
          batch_start: this.modalCreate.batchDate && this.modalCreate.batchDate.start && this.$moment(this.modalCreate.batchDate.start).format('YYYY-MM-DD HH:mm:ss'),
          batch_end: this.modalCreate.batchDate && this.modalCreate.batchDate.end && this.$moment(this.modalCreate.batchDate.end).format('YYYY-MM-DD HH:mm:ss')
        }

        const params = Object.fromEntries(Object.entries(data).map(([key, value]) => [key, value || null]))

        if (!Object.entries(params).every(([key, value]) => value)) {
          this.$notify('error', '오류', '값을 입력해주세요', { duration: 1000, permanent: false })
          return
        }

        await this.$axios.post('/batches', params)
        await this.$refs.vuetable.refresh()

        this.$notify('success', '안내', '등록되었습니다', { duration: 1000, permanent: false })
        this.hideModal('modal-create')
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.message : e)
      } finally {
        this.$store.commit('setProcessing', false)
      }
    },
    async edit () {
      try {
        this.$store.commit('setProcessing', true)

        const data = {
          batch_name: this.modalEdit.batchName,
          batch_start: this.modalEdit.batchDate && this.modalEdit.batchDate.start && this.$moment(this.modalEdit.batchDate.start).format('YYYY-MM-DD HH:mm:ss'),
          batch_end: this.modalEdit.batchDate && this.modalEdit.batchDate.end && this.$moment(this.modalEdit.batchDate.end).format('YYYY-MM-DD HH:mm:ss')
        }

        const params = Object.fromEntries(Object.entries(data).map(([key, value]) => [key, value || null]))

        await this.$axios.put(`/batches/${this.modalEdit.batchId}`, params)
        await this.$refs.vuetable.refresh()

        this.$notify('primary', '안내', '변경되었습니다', { duration: 1000, permanent: false })
        this.hideModal('modal-edit')
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.message : e)
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
