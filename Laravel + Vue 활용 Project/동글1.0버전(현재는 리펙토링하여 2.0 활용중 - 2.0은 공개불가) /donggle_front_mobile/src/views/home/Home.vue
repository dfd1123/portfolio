<template>
  <div>
    <Header />
    <!-- content -->
    <div id="dg-home-wrapper">
      <!-- 위로가기버튼 -->
      <div
        class="_go_top"
        v-scroll-to="'#app'"
      >
        <button>위로가기</button>
      </div>
      <!-- 위로가기버튼 E -->
      <!-- 해시태그 popup: ._hashlist-con
        ._hashlist-more 누르면 열리고,
      ._hashtag_bg 이나 ._hashtag_close 터치시 닫힘-->
      <!-- 배너 -->
      <MainBanner />
      <!-- 배너 E -->
      <div class="dg-main-contents-wrapper">
        <!-- 각 상품 리스트들은 최대 20개까지 슬라이드됨 -->
        <!-- 실시간 베스트 -->
        <section class="main-slide-wrap best-product-wrap">
          <h2>실시간 BEST</h2>
          <router-link
            to="/product/list/best"
            class="in-more"
          >
            <span>더보기</span>
            <img
              src="/images/btn/btn_more.svg"
              alt="btn"
            >
          </router-link>
          <div class="best-product-scroll">
            <ul
              v-if="bestListsOne.length > 0"
              class="l-grid-group"
            >
              <!-- 갯수가 늘어나면 x축으로 스크롤 -->
              <li
                v-for="(bestList, index) in bestListsOne"
                :key="'bestLists'+index"
                class="l-grid-list"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="bestList"
                  @popup-open="HashTagPopupOpen"
                  style="padding-bottom: 5px;"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <ul
              v-if="bestListsTwo.length > 0"
              class="l-grid-group"
            >
              <!-- 갯수가 늘어나면 x축으로 스크롤 -->
              <li
                v-for="(bestList, index) in bestListsTwo"
                :key="'bestLists'+index"
                class="l-grid-list"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="bestList"
                  @popup-open="HashTagPopupOpen"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <div
              v-else
              v-show="created"
              class="non_data"
            >
              실시간 BEST 상품이 없습니다..ㅠㅠ
            </div>
          </div>
        </section>
        <!-- 실시간 베스트 E -->
        <!-- 인기해시태그 -->
        <section class="main-slide-wrap best-product-wrap">
          <h2>주간 신상(Weekly New)</h2>
          <router-link
            to="/product/list/weeklyNew"
            class="in-more"
          >
            <span>더보기</span>
            <img
              src="/images/btn/btn_more.svg"
              alt="btn"
            >
          </router-link>
          <div class="best-product-scroll">
            <ul
              v-if="weekListsOne.length > 0"
              class="l-grid-group"
            >
              <!-- 갯수가 늘어나면 x축으로 스크롤 -->
              <li
                v-for="weekList in weekListsOne"
                :key="'weekLists'+weekList.item_id"
                class="l-grid-list"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="weekList"
                  @popup-open="HashTagPopupOpen"
                  style="padding-bottom: 5px;"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <ul
              v-if="weekListsTwo.length > 0"
              class="l-grid-group"
            >
              <!-- 갯수가 늘어나면 x축으로 스크롤 -->
              <li
                v-for="weekList in weekListsTwo"
                :key="'weekLists'+weekList.item_id"
                class="l-grid-list"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="weekList"
                  @popup-open="HashTagPopupOpen"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <div
              v-else
              v-show="created"
              class="non_data"
            >
              주간 신상 상품이 없습니다..ㅠㅠ
            </div>
          </div>
        </section>
        <!-- 인기해시태그 E -->
        <!-- 오늘신상 -->
        <section class="main-slide-wrap best-product-wrap">
          <h2>국내자체제작</h2>
          <router-link
            to="/product/list/self"
            class="in-more"
          >
            <span>더보기</span>
            <img
              src="/images/btn/btn_more.svg"
              alt="btn"
            >
          </router-link>
          <div class="best-product-scroll">
            <ul
              v-if="selfListsOne.length > 0"
              class="l-grid-group"
            >
              <!-- 갯수가 늘어나면 x축으로 스크롤 -->
              <li
                v-for="selfList in selfListsOne"
                :key="'selfLists'+selfList.item_id"
                class="l-grid-list"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="selfList"
                  @popup-open="HashTagPopupOpen"
                  style="padding-bottom: 5px;"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <ul
              v-if="selfListsTwo.length > 0"
              class="l-grid-group"
            >
              <!-- 갯수가 늘어나면 x축으로 스크롤 -->
              <li
                v-for="selfList in selfListsTwo"
                :key="'selfLists'+selfList.item_id"
                class="l-grid-list"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="selfList"
                  @popup-open="HashTagPopupOpen"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <div
              v-else
              v-show="created"
              class="non_data"
            >
              국내 자체 제작 상품이 없습니다..ㅠㅠ
            </div>
          </div>
        </section>
        <!-- 오늘신상 E -->
        <!-- 추천 스토어 -->
        <section class="bast-store-wrap">
          <h2>추천 스토어</h2>
          <div class="recommend-store-wrap">
            <!-- 5개 보이기 -->
            <SlickStore
              v-if="recomendStore.length > 0"
              :slick-options="recomendOption"
              :store-lists="recomendStore"
            />
          </div>
        </section>
        <!-- 추천 스토어 E -->
        <HashtagPopup
          :hashtag-popup-open="hashtagPopupOpen"
          :show-item="showItem"
          @popup-close="HashTagPopupClose"
        />
      </div>
    </div>
    <!-- content E -->

    <MainPopup
      v-for="popup in popupList"
      :key="popup.id"
      :popup-id="popup.id"
      :image="(JSON.parse(popup.mb_img || null) || [''])[0]"
      :link="popup.link"
    />
  </div>
