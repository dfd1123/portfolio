<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.faq')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.faq')">
          <vuetable
            ref="vuetable"
            :api-url="Faq.apiUrl"
            :fields="Faq.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="faqClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalright" ref="modalright" title="공지사항 정보" class="modal-right">
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
            <b-button variant="secondary">삭제</b-button>
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
  name: 'Faq',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Faq: {
        apiUrl: '/faq/paginate',
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
      selectedFaq: {
        ntc_no: '',
        ntc_title: '',
        ntc_content: '',
        ntc_reg_dt: ''
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
    faqClick (row) {
      this.selectedNotice.ntc_no = row.ntc_no
      this.selectedNotice.ntc_title = row.ntc_title
      this.selectedNotice.ntc_content = row.ntc_content
      this.selectedNotice.ntc_reg_dt = row.ntc_reg_dt
      this.$refs['modalright'].show()
    },
    FaqUpdate () {
      try {
        this.$axios.put('/faq/' + this.selectedNotice.ntc_no, {
          ntc_title: this.selectedNotice.ntc_title,
          ntc_content: this.selectedNotice.ntc_content
        }).then((response) => {
          this.$refs['modalright'].hide()
        })
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>
