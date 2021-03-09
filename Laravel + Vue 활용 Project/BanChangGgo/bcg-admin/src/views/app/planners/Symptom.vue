<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.symptom')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.symptom')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="Symptom.apiUrl"
            :fields="Symptom.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="sptClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="증상 상세 정보">
          <b-form>
            <b-form-group label="증상 이름">
              <b-form-input v-model="selectedSymptom.spt_title" />
            </b-form-group>
            <b-form-group label="증상 아이콘 이미지">
              <img :src="selectedSymptom.url" class="mb-2" style="width:100%;" />
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="file"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="selectedPreview"
                ></b-form-file>
              </b-input-group>
            </b-form-group>
            <b-form-group label="컨텐츠">
              <table style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>이름</th>
                    <th>설명</th>
                    <th>선택</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="smptm in symptom_list" :key="smptm.smptm_no">
                    <td>{{smptm.smptm_title}}</td>
                    <td>{{smptm.smptm_desc}}</td>
                    <td>
                      <input
                        type="checkbox"
                        :value="smptm.smptm_no"
                        v-model="selectedSymptom.spt_contents"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-form-group>
          </b-form>
          <template slot="modal-footer">
            <b-button
              variant="info"
              v-if="selectedSymptom.state == 0"
              @click="symptomStateUdp(1)"
            >활성화 하기</b-button>
            <b-button
              variant="secondary"
              v-if="selectedSymptom.state == 1"
              @click="symptomStateUdp(0)"
            >비활성화 하기</b-button>
            <b-button variant="outline-info" @click="symptomUdp()">수정</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="증상 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="증상 이름">
              <b-form-input v-model="newSymptom.spt_title" />
            </b-form-group>
            <b-form-group label="증상 아이콘 이미지">
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="newSymptom.spt_thumb"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="preview"
                ></b-form-file>
              </b-input-group>
            </b-form-group>
            <b-form-group label="미리보기">
              <img ref="preview-img" :src="newSymptom.url" style="width:100%;" />
            </b-form-group>
            <b-form-group label="컨텐츠">
              <table style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>이름</th>
                    <th>설명</th>
                    <th>선택</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="smptm in symptom_list" :key="smptm.smptm_no">
                    <td>{{smptm.smptm_title}}</td>
                    <td>{{smptm.smptm_desc}}</td>
                    <td>
                      <input
                        type="checkbox"
                        :value="smptm.smptm_no"
                        v-model="newSymptom.spt_contents"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-form-group>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="success" @click="regist" class="mr-1">등록</b-button>
            <b-button variant="secondary" @click="hideModal('modalright')">취소</b-button>
          </template>
        </b-modal>
      </b-colxx>
    </b-row>
  </div>
