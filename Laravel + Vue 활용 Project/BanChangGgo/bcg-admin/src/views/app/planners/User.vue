<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.user')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <div class="mb-2">
        <b-button
          variant="empty"
          class="pt-0 pl-0 d-inline-block d-md-none"
          v-b-toggle.displayOptions
        >
          {{ $t('survey.display-options') }}
          <i class="simple-icon-arrow-down align-middle" />
        </b-button>
        <b-collapse id="displayOptions" class="d-md-block">
          <div class="d-block d-md-inline-block pt-1">
            <b-dropdown
              id="ddown1"
              :text="`${sort.label}`"
              variant="outline-dark"
              class="mr-1 float-md-left btn-group"
              size="lg"
            >
              <b-dropdown-item
                v-for="(order,index) in sortOptions"
                :key="`order${index}`"
                @click="changeOrderBy(order)"
              >{{ order.label }}</b-dropdown-item>
            </b-dropdown>
            <b-row>
              <b-colxx xxs="8">
                <b-form-input v-model="paramType.keyword" />
              </b-colxx>
              <b-colxx xxs="4">
                <div class="simple-line-icons">
                  <div class="glyph">
                    <div
                      class="glyph-icon simple-icon-magnifier"
                      @click="searchUser"
                      style="cursor:pointer"
                    ></div>
                  </div>
                </div>
              </b-colxx>
            </b-row>
          </div>
        </b-collapse>
      </div>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.user')">
          <vuetable
            ref="vuetable"
            :api-url="User.apiUrl"
            :fields="User.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="usrClick"
            :append-params="appendParams"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalright" ref="modalright" title="유저 상세 정보" class="modal-right">
          <div>
            <p>이메일 : {{selectedUser.usr_email}}</p>
            <p>기타정보 : {{selectedUser.usr_extra}}</p>
            <p>bmi 정보 : {{selectedUser.bmi.text}}</p>
            <p>이름 : {{selectedUser.usr_name}}</p>
            <p>등록일 : {{selectedUser.usr_reg_dt}}</p>
            <p>가입 경로 : {{selectedUser.usr_reg_type}}</p>
            <p>
              썸네일 :
              <img
                :src="`/fdata/user_thumb/${selectedUser.usr_thumb}`"
                style="max-width: 100%; max-height: 25vmin;"
              />
            </p>
          </div>

          <b-button variant="info" v-if="selectedUser.state == 0" @click="userStateUdp(1)">활성화 하기</b-button>
          <b-button
            variant="secondary"
            v-if="selectedUser.state == 1"
            @click="userStateUdp(0)"
          >비활성화 하기</b-button>
          <template slot="modal-footer">
            <b-button variant="outline-secondary" @click="hideModal('modalright')">닫기</b-button>
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
  name: 'User',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      User: {
        apiUrl: '/users/paginate',
        fields: [{
          name: 'usr_no',
          title: '고유번호'
        }, {
          name: 'usr_name',
          title: '이름'
        }, {
          name: 'usr_email',
          title: '이메일'
        }, {
          name: 'usr_reg_dt',
          title: '등록일(탈퇴일)'
        }, {
          name: 'state',
          title: '상태',
          callback: this.state
        }]
      },
      selectedUser: {
        usr_no: '',
        usr_email: '',
        usr_extra: '',
        usr_name: '',
        usr_reg_dt: '',
        usr_reg_type: '',
        usr_thumb: '',
        state: '',
        bmi: {
          height: '',
          weight: '',
          bmi: {
            value: '',
            text: ''
          },
          text: ''
        }
      },
      sort: { column: 'usr_no', label: '고유번호' },
      sortOptions: [
        { column: 'usr_no', label: '고유번호' },
        { column: 'usr_email', label: '이메일' },
        { column: 'usr_name', label: '이름' }
      ],
      selectedItems: [],
      appendParams: {},
      paramType: {
        type: 'usr_no',
        keyword: ''
      }
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
    usrClick (row) {
      this.selectedUser.usr_no = row.usr_no
      this.selectedUser.usr_email = row.usr_email
      if (JSON.parse(row.usr_extra).gender === 'F') {
        this.selectedUser.usr_extra = '(성별 : 여성)'
      } else {
        this.selectedUser.usr_extra = '(성별 : 남성)'
      }
      this.selectedUser.usr_extra += ' , (태어난 년도 : ' + JSON.parse(row.usr_extra).born_year + ')'
      this.selectedUser.usr_extra += ' , (유저 타입 : ' + JSON.parse(row.usr_extra).user_type + ')'
      this.selectedUser.bmi.height = JSON.parse(row.usr_extra).height
      this.selectedUser.bmi.weight = JSON.parse(row.usr_extra).weight
      this.selectedUser.bmi.bmi.value = (JSON.parse(row.usr_extra).weight / ((JSON.parse(row.usr_extra).height / 100) * (JSON.parse(row.usr_extra).height / 100))).toFixed(2)
      if (this.selectedUser.bmi.bmi.value < 18.5) {
        this.selectedUser.bmi.bmi.text = '저체중'
      } else if (this.selectedUser.bmi.bmi.value >= 18.5 && this.selectedUser.bmi.bmi.value < 23) {
        this.selectedUser.bmi.bmi.text = '정상'
      } else if (this.selectedUser.bmi.bmi.value >= 23 && this.selectedUser.bmi.bmi.value < 25) {
        this.selectedUser.bmi.bmi.text = '과체중'
      } else if (this.selectedUser.bmi.bmi.value >= 25 && this.selectedUser.bmi.bmi.value < 30) {
        this.selectedUser.bmi.bmi.text = '비만'
      } else if (this.selectedUser.bmi.bmi.value >= 30) {
        this.selectedUser.bmi.bmi.text = '고도비만'
      }
      this.selectedUser.bmi.text = '키 : ' + this.selectedUser.bmi.height + ' / 몸무게 : ' + this.selectedUser.bmi.weight + ' / bmi 수치 : ' + this.selectedUser.bmi.bmi.value + '(' + this.selectedUser.bmi.bmi.text + ')'
      this.selectedUser.usr_name = row.usr_name
      this.selectedUser.usr_reg_dt = row.usr_reg_dt
      this.selectedUser.usr_reg_type = row.usr_reg_type
      this.selectedUser.usr_thumb = row.usr_thumb
      this.selectedUser.state = row.state
      this.$refs['modalright'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    userStateUdp (state) {
      try {
        this.$axios.put('/users/' + this.selectedUser.usr_no, {
          state: state
        }).then((response) => {
          this.$refs.vuetable.refresh()
          this.$refs['modalright'].hide()
        })
      } catch (e) {
        console.log(e)
      }
    },
    changeOrderBy (sort) {
      this.sort = sort
      this.paramType.type = sort.column
    },
    searchUser () {
      this.appendParams = this.paramType
      this.$refs.vuetable.refresh()
    },
    state (state) {
      return ['비활성', '활성', '탈퇴'][state]
    }
  }
}
</script>

<style>
.vuetable tbody tr td,
.modal-body div p {
  text-overflow: ellipsis;
  overflow: hidden;
}
</style>
