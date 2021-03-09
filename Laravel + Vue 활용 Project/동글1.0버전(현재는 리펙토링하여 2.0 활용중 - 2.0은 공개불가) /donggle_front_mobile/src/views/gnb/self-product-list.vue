<template>
  <div>
    <Header />
    <div
      id="dg-gnb-choice-wrapper"
      class="dg-product-list-wrapper"
    >
      <!--
      상품정렬은 스크롤시 상단에 고정됨
      .dg-list-lineup_wrap에 .scroll
     -->
      <!-- 상품정렬 -->
      <div class="l-con-title-group dg-list-lineup_wrap">
        <select
          id="orderBy"
          :class="['dg-list-lineup', {'posFixed': posFixed}]"
          v-model="form.orderBy"
          @change="Submit()"
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
      <!-- * 상품리스트 구역 -->
      <div class="l-con-area">
        <div class="l-con-article">
          <div class="l-contents-group">
            <ul
              v-if="itemLists.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="itemList in itemLists"
                :key="'item'+itemList.item_id"
                class="l-grid-list l-col-2"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="itemList"
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
              국내자체제작으로 등록된 상품이 아직 없습니다.
            </div>
          </div>
        </div>
      </div>
      <!-- * 상품리스트 구역 E -->
    </div>
    <HashtagPopup
      :hashtag-popup-open="hashtagPopupOpen"
      :show-item="showItem"
      @popup-close="HashTagPopupClose"
    />
  </div>
</template>

<script>
  import Header from '@/components/common/header/home/header.vue'
  import ItemThumb from '@/components/thumb/item-thumb.vue'
  import HashtagPopup from '@/components/popup/hashtag-popup.vue'

  export default {
    components: {
      Header,
      ItemThumb,
      HashtagPopup
    },
    data: function () {
      return {
        hashtagPopupOpen: false,
        showItem: {},
        created: false,
        form: {
          itemCount: this.$route.query.itemCount || 0,
          orderBy: this.$route.query.orderBy || 'popular',
          offset: this.$route.query.offset || 0,
          limit: this.$route.query.limit || 30
        },
        itemLists: [],
        posFixed: false,
        position: 0
      }
    },
    async created () {
      this.$store.commit('ProgressShow')

      const res = await this.ItemLoad()
      this.itemLists = res.query.items
      this.form.itemCount = res.query.count
      this.form.offset += res.query.items.length

      window.addEventListener('scroll', this.InfiniteLoad)
      window.addEventListener('scroll', this.OrderByScroll)

      this.created = true

      this.$nextTick(function () {
        if (this.$store.state.beforUrl.includes('/product/view/') && this.$store.state.position !== 0) {
          document.documentElement.scrollTop = this.$store.state.position
          this.$store.commit('ResetPosition')
        }
      })

      this.$store.commit('ProgressHide')
    },
    beforeDestroy () {
      this.$store.commit('SavePosition', this.position)
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
      window.removeEventListener('scroll', this.OrderByScroll)
    },
    methods: {
      async ItemLoad () {
        try {
          const params = this.form

          if (this.$store.state.beforUrl.includes('/product/view/') && this.$store.state.offset && this.$store.state.position !== 0) {
            params.limit = this.$store.state.offset
          }

          const res = (await this.$http.get(this.$APIURI + 'items/self_list', { params })).data

          return res
        } catch (e) {
          console.log(e)
        }
      },
      async Submit () {
        this.$store.commit('ProgressShow')

        const params = {
          orderBy: this.form.orderBy
        }
        this.$router.replace({ name: 'self-product-list', query: params })
        this.form.offset = 0
        const res = await this.ItemLoad()
        this.itemLists = res.query.items
        this.form.itemCount = res.query.count
        this.form.offset += res.query.items.length

        this.$store.commit('ProgressHide')
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
        this.position = document.documentElement.scrollTop
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 50) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 50) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.form.itemCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          const res = await this.ItemLoad()

          this.itemLists = this.itemLists.concat(res.query.items)
          this.form.itemCount = res.query.count
          this.form.offset += res.query.items.length
          this.$store.commit('ViewOffsetSet', this.form.offset)
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
  .dg-list-lineup {
    &.posFixed {
      position: fixed;
      top: 50px;
      left: 0;
      width: 100%;
      border-radius: 0;
      border: none;
      border-bottom: 1px solid #f0f0f0;
      z-index: 2;
    }
  }
</style>
