<template>
  <div id="dg-mypage-default-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <div class="l-con-area">
        <!-- 1) 주문내역 조회 -->
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-15">
            <h2 class="in-subject">
              최근 주문내역 조회
            </h2>
            <router-link
              class="in-more"
              to="/mypage/order/history"
            >
              <span>주문내역 더보기</span>
              <img
                src="/images/btn/btn_more.svg"
                alt="btn"
              >
            </router-link>
          </div>

          <div class="l-contents-group">
            <!-- 2. 주문 내역 있을 때  (최근 1건) -->
            <div
              v-if="orderLists.length > 0"
              class="l-grid-group"
            >
              <div
                v-for="orderList in orderLists"
                :key="'ordeList'+orderList.order_no"
                class="l-grid-list l-col-1"
              >
                <OrderList :order-list="orderList" />
              </div>
            </div>
            <!-- 2. 주문 내역 있을 떄 E -->
            <!-- 1. 주문 내역 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_order.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">최근 주문 내역이 없습니다.</span>
            </div>
            <!-- 1. 주문 내역 없을 때 E -->
          </div>
        </article>
        <!-- 1) 주문내역 조회 E -->

        <!-- 2) 장바구니 -->
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-28">
            <h2 class="in-subject">
              장바구니 조회
            </h2>
            <router-link
              class="in-more"
              to="/cart"
            >
              <span>장바구니 더보기</span>
              <img
                src="/images/btn/btn_more.svg"
                alt="btn"
              >
            </router-link>
          </div>

          <div class="l-contents-group">
            <!-- 2. 장바구니 상품 있을 때 (상품 2개 표시) -->
            <div
              v-if="cartLists.length > 0"
              class="l-grid-group"
            >
              <div
                v-for="cartList in cartLists"
                :key="'cartList'+cartList.id"
                class="l-grid-list l-col-1"
              >
                <CartWideItemList
                  :item-list="cartList"
                  :show-check-box="false"
                  :btn-list-show="false"
                  :show-coupon="false"
                  :show-delete-btn="false"
                  :show-option-edit-btn="false"
                  :show-request-msg="false"
                />
              </div>
            </div>
            <!-- 2. 장바구니 상품 있을 때 (상품 2개 표시) E -->
            <!-- 1. 장바구니 상품 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_cart.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">장바구니 상품이 없습니다.</span>
            </div>
            <!-- 1. 장바구니 상품 없을 때 E -->
          </div>
        </article>
        <!-- 2) 장바구니 E -->

        <!-- 3) 내가 찜한 상품 -->
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-30">
            <h2 class="in-subject">
              내가 찜한 상품
            </h2>
            <router-link
              class="in-more"
              to="/mypage/like/list"
            >
              <span>찜한 상품 더보기</span>
              <img
                src="/images/btn/btn_more.svg"
                alt="btn"
              >
            </router-link>
          </div>

          <div class="l-contents-group">
            <!-- 2. 찜한 상품 있을 때 -->
            <ul
              v-if="wishItems.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="wishItem in wishItems"
                :key="'recentViewItem'+wishItem.item_id"
                class="l-grid-list l-col-4"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="wishItem"
                  kind="like"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <!-- 2. 찜한 상품 있을 때 E -->
            <!-- 1. 찜한 상품 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_like.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">찜한 상품이 없습니다.</span>
            </div>
            <!-- 1. 찜한 상품 없을 때 E -->
          </div>
        </article>
        <!-- 3) 내가 찜한 상품 E -->

        <!-- 4) 최근 본 상품 -->
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-30">
            <h2 class="in-subject">
              최근 본 상품 조회
            </h2>
            <router-link
              class="in-more"
              to="/mypage/recent/list"
            >
              <span>최근 본 상품 더보기</span>
              <img
                src="/images/btn/btn_more.svg"
                alt="btn"
              >
            </router-link>
          </div>

          <div class="l-contents-group">
            <!-- 2. 최근 본 상품 있을 때 -->
            <ul
              v-if="recentViewItems.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="recentViewItem in recentViewItems"
                :key="'recentViewItem'+recentViewItem.item_id"
                class="l-grid-list l-col-4"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="recentViewItem"
                  kind="recent"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <!-- 2. 최근 본 상품 있을 때 E -->
            <!-- 1. 최근 본 상품 없을 때 -->
            <div
              v-else
              class="nothing-history"
            >
              <img
                src="/images/icon/empty_recent.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">최근 본 상품이 없습니다.</span>
            </div>
            <!-- 1. 최근 본 상품 없을 때 E -->
          </div>
        </article>
        <!-- 4) 최근 본 상품 E -->
      </div>
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import OrderList from '@/components/mypage/order-list.vue'
  import CartWideItemList from '@/components/mypage/cart-wide-item-list.vue'
  import ItemThumb from '@/components/thumbnail/item-thumb.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
      OrderList,
      CartWideItemList,
      ItemThumb
    },
    data: function () {
      return {
        orderBy: 'new',
        orderLists: [],
        cartLists: [],
        wishItems: [],
        recentViewItems: []
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.FetchData()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async FetchData () {
        const [res1, res2, res3] = (await Promise.all([
          this.$http.get(this.$APIURI + 'order/mypage_main_list', { params: { limit: 5 } }),
          this.$http.get(this.$APIURI + 'cart/cart_list', { params: { limit: 5 } }),
          this.$http.get(this.$APIURI + 'wish/list', { params: { limit: 8 } })
        ])).map(res => res.data)

        this.orderLists = res1.query
        this.cartLists = res2.query
        this.wishItems = res3.query.wishes
        if (localStorage.getItem('items')) {
          const recentViewItems = JSON.parse(localStorage.getItem('items'))
          this.recentViewItems = recentViewItems.slice(0, 8)
        } else {
          this.recentViewItems = []
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
