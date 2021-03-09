<template>
  <div class="form-input">
    <div class="full-input-btns">
      <input
        type="text"
        disabled="disabled"
        readonly="readonly"
        :value="info.map((x) => x.userNm).join(',')"
      >
      <button
        type="button"
        class="btn-01 type-04 squre-round"
        @click="layerPopOpen('expert-search');"
      >
        <span>전문가 조회</span>
      </button>
    </div>
    <SearchExpert
      @value-set="valueSet"
      @value-found="valueFound"
      :status="status"
      :xpert-uuids="castArray(inputData.value)"
      :svc-id="inputData.svcId"
      :single="inputData.single"
      :style="{visibility: readonly ? 'hidden' : 'visible'}"
      :pre-loading="inputData.preLoading"
    />
  </div>
</template>

<script>
  import SearchExpert from '@/components/popup/SearchExpert.vue'
  import isEqual from 'lodash/isEqual'
  import castArray from 'lodash/castArray'

  export default {
    props: {
      inputData: {
        type: Object,
        default () {
          return {}
        },
        required: true
      },
      readonly: {
        type: Boolean,
        default: false
      },
      status: {
        type: String,
        required: true
      },
      preLoading: {
        type: Boolean,
        default: false
      }
    },
    components: {
      SearchExpert
    },
    data () {
      return {
        info: []
      }
    },
    methods: {
      ...{ isEqual, castArray },
      valueSet (info) {
        this.info = info
        const value = this.info.map(x => x.userUuid)

        if (this.inputData.single) {
          this.$emit('input', this.FirstData(value))
        } else {
          this.$emit('input', value)
        }
      },
      valueFound (info) {
        this.info = info
      },
      layerPopOpen (_id) {
        window.$('.' + _id).stop(true).fadeIn(350)
      }
    }
  }
</script>

<style scoped>
</style>
