<template>
  <article class="l-con-article">
    <div class="l-con-title-group type02">
      <h2 class="in-subject">
        예상 결제금액
      </h2>
    </div>

    <!-- * 전체금액 안내 카드 -->
    <div class="dg-total-price-card">
      <ul class="in-price-infos">
        <li class="_col _col--base">
          <span class="_label">상품금액 합계</span>
          <b class="_prc"><i>{{ OrderPrice }}</i>원</b>
        </li>
        <li class="_col col--delivery">
          <span class="_label">묶음 배송비</span>
          <b class="_prc"><i>{{ SendPrice }}</i>원</b>
        </li>
        <li class="_col col--sale">
          <span class="_label">할인금액</span>
          <b class="_prc"><i>{{ DiscountPrice }}</i>원</b>
        </li>
        <li class="_col _col--total">
          <span class="_label">예상 결제금액</span>
          <b class="_prc"><i>{{ TotalPrice }}</i>원</b>
        </li>
      </ul>
    </div>
    <!-- * 전체금액 안내 카드 E -->
    <div
      v-if="false"
      class="in-guide-article"
    >
      <h5 class="_subject">
        배송준비기간 선택
      </h5>

      <div class="in-days">
        <div class="_days">
          <input
            type="radio"
            id="twoDay"
            value="2"
            v-model="readyTerm"
            @change="$emit('ready-term', readyTerm)"
            class="dg-input-checkbox display_none"
          >
          <label
            for="twoDay"
            class="dg-input-checkbox_label"
          ></label>
          <label for="twoDay">2일</label>
        </div>
        <div class="_days">
          <input
            type="radio"
            id="fiveDay"
            value="5"
            v-model="readyTerm"
            @change="$emit('ready-term', readyTerm)"
            class="dg-input-checkbox display_none"
          >
          <label
            for="fiveDay"
            class="dg-input-checkbox_label"
          ></label>
          <label for="fiveDay">5일</label>
        </div>
        <div class="_days">
          <input
            type="radio"
            id="tenDay"
            value="10"
            v-model="readyTerm"
            @change="$emit('ready-term', readyTerm)"
            class="dg-input-checkbox display_none"
          >
          <label
            for="tenDay"
            class="dg-input-checkbox_label"
          ></label>
          <label for="tenDay">10일</label>
        </div>
      </div>

      <p class="_paragraph">
        동글은 묶음 배송 서비스를 기본으로 제공하여 고객님의
        배송비용을 아껴드립니다.<br>다만 도매의 특성상 재고가 없을
        경우 상품 확보에 시일이 걸릴 수 있습니다.<br>아래에 보이는
        2일/5일/10일로 배송준비기간을 선택해 주시면, 선택한 기간 내에
        출고되지 못한 상품은 자동으로 주문취소 및 환불처리 됩니다.
      </p>
    </div>
  </article>
</template>

<script>
  export default {
    data: function () {
      return {
        readyTerm: this.readyItemTerm,
        selectItemsId: this.selectCarts,
        selectItems: []
      }
    },
    watch: {
      selectCarts () {
        this.selectItems = this.selectCarts
      }
    },
    props: {
      itemLists: {
        type: Array,
        default: () => {
          return []
        }
      },
      selectCarts: {
        type: Array,
        default: () => {
          return []
        }
      },
      readyItemTerm: {
        type: String,
        default: '2'
      },
      sendCost: {
        type: Number,
        default: 0
      }
    },
    computed: {
      OrderPrice () {
        let price = 0
        for (let i = 0; i < this.selectCarts.length; i++) {
          this.itemLists.forEach(item => {
            if (item.id === this.selectCarts[i]) {
              price += ((item.price + item.option_price) * item.qty)
            }
          })
        }

        return this.NumberFormat(price)
      },
      SendPrice () {
        return this.NumberFormat(this.sendCost)
      },
      DiscountPrice () {
        let price = 0
        for (let i = 0; i < this.selectCarts.length; i++) {
          this.itemLists.forEach(item => {
            if (item.id === this.selectCarts[i]) {
              price += ((item.cp_price || 0) + item.level_discount)
            }
          })
        }

        return this.NumberFormat(price)
      },
      TotalPrice () {
        let price = 0
        let discountPrice = 0
        const sendCost = this.sendCost
        for (let i = 0; i < this.selectCarts.length; i++) {
          this.itemLists.forEach(item => {
            if (item.id === this.selectCarts[i]) {
              price += ((item.price + item.option_price) * item.qty)
              discountPrice += ((item.cp_price || 0) + item.level_discount)
            }
          })
        }

        return this.NumberFormat(price + sendCost - discountPrice)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
