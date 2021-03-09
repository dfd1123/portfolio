<template>
  <div
    id="dg-store-default-wrapper"
    class="dg-page-wrapper"
  >
    <div class="l-store-contents">
      <div class="l-side-navi">
        <StoreInfo
          :store="store"
          :item-cnt="allCount"
        />
      </div>

      <div class="l-con-area">
        <article class="dg-store-main-image">
          <h3 class="dg_blind">
            배너 이미지
          </h3>
          <span
            class="in-image"
            v-if="ConvertImage(store.image).length > 0"
            :style="'background-image: url('+storageUrl + ConvertImage(store.image)[0]+');'"
          ></span>
          <span
            v-else
            class="in-image"
            style="background-image: url(/images/img/thumbnail.png); background-size:auto;background-color:#fafafa;"
          ></span>
        </article>

        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-30">
            <h2 class="in-subject">
              판매중인 상품들
            </h2>
            <ul class="in-sorting">
              <li class="_type">
                <input
                  type="radio"
                  id="popular"
                  class="sort_radio_input display_none"
                  value="popular"
                  v-model="orderBy"
                  @change="Submit"
                  checked
                >
                <label
                  for="popular"
                  class="word"
                >
                  인기순
                </label>
              </li>
              <li class="_type">
                <input
                  type="radio"
                  id="new"
                  class="sort_radio_input display_none"
                  value="new"
                  v-model="orderBy"
                  @change="Submit"
                >
                <label
                  for="new"
                  class="word"
                >
                  신상품순
                </label>
              </li>
              <li class="_type">
                <input
                  type="radio"
                  id="lowPrice"
                  class="sort_radio_input display_none"
                  value="lowPrice"
                  v-model="orderBy"
                  @change="Submit"
                >
                <label
                  for="lowPrice"
                  class="word"
                >
                  낮은 가격순
                </label>
              </li>
              <li class="_type">
                <input
                  type="radio"
                  id="highPrice"
                  class="sort_radio_input display_none"
                  value="highPrice"
                  v-model="orderBy"
                  @change="Submit"
                >
                <label
                  for="highPrice"
                  class="word"
                >
                  높은 가격순
                </label>
              </li>
              <li class="_type">
                <input
                  type="radio"
                  id="rating"
                  class="sort_radio_input display_none"
                  value="rating"
                  v-model="orderBy"
                  @change="Submit"
                >
                <label
                  for="rating"
                  class="word"
                >
                  상품평 순
                </label>
              </li>
            </ul>
          </div>

          <div class="l-contents-group">
            <!-- 2. 찜한 상품 있을 때 -->
            <ul
              v-if="itemLists.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="itemList in itemLists"
                :key="'item'+itemList.item_id"
                class="l-grid-list l-col-4"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="itemList"
                  :search-yn="false"
                  @hash-tag="InputHashTag($event)"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
            <!-- 2. 찜한 상품 있을 때 E -->
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
        </article>
      </div>
    </div>
  </div>
</template>

<script>
  import ItemThumb from '@/components/thumbnail/item-thumb.vue'
  import StoreInfo from '@/components/store/store-info.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      ItemThumb,
      StoreInfo,
      Loading
    },
    data: function () {
      return {
        store: {},
        bottomLoadingShow: false,
        itemLists: [],
        orderBy: 'popular',
        allCount: 0,
        limit: 20,
        offset: 0
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
      async InfiniteLoad () {
        let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight

        if (bottomOfWindow && this.allCount !== this.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          const res = await this.itemLoad()

          this.itemLists = this.itemLists.concat(res.query.items)
          this.offset += res.query.items.length
          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
