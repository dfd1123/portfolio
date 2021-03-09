import Vue from 'vue'
import utils from './utils'
import responses from './responses'
import dates from './dates'
import mobile from './mobile_certify'
import { mapGetters, mapMutations } from 'vuex'
import { createUniqIdsMixin } from 'vue-uniq-ids'
import Datepicker from 'vuejs-datepicker'
import DatePick from 'vue-date-pick'
import 'vue-date-pick/dist/vueDatePick.css'
import isVisible from 'is-element-visible'
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/material.css'
import VueMonthlyPicker from 'vue-monthly-picker'
import { VMonthPickerInput, VMonthPickerCustom } from 'v-month-picker'
import _isEmpty from 'lodash/isEmpty'
import _get from 'lodash/get'
import _set from 'lodash/set'
import _head from 'lodash/head'
import _last from 'lodash/last'
import _castArray from 'lodash/castArray'
import _take from 'lodash/take'
import _takeRight from 'lodash/takeRight'
import _flatten from 'lodash/flatten'
import _uniq from 'lodash/uniq'
import _range from 'lodash/range'
import _rangeRight from 'lodash/rangeRight'
import _throttle from 'lodash/throttle'

const mixin = {
  data: function () {
    return {
      nativeToken: null
    }
  },
  components: {
    Datepicker,
    VueSlider,
    DatePick,
    VueMonthlyPicker,
    VMonthPickerInput,
    VMonthPickerCustom
  },
  computed: {
    ...mapGetters([
      'isUser',
      'isStore',
      'isConfirm',
      'user',
      'store',
      'company'
    ])
  },
  methods: {
    ...utils,
    ...responses,
    ...dates,
    ...mobile,
    ...mapMutations([
      'loading'
    ]),
    ...{
      _isEmpty,
      _get,
      _set,
      _head,
      _last,
      _castArray,
      _take,
      _takeRight,
      _flatten,
      _uniq,
      _range,
      _rangeRight,
      _throttle
    },
    isVisible,
    test (e) {
      console.log('test!!!', e)
    }
  }
}

const uniqIdsMixin = createUniqIdsMixin({
  prefix: 'uniq-',
  attrs: ['id', 'for', 'name']
})

Vue.mixin(mixin)
Vue.mixin(uniqIdsMixin)

export default mixin
