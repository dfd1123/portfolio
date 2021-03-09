<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.plan')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx sm="4">
        <b-form-group label="유저 고유번호">
          <b-form-input v-model="Paramtype.usr_no" />
        </b-form-group>
      </b-colxx>
      <b-colxx sm="4">
        <b-form-group label="차수">
          <b-form-select v-model="Paramtype.bt_order" :options="orderOption" plain />
        </b-form-group>
      </b-colxx>
      <b-colxx sm="4">
        <b-button variant="success" class="mb-2" @click="searchPlan" style="margin-top:2em;">검색</b-button>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.plan')">
          <vuetable
            ref="vuetable"
            :api-url="HRP.apiUrl"
            :fields="HRP.fields"
            :append-params="appendParams"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="HRPClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="건강리포트 정보">
          <b-form>
            <b-form-group label="사용자 일정">
              <table style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>시간</th>
                    <th>제목</th>
                    <th>결과</th>
                    <th>변경일시</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in planList" :key="item.id">
                    <td>{{item.time}}</td>
                    <td>{{item.title}}</td>
                    <td style="text-align: center">{{item.result === 0 ? 'X' : 'O'}}</td>
                    <td>{{item.updated_dt}}</td>
                  </tr>
                </tbody>
              </table>
            </b-form-group>
          </b-form>
          <template slot="modal-footer">
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
  name: 'Plan',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      HRP: {
        apiUrl: '',
        fields: [{
          name: 'upt_reg_dt',
          title: '등록일'
        }, {
          name: 'usr_no',
          title: '유저 고유번호'
        }, {
          name: 'upt_type',
          title: '유저타입'
        }, {
          name: 'usr_name',
          title: '유저이름'
        }]
      },
      appendParams: {
      },
      Paramtype: {
        usr_no: '',
        bt_order: ''
      },
      selectedHRP: {
        hrp_no: '',
        hrp_comment: '',
        hrp_reg_dt: '',
        mdcn_info: '',
        ntrcn_info: '',
        health_info: '',
        dc_no: '',
        hrp_comment_detail: '',
        hrp_comment_med: '',
        hrp_extra: ''
      },
      newHRP: {
        hrp_comment: '',
        mdcn_info: '',
        ntrcn_info: '',
        health_info: '',
        dc_no: '',
        hrp_comment_detail: '',
        hrp_comment_med: '',
        hr_no: ''
      },
      diseaseOption: [
      ],
      orderOption: [],
      searchOn: false,
      planList: []
    }
  },
  async created () {
    try {
      this.orderOption = await this.$axios.get('/batches')
        .then(response => response.data)
        .then(data => data.map(x => x.bt_order))
    } catch (e) {
      console.log(e)
    }
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

    HRPClick (row) {
      this.planList = JSON.parse(row.upt_list)
      this.$refs['modalbackdrop'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    addNotification (
      type = 'success',
      title = '안내',
      message = '변경되었습니다'
    ) {
      this.$notify(type, title, message, { duration: 3000, permanent: false })
    },
    planChange () {
      this.$refs.vuetable.refresh()
    },
    async searchPlan () {
      if (this.Paramtype.usr_no === '' || this.Paramtype.bt_order === '') {
        this.addNotification('error filled', '안내', '모든 항목을 채워주세요')
      } else {
        try {
          const userBatch = await this.$axios.get('/user_batches', {
            params: {
              usr_no: this.Paramtype.usr_no,
              bt_order: this.Paramtype.bt_order
            }
          }).then(response => response.data.length > 0 ? response.data[0] : null)

          if (!userBatch) {
            this.appendParams = {}
            this.$refs.vuetable.tableData = []
            return
          }

          this.appendParams = {
            usr_no: userBatch.usr_no,
            upt_no: userBatch.upt_no,
            start_dt: userBatch.ubt_start,
            end_dt: userBatch.ubt_end
          }
          this.HRP.apiUrl = '/user_plans/paginate'
          await this.$nextTick()
          await this.$refs.vuetable.refresh()
        } catch (e) {
          console.log(e)
        }
      }
    }
  }
}
</script>
