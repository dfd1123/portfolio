<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.template')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card class="mb-4" :title="$t('menu.template')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="openCreate"
            >등록</b-button
          >
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
      title="질문지 등록"
      modal-class="modal-right"
      :no-close-on-backdrop="true"
    >
      <b-form>
        <b-form-group label="질문지번호">
          <b-form-input
            type="number"
            v-model.number="modalCreate.templateOrder"
          />
        </b-form-group>
        <b-form-group label="질문지 메인제목(ko)">
          <b-form-input v-model="modalCreate.templateTitle" />
        </b-form-group>
        <b-form-group label="질문지 서브제목(en)">
          <b-form-input v-model="modalCreate.templateTitleEn" />
        </b-form-group>
        <b-form-group label="질문지 설명">
          <b-form-input v-model="modalCreate.templateDesc" />
        </b-form-group>
      </b-form>
      <p style="color:#0100FF">
        내용에 &#x3C;br&#x3E; 을 입력하시면 개행됩니다
      </p>
      <p style="color:#0100FF">질문지 내용은 등록 후 작성할 수 있습니다</p>
      <template slot="modal-footer">
        <b-button variant="success" @click="create" class="mr-1">등록</b-button>
        <b-button variant="secondary" @click="hideModal('modal-create')"
          >취소</b-button
        >
      </template>
    </b-modal>

    <b-modal id="modal-edit" ref="modal-edit" title="질문지 상세페이지">
      <b-form>
        <b-form-group label="질문지ID">
          <p>{{ modalEdit.templateId }}</p>
        </b-form-group>
        <b-form-group label="질문지번호">
          <b-form-input v-model="modalEdit.templateOrder" />
        </b-form-group>
        <b-form-group label="질문지 메인제목(ko)">
          <b-form-input v-model="modalEdit.templateTitle" />
        </b-form-group>
        <b-form-group label="질문지 서브제목(en)">
          <b-form-input v-model="modalEdit.templateTitleEn" />
        </b-form-group>
        <b-form-group label="질문지 설명">
          <b-form-input v-model="modalEdit.templateDesc" />
        </b-form-group>
        <b-form-group label="생성일">
          <p>{{ modalEdit.createdAt }}</p>
        </b-form-group>
        <b-form-group label="변경일">
          <p>{{ modalEdit.updatedAt }}</p>
        </b-form-group>
        <b-button variant="outline-info" @click="openNested1(modalEdit)"
          >내용 열기</b-button
        >
      </b-form>
      <template slot="modal-footer">
        <b-button variant="outline-info" @click="edit">수정</b-button>
        <b-button variant="outline-secondary" @click="hideModal('modal-edit')"
          >닫기</b-button
        >
      </template>
    </b-modal>

    <b-modal
      id="modal-nested-1"
      ref="modal-nested-1"
      size="lg"
      title="질문지 내용"
    >
      <b-row v-for="(step, index) in modalNested1" :key="index">
        <b-colxx xxs="12">
          <div v-for="(page, pageIndex) in step" :key="pageIndex">
            <b-card :title="`Page ${pageIndex + 1}`" class="mb-3">
              <b-form v-for="(qna, index) in page" :key="index">
                <b-form-group
                  label-cols-sm="4"
                  label-cols-lg="3"
                  :label="
                    `질문 ${[
                      0,
                      ...Array.from(
                        { length: pageIndex },
                        (x, i) => step[i].length
                      )
                    ].reduce((a, x) => a + x) +
                      index +
                      1}`
                  "
                  class="text-primary"
                >
                  <b-button
                    class="btn float-right ml-1"
                    size="sm"
                    variant="danger"
                    @click="remove(index, page)"
                    >제거</b-button
                  >
                  <b-button
                    class="btn float-right"
                    size="sm"
                    variant="success"
                    @click="insertItem(index, page)"
                    >추가</b-button
                  >
                </b-form-group>
                <b-form-group label-cols-sm="4" label-cols-lg="3" label="범주">
                  <b-form-input v-model="qna.category" />
                </b-form-group>
                <b-form-group label-cols-sm="4" label-cols-lg="3" label="질문">
                  <b-form-input v-model="qna.question" />
                </b-form-group>
                <b-form-group
                  label-cols-sm="4"
                  label-cols-lg="3"
                  label="답변목록"
                >
                  <b-form-input
                    v-for="(choice, index) in qna.choice"
                    :key="index"
                    v-model="qna.choice[index]"
                    class="mb-1"
                  />
                </b-form-group>
                <b-form-group label-cols-sm="4" label-cols-lg="3" label="종류">
                  <b-form-select
                    v-model="qna.type"
                    plain
                    :options="[
                      { value: 'a', text: '역문항' },
                      { value: 'b', text: '정문항' }
                    ]"
                    class="mb-3"
                  />
                </b-form-group>
              </b-form>
              <b-button
                class="btn float-right ml-1"
                size="sm"
                variant="danger"
                @click="remove(pageIndex, step)"
                >페이지 제거</b-button
              >
              <b-button
                class="btn float-right"
                size="sm"
                variant="success"
                @click="insertPage(pageIndex, step)"
                >페이지 추가</b-button
              >
            </b-card>
          </div>
        </b-colxx>
      </b-row>
      <template slot="modal-footer">
        <b-button
          variant="btn btn-outline-secondary"
          @click="hideModal('modal-nested-1')"
          >닫기</b-button
        >
      </template>
    </b-modal>
    <b-button
      variant="danger"
      class="mb-2 mr-3"
      @click="$refs.excelUpload.click()"
    >
      엑셀 업로드
      <input
        ref="excelUpload"
        @change="importExcel"
        type="file"
        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        style="display: none"
      />
    </b-button>
    <b-button variant="success" class="mb-2" @click="exportExcel"
      >엑셀 다운로드</b-button
    >
  </div>
