<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.write')" />
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
          <b-form-input v-model="Paramtype.bt_order" />
        </b-form-group>
      </b-colxx>
      <b-colxx sm="4">
        <b-button variant="success" class="mb-2" @click="searchHRP" style="margin-top:2em;">검색</b-button>
      </b-colxx>
    </b-row>
    <b-row v-if="foundUser">
      <b-colxx xxs="12" style="margin-bottom:10px;">
        <b-card>
          <span class="tab">유저 고유번호: {{foundUser.usr_no}}</span>
          <span class="tab">유저차수: {{foundUser.bt_order}}</span>
          <span class="tab">유저이름: {{foundUser.usr_name}}</span>
          <span
            class="tab"
          >작성한 질병목록: [{{foundUser.disease_list.map(x => x.dc_cat_name).join(', ') || '없음'}}]</span>
          <span class="tab">리포트 고유번호: {{foundUser.hr_no}}</span>
          <span class="tab">리포트 상태: {{ foundUser.state === 0 ? '작성중' : '작성완료'}}</span>
        </b-card>
      </b-colxx>
    </b-row>
    <b-row v-else>
      <b-colxx xxs="12" style="margin-bottom:10px">
        <b-card style="opacity: 0.5">검색된 유저가 없습니다</b-card>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.write')">
          <b-button
            v-if="searchOn"
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
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
            <b-form-group label="한마디">
              <b-form-input v-model="selectedHRP.hrp_comment" />
            </b-form-group>
            <b-form-group label="등록일">
              <p>{{selectedHRP.hrp_reg_dt}}</p>
            </b-form-group>
            <b-form-group label="보조약품">
              <table style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>이름</th>
                    <th>설명</th>
                    <th>선택</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="mdcn in mdcn_list" :key="mdcn.mdcn_no">
                    <td>{{mdcn.mdcn_title}}</td>
                    <td>{{mdcn.mdcn_desc}}</td>
                    <td>
                      <input
                        type="checkbox"
                        ref="mdcn_check"
                        :value="mdcn.mdcn_no"
                        v-model="mdcn_check"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-form-group>
            <b-form-group label="영양개선">
              <table style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>이름</th>
                    <th>설명</th>
                    <th>선택</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="ntrcn in ntrcn_list" :key="ntrcn.ntrcn_no">
                    <td>{{ntrcn.ntrcn_title}}</td>
                    <td>{{ntrcn.ntrcn_desc}}</td>
                    <td>
                      <input
                        type="checkbox"
                        ref="ntrcn_check"
                        :value="ntrcn.ntrcn_no"
                        v-model="ntrcn_check"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-form-group>
            <b-form-group label="생활개선">
              <table style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>이름</th>
                    <th>링크</th>
                    <th>설명</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="hlth in hlth_list" :key="hlth.hlth_no">
                    <td>{{hlth.hlth_title}}</td>
                    <td>{{hlth.hlth_desc}}</td>
                    <td>
                      <input
                        type="checkbox"
                        ref="hlth_check"
                        :value="hlth.hlth_no"
                        v-model="hlth_check"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </b-form-group>
            <b-form-group label="질병분류">
              <p>{{selectedHRP.dc_no}}</p>
            </b-form-group>
            <b-form-group label="한마디 자세히보기">
              <b-textarea v-model="selectedHRP.hrp_comment_detail" :rows="10" :max-rows="10" />
            </b-form-group>
            <b-form-group label="한마디(의학)">
              <b-textarea v-model="selectedHRP.hrp_comment_med" :rows="10" :max-rows="10" />
            </b-form-group>
          </b-form>
          <template slot="modal-footer">
            <b-button variant="danger" @click="HRPdelete">삭제</b-button>
            <b-button variant="info" @click="HRPUpdate">수정</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="건강 리포트 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="한마디">
              <b-form-input v-model="newHRP.hrp_comment" />
            </b-form-group>
            <b-form-group label="보조약품">
              <div style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
              <table style="width:100%;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>이름</th>
                    <th>설명</th>
                    <th>선택</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="mdcn in mdcn_list" :key="mdcn.mdcn_no">
                    <td>{{mdcn.mdcn_title}}</td>
                    <td>{{mdcn.mdcn_desc}}</td>
                    <td>
                      <input
                        type="checkbox"
                        ref="mdcn_check"
                        :value="mdcn.mdcn_no"
                        v-model="reg_mdcn_check"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
              </div>
            </b-form-group>
            <b-form-group label="영양개선">
              <div style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
              <table style="width:100%;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>이름</th>
                    <th>설명</th>
                    <th>선택</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="ntrcn in ntrcn_list" :key="ntrcn.ntrcn_no">
                    <td>{{ntrcn.ntrcn_title}}</td>
                    <td>{{ntrcn.ntrcn_desc}}</td>
                    <td>
                      <input
                        type="checkbox"
                        ref="ntrcn_check"
                        :value="ntrcn.ntrcn_no"
                        v-model="reg_ntrcn_check"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
              </div>
            </b-form-group>
            <b-form-group label="생활개선">
              <div style="width:100%;border:1px solid #000;height: 10em;overflow-y: scroll;">
              <table style="width:100%;">
                <thead style="border-bottom: 1px solid #8d8d8d;">
                  <tr>
                    <th>이름</th>
                    <th>설명</th>
                    <th>선택</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="hlth in hlth_list" :key="hlth.hlth_no">
                    <td>{{hlth.hlth_title}}</td>
                    <td>{{hlth.hlth_desc}}</td>
                    <td>
                      <input
                        type="checkbox"
                        ref="hlth_check"
                        :value="hlth.hlth_no"
                        v-model="reg_hlth_check"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
              </div>
            </b-form-group>
            <b-form-group label="작성할 질병분류">
              <b-form-select v-model="newHRP.dc_no" :options="diseaseOption" plain />
              <p style="color:#0100FF;margin-top:1em;">
                선택지가 없다면 모든 질병에 대해
                <br />작성완료하였습니다
              </p>
            </b-form-group>
            <b-form-group label="한마디 자세히보기">
              <b-textarea v-model="newHRP.hrp_comment_detail" :rows="10" :max-rows="10" />
            </b-form-group>
            <b-form-group label="한마디(의학)">
              <b-textarea v-model="newHRP.hrp_comment_med" :rows="10" :max-rows="10" />
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
import { mapGetters } from 'vuex'

