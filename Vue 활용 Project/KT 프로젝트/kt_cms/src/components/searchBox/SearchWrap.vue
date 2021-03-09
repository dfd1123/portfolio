<template>
  <div
    v-on-clickaway="away"
    class="list-search-filter"
    :style="{display: visible ? 'block' : 'none'}"
  >
    <div class="list-search-filter-group">
      <h3 class="search-filter-title">
        목록을 필터링 하세요.
      </h3>
      <fieldset>
        <legend>검색 필터 입력 폼</legend>
        <ul class="grid-layout">
          <li
            v-for="(searchLi, index) in searchLis"
            :key="index"
            :class="searchLi.grid"
          >
            <div
              v-if="searchLi.type === 'text'"
              class="title-input-cell"
            >
              <em>{{ searchLi.label }}</em>
              <Input
                :type="searchLi.type"
                :id="searchLi.name + '_search'"
                v-model="searchLi.value"
                width="100%;"
                @click-event="FetchData"
              />
            </div>

            <div
              v-if="searchLi.type === 'date'"
              class="title-input-cell"
            >
              <em>{{ searchLi.label }}</em>
              <DatePicker
                :id="searchLi.name + '_search'"
                width="100% !important;"
                :searchli="searchLi"
                @from-value="searchLi.from_value = $event"
                @to-value="searchLi.to_value = $event"
              />
            </div>

            <div
              v-if="searchLi.type === 'select'"
              class="title-input-cell"
            >
              <em>{{ searchLi.label }}</em>
              <Select
                :id="searchLi.name + '_search'"
                width="100% !important;"
                :searchli="searchLi"
                :options="searchLi.options"
                :value="searchLi.value"
                @input="searchLi.value = $event"
              />
            </div>

            <div
              v-if="searchLi.type === 'selectinput'"
              class="title-input-cell"
              style="padding-left: 0; white-space: nowrap; width: 76%;"
            >
              <Select
                :id="searchLi.name + '_search'"
                style="width: 55.5%; margin-right: 10px"
                :searchli="searchLi"
                :options="searchLi.selectOptions"
                :value="searchLi.selectValue"
                @input="searchLi.selectValue = $event"
              />
              <Input
                type="text"
                :id="searchLi.name + '_search'"
                style="width: 71.5%;"
                v-model="searchLi.inputValue"
                @click-event="FetchData"
              />
            </div>

            <div
              v-if="searchLi.type === 'datetimes'"
              class="title-input-cell"
            >
              <em>{{ searchLi.label }}</em>
              <div style="white-space: nowrap">
                <Datetime
                  :id="searchLi.name + '_search_from'"
                  :input-data="searchLi"
                  status="from"
                  @input="searchLi.from_value = $event"
                  style="display: inline-block"
                />
                <div class="date-time-bind">
                  ~
                </div>
                <Datetime
                  :id="searchLi.name + '_search_to'"
                  :input-data="searchLi"
                  :hide-button="true"
                  status="to"
                  @input="searchLi.to_value = $event"
                  style="display: inline-block"
                />
              </div>
            </div>
          </li>
        </ul>
      </fieldset>
      <div class="btn-page-wrap">
        <BtagButton
          btn-type="type-02"
          btn-name="검색"
          @click-event="FetchData"
        />
      </div>
    </div>
  </div>
</template>

<script>
  import { mixin as clickaway } from 'vue-clickaway'
  import BtagButton from '@/components/button/BtagButton.vue'
  import Input from '@/components/form/input.vue'
  import DatePicker from '@/components/form/datepicker.vue'
  import Select from '@/components/form/select.vue'
  import Datetime from '@/components/form/datetime.vue'

  export default {
    mixins: [clickaway],
    components: {
      Input,
      DatePicker,
      BtagButton,
      Select,
      Datetime
    },
    data () {
      return {}
    },
    computed: {
      searchLis () {
        return this.searchLists.filter(x => !x.hide)
      }
    },
    props: {
      searchLists: {
        type: Array,
        required: true,
        default: function () {
          return {}
        }
      },
      visible: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      away (e) {
        if (this.visible) {
          this.$emit('click-away')
        }
      },
      FetchData () {
        this.$emit('click-event')
      }
    }
  }
</script>

<style scoped>
  .date-time-bind {
    display: inline-block;
    margin-left: -5px;
    text-align: center;
    width: 42px;
    line-height: 36px;
  }
</style>
