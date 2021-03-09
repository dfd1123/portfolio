<template>
  <div
    id="dg-order-process-wrapper"
    class="dg-order-wrapper"
  >
    <!-- 1) 주문상품 확인리스트 구역 -->
    <div class="l-con-area full">
      <article class="l-con-article">
        <div class="type02 not-process-first">
          <div class="_page_title_wrap">
            <h2>
              주문/결제
              <a
                href="#"
                class="_back_btn"
                @click.prevent="$router.go(-1)"
              >뒤로가기</a>
            </h2>
          </div>
          <div class="in-breadcrumb-wrap">
            <ul class="in-breadcrumb">
              <li class="_list">
                <i class="_num">1</i>
                <span>장바구니</span>
                <img
                  src="/images/mobile/icon/icon_breadcrumb_arrow.svg"
                  alt="arrow"
                  class="_next"
                >
              </li>
              <li class="_list active">
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

        <!-- 주문상품 리스트 -->
        <div class="ordered-list-group_wrap">
          <input
            id="test"
            type="checkbox"
            class="order_check display_none"
          >
          <label
            for="test"
            class="order_check_label"
          >주문상품 확인 및 쿠폰적용</label>
          <ul class="l-grid-group ordered-list-group">
            <li class="l-grid-list l-col-1">
              <!-- * 소제목 -->
              <div>
                <span class="_etc_ment">상품수량 및 옵션변경은 상품상세 또는 장바구니에서 가능합니다.</span>
              </div>
              <!-- * 소제목 E -->
            </li>

            <li class="l-grid-list l-col-1">
              <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) -->
              <div class="dg-clothes-history-card type-cart type-detail ">
                <div class="in-content">
                  <ProductList
                    v-for="orderList in orderLists"
                    :key="'order'+orderList.id"
                    :item-list="orderList"
                    :show-check-box="false"
                    :show-delete-btn="false"
                    :show-option-edit-btn="false"
                    :show-coupon="true"
                    :show-request-msg="true"
                    @inputHandler="orderList.request_msg = $event"
                    @popup-show="CouponPopupOpen"
                  />
                </div>
              </div>
              <!-- * 상품내역 카드 (type-cart, type-detail 클래스 추가) E -->
            </li>

            <li class="l-grid-list l-col-1">
              <!-- * 최종 가격 안내 -->
              <div class="dg-final-price-label">
                <span class="_delivry_price">배송비 <b class="_point_size">{{ NumberFormat(form.sendCost) }} 원</b></span>
              </div>
              <!-- * 최종 가격 안내 E -->
            </li>
          </ul>
        </div>
        <!-- 주문상품 리스트 E -->
      </article>
    </div>
    <!-- 1) 주문상품 확인리스트 구역 E -->

    <!-- 2) 주문정보들 -->
    <div class="dg-order-info-group clear_both">
      <form
        name="niceForm"
        method="post"
        :action="$APIURI + 'pay'"
        accept-charset="euc-kr"
      >
        <!-- 주문 정보 -->
        <div class="in-detail-info">
          <!-- [1] 쿠폰 적용 -->
          <article class="l-con-article">
            <h4 class="in-subject">
              쿠폰적용
            </h4>
            <div class="in-coupon-box">
              <select
                @change="AllCouponSubmit($event.target.value)"
                class="_coupon_tit"
              >
                <option value="">
                  [쿠폰]을 선택하세요.
                </option>
                <option
                  v-for="coupon in allCouponLists"
                  :key="'allCoupon'+coupon.id"
                  :value="coupon.cp_id"
                >
                  {{ coupon.cp_subject }}
                </option>
              </select>
            </div>
          </article>
          <!-- [1] E -->

          <!-- [2] 배송준비 기간 -->
          <article class="l-con-article">
            <h4 class="in-subject">
              <span>배송준비 기간</span>
            </h4>
            <div class="in-guide-article">
              <h5 class="_subject display_none">
                배송준비기간 선택
              </h5>

              <div class="in-days">
                <div class="_days">
                  <input
                    type="radio"
                    name="hopeDate"
                    id="hopeTwo"
                    value="2"
                    :disabled="minimumHopdate > 2"
                    v-model="form.hope_date"
                    class="dg-input-checkbox display_none"
                  >
                  <label
                    for="hopeTwo"
                    class="dg-input-checkbox_label"
                  ></label>
                  <label for="hopeTwo">2일</label>
                </div>
                <div class="_days">
                  <input
                    type="radio"
                    name="hopeDate"
                    id="hopeFive"
                    value="5"
                    :disabled="minimumHopdate > 5"
                    v-model="form.hope_date"
                    class="dg-input-checkbox display_none"
                  >
                  <label
                    for="hopeFive"
                    class="dg-input-checkbox_label"
                  ></label>
                  <label for="hopeFive">5일</label>
                </div>
                <div class="_days">
                  <input
                    type="radio"
                    name="hopeDate"
                    id="hopeTen"
                    value="10"
                    :disabled="minimumHopdate >= 10"
                    v-model="form.hope_date"
                    class="dg-input-checkbox display_none"
                  >
                  <label
                    for="hopeTen"
                    class="dg-input-checkbox_label"
                  ></label>
                  <label for="hopeTen">10일</label>
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
            <span class="information_span">주문하시려는 제품 또는 제품들의 총 최소 배송준비 기간이 <b>{{ minimumHopdate }}</b>일 입니다.</span>
          </article>
          <!-- [2] E -->

          <!-- [3] 주문고객 정보 -->
          <article class="l-con-article">
            <h4 class="in-subject">
              <span>주문고객 정보</span>
              <div class="_etc_check">
                <input
                  type="checkbox"
                  id="sameBuyer"
                  class="dg-input-checkbox display_none"
                  v-model="userSameBuyer"
                  @change="UserSameBuyer()"
                >
                <label
                  for="sameBuyer"
                  class="dg-input-checkbox_label"
                ></label>
                <label for="sameBuyer">회원정보와 동일합니다.</label>
              </div>
            </h4>
            <ul class="in-information">
              <li class="_list">
                <span class="_label">이름</span>
                <div class="_input_group">
                  <input
                    type="text"
                    name="s_name"
                    class="_input"
                    placeholder="이름을 입력하세요."
                    :value="form.s_name"
                    :readonly="userSameBuyer"
                  >
                </div>
              </li>
              <li class="_list">
                <span class="_label">전화번호</span>
                <div class="_input_group">
                  <input
                    type="tel"
                    name="s_hp"
                    class="_input"
                    placeholder="전화번호를 입력하세요."
                    :value="form.s_hp"
                    :readonly="userSameBuyer"
                  >
                </div>
              </li>
              <li class="_list">
                <span class="_label">이메일</span>
                <div class="_input_group">
                  <input
                    type="email"
                    name="s_id"
                    class="_input"
                    placeholder="이메일을 입력하세요."
                    :value="form.s_id"
                    :readonly="userSameBuyer"
                  >
                </div>
              </li>
            </ul>
          </article>
          <!-- [3] E -->

          <!-- [4] 배송지 정보 -->
          <article class="l-con-article">
            <h4 class="in-subject">
              <span>배송지 정보</span>
              <div class="_etc_buttons clear_both">
                <button
                  type="button"
                  :class="['square-btn-outline', {'active':form.delivery_index === 1}]"
                  @click="DeliveryIndex(1)"
                >
                  1
                </button>
                <button
                  type="button"
                  :class="['square-btn-outline', {'active':form.delivery_index === 2}]"
                  @click="DeliveryIndex(2)"
                >
                  2
                </button>
                <button
                  type="button"
                  :class="['square-btn-outline', {'active':form.delivery_index === 3}]"
                  @click="DeliveryIndex(3)"
                >
                  3
                </button>
              </div>
            </h4>
            <ul class="in-information">
              <li class="_list">
                <span class="_label">이름</span>
                <div class="_input_group">
                  <input
                    type="text"
                    name="g_name"
                    class="_input"
                    placeholder="이름을 입력하세요."
                    v-model="form.g_name"
                  >
                </div>
              </li>
              <li class="_list">
                <span class="_label">전화번호</span>
                <div class="_input_group">
                  <input
                    type="tel"
                    name="g_hp"
                    class="_input"
                    placeholder="전화번호를 입력하세요."
                    v-model="form.g_hp"
                  >
                </div>
              </li>
              <li class="_list _list--post">
                <span class="_label">우편번호</span>
                <div class="_input_group">
                  <input
                    type="text"
                    name="g_post_num"
                    class=" _input"
                    placeholder="우편번호"
                    v-model="form.g_post_num"
                    :readonly="true"
                  >
                  <button
                    type="button"
                    class="square-btn-outline"
                    @click="daumAdressPopup = true"
                  >
                    주소찾기
                  </button>
                </div>
              </li>
              <li class="_list">
                <span class="_label">기본주소</span>
                <div class="_input_group">
                  <input
                    type="text"
                    name="g_addr1"
                    class="_input"
                    placeholder="기본주소를 입력하세요."
                    v-model="form.g_addr1"
                    :readonly="true"
                  >
                </div>
              </li>
              <li class="_list">
                <span class="_label">상세주소</span>
                <div class="_input_group">
                  <input
                    type="text"
                    name="g_addr2"
                    class="_input"
                    placeholder="상세주소를 입력하세요."
                    v-model="form.g_addr2"
                  >
                </div>
              </li>
              <li class="_list _list--textarea">
                <span class="_label">배송 요청사항</span>
                <div class="_input_group">
                  <textarea
                    name="delivery_memo"
                    class="_textarea"
                    placeholder="배송시 요청사항을 작성해주세요"
                    v-model="form.delivery_memo"
                  ></textarea>
                </div>
              </li>
            </ul>
          </article>
          <!-- [4] E -->

          <!-- [5] 결제수단 -->
          <article class="l-con-article">
            <h4 class="in-subject">
              결제수단
            </h4>
            <div class="in-paytype-buttons">
              <button
                type="button"
                :class="{'active': pg === 'paypleCert'}"
                @click="pg='paypleCert'"
              >
                <img
                  src="/images/icon/icon_account-on.svg"
                  alt="card icon"
                  class="_icon _icon--on"
                >
                <img
                  src="/images/icon/icon_account-off.svg"
                  alt="card icon"
                  class="_icon _icon--off"
                >
                <span class="_word">PAYPLE<br>간편 계좌 결제</span>
              </button>
              <button
                type="button"
                :class="{'active': pg === 'paypleCard'}"
                @click="pg='paypleCard'"
              >
                <img
                  src="/images/icon/icon_card-on.svg"
                  alt="account icon"
                  class="_icon _icon--on"
                >
                <img
                  src="/images/icon/icon_card-off.svg"
                  alt="account icon"
                  class="_icon _icon--off"
                >
                <span class="_word">PAYPLE<br>간편 카드 결제</span>
              </button>
              <button
                type="button"
                :class="{'active': pg === 'nice'}"
                @click="pg='nice'"
              >
                <img
                  src="/images/icon/icon_bank-on.svg"
                  alt="bank icon"
                  class="_icon _icon--on"
                >
                <img
                  src="/images/icon/icon_bank-off.svg"
                  alt="bank icon"
                  class="_icon _icon--off"
                >
                <span class="_word">NICEPAY 결제</span>
              </button>
            </div>
          </article>
          <!-- [5] E -->
        </div>
        <!-- 주문 정보 E -->
        <input
          type="hidden"
          name="PayMethod"
          value=""
        >
        <input
          type="hidden"
          name="GoodsName"
          value="(주)동글 의류 구매"
        >
        <input
          type="hidden"
          name="Amt"
          :value="ReceiptPrice"
        >
        <input
          type="hidden"
          name="MID"
          :value="mid"
        >
        <input
          type="hidden"
          name="Moid"
          :value="moid"
        >
        <input
          type="hidden"
          name="BuyerName"
          :value="$store.state.user.name"
        >
        <input
          type="hidden"
          name="BuyerEmail"
          :value="$store.state.user.email"
        >
        <input
          type="hidden"
          name="BuyerTel"
          :value="$store.state.user.mobile_number"
        >
        <!-- 옵션 -->
        <input
          type="hidden"
          name="GoodsCl"
          value="1"
        > <!-- 상품구분(실물(1),컨텐츠(0)) -->
        <input
          type="hidden"
          name="TransType"
          value="0"
        > <!-- 일반(0)/에스크로(1) -->
        <input
          type="hidden"
          name="CharSet"
          value="utf-8"
        > <!-- 응답 파라미터 인코딩 방식 -->
        <input
          type="hidden"
          name="ReqReserved"
          :value="JSON.stringify(form)"
        > <!-- 상점 예약필드 -->
        <input
          type="hidden"
          name="VbankExpDate"
          :value="$moment().add(3, 'days').format('YYYYMMDD')"
        >
        <!-- 변경 불가능 -->
        <input
          type="hidden"
          name="EdiDate"
          :value="ediDate"
        > <!-- 전문 생성일시 -->
        <input
          type="hidden"
          name="SignData"
          :value="hashString"
        > <!-- 해쉬값 -->
        <input
          type="hidden"
          name="ReturnURL"
          :value="$APIURI + 'pay'"
        >
      </form>

      <!-- 결제 정보 -->
      <div class="in-pay-info">
        <!-- [1] 결제 정보 -->
        <article class="l-con-article">
          <h4 class="in-subject">
            결제금액
          </h4>
          <div class="dg-total-price-card">
            <ul class="in-price-infos">
              <li class="_col _col--base">
                <span class="_label">상품금액 합계</span>
                <b class="_prc"><i>{{ NumberFormat(TotalPrice) }}</i> 원</b>
              </li>
              <li class="_col col--delivery">
                <span class="_label">배송비</span>
                <b class="_prc"><i>{{ NumberFormat(form.sendCost) }}</i> 원</b>
              </li>
              <li class="_col col--sale display_none">
                <span class="_label">회원등급 할인</span>
                <b class="_prc"><i>- {{ NumberFormat(levelDiscount) }}</i> 원</b>
              </li>
              <li class="_col col--sale">
                <span class="_label">보유쿠폰 할인</span>
                <b class="_prc"><i>- {{ NumberFormat(CouponDiscount) }}</i> 원</b>
              </li>
              <li class="_col _col--total">
                <span class="_label">최종 결제금액</span>
                <b class="_prc"><i>{{ NumberFormat(ReceiptPrice) }}</i> 원</b>
              </li>
            </ul>
          </div>

          <ul class="in-agree-list">
            <li class="_agr_list">
              <input
                type="checkbox"
                id="privacyAgree"
                class="dg-input-checkbox display_none"
                v-model="privacyAgree"
              >
              <label
                for="privacyAgree"
                class="dg-input-checkbox_label"
              ></label>
              <label
                for="privacyAgree"
                class="dg-input-checkbox"
              >개인정보 제3자 제공고지</label>
            </li>
            <li class="_agr_list">
              <input
                type="checkbox"
                id="buyAgree"
                class="dg-input-checkbox display_none"
                v-model="buyAgree"
              >
              <label
                for="buyAgree"
                class="dg-input-checkbox_label"
              ></label>
              <label
                for="buyAgree"
                class="dg-input-checkbox"
              >구매진행에 동의합니다.</label>
              <input
                id="agree-info-more"
                type="checkbox"
                class="agree-info-more display_none"
              >
              <label
                for="agree-info-more"
                class="_agree_more"
              >더보기<img
                src="/images/icon/icon_more_small.svg"
                alt="more icon"
              ></label>
              <ul
                id="agree-info-view"
                class="_agree_info_view"
              >
                <li>* 제공받는자 : 판매자, 동글</li>
                <li>* 목적 : 판매자와 구매자 사이의 원활한 거래 진행, 상품의 배송을 위한 배송지 확인, 고객상담 및 불만처리 등</li>
                <li>* 정보 : 별명, 이름, 전화, 주소</li>
                <li>* 보유기간 : 발송완료 후 15일</li>
              </ul>
            </li>
          </ul>
          <button
            type="button"
            class="theme-btn-gradient btn-shadow"
            @click="Submit"
          >
            {{ NumberFormat(ReceiptPrice) }}원 결제
          </button>
        </article>
        <!-- [1] E -->
      </div>
      <!-- 결제 정보 E -->
    </div>
    <!-- 2) 주문정보들 E -->
    <CouponPopup
      :class="{'active': couponPopupShow}"
      :item-lists="orderLists"
      :coupon-lists="couponLists"
      @submit="CouponSubmit"
      @popup-close="CouponPopupClose"
    />
    <div :class="['vue-daum-postcode-wrapper', {'fix-active':daumAdressPopup}]">
      <div class="_popup_title">
        <h4>
          주소찾기
          <button
            type="button"
            class="_close_btn"
            @click="daumAdressPopup = false"
          >
          </button>
        </h4>
      </div>
      <vue-daum-postcode @complete="AddressSelect" />
    </div>
  </div>