export default {
  name: 'Write',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  computed: {
    ...mapGetters(['currentUser'])
  },
  data () {
    return {
      HRP: {
        apiUrl: '',
        fields: [{
          name: 'dc_cat_name',
          title: '질병'
        }, {
          name: 'hrp_reg_dt',
          title: '작성 시간'
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
      userdisease: [

      ],
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
      searchOn: false,
      mdcn_list: [],
      mdcn_check: [],
      ntrcn_list: [],
      ntrcn_check: [],
      hlth_list: [],
      hlth_check: [],
      reg_mdcn_check: [],
      reg_ntrcn_check: [],
      reg_hlth_check: [],
      foundUser: null
    }
  },
  created () {
    this.loadMDCN()
    this.loadNTRCN()
    this.loadHEALTH()
    // console.log(this.currentUser.adm_no)
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
    loadMDCN () {
      try {
        this.$axios.get('/medicine_infos').then((response) => {
          for (var i = 0; i < response.data.length; i++) {
            this.mdcn_list = response.data
          }
        })
      } catch (e) {
        console.log(e)
      }
    },
    loadNTRCN () {
      try {
        this.$axios.get('/nutrition_infos').then((response) => {
          for (var i = 0; i < response.data.length; i++) {
            this.ntrcn_list = response.data
          }
        })
      } catch (e) {
        console.log(e)
      }
    },
    loadHEALTH () {
      try {
        this.$axios.get('/health_infos').then((response) => {
          for (var i = 0; i < response.data.length; i++) {
            this.hlth_list = response.data
          }
        })
      } catch (e) {
        console.log(e)
      }
    },
    loadDisease () {
      try {
        this.$axios.get('/disease_categories').then((response) => {
          // eslint-disable-next-line no-unused-vars
          this.diseaseOption = []
          var check = false
          for (var i = 0; i < response.data.length; i++) {
            check = false
            for (var j = 0; j < this.userdisease.length; j++) {
              if (response.data[i].dc_no === parseInt(this.userdisease[j])) {
                check = true
              }
            }
            if (!check) {
              this.diseaseOption.push({ value: response.data[i].dc_no, text: response.data[i].dc_cat_name })
            }
          }
        })
      } catch (e) {
        console.log(e)
      }
    },
    HRPClick (row) {
      this.selectedHRP.hrp_no = row.hrp_no
      this.selectedHRP.hrp_comment = row.hrp_comment
      this.selectedHRP.hrp_reg_dt = row.hrp_reg_dt
      this.selectedHRP.mdcn_info = row.mdcn_info
      this.mdcn_check = []
      for (var i = 0; i < row.mdcn_info.length; i++) {
        this.mdcn_check.push(row.mdcn_info[i].mdcn_no)
      }
      this.selectedHRP.ntrcn_info = row.ntrcn_info
      this.ntrcn_check = []
      for (i = 0; i < row.ntrcn_info.length; i++) {
        this.ntrcn_check.push(row.ntrcn_info[i].ntrcn_no)
      }
      this.selectedHRP.health_info = row.health_info
      this.hlth_check = []
      for (i = 0; i < row.health_info.length; i++) {
        this.hlth_check.push(row.health_info[i].hlth_no)
      }
      this.selectedHRP.dc_no = row.dc_no
      this.selectedHRP.hrp_comment_detail = row.hrp_comment_detail
      this.selectedHRP.hrp_comment_med = row.hrp_comment_med
      this.selectedHRP.hrp_extra = row.hrp_extra
      this.selectedHRP.hr_no = row.hr_no
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
    searchHRP () {
      if (this.Paramtype.usr_no === '' || this.Paramtype.bt_order === '') {
        this.addNotification('error filled', '안내', '모든 항목을 채워주세요')
      } else {
        this.HRP.apiUrl = '/health_report_pages/paginate'
        this.appendParams = this.Paramtype
        this.$refs.vuetable.refresh()
        this.userdisease = []
        try {
          this.$axios.get('/health_reports', {
            params: { usr_no: this.Paramtype.usr_no, bt_order: this.Paramtype.bt_order }
          }).then((response) => {
            for (var j = 0; j < response.data.length; j++) {
              if (response.data[j].bt_order === parseInt(this.Paramtype.bt_order)) {
                this.newHRP.hr_no = response.data[j].hr_no
                for (var i = 0; i < response.data[j].disease_list.length; i++) {
                  console.log(response.data[j].disease_list[i].dc_no, response.data[j].hr_no)
                  this.userdisease.push(response.data[j].disease_list[i].dc_no)
                }
              }
            }

            if (response.data.length > 0) {
              this.foundUser = response.data[0]
            } else {
              this.foundUser = null
            }

            this.searchOn = true
          })
        } catch (e) {
          console.log(e)
        }
      }
    },
    HRPUpdate () {
      try {
        this.$axios.put('/health_report_pages/' + this.selectedHRP.hrp_no, {
          hrp_comment: this.selectedHRP.hrp_comment,
          mdcn_info: JSON.stringify(this.mdcn_check),
          ntrcn_info: JSON.stringify(this.ntrcn_check),
          health_info: JSON.stringify(this.hlth_check),
          hrp_comment_detail: this.selectedHRP.hrp_comment_detail,
          hrp_comment_med: this.selectedHRP.hrp_comment_med
        }).then((response) => {
          // console.log(response)
          if (response.data !== '') {
            this.$refs.vuetable.refresh()
            this.$refs['modalbackdrop'].hide()
            this.addNotification('primary', '안내', '변경되었습니다')
          }
        })
      } catch (e) {
        console.log(e)
      }
    },
    HRPdelete () {
      try {
        this.$axios.delete('/health_report_pages/' + this.selectedHRP.hrp_no).then((response) => {
          // console.log(response)
          if (response.data !== '') {
            this.$refs.vuetable.refresh()
            this.$refs['modalbackdrop'].hide()
            this.addNotification('primary', '안내', '삭제되었습니다')
          }
        })
      } catch (e) {
        console.log(e)
      }
    },
    rightmodal () {
      this.newHRP.hrp_comment = ''
      this.newHRP.mdcn_info = ''
      this.newHRP.ntrcn_info = ''
      this.newHRP.health_info = ''
      this.newHRP.dc_no = ''
      this.newHRP.hrp_comment_detail = ''
      this.newHRP.hrp_commhrp_comment_medent = ''
      this.reg_mdcn_check = []
      this.reg_ntrcn_check = []
      this.reg_hlth_check = []
      this.loadDisease()
      this.$refs['modalright'].show()
    },
    regist () {
      if (this.newHRP.hrp_comment === '' ||
        this.newHRP.dc_no === '' ||
        this.newHRP.hrp_comment_detail === '' ||
        this.newHRP.hrp_comment_med === '' ||
        this.reg_mdcn_check.length === 0 ||
        this.reg_ntrcn_check.length === 0 ||
        this.reg_hlth_check.length === 0) {
        this.addNotification('error filled', '안내', '모든 항목을 채워주세요')
      } else {
        try {
          this.$axios.post('/health_report_pages', {
            hr_no: this.newHRP.hr_no,
            hrp_comment: this.newHRP.hrp_comment,
            mdcn_info: JSON.stringify(this.reg_mdcn_check),
            ntrcn_info: JSON.stringify(this.reg_ntrcn_check),
            health_info: JSON.stringify(this.reg_hlth_check),
            dc_no: this.newHRP.dc_no,
            hrp_comment_detail: this.newHRP.hrp_comment_detail,
            hrp_comment_med: this.newHRP.hrp_comment_med,
            adm_no: this.currentUser.adm_no
          }).then((response) => {
            // console.log(response)
            if (response.status === 201 && response.data !== '') {
              this.$refs.vuetable.refresh()
              this.$refs['modalright'].hide()
              this.addNotification('primary', '안내', '등록되었습니다')
            }
          })
        } catch (e) {
          console.log(e)
        }
      }
    }
  }
}
</script>

<style scoped>
.tab {
  margin-right: 28px;
  font-size: 1rem;
  white-space: nowrap;
}
</style>
