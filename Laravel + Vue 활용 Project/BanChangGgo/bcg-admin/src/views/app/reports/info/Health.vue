<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.health')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.health')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="Health.apiUrl"
            :fields="Health.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="hlthClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="생활개선 상세정보">
          <b-form>
            <b-form-group label="제목">
              <b-form-input v-model="selectedHealth.hlth_title" />
            </b-form-group>
            <b-form-group label="설명">
              <b-textarea v-model="selectedHealth.hlth_desc" :rows="10" />
            </b-form-group>
            <b-form-group label="링크">
              <b-form-input v-model="selectedHealth.hlth_link" />
            </b-form-group>
            <p>
              사진 :
              <img :src="selectedHealth.url" style="width:100%;" />
            </p>
            <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
              <b-form-file
                v-model="file"
                :placeholder="$t('input-groups.choose-file')"
                accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                @change="selectedPreview"
              ></b-form-file>
            </b-input-group>
            <p>상태 : {{['비활성화','활성화'][selectedHealth.state]}}</p>
          </b-form>
          <template slot="modal-footer">
            <b-button
              variant="info"
              v-if="selectedHealth.state == 0"
              @click="healthStateUdp(1)"
            >활성화 하기</b-button>
            <b-button
              variant="secondary"
              v-if="selectedHealth.state == 1"
              @click="healthStateUdp(0)"
            >비활성화 하기</b-button>
            <b-button variant="outline-info" @click="healthUdp()">수정</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="생활개선 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="제목">
              <b-form-input v-model="newHealth.hlth_title" />
            </b-form-group>
            <b-form-group label="링크">
              <b-form-input v-model="newHealth.hlth_link" />
            </b-form-group>
            <b-form-group label="설명">
              <b-textarea v-model="newHealth.hlth_desc" :rows="10" />
            </b-form-group>
            <b-form-group label="이미지">
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="newHealth.hlth_thumb"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="preview"
                ></b-form-file>
              </b-input-group>
            </b-form-group>
            <b-form-group label="미리보기">
              <img ref="preview-img" :src="newHealth.url" style="width:100%;" />
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
import VuetablePaginationBootstrap from '../../../../components/Common/VuetablePaginationBootstrap'
export default {
  name: 'Health',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Health: {
        apiUrl: '/health_infos/paginate',
        fields: [{
          name: 'hlth_title',
          title: '제목'
        }, {
          name: 'hlth_desc',
          title: '설명'
        }, {
          name: 'hlth_link',
          title: '링크'
        }, {
          name: 'state',
          title: '상태',
          callback: this.state
        }]
      },

      selectedHealth: {
        hlth_no: '',
        hlth_title: '',
        hlth_desc: '',
        hlth_thumb: '',
        hlth_link: '',
        hlth_extra: '',
        state: '',
        url: ''
      },
      newHealth: {
        hlth_title: '',
        hlth_desc: '',
        hlth_thumb: '',
        hlth_link: '',
        hlth_extra: '',
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
    hlthClick (row) {
      this.selectedHealth.hlth_no = row.hlth_no
      this.selectedHealth.hlth_title = row.hlth_title
      this.selectedHealth.hlth_desc = row.hlth_desc
      this.selectedHealth.hlth_thumb = row.hlth_thumb
      this.selectedHealth.url = '/fdata/health_thumb/' + row.hlth_thumb
      this.selectedHealth.hlth_link = row.hlth_link
      this.selectedHealth.hlth_extra = row.hlth_extra
      this.selectedHealth.state = row.state
      this.$refs['modalbackdrop'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    healthStateUdp (state) {
      try {
        this.$axios.post('/health_infos/' + this.selectedHealth.hlth_no, {
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
    healthUdp () {
      if (this.selectedHealth.hlth_title !== '' && this.selectedHealth.hlth_title !== undefined &&
        this.selectedHealth.hlth_desc !== '' && this.selectedHealth.hlth_desc !== undefined &&
        this.selectedHealth.hlth_link !== '' && this.selectedHealth.hlth_link !== undefined &&
        this.file !== '' && this.file !== undefined &&
        this.selectedHealth.hlth_extra !== '' && this.selectedHealth.hlth_extra !== undefined) {
        try {
          const formData = new FormData()
          if (this.file) {
            formData.append('file1', this.file)
          }
          formData.append('hlth_title', this.selectedHealth.hlth_title)
          formData.append('hlth_desc', this.selectedHealth.hlth_desc)
          formData.append('hlth_link', this.selectedHealth.hlth_link)
          formData.append('hlth_extra', this.selectedHealth.hlth_extra)
          formData.append('state', this.selectedHealth.state)
          this.$axios.post('/health_infos/' + this.selectedHealth.hlth_no, formData, {
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
      this.newHealth.hlth_title = ''
      this.newHealth.hlth_desc = ''
      this.newHealth.hlth_link = ''
      this.newHealth.hlth_thumb = ''
      this.newHealth.url = ''
      this.$refs['modalright'].show()
    },
    selectedPreview (e) {
      const file = e.target.files[0]
      this.selectedHealth.url = URL.createObjectURL(file)
    },
    preview (e) {
      const file = e.target.files[0]
      this.newHealth.url = URL.createObjectURL(file)
    },
    regist () {
      if (this.newHealth.hlth_title !== '' && this.newHealth.hlth_title !== undefined &&
        this.newHealth.hlth_link !== '' && this.newHealth.hlth_link !== undefined &&
        this.newHealth.hlth_desc !== '' && this.newHealth.hlth_desc !== undefined &&
        this.newHealth.hlth_thumb !== '' && this.newHealth.hlth_thumb !== undefined) {
        try {
          // eslint-disable-next-line no-unused-vars
          const formData = new FormData()
          if (this.newHealth.hlth_thumb) {
            formData.append('file1', this.newHealth.hlth_thumb)
          }
          formData.append('hlth_title', this.newHealth.hlth_title)
          formData.append('hlth_link', this.newHealth.hlth_link)
          formData.append('hlth_desc', this.newHealth.hlth_desc)
          this.$axios.post('/health_infos', formData, {
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
    },
    state (state) {
      return ['비활성', '활성'][state]
    }
  }
}
</script>
