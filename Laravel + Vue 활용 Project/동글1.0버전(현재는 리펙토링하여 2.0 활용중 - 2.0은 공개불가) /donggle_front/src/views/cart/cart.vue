<template>
  <div
    id="dg-snb-cart-wrapper"
    class="l-page-wrapper"
  >
    <!-- 1) 장바구니 확인 구역 -->
    <div class="l-con-area full">
      <article class="l-con-article mb-80">
        <div class="l-con-title-group type02 mb-18">
          <h2 class="in-subject">
            <span>장바구니</span>
            <ul class="in-breadcrumb">
              <li class="_list active">
                <i class="_num">1</i>
                <span>장바구니</span>
                <img
                  src="/images/icon/icon_breadcrumb_arrow.svg"
                  alt="arrow"
                  class="_next"
                >
              </li>
              <li class="_list">
                <i class="_num">2</i>
                <span>주문/결제</span>
                <img
                  src="/images/icon/icon_breadcrumb_arrow.svg"
                  alt="arrow"
                  class="_next"
                >
              </li>
              <li class="_list">
                <i class="_num">3</i>
                <span>주문완료</span>
              </li>
            </ul>
          </h2>
        </div>

        <div class="l-contents-group">
          <!-- ※※※※ 장바구니 내역 있을 때 -->
          <ul
            v-if="cartLists.length > 0"
            class="l-grid-group"
          >
            <li class="l-grid-list l-col-1">
              <!-- * 상단 전체선택-선택해제-선택상품 삭제 -->
              <ul class="in-sorting">
                <li class="_type active">
                  <input
                    type="checkbox"
                    id="allCheck"
                    v-model="allCheck"
                    @change="AllCheck()"
                    class="dg-input-checkbox display_none"
                  >
                  <label
                    for="allCheck"
                    class="dg-input-checkbox_label"
                  ></label>
                  <label
                    for="allCheck"
                    class="word"
                  >전체선택</label>
                </li>
                <li class="_type">
                  <button
                    type="button"
                    class="word label_like_btn"
                    @click="AllCheckCancel()"
                  >
                    선택해제
                  </button>
                </li>
                <li class="_type">
                  <button
                    type="button"
                    class="word label_like_btn"
                    @click="AllCheckDelete()"
                  >
                    선택상품 삭제
                  </button>
                </li>
              </ul>
              <!-- * 상단 전체선택-선택해제-선택상품 삭제 E -->
            </li>

            <li
              v-for="cartList in cartLists"
              :key="'cart'+cartList.id"
              class="l-grid-list l-col-1"
            >
              <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) -->
              <CartWideItemList
                :item-list="cartList"
                :show-check-box="true"
                :show-coupon="false"
                :show-request-msg="false"
                :select-carts="selectCarts"
                @select-cart="selectCarts = $event"
                @inputHandler="cartList.request_msg = $event"
                @popup-show="PopupOpen(cartList)"
                @delete-cart-list="DeleteCartList"
              />
              <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) E -->
            </li>

            <li class="l-grid-list l-col-1">
              <!-- * 최종 가격 안내 -->
              <div class="dg-final-price-label">
                <span class="_delivry_price">배송비 <b class="_point_size">{{ NumberFormat(sendCost) }} 원</b></span>
              </div>
              <!-- * 최종 가격 안내 E -->
            </li>

            <li class="l-grid-list l-col-1">
              <ul class="in-sorting">
                <li class="_type active">
                  <input
                    type="checkbox"
                    id="allCheck"
                    v-model="allCheck"
                    @change="AllCheck()"
                    class="dg-input-checkbox display_none"
                  >
                  <label
                    for="allCheck"
                    class="dg-input-checkbox_label"
                  ></label>
                  <label
                    for="allCheck"
                    class="word"
                  >전체선택</label>
                </li>
                <li class="_type">
                  <button
                    type="button"
                    class="word label_like_btn"
                    @click="AllCheckCancel()"
                  >
                    선택해제
                  </button>
                </li>
                <li class="_type">
                  <button
                    type="button"
                    class="word label_like_btn"
                    @click="AllCheckDelete()"
                  >
                    선택상품 삭제
                  </button>
                </li>
              </ul>
              <!-- * 상단 전체선택-선택해제-선택상품 삭제 E -->
            </li>
          </ul>
          <!-- ※※※※ 장바구니 내역 있을 때 END -->
          <!-- ※※※※ 장바구니 내역 없을 때 -->
          <div v-else>
            <ul class="l-grid-group">
              <li class="l-grid-list l-col-1">
                <div class="nothing-history">
                  <img
                    src="/images/icon/empty_cart.svg"
                    alt="icon empty"
                    class="in-empty-icon"
                  >
                  <span class="in-empty-ment">장바구니 상품이 없습니다.</span>
                </div>
              </li>
            </ul>
            <div class="dg-button-wrap dg-button-wrap--single">
              <button
                type="button"
                class="theme-btn-gradient btn-shadow"
                @click="$router.push('/product/list/best')"
              >
                상품 둘러보기
              </button>
            </div>
            <!-- ※※※※ 장바구니 내역 없을 때 END -->
          </div>
        </div>
      </article>
    </div>
    <!-- 1) 장바구니 확인 구역 E -->

    <!-- 2) ※※※※ 장바구니 내역 있을 때 : 예상 결제금액 -->
    <div class="l-con-area full">
      <PredictionPrice
        :ready-item-term="readyItemTerm"
        :item-lists="cartLists"
        :select-carts="selectCarts"
        :send-cost="sendCost"
        @ready-term="readyItemTerm = $event"
      />
      <div class="dg-button-wrap dg-button-wrap--double">
        <button
          type="button"
          class="theme-btn-gradient--dark btn-shadow"
          @click="$router.back()"
        >
          취소
        </button>
        <button
          type="button"
          class="theme-btn-gradient btn-shadow"
          @click="Submit"
        >
          주문하기
        </button>
      </div>
    </div>
    <!-- 2) ※※※※ 장바구니 내역 있을 때 : 예상 결제금액 E -->
    <OptionChangePopup
      v-show="popupShow"
      :item="item"
      :option-list="options"
      @popup-close="PopupClose"
      @option-change="OptionChange"
    />
  </div>
