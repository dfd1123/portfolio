<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.schedule')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx sm="4">
        <b-form-group label="유저타입">
          <b-form-select
            v-model="appendParams.pt_type"
            :options="typeOption"
            plain
            @change="planChange"
          />
        </b-form-group>
      </b-colxx>
      <b-colxx sm="4">
        <b-form-group label="일차">
          <b-form-select
            v-model="appendParams.pt_day"
            :options="dayOption"
            plain
            @change="planChange"
          />
        </b-form-group>
      </b-colxx>
      <b-colxx sm="4">
        <b-form-group label="차수">
          <b-form-select
            v-model="appendParams.bt_order"
            :options="orderOption"
            plain
            @change="planChange"
          />
        </b-form-group>
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
            :api-url="Plantemplate.apiUrl"
            :fields="Plantemplate.fields"
            :http-fetch="myFetch"
            :append-params="appendParams"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="planClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="스케쥴 상세 정보">
          <b-form>
            <b-form-group label="타입">
              <b-form-select v-model="selectedPlan.pt_type" :options="typeOption" plain />
            </b-form-group>
            <b-form-group label="플랜명">
              <b-form-input v-model="selectedPlan.pt_title" />
            </b-form-group>
            <b-form-group label="시(24시 기준)">
              <b-form-select v-model="selectedPlan.h" :options="HourOption" plain />
            </b-form-group>
            <b-form-group label="분(1분 기준)">
              <b-form-select v-model="selectedPlan.m" :options="MinuteOption" plain />
            </b-form-group>
            <b-form-group label="메모">
              <b-form-input v-model="selectedPlan.pt_memo" />
            </b-form-group>
            <b-form-group label="일차">
              <b-form-select v-model="selectedPlan.pt_day" :options="dayOption" plain />
            </b-form-group>
            <b-form-group label="차수">
              <b-form-select v-model="appendParams.bt_order" :options="orderOption" plain />
            </b-form-group>
            <p>상태 : {{['비활성화','활성화'][selectedPlan.state]}}</p>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="info" v-if="selectedPlan.state == 0" @click="planStateUdp(1)">활성화 하기</b-button>
            <b-button
              variant="secondary"
              v-if="selectedPlan.state == 1"
              @click="planStateUdp(0)"
            >비활성화 하기</b-button>
            <b-button variant="outline-info" @click="planUdp()">수정</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="스케쥴 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="타입">
              <b-form-select v-model="newPlan.pt_type" :options="typeOption" plain />
            </b-form-group>
            <b-form-group label="플랜명">
              <b-form-input v-model="newPlan.pt_title" />
            </b-form-group>
            <b-form-group label="시(24시 기준)">
              <b-form-select v-model="newPlan.h" :options="HourOption" plain />
            </b-form-group>
            <b-form-group label="분(1분 기준)">
              <b-form-select v-model="newPlan.m" :options="MinuteOption" plain />
            </b-form-group>
            <b-form-group label="메모">
              <b-form-input v-model="newPlan.pt_memo" />
            </b-form-group>
            <b-form-group label="일차">
              <b-form-select v-model="newPlan.pt_day" :options="dayOption" plain />
            </b-form-group>
            <b-form-group label="차수">
              <b-form-select v-model="newPlan.bt_order" :options="orderOption" plain />
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
  name: 'Schedule',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Plantemplate: {
        apiUrl: '/plan_templates/paginate',
        fields: [{
          name: 'pt_title',
          title: '플랜명'
        }, {
          name: 'pt_time',
          title: '시간'
        }, {
          name: 'pt_memo',
          title: '메모'
        }]
      },
      appendParams: {
        pt_type: '1인가구',
        pt_day: 1,
        bt_order: 1,
        state: 1
      },
      HourOption: [],
      MinuteOption: [],
      dayOption: [],
      typeOption: ['1인가구', '수험생/학부모', '장년층', '기타'],
      orderOption: [],
      selectedPlan: {
        pt_no: '',
        pt_type: '',
        pt_title: '',
        pt_time: '',
        pt_memo: '',
        pt_day: '',
        bt_order: '',
        state: '',
        h: '',
        m: ''
      },
      newPlan: {
        pt_type: '',
        pt_title: '',
        pt_time: '',
        pt_memo: '',
        pt_day: '',
        bt_order: '',
        h: '',
        m: ''
      },
      file: ''
    }
  },
  async created () {
    this.forD_time()
    this.forH_time()
    this.forM_time()

    try {
      this.orderOption = await this.$axios.get('/batches')
        .then(response => response.data)
        .then(data => data.map(x => x.bt_order))
    } catch (e) {
      console.log(e)
    }
  },
  methods: {
    forD_time () {
      for (var i = 1; i <= 31; i++) {
        this.dayOption.push(i)
      }
    },
    forH_time () {
      for (var i = 0; i < 24; i++) {
        this.HourOption.push(this.pad(i, 2))
      }
    },
    forM_time () {
      for (var i = 0; i < 60; i++) {
        this.MinuteOption.push(this.pad(i, 2))
      }
    },
    pad (n, width) {
      n = n + ''
      return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n
    },
    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
    },
    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    },
    myFetch (apiUrl, httpOptions) {
      return this.$axios.get(apiUrl, httpOptions)
    },
    planClick (row) {
      this.selectedPlan.pt_no = row.pt_no
      this.selectedPlan.pt_type = row.pt_type
      this.selectedPlan.pt_title = row.pt_title
      this.selectedPlan.h = row.pt_time.split(':')[0]
      this.selectedPlan.m = row.pt_time.split(':')[1]
      this.selectedPlan.pt_memo = row.pt_memo
      this.selectedPlan.pt_day = row.pt_day
      this.selectedPlan.bt_order = row.bt_order
      this.selectedPlan.state = row.state
      this.$refs['modalbackdrop'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    planStateUdp (state) {
      try {
        this.$axios.put('/plan_templates/' + this.selectedPlan.pt_no, {
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
    planChange () {
      this.$refs.vuetable.refresh()
    },
    planUdp () {
      if (this.selectedPlan.pt_type !== '' && this.selectedPlan.pt_type !== undefined &&
        this.selectedPlan.pt_title !== '' && this.selectedPlan.pt_title !== undefined &&
        this.selectedPlan.h !== '' && this.selectedPlan.h !== undefined &&
        this.selectedPlan.m !== '' && this.selectedPlan.m !== undefined &&
        this.selectedPlan.pt_memo !== '' && this.selectedPlan.pt_memo !== undefined &&
        this.selectedPlan.pt_day !== '' && this.selectedPlan.pt_day !== undefined &&
        this.selectedPlan.bt_order !== '' && this.selectedPlan.bt_order !== undefined &&
        this.selectedPlan.state !== '' && this.selectedPlan.state !== undefined) {
        try {
          this.$axios.put('/plan_templates/' + this.selectedPlan.pt_no, {
            pt_type: this.selectedPlan.pt_type,
            pt_title: this.selectedPlan.pt_title,
            pt_time: this.selectedPlan.h + ':' + this.selectedPlan.m,
            pt_memo: this.selectedPlan.pt_memo,
            pt_day: this.selectedPlan.pt_day,
            bt_order: this.selectedPlan.bt_order,
            state: this.selectedPlan.state
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
      this.newPlan.pt_type = ''
      this.newPlan.pt_title = ''
      this.newPlan.pt_time = ''
      this.newPlan.pt_memo = ''
      this.newPlan.pt_day = ''
      this.newPlan.bt_order = ''
      this.$refs['modalright'].show()
    },
    regist () {
      if (this.newPlan.pt_type !== '' && this.newPlan.pt_type !== undefined &&
        this.newPlan.pt_title !== '' && this.newPlan.pt_title !== undefined &&
        this.newPlan.h !== '' && this.newPlan.h !== undefined &&
        this.newPlan.m !== '' && this.newPlan.m !== undefined &&
        this.newPlan.pt_memo !== '' && this.newPlan.pt_memo !== undefined &&
        this.newPlan.pt_day !== '' && this.newPlan.pt_day !== undefined &&
        this.newPlan.bt_order !== '' && this.newPlan.bt_order !== undefined &&
        this.newPlan.state !== '' && this.newPlan.bt_order !== undefined
      ) {
        try {
          this.$axios.post('/plan_templates', {
            pt_type: this.newPlan.pt_type,
            pt_title: this.newPlan.pt_title,
            pt_time: this.newPlan.h + ':' + this.newPlan.m,
            pt_memo: this.newPlan.pt_memo,
            pt_day: this.newPlan.pt_day,
            bt_order: this.newPlan.bt_order,
            state: 1
          }).then((response) => {
            this.$refs.vuetable.refresh()
            this.$refs['modalright'].hide()
            this.addNotification('primary', '안내', '등록되었습니다')
          })
        } catch (e) {
          console.log(e)
        }
      } else {
        console.log(this.newPlan.h + ':' + this.newPlan.m)
        this.addNotification('error filled', '안내', '모든 항목을 채워주세요')
      }
    }
  }
}
</script>
