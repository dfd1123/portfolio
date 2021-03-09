<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.checklist')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.checklist')">
          <vuetable
            ref="vuetable"
            :api-url="Ubatch.apiUrl"
            :fields="Ubatch.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="ubtClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="질문지 상세 정보">
          <b-form>
            <p>참가 차수 : {{selectedUbatch.bt_order}}</p>
            <p>유저 이름 : {{selectedUbatch.usr_name}}</p>
            <b-form-group>
              <b-card title="기록일">
                <!-- <calendar-view
                  style="min-height:500px"
                  :events="calendar.events"
                  :show-date="calendar.showDate"
                  :time-format-options="{hour: 'numeric', minute:'2-digit'}"
                  :enable-drag-drop="true"
                  :show-event-times="true"
                  display-period-uom="month"
                  :starting-day-of-week="7"
                  current-period-label="Today"
                >
                  <calendar-view-header
                    slot="header"
                    slot-scope="t"
                    :header-props="t.headerProps"
                    @input="setShowDate"
                  />
                </calendar-view>-->
                <v-calendar :attributes="attrs"></v-calendar>
              </b-card>
            </b-form-group>
            <p>상태 : {{['준비','시작','종료'][selectedUbatch.state]}}</p>
            <b-button variant="outline-info" @click="showList('modalnestedinline')">답변 보기</b-button>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal id="modalnestedinline" ref="modalnestedinline" size="lg">
          <b-form>
            <b-row>
              <b-colxx xxs="12">
                <b-card>
                  <table style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
                    <thead style="border-bottom: 1px solid #8d8d8d;">
                      <tr>
                        <th>index</th>
                        <th>질문</th>
                        <th>답변</th>
                      </tr>
                    </thead>
                    <tbody v-for="(list,index) in ubt_qna_list" :key="index">
                      <tr v-for="(qna, index2) in list" :key="index2">
                        <td>{{(index+1)}} - {{(index2+1)}}</td>
                        <td>{{qna.title}}</td>
                        <td>{{view(qna.value, qna.option)}}</td>
                      </tr>
                    </tbody>
                  </table>
                </b-card>
              </b-colxx>
            </b-row>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="secondary" @click="hideModal('modalnestedinline')">닫기</b-button>
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
  name: 'Checklist',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Ubatch: {
        apiUrl: '/user_batches/paginate',
        fields: [{
          name: 'bt_order',
          title: '차수'
        }, {
          name: 'ubt_start',
          title: '기록 시작일'
        }, {
          name: 'ubt_end',
          title: '기록 종료일'
        }, {
          name: 'usr_name',
          title: '유저 이름'
        }, {
          name: 'state',
          title: '상태',
          callback: this.state
        }]
      },
      attrs: [
        {
          key: 'today',
          highlight: {
            backgroundColor: '#f18024'
          },
          dates: {
            start: null, // From the beginning of time
            end: null // Until today
          }
        }
      ],
      calendar: {
        showDate: this.thisMonth(1),
        events: []
      },
      ubt_qna_list: [],
      selectedUbatch: {
        ubt_no: '',
        bt_order: '',
        ubt_start: '',
        ubt_end: '',
        ubt_reg_dt: '',
        ubt_qna_list: [],
        usr_name: '',
        state: '',
        date_picker: {
          start: '',
          end: ''
        }
      }
    }
  },
  created: function () {
    // this.loadMonth()
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
    ubtClick (row) {
      this.selectedUbatch.ubt_no = row.ubt_no
      this.selectedUbatch.bt_order = row.bt_order
      this.selectedUbatch.ubt_start = new Date(row.ubt_start)
      this.selectedUbatch.ubt_end = new Date(row.ubt_end)
      this.attrs[0].dates.start = new Date(row.ubt_start)
      this.attrs[0].dates.end = new Date(row.ubt_end)
      this.selectedUbatch.usr_name = row.usr_name
      this.selectedUbatch.bt_ureg_dt = row.ubt_reg_dt
      this.selectedUbatch.ubt_qna_list = row.ubt_qna_list
      this.selectedUbatch.state = row.state
      this.calendar.showDate = this.setThisMonth(row.ubt_start)
      this.calendar.events = []
      this.calendar.events.push({
        id: 'day',
        startDate: this.thisMonth(new Date(row.ubt_start).getDate()),
        endDate: this.thisMonth(new Date(row.ubt_end).getDate()),
        title: '기록일'
      })
      this.$refs['modalbackdrop'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    showList (refname) {
      this.ubt_qna_list = this.selectedUbatch.ubt_qna_list
      this.$refs[refname].show()
    },
    addNotification (
      type = 'success',
      title = '안내',
      message = '변경되었습니다'
    ) {
      this.$notify(type, title, message, { duration: 3000, permanent: false })
    },
    thisMonth (d, h, m) {
      const t = new Date()
      return new Date(t.getFullYear(), t.getMonth(), d, h || 0, m || 0)
    },
    setShowDate (d) {
      this.calendar.showDate = d
    },
    setThisMonth (date) {
      const t = new Date(date)
      return new Date(t.getFullYear(), t.getMonth())
    },
    view (value, option) {
      if (value !== null && value !== undefined) {
        return value
      } else {
        console.log(option)
        // eslint-disable-next-line no-unused-vars
        var optvalue = ''
        if (option !== undefined) {
          for (var i = 0; i < option.length; i++) {
            if (option[i].checked) {
              if (option[i].value !== undefined) {
                optvalue += i !== option.length - 1 ? option[i].value + ',' : option[i].value
              } else {
                optvalue += i !== option.length - 1 ? option[i].name + ',' : option[i].name
              }
            }
          }
        }
        return optvalue
      }
    },
    state (state) {
      return ['비활성', '활성', '탈퇴'][state]
    }
  }
}
</script>
