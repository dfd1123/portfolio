<template>
  <div
    v-if="status === 'edit' && !vDatas.onlyNew"
    class="form-group-cell"
    :class="(wideComponents.includes(vDatas.type) || vDatas.wide) ? 'grid-01' : 'grid-02'"
  >
    <h3
      v-if="['datagrid', 'formtitle'].includes(vDatas.type)"
      class="side-title small red-border"
      :style="{'margin-bottom': vDatas.type === 'formtitle' ? '0px' : ''}"
    >
      {{ vDatas.label }}
    </h3>
    <label
      v-else-if="!['textarea', 'chatinfo', 'expertdata'].includes(vDatas.type)"
      class="form-title"
      :style="{visibility: vDatas.type === 'empty' ? 'hidden' : 'initial'}"
    >{{ vDatas.type === 'empty' ? 'empty' :vDatas.label }}</label>
    <div class="form-input">
      <Input
        v-if="['text', 'number', 'password'].includes(vDatas.type)"
        :type="vDatas.type"
        :name="vDatas.name"
        :id="vDatas.name + '_' + status"
        :readonly="vDatas.readonly"
        :button="vDatas.button"
        v-model="vDatas.value"
        class="form-input"
        style="width:100%;"
        width="100%;"
        @inputButtonClick="$emit('inputButtonClick', $event)"
      />
      <Select
        v-else-if="vDatas.type === 'select'"
        :input-data="vDatas"
        :id="vDatas.name + '_' + status"
        :readonly="vDatas.readonly"
        @input="vDatas.value = $event"
        :options="vDatas.options"
        :value="vDatas.value"
        class="auto-width-full"
      />
      <Location
        v-else-if="vDatas.type == 'location'"
        :name="vDatas.name"
        :id="vDatas.name + '_' + status"
        :readonly="vDatas.readonly"
        v-model="vDatas.value"
        class="form-input"
        after-char=","
      />
      <Size
        v-else-if="vDatas.type == 'size'"
        :name="vDatas.name"
        :id="vDatas.name + '_' + status"
        :readonly="vDatas.readonly"
        v-model="vDatas.value"
        class="form-input"
        after-char="M"
      />
      <File
        v-else-if="vDatas.type === 'file'"
        :name="vDatas.name"
        :id="vDatas.name + '_' + status"
        :input-data="vDatas"
        :readonly="vDatas.readonly"
        :download="vDatas.download"
        @input="vDatas.value = $event"
        @download="$emit('download', $event)"
      />
      <InputExpert
        v-else-if="vDatas.type === 'expert'"
        :input-data="vDatas"
        :status="status"
        :id="id"
        @input="vDatas.value = $event"
      />
      <InputWorkzone
        v-else-if="vDatas.type === 'zone'"
        :input-data="vDatas"
        :status="status"
        @input="vDatas.value = $event"
      />
      <InputEquipment
        v-else-if="vDatas.type === 'equipment'"
        :input-data="vDatas"
        :status="status"
        @input="vDatas.value = $event"
      />
      <TextAreaInfo
        v-if="vDatas.type === 'textarea'"
        :text-area="vDatas"
        :readonly="vDatas.readonly"
        :status="status"
      />
      <SearchList
        v-else-if="vDatas.type === 'table'"
        :input-data="vDatas"
        :th-datas="vDatas.thDatas"
        :result-lists="vDatas.resultLists"
        @view-open="vDatas.SetView"
      />
      <Imageview
        v-else-if="vDatas.type === 'image'"
        :name="vDatas.name"
        :input-data="vDatas"
        :readonly="vDatas.readonly"
        :download="vDatas.download"
        @input="vDatas.value = $event"
        @download="$emit('download', $event)"
      />
      <Imagebox
        v-else-if="vDatas.type === 'imagebox'"
        :input-data="vDatas"
        :readonly="vDatas.readonly"
        @input="vDatas.value = $event"
      />
      <Datagrid
        v-else-if="vDatas.type === 'datagrid'"
        :status="status"
        :setting="vDatas.setting"
      />
      <InputUser
        v-else-if="vDatas.type === 'user'"
        :input-data="vDatas"
        :status="status"
        :readonly="vDatas.readonly"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <ServiceInfo
        v-else-if="vDatas.type === 'service'"
        :input-data="vDatas"
        :setting="vDatas.setting"
        :status="status"
        :use-add="vDatas.useAdd"
        @input="vDatas.value = $event"
      />
      <InputCustomer
        v-else-if="vDatas.type === 'customer'"
        :input-data="vDatas"
        :status="status"
        :align="vDatas.align"
        :readonly="vDatas.readonly"
        @input="vDatas.value = $event"
      />
      <InputUnitService
        v-else-if="vDatas.type === 'unitservice'"
        :input-data="vDatas"
        :status="status"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <ServiceExpension
        v-else-if="vDatas.type === 'expension'"
        :input-data="vDatas"
        :status="status"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <FormTitle
        v-else-if="vDatas.type === 'formtitle'"
        :input-data="vDatas"
        :status="status"
        :label-name="vDatas.labelName"
      />
      <Datetime
        v-else-if="vDatas.type === 'datetime'"
        :input-data="vDatas"
        :status="status"
        :min-date="vDatas.minDate"
        :max-date="vDatas.maxDate"
        @input="vDatas.value = $event"
      />
      <InputWorker
        v-else-if="vDatas.type === 'worker'"
        :input-data="vDatas"
        :status="status"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <ChatInfo
        v-else-if="vDatas.type === 'chatinfo'"
        :status="status"
        :setting="vDatas.setting"
      />
      <ExpertDataInfo
        v-else-if="vDatas.type === 'expertdata'"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        :setting="vDatas.setting"
      />
      <InputApp
        v-else-if="vDatas.type === 'app'"
        :readonly="vDatas.readonly"
        :input-data="vDatas"
        :status="status"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <div
        v-else-if="vDatas.type === 'empty'"
        class="form-input"
        style="width:100%;"
      >
        <input
          type="text"
          style="visibility:hidden"
        >
      </div>
    </div>
  </div>
  <div
    v-else-if="status === 'create' && !vDatas.onlyView"
    class="form-group-cell"
    :class="(wideComponents.includes(vDatas.type) || vDatas.wide) ? 'grid-01' : 'grid-02'"
  >
    <h3
      v-if="['datagrid', 'formtitle'].includes(vDatas.type)"
      class="side-title small red-border"
      :style="{'margin-bottom': vDatas.type === 'formtitle' ? '0px' : ''}"
    >
      {{ vDatas.label }}
    </h3>
    <label
      v-else-if="!['textarea', 'chatinfo', 'expertdata'].includes(vDatas.type)"
      class="form-title"
      :style="{visibility: vDatas.type === 'empty' ? 'hidden' : 'initial'}"
    >{{ vDatas.type === 'empty' ? 'empty' :vDatas.label }}</label>
    <div class="form-input">
      <Input
        v-if="['text', 'number', 'password'].includes(vDatas.type)"
        :type="vDatas.type"
        :name="vDatas.name"
        :id="vDatas.name + '_' + status"
        :readonly="vDatas.isCreateReadOnly"
        :button="vDatas.button"
        v-model="vDatas.value"
        class="form-input"
        style="width:100%;"
        width="100%;"
        @inputButtonClick="$emit('inputButtonClick', $event)"
      />
      <Select
        v-else-if="vDatas.type === 'select'"
        :input-data="vDatas"
        :id="vDatas.name + '_' + status"
        :options="vDatas.options"
        :readonly="vDatas.isCreateReadOnly"
        :value="vDatas.value"
        @input="vDatas.value = $event"
        class="auto-width-full"
      />
      <Location
        v-else-if="vDatas.type === 'location'"
        :name="vDatas.name"
        :id="vDatas.name + '_' + status"
        :readonly="vDatas.isCreateReadOnly"
        v-model="vDatas.value"
        class="form-input"
        after-char=","
      />
      <Size
        v-else-if="vDatas.type === 'size'"
        :name="vDatas.name"
        :id="vDatas.name + '_' + status"
        :readonly="vDatas.isCreateReadOnly"
        v-model="vDatas.value"
        class="form-input"
        after-char="M"
      />
      <File
        v-else-if="vDatas.type === 'file'"
        :id="vDatas.name + '_' + status"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        @input="vDatas.value = $event"
      />
      <InputExpert
        v-else-if="vDatas.type === 'expert'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        @input="vDatas.value = $event"
      />
      <InputWorkzone
        v-else-if="vDatas.type === 'zone'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        @input="vDatas.value = $event"
      />
      <InputEquipment
        v-else-if="vDatas.type === 'equipment'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        @input="vDatas.value = $event"
      />
      <TextAreaInfo
        v-if="vDatas.type === 'textarea'"
        :text-area="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
      />
      <SearchList
        v-else-if="vDatas.type === 'table'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :th-datas="vDatas.thDatas"
        :result-lists="vDatas.resultLists"
        @view-open="vDatas.SetView"
      />
      <Imagebox
        v-else-if="vDatas.type === 'imagebox'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        @input="vDatas.value = $event"
      />
      <Datagrid
        v-else-if="vDatas.type === 'datagrid'"
        :status="status"
        :setting="vDatas.setting"
      />
      <InputUser
        v-else-if="vDatas.type === 'user'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <ServiceInfo
        v-else-if="vDatas.type === 'service'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :setting="vDatas.setting"
        :status="status"
        :use-add="vDatas.useAdd"
        @input="vDatas.value = $event"
      />
      <InputCustomer
        v-else-if="vDatas.type === 'customer'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <InputUnitService
        v-else-if="vDatas.type === 'unitservice'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <ServiceExpension
        v-else-if="vDatas.type === 'expension'"
        :input-data="vDatas"
        :status="status"
        :readonly="vDatas.isCreateReadOnly"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <FormTitle
        v-else-if="vDatas.type === 'formtitle'"
        :input-data="vDatas"
        :status="status"
        :label-name="vDatas.labelName"
      />
      <Datetime
        v-else-if="vDatas.type === 'datetime'"
        :input-data="vDatas"
        :status="status"
        :readonly="vDatas.isCreateReadOnly"
        :min-date="vDatas.minDate"
        :max-date="vDatas.maxDate"
        @input="vDatas.value = $event"
      />
      <InputWorker
        v-else-if="vDatas.type === 'worker'"
        :input-data="vDatas"
        :status="status"
        :readonly="vDatas.isCreateReadOnly"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <ChatInfo
        v-else-if="vDatas.type === 'chatinfo'"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        :setting="vDatas.setting"
      />
      <ExpertDataInfo
        v-else-if="vDatas.type === 'expertdata'"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        :setting="vDatas.setting"
      />
      <InputApp
        v-else-if="vDatas.type === 'app'"
        :input-data="vDatas"
        :readonly="vDatas.isCreateReadOnly"
        :status="status"
        :align="vDatas.align"
        @input="vDatas.value = $event"
      />
      <div
        v-else-if="vDatas.type === 'empty'"
        class="form-input"
        style="width:100%;"
      >
        <input
          type="text"
          style="visibility:hidden"
        >
      </div>
    </div>
  </div>
