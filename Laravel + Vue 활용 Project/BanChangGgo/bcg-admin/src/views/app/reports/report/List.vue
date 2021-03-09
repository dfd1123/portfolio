<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.list')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.list')">
          <vuetable
            ref="vuetable"
            :api-url="HealthReport.apiUrl"
            :fields="HealthReport.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="hrClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="리포트 상세">
          <b-form>
            <b-form-group label="리포트 고유번호">
              <p>{{selectedHr.hr_no}}</p>
            </b-form-group>
            <b-form-group label="유저 고유번호">
              <p>{{selectedHr.usr_no}}</p>
            </b-form-group>
            <b-form-group label="유저차수">
              <p>{{selectedHr.bt_order}} 차</p>
            </b-form-group>
            <b-form-group label="유저이름">
              <p>{{selectedHr.usr_name}}</p>
            </b-form-group>
            <b-form-group label="작성한 질병목록">
              <p>{{selectedHr.disease_list.length === 0 ? '미작성' : selectedHr.disease_list.map(x => x.dc_cat_name).map(x => `[${x}]`).join(', ') + ` (총 ${selectedHr.disease_list.length}개)`}}</p>
            </b-form-group>
            <b-form-group label="등록일">
              <p>{{selectedHr.hr_reg_dt}}</p>
            </b-form-group>
            <b-form-group label="상태">
              <p>{{selectedHr.state === 0 ? '작성중' : '작성완료'}}</p>
            </b-form-group>
          </b-form>
          <template slot="modal-footer">
            <b-button
              v-if="selectedHr.state === 0"
              variant="outline-info"
              @click="publish(selectedHr)"
            >작성완료(공개)</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
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
  name: 'List',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      HealthReport: {
        apiUrl: '/health_reports/paginate',
        fields: [{
          name: 'hr_no',
          title: '리포트 고유번호'
        }, {
          name: 'usr_no',
          title: '유저 고유번호'
        }, {
          name: 'bt_order',
          title: '유저차수'
        }, {
          name: 'usr_name',
          title: '유저이름'
        }, {
          name: 'completed',
          title: '달성률(%)',
          callback: (value) => {
            return value ? `${value}%` : 'N/A'
          }
        }, {
          name: 'disease_list',
          title: '작성한 질병목록',
          callback: (value) => {
            return value.length === 0 ? '미작성' : value.map(x => x.dc_cat_name).map(x => `[${x}]`).join(', ') + ` (총 ${value.length}개)`
          }
        }, {
          name: 'hr_reg_dt',
          title: '등록일'
        }, {
          name: 'state',
          title: '상태',
          callback: (value) => {
            return value === 0 ? '작성중' : '작성완료'
          }
        }]
      },
      selectedHr: {
        hr_no: '',
        bt_no: '',
        bt_order: '',
        disease_list: '',
        hr_reg_dt: '',
        ubt_no: '',
        usr_name: '',
        usr_no: '',
        disease: '',
        isMdcn: false,
        isntrcn: false,
        ishealth: false
      }
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
    hrClick (row) {
      this.selectedHr = row
      this.$refs['modalbackdrop'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    showList (refname) {
      this.$refs[refname].show()
    },
    addNotification (
      type = 'success',
      title = '안내',
      message = '변경되었습니다'
    ) {
      this.$notify(type, title, message, { duration: 3000, permanent: false })
    },
    async publish (healthReport) {
      if (healthReport.disease_list.length === 0) {
        alert('작성한 리포트가 없습니다.')
      }

      if (confirm('정말로 작성완료(공개) 하시겠습니까?\n메디플래너 작성을 완료한 사용자에게 해당 리포트가 보이게됩니다')) {
        try {
          await this.$axios.put('/health_reports/' + healthReport.hr_no, {
            state: 1
          }).then((response) => {
            this.$refs.vuetable.refresh()
            this.$refs['modalbackdrop'].hide()
            this.addNotification('primary')
          })
        } catch (e) {
          console.log(e)
        } finally {

        }
      }
    }
  }
}
</script>
