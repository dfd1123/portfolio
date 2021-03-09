<template>
  <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) -->
  <div class="dg-clothes-history-card type-cart type-detail">
    <div class="in-content">
      <div class="inner-box">
        <!-- ※ 인풋체크박스, 삭제버튼 추가 -->
        <input
          type="checkbox"
          :id="'carList'+itemList.id"
          :value="itemList.id"
          v-if="showCheckBox"
          v-model="choiceCarts"
          @change="$emit('select-cart', choiceCarts)"
          class="dg-input-checkbox display_none"
        >
        <label
          :for="'carList'+itemList.id"
          v-if="showCheckBox"
          class="dg-input-checkbox_label"
        ></label>
        <button
          class="_del_btn"
          type="button"
          v-if="showDeleteBtn"
          @click="$emit('delete-cart-list', itemList.id)"
        >
          <img
            src="/images/btn/btn_cancle_gr.svg"
            alt="button image"
          >
        </button>
        <!-- ※ 인풋체크박스, 삭제버튼 추가 END -->

        <figure class="dg-clothes-thumbnail">
          <router-link
            v-if="ConvertImage(itemList.images).length > 0"
            class="in-image"
            :to="'/product/view/' + itemList.item_id"
            :style="'background-image: url('+ storageUrl + ConvertImage(itemList.images)[0]+');'"
          />
          <router-link
            v-else
            class="in-image"
            :to="'/product/view/' + itemList.item_id"
            style="background-image: url('/images/img/thumbnail.png');"
          />
        </figure>
        <ul class="_informations">
          <li class="_sellername">
            <router-link :to="'/store/' + itemList.store_id">
              {{ itemList.company_name || '-' }}
            </router-link>
          </li>
          <li class="_prdtnum">
            <router-link :to="'/product/view/' + itemList.item_id">
              {{ itemList.item_id }}
            </router-link>
          </li>
        </ul>
        <h3 class="_name">
          <router-link :to="'/product/view/' + itemList.item_id">
            {{ itemList.item_name }}
          </router-link>
        </h3>
        <div class="_options">
          <div :class="{'_in_col':showCoupon}">
            <dl class="_op_category">
              <dt class="_op_tit">
                <span class="_tit_span">옵션</span>
                <button
                  v-if="showOptionEditBtn"
                  class="rounded-btn-outline _edit_btn"
                  @click="$emit('popup-show', itemList.item_id)"
                >
                  선택사항 수정
                </button>
              </dt>
              <dd class="_op_list">
                <span
                  v-for="(option,index) in OptionFormat()"
                  :key="'option'+index"
                >{{ option }}</span>
              </dd>
            </dl>
            <div class="_amount">
              <span>수량 : <b>{{ itemList.qty }}</b>개</span>
            </div>
          </div>
          <div
            v-if="showCoupon"
            class="_in_col"
          >
            <dl class="_cp_status">
              <dt class="_cp_tit">
                <span class="_tit_span">적용쿠폰</span>
                <button
                  type="button"
                  class="rounded-btn-outline _edit_btn"
                  @click="$emit('popup-show', itemList.item_id)"
                >
                  쿠폰적용
                </button>
              </dt>
              <dd class="_cp_list">
                <!-- ※ 쿠폰이 있을때 / 없을 때 -->
                <span v-if="couponId">{{ couponSubject }}</span>
                <span
                  v-else
                  class="nothing"
                >적용된 쿠폰이 없습니다.</span>
                <!-- ※ 쿠폰이 있을때 / 없을 때 END -->
              </dd>
            </dl>
          </div>
        </div>
        <div class="_price mb-0">
          <span class="_prdt_price">상품금액 <b>{{ NumberFormat((itemList.price + itemList.option_price) * itemList.qty) }} 원</b></span>
          <span class="_sale_price">할인금액 <b>- {{ NumberFormat((couponPrice)) }} 원</b></span>
          <span class="_total_price">총 상품금액 <b>{{ NumberFormat(((itemList.price + itemList.option_price) * itemList.qty) - (itemList.coupon_price || 0)) }} 원</b></span>
        </div>
        <!-- ※ 주문요청사항 -->
        <div
          v-if="showRequestMsg"
          class="_ask_textarea"
        >
          <textarea
            v-model="requestMsg"
            @input="$emit('inputHandler',requestMsg)"
            maxlength="500"
            placeholder="주문 요청사항을 입력해주세요."
          ></textarea>
          <span>{{ RequestMsgLength }} / 500</span>
        </div>
        <!-- ※ 주문요청사항 END -->
      </div>
    </div>
  </div>
  <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) E -->
</template>

<script>
  export default {
    data: function () {
      return {
        item: {},
        selectCart: false,
        choiceCarts: this.selectCarts,
        requestMsg: ''
      }
    },
    props: {
      showCheckBox: {
        type: Boolean,
        default: false
      },
      showDeleteBtn: {
        type: Boolean,
        default: true
      },
      showOptionEditBtn: {
        type: Boolean,
        default: true
      },
      btnListShow: {
        type: Boolean,
        default: false
      },
      showCoupon: {
        type: Boolean,
        default: true
      },
      showRequestMsg: {
        type: Boolean,
        default: true
      },
      itemList: {
        type: Object,
        required: true
      },
      selectCarts: {
        type: Array,
        default: () => { return [] }
      },
      couponId: {
        type: String,
        default: null
      },
      couponSubject: {
        type: String,
        default: null
      },
      couponPrice: {
        type: Number,
        default: 0
      }
    },
    computed: {
      RequestMsgLength () {
        return this.requestMsg.length
      }
    },
    watch: {
      selectCarts () {
        this.choiceCarts = this.selectCarts
      }
    },
    methods: {
      OptionFormat () {
        if (this.itemList.option) {
          const options = this.itemList.option.split(',')
          const optionSubject = this.itemList.option_subject.split(',')
          const productOption = []

          for (let i = 0; i < optionSubject.length; i++) {
            productOption.push(optionSubject[i] + ' : ' + options[i])
          }

          productOption.push('추가비용 : +' + this.itemList.option_price + '원')

          return productOption
        }
      },
      CheckCancel () {
        this.selectCart = false
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
