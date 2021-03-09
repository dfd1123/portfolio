<template>
  <div
    class="_popup_wrapper _coupon_popup_wrapper"
    v-infinite-scroll="ItemLoad"
    :infinite-scroll-disabled="busy"
    :infinite-scroll-distance="limit"
  >
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          쿠폰 적용상품
          <button
            type="button"
            class="_close_btn"
            @click="ClosePopup"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <!-- 팝업 타이틀 -->
        <div class="_title">
          <h5 v-if="coupon.cz_subject">
            {{ coupon.cz_subject || '-' }}
          </h5>
          <h5 v-else>
            {{ coupon.cp_subject || '-' }}
          </h5>
          <p
            v-if="this.coupon.cz_start"
            class="_date"
          >
            다운로드 가능 기간: {{ $moment(this.coupon.cz_start).format('YYYY-MM-DD') }} ~ {{ $moment(this.coupon.cz_end).format('YYYY-MM-DD') }}
          </p>
          <p
            v-else
            class="_date"
          >
            사용가능 기간: {{ $moment(this.coupon.cp_start).format('YYYY-MM-DD') }} ~ {{ $moment(this.coupon.cp_end).format('YYYY-MM-DD') }}
          </p>
        </div>
        <!-- 팝업 타이틀 E -->
        <div class="l-con-title-group dg-list-lineup_wrap">
          <select
            v-model="orderBy"
            @change="FetchData"
            class="dg-list-lineup"
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
        <!-- 정렬 셀렉트 E -->
        <div class="l-con-area">
          <div class="l-con-article">
            <div class="l-contents-group">
              <ul class="l-grid-group">
                <li
                  v-for="itemList in itemLists"
                  :key="itemList.item_id"
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
            </div>
          </div>
        </div>
        <!-- 상품목록 E -->
      </div>
    </div>
  </div>
</template>

<script>
  import ItemThumb from '@/components/thumb/item-thumb.vue'
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
      },
      HashTagPopupOpen (itemList) {
        this.$emit('popup-open', itemList)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
