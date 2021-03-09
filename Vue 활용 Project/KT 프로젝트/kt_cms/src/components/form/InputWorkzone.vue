<template>
  <div class="form-input">
    <div class="full-input-btns">
      <input
        type="text"
        disabled="disabled"
        readonly="readonly"
        :value="info.wrkZoneNm || ''"
      >
      <button
        type="button"
        class="btn-01 type-04 squre-round"
        @click.stop="layerPopOpen('wrkzone-search');"
      >
        <span>구역조회</span>
      </button>
    </div>
    <SearchWorkzone
      @value-set="valueSet"
      @value-found="valueFound"
      :status="status"
      :wrk-zone-id="inputData.value"
      :svc-id="inputData.svcId"
      :style="{visibility: readonly ? 'hidden' : 'visible'}"
      :pre-loading="inputData.preLoading"
    />
  </div>
</template>

<script>
  import SearchWorkzone from '@/components/popup/SearchWorkzone.vue'

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
      SearchWorkzone
    },
    data () {
      return {
        info: {}
      }
    },
    watch: {
      'inputData.value' (newVal) {
        if (this.info.wrkZoneId !== newVal) {
          this.info.wrkZoneId = null
        }
      },
      'inputData.svcId' (newVal) {
        if (this.info.svcId !== newVal) {
          this.info.svcId = null
        }
      }
    },
    methods: {
      valueSet (info) {
        this.info = info || {
          wrkZoneId: null,
          svcId: null
        }
        this.$emit('input', this.info.wrkZoneId)
      },
      valueFound (info) {
        this.info = info || {
          wrkZoneId: null,
          svcId: null
        }
      },
      layerPopOpen (_id) {
        if (this.readonly) {
          return
        }

        window.$('.' + _id).stop(true).fadeIn(350)
      }
    }
  }
</script>

<style scoped>
</style>