</template>

<script>
  import Input from '@/components/form/input.vue'
  import Location from '@/components/form/location.vue'
  import Size from '@/components/form/size.vue'
  import Select from '@/components/form/select.vue'
  import File from '@/components/form/file.vue'
  import InputExpert from '@/components/form/InputExpert.vue'
  import InputWorkzone from '@/components/form/InputWorkzone.vue'
  import InputEquipment from '@/components/form/InputEquipment.vue'
  import TextAreaInfo from '@/components/form/TextAreaInfo.vue'
  import SearchList from '@/components/search_list/SearchList.vue'
  import Imageview from '@/components/form/imageview.vue'
  import Imagebox from '@/components/form/imagebox.vue'
  import Datagrid from '@/components/form/datagrid.vue'
  import InputUser from '@/components/form/InputUser.vue'
  import ServiceInfo from '@/components/form/ServiceInfo.vue'
  import InputCustomer from '@/components/form/InputCustomer.vue'
  import InputUnitService from '@/components/form/InputUnitService.vue'
  import ServiceExpension from '@/components/form/ServiceExpension.vue'
  import FormTitle from '@/components/form/formTitle.vue'
  import Datetime from '@/components/form/datetime.vue'
  import InputWorker from '@/components/form/InputWorker.vue'
  import ChatInfo from '@/components/form/ChatInfo.vue'
  import ExpertDataInfo from '@/components/form/ExpertDataInfo.vue'
  import InputApp from '@/components/form/InputApp.vue'

  export default {
    name: 'ViewInfor',
    components: {
      Input,
      Location,
      Size,
      Select,
      File,
      InputExpert,
      InputWorkzone,
      InputEquipment,
      TextAreaInfo,
      SearchList,
      Imageview,
      Imagebox,
      Datagrid,
      InputUser,
      ServiceInfo,
      InputCustomer,
      InputUnitService,
      ServiceExpension,
      FormTitle,
      Datetime,
      InputWorker,
      ChatInfo,
      ExpertDataInfo,
      InputApp
    },
    data () {
      return {
        vDatas: [],
        wideComponents: [
          'textarea', 'table', 'image', 'datagrid', 'service', 'expension', 'formtitle', 'chatinfo', 'expertdata'
        ]
      }
    },
    props: {
      viewData: {
        type: Object,
        required: true,
        default: () => {
          return {}
        }
      },
      status: {
        type: String,
        required: true
      },
      id: {
        type: String,
        default: ''
      }
    },
    watch: {
      viewData () {
        this.vDatas = this.viewData
      }
    },
    created () {
      this.vDatas = this.viewData
    },
    computed: {
      DataVal () {
        return (status === 'edit') ? this.viewData.value : ''
      }
    }
  }
</script>

<style scoped>
</style>