</template>

<script>
  import Header from '@/components/common/header/home/header.vue'
  import MainBanner from '@/components/banner/main-banner.vue'
  import ItemThumb from '@/components/thumb/item-thumb.vue'
  import SlickStore from '@/components/slick/slick-store.vue'
  import HashtagPopup from '@/components/popup/hashtag-popup.vue'
  import MainPopup from '@/components/popup/main-popup.vue'

  export default {
    components: {
      Header,
      MainBanner,
      ItemThumb,
      SlickStore,
      HashtagPopup,
      MainPopup
    },
    data: function () {
      return {
        created: false, // created lifecicle hook이 다 작동 되었는지 판별하는 변수
        hashtagPopupOpen: false, // hash tag를 클릭했을 경우 해당 상품의 hash tag들이 보이는 팝업의 노출 유무를 나타내는 변수
        showItem: {}, // hash tag 팝업 컴포넌트에 들어가는 상품 정보를 담는 Object
        bestLists: [], // 인기상품 리스트들이 들어가는 Array 변수
        bestListsOne: [],
        bestListsTwo: [],
        weekLists: [], // 주간신상 상품 리스트들이 들어가는 Array 변수
        weekListsOne: [],
        weekListsTwo: [],
        selfLists: [], // 국내자체제작 상품 리스트들이 들어가는 Array 변수
        selfListsOne: [],
        selfListsTwo: [],
        recomendStore: [], // 추천 스토어 리스트들이 들어가는 Array 변수
        popupList: [], // 메인 팝업
        recomendOption: {
          // 추천 스토어 리스트들에서 작동되는 slick-slider option Object
          infinite: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          mobileFirst: true,
          arrows: false,
          dots: true,
          autoplaySpeed: 2000,
          swipe: true,
          responsive: [
            {
              breakpoint: 769,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                mobileFirst: true,
                arrows: false,
                dots: true
              }
            }
          ]
        }
      }
    },
    async created () {
      this.$store.commit('ProgressShow') // Progress Loading 보이기(Vuex)
      await this.fetchData() // 상품 및 스토어 Data Loading
      this.$store.commit('ProgressHide') // Progress Loading 숨기기(Vuex)
    },
    methods: {
      async fetchData () {
        // 상품 및 스토어 Data Load Function
        try {
          const [res1, res2, res3, res4, res5] = (
            await Promise.all([
              this.$http.get(this.$APIURI + 'items/best_list', {
                params: { limit: 20 }
              }),
              this.$http.get(this.$APIURI + 'items/week_list', {
                params: { limit: 20 }
              }),
              this.$http.get(this.$APIURI + 'items/self_list', {
                params: { limit: 20 }
              }),
              this.$http.get(this.$APIURI + 'seller/recomend'),
              this.$http.get(this.$APIURI + 'popup/list')
            ])
          ).map(res => res.data)

          if (res1.state !== 1) {
            console.log(res1.msg)
          }

          if (res2.state !== 1) {
            console.log(res2.msg)
          }

          if (res3.state !== 1) {
            console.log(res3.msg)
          }

          if (res4.state !== 1) {
            console.log(res4.msg)
          }

          if (res5.state !== 1) {
            console.log(res5.msg)
          }

          if (res1.query.items.length >= 20) {
            res1.query.items.forEach((item, index) => {
              if (index < 10) {
                this.bestListsOne.push(item)
              } else {
                this.bestListsTwo.push(item)
              }
            })
          } else {
            this.bestListsOne = res1.query.items
          }

          if (res2.query.items.length >= 20) {
            res2.query.items.forEach((item, index) => {
              if (index < 10) {
                this.weekListsOne.push(item)
              } else {
                this.weekListsTwo.push(item)
              }
            })
          } else {
            this.weekListsOne = res2.query.items
          }

          if (res3.query.items.length >= 20) {
            res3.query.items.forEach((item, index) => {
              if (index < 10) {
                this.selfListsOne.push(item)
              } else {
                this.selfListsTwo.push(item)
              }
            })
          } else {
            this.selfListsOne = res3.query.items
          }

          /*
          this.bestLists = res1.query.items
          this.weekLists = res2.query.items
          this.selfLists = res3.query.items
          */

          this.recomendStore = res4.query
          this.popupList = res5.query.popups

          this.created = true
        } catch (e) {
          console.log(e)
        }
      },
      HashTagPopupOpen (item) {
        // hash tag 팝업 Open
        document.body.style.overflowY = 'hidden'
        this.showItem = item
        this.hashtagPopupOpen = true
      },
      HashTagPopupClose () {
        // hash tag 팝업 Close
        document.body.style.overflowY = 'auto'
        this.hashtagPopupOpen = false
        this.showItem = {}
      }
    }
  }
</script>

<style lang="scss" scoped>
  .non_data {
    text-align: center;
    padding: 93px 0;
    font-size: 1.2rem;
    font-weight: bold;
    color: #bbb;
  }
</style>