</template>

<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'
import { saveAs } from 'file-saver'

export default {
  name: 'Template',
  components: {
    Vuetable,
    VuetablePaginationBootstrap
  },
  data () {
    return {
      table: {
        apiUrl: '/cp_test_templates/paginate',
        fields: [
          {
            name: 'cpt_id',
            title: '질문지ID'
          },
          {
            name: 'cpt_order',
            title: '질문지번호'
          },
          {
            name: 'cpt_title',
            title: '질문지 메인제목(ko)'
          },
          {
            name: 'cpt_title_en',
            title: '질문지 서브제목(en)'
          },
          {
            name: 'cpt_desc',
            title: '질문지 설명'
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
        templateOrder: null,
        templateTitle: null,
        templateTitleEn: null,
        templateDesc: null
      },
      modalEdit: {
        batchName: null,
        batchDate: null
      },
      modalNested1: {
        list: null
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
        templateOrder: null,
        templateTitle: null,
        templateTitleEn: null,
        templateDesc: null
      }
      this.showModal('modal-create')
    },
    openEdit (row) {
      this.modalEdit = {
        templateId: row.cpt_id,
        templateOrder: row.cpt_order,
        templateTitle: row.cpt_title,
        templateTitleEn: row.cpt_title_en,
        templateDesc: row.cpt_desc,
        templateQuestion:
          JSON.parse(JSON.stringify(row.cpt_question)) ||
          this.insertPage(0, []),
        createdAt: row.created_at,
        updatedAt: row.updated_at
      }
      this.showModal('modal-edit')
    },
    openNested1 () {
      this.modalNested1 = {
        list: this.modalEdit.templateQuestion
      }
      this.showModal('modal-nested-1')
    },
    async create () {
      try {
        this.$store.commit('setProcessing', true)

        const data = {
          cpt_order: this.modalCreate.templateOrder,
          cpt_title: this.modalCreate.templateTitle,
          cpt_title_en: this.modalCreate.templateTitleEn,
          cpt_desc: this.modalCreate.templateDesc
        }

        const params = Object.fromEntries(
          Object.entries(data).map(([key, value]) => [key, value || null])
        )

        if (!Object.entries(params).every(([key, value]) => value)) {
          this.$notify('error', '오류', '값을 입력해주세요', {
            duration: 1000,
            permanent: false
          })
          return
        }

        await this.$axios.post('/cp_test_templates', params)
        await this.$refs.vuetable.refresh()

        this.$notify('success', '안내', '등록되었습니다', {
          duration: 1000,
          permanent: false
        })
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
          cpt_order: this.modalEdit.templateOrder,
          cpt_title: this.modalEdit.templateTitle,
          cpt_title_en: this.modalEdit.templateTitleEn,
          cpt_desc: this.modalEdit.templateDesc,
          cpt_question: this.modalEdit.templateQuestion
        }

        const params = Object.fromEntries(
          Object.entries(data).map(([key, value]) => [key, value || null])
        )

        await this.$axios.put(
          `/cp_test_templates/${this.modalEdit.templateId}`,
          params
        )
        await this.$refs.vuetable.refresh()

        this.$notify('primary', '안내', '변경되었습니다', {
          duration: 1000,
          permanent: false
        })
        this.hideModal('modal-edit')
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.message : e)
      } finally {
        this.$store.commit('setProcessing', false)
      }
    },
    remove (index, list) {
      if (confirm('해당 항목을 삭제하시겠습니까?')) {
        list.splice(index, 1)
      }
    },
    insertPage (index, list) {
      list.splice(index + 1, 0, [
        { type: null, value: null, category: null, question: null }
      ])
      return list
    },
    insertItem (index, list) {
      list.splice(index + 1, 0, {
        type: null,
        value: null,
        category: null,
        question: null
      })
      return list
    },
    async exportExcel () {
      try {
        this.$store.commit('setProcessing', true)

        await this.$axios
          .get(`/cp_test_templates/export`, {
            responseType: 'blob'
          })
          .then(res => {
            console.log(res)
            saveAs(new Blob([res.data]), 'template.xlsx')
          })
          .catch(e => e.response)
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.message : e)
      } finally {
        this.$store.commit('setProcessing', false)
      }
    },
    async importExcel (e) {
      try {
        this.$store.commit('setProcessing', true)

        const formData = new FormData()
        formData.append('file', this.$refs.excelUpload.files[0])

        await this.$axios.post(`/cp_test_templates/import`, formData)
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.message : e)
      } finally {
        e.target.value = ''
        this.$store.commit('setProcessing', false)
      }
    },
    onRowClass (dataItem, index) {
      return Number(index) % 2 === 0 ? 'bg-semi-muted' : ''
    }
  }
}
</script>

<style scoped>
</style>
