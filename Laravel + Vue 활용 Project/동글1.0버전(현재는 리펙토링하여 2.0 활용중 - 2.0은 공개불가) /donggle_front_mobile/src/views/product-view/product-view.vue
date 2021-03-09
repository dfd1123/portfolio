<template>
  <!-- content -->
  <!--
    _sale     품절이 아닐 때
    구매하기 버튼은 ._sale에 display:none은 우선순위 밀림 .product-buy_option_btn > .dg-btn_gra._sale
    _soldout  품절일 때

    ._quick_menu ._soldout,
    .in-pricedetail ._soldout
      에 display:none

    _fill     글이 있을 때
    _empty    글이 없을 때

    ._sell_review_wrap ._empty,
    ._qna_wrap ._empty
      에 display:none

    리뷰에 이미지가 있을 떄
    ._review_write_text._with_img

    ._review_wrap > ._write_review
    클릭시
    .review_write_popup_wrapper
    .display_none 삭제

    ._qna_wrap > ._write_review
    클릭시
    .qna_write_popup_wrapper
    .display_none 삭제

    ._board_pager : pagination 자리
   -->
  <div id="dg-product-detail-view-wrapper">
    <div
      class="_go_top"
      v-scroll-to="'#app'"
    >
      <button>
        위로가기
      </button>
    </div>
    <!-- 상품문의 작성 팝업 -->
    <QnaWritePopup
      :item="item"
      @close-event="CloseQnaPopup"
      @qna-load="QnaLoad"
      :class="{'posFixed': qnaPopupShow}"
    />
    <!-- 상품문의 작성 팝업 E -->
    <div>
      <!-- content1 -->
      <section
        id="product_view"
        class="clear_both _page_title_wrap"
      >
        <h2>
          제품상세
          <a
            href="#"
            class="_back_btn"
            @click.prevent="$router.go(-1)"
          >뒤로가기</a>
          <button
            type="button"
            class="_cart_btn"
            @click="$router.push('/cart')"
          >
            카트
          </button>
        </h2>

        <ProductViewTop
          :item="item"
          :options="options"
          :option-subject="optionSubject"
          :options-name="optionsName"
          :review-count="reviewCount"
          @sns-popup-open="SnsPopupOpen"
          @sns-popup-close="SnsPopupClose"
          @submit="Submit"
        />
      </section>
    </div>
    <!-- content2 -->
    <section
      id="product_detail"
      class="clear_both"
    >
      <h2 class="dg_blind">
        상품정보보기
      </h2>
      <!-- tab title gruop -->
      <div :class="['dg-tab-title', {'posi-fixed':tabTopFixed}]">
        <div class="tab-title_wrap">
          <ul class="tab-title_width">
            <!--
              눌러서 영역 열기 열린 영역의 제목에 .active
            -->
            <li>
              <a
                href="#"
                v-scroll-to="{ el: '#introduce', offset: -100 }"
                :class="['tab-title_content', {'active':tabActive === 1}]"
              >상품정보</a>
            </li>
            <li>
              <a
                href="#"
                v-scroll-to="{ el: '#review', offset: -100 }"
                :class="['tab-title_content', {'active':tabActive === 2}]"
              >사용후기<span>{{ NumberFormat(reviewCount) }}</span></a>
            </li>
            <li>
              <a
                href="#"
                v-scroll-to="{ el: '#qna', offset: -100 }"
                :class="['tab-title_content', {'active':tabActive === 3}]"
              >상품문의<span>{{ NumberFormat(qnaCount) }}</span></a>
            </li>
            <li>
              <a
                href="#"
                v-scroll-to="{ el: '#refund_about_info', offset: -100 }"
                :class="['tab-title_content', {'active':tabActive === 4}]"
              >배송/교환/반품</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- tab title gruop E -->
      <div class="product-detail_wrap clear_both">
        <!-- tab content -->
        <div class="product-detail">
          <!-- 상품정보란 -->
          <div class="product-infomation_wrap">
            <section
              id="introduce"
              class="product-infomation"
            >
              <div v-html="item.introduce_mobile">
              </div>
            </section>

            <!-- 세탁, 원단 정보란 -->
            <div class="_ect_detail_info_layer">
              <div class="_ect_desc_layer">
                <ul>
                  <li>
                    <dl class="clear_both _ect_wrap">
                      <dt class="_title">
                        세탁방법
                      </dt>
                      <dd class="_desc">
                        <span
                          v-for="(clean,index) in JSON.parse(item.how_clean || '[]')"
                          :key="'how_clean'+index"
                          :class="['_text', {'non_active':!clean.status}]"
                        >{{ clean.name }}</span>
                      </dd>
                    </dl>
                  </li>
                  <li>
                    <dl class="clear_both _ect_wrap">
                      <dt class="_title">
                        소재
                      </dt>
                      <dd class="_desc">
                        <span
                          v-for="(material,index) in JSON.parse(item.material || '[]')"
                          :key="'material'+index"
                          :class="['_text', {'non_active':!material.status}]"
                        >{{ material.name }}</span>
                      </dd>
                    </dl>
                  </li>
                </ul>
              </div>
            </div>
            <!-- 세탁, 원단 정보란 E -->

            <!-- 기타 상세정보란 -->
            <section class="_ect_desc_wrap _etc_detail_info_wrap">
              <h3>기타 상세정보</h3>
              <div class="_ect_desc_layer _round_btn">
                <ul>
                  <li
                    v-for="(etcInfo,index) in JSON.parse(item.etc_detail_info || '[]')"
                    :key="'etcInfo'+index"
                  >
                    <dl class="clear_both _ect_wrap">
                      <dt class="_title">
                        {{ etcInfo.kind }}
                      </dt>
                      <dd class="_desc">
                        <div
                          v-for="(option, idx) in etcInfo.option"
                          :key="'etcOption' + idx"
                          :class="['_detail_info', {'active':option.status}]"
                        >
                          <p>{{ option.name }}</p>
                        </div>
                      </dd>
                    </dl>
                  </li>
                </ul>
              </div>
            </section>
            <!-- 기타 상세정보란 E -->
            <!-- 해시태그# 해당 단어로 검색된 페이지 열림 -->
            <div class="dg-clothes-list-card dg-clothes-detail mo-hash-wrap">
              <h3><small>#Hachtag</small>상품 해시태그</h3>
              <ul class="in-pricedetail">
                <li class="_hashlist">
                  <div class="_inner">
                    <router-link
                      v-for="(hashTag, index) in HashTag"
                      :key="'hashTag' + index"
                      :to="'/total/search?searchKeyword='+hashTag"
                      class="rounded-list rounded-list-s"
                    >
                      <span>{{ hashTag }}</span>
                    </router-link>
                  </div>
                </li>
              </ul>
            </div>
            <!-- 해시태그# E -->
          </div>
          <!-- 상품정보란 E -->

          <!-- 구매후기 -->
          <ReviewSection
            id="review"
            :reviews="reviews"
            :review-page="reviewPage"
            :review-limit="reviewLimit"
            :review-count="reviewCount"
            @reviews-load="ReviewPageChange"
            @popup-event="OpenReviewPopup"
          />
          <!-- 구매후기 E -->

          <!-- 상품문의 -->
          <QnaSection
            id="qna"
            :qnas="qnas"
            :qna-page="qnaPage"
            :qna-limit="qnaLimit"
            :qna-count="qnaCount"
            :seller-id="item.seller_id"
            :item-id="id"
            @qnas-load="QnaPageChange"
            @popup-event="OpenQnaPopup"
          />
          <!-- 상품문의 E -->

          <!-- 배송/교환/반품 -->
          <RefundAboutInfo id="refund_about_info" />
          <!-- 배송/교환/반품 E -->
        </div>
        <!-- con2 left E -->

        <!-- 옵션선택 구매하기 -->
        <ProductOptionControl
          :item="item"
          :options="options"
          :option-subject="optionSubject"
          :options-name="optionsName"
          @submit="Submit"
        />
      </div>
    </section>
    <SnsSharePopup
      :class="{'active':snsPopupShow}"
      :txt="'[동글]'+item.title"
      :item="item"
      @sns-popup-close="SnsPopupClose"
    />
  </div>
  <!-- content E -->
