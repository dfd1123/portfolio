<template>
  <div class="_popup_wrapper _order_popup_wrapper _coupon_sale_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          쿠폰 할인적용
          <button
            type="button"
            class="_close_btn"
            @click="$emit('popup-close')"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <div class="_coupon_more">
          <div class="_text_box">
            <img
              src="/images/icon/icon_exclamation_mark.svg"
              alt="icon"
            >
            사용 가능한 쿠폰만 보여집니다.
          </div>
          <router-link
            to="/mypage/mycoupon"
            class="in-more _with_title"
          >
            <span>쿠폰함</span>
            <img
              src="/images/btn/btn_more.svg"
              alt="btn"
            >
          </router-link>
        </div>
        <div class="_option_title_wrap">
          <h5>쿠폰 적용</h5>
          <div class="_coupon_used_wrapper over-y_scroll">
            <div
              v-for="(itemList, index) in itemLists"
              :key="'item'+itemList.id"
              class="_coupon_used_wrap"
            >
              <div class="choice_option choice_option_title">
                <div class="_name">
                  <span>{{ itemList.item_name }}</span>
                </div>
              </div>
              <div class="_coupon_used">
                <div class="_store_info">
                  <span class="_title">스토어명</span>
                  <span class="_content">{{ itemList.company_name }}</span>
                  <span class="_title _order_number_title">품번</span>
                  <span class="_content _num">{{ itemList.item_id }}</span>
                </div>
                <div class="_store_info _option_info">
                  <span class="_title _title_option">옵션</span>
                  <span class="_content _option_text">
                    {{ OptionFullName(itemList) }}
                  </span>
                  <span class="_content">수량<b> {{ itemList.qty }} </b>개</span>
                </div>
                <div class="_store_info _end_info">
                  <span class="_title _bold">할인쿠폰 선택</span>
                  <div class="choice_product _choice_coupon">
                    <select @change="SelectOption(item[index], $event.target.value)">
                      <option value="">
                        쿠폰을 선택해주세요
                      </option>
                      <option
                        v-for="coupon in PossibleOptions(itemList)"
                        :key="'coupon' + coupon.id"
                        :value="coupon.id"
                        :disabled="coupon.cp_use === 1"
                      >
                        {{ coupon.cp_subject }}
                      </option>
                    </select>
                  </div>
                  <span class="_content _price">쿠폰할인<strong>-{{ NumberFormat(itemList.coupon_price || 0) }} 원</strong></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="dg-dubble_btn_wrap clear_both">
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn"
            @click="$emit('popup-close')"
          >
            닫기
          </button>
          <button
            type="button"
            class="dg-btn_gra dg-dubble_btn"
            @click="Submit"
          >
            적용하기
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        item: this.itemLists
      }
    },
    props: {
      itemLists: {
        type: Array,
        required: true
      },
      couponLists: {
        type: Array,
        required: true
      }
    },
    watch: {
      itemLists () {
        this.item = this.itemLists
      }
    },
    methods: {
      OptionFullName (item) {
        let optionFullName = ''
        const optionName = item.option.split(',')
        const optionSubject = item.option_subject.split(',')
        for (let i = 0; i < optionName.length; i++) {
          optionFullName += optionSubject[i] + ':' + optionName[i] + ', '
          if (i === optionName.length - 1) {
            optionFullName += '추가비용:' + this.NumberFormat(item.option_price) + '원'
          }
        }

        return optionFullName
      },
      PossibleOptions (item) {
        const possibleOptions = []
        this.couponLists.forEach(couponList => {
          if (Number(couponList.cp_target) === item.item_id) {
            possibleOptions.push(couponList)
          }

          if (item.ca_id.length <= couponList.cp_target.length) {
            const caId = item.ca_id.substring(0, couponList.cp_target.length)

            if (couponList.cp_target === caId) {
              if (possibleOptions.indexOf(couponList) === -1) {
                possibleOptions.push(couponList)
              }
            }
          }
        })

        return possibleOptions
      },
      SelectOption (item, value) {
        let price = 0
        // let existConfirm = false
        this.couponLists.forEach((couponList, index) => {
          if (item.coupon_id !== undefined && item.coupon_id !== null) {
            console.log(couponList.id + '?=' + item.coupon_id)
            // console.log(index)
            if (couponList.id === item.coupon_id) {
              this.couponLists[index].cp_use = 0
              this.couponLists[index].od_id = null
              this.couponLists[index].item_id = null
              item.coupon_id = null
              item.coupon_subject = ''
              item.coupon_price = 0
            }
          }
        })
        this.couponLists.forEach((couponList, index) => {
          if (couponList.id === Number(value)) {
            this.couponLists[index].cp_use = 1
            this.couponLists[index].od_id = item.order_id
            this.couponLists[index].item_id = item.item_id
            item.coupon_subject = couponList.cp_subject
            if (couponList.cp_type === 0) {
              price = couponList.cp_price
            } else {
              price = item.price * (couponList.cp_price / 100)
              console.log(price)
              price = price - (price % couponList.cp_trunc)
            }
            item.coupon_price = price
            item.coupon_id = couponList.cp_id
          }
        })
      },
      Submit () {
        this.$emit('submit', this.itemLists, this.couponLists)
        this.$emit('popup-close')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
