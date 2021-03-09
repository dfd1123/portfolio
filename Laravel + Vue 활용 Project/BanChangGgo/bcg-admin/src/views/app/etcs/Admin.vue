<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.admin')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.admin')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="Admin.apiUrl"
            :fields="Admin.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="admClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="관리자 정보" class="modal-right">
          <div>
            <p>계정 : {{selectedAdmin.adm_email}}</p>
            <p>가입일 : {{selectedAdmin.adm_reg_dt}}</p>
            <p>권한 레벨 : {{selectedAdmin.adm_level}}</p>
            <p>상태 : {{['비활성','활성'][selectedAdmin.state]}}</p>
            <b-form-group label="이름 :">
              <b-form-input v-model="selectedAdmin.adm_name" />
            </b-form-group>
            <b-form-group label="연락처 :">
              <b-form-input v-model="selectedAdmin.adm_contact" />
            </b-form-group>
            <b-form-group label="메모 :">
              <b-textarea v-model="selectedAdmin.adm_memo" :rows="4" :max-rows="4" />
            </b-form-group>
            <p>
              썸네일 :
              <img :src="selectedAdmin.url" style="width:100%;" />
            </p>
            <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
              <b-form-file
                v-model="file"
                :placeholder="$t('input-groups.choose-file')"
                accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                @change="selectedPreview"
              ></b-form-file>
            </b-input-group>
          </div>
          <template slot="modal-footer">
            <b-button
              variant="info"
              v-if="selectedAdmin.state == 0"
              @click="adminUdp({...selectedAdmin, ...{state: 1}})"
            >활성화 하기</b-button>
            <b-button
              variant="secondary"
              v-if="selectedAdmin.state == 1"
              @click="adminUdp({...selectedAdmin, ...{state: 0}})"
            >비활성화 하기</b-button>
            <b-button variant="outline-info" @click="adminUdp(selectedAdmin)">수정</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="관리자 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="계정(이메일)">
              <b-form-input v-model="newAdmin.adm_email" />
            </b-form-group>
            <b-form-group label="비밀번호">
              <b-form-input v-model="newAdmin.adm_pwd" type="password" />
            </b-form-group>
            <b-form-group label="이름">
              <b-form-input v-model="newAdmin.adm_name" />
            </b-form-group>
            <b-form-group label="연락처">
              <b-form-input v-model="newAdmin.adm_contact" />
            </b-form-group>
            <b-form-group label="메모">
              <b-form-input v-model="newAdmin.adm_memo" />
            </b-form-group>
            <b-form-group label="썸네일">
              <b-input-group :prepend="$t('input-groups.upload')" class="mb-3">
                <b-form-file
                  v-model="newAdmin.adm_thumb"
                  :placeholder="$t('input-groups.choose-file')"
                  accept=".gif, .jpg, .jpeg, .png, .svg, .webp"
                  @change="preview"
                ></b-form-file>
              </b-input-group>
            </b-form-group>
            <b-form-group label="미리보기">
              <img ref="preview-img" :src="newAdmin.url" style="width:100%;" />
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
  name: 'Admin',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Admin: {
        apiUrl: '/admins/paginate',
        fields: [{
          name: 'adm_name',
          title: '이름'
        }, {
          name: 'adm_email',
          title: '계정'
        }, {
          name: 'adm_reg_dt',
          title: '가입일'
        }, {
          name: 'adm_level',
          title: '권한 레벨'
        }, {
          name: 'state',
          title: '상태',
          callback: this.state
        }]
      },
      selectedAdmin: {
        adm_no: '',
        adm_name: '',
        adm_email: '',
        adm_reg_dt: '',
        adm_level: '',
        state: '',
        adm_contact: '',
        adm_memo: '',
        adm_thumb: '',
        url: ''
      },
      newAdmin: {
        adm_name: '',
        adm_email: '',
        adm_pwd: '',
        adm_level: '',
        state: '',
        adm_contact: '',
        adm_memo: '',
        adm_thumb: '',
        url: ''
      },
      file: {}
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
    admClick (row) {
      this.selectedAdmin.adm_no = row.adm_no
      this.selectedAdmin.adm_name = row.adm_name
      this.selectedAdmin.adm_email = row.adm_email
      this.selectedAdmin.adm_reg_dt = row.adm_reg_dt
      this.selectedAdmin.adm_level = row.adm_level
      this.selectedAdmin.state = row.state
      this.selectedAdmin.adm_contact = row.adm_contact
      this.selectedAdmin.adm_memo = row.adm_memo || ''
      this.selectedAdmin.adm_thumb = row.adm_thumb || ''
      this.selectedAdmin.url = '/fdata/admin_thumb/' + row.adm_thumb
      this.file = ''
      this.$refs['modalbackdrop'].show()
    },
    adminUdp (admin) {
      try {
        const formData = new FormData()
        if (this.file) {
          formData.append('file1', this.file)
        }
        formData.append('adm_name', admin.adm_name)
        formData.append('adm_contact', admin.adm_contact)
        formData.append('adm_memo', admin.adm_memo)
        formData.append('state', admin.state)
        this.$axios.post('/admins/' + admin.adm_no, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        }).then((response) => {
          this.$refs.vuetable.refresh()
          this.$refs['modalbackdrop'].hide()
          this.addNotification('primary')
        })
      } catch (e) {
        console.log(e)
      }
    },
    regist () {
      if (this.newAdmin.adm_name !== '' && this.newAdmin.adm_name !== undefined &&
        this.newAdmin.adm_email !== '' && this.newAdmin.adm_email !== undefined &&
        this.newAdmin.adm_pwd !== '' && this.newAdmin.adm_pwd !== undefined &&
        this.newAdmin.adm_contact !== '' && this.newAdmin.adm_contact !== undefined &&
        this.newAdmin.adm_memo !== '' && this.newAdmin.adm_memo !== undefined) {
        try {
          // eslint-disable-next-line no-unused-vars
          const formData = new FormData()
          if (this.newAdmin.adm_thumb) {
            formData.append('file1', this.newAdmin.adm_thumb)
          }
          formData.append('adm_name', this.newAdmin.adm_name)
          formData.append('adm_email', this.newAdmin.adm_email)
          formData.append('adm_pwd', this.newAdmin.adm_pwd)
          formData.append('adm_contact', this.newAdmin.adm_contact)
          formData.append('adm_memo', this.newAdmin.adm_memo)
          formData.append('state', 1)
          this.$axios.post('/register', formData, {
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
    rightmodal () {
      this.newAdmin.adm_name = ''
      this.newAdmin.adm_email = ''
      this.newAdmin.adm_pwd = ''
      this.newAdmin.adm_contact = ''
      this.newAdmin.adm_memo = ''
      this.newAdmin.adm_thumb = ''
      this.newAdmin.url = ''
      this.$refs['modalright'].show()
    },
    hideModal (name) {
      this.$refs[name].hide()
    },
    addNotification (
      type = 'success',
      title = '안내',
      message = '변경되었습니다'
    ) {
      this.$notify(type, title, message, { duration: 3000, permanent: false })
    },
    state (state) {
      return ['비활성', '활성'][state]
    },
    selectedPreview (e) {
      const file = e.target.files[0]
      this.selectedAdmin.url = URL.createObjectURL(file)
    },
    preview (e) {
      const file = e.target.files[0]
      this.newAdmin.url = URL.createObjectURL(file)
    }
  }
}
</script>