</template>

<script>
  import ProductViewTop from '@/components/product-view/product-view-top.vue'
  import QnaWritePopup from '@/components/popup/qna-write-popup.vue'
  import QnaSection from '@/components/product-view/qna-section.vue'
  import ReviewSection from '@/components/product-view/review-section.vue'
  import RefundAboutInfo from '@/components/product-view/refund-about-info.vue'
  import ProductOptionControl from '@/components/product-view/product-option-control.vue'
  import SnsSharePopup from '@/components/popup/sns-share-popup.vue'

  export default {
    components: {
      ProductViewTop,
      QnaWritePopup,
      QnaSection,
      ReviewSection,
      RefundAboutInfo,
      ProductOptionControl,
      SnsSharePopup
    },
    data: function () {
      return {
        item: {},
        options: [],
        optionSubject: [],
        optionsName: [],
        reviews: [],
        reviewPage: 1,
        reviewLimit: 10,
        reviewCount: 0,
        reviewPopupShow: false,
        qnas: [],
        qnaPage: 1,
        qnaLimit: 10,
        qnaCount: 0,
        qnaPopupShow: false,
        form: {
          selectItems: []
        },
        stickyOptions: {
          stickyClass: 'is-affixed',
          topSpacing: 132,
          bottomSpacing: 132
        },
        selectOption: [],
        totalPrice: 0,
        tabActive: 1,
        tabTopFixed: false,
        snsPopupShow: false
      }
    },
    props: {
      id: {
        type: String,
        required: true
      }
    },
    async created () {
      window.addEventListener('scroll', this.HandleScroll)

      this.$store.commit('ProgressShow')

      this.$store.commit('SelectOptionReset')
      this.$store.commit('TotalPriceReset')

      const itemInfo = await this.ItemLoad()
      const qnaInfo = await this.QnaLoad()
      const reviewInfo = await this.ReviewLoad()

      this.item = itemInfo.query.item
      this.options = itemInfo.query.item_option
      this.optionSubject = this.item.option_subject.split(',')

      this.qnas = qnaInfo.query.item_qna
      this.qnaPage = Number(qnaInfo.query.page)
      this.qnaCount = qnaInfo.query.count

      this.reviews = reviewInfo.query.reviews
      this.reviewPage = Number(reviewInfo.query.page)
      this.reviewCount = reviewInfo.query.count

      if (this.item === null || this.item.delete_yn === 1) {
        const result = await this.WarningAlert('이 상품은 삭제되거나 존재하지 않은 상품입니다.')
        if (result) {
          this.$router.go(-1)
        }
      }

      const optionsName = []

      for (let i = 0; i < this.optionSubject.length; i++) {
        this.$set(this.optionsName, i, [])
      }

      this.options.forEach(option => {
        const optionArr = option.name.split(',')
        if (optionsName.indexOf(optionArr[0]) === -1) {
          optionsName.push(optionArr[0])
        }
      })

      this.$set(this.optionsName, 0, optionsName)

      this.$store.commit('ProgressHide')

      this.HitIncreament()
      this.AddRecentItem()
    },
    beforeDestroy () {
      this.$store.commit('SelectOptionReset')
      this.$store.commit('TotalPriceReset')
    },
    destroyed () {
      window.removeEventListener('scroll', this.HandleScroll)
    },
    computed: {
      HashTag () {
        if (this.item.hash_tag) {
          return this.item.hash_tag.split(',')
        } else {
          return []
        }
      }
    },
    methods: {
      async ItemLoad () {
        const params = { item_id: Number(this.id) }
        const res = (await this.$http.get(this.$APIURI + 'items/view', { params })).data

        return res
      },
      async QnaLoad () {
        const params = {
          page: this.qnaPage,
          limit: this.qnaLimit,
          item_id: Number(this.id)
        }
        const res = (await this.$http.get(this.$APIURI + 'item_qna/item_qa', { params })).data

        return res
      },
      async ReviewLoad () {
        const params = {
          page: this.reviewPage,
          limit: this.reviewLimit,
          item_id: Number(this.id)
        }
        const res = (await this.$http.get(this.$APIURI + 'review/item_review', { params })).data

        return res
      },
      ParamsSet () {
        const params = {
          item_id: this.item.item_id,
          options: JSON.stringify(this.$store.state.selectOption)
        }

        return params
      },
      Validation () {
        if (this.$store.state.selectOption.length <= 0) {
          this.WarningAlert('옵션을 선택하여 주세요.')
          return false
        }

        return true
      },
      async Submit (kind) {
        if (this.Validation()) {
          const params = this.ParamsSet()
          try {
            const res = (await this.$http.post(this.$APIURI + 'cart', params)).data

            if (res.state === 1) {
              if (kind === 'direct') {
                this.$store.commit('SelectItemsStore', res.query)
                this.$router.push('/order')
              } else {
                const result = await this.Confirm('장바구니에 성공적으로 담겼습니다.\n장바구니를 확인하시겠습니까?')
                if (result) {
                  this.$router.push('/cart')
                }
              }
            }
          } catch (e) {
            console.log(e)
          }
        }
      },
      async QnaPageChange (currentPage) {
        this.qnaPage = currentPage
        const res = await this.QnaLoad()

        this.qnas = res.query.item_qna
        this.qnaPage = Number(res.query.page)
        this.qnaCount = res.query.count
      },
      async ReviewPageChange (currentPage) {
        this.reviewPage = currentPage
        const res = await this.ReviewLoad()

        this.reviews = res.query.reviews
        this.reviewPage = Number(res.query.page)
        this.reviewCount = res.query.count
      },
      OpenReviewPopup () {
        this.reviewPopupShow = true
      },
      CloseReviewPopup () {
        this.reviewPopupShow = false
        // this.item = {}
      },
      OpenQnaPopup () {
        document.body.style.overflowY = 'hidden'
        this.qnaPopupShow = true
      },
      async CloseQnaPopup () {
        document.body.style.overflowY = 'auto'
        this.qnaPopupShow = false
        // this.item = {}
        const qnaInfo = await this.QnaLoad()

        this.qnas = qnaInfo.query.item_qna
        this.qnaPage = Number(qnaInfo.query.page)
        this.qnaCount = qnaInfo.query.count
      },
      SnsPopupOpen () {
        document.body.style.overflowY = 'hidden'
        this.snsPopupShow = true
      },
      SnsPopupClose () {
        document.body.style.overflowY = 'auto'
        this.snsPopupShow = false
      },
      AddRecentItem () {
        let arr = []
        if (!localStorage.items) {
          arr.push(this.item)
        } else {
          let duplicateIndex = -1
          arr = JSON.parse(localStorage.getItem('items'))
          for (let i = 0; i < arr.length; i++) {
            if (arr[i].item_id === this.item.item_id) {
              duplicateIndex = i
              break
            }
          }

          if (duplicateIndex !== -1) {
            arr.splice(duplicateIndex, 1)
          }
          if (arr.length >= 50) {
            arr = arr.shift()
          }
          arr.push(this.item)
        }

        localStorage.setItem('items', JSON.stringify(arr))
      },
      HitIncreament () {
        if (this.$cookies.isKey('viewProducts')) {
          let tempArr = []
          if (this.$cookies.get('viewProducts')) {
            tempArr = this.$cookies.get('viewProducts').split(';')
          }
          let exist = false
          tempArr.forEach(el => {
            if (Number(el) === this.item.item_id) {
              exist = true
            }
          })

          if (!exist) {
            let viewProducts = this.$cookies.get('viewProducts')
            viewProducts = viewProducts + ';' + this.item.item_id
            this.$cookies.set('viewProducts', viewProducts, '1d')
            const params = {
              item_id: this.item.item_id
            }
            this.$http.put(this.$APIURI + 'itemhit/hit', params)
          }
        } else {
          // console.log('awdaw')
          this.$cookies.set('viewProducts', this.item.item_id, { expires: '1d' })
          const params = {
            item_id: this.item.item_id
          }
          this.$http.put(this.$APIURI + 'itemhit/hit', params)
        }
      },
      async HandleScroll () {
        const introduceTop = document.getElementById('introduce').offsetTop
        const reviewTop = document.getElementById('review').offsetTop
        const qnaTop = document.getElementById('qna').offsetTop
        const refundAboutInfoTop = document.getElementById('refund_about_info').offsetTop

        if (window.pageYOffset >= (introduceTop - 160)) {
          this.tabActive = 1
        }

        if (window.pageYOffset >= (reviewTop - 160)) {
          this.tabActive = 2
        }

        if (window.pageYOffset >= (qnaTop - 160)) {
          this.tabActive = 3
        }

        if (window.pageYOffset >= (refundAboutInfoTop - 160)) {
          this.tabActive = 4
        }

        const productDetail = document.getElementById('product_detail').offsetTop

        if (productDetail <= window.pageYOffset) {
          this.tabTopFixed = true
        } else {
          this.tabTopFixed = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .posi-fixed {
    background: #fff;
    width: 100%;
    position: fixed;
    top: 50px;
    left: 0;
    z-index: 3;
  }

  ._ect_desc_layer ._desc ._text.non_active {
    color: #f0f0f0 !important;
    text-decoration: line-through;
    font-weight: 200;
  }
</style>
