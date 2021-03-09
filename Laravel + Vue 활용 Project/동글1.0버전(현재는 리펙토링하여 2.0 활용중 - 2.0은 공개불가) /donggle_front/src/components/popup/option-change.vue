<template>
  <div class="_popup_wrapper _order_popup_wrapper _change_option_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          상품옵션 변경
          <button
            type="button"
            class="_close_btn"
            @click="PopupClose"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <div class="_option_title_wrap">
          <h5>상품옵션 변경</h5>
          <div class="choice_option choice_option_title">
            <div class="_name">
              <span>{{ item.item_name }}</span>
            </div>
          </div>
          <div class="_store_info">
            <span class="_title">스토어명</span>
            <span class="_content">{{ item.company_name || '-' }}</span>
            <span class="_title _order_number_title">품번</span>
            <span class="_content _num">{{ item.item_id }}</span>
          </div>
        </div>
        <div class="choice_product">
          <h4>옵션선택</h4>
          <OptionSelect
            v-for="(optionNm, index) in optionsName"
            :key="'optionNm'+ index"
            :option-nm="optionNm"
            :option-subject="optionSubject"
            :index="index"
            @option-change="OptionChange"
          />
        </div>
        <!-- 옵션선택 E -->
        <!-- 선택한 옵션보기 -->
        <div class="change_option_wrap over-y_scroll">
          <SelectOptionList
            v-for="(option, index) in selectOption"
            :key="'selectOption'+index"
            :option="option"
            :option-subject="optionSubject"
            :item-list="item"
            @input="selectOption[index] = $event"
          />
          <!--
          <div
            v-for="(option, index) in selectOption"
            :key="'selectOption'+index"
            class="choice_option _change_option"
          >
            <div class="_name">
              <span>{{ OptionFullName(option) }}</span>
            </div>
            <div class="_amount clear_both">
              <div
                class="_plusbtn"
                @click="option.qty + 1"
              ></div>
              <input
                type="number"
                class="_number-input"
                maxlength="2"
                placeholder="1"
                value="1"
                v-model="option.qty"
              >
              <div
                class="_minusbtn"
                @click="option.qty - 1"
              ></div>
            </div>
            <span class="_total_price">{{ OptionTotalPrice(option) }}</span>
            <div class="_close_btn"></div>
          </div>
          -->
        </div>
        <!-- 선택한 옵션보기 E -->
        <div class="dg-dubble_btn_wrap clear_both">
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn"
            @click="PopupClose"
          >
            닫기
          </button>
          <button
            type="button"
            class="dg-btn_gra dg-dubble_btn"
            @click="Submit"
          >
            변경하기
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Vue from 'vue'
  import OptionSelect from '@/components/option/select.vue'
  import SelectOptionList from '@/components/option/select-option-list.vue'

  export default {
    components: {
      OptionSelect,
      SelectOptionList
    },
    data: function () {
      return {
        itemList: {},
        optionSubject: [],
        options: [],
        optionsName: [],
        selectOption: [],
        selectArr: []
      }
    },
    props: {
      item: {
        type: Object,
        required: true
      },
      optionList: {
        type: Array,
        required: true
      }
    },
    watch: {
      item () {
        console.log('item Change')
        this.itemList = this.item
        this.optionSubject = this.item.option_subject.split(',')
      },
      optionList () {
        console.log('optionList Change')
        this.options = this.optionList
        this.FirstOptionSet()
      }
    },
    methods: {
      FirstOptionSet () {
        const selectTags = document.getElementsByClassName('select-option')
        const optionsName = []

        for (let i = 0; i < selectTags.length; i++) {
          selectTags[i].selectedIndex = 0
        }

        for (let i = 0; i < this.optionSubject.length; i++) {
          this.$set(this.optionsName, i, [])
        }

        this.optionList.forEach(option => {
          const optionArr = option.name.split(',')
          if (optionsName.indexOf(optionArr[0]) === -1) {
            optionsName.push(optionArr[0])
          }
        })

        Vue.set(this.optionsName, 0, optionsName)
      },
      OptionChange (index, value) {
        this.selectArr.splice(index, 1, value)
        let optionArr = []
        let optionsName = []

        if (index !== 0) {
          const temp = this.selectArr.join(',')

          this.optionList.forEach(option => {
            if (option.name.indexOf(temp) !== -1) {
              optionArr.push(option)
            }
          })
        } else {
          this.optionList.forEach(option => {
            if (option.name.indexOf(this.selectArr + ',') !== -1) {
              let tArr = option.name.split(',')
              tArr.forEach(arr => {
                arr = arr.replace(/\s/g, '')
                if (arr === String(this.selectArr)) {
                  optionArr.push(option)
                }
              })
            }
          })
        }

        if (this.optionSubject.length !== index + 1) {
          if (this.optionSubject.length !== index + 2) {
            optionArr.forEach(option => {
              const tempArr = option.name.split(',')
              if (this.optionsName[index + 1].indexOf(tempArr[index + 1]) === -1) {
                optionsName.push(tempArr[index + 1])
              }
            })
            Vue.set(this.optionsName, index + 1, optionsName)
          } else {
            this.optionsName[index + 1] = []
            optionArr.forEach(option => {
              const tempArr = option.name.split(',')
              if (this.optionsName[index + 1].indexOf(tempArr[index + 1]) === -1) {
                optionsName.push(tempArr[index + 1] + '( +' + option.price + '원 )')
              }
            })
            Vue.set(this.optionsName, index + 1, optionsName)
          }
        } else {
          const otName = this.selectArr.join(',')
          this.optionList.forEach(option => {
            if (option.name + '( +' + option.price + '원 )' === otName) {
              option.ot_qty--
              option.qty = 1
              this.selectOption = []
              this.selectOption.push(option)
            }
          })

          this.FirstOptionSet()

          Vue.set(this, 'selectArr', [])
        }
      },
      PopupClose () {
        this.selectOption = []
        this.selectArr = []
        this.optionSubject = []
        this.$emit('popup-close')
      },
      Submit () {
        this.$emit('option-change', this.selectOption, this.item.id)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
