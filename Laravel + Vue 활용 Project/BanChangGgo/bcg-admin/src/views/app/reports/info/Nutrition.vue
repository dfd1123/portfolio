<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.nutrition')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.nutrition')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="Nutrition.apiUrl"
            :fields="Nutrition.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="ntrcnClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="영양개선 상세정보">
          <b-form>
            <b-form-group label="제목">
              <b-form-input v-model="selectedNutrition.ntrcn_title" />
            </b-form-group>
            <b-form-group label="설명">
              <b-textarea v-model="selectedNutrition.ntrcn_desc" :rows="10" />
            </b-form-group>
            <b-form-group label="링크">
              <b-form-input v-model="selectedNutrition.ntrcn_link" />
            </b-form-group>
            <p>
              사진 :
              <img :src="selectedNutrition.url" style="width:100%;" />
            </p>
            <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
              <b-form-file
                v-model="file"
                :placeholder="$t('input-groups.choose-file')"
                accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                @change="selectedPreview"
              ></b-form-file>
            </b-input-group>
            <p>상태 : {{['비활성화','활성화'][selectedNutrition.state]}}</p>
          </b-form>
          <template slot="modal-footer">
            <b-button
              variant="info"
              v-if="selectedNutrition.state == 0"
              @click="nutritionStateUdp(1)"
            >활성화 하기</b-button>
            <b-button
              variant="secondary"
              v-if="selectedNutrition.state == 1"
              @click="nutritionStateUdp(0)"
            >비활성화 하기</b-button>
            <b-button variant="outline-info" @click="nutritionUdp()">수정</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="영양개선 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="제목">
              <b-form-input v-model="newNutrition.ntrcn_title" />
            </b-form-group>
            <b-form-group label="링크">
              <b-form-input v-model="newNutrition.ntrcn_link" />
            </b-form-group>
            <b-form-group label="설명">
              <b-textarea v-model="newNutrition.ntrcn_desc" :rows="10" />
            </b-form-group>
            <b-form-group label="이미지">
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="newNutrition.ntrcn_thumb"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="preview"
                ></b-form-file>
              </b-input-group>
            </b-form-group>
            <b-form-group label="미리보기">
              <img ref="preview-img" :src="newNutrition.url" style="width:100%;" />
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
  name: 'Nutrition',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Nutrition: {
        apiUrl: '/nutrition_infos/paginate',
        fields: [{
          name: 'ntrcn_title',
          title: '제목'
        }, {
          name: 'ntrcn_desc',
          title: '설명'
        }, {
          name: 'ntrcn_link',
          title: '링크'
        }, {
          name: 'state',
          title: '상태',
          callback: this.state
        }]
      },
      selectedNutrition: {
        ntrcn_no: '',
        ntrcn_title: '',
        ntrcn_desc: '',
        ntrcn_thumb: '',
        ntrcn_link: '',
        ntrcn_extra: '',
        state: '',
        url: ''
      },
      newNutrition: {
        ntrcn_title: '',
        ntrcn_desc: '',
        ntrcn_thumb: '',
        ntrcn_link: '',
        ntrcn_extra: '',
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
    ntrcnClick (row) {
      this.selectedNutrition.ntrcn_no = row.ntrcn_no
      this.selectedNutrition.ntrcn_title = row.ntrcn_title
      this.selectedNutrition.ntrcn_desc = row.ntrcn_desc
      this.selectedNutrition.ntrcn_thumb = row.ntrcn_thumb
      this.selectedNutrition.url = '/fdata/nutrition_thumb/' + row.ntrcn_thumb
      this.selectedNutrition.ntrcn_link = row.ntrcn_link
      this.selectedNutrition.ntrcn_extra = row.ntrcn_extra
      this.selectedNutrition.state = row.state
      this.file = ''
      this.$refs['modalbackdrop'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    nutritionStateUdp (state) {
      try {
        this.$axios.post('/nutrition_infos/' + this.selectedNutrition.ntrcn_no, {
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
    nutritionUdp () {
      if (this.selectedNutrition.ntrcn_title !== '' && this.selectedNutrition.ntrcn_title !== undefined &&
        this.selectedNutrition.ntrcn_desc !== '' && this.selectedNutrition.ntrcn_desc !== undefined &&
        this.selectedNutrition.ntrcn_link !== '' && this.selectedNutrition.ntrcn_link !== undefined &&
        this.file !== '' && this.file !== undefined &&
        this.selectedNutrition.ntrcn_extra !== '' && this.selectedNutrition.ntrcn_extra !== undefined) {
        try {
          // eslint-disable-next-line no-unused-vars
          const formData = new FormData()
          if (this.file) {
            formData.append('file1', this.file)
          }
          formData.append('mdcn_title', this.selectedNutrition.mdcn_title)
          formData.append('mdcn_desc', this.selectedNutrition.mdcn_desc)
          formData.append('mdcn_link', this.selectedNutrition.mdcn_link)
          formData.append('mdcn_extra', this.selectedNutrition.mdcn_extra)
          formData.append('state', this.selectedNutrition.state)
          this.$axios.post('/nutrition_infos/' + this.selectedNutrition.ntrcn_no, formData, {
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
      this.newNutrition.ntrcn_title = ''
      this.newNutrition.ntrcn_desc = ''
      this.newNutrition.ntrcn_link = ''
      this.newNutrition.ntrcn_thumb = ''
      this.newNutrition.url = ''
      this.$refs['modalright'].show()
    },
    selectedPreview (e) {
      const file = e.target.files[0]
      this.selectedNutrition.url = URL.createObjectURL(file)
    },
    preview (e) {
      const file = e.target.files[0]
      this.newNutrition.url = URL.createObjectURL(file)
    },
    regist () {
      if (this.newNutrition.ntrcn_title !== '' && this.newNutrition.ntrcn_title !== undefined &&
        this.newNutrition.ntrcn_link !== '' && this.newNutrition.ntrcn_link !== undefined &&
        this.newNutrition.ntrcn_desc !== '' && this.newNutrition.ntrcn_desc !== undefined &&
        this.newNutrition.ntrcn_thumb !== '' && this.newNutrition.ntrcn_thumb !== undefined) {
        try {
          // eslint-disable-next-line no-unused-vars
          const formData = new FormData()
          if (this.newNutrition.ntrcn_thumb) {
            formData.append('file1', this.newNutrition.ntrcn_thumb)
          }
          formData.append('ntrcn_title', this.newNutrition.ntrcn_title)
          formData.append('ntrcn_link', this.newNutrition.ntrcn_link)
          formData.append('ntrcn_desc', this.newNutrition.ntrcn_desc)
          this.$axios.post('/nutrition_infos', formData, {
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
