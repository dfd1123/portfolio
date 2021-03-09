<template>
  <div class="form-input">
    <div class="full-input-btns">
      <input
        type="text"
        disabled="disabled"
        readonly="readonly"
        :value="info.applNm || ''"
      >
      <button
        type="button"
        class="btn-01 type-04 squre-round"
        @click.stop="layerPopOpen('app-search');"
      >
        <span>찾기</span>
      </button>
    </div>
    <SearchApp
      @value-set="valueSet"
      @value-found="valueFound"
      :status="status"
      :align="align"
      :appl-seq="inputData.value"
      :style="{visibility: readonly ? 'hidden' : 'visible'}"
      :pre-loading="inputData.preLoading"
    />
  </div>
</template>

<script>
  import SearchApp from '@/components/popup/SearchApp.vue'

  export default {
    props: {
      inputData: {
        type: Object,
        default () {
          return {}
        },
        required: true
      },
      status: {
        type: String,
        required: true
      },
      readonly: {
        type: Boolean,
        default: false
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
      SearchApp
    },
    watch: {
      'inputData.value' (newVal) {
        if (String(this.info.applSeq) !== String(newVal)) {
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
        if (this.readonly) {
          return
        }

        window.$('.' + _id).stop(true).fadeIn(350)
      },
      valueSet (info) {
        this.info = info
        this.$emit('input', String(this.info.applSeq))
      },
      valueFound (info) {
        this.info = info
      }
    }
  }
</script>

<style scoped>
</style>
