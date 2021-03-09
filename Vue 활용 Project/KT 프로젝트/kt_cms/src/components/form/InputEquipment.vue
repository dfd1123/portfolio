<template>
  <div class="form-input">
    <div class="full-input-btns">
      <input
        type="text"
        :value="equipmentInfo.facNm || ''"
        required
        readonly="readonly"
      >
      <button
        type="button"
        class="btn-01 type-04 squre-round"
        @click="layerPopOpen('equipment-search');"
      >
        <span>설비조회</span>
      </button>
    </div>
    <SearchEquipment
      @equipment-set="EquipmentSet"
      @equipment-found="EquipmentFound"
      :id="status + 'equipment-search'"
      :status="status"
      :fac-id="inputData.value"
      :svc-id="inputData.svcId"
      :style="{visibility: readonly ? 'hidden' : 'visible'}"
      :pre-loading="inputData.preLoading"
    />
  </div>
</template>

<script>
  import SearchEquipment from '@/components/popup/SearchEquipment.vue'

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
      SearchEquipment
    },
    watch: {
      'inputData.value' (newVal) {
        if (!newVal) {
          this.equipmentInfo.facId = null
        }
      },
      'inputData.svcId' (newVal) {
        if (!newVal) {
          this.equipmentInfo.svcId = null
        }
      }
    },
    data () {
      return {
        equipmentInfo: {}
      }
    },
    methods: {
      layerPopOpen (_id) {
        window.$('.' + _id).stop(true).fadeIn(350)
      },
      layerPopClose (_obj) {
        window.$(_obj).parents('.layer-pop-parents').stop(true).fadeOut(350)
      },
      EquipmentSet (equipmentInfo) {
        this.equipmentInfo = equipmentInfo || {}
        this.$emit('input', this.equipmentInfo.facId || null)
      },
      EquipmentFound (equipmentInfo) {
        this.equipmentInfo = equipmentInfo || {}
      }
    }
  }
</script>

<style scoped>
</style>
