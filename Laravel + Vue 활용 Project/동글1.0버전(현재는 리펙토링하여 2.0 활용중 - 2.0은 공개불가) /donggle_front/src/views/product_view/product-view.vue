<template>
  <div id="dg-product-detail-view-wrapper">
    <div class="dg-content_center">
      <!-- content1 -->
      <ProductViewTop
        :item="item"
        :options="options"
        :option-subject="optionSubject"
        :options-name="optionsName"
        :review-count="reviewCount"
        @submit="Submit"
      />
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
      <div class="dg-tab-title">
        <div class="tab-title_wrap dg-content_center">
          <div class="tab-title_width">
            <div>
              <a
                href="#"
                v-scroll-to="'#introduce'"
                :class="['tab-title_content', {'active':tabActive === 1}]"
              >상품정보</a>
            </div>
            <div>
              <a
                href="#"
                v-scroll-to="'#review'"
                :class="['tab-title_content', {'active':tabActive === 2}]"
              >사용후기<span>({{ NumberFormat(reviewCount) }})</span></a>
            </div>
            <div>
              <a
                href="#"
                v-scroll-to="'#qna'"
                :class="['tab-title_content', {'active':tabActive === 3}]"
              >상품문의<span>({{ NumberFormat(qnaCount) }})</span></a>
            </div>
            <div>
              <a
                href="#"
                v-scroll-to="'#refund_about_info'"
                :class="['tab-title_content', {'active':tabActive === 4}]"
              >배송/교환/반품</a>
            </div>
          </div>
        </div>
      </div>
      <!-- tab title gruop E -->
      <div class="product-detail_wrap clear_both dg-content_center">
        <!-- con2 left -->
        <div class="product-detail">
          <div :class="['tab-title_wrap clear_both', {'tab-top-fix': tabTopFixed}]">
            <div>
              <a
                href="#"
                v-scroll-to="{ el: '#introduce', offset: -250 }"
                :class="['tab-title_content', {'active':tabActive === 1}]"
              >상품정보</a>
            </div>
            <div>
              <a
                href="#"
                v-scroll-to="{ el: '#review', offset: -250 }"
                :class="['tab-title_content', {'active':tabActive === 2}]"
              >사용후기<span>({{ NumberFormat(reviewCount) }})</span></a>
            </div>
            <div>
              <a
                href="#"
                v-scroll-to="{ el: '#qna', offset: -250 }"
                :class="['tab-title_content', {'active':tabActive === 3}]"
              >상품문의<span>({{ NumberFormat(qnaCount) }})</span></a>
            </div>
            <div>
              <a
                href="#"
                v-scroll-to="{ el: '#refund_about_info', offset: -250 }"
                :class="['tab-title_content', {'active':tabActive === 4}]"
              >배송/교환/반품</a>
            </div>
          </div>
          <section
            id="introduce"
            class="product-infomation"
          >
            <div v-html="item.introduce_pc"></div>
          </section>

          <!-- 세탁, 원단 정보란 -->
          <div
            v-if="item.how_clean !== undefined && item.material !== undefined"
            class="_ect_detail_info_layer"
          >
            <table class="_ect_desc_layer">
              <caption class="dg_blind">
                세탁 정보
              </caption>
              <thead class="dg_blind">
                <tr>
                  <th class="_title">
                    방법
                  </th>
                  <th class="_title">
                    정보
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th class="_title">
                    세탁방법
                  </th>
                  <td class="_desc infor-list">
                    <span
                      v-for="(clean,index) in JSON.parse(item.how_clean)"
                      :key="'how_clean'+index"
                      :class="['_text', {'non_active':!clean.status}]"
                    >{{ clean.name }}&nbsp;</span>
                  </td>
                </tr>
                <tr>
                  <th class="_title">
                    소재
                  </th>
                  <td class="_desc infor-list">
                    <span
                      v-for="(material,index) in JSON.parse(item.material)"
                      :key="'material'+index"
                      :class="['_text', {'non_active':!material.status}]"
                    >{{ material.name }}&nbsp;</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- 세탁, 원단 정보란 E -->

          <!-- 기타 상세정보란 -->
          <section
            v-if="item.etc_detail_info !== undefined"
            class="_ect_desc_wrap"
          >
            <h3>기타 상세정보</h3>
            <table class="_ect_desc_layer">
              <caption class="dg_blind">
                기타 정보
              </caption>
              <thead class="dg_blind">
                <tr>
                  <th class="_title">
                    기능
                  </th>
                  <th class="_title">
                    세부정보
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(etcInfo,index) in JSON.parse(item.etc_detail_info)"
                  :key="'etcInfo'+index"
                >
                  <th class="_title">
                    {{ etcInfo.kind }}
                  </th>
                  <td class="_desc">
                    <div
                      v-for="(option, idx) in etcInfo.option"
                      :key="'etcOption' + idx"
                      :class="['_detail_info', {'active':option.status}]"
                    >
                      <p>{{ option.name }}</p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </section>
          <!-- 기타 상세정보란 E -->

          <!-- 구매후기 -->
          <ReviewSection
            id="review"
            :reviews="reviews"
            :review-page="reviewPage"
            :review-limit="reviewLimit"
            :review-count="reviewCount"
            :item-id="id"
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

        <!-- con2 right(퀵메뉴) -->
        <div class="sticky">
          <ProductSmallControl
            :item="item"
            :options="options"
            :option-subject="optionSubject"
            :options-name="optionsName"
            @submit="Submit"
          />
          <!-- con2 right E -->
        </div>
      </div>
    </section>
    <!-- 구매후기 작성 팝업 -->
    <ReviewWritePopup
      :item="item"
      kind="create"
      @close-event="CloseReviewPopup"
      v-show="reviewPopupShow"
    />
    <!-- 구매후기 작성 팝업 E -->

    <QnaWritePopup
      :item="item"
      @close-event="CloseQnaPopup"
      @qna-load="QnaLoad"
      v-show="qnaPopupShow"
    />
  </div>
  <!-- content E -->
