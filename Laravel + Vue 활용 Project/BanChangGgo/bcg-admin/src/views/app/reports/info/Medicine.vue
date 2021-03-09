<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.medicine')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.medicine')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="Medicine.apiUrl"
            :fields="Medicine.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="mdcnClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="보조약품 상세정보">
          <div>
            <b-form>
              <b-form-group label="약품명">
                <b-form-input v-model="selectedMedicine.mdcn_title" />
              </b-form-group>
              <b-form-group label="약품 한줄설명">
                <b-form-input v-model="selectedMedicine.mdcn_extra.sub_title" />
              </b-form-group>
              <b-form-group label="약품 가격">
                <b-form-input v-model="selectedMedicine.mdcn_extra.price" type="number" />
              </b-form-group>
              <b-form-group label="약품설명">
                <b-textarea v-model="selectedMedicine.mdcn_desc" :rows="10" />
              </b-form-group>
              <b-form-group label="링크">
                <b-form-input v-model="selectedMedicine.mdcn_link" />
              </b-form-group>
              <p>
                사진 :
                <img :src="selectedMedicine.url" style="width:100%;" />
              </p>
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="file"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="selectedPreview"
                ></b-form-file>
              </b-input-group>
              <p>상태 : {{['비활성화','활성화'][selectedMedicine.state]}}</p>
            </b-form>
          </div>
          <template slot="modal-footer">
            <b-button
              variant="info"
              v-if="selectedMedicine.state == 0"
              @click="medicineStateUdp(1)"
            >활성화 하기</b-button>
            <b-button
              variant="secondary"
              v-if="selectedMedicine.state == 1"
              @click="medicineStateUdp(0)"
            >비활성화 하기</b-button>
            <b-button variant="outline-info" @click="medicineUdp()">수정</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="보조약품 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="약품명">
              <b-form-input v-model="newMedicine.mdcn_title" />
            </b-form-group>
            <b-form-group label="약품 링크">
              <b-form-input v-model="newMedicine.mdcn_link" />
            </b-form-group>
            <b-form-group label="약품 추가제목">
              <b-form-input v-model="newMedicine.mdcn_extra.sub_title" />
            </b-form-group>
            <b-form-group label="약품 가격">
              <b-form-input v-model="newMedicine.mdcn_extra.price" />
            </b-form-group>
            <b-form-group label="약품 설명">
              <b-textarea v-model="newMedicine.mdcn_desc" :rows="10" />
            </b-form-group>
            <b-form-group label="약품 이미지">
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="newMedicine.mdcn_thumb"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="preview"
                ></b-form-file>
              </b-input-group>
            </b-form-group>
            <b-form-group label="미리보기">
              <img ref="preview-img" :src="newMedicine.url" style="width:100%;" />
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
  name: 'Medicine',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Medicine: {
        apiUrl: '/medicine_infos/paginate',
        fields: [{
          name: 'mdcn_title',
          title: '약품명'
        }, {
          name: 'mdcn_desc',
          title: '약품 설명'
        }, {
          name: 'mdcn_link',
          title: '링크'
        }, {
          name: 'state',
          title: '상태',
          callback: this.state
        }]
      },
      selectedMedicine: {
        mdcn_no: '',
        mdcn_title: '',
        mdcn_desc: '',
        mdcn_link: '',
        mdcn_thumb: '',
        mdcn_extra: {
          price: '',
          sub_title: ''
        },
        state: '',
        url: '',
        subtitle: '',
        price: 0
      },
      newMedicine: {
        mdcn_title: '',
        mdcn_desc: '',
        mdcn_link: '',
        mdcn_thumb: '',
        mdcn_extra: {
          price: '',
          sub_title: ''
        },
        url: ''
      },
      file: ''
    }
  },
  created: function () {
    // 로그인 필요한페이지는  created에서 추후 JWT검증해야한다.

    // 여기서 목록 호출
    // this.userListload()
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
    mdcnClick (row) {
      this.selectedMedicine.mdcn_no = row.mdcn_no
      this.selectedMedicine.mdcn_title = row.mdcn_title
      this.selectedMedicine.mdcn_desc = row.mdcn_desc
      this.selectedMedicine.mdcn_link = row.mdcn_link
      this.selectedMedicine.mdcn_thumb = row.mdcn_thumb
      this.selectedMedicine.url = '/fdata/medicine_thumb/' + row.mdcn_thumb
      this.selectedMedicine.mdcn_extra = JSON.parse(row.mdcn_extra)
      this.selectedMedicine.state = row.state
      this.file = ''
      this.$refs['modalbackdrop'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    medicineStateUdp (state) {
      try {
        this.$axios.post('/medicine_infos/' + this.selectedMedicine.mdcn_no, {
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
    medicineUdp () {
      if (this.selectedMedicine.mdcn_title !== '' && this.selectedMedicine.mdcn_title !== undefined &&
        this.selectedMedicine.mdcn_desc !== '' && this.selectedMedicine.mdcn_desc !== undefined &&
        this.selectedMedicine.mdcn_link !== '' && this.selectedMedicine.mdcn_link !== undefined &&
        this.selectedMedicine.mdcn_extra !== '' && this.selectedMedicine.mdcn_extra !== undefined) {
        try {
          if (this.selectedMedicine.mdcn_extra.price === '') {
            this.addNotification('error filled', '안내', '약품가격은 공백으로 수정할 수 없습니다')
            return false
          }
          const formData = new FormData()
          if (this.file) {
            formData.append('file1', this.file)
          }
          formData.append('mdcn_title', this.selectedMedicine.mdcn_title)
          formData.append('mdcn_desc', this.selectedMedicine.mdcn_desc)
          formData.append('mdcn_link', this.selectedMedicine.mdcn_link)
          formData.append('mdcn_extra', JSON.stringify(this.selectedMedicine.mdcn_extra))
          formData.append('state', this.selectedMedicine.state)
          this.$axios.post('/medicine_infos/' + this.selectedMedicine.mdcn_no, formData, {
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
      this.newMedicine.mdcn_title = ''
      this.newMedicine.mdcn_link = ''
      this.newMedicine.mdcn_desc = ''
      this.newMedicine.mdcn_thumb = ''
      this.newMedicine.mdcn_extra = { price: '', sub_title: '' }
      this.newMedicine.url = ''
      this.$refs['modalright'].show()
    },
    selectedPreview (e) {
      const file = e.target.files[0]
      this.selectedMedicine.url = URL.createObjectURL(file)
    },
    preview (e) {
      const file = e.target.files[0]
      this.newMedicine.url = URL.createObjectURL(file)
    },
    regist () {
      if (this.newMedicine.mdcn_title !== '' && this.newMedicine.mdcn_title !== undefined &&
        this.newMedicine.mdcn_link !== '' && this.newMedicine.mdcn_link !== undefined &&
        this.newMedicine.mdcn_desc !== '' && this.newMedicine.mdcn_desc !== undefined &&
        this.newMedicine.mdcn_thumb !== '' && this.newMedicine.mdcn_thumb !== undefined) {
        try {
          if (this.newMedicine.mdcn_extra.price === '') {
            this.addNotification('error filled', '안내', '약품가격은 공백으로 수정할 수 없습니다')
            return false
          }
          const formData = new FormData()
          if (this.newMedicine.mdcn_thumb) {
            formData.append('file1', this.newMedicine.mdcn_thumb)
          }
          formData.append('mdcn_title', this.newMedicine.mdcn_title)
          formData.append('mdcn_link', this.newMedicine.mdcn_link)
          formData.append('mdcn_desc', this.newMedicine.mdcn_desc)
          formData.append('mdcn_extra', JSON.stringify(this.newMedicine.mdcn_extra))
          this.$axios.post('/medicine_infos', formData, {
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
