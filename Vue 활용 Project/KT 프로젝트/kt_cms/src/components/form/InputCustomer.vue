<template>
  <div class="form-input">
    <div class="full-input-btns">
      <input
        type="text"
        :value="info.custNm || ''"
        required
        readonly="readonly"
      >
      <button
        type="button"
        class="btn-01 type-04 squre-round"
        @click.stop="layerPopOpen('customer-search');"
      >
        <span>고객 조회</span>
      </button>
    </div>
    <SearchCustomer
      @value-set="valueSet"
      @value-found="valueFound"
      :id="status + 'customer-search'"
      :status="status"
      :align="align"
      :cust-id="inputData.value"
      :style="{visibility: readonly ? 'hidden' : 'visible'}"
      :pre-loading="inputData.preLoading"
    />
  </div>
</template>

<script>
  import SearchCustomer from '@/components/popup/SearchCustomer.vue'

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
      SearchCustomer
    },
    watch: {
      'inputData.value' (newVal) {
        if (this.info.custId !== newVal) {
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
        this.$emit('input', this.info.custId)
        if (this.inputData.onChange) {
          this.inputData.onChange(this.info.custId, { ...this.info })
        }
      },
      valueFound (info) {
        this.info = info
        if (this.inputData.onChange) {
          this.inputData.onChange(this.info.custId, { ...this.info })
        }
      }
    }
  }
</script>

<style scoped>
</style>