</template>

<script>
  import VueStickyDirective from '@renatodeleao/vue-sticky-directive'
  import ProductViewTop from '@/components/product/product-view-top.vue'
  import QnaSection from '@/components/product/qna-section.vue'
  import ReviewSection from '@/components/product/review-section.vue'
  import QnaWritePopup from '@/components/popup/qna-write-popup.vue'
  import ReviewWritePopup from '@/components/popup/review-write-popup.vue'
  import RefundAboutInfo from '@/components/product/refund-about-info.vue'
  import ProductSmallControl from '@/components/product/product-small-control.vue'

  export default {
    directives: {
      'sticky': VueStickyDirective
    },
    components: {
      ProductViewTop,
      QnaSection,
      ReviewSection,
      QnaWritePopup,
      ReviewWritePopup,
      RefundAboutInfo,
      ProductSmallControl
    },
    data: function () {
      return {
        item: {},
        options: [],
        optionSubject: [],
        optionsName: [],
        reviews: [],
        reviewPage: 1,
        reviewLimit: 5,
        reviewCount: 0,
        reviewPopupShow: false,
        qnas: [],
        qnaPage: 1,
        qnaLimit: 5,
        qnaCount: 0,
        qnaPopupShow: false,
        form: {
          selectItems: []
        },
        stickyOptions: {
          stickyClass: 'is-affixed',
          topSpacing: 132,
          bottomSpacing: 132,
          resizeSensor: true
        },
        stickyEventLog: 'static',
        selectOption: [],
        totalPrice: 0,
        tabActive: 1,
        tabTopFixed: false
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

      if (!this.item || this.item.delete_yn === 1) {
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

      document.documentElement.scrollTop = 0
    },
    beforeDestroy () {
      this.$store.commit('SelectOptionReset')
      this.$store.commit('TotalPriceReset')
    },
    destroyed () {
      window.removeEventListener('scroll', this.HandleScroll)
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
        this.qnaPopupShow = true
      },
      async CloseQnaPopup () {
        this.qnaPopupShow = false
        // this.item = {}
        const qnaInfo = await this.QnaLoad()

        this.qnas = qnaInfo.query.item_qna
        this.qnaPage = Number(qnaInfo.query.page)
        this.qnaCount = qnaInfo.query.count
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
          this.$cookies.set('viewProducts', this.item.item_id, '1d')
          const params = {
            item_id: this.item.item_id
          }
          this.$http.put(this.$APIURI + 'itemhit/hit', params)
        }
      },
      async HandleScroll () {
        const introduceTop = document.getElementById('introduce').offsetTop + 400
        const reviewTop = document.getElementById('review').offsetTop + 400
        const qnaTop = document.getElementById('qna').offsetTop + 400
        const refundAboutInfoTop = document.getElementById('refund_about_info').offsetTop + 400

        if (window.pageYOffset >= introduceTop) {
          this.tabActive = 1
        }

        if (window.pageYOffset >= (reviewTop + 500)) {
          this.tabActive = 2
        }

        if (window.pageYOffset >= qnaTop + 500) {
          this.tabActive = 3
        }

        if (window.pageYOffset >= (refundAboutInfoTop + 500)) {
          this.tabActive = 4
        }

        let introduce = document.getElementById('introduce')
        introduce = await introduce.getBoundingClientRect()

        if ((introduce.top - 170) < 0) {
          this.tabTopFixed = true
        } else {
          this.tabTopFixed = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .infor-list > span.non_active {
    color: #f0f0f0 !important;
    text-decoration: line-through;
  }

  @supports (position: sticky) or (position: -webkit-sticky) {
    .sticky {
      position: -webkit-sticky;
      position: sticky;
      top: 130px;
      padding: 10px;
      min-width: 322px;
    }
  }
</style>
