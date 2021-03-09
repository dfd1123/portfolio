<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.disease')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card :title="$t('menu.disease')">
          <b-button
            variant="info"
            class="mb-2"
            style="position: absolute;right: 1em;top: 1em;"
            @click="rightmodal"
          >등록</b-button>
          <vuetable
            ref="vuetable"
            :api-url="Disease.apiUrl"
            :fields="Disease.fields"
            :http-fetch="myFetch"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="dcClick"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
        <b-modal id="modalbackdrop" ref="modalbackdrop" title="질병 상세 정보">
          <div>
            <b-form>
              <b-form-group label="카테고리 명">
                <b-form-input v-model="selectedDisease.dc_cat_name" />
              </b-form-group>
              <b-form-group label="비고">
                <b-form-input v-model="selectedDisease.dc_cat_etc" />
              </b-form-group>
            </b-form>
            <p>상태 : {{['비활성화','활성화'][selectedDisease.state]}}</p>
          </div>
          <template slot="modal-footer">
            <b-button
              variant="primary"
              v-if="selectedDisease.state == 0"
              @click="diseaseStateUdp(1)"
            >삭제 복구</b-button>
            <b-button
              variant="secondary"
              v-if="selectedDisease.state == 1"
              @click="diseaseStateUdp(0)"
            >삭제</b-button>
            <b-button variant="outline-info" @click="diseaseUdp()">수정</b-button>
            <b-button variant="outline-secondary" @click="hideModal('modalbackdrop')">닫기</b-button>
          </template>
        </b-modal>
        <b-modal
          id="modalright"
          ref="modalright"
          title="질병 등록"
          modal-class="modal-right"
          :no-close-on-backdrop="true"
        >
          <b-form>
            <b-form-group label="카테고리 명">
              <b-form-input v-model="newDisease.dc_cat_name" />
            </b-form-group>
            <b-form-group label="비고">
              <b-form-input v-model="newDisease.dc_cat_etc" />
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
export default {
  name: 'Disease',
  components: {
    'vuetable': Vuetable,
    'vuetable-pagination-bootstrap': VuetablePaginationBootstrap
  },
  data () {
    return {
      Disease: {
        apiUrl: '/disease_categories/paginate',
        fields: [{
          name: 'dc_cat_name',
          title: '카테고리 명'
        }, {
          name: 'dc_cat_etc',
          title: '비고'
        }, {
          name: 'state',
          title: '상태',
          callback: this.state
        }]
      },
      selectedDisease: {
        dc_no: '',
        dc_cat_name: '',
        dc_cat_etc: '',
        state: ''
      },
      newDisease: {
        dc_cat_name: '',
        dc_cat_etc: ''
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
    dcClick (row) {
      this.selectedDisease.dc_no = row.dc_no
      this.selectedDisease.dc_cat_name = row.dc_cat_name
      this.selectedDisease.dc_cat_etc = row.dc_cat_etc
      this.selectedDisease.state = row.state
      this.$refs['modalbackdrop'].show()
    },
    hideModal (refname) {
      this.$refs[refname].hide()
    },
    diseaseStateUdp (state) {
      try {
        this.$axios.put('/disease_categories/' + this.selectedDisease.dc_no, {
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
    diseaseUdp () {
      if (this.selectedDisease.dc_cat_name !== '' && this.selectedDisease.dc_cat_name !== undefined &&
        this.selectedDisease.dc_cat_etc !== '' && this.selectedDisease.dc_cat_etc !== undefined) {
        try {
          this.$axios.put('/disease_categories/' + this.selectedDisease.dc_no, {
            dc_cat_name: this.selectedDisease.dc_cat_name,
            dc_cat_etc: this.selectedDisease.dc_cat_etc,
            state: this.selectedDisease.state
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
      this.newDisease.dc_cat_name = ''
      this.newDisease.dc_cat_etc = ''
      this.$refs['modalright'].show()
    },
    regist () {
      if (this.newDisease.dc_cat_name !== '' && this.newDisease.dc_cat_name !== undefined
      ) {
        try {
          this.$axios.post('/disease_categories', {
            dc_cat_name: this.newDisease.dc_cat_name,
            dc_cat_etc: this.newDisease.dc_cat_etc,
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
        this.addNotification('error filled', '안내', '모든 항목을 채워주세요')
      }
    },
    state (state) {
      return ['비활성', '활성'][state]
    }
  }
}
</script>
