<template>
  <div class="form-input">
    <div class="full-input-btns">
      <input
        type="text"
        :value="userInfo.userId || ''"
        required
        readonly="readonly"
      >
      <button
        type="button"
        class="btn-01 type-04 squre-round"
        @click="layerPopOpen('user-search');"
      >
        <span>사용자 조회</span>
      </button>
    </div>
    <SearchUser
      @user-set="UserSet"
      @user-found="UserFound"
      :id="status + 'user-search'"
      :status="status"
      :align="align"
      :svc-id="inputData.svcId || null"
      :cust-id="inputData.custId || null"
      :only-admin="inputData.onlyAdmin || false"
      :index-value="inputData.value"
      :index-name="inputData.indexName || 'userId'"
      :style="{visibility: readonly ? 'hidden' : 'visible'}"
      :pre-loading="inputData.preLoading"
    />
  </div>
</template>

<script>
  import SearchUser from '@/components/popup/SearchUser.vue'

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
      SearchUser
    },
    watch: {
      'inputData.value' (newVal) {
        if (this.userInfo[this.inputData.indexName || 'userId'] !== newVal) {
          this.userInfo = {}
        }
      }
    },
    data () {
      return {
        userInfo: {}
      }
    },
    methods: {
      layerPopOpen (_id) {
        if (this.readonly) {
          return
        }

        window.$('.' + _id).stop(true).fadeIn(350)
      },
      layerPopClose (_obj) {
        window.$(_obj).parents('.layer-pop-parents').stop(true).fadeOut(350)
      },
      UserSet (userInfo) {
        this.userInfo = userInfo || {}
        this.$emit('input', this.userInfo[this.inputData.indexName || 'userId'] || null)
        if (this.inputData.onChange) {
          this.inputData.onChange(this.userInfo[this.inputData.indexName || 'userId'] || null, { ...this.userInfo })
        }
      },
      UserFound (userInfo) {
        this.userInfo = userInfo || {}
      }
    }
  }
</script>

<style scoped>
</style>
