<template>
  <div
    id="dg-snb-cart-wrapper"
    class="dg-order-wrapper l-snb-wrapper"
  >
    <!--
      주문하기와 동일한 레이아웃.
      쿠폰 할인적용 팝업, 상품옵션변경 팝업도 존재
    -->
    <!-- 1) 장바구니 단계 확인 구역 -->
    <div class="l-con-area full">
      <div class="l-con-article">
        <div class="type02">
          <div class="_page_title_wrap">
            <h2>
              장바구니
              <a
                href="#"
                class="_back_btn"
                @click.prevent="$router.go(-1)"
              >뒤로가기</a>
            </h2>
          </div>
          <div class="in-breadcrumb-wrap">
            <ul class="in-breadcrumb">
              <li class="_list active">
                <i class="_num">1</i>
                <span>장바구니</span>
                <img
                  src="/images/mobile/icon/icon_breadcrumb_arrow.svg"
                  alt="arrow"
                  class="_next"
                >
              </li>
              <li class="_list">
                <i class="_num">2</i>
                <span>주문/결제<span>
                  <img
                    src="/images/mobile/icon/icon_breadcrumb_arrow.svg"
                    alt="arrow"
                    class="_next"
                  >
                </span></span>
              </li>
              <li class="_list">
                <i class="_num">3</i>
                <span>주문완료</span>
              </li>
            </ul>
          </div>
        </div>
        <!-- 1) 장바구니 단계 확인 구역 E -->

        <div>
          <!-- ※※※※ 장바구니 내역 있을 때 -->
          <!-- 주문상품 리스트 -->
          <div v-if="cartLists.length > 0">
            <ul class="l-grid-group">
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
                      class="word"
                      @click="AllCheckCancel()"
                    >
                      선택해제
                    </button>
                  </li>
                  <li class="_type">
                    <button
                      type="button"
                      class="word _blue"
                      @click="AllCheckDelete()"
                    >
                      선택상품 삭제
                    </button>
                  </li>
                </ul>
                <!-- * 상단 전체선택-선택해제-선택상품 삭제 E -->
              </li>

              <li class="l-grid-list l-col-1">
                <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) -->
                <div class="dg-clothes-history-card type-cart type-detail ">
                  <div class="in-content">
                    <CartList
                      v-for="cartList in cartLists"
                      :key="'cart'+cartList.id"
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
                  </div>
                </div>
                <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) E -->
              </li>

              <li class="l-grid-list l-col-1">
                <!-- * 최종 가격 안내 -->
                <div class="dg-final-price-label">
                  <span class="_delivry_price">배송비 <b class="_point_size">{{ NumberFormat(sendCost) }} 원</b></span>
                </div>
                <!-- * 최종 가격 안내 E -->
              </li>
            </ul>
          </div>
          <div
            v-else
            class="snb-cart-empty"
          >
            <!-- ※※※※ 장바구니 내역 없을 때 -->
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
                <div class="dg-button-wrap dg-button-wrap--single">
                  <a
                    href="#"
                    class="theme-btn-gradient btn-shadow"
                    @click.prevent="$router.push('/product/list/best')"
                  >
                    상품 둘러보기
                  </a>
                </div>
              </li>
            </ul>
            <!-- ※※※※ 장바구니 내역 없을 때 END -->
            <!-- 2) ※※※※ 장바구니 내역 없을 때 : 최근 본 상품 -->
            <section
              v-if="false"
              class="main-slide-wrap best-product-wrap"
            >
              <h2>최근 본 상품</h2>
              <a
                href="#"
                class="in-more"
              >
                <span>더보기</span>
                <img
                  src="/images/btn/btn_more.svg"
                  alt="btn"
                >
              </a>
              <div class="best-product-scroll">
                <ul class="l-grid-group">
                  <!-- 갯수가 늘어나면 x축으로 스크롤 -->
                  <li
                    v-for="(recentList, index) in recentLists"
                    :key="'newLists'+index"
                    class="l-grid-list"
                  >
                    <!-- * 옷상품 카드 -->
                    <ItemThumb
                      :item-list="recentList"
                      kind="like"
                      @popup-open="HashTagPopupOpen"
                    />
                    <!-- * 옷상품 카드 E -->
                  </li>
                </ul>
              </div>
            </section>
            <!-- 2) ※※※※ 장바구니 내역 없을 때 : 최근 본 상품 E -->
          </div>
          <!-- 2) 예상 결제금액 -->
          <div class="l-con-area full">
            <PredictionPrice
              :ready-item-term="readyItemTerm"
              :item-lists="cartLists"
              :select-carts="selectCarts"
              :send-cost="sendCost"
              @ready-term="readyItemTerm = $event"
            />

            <div class="dg-button-wrap dg-button-wrap--double dg-order-button-wrap clear_both">
              <button
                type="button"
                class="theme-btn-gradient--dark _order_btn"
                @click="AllSubmit"
              >
                전체상품 주문
              </button>
              <button
                type="button"
                class="theme-btn-gradient _order_btn"
                @click="Submit"
              >
                선택상품 주문
              </button>
            </div>
          </div>
          <!-- 2) 예상 결제금액 E -->
          <!-- ※※※※ 장바구니 내역 있을 때 END -->
        </div>
      </div>
    </div>
    <OptionChangePopup
      :class="{'posFixed':popupShow}"
      :item="item"
      :option-list="options"
      @popup-close="PopupClose"
      @option-change="OptionChange"
    />
  </div>
</template>

<script>
  import CartList from '@/components/cart/cart-list.vue'
  import PredictionPrice from '@/components/cart/prediction-price.vue'
  import OptionChangePopup from '@/components/popup/option-change.vue'

  export default {
    components: {
      CartList,
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
        document.body.style.overflowY = 'hidden'

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
        document.body.style.overflowY = 'auto'

        this.item = []

        this.popupShow = false
      },
      async OptionChange (selectOption, cartId) {
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
      },
      AllSubmit () {
        const selectItems = []
        this.cartLists.forEach(item => {
          this.cartLists.map(x => {
            if (x.id === item.id) {
              selectItems.push(x)
            }
          })
        })

        this.$store.commit('SelectItemsStore', selectItems)
        this.$router.push('/order')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
