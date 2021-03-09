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
        <router-link
          to="/mypage/mycoupon"
          class="in-more"
        >
          <span>쿠폰함</span>
          <img
            src="/images/btn/btn_more.svg"
            alt="btn"
          >
        </router-link>

        <div class="_product_sale_wrap over-y_scroll">
          <div
            v-for="(itemList, index) in itemLists"
            :key="'item'+itemList.id"
            class="_product_sale"
          >
            <div class="_product_view">
              <div class="_img_box">
                <img
                  v-if="ConvertImage(itemList.image).length > 0"
                  :src="storageUrl+ConvertImage(itemList.image)[0]"
                  :alt="itemList.item_name"
                >
                <img
                  v-else
                  src="/images/img/thumbnail.png"
                  alt="동글"
                >
              </div>
              <div class="_text">
                <p class="_title">
                  {{ itemList.item_name }}
                </p>
                <p class="_option">
                  {{ OptionFullName(itemList) }}
                </p>
                <p class="_num">
                  수량 {{ itemList.qty }}개
                </p>
              </div>
            </div>
            <div class="_coupon_select_wrap">
              <h5>쿠폰할인</h5>
              <p class="_price">
                -{{ NumberFormat(itemList.cp_price || 0) }} 원
              </p>
              <select
                class="_coupon_tit"
                @change="SelectOption(index, $event.target.value)"
              >
                <option value="">
                  쿠폰을 선택해주세요
                </option>
                <option
                  v-for="coupon in PossibleOptions(itemList)"
                  :key="'coupon' + coupon.id"
                  :value="coupon.cp_id"
                  :disabled="coupon.cp_use === 1"
                >
                  {{ coupon.cp_subject }}
                </option>
              </select>
            </div>
          </div>
        </div>

        <div class="dg-dubble_btn_wrap clear_both">
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn"
            v-ripple
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
      SelectOption (idx, value) {
        console.log(idx)
        let price = 0
        // let existConfirm = false
        this.couponLists.forEach((couponList, index) => {
          if (this.itemLists[idx].cp_id !== undefined && this.itemLists[idx].cp_id !== null) {
            if (couponList.cp_id === this.itemLists[idx].cp_id) {
              this.couponLists[index].cp_use = 0
              this.couponLists[index].od_id = null
              this.couponLists[index].item_id = null
              this.itemLists[idx].cp_id = null
              this.itemLists[idx].coupon_subject = ''
              this.itemLists[idx].cp_price = 0
            }
          }
        })
        this.couponLists.forEach((couponList, index) => {
          if (couponList.cp_id === value) {
            this.couponLists[index].cp_use = 1
            this.couponLists[index].od_id = this.itemLists[idx].order_id
            this.couponLists[index].item_id = this.itemLists[idx].item_id
            this.itemLists[idx].coupon_subject = couponList.cp_subject
            if (couponList.cp_type === 0) {
              price = couponList.cp_price
            } else {
              price = this.itemLists[idx].price * (couponList.cp_price / 100)
              console.log(price)
              price = price - (price % couponList.cp_trunc)
            }
            this.itemLists[idx].cp_price = price
            this.itemLists[idx].cp_id = couponList.cp_id
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
  ._popup_content {
    padding: 0;
  }

  .in-coupon-box ._coupon_tit{
    padding-top: 0;
    margin-top: 0;
    border-radius: 0;
    font-size: 0.813em;
  }
</style>
