<template>
  <div class="form-input">
    <div class="full-input-btns">
      <input
        type="text"
        :value="info.unitSvcNm || ''"
        required
        readonly="readonly"
      >
      <button
        type="button"
        class="btn-01 type-04 squre-round"
        @click="layerPopOpen('unit-service-search--1');"
      >
        <span>찾기</span>
      </button>
    </div>
    <SearchUnitService
      @value-set="valueSet"
      @value-found="valueFound"
      :id="status + 'unit-service-search--1'"
      :index="-1"
      :status="status"
      :align="align"
      :unit-svc-id="inputData.value"
      :style="{visibility: readonly ? 'hidden' : 'visible'}"
      :pre-loading="inputData.preLoading"
    />
  </div>
</template>

<script>
  import SearchUnitService from '@/components/popup/SearchUnitService.vue'

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
      align: {
        type: String,
        default: 'right'
      },
      preLoading: {
        type: Boolean,
        default: false
      }
    },
    components: {
      SearchUnitService
    },
    watch: {
      'inputData.value' (newVal) {
        if (this.info.unitSvcId !== newVal) {
          this.info = {}
        }
      }
    },
    data () {
      return {
        info: {}
      }
    },
    methods: {
      layerPopOpen (_id) {
        window.$('.' + _id).stop(true).fadeIn(350)
      },
      layerPopClose (_obj) {
        window.$(_obj).parents('.layer-pop-parents').stop(true).fadeOut(350)
      },
      valueSet (info) {
        this.info = info
        this.$emit('input', this.info.unitSvcId)
      },
      valueFound (info) {
        this.info = info
      }
    }
  }
</script>

<style scoped>
</style>
