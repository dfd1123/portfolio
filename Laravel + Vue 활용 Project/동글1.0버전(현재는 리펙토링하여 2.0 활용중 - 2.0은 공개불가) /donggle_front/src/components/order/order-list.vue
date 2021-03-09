<template>
  <div class="dg-clothes-history-card type-detail">
    <div class="in-content">
      <div class="inner-box">
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
            {{ itemList.item_name || '-' }}
          </router-link>
        </h3>
        <div class="_options">
          <div>
            <dl class="_op_category">
              <dt class="_op_tit">
                <span class="_tit_span">옵션</span>
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
        </div>
        <div class="_price">
          <span class="_prdt_price">상품금액 <b>{{ NumberFormat((itemList.item_price + itemList.item_option_price) * itemList.qty ) }} 원</b></span>
          <span class="_sale_price">개별 쿠폰 할인금액 <b>- {{ NumberFormat((itemList.coupon_price || 0)) }} 원</b></span>
          <span class="_total_price">총 상품금액 <b>{{ NumberFormat(itemList.this_price) }} 원</b></span>
        </div>
        <dl
          v-if="itemList.memo"
          class="_etc_ment"
        >
          <dt class="_etc_ment_tit">
            주문 요청사항
          </dt>
          <dd class="_etc_ment_list">
            <span v-html="itemList.memo"></span>
          </dd>
        </dl>
      </div>
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

          productOption.push('추가비용: +' + this.itemList.item_option_price + '원')

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
