<template>
  <div class="form-input">
    <div class="full-input-btns">
      <input
        type="text"
        disabled="disabled"
        readonly="readonly"
        :value="info.unitSvcNm"
      >
      <button
        type="button"
        class="btn-01 type-04 squre-round"
        @click="layerPopOpen(`unit-service-search-${index}`);"
      >
        <span>찾기</span>
      </button>
    </div>
    <SearchUnitService
      @value-set="valueSet"
      @value-found="valueFound"
      :status="status"
      :index="index"
      :unit-svc-id="value"
      :pre-loading="true"
    />
  </div>
</template>

<script>
  import SearchUnitService from '@/components/popup/SearchUnitService.vue'
  import isEqual from 'lodash/isEqual'

  export default {
    props: {
      value: {
        type: String,
        default: ''
      },
      status: {
        type: String,
        required: true
      },
      index: {
        type: Number,
        required: true
      }
    },
    components: {
      SearchUnitService
    },
    data () {
      return {
        info: []
      }
    },
    watch: {
      value (newVal) {
        if (this.info !== newVal) {
          this.info = []
        }
      }
    },
    methods: {
      ...{ isEqual },
      valueSet (info) {
        this.info = info
        const value = info.unitSvcId

        this.$emit('input', value)
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
