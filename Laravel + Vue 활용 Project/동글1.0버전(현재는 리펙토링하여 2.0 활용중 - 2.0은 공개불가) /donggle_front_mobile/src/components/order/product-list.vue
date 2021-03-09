<template>
  <div class="inner-wrap">
    <div class="inner-box">
      <h3 class="_name">
        <router-link :to="'/product/view/'+itemList.item_id">
          [{{ itemList.company_name || '-' }}] {{ itemList.item_name }}
        </router-link>
      </h3>
      <div class="_options">
        <figure class="dg-clothes-thumbnail">
          <router-link
            v-if="ConvertImage(itemList.images).length > 0"
            class="in-image"
            :to="'/product/view/'+itemList.item_id"
            :style="'background-image: url('+storageUrl+ConvertImage(itemList.images)[0]+');'"
          />
          <router-link
            v-else
            class="in-image"
            :to="'/product/view/'+itemList.item_id"
            style="background-image: url('/images/img/thumbnail.png');"
          />
        </figure>
        <div class="_in_col">
          <dl class="_op_category">
            <dd class="_op_list">
              <span
                v-for="(option,index) in OptionFormat()"
                :key="'option'+index"
              >{{ option }}</span>
            </dd>
          </dl>
          <div class="_amount">
            <span>수량 : {{ itemList.qty }}개</span>
          </div>
          <div class="_price mb-0">
            <span class="_prdt_price">{{ NumberFormat((itemList.price + itemList.option_price) * itemList.qty) }} 원</span>
            <!-- 쿠폰할인 적용되었을때 배지 -->
            <span
              v-if="itemList.cp_id"
              class="_sale_badge"
            >쿠폰할인</span>
          </div>
        </div>
      </div>
      <div class="_price_wrap mb-0">
        <div class="_sale_price">
          <span>쿠폰 할인</span> <b>- {{ NumberFormat((itemList.cp_price || 0) + itemList.level_discount) }} 원</b>
        </div>
        <div class="_total_price">
          <span>총 상품금액</span> <b>{{ NumberFormat(((itemList.price + itemList.option_price) * itemList.qty) - (itemList.cp_price || 0)) }} 원</b>
        </div>
      </div>
      <div
        v-if="showCoupon"
        class="_edit_btn_wrap"
      >
        <button
          type="button"
          class="rounded-btn-outline _edit_btn _cu_btn"
          @click="$emit('popup-show', itemList.item_id)"
        >
          쿠폰적용
        </button>
      </div>
      <!-- ※ 주문요청사항 -->

      <input
        :id="'order_request'+itemList.id"
        type="checkbox"
        class="order_request display_none"
      >
      <label
        :for="'order_request'+itemList.id"
        class="order_request_label"
      >주문 요청사항 입력</label>
      <div class="_ask_textarea">
        <textarea
          v-model="requestMsg"
          @input="$emit('inputHandler',requestMsg)"
          placeholder="주문 요청사항을 입력해주세요."
          maxlength="500"
        ></textarea>
        <span>{{ RequestMsgLength }}/500</span>
      </div>
      <!-- ※ 주문요청사항 END -->
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
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

          productOption.push('추가비용: +' + this.itemList.option_price + '원')

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
.dg-clothes-history-card .in-content .order_request_label{
  background-size: 8px;
  background-position: 105px;
}
</style>
