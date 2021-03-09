<template>
  <div class="_popup_wrapper _coupon_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap _popup_wrap_930">
      <div class="_popup_title">
        <h4>
          쿠폰 적용 상품
          <button
            type="button"
            class="_close_btn"
            @click="ClosePopup"
          >
          </button>
        </h4>
      </div>
      <div
        class="_popup_content over-y_scroll"
        v-infinite-scroll="ItemLoad"
        :infinite-scroll-disabled="busy"
        :infinite-scroll-distance="limit"
      >
        <div class="_popup_coupon_table_wrap">
          <table class="_popup_coupon_sort_table">
            <caption class="dg_blind">
              선택 쿠폰
            </caption>
            <thead class="dg_blind">
              <tr>
                <th>종류</th>
                <th>내용</th>
              </tr>
            </thead>
            <tbody>
              <tr class="_popup_coupon_table_con">
                <th>쿠폰명</th>
                <td v-if="coupon.cz_subject">
                  {{ coupon.cz_subject || '-' }}
                </td>
                <td v-else>
                  {{ coupon.cp_subject || '-' }}
                </td>
              </tr>
              <tr class="_popup_coupon_table_con">
                <th>다운로드 가능 기간</th>
                <td v-if="this.coupon.cz_start">
                  {{ $moment(this.coupon.cz_start).format('YYYY-MM-DD') }} ~ {{ $moment(this.coupon.cz_end).format('YYYY-MM-DD') }}
                </td>
                <td v-else>
                  {{ $moment(this.coupon.cp_start).format('YYYY-MM-DD') }} ~ {{ $moment(this.coupon.cp_end).format('YYYY-MM-DD') }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="_popup_coupon_list">
          <!-- 상품 정렬 리스트 -->
          <div class="l-con-title-group type01">
            <h2 class="in-subject">
              상품리스트
            </h2>
            <ul class="in-sorting">
              <li class="_type">
                <input
                  type="radio"
                  id="popular"
                  class="sort_radio_input display_none"
                  value="popular"
                  v-model="orderBy"
                  @change="FetchData"
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
                  @change="FetchData"
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
                  @change="FetchData"
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
                  @change="FetchData"
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
                  @change="FetchData"
                >
                <label
                  for="review"
                  class="word"
                >
                  상품평 순
                </label>
              </li>
            </ul>
          </div>
          <!-- 상품 정렬 리스트 E -->
          <!-- 상품 리스트 -->
          <div class="l-contents-group">
            <ul class="l-grid-group">
              <!-- 1페이지당 4*5개씩 보이기 -->
              <li
                v-for="itemList in itemLists"
                :key="itemList.item_id"
                class="l-grid-list l-col-4"
              >
                <!-- * 옷상품 카드 -->
                <ItemThumb :item-list="itemList" />
                <!-- * 옷상품 카드 E -->
              </li>
            </ul>
          </div>
          <!-- 상품 리스트 E -->
          <!-- pagination 자리 -->
          <div class="_board_pager">
          </div>
          <!-- pagination 자리 E -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import ItemThumb from '@/components/thumbnail/item-thumb.vue'
  import infiniteScroll from 'vue-infinite-scroll'

  export default {
    directives: { infiniteScroll },
    components: {
      ItemThumb
    },
    data: function () {
      return {
        orderBy: 'popular',
        itemLists: [],
        count: 0,
        limit: 20,
        offset: 0,
        busy: false
      }
    },
    props: {
      coupon: {
        type: Object,
        required: true
      }
    },
    watch: {
      coupon () {
        this.FetchData()
      }
    },
    methods: {
      async FetchData () {
        let res = []
        let params = {}
        if (this.coupon.cp_method === 0) {
          params.item_id = Number(this.coupon.cp_target)
          params.limit = this.limit
          params.orderBy = this.orderBy
          this.offset = 0

          try {
            res = (await this.$http.get(this.$APIURI + 'items/search_list', { params })).data
            if (res.state === 1) {
              this.itemLists = res.query.items
              this.count = res.query.count
              this.offset += res.query.items.length
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        } else if (this.coupon.cp_method === 1) {
          params.ca_id = this.coupon.cp_target
          params.limit = this.limit
          params.orderBy = this.orderBy
          this.offset = 0

          try {
            res = (await this.$http.get(this.$APIURI + 'items/search_cateby', { params })).data
            if (res.state === 1) {
              this.itemLists = res.query.items
              this.count = res.query.count
              this.offset += res.query.items.length
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        }
      },
      async ItemLoad () {
        if (this.offset === this.count) {
          return false
        }

        let res = []
        let params = {}
        if (this.coupon.cp_method === 0) {
          params.item_id = Number(this.coupon.cp_target)
          params.limit = this.limit
          params.orderBy = this.orderBy
          params.offset = this.offset

          try {
            res = (await this.$http.get(this.$APIURI + 'items/search_list', { params })).data
            if (res.state === 1) {
              this.itemLists = res.query.items
              this.count = res.query.count
              this.offset += res.query.items.length
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        } else if (this.coupon.cp_method === 1) {
          params.ca_id = this.coupon.cp_target
          params.limit = this.limit
          params.orderBy = this.orderBy
          params.offset = this.offset

          try {
            res = (await this.$http.get(this.$APIURI + 'items/search_cateby', { params })).data
            if (res.state === 1) {
              this.itemLists = res.query.items
              this.count = res.query.count
              this.offset += res.query.items.length
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        }
      },
      ClosePopup () {
        this.offset = 0
        this.count = 0
        this.orderBy = 'popular'
        this.itemLists = []

        this.$emit('close-popup')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
