<template>
  <div id="dg-mypage-current-list-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 최근 본 상품 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-30">
            <h2 class="in-subject">
              최근 본 상품
            </h2>
          </div>

          <div class="l-contents-group">
            <ul
              v-if="itemLists.length > 0"
              class="l-grid-group"
            >
              <li
                v-for="itemList in itemLists"
                :key="'recent'+itemList.item_id"
                class="l-grid-list l-col-4"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb
                  :item-list="itemList"
                  kind="recent"
                />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>

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
      </div>
      <!-- 1) 찜한 상품 E -->
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import ItemThumb from '@/components/thumbnail/item-thumb.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
      ItemThumb
    },
    data: function () {
      return {
        itemLists: []
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.FetchData()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async FetchData () {
        if (localStorage.getItem('items')) {
          this.itemLists = JSON.parse(localStorage.getItem('items'))
          this.itemLists.reverse()
        } else {
          this.itemLists = []
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
