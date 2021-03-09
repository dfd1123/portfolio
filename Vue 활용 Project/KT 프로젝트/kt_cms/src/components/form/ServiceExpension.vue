<template>
  <div>
    <div
      v-if="inputData.value"
      class="service-form"
      :style="{'height': inputData.height ? inputData.height : 'initial'}"
    >
      <div class="form-group">
        <div class="form-group-list">
          <div class="table-list-data">
            <div
              v-for="item in inputData.value"
              :key="item.atribId"
              class="form-group-cell grid-02"
            >
              <label class="form-title">{{ item.atribNm }}</label>
              <Input
                type="text"
                :id="item.atribId + '_' + status"
                :readonly="inputData.readonly"
                :button="inputData.button"
                v-model="item.value"
                class="form-input"
                style="width:100%;"
                width="100%;"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Input from '@/components/form/input.vue'

  export default {
    components: {
      Input
    },
    data () {
      return {
      }
    },
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
      }
    },
    methods: {
    }
  }
</script>

<style scoped>
</style>