</template>

<script>
  import ProductList from '@/components/order/product-list.vue'
  import CouponPopup from '@/components/popup/coupon-popup.vue'

  export default {
    components: {
      ProductList,
      CouponPopup
    },
    data: function () {
      return {
        orderLists: [],
        form: {
          s_uid: this.$store.state.user.id,
          s_id: this.$store.state.user.email,
          s_name: this.$store.state.user.name,
          s_hp: this.$store.state.user.mobile_number,
          g_name: '',
          g_hp: '',
          g_post_num: '',
          g_addr1: '',
          g_addr2: '',
          g_extra_addr: '',
          g_addr_jibeon: '',
          delivery_memo: '',
          hope_date: 2,
          sendCost: 0,
          deposit_name: null,
          bank_number: null,
          bank_name: null,
          bank_account: null,
          total_cp_id: null,
          delivery_index: 1
        },
        result: {},
        pg: null,
        paypleCard: null,
        paypleTransfer: null,
        cart_count: 0,
        allCouponLists: [],
        couponLists: [],
        selectCouponLists: [],
        levelDiscount: 0,
        couponDiscount: 0,
        couponPrice: 0,
        receiptPrice: 0,
        minimumHopdate: 2,
        mid: null,
        moid: null,
        ediDate: null,
        hashString: null,
        couponPopupShow: false,
        daumAdressPopup: false,
        userSameBuyer: true,
        privacyAgree: false,
        buyAgree: false,
        nicePayCdn: null,
        paypleCdn: null,
        paying: false
      }
    },
    async created () {
      const beforeDate = this.$moment().subtract(1, 'M')
      const isOldUser = this.$moment(beforeDate).isAfter(this.$store.state.user.created_at)
      let isSubscribeEnd = false

      if (this.$store.state.user.regular_end && this.$moment().isAfter(this.$store.state.user.regular_end)) {
        isSubscribeEnd = true
      }

      if (isOldUser && isSubscribeEnd && this.$store.state.user.level === 1) {
        this.$router.push('/subscribe')
      }

      // 나이스페이 CDN 생성
      this.nicePayCdn = document.createElement('script')
      this.nicePayCdn.setAttribute('src', 'https://web.nicepay.co.kr/v3/webstd/js/nicepay-3.0.js')
      document.head.appendChild(this.nicePayCdn)

      // 페이플 CDN 생성
      this.paypleCdn = document.createElement('script')
      this.paypleCdn.setAttribute('src', 'https://cpay.payple.kr/js/cpay.payple.1.0.1.js')
      document.head.appendChild(this.paypleCdn)

      /*
      if (!this.$store.state.user.account_bank || !this.$store.state.user.account_name || !this.$store.state.user.account_number) {
        const result = await this.WarningAlert('환불 받을 계좌정보가 등록되어 있지 않습니다. 환불 계좌 등록 페이지로 이동합니다.')
        if (result) {
          this.$router.push('/mypage/payment/info')
        }
      }
      */

      this.$store.commit('ProgressShow')

      let cartIds = []
      this.$store.state.selectItems.forEach(selectItem => {
        cartIds.push(selectItem.id)
      })

      const params = {
        cart_ids: cartIds
      }

      await this.OrderIdCreate(params)

      this.form.sendCost += (this.orderLists[0].sc_price + this.orderLists[0].send_cost)

      this.receiptPrice += this.form.sendCost

      await this.CouponLoad()

      this.DeliveryIndex(1)

      this.orderLists.forEach(orderList => {
        if (orderList.possible_ready_term > this.minimumHopdate) {
          this.minimumHopdate = orderList.possible_ready_term
        }
      })

      this.form.hope_date = this.minimumHopdate

      this.$store.commit('ProgressHide')
    },
    beforeDestroy () {
      this.daumAdressPopup = false
    },
    destroyed () {
      this.nicePayCdn.parentNode.removeChild(this.nicePayCdn)
      this.paypleCdn.parentNode.removeChild(this.paypleCdn)
    },
    computed: {
      TotalPrice () {
        let totalPrice = 0
        this.orderLists.forEach(orderList => {
          totalPrice += orderList.qty * (orderList.price + orderList.option_price)
        })

        return totalPrice
      },
      CouponDiscount () {
        return this.couponDiscount + this.couponPrice
      },
      ReceiptPrice () {
        let price = this.form.sendCost
        this.orderLists.forEach(orderList => {
          price = price + orderList.qty * (orderList.option_price + orderList.price)
        })
        return price - (this.couponDiscount + this.couponPrice + this.levelDiscount)
      }
    },
    methods: {
      async OrderIdCreate (params) {
        console.log('')
        try {
          const res = (await this.$http.get(this.$APIURI + 'cart/order_list', { params })).data

          if (res.state === 1) {
            this.orderLists = []
            this.orderLists = res.query.carts
            this.receiptPrice = Number(res.query.price)
            this.mid = res.query.MID
            this.moid = res.query.moid
            this.ediDate = res.query.ediDate
            this.hashString = res.query.hashString
            this.levelDiscount = Number(res.query.levelDiscountPrice)
            this.paypleCard = res.query.payple_card
            this.paypleTransfer = res.query.payple_transfer

            return res.query
          } else {
            console.log('주문id 생성 오류 원인:', res.msg)
          }
        } catch (e) {
          console.log('주문id 생성 오류 원인:', e)
        }
      },
      async CouponLoad () {
        try {
          let totalPrice = 0
          this.orderLists.forEach(orderList => {
            totalPrice += orderList.qty * (orderList.price + orderList.option_price)
          })

          const params = {
            total_price: totalPrice
          }

          const res = (await this.$http.get(this.$APIURI + 'coupon/my_coupon', { params })).data

          if (res.state === 1) {
            res.query.forEach(coupon => {
              if (coupon.cp_method === 0 || coupon.cp_method === 1) {
                this.couponLists.push(coupon)
              } else {
                this.allCouponLists.push(coupon)
              }
            })
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      AllCouponSubmit (value) {
        if (value) {
          let price = 0
          this.form.total_cp_id = value
          this.allCouponLists.forEach((coupon, index) => {
            if (coupon.cp_id === value) {
              if (coupon.cp_type === 0) {
                price = coupon.cp_price
              } else {
                price = this.receiptPrice * (coupon.cp_price / 100)
                price = price - (price % coupon.cp_trunc)
              }
              this.couponDiscount = price
            }
          })
        } else {
          this.form.total_cp_id = null
          this.couponDiscount = 0
        }
      },
      async CouponSubmit (itemLists, couponLists) {
        this.orderLists = itemLists
        this.couponLists = couponLists

        this.orderLists.forEach(orderList => {
          this.couponPrice = 0
          this.couponPrice += (orderList.coupon_price || 0)
        })
      },
      Validation () {
        if (!this.form.s_name || this.form.s_name === '') {
          this.WarningAlert('주문고객 정보를 모두 입력해주세요.')
          return false
        }

        if (!this.form.s_hp || this.form.s_hp === '') {
          this.WarningAlert('주문고객 정보를 모두 입력해주세요.')
          return false
        }

        if (!this.form.s_id || this.form.s_id === '') {
          this.WarningAlert('주문고객 정보를 모두 입력해주세요.')
          return false
        }

        if (!this.form.g_name || this.form.g_name === '') {
          this.WarningAlert('배송지 정보를 모두 입력해주세요.')
          return false
        }

        if (!this.form.g_hp || this.form.g_hp === '') {
          this.WarningAlert('배송지 정보를 모두 입력해주세요.')
          return false
        }

        if (!this.form.g_post_num || this.form.g_post_num === '') {
          this.WarningAlert('배송지 정보를 모두 입력해주세요.')
          return false
        }

        if (!this.form.g_addr1 || this.form.g_addr1 === '') {
          this.WarningAlert('배송지 정보를 모두 입력해주세요.')
          return false
        }

        if (!this.form.g_addr2 || this.form.g_addr2 === '') {
          this.WarningAlert('배송지 정보를 모두 입력해주세요.')
          return false
        }

        if (!this.pg) {
          this.WarningAlert('결제 수단을 선택해주세요.')
          return false
        }

        if (!this.privacyAgree) {
          this.WarningAlert('개인정보 제공에 동의해주셔야 결제가 진행됩니다.')
          return false
        }

        if (!this.buyAgree) {
          this.WarningAlert('결제에 동의하지 않으셨습니다.')
          return false
        }

        return true
      },
      async Submit () {
        if (!this.Validation()) {
          return false
        }

        if (this.pg === 'nice') {
          this.NicePayOrder()
        } else if (this.pg === 'paypleCard' || this.pg === 'paypleCert') {
          const type = this.pg === 'paypleCard' ? 'card' : 'cert'
          this.PayPleOrder(type)
        }
      },
      async NicePayOrder () {
        this.$store.commit('ProgressShow')

        let memo = []
        let params = {}
        this.orderLists.forEach(async orderList => {
          params = {
            _method: 'put',
            cp_id: orderList.cp_id,
            cart_id: orderList.id
          }

          if (orderList.request_msg) {
            memo.push(orderList.request_msg)
          } else {
            memo.push(null)
          }

          try {
            await this.$http.post(this.$APIURI + 'cart/coupon', params)
          } catch (e) {
            console.log(e)
          }
        })

        this.form.memo = memo
        let cartIds = []

        this.$store.state.selectItems.forEach(selectItem => {
          cartIds.push(selectItem.id)
        })

        params = {
          cart_ids: cartIds,
          total_cp_id: this.form.total_cp_id
        }

        const result = await this.OrderIdCreate(params)

        params = {
          Moid: result.moid,
          PayMethod: this.payMethod,
          mobile_yn: 1
        }

        params = Object.assign(params, this.form)

        try {
          const res = (await this.$http.post(this.$APIURI + 'order', params)).data

          if (res.query) {
            this.nicepayStart()
          } else {
            this.WarningAlert(res.msg)
            this.orderLists.forEach(async orderList => {
              params = {
                _method: 'put',
                cp_id: null,
                cart_id: orderList.id
              }
              try {
                await this.$http.post(this.$APIURI + 'cart/coupon', params)
              } catch (e) {
                console.log(e)
              }
            })
          }
        } catch (e) {
          this.orderLists.forEach(async orderList => {
            params = {
              _method: 'put',
              cp_id: null,
              cart_id: orderList.id
            }
            try {
              await this.$http.post(this.$APIURI + 'cart/coupon', params)
            } catch (e) {
              console.log(e)
            }
          })
          console.log(e)
        } finally {
          this.$store.commit('ProgressHide')
        }
      },
      async PayPleOrder (type) {
        this.$store.commit('ProgressShow')

        let memo = []
        let params = {}
        this.orderLists.forEach(async orderList => {
          params = {
            _method: 'put',
            cp_id: orderList.cp_id,
            cart_id: orderList.id
          }

          if (orderList.request_msg) {
            memo.push(orderList.request_msg)
          } else {
            memo.push(null)
          }

          try {
            await this.$http.post(this.$APIURI + 'cart/coupon', params)
          } catch (e) {
            console.log(e)
          }
        })

        this.form.memo = memo
        let cartIds = []

        this.$store.state.selectItems.forEach(selectItem => {
          cartIds.push(selectItem.id)
        })

        params = {
          cart_ids: cartIds,
          total_cp_id: this.form.total_cp_id
        }

        const result = await this.OrderIdCreate(params)

        params = {
          Moid: result.moid,
          PayMethod: this.payMethod,
          mobile_yn: 1
        }

        params = Object.assign(params, this.form)

        try {
          const res = (await this.$http.post(this.$APIURI + 'order', params)).data

          if (res.query) {
            const params = {
              PCD_RST_URL: this.$APIURI + 'payple/order_mobile',
              PCD_CPAY_VER: '1.0.1',
              PCD_PAY_WORK: 'CERT',
              PCD_CARD_VER: '01',
              PCD_PAY_TYPE: type,
              PCD_PAYER_AUTHTYPE: 'pwd',
              payple_auth_file: this.$APIURI + 'payple/auth?referer=' + window.location.protocol + '//' + window.location.host + '&time=' + new Date().getTime(),
              // callbackFunction: this.GetResult,
              PCD_PAYER_NO: this.$store.state.user.id,
              PCD_PAYER_EMAIL: this.$store.state.user.email,
              PCD_PAY_GOODS: '(주)동글 상품 결제',
              PCD_PAY_TOTAL: this.receiptPrice,
              PCD_PAY_OID: this.moid,
              PCD_TAXSAVE_FLAG: 'Y',
              PCD_SIMPLE_FLAG: 'N'
            }

            if (type === 'card') {
              if (this.paypleCard && result.payple_card) {
                params.PCD_PAYER_ID = result.payple_card.pp_tno
                params.PCD_SIMPLE_FLAG = 'Y'
              }
            } else {
              if (this.paypleTransfer && result.payple_transfer) {
                params.PCD_PAYER_ID = result.payple_transfer.pp_tno
                params.PCD_SIMPLE_FLAG = 'Y'
              }
            }

            window.PaypleCpayAuthCheck(params)
          } else {
            this.WarningAlert(res.msg)
            this.orderLists.forEach(async orderList => {
              params = {
                _method: 'put',
                cp_id: null,
                cart_id: orderList.id
              }
              try {
                await this.$http.post(this.$APIURI + 'cart/coupon', params)
              } catch (e) {
                console.log(e)
              }
            })
          }
        } catch (e) {
          this.orderLists.forEach(async orderList => {
            params = {
              _method: 'put',
              cp_id: null,
              cart_id: orderList.id
            }
            try {
              await this.$http.post(this.$APIURI + 'cart/coupon', params)
            } catch (e) {
              console.log(e)
            }
          })
          console.log(e)
        } finally {
          this.$store.commit('ProgressHide')
        }
      },
      async GetResult (response) {
        if (!this.paying) {
          console.log(response)
          this.paying = true
          const params = response

          try {
            this.$store.commit('ProgressShow')

            const res = (await this.$http.post(this.$APIURI + 'payple/order', params)).data

            if (res.state === 1) {
              this.$router.push('/order/complete?orderId=' + res.query)
            } else {
              this.WarningAlert(res.msg)
            }
          } catch (e) {
            console.log(e)
          } finally {
            this.paying = false
            this.$store.commit('ProgressHide')
          }
        }
      },
      UserSameBuyer () {
        if (this.userSameBuyer) {
          this.form.s_uid = this.$store.state.user.id
          this.form.s_id = this.$store.state.user.email
          this.form.s_name = this.$store.state.user.name
          this.form.s_hp = this.$store.state.user.mobile_number
        } else {
          this.form.s_uid = null
          this.form.s_id = null
          this.form.s_name = null
          this.form.s_hp = null
        }
      },
      DeliveryIndex (index) {
        if (this.$store.state.delivery.length > 0) {
          this.form.delivery_index = index
          const delivery = this.$store.state.delivery[index - 1] || null

          if (delivery) {
            this.form.g_name = delivery.name
            this.form.g_hp = delivery.phone_num
            this.form.g_addr1 = delivery.addr1
            this.form.g_addr2 = delivery.addr2
            this.form.g_post_num = delivery.post_num
            this.form.g_extra_addr = delivery.extra_addr
            this.g_addr_jibeon = delivery.addr_jibeon
          } else {
            this.form.g_name = null
            this.form.g_hp = null
            this.form.g_addr1 = null
            this.form.g_addr2 = null
            this.form.g_post_num = null
            this.form.g_extra_addr = null
            this.g_addr_jibeon = null
          }
        } else {
          this.form.delivery_index = index
        }
      },
      AddressSelect (address) {
        this.daumAdressPopup = false
        this.form.g_post_num = address.zonecode
        this.form.g_addr1 = address.roadAddress
        this.form.g_addr_jibeon = address.jibunAddress
      },
      CouponPopupOpen (itemId) {
        console.log('상품 정보 가져오기 API 호출 item_id:', itemId)
        console.log('해당 상품에 사용 가능한 쿠폰 리스트 불러오기 API 호출')

        // this.couponLists = couponLists

        for (let i = 0; i < this.selectCouponLists.length; i++) {
          for (let j = 0; j < this.couponLists.length; j++) {
            if (this.selectCouponLists[i].id === this.couponLists[j].id) {
              this.couponLists.splice(j, 1)
            }
          }
        }

        this.couponPopupShow = true
        document.body.style.overflowY = 'hidden'
      },
      CouponPopupClose () {
        this.couponPopupShow = false
        document.body.style.overflowY = 'auto'
      },
      ObjectEquals (x, y) {
        if (x === y) return true
        if (!(x instanceof Object) || !(y instanceof Object)) return false
        if (x.constructor !== y.constructor) return false
        for (var p in x) {
          if (!x.hasOwnProperty(p)) continue
          if (!y.hasOwnProperty(p)) return false
          if (x[p] === y[p]) continue
          if (typeof (x[p]) !== 'object') return false
          if (!Object.equals(x[p], y[p])) return false
        }

        for (p in y) {
          if (y.hasOwnProperty(p) && !x.hasOwnProperty(p)) return false
        }
        return true
      },
      async nicepayStart () {
        document.niceForm.action = 'https://web.nicepay.co.kr/v3/v3Payment.jsp'
        document.niceForm.submit()
      },

      // [PC 결제창 전용]결제창 종료 함수 <<'nicepayClose()' 이름 수정 불가능>>
      nicepayClose () {
        this.WarningAlert('결제가 취소 되었습니다')
      }
    }
  }
</script>

<style lang="scss" scoped>
  .popup_post_type2 .popup_head {
    padding-top: 50px;
  }

  input[type="tel"],
  input[type="email"] {
    -webkit-appearance: none;
    -webkit-border-radius: 0px;
    border-radius: 0;
    box-shadow: 0px 0px 0px rgba(0, 0, 0, 0);
  }

  .dg-order-wrapper .not-process-first ._page_title_wrap {
    padding-top: calc(50px + 42px);
  }

  .in-coupon-box ._coupon_tit {
    padding-top: 0;
    margin-top: 0;
    border-radius: 0;
    font-size: 0.813em;
  }
</style>
