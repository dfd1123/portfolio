<template>
  <div class="_popup_wrapper _order_popup_wrapper _change_option_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          상품옵션 변경<button
            type="button"
            class="_close_btn"
            @click="PopupClose"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <div class="_option_title_wrap">
          <div class="_product_view">
            <div class="_img_box">
              <img
                v-if="ConvertImage(item.images).length > 0"
                :src="storageUrl+ConvertImage(item.images)[0]"
                alt="팝콘체크 롱코트"
              >
              <img
                v-else
                src="/images/img/thumbnail.png"
                alt="팝콘체크 롱코트"
              >
            </div>
            <div class="_text">
              <p class="_title">
                {{ item.item_name }}
              </p>
            </div>
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
        </div>
        <!-- 선택한 옵션보기 E -->
        <div class="dg-dubble_btn_wrap clear_both">
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn"
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
        this.itemList = this.item
      },
      optionList () {
        this.options = this.optionList
        this.FirstOptionSet()
      },
      'item.option_subject' () {
        this.optionSubject = this.item.option_subject.split(',')
      }
    },
    methods: {
      FirstOptionSet () {
        this.optionSubject = this.item.option_subject.split(',')
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
                if (option.sold_out === 1) {
                  optionsName.push(tempArr[index + 1] + '( 품절 )')
                } else {
                  optionsName.push(tempArr[index + 1] + '( +' + option.price + '원 )')
                }
              }
            })
            Vue.set(this.optionsName, index + 1, optionsName)
          }
        } else {
          const otName = this.selectArr.join(',')
          this.optionList.forEach(option => {
            if (option.name + '( +' + option.price + '원 )' === otName) {
              option.stock_qty--
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
