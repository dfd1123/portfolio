<template>
  <div
    id="dg-store-default-wrapper"
    class="dg-store-wrapper"
  >
    <div>
      <div class="l-con-area">
        <article class="dg-store-main-image">
          <div class="_page_title_wrap">
            <h2>
              {{ store.brandname }}
              <button
                type="button"
                class="_back_btn"
                @click="$router.go(-1)"
              >
                뒤로가기
              </button>
              <router-link
                :to="'/store/'+store.store_id+'/qna'"
                class="_qna_btn"
              >
                스토어문의
              </router-link>
              <button
                type="button"
                class="_shere_btn"
                v-clipboard="copyData"
                @success="SuccessAlert('URL을 복사하였습니다.')"
                @error="ErrorAlert('URL을 복사에 실패하였습니다.')"
              >
                공유하기
              </button>
            </h2>
          </div>
        </article>

        <StoreInfo
          :store="store"
          :item-cnt="allCount"
        />

        <!-- 2. 스토어 상품보기 -->
        <div
          id="dg-gnb-choice-wrapper"
          class="l-con-article"
          style="min-height: 700px;"
        >
          <!-- 상품정렬 -->
          <div class="l-con-title-group dg-list-lineup_wrap">
            <select
              v-model="orderBy"
              @change="Submit"
              :class="['dg-list-lineup',{'posFixed':posFixed}]"
            >
              <option value="popular">
                인기순
              </option>
              <option value="new">
                신상품 순
              </option>
              <option value="lowPrice">
                낮은 가격 순
              </option>
              <option value="highPrice">
                높은 가격 순
              </option>
              <option value="rating">
                상품평 순
              </option>
            </select>
          </div>
          <!-- 상품정렬 E -->
          <!-- 2. 등록된 상품이 있을 때 -->
          <div
            v-if="itemLists.length > 0"
            class="l-con-area"
          >
            <div class="l-con-article">
              <div class="l-contents-group">
                <ul class="l-grid-group">
                  <li
                    v-for="itemList in itemLists"
                    :key="'item'+itemList.item_id"
                    class="l-grid-list l-col-2"
                  >
                    <!-- * 옷상품 카드 -->
                    <ItemThumb
                      :item-list="itemList"
                      :search-yn="true"
                      @hash-tag="InputHashTag($event)"
                      @popup-open="HashTagPopupOpen"
                    />
                    <!-- * 옷상품 카드 E -->
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- 2. 등록된 상품이 있을 때 E -->
          <!-- 1. 등록된 상품이 없을 때 -->
          <div
            v-else
            class="nothing-history"
          >
            <img
              src="/images/icon/empty_recent.svg"
              alt="icon empty"
              class="in-empty-icon"
            >
            <span class="in-empty-ment">등록된 상품이 없습니다.</span>
          </div>
          <!-- 1. 등록된 상품이 없을 때  E -->
          <div
            class="loading_wrap"
            v-show="bottomLoadingShow"
          >
            <Loading />
          </div>
        </div>
        <!-- 2. 스토어 상품보기 E -->
      </div>
    </div>
    <HashtagPopup
      :hashtag-popup-open="hashtagPopupOpen"
      :show-item="showItem"
      @popup-close="HashTagPopupClose"
    />
  </div>
</template>

<script>
  import ItemThumb from '@/components/thumb/item-thumb.vue'
  import StoreInfo from '@/components/store/store-info.vue'
  import HashtagPopup from '@/components/popup/hashtag-popup.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      ItemThumb,
      StoreInfo,
      HashtagPopup,
      Loading
    },
    data: function () {
      return {
        store: {},
        showItem: {},
        bottomLoadingShow: false,
        itemLists: [],
        orderBy: 'popular',
        allCount: 0,
        limit: 20,
        offset: 0,
        hashtagPopupOpen: false,
        posFixed: false,
        copyData: window.location.href
      }
    },
    props: {
      storeId: {
        type: String,
        required: true
      }
    },
    async created () {
      window.addEventListener('scroll', this.InfiniteLoad)
      window.addEventListener('scroll', this.OrderByScroll)

      this.$store.commit('ProgressShow')
      const res = await this.itemLoad()
      await this.StoreLoad()

      this.itemLists = res.query.items
      this.offset += res.query.items.length
      this.allCount = res.query.count
      this.$store.commit('ProgressHide')
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
      window.removeEventListener('scroll', this.OrderByScroll)
    },
    methods: {
      async StoreLoad () {
        const params = {
          id: this.storeId
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'seller/view', { params })).data
          if (res.state === 1) {
            this.store = res.query
          }
        } catch (e) {
          console.log(e)
        }
      },
      async itemLoad () {
        const params = {
          store_id: this.storeId,
          limit: this.limit,
          offset: this.offset,
          orderBy: this.orderBy
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'items/store_list', { params })).data
          if (res.state === 1) {
            return res
          }
        } catch (e) {
          console.log(e)
        }
      },
      async Submit () {
        this.$store.commit('ProgressShow')
        this.offset = 0
        const res = await this.itemLoad()

        this.itemLists = res.query.items
        this.offset = res.query.items.length
        this.allCount = res.query.count
        this.$store.commit('ProgressHide')
      },
      InputHashTag (hashTag) {
        const idx = this.form.hash_tag.indexOf(hashTag)
        if (idx <= -1) this.form.hash_tag.push(hashTag)
      },
      HashTagPopupOpen (item) {
        document.body.style.overflowY = 'hidden'
        this.showItem = item
        this.hashtagPopupOpen = true
      },
      HashTagPopupClose () {
        document.body.style.overflowY = 'auto'
        this.hashtagPopupOpen = false
        this.showItem = {}
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.allCount !== this.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          const res = await this.itemLoad()

          this.itemLists = this.itemLists.concat(res.query.items)
          this.offset += res.query.items.length
          this.bottomLoadingShow = false
        }
      },
      OrderByScroll () {
        if (window.pageYOffset > document.getElementById('dg-gnb-choice-wrapper').offsetTop) {
          this.posFixed = true
        } else if (window.pageYOffset < document.getElementById('dg-gnb-choice-wrapper').offsetTop) {
          this.posFixed = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
