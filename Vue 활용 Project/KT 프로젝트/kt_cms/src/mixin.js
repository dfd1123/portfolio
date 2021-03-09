import Vue from 'vue'
import { mapGetters } from 'vuex'
import get from 'lodash/get'
import set from 'lodash/set'
import pick from 'lodash/pick'
import isEqual from 'lodash/isEqual'
import cloneDeep from 'lodash/cloneDeep'
import isEmpty from 'lodash/isEmpty'

/* mixins */
const mixin = {
  computed: {
    ...mapGetters([
      'isEditSideOpen',
      'isLogin',
      'user',
      'auth'
    ])
  },
  methods: {
    ...{
      get,
      set,
      pick,
      isEqual,
      cloneDeep,
      isEmpty
    },
    FormatDate (dateInput) {
      return this.$moment(dateInput).format('YYYY-MM-DD HH:mm:ss')
    },
    UnFormatDate (dateInput) {
      return this.$moment(dateInput).format('YYYY-MM-DDTHH:mm:ss.SSS')
    },
    CheckDate (dateInput) {
      if (typeof dateInput !== 'string') {
        return false
      }

      return this.$moment(dateInput, 'YYYY-MM-DDTHH:mm:ss.SSS', true).isValid() ||
        this.$moment(dateInput, 'YYYY-MM-DD HH:mm:ss', true).isValid()
    },
    FirstData (responseData, defaultValue = null) {
      if (responseData) {
        if (Array.isArray(responseData)) {
          if (responseData.length > 0) {
            return responseData[0]
          } else {
            return defaultValue
          }
        } else {
          return responseData
        }
      }

      return defaultValue
    },
    UpdatePageInfo (responseData) {
      if (responseData.data.pageInfo) {
        const { totalCount, size, page } = responseData.data.pageInfo
        this.records = Number(totalCount)
        this.size = Number(size)
        this.currentPage = Number(page)
      } else {
        this.records = 1
        this.size = 20
        this.currentPage = 1
      }
      if (this.records === 0 && this.currentPage > 1) {
        this.currentPage = this.currentPage - 1
        this.$refs.pagination.SetPage(this.currentPage)
      }

      return responseData
    },
    OnSearchPage () {
      this.currentPage = 1
      this.FetchData()
    },
    OnChangePage (currentPage) {
      this.currentPage = currentPage
      this.FetchData()
    },
    OnChangeSize (size) {
      this.size = size
      this.currentPage = 1
      this.FetchData()
    },
    NormalOrError (responseData) {
      if (![200, 204].includes(Number(responseData.data.responseCode))) {
        throw Error(responseData.data.message)
      }

      if (Number(responseData.data.responseCode) === 204) {
        throw Error(responseData.data.message)
      }

      return responseData
    },
    FirstOrError (responseData) {
      const first = this.FirstData(responseData.data.data)
      if (!first) {
        throw Error('조회결과가 없습니다')
      }

      return first
    },
    Requirement () {
      const empty = this.viewDatas.find(x => x.required && (Array.isArray(x.value) ? x.value.length === 0 : ['', null].includes(x.value)))
      if (empty) {
        alert(`'${empty.label}'을(를) 입력해주세요!`)
        return false
      }

      return true
    },
    GenaratorId () {
      let possible = 'abcdefghijklmnopqrstuvwxyz0123456789'
      let random = ''
      for (let i = 0; i < 2; i++) {
        random += possible.charAt(Math.floor(Math.random() * possible.length))
      }
      return this.$moment().format('MMDDhhmm') + random
    }
  }
}

Vue.mixin(mixin)
