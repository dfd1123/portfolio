<template>
  <div class="_option_choice">
    <div class="product-buy_option_btn">
      <!-- 품절상태 -->
      <div
        v-if="(item.soldout_yn || 0) === 1 || (item.sell_yn || 1) !== 1 || (item.delete_yn || 0) === 1"
        class="_soldout dg-btn_line"
      >
        품절
      </div>
      <!-- 품절상태 E -->
      <div v-else>
        <input
          type="checkbox"
          id="buy"
          class="product-buy_option display_none"
          v-model="buyPopup"
        >
        <label
          for="buy"
          class="dg-btn_gra _sale"
        >구매하기</label>
        <!-- 품절상태에서 보이지 않기 -->
        <div class="product-buy_option_wrapper display_none">
          <!-- 누르면 옵션선택창 닫힘 -->
          <div
            class="product-buy_bg"
            @click="buyPopup = false"
          ></div>
          <div class="product-buy_option_wrap">
            <!-- 옵션선택 -->
            <div class="over-y_scroll">
              <div class="choice_product">
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
              <div class="choice_option_wrap">
                <SelectOptionList
                  v-for="(option, index) in $store.state.selectOption"
                  :key="'selectOption'+index"
                  :option="option"
                  :option-subject="optionSubject"
                  :item-list="itemList"
                  :index="Number(index)"
                  :price="option.qty*(option.price + itemList.price)"
                  :qty="option.qty"
                  @input="ChangeQty(index, $event)"
                  @delete-option="DeleteOption(index)"
                  @total-price="TotalPrice"
                />
              </div>
            </div>
            <!-- 품절상태에서 보이지 않기 E -->

            <div class="_quick_menu_bot">
              <div class="_total clear_both">
                <span class="_number">총 {{ NumberFormat(selectOption.length) }}개</span><span class="_price">{{ NumberFormat($store.state.totalPrice) }} 원</span>
              </div>
              <!-- 선택한 옵션보기 E -->
              <div class="dg-end_btn_wrap clear_both">
                <button
                  class="dg-btn_line dg-dubble_btn"
                  @click="$emit('submit', 'cart')"
                >
                  장바구니 담기
                </button>
                <button
                  type="button"
                  v-if="item.soldout_yn === 0"
                  class="dg-btn_gra dg-dubble_btn"
                  @click="$emit('submit', 'direct')"
                >
                  구매하기
                </button>
              </div>
            </div>
          </div>
          <!-- buy option wrap E -->
        </div>
      </div>
    </div>
    <!-- 옵션선택 구매하기 E -->
  </div>
</template>

<script>
  import OptionSelect from '@/components/product-view/option-select.vue'
  import SelectOptionList from '@/components/product-view/option-select-list.vue'

  export default {
    components: {
      OptionSelect,
      SelectOptionList
    },
    data: function () {
      return {
        itemList: this.item,
        selectOption: this.$store.state.selectOption,
        selectArr: [],
        totalPrice: this.$store.state.totalPrice,
        buyPopup: false
      }
    },
    props: {
      item: {
        type: Object,
        required: true
      },
      options: {
        type: Array,
        required: true
      },
      optionSubject: {
        type: Array,
        required: true
      },
      optionsName: {
        type: Array,
        required: true
      }
    },
    updated () {
      this.itemList = this.item
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

        this.options.forEach(option => {
          const optionArr = option.name.split(',')
          if (optionsName.indexOf(optionArr[0]) === -1) {
            optionsName.push(optionArr[0])
          }
        })

        this.$set(this.optionsName, 0, optionsName)
      },
      OptionChange (index, value) {
        this.selectArr.splice(index, 1, value)
        let optionArr = []
        let optionsName = []

        if (index !== 0) {
          const temp = this.selectArr.join(',')

          this.options.forEach(option => {
            if (option.name.indexOf(temp) !== -1) {
              optionArr.push(option)
            }
          })
        } else {
          this.options.forEach(option => {
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
            this.$set(this.optionsName, index + 1, optionsName)
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
            this.$set(this.optionsName, index + 1, optionsName)
          }
        } else {
          const otName = this.selectArr.join(',')
          this.options.forEach(option => {
            if (option.name + '( +' + option.price + '원 )' === otName) {
              if (this.selectOption.indexOf(option) === -1) {
                option.stock_qty--
                option.qty = 1
                // this.selectOption = []
                this.selectOption.push(option)
              }
            }
          })

          this.FirstOptionSet()

          this.$set(this, 'selectArr', [])

          this.$store.commit('SelectOptionStore', this.selectOption)
          this.TotalPrice()
        }
      },
      ChangeQty (index, value) {
        this.selectOption[index] = value
        this.$store.commit('SelectOptionStore', { ...this.selectOption })

        this.TotalPrice()
      },
      DeleteOption (index) {
        this.selectOption.splice(index, 1)
        this.$store.commit('SelectOptionStore', this.selectOption)

        this.TotalPrice()
      },
      TotalPrice () {
        let price = 0
        if (this.selectOption.length > 0) {
          this.selectOption.forEach(option => {
            price = price + option.qty * (this.item.price + option.price)
          })
        }
        this.totalPrice = Number(price)

        this.$store.commit('TotalPriceChange', this.totalPrice)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
