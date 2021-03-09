<template>
  <div>
    <!--
    <h3 class="con-title">
      서비스 정보(선택)
      <button
        type="button"
        class="btn-01 type-03 squre-round btn-service-add"
        @click="AddService"
        :style="{display: !useAdd ? 'none' : ''}"
      >
        +
      </button>
    </h3>
    -->
    <div
      v-if="services.length > 0"
      class="service-form"
      :style="{'height': inputData.height ? inputData.height : 'initial'}"
    >
      <div
        v-for="(service, index) in services"
        :key="index"
        class="form-group"
      >
        <div class="form-group-list">
          <div
            v-for="item in template"
            :key="item.name"
            class="table-list-data"
          >
            <div
              class="form-group-cell"
              :class="item.wide ? 'grid-01' : 'grid-02'"
            >
              <label class="form-title">{{ item.label }}</label>
              <Input
                v-if="['text', 'number', 'password'].includes(item.type)"
                :id="item.name + '_' + status"
                :type="item.type"
                :name="item.name"
                :readonly="status === 'create' ? false :item.readonly"
                v-model="service[item.name]"
                class="form-input"
                style="width:100%;"
                width="100%;"
              />
              <Select
                v-else-if="item.type === 'select'"
                :id="item.name + '_' + status"
                :options="item.options"
                :readonly="status === 'create' ? false : item.readonly"
                v-model="service[item.name]"
                class="form-input"
              />
              <ServiceInfoUnitService
                v-else-if="item.type === 'unitservice'"
                :id="item.name + '_' + status"
                :index="index"
                :value="service[item.name]"
                :readonly="status === 'create' ? false : item.readonly"
                :status="status"
                v-model="service[item.name]"
                class="form-input"
              />
              <template v-else-if="item.type === 'empty'">
                <label
                  class="form-title"
                  style="visibility: hidden"
                >empty</label>
                <div
                  class="form-input"
                  style="width:100%;"
                >
                  <input
                    type="text"
                    style="visibility:hidden"
                  >
                </div>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      v-else
      class="service-empty"
    >
      등록된 게시물이 없습니다
    </div>
  </div>
</template>

<script>
  import Input from '@/components/form/input.vue'
  import Select from '@/components/form/select.vue'
  import ServiceInfoUnitService from '@/components/form/ServiceInfoUnitService.vue'

  export default {
    components: {
      Input,
      Select,
      ServiceInfoUnitService
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
      setting: {
        type: Object,
        required: true,
        default: () => ({})
      },
      status: {
        type: String,
        required: true
      },
      useAdd: {
        type: Boolean,
        default: true
      }
    },
    created () {

    },
    computed: {
      template () {
        return this.setting.template
      },
      services () {
        return this.inputData.value
      }
    },
    methods: {
      AddService () {
        if (this.services.length < 5) {
          this.inputData.value.push({})
        } else {
          alert('최대 추가 갯수는 5개 입니다.')
        }
      }
    }
  }
</script>

<style scoped>
  .btn-service-add {
    float: right;
    margin-top: -9px;
    min-width: initial;
  }

  .form-group {
    padding: 2px;
    padding-top: 10px;
    padding-bottom: 0px;
    border-top: 0px solid #666;
  }

  .form-group-list {
    margin: 0;
  }

  .form-group-cell {
    padding: 6px 16px;
  }

  .form-title {
    margin-bottom: 0px;
  }

  .service-form {
    overflow-y: auto;
  }

  .service-empty {
    text-align: center;
    padding: 18px 20px;
    background: #f8f8f8;
  }
</style>
