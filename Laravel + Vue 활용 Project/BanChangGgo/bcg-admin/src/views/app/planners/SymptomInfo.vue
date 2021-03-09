<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.spinfo')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.spinfo')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="SymptomInfo.apiUrl"
            :fields="SymptomInfo.fields"
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
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="증상컨텐츠 세부 정보">
          <b-form>
            <b-form-group label="제목">
              <b-form-input v-model="selectedSymptomInfo.smptm_title" />
            </b-form-group>
            <b-form-group label="설명">
              <b-form-input v-model="selectedSymptomInfo.smptm_desc" />
            </b-form-group>
            <b-form-group label="링크">
              <b-form-input v-model="selectedSymptomInfo.smptm_link" />
            </b-form-group>
            <b-form-group label="이미지">
              <img :src="selectedSymptomInfo.url" class="mb-2" style="width:100%;" />
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="file"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="selectedPreview"
                ></b-form-file>
              </b-input-group>
            </b-form-group>
          </b-form>
          <template slot="modal-footer">
            <b-button
              variant="info"
              v-if="selectedSymptomInfo.state == 0"
              @click="symptomStateUdp(1)"
            >활성화 하기</b-button>
            <b-button
              variant="secondary"
              v-if="selectedSymptomInfo.state == 1"
              @click="symptomStateUdp(0)"
            >비활성화 하기</b-button>
            <b-button variant="outline-info" @click="symptomUdp()">수정</b-button>
            <b-button variant="outline-success" @click="symptomDelete()">삭제</b-button>
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
            <b-form-group label="제목">
              <b-form-input v-model="newSymptomInfo.smptm_title" />
            </b-form-group>
            <b-form-group label="설명">
              <b-form-input v-model="newSymptomInfo.smptm_desc" />
            </b-form-group>
            <b-form-group label="링크">
              <b-form-input v-model="newSymptomInfo.smptm_link" />
            </b-form-group>
            <b-form-group label="이미지">
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="newSymptomInfo.smptm_thumb"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="preview"
                ></b-form-file>
              </b-input-group>
            </b-form-group>
            <b-form-group label="미리보기">
              <img ref="preview-img" :src="newSymptomInfo.url" style="width:100%;" />
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
  name: 'SymptomInfo',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      SymptomInfo: {
        apiUrl: '/symptom_infos/paginate',
        fields: [{
          name: 'smptm_title',
          title: '제목'
        }, {
          name: 'smptm_desc',
          title: '설명'
        }, {
          name: 'smptm_link',
          title: '링크'
        }, {
          name: 'state',
          title: '상태',
          formatter (value) {
            return ['비활성', '활성'][value]
          }
        }]
      },
      selectedSymptomInfo: {
        smptm_no: '',
        smptm_title: '',
        smptm_desc: '',
        smptm_thumb: '',
        smptm_link: '',
        smptm_extra: '',
        state: '',
        url: ''
      },
      newSymptomInfo: {
        smptm_title: '',
        smptm_desc: '',
        smptm_thumb: '',
        smptm_link: '',
        url: ''
      },
      file: ''
    }
  },
  created: function () {
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
      this.$refs[refname].hide()
    },
    sptClick (row) {
      this.selectedSymptomInfo.smptm_no = row.smptm_no
      this.selectedSymptomInfo.smptm_title = row.smptm_title
      this.selectedSymptomInfo.smptm_desc = row.smptm_desc
      this.selectedSymptomInfo.smptm_thumb = row.smptm_thumb
      this.selectedSymptomInfo.url = '/fdata/symptom_info_thumb/' + row.smptm_thumb
      this.selectedSymptomInfo.smptm_link = row.smptm_link
      this.selectedSymptomInfo.smptm_extra = row.smptm_extra
      this.selectedSymptomInfo.state = row.state
      this.$refs['modalbackdrop'].show()
    },
    symptomDelete () {
      try {
        this.$axios.delete('/symptom_infos/' + this.selectedSymptomInfo.smptm_no).then((response) => {
          this.$refs.vuetable.refresh()
          this.$refs['modalbackdrop'].hide()
          this.addNotification('primary', '안내', '삭제되었습니다')
        })
      } catch (e) {
        console.log(e)
      }
      this.$refs['modalbackdrop'].hide()
    },
    symptomStateUdp (state) {
      try {
        this.$axios.post('/symptom_infos/' + this.selectedSymptomInfo.smptm_no, {
          state: state
        }).then((response) => {
          this.$refs.vuetable.refresh()
          this.$refs['modalbackdrop'].hide()
          this.addNotification('primary', '안내', '변경되었습니다')
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
      if (this.selectedSymptomInfo.smptm_title !== '' && this.selectedSymptomInfo.smptm_title !== undefined &&
        this.selectedSymptomInfo.smptm_desc !== '' && this.selectedSymptomInfo.smptm_desc !== undefined &&
        this.selectedSymptomInfo.smptm_link !== '' && this.selectedSymptomInfo.smptm_link !== undefined) {
        try {
          // eslint-disable-next-line no-unused-vars
          const formData = new FormData()
          if (this.file) {
            formData.append('file1', this.file)
          }
          formData.append('smptm_title', this.selectedSymptomInfo.smptm_title)
          formData.append('smptm_desc', this.selectedSymptomInfo.smptm_desc)
          formData.append('smptm_link', this.selectedSymptomInfo.smptm_link)
          this.$axios.post('/symptom_infos/' + this.selectedSymptomInfo.smptm_no, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          }).then((response) => {
            this.$refs.vuetable.refresh()
            this.$refs['modalbackdrop'].hide()
            this.addNotification('primary', '안내', '변경되었습니다')
          })
        } catch (e) {
          console.log(e)
        }
      } else {
        this.addNotification('error filled', '안내', '모든 항목을 채워주세요')
      }
    },
    rightmodal () {
      this.newSymptomInfo.smptm_title = ''
      this.newSymptomInfo.smptm_desc = ''
      this.newSymptomInfo.smptm_link = ''
      this.newSymptomInfo.smptm_thumb = ''
      this.newSymptomInfo.url = ''
      this.$refs['modalright'].show()
    },
    selectedPreview (e) {
      const file = e.target.files[0]
      this.selectedSymptomInfo.url = URL.createObjectURL(file)
    },
    preview (e) {
      const file = e.target.files[0]
      this.newSymptomInfo.url = URL.createObjectURL(file)
    },
    regist () {
      if (this.newSymptomInfo.smptm_title !== '' && this.newSymptomInfo.smptm_title !== undefined &&
        this.newSymptomInfo.smptm_desc !== '' && this.newSymptomInfo.smptm_desc !== undefined &&
        this.newSymptomInfo.smptm_link !== '' && this.newSymptomInfo.smptm_link !== undefined &&
        this.newSymptomInfo.smptm_thumb !== '' && this.newSymptomInfo.smptm_thumb !== undefined) {
        try {
          // eslint-disable-next-line no-unused-vars
          const formData = new FormData()
          if (this.newSymptomInfo.smptm_thumb) {
            formData.append('file1', this.newSymptomInfo.smptm_thumb)
          }
          formData.append('smptm_title', this.newSymptomInfo.smptm_title)
          formData.append('smptm_desc', this.newSymptomInfo.smptm_desc)
          formData.append('smptm_link', this.newSymptomInfo.smptm_link)
          this.$axios.post('/symptom_infos', formData, {
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