</template>
<script>
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'
export default {
  name: 'Symptom',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Symptom: {
        apiUrl: '/symptoms/paginate',
        fields: [{
          name: 'spt_title',
          title: '증상 이름'
        }, {
          name: 'stp_thumb',
          title: '증상 아이콘 이미지'
        }, {
          name: 'spt_contents',
          title: '세부 컨텐츠'
        }, {
          name: 'state',
          title: '상태',
          formatter (value) {
            return ['비활성', '활성'][value]
          }
        }]
      },
      selectedSymptom: {
        spt_no: '',
        spt_title: '',
        spt_thumb: '',
        spt_contents: [],
        state: '',
        url: ''
      },
      newSymptom: {
        spt_title: '',
        spt_thumb: '',
        spt_contents: [],
        url: ''
      },
      file: '',
      symptom_list: [],
      smptm_check: []
    }
  },
  async created () {
    await this.loadInfo()
  },
  methods: {
    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    },
    myFetch (apiUrl, httpOptions) {
      return this.$axios.get(apiUrl, httpOptions)
    },
    hideModal (refname) {
      this.newSymptom.spt_title = ''
      this.newSymptom.spt_thumb = ''
      this.newSymptom.spt_contents = []
      this.newSymptom.url = ''
      this.$refs[refname].hide()
      console.log('hide modal:: ' + refname)
    },
    async loadInfo () {
      try {
        await this.$axios.get('/symptom_infos').then((response) => {
          for (var i = 0; i < response.data.length; i++) {
            this.symptom_list = response.data
          }
        })
      } catch (e) {
        console.log(e)
      }
    },
    sptClick (row) {
      this.selectedSymptom.spt_no = row.spt_no
      this.selectedSymptom.spt_title = row.spt_title
      this.selectedSymptom.spt_thumb = row.spt_thumb
      this.selectedSymptom.url = '/fdata/symptom_thumb/' + row.spt_thumb
      this.selectedSymptom.spt_contents = []
      for (var i = 0; i < JSON.parse(row.spt_contents).length; i++) {
        this.selectedSymptom.spt_contents.push(JSON.parse(row.spt_contents)[i])
      }
      this.selectedSymptom.state = row.state
      this.$refs['modalbackdrop'].show()
    },
    symptomDelete () {
      this.$refs['modalbackdrop'].hide()
    },
    symptomStateUdp (state) {
      try {
        this.$axios.post('/symptoms/' + this.selectedSymptom.spt_no, {
          state: state
        }).then((response) => {
          this.$refs.vuetable.refresh()
          this.$refs['modalbackdrop'].hide()
          this.addNotification('primary')
        })
      } catch (e) {
        console.log(e)
      }
    },
    addNotification (
      type = 'success',
      title = '안내',
      message = '변경되었습니다'
    ) {
      this.$notify(type, title, message, { duration: 3000, permanent: false })
    },
    symptomUdp () {
      if (this.selectedSymptom.spt_title !== '' && this.selectedSymptom.spt_title !== undefined) {
        try {
          // eslint-disable-next-line no-unused-vars
          const formData = new FormData()
          if (this.file) {
            formData.append('file1', this.file)
          }
          formData.append('spt_title', this.selectedSymptom.spt_title)
          formData.append('spt_contents', JSON.stringify(this.selectedSymptom.spt_contents))
          this.$axios.post('/symptoms/' + this.selectedSymptom.spt_no, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          }).then((response) => {
            this.$refs.vuetable.refresh()
            this.$refs['modalbackdrop'].hide()
            this.addNotification('primary')
          })
        } catch (e) {
          console.log(e)
        }
      } else {
        this.addNotification('error filled', '안내', '모든 항목을 채워주세요')
      }
    },
    rightmodal () {
      this.newSymptom.spt_title = ''
      this.newSymptom.spt_thumb = ''
      this.newSymptom.spt_contents = []
      this.newSymptom.url = ''
      this.$refs['modalright'].show()
    },
    selectedPreview (e) {
      const file = e.target.files[0]
      this.selectedSymptom.url = URL.createObjectURL(file)
    },
    preview (e) {
      const file = e.target.files[0]
      this.newSymptom.url = URL.createObjectURL(file)
    },
    regist () {
      if (this.newSymptom.spt_title !== '' && this.newSymptom.spt_title !== undefined &&
        this.newSymptom.spt_thumb !== '' && this.newSymptom.spt_thumb !== undefined &&
        this.newSymptom.spt_contents.length > 0) {
        try {
          // eslint-disable-next-line no-unused-vars
          const formData = new FormData()
          if (this.newSymptom.spt_thumb) {
            formData.append('file1', this.newSymptom.spt_thumb)
          }
          formData.append('spt_title', this.newSymptom.spt_title)
          formData.append('spt_contents', JSON.stringify(this.newSymptom.spt_contents))
          this.$axios.post('/symptoms', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          }).then((response) => {
            this.$refs.vuetable.refresh()
            this.$refs['modalright'].hide()
            this.addNotification('primary', '안내', '등록되었습니다')
          })
        } catch (e) {
          console.log(e)
        }
      } else {
        this.addNotification('error filled', '안내', '모든 항목을 채워주세요')
      }
    }
  }
}
</script>