</template>

<script>
  import CartWideItemList from '@/components/mypage/cart-wide-item-list.vue'
  import PredictionPrice from '@/components/mypage/prediction-price.vue'
  import OptionChangePopup from '@/components/popup/option-change.vue'

  export default {
    components: {
      CartWideItemList,
      PredictionPrice,
      OptionChangePopup
    },
    data: function () {
      return {
        cartLists: [],
        item: {},
        options: [],
        allCheck: true,
        selectCarts: [],
        readyItemTerm: '2',
        popupShow: false,
        sendCost: 0
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.CartListLoad()

      if (this.cartLists.length > 0) {
        this.sendCost = (this.cartLists[0].sc_price + this.cartLists[0].send_cost)
      }

      this.cartLists.forEach(cart => {
        this.selectCarts.push(cart.id)
      })
      this.$store.commit('ProgressHide')
    },
    methods: {
      async CartListLoad () {
        try {
          const res = (await this.$http.get(this.$APIURI + 'cart/cart_list')).data
          if (res.state === 1) {
            this.cartLists = res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async CartDelete (id) {
        const params = {
          cart_id: id,
          _method: 'delete'
        }

        const res = (await this.$http.post(this.$APIURI + 'cart/delete', params)).data

        return res
      },
      AllCheck () {
        if (this.allCheck) {
          this.cartLists.forEach(cart => {
            this.selectCarts.push(cart.id)
          })
        } else {
          this.selectCarts = []
        }
      },
      AllCheckCancel () {
        this.selectCarts = []
        this.allCheck = false
      },
      async AllCheckDelete () {
        const result = await this.Confirm('정말 선택하신 상품들을 카트에서 삭제 하시겠습니까?')
        if (result) {
          const del = await this.CartDelete(this.selectCarts)

          if (del.state === 1) {
            await this.CartListLoad()
          } else {
            this.WarningAlert(del.msg)
          }

          this.CartListLoad()

          this.selectCarts = []
          this.allCheck = false
        }
      },
      async DeleteCartList (id) {
        const result = await this.Confirm('정말 해당 상품을 장바구니에서 삭제하시겠습니까?')

        if (result) {
          this.$store.commit('ProgressShow')

          const cartId = []
          cartId.push(id)
          const del = await this.CartDelete(cartId)

          if (del.state === 1) {
            await this.CartListLoad()
          } else {
            this.WarningAlert(del.msg)
          }

          this.$store.commit('ProgressHide')
        }
      },
      async PopupOpen (cartList) {
        this.item = []
        this.item = cartList

        this.popupShow = true

        const params = {
          item_id: cartList.item_id
        }
        const res = (await this.$http.get(this.$APIURI + 'items/view', { params })).data

        if (res.state === 1) {
          this.options = []
          this.options = res.query.item_option
        }

        // this.item = item
        // this.options = options
      },
      PopupClose () {
        this.item = []
        this.popupShow = false
      },
      async OptionChange (selectOption, cartId) {
        console.log(selectOption)
        const params = {
          cart_id: cartId,
          options: JSON.stringify(selectOption),
          _method: 'put'
        }

        try {
          const res = (await this.$http.post(this.$APIURI + 'cart/option', params)).data
          if (res.state === 1) {
            this.CartListLoad()
          } else {
            console.log('옵션 변경 실패! 에러:', res.msg)
          }
        } catch (e) {
          console.log('옵션 변경 실패! 에러:', e)
        } finally {
          this.PopupClose()
        }
      },
      Submit () {
        const selectItems = []
        this.selectCarts.forEach(item => {
          this.cartLists.map(x => {
            if (x.id === item) {
              selectItems.push(x)
            }
          })
        })

        if (selectItems.length <= 0) {
          this.InfoAlert('구매하실 상품을 선택하여 주세요')

          return false
        }
        this.$store.commit('SelectItemsStore', selectItems)
        this.$router.push('/order')
      }
    }
  }
</script>

<style lang="scss" scoped>
  .label_like_btn {
    border: none;
    background: transparent;
    outline: none;
  }
</style>
