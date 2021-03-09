<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.notice')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.notice')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="Notice.apiUrl"
            :fields="Notice.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="ntcClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="공지사항 정보">
          <b-form>
            <b-form-group label="공지사항 제목">
              <b-form-input v-model="selectedNotice.ntc_title" />
            </b-form-group>
            <b-form-group label="공지사항 내용">
              <b-textarea v-model="selectedNotice.ntc_content" :rows="10" :max-rows="10" />
            </b-form-group>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="info" @click="NoticeUpdate">수정</b-button>
            <b-button variant="secondary" @click="NoticeDelete">삭제</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="공지사항 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="제목">
              <b-form-input v-model="newNotice.ntc_title" />
            </b-form-group>
            <b-form-group label="내용">
              <b-textarea v-model="newNotice.ntc_content" :rows="10" :max-rows="10" />
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
  name: 'Notice',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Notice: {
        apiUrl: '/notices/paginate',
        fields: [{
          name: 'ntc_title',
          title: '공지사항 제목'
        }, {
          name: 'ntc_content',
          title: '공지사항 내용'
        }, {
          name: 'ntc_reg_dt',
          title: '생성일'
        }]
      },
      selectedNotice: {
        ntc_no: '',
        ntc_title: '',
        ntc_content: '',
        ntc_reg_dt: ''
      },
      newNotice: {
        ntc_title: '',
        ntc_content: ''
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
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    ntcClick (row) {
      this.selectedNotice.ntc_no = row.ntc_no
      this.selectedNotice.ntc_title = row.ntc_title
      this.selectedNotice.ntc_content = row.ntc_content
      this.selectedNotice.ntc_reg_dt = row.ntc_reg_dt
      this.$refs['modalbackdrop'].show()
    },
    NoticeUpdate () {
      try {
        this.$axios.put('/notices/' + this.selectedNotice.ntc_no, {
          ntc_title: this.selectedNotice.ntc_title,
          ntc_content: this.selectedNotice.ntc_content
        }).then((response) => {
          this.$refs.vuetable.refresh()
          this.$refs['modalbackdrop'].hide()
          this.addNotification('primary')
        })
      } catch (e) {
        console.log(e)
      }
    },
    NoticeDelete () {
      try {
        this.$axios.delete('/notices/' + this.selectedNotice.ntc_no).then((response) => {
          this.$refs.vuetable.refresh()
          this.addNotification('primary')
        })
      } catch (e) {
        console.log(e)
      }
      this.$refs['modalbackdrop'].hide()
    },
    symptomStateUdp (state) {
      console.log(state)
    },
    addNotification (
      type = 'success',
      title = '안내',
      message = '변경되었습니다'
    ) {
      this.$notify(type, title, message, { duration: 3000, permanent: false })
    },
    rightmodal () {
      this.newNotice.ntc_title = ''
      this.newNotice.ntc_content = ''
      this.$refs['modalright'].show()
    },
    regist () {
      if (this.newNotice.ntc_title !== '' && this.newNotice.ntc_title !== undefined &&
        this.newNotice.ntc_content !== '' && this.newNotice.ntc_content !== undefined) {
        try {
          this.$axios.post('/notices', {
            ntc_title: this.newNotice.ntc_title,
            ntc_content: this.newNotice.ntc_content
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
