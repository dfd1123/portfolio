<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.batch')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.batch')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="Batch.apiUrl"
            :fields="Batch.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="btClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="차수 상세페이지">
          <b-form>
            <b-form-group label="차수">
              <p>{{selectedBatch.bt_order}} 차</p>
            </b-form-group>
            <b-form-group label="모집일">
              <v-date-picker
                mode="range"
                v-model="selectedBatch.date_picker"
                :input-props="{ class:'form-control', placeholder: $t('form-components.date') }"
              ></v-date-picker>
            </b-form-group>
            <b-form-group label="메모">
              <b-form-input v-model="selectedBatch.bt_memo" />
            </b-form-group>
            <b-form-group label="인원제한">
              <b-form-input v-model="selectedBatch.bt_max" />
              <p style="color:#0100FF">인원제한이 0명이면 무제한</p>
            </b-form-group>
            <p>참가인원 : {{selectedBatch.bt_use}}</p>
            <p>상태 : {{['예정','진행중','종료'][selectedBatch.state]}}</p>
            <b-button variant="outline-info" @click="showList('modalnestedinline')">질문 관리</b-button>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="info" v-if="selectedBatch.state == 0" @click="batchStateUdp(1)">시작하기</b-button>
            <b-button
              variant="secondary"
              v-if="selectedBatch.state == 1"
              @click="batchStateUdp(2)"
            >종료하기</b-button>
            <b-button variant="outline-info" @click="batchUdp()">수정</b-button>
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
                        <th>타입</th>
                        <th>비고</th>
                      </tr>
                    </thead>
                    <tbody v-for="(list,index) in qna_list_type" :key="index">
                      <tr v-for="(qna, index2) in list" :key="index2">
                        <td>{{(index+1)}} - {{(index2+1)}}</td>
                        <td>{{qna.title}}</td>
                        <td>{{qna.type}}</td>
                        <td>
                          <b-badge
                            class="mb-1"
                            pill
                            variant="danger"
                            @click="qnaDelete(index, index2)"
                          >삭제</b-badge>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </b-card>
              </b-colxx>
            </b-row>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="info" @click="showModal('qnaregistmodal')" class="mr-1">추가</b-button>
            <b-button variant="success" @click="qnaUpdate()" class="mr-1">수정</b-button>
            <b-button variant="secondary" @click="hideModal('modalnestedinline')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright2"
          ref="qnaregistmodal"
          title="질문지 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <p style="margin-top:1em;">※ 유저가 한 페이지에서 보이는 질문입니다.</p>

          <b-badge class="mb-1" variant="info" style="cursor:pointer" @click="addQna()">+</b-badge>
          <b-badge class="mb-1" variant="primary" style="cursor:pointer" @click="removeQna()">-</b-badge>
          <b-form
            v-for="(qna, index) in newQna"
            :key="index"
            style="border-bottom:1px solid #8d8d8d"
          >
            <div style="padding-top:1em;">
              <p
                style="font-size: 1.4em;font-weight: bold;"
              >{{qna_list_type.length+1}} - {{index+1}}번째 질문</p>
            </div>
            <b-form-group label="질문 내용">
              <b-form-input v-model="newQna[index].title" placeholder="자세하게 적어주세요" />
            </b-form-group>
            <b-form-group label="질문 타입">
              <b-form-select
                v-model="newQna[index].type"
                :options="selectOption"
                plain
                @change="typechange(index, $event)"
              />
            </b-form-group>
            <b-form-group v-for="(info, index2) in newQna[index].option" :key="index2">
              <b-form-input v-model="newQna[index].option[index2].name" placeholder="답변 항목" />
            </b-form-group>
            <b-form-group>
              <b-button
                variant="light"
                v-if="newQna[index].type === 'select' || newQna[index].type === 'single' || newQna[index].type === 'multi'"
                type="button"
                @click="addOption(index)"
              >답변 항목 추가</b-button>
              <b-button
                variant="dark"
                v-if="newQna[index].type === 'select' || newQna[index].type === 'single' || newQna[index].type === 'multi'"
                type="button"
                @click="removeOption(index)"
              >답변 항목 제거</b-button>
            </b-form-group>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="success" @click="qnaRegist" class="mr-1">등록</b-button>
            <b-button variant="secondary" @click="hideModal('qnaregistmodal')">취소</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="차수 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="차수">
              <b-form-input v-model="newBatch.bt_order" />
            </b-form-group>
            <b-form-group label="모집일">
              <v-date-picker
                mode="range"
                v-model="newBatch.date_picker"
                :input-props="{ class:'form-control', placeholder: $t('form-components.date') }"
              ></v-date-picker>
            </b-form-group>
            <b-form-group label="메모">
              <b-form-input v-model="newBatch.bt_memo" />
            </b-form-group>
            <b-form-group label="인원제한">
              <b-form-input v-model="newBatch.bt_max" />
              <p style="color:#0100FF">인원제한이 0명이면 무제한</p>
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
  name: 'Batch',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Batch: {
        apiUrl: '/batches/paginate',
        fields: [{
          name: 'bt_order',
          title: '차수'
        }, {
          name: 'bt_start',
          title: '모집시작일'
        }, {
          name: 'bt_end',
          title: '모집종료일'
        }, {
          name: 'bt_max',
          title: '인원제한',
          callback: this.btMax
        }, {
          name: 'state',
          title: '상태',
          callback: this.state
        }]
      },
      qna_list_type: [],
      orderOption: [],
      selectedBatch: {
        bt_no: '',
        bt_order: '',
        bt_start: '',
        bt_end: '',
        bt_memo: '',
        bt_max: '',
        bt_use: '',
        bt_reg_dt: '',
        bt_qna_list: [],
        state: '',
        date_picker: {
          start: '',
          end: ''
        }
      },
      newBatch: {
        bt_order: '',
        bt_start: '',
        bt_end: '',
        bt_memo: '',
        bt_max: '',
        bt_use: '',
        bt_reg_dt: '',
        bt_qna_list: [],
        date_picker: {
          start: '',
          end: ''
        }
      },
      selectOption: ['text', 'select', 'single', 'multi', 'number', 'time'],
      newQna: [{
        title: '',
        type: 'text',
        option: null
      }],
      type: 0
    }
  },
  async created () {
    try {
      this.orderOption = await this.$axios.get('/batches')
        .then(response => response.data)
        .then(data => data.map(x => x.bt_order))

      this.newBatch.bt_order = Math.max(...this.orderOption) + 1
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
    btClick (row) {
      this.selectedBatch.bt_no = row.bt_no
      this.selectedBatch.bt_order = row.bt_order
      this.selectedBatch.bt_start = new Date(row.bt_start)
      this.selectedBatch.bt_end = new Date(row.bt_end)
      this.selectedBatch.date_picker.start = new Date(row.bt_start)
      this.selectedBatch.date_picker.end = new Date(row.bt_end)
      this.selectedBatch.bt_memo = row.bt_memo
      this.selectedBatch.bt_max = row.bt_max
      this.selectedBatch.bt_use = row.bt_use
      this.selectedBatch.bt_reg_dt = row.bt_reg_dt
      this.selectedBatch.bt_qna_list = JSON.parse(row.bt_qna_list)
      this.selectedBatch.state = row.state
      this.$refs['modalbackdrop'].show()
    },
    showModal (refname) {
      this.newQna = [{
        title: '',
        type: 'text',
        option: null
      }]
      this.$refs[refname].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    showList (refname) {
      this.qna_list_type = JSON.parse(JSON.stringify(this.selectedBatch.bt_qna_list))
      this.$refs[refname].show()
    },
    qnaDelete (main, sub) {
      this.qna_list_type[main].splice(sub, 1)
      if (this.qna_list_type[main].length === 0) {
        this.qna_list_type.splice(main, 1)
      }
    },
    qnaUpdate () {
      try {
        this.$axios.put('/batches/' + this.selectedBatch.bt_no, {
          bt_qna_list: JSON.stringify(this.qna_list_type)
        }).then((response) => {
          this.$refs.vuetable.refresh()
          this.$refs['modalnestedinline'].hide()
          this.addNotification('primary')
        })
      } catch (e) {
        console.log(e)
      }
    },
    typechange (index, event) {
      if (event === 'multi' ||
        event === 'single' ||
        event === 'select') {
        this.newQna[index].option = [{ name: '' }]
      } else {
        this.newQna[index].option = null
      }
    },
    addQna () {
      this.newQna.push({ option: null, title: '', type: 'text' })
    },
    removeQna () {
      if (this.newQna.length > 1) {
        this.newQna.splice(this.newQna.length - 1, 1)
      }
    },
    addOption (index) {
      this.newQna[index].option.push({ name: '' })
    },
    removeOption (index) {
      if (this.newQna[index].option.length > 1) {
        this.newQna[index].option.splice(this.newQna[index].option.length - 1, 1)
      }
    },
    batchStateUdp (state) {
      try {
        this.$axios.put('/batches/' + this.selectedBatch.bt_no, {
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
    batchUdp () {
      if (this.selectedBatch.bt_order !== '' && this.selectedBatch.bt_order !== undefined &&
        this.selectedBatch.date_picker.start !== '' && this.selectedBatch.date_picker.start !== undefined &&
        this.selectedBatch.date_picker.end !== '' && this.selectedBatch.date_picker.end !== undefined &&
        this.selectedBatch.bt_memo !== '' && this.selectedBatch.bt_memo !== undefined &&
        this.selectedBatch.bt_max !== '' && this.selectedBatch.bt_max !== undefined &&
        this.selectedBatch.state !== '' && this.selectedBatch.state !== undefined) {
        try {
          this.$axios.put('/batches/' + this.selectedBatch.bt_no, {
            bt_order: this.selectedBatch.bt_order,
            bt_start: this.selectedBatch.date_picker.start,
            bt_end: this.selectedBatch.date_picker.end,
            bt_memo: this.selectedBatch.bt_memo,
            bt_max: this.selectedBatch.bt_max,
            state: this.selectedBatch.state
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
      // this.newBatch.bt_order = ''
      this.newBatch.bt_start = ''
      this.newBatch.bt_end = ''
      this.newBatch.bt_memo = ''
      this.newBatch.bt_max = ''
      this.newBatch.bt_reg_dt = ''
      this.newBatch.bt_qna_list = ''
      this.newBatch.date_picker.start = ''
      this.newBatch.date_picker.end = ''
      this.$refs['modalright'].show()
    },
    regist () {
      if (this.newBatch.bt_order !== '' && this.newBatch.bt_order !== undefined &&
        this.newBatch.date_picker.start !== '' && this.newBatch.date_picker.start !== undefined &&
        this.newBatch.date_picker.end !== '' && this.newBatch.date_picker.end !== undefined &&
        this.newBatch.bt_memo !== '' && this.newBatch.bt_memo !== undefined &&
        this.newBatch.bt_max !== '' && this.newBatch.bt_max !== undefined &&
        this.newBatch.bt_order !== '' && this.newBatch.bt_order !== undefined &&
        this.newBatch.state !== '' && this.newBatch.bt_order !== undefined
      ) {
        try {
          this.$axios.post('/batches', {
            bt_order: this.newBatch.bt_order,
            bt_start: this.newBatch.date_picker.start,
            bt_end: this.newBatch.date_picker.end,
            bt_memo: this.newBatch.bt_memo,
            bt_max: this.newBatch.bt_max,
            state: 0
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
    qnaRegist () {
      try {
        var qnalist = this.qna_list_type
        qnalist.push(this.newQna)
        this.$axios.put('/batches/' + this.selectedBatch.bt_no, {
          bt_qna_list: JSON.stringify(qnalist)
        }).then((response) => {
          this.$refs.vuetable.refresh()
          this.$refs['qnaregistmodal'].hide()
          this.addNotification('primary', '안내', '추가되었습니다')
        })
      } catch (e) {
        console.log(e)
      }
    },
    state (state) {
      return ['예정', '진행중', '종료'][state]
    },
    btMax (state) {
      return state === 0 ? '무제한' : state
    }
  }
}
</script>
