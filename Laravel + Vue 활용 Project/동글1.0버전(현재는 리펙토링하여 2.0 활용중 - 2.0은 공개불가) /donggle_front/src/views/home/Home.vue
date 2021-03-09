<template>
  <div id="dg-home-wrapper">
    <MainBanner />

    <!-- 하위 컨텐츠 구역 -->
    <div class="l-page-wrapper">
      <!-- 1) 실시간 BEST -->
      <article class="l-con-article">
        <div class="l-con-title-group type-main">
          <h2 class="in-subject">
            실시간 BEST
          </h2>
          <router-link
            class="in-more"
            to="/product/list/best"
          >
            <span>더보기</span>
            <img
              src="/images/btn/btn_more.svg"
              alt="btn"
            >
          </router-link>
        </div>

        <div class="l-contents-group">
          <div class="l-grid-group best-list-slide best-main-lists">
            <SlickItem
              v-if="bestLists.length > 0"
              :slick-options="bestSlickOption"
              :item-lists="bestLists"
            />
            <div
              v-else
              v-show="created"
              class="non_data"
            >
              인기상품(BEST) 상품이<br>없습니다..ㅠㅠ
            </div>
          </div>
        </div>
      </article>
      <!-- 1) E -->

      <!-- 2) 인기 #해시태그 상품 -->
      <article class="l-con-article">
        <div class="l-con-title-group type-main">
          <h2 class="in-subject">
            주간신상(Weekly New)
          </h2>
          <router-link
            class="in-more"
            to="/product/list/weeklyNew"
          >
            <span>더보기</span>
            <img
              src="/images/btn/btn_more.svg"
              alt="btn"
            >
          </router-link>
        </div>

        <div class="l-contents-group">
          <div class="l-grid-group popular-main-lists">
            <SlickItem
              v-if="weekLists.length > 0"
              :slick-options="weekSlickOption"
              :item-lists="weekLists"
            />
            <div
              v-else
              v-show="created"
              class="non_data"
            >
              주간신상 상품이<br>없습니다..ㅠㅠ
            </div>
          </div>
        </div>
      </article>
      <!-- 2) E -->

      <!-- 3) 오늘 신상 -->
      <article class="l-con-article">
        <div class="l-con-title-group type-main">
          <h2 class="in-subject">
            국내자체제작
          </h2>
          <router-link
            class="in-more"
            to="/product/list/self"
          >
            <span>더보기</span>
            <img
              src="/images/btn/btn_more.svg"
              alt="btn"
            >
          </router-link>
        </div>

        <div class="l-contents-group">
          <div class="l-grid-group today-new-main-lists">
            <SlickItem
              v-if="selfLists.length > 0"
              :slick-options="selfSlickOption"
              :item-lists="selfLists"
            />
            <div
              v-else
              v-show="created"
              class="non_data"
            >
              국내 자체 제작 상품이<br>없습니다..ㅠㅠ
            </div>
          </div>
        </div>
      </article>
      <!-- 3) E -->

      <!-- 4) 추천 스토어 -->
      <article class="l-con-article l-con-article--main-recmnd">
        <div class="l-con-title-group type-main">
          <h2 class="in-subject">
            추천 스토어
          </h2>
        </div>

        <div class="l-contents-group best-store-main-lists">
          <SlickStore
            v-if="recomendStore.length > 0"
            :slick-options="recomendOption"
            :store-lists="recomendStore"
          />
          <div
            v-else
            v-show="created"
            class="non_data"
          >
            추천 스토어가<br>없습니다..ㅠㅠ
          </div>
        </div>
      </article>
      <!-- 4) E -->
    </div>
    <!-- 하위 컨텐츠 구역 E -->

    <MainPopup
      v-for="popup in popupList"
      :key="popup.id"
      :popup-id="popup.id"
      :image="(JSON.parse(popup.pc_img || null) || [''])[0]"
      :link="popup.link"
    />
  </div>
</template>

<script>
  import MainBanner from '@/components/banner/main-banner.vue'
  import SlickItem from '@/components/slick/slick-item.vue'
  import SlickStore from '@/components/slick/slick-store.vue'
  import MainPopup from '@/components/popup/main-popup.vue'

  export default {
    components: {
      MainBanner,
      SlickItem,
      SlickStore,
      MainPopup
    },
    data: function () {
      return {
        created: false,
        bestLists: [],
        weekLists: [],
        selfLists: [],
        recomendStore: [],
        popupList: [],
        bestSlickOption: {
          dots: true,
          // slidesPerRow: 5,
          slidesToShow: 5,
          slidesToScroll: 1,
          rows: 2,
          swipe: false,
          responsive: [
            {
              breakpoint: 478,
              settings: {
                slidesPerRow: 1,
                rows: 1
              }
            }
          ],
          autoplay: true,
          autoplaySpeed: 3000,
          infinite: true,
          arrows: true
        },
        weekSlickOption: {
          dots: true,
          // slidesPerRow: 5,
          slidesToShow: 5,
          slidesToScroll: 1,
          rows: 2,
          swipe: false,
          responsive: [
            {
              breakpoint: 478,
              settings: {
                slidesPerRow: 1,
                rows: 1
              }
            }
          ],
          autoplay: true,
          autoplaySpeed: 2900,
          infinite: true,
          arrows: true
        },
        selfSlickOption: {
          dots: true,
          // slidesPerRow: 5,
          slidesToShow: 5,
          slidesToScroll: 1,
          rows: 2,
          swipe: false,
          responsive: [
            {
              breakpoint: 478,
              settings: {
                slidesPerRow: 1,
                rows: 1
              }
            }
          ],
          autoplay: true,
          autoplaySpeed: 2800,
          infinite: true,
          arrows: true
        },
        recomendOption: {
          dots: true,
          // slidesPerRow: 5,
          slidesToShow: 1,
          slidesToScroll: 1,
          rows: 1,
          swipe: false,
          autoplay: true,
          autoplaySpeed: 3000,
          infinite: true,
          arrows: true
        }
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.fetchData()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async fetchData () {
        try {
          const [res1, res2, res3, res4, res5] = (await Promise.all([
            this.$http.get(this.$APIURI + 'items/best_list', { params: { limit: 20 } }),
            this.$http.get(this.$APIURI + 'items/week_list', { params: { limit: 20 } }),
            this.$http.get(this.$APIURI + 'items/self_list', { params: { limit: 20 } }),
            this.$http.get(this.$APIURI + 'seller/recomend'),
            this.$http.get(this.$APIURI + 'popup/list')
          ])).map(res => res.data)

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

          this.bestLists = res1.query.items
          this.weekLists = res2.query.items
          this.selfLists = res3.query.items
          this.recomendStore = res4.query
          this.popupList = res5.query.popups

          this.created = true
        } catch (e) {
          console.log(e)
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .non_data {
    text-align: center;
    padding: 107px 0;
  }
</style>
