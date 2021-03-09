<template>
  <div>
    <h3 class="con-title">
      {{ txtArea.label }} <span v-if="txtArea.max">({{ txtArea.max }}자 이내)</span>
    </h3>
    <fieldset>
      <legend>{{ txtArea.label }} 입력 폼</legend>
      <TextArea
        :id="txtArea.name"
        v-model="txtArea.value"
        width="100%;"
        :readonly="readonly"
      />
    </fieldset>
  </div>
</template>

<script>
  import TextArea from '@/components/form/textarea.vue'

  export default {
    components: {
      TextArea
    },
    data () {
      return {
        txtArea: {}
      }
    },
    props: {
      textArea: {
        type: Object,
        required: true,
        default () {
          return {}
        }
      },
      status: {
        type: String,
        required: true
      },
      readonly: {
        type: Boolean,
        default: false
      }
    },
    created () {
      this.txtArea = this.textArea
    },
    computed: {
      TextAreaVal () {
        return (status === 'edit') ? this.textArea.value : ''
      }
    }
  }
</script>

<style scoped>
</style>
