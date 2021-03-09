<template>
  <div
    id="dg-category-choice-wrapper"
    class="dg-product-list-wrapper"
  >
    <div class="_page_title_wrap">
      <h2>
        {{ CategoryName(nowCategory.ca_name || '-') }}
        <button
          type="button"
          class="_back_btn"
          @click="$router.go(-1)"
        >
          뒤로가기
        </button>
      </h2>
    </div>
    <!-- * 필터검색 팝업 -->
    <FilterPopup
      :class="{'active':filterPopup}"
      @close-popup="FilterPopupClose"
      :form="form"
      @input-form="form = $event"
      :main-colors="mainColors"
      :sub-colors="subColors"
      @submit="Submit()"
    />
    <!-- 필터검색 팝업 E -->

    <!--
      필터검색과 상품정렬은 스크롤시 상단에 고정됨
      .dg-filter-search_wrap 과 .dg-list-lineup_wrap에 .scroll
     -->
    <!-- 필터검색 -->
    <div class="dg-filter-search_wrap">
      <h2>필터검색</h2>
      <button
        class="dg-filter-search-btn"
        @click="FilterPopupOpen"
      >
        필터검색
      </button>
      <div class="dg-filter-search-list">
        <div class="in-section">
          <button
            v-for="(hashTag, index) in form.hash_tag"
            :key="'hashTag'+index"
            type="button"
            class="rounded-btn-outline"
          >
            <span>#{{ hashTag }}</span>
            <img
              src="/images/btn/btn_cancle_tag.svg"
              @click="DeleteTag(hashTag)"
              alt="cancel tag"
            >
          </button>
        </div>
      </div>
    </div>
    <!-- 필터검색 E -->

    <div class="related-category_wrap">
      <h2>다음 카테고리</h2>
      <div class="dg-filter-search-list">
        <div class="in-section">
          <router-link
            v-for="category in nextCategorys"
            :key="'cate'+category.id"
            :to="'/product/list/'+category.id"
            class="rounded-btn-outline"
          >
            {{ CategoryName(category.ca_name) }}
          </router-link>
        </div>
      </div>
    </div>

    <!-- 상품정렬 -->
    <div class="l-con-title-group dg-list-lineup_wrap">
      <select
        :class="['dg-list-lineup', {'posFixed': posFixed}]"
        v-model="form.orderBy"
        @change="Submit"
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
    <div
      id="product_sorting_area"
      class="l-con-area"
    >
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
                :search-yn="true"
                @hash-tag="InputHashTag($event)"
                @popup-open="HashTagPopupOpen"
              />
              <!-- * 옷상품 카드 E -->
            </li>
          </ul>
          <div
            v-else
            v-show="created && !bottomLoadingShow"
            class="non_data"
          >
            <div class="nothing-history">
              <img
                src="/images/icon/empty_recent.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">검색 결과에 포함되는 상품이 없습니다.</span>
            </div>
          </div>
          <div
            class="loading_wrap"
            v-show="bottomLoadingShow"
          >
            <Loading />
          </div>
        </div>
      </div>
    </div>
    <!-- * 상품리스트 구역 E -->
    <HashtagPopup
      :hashtag-popup-open="hashtagPopupOpen"
      :show-item="showItem"
      @popup-close="HashTagPopupClose"
    />
  </div>
</template>

<script>
  import ItemThumb from '@/components/thumb/item-thumb.vue'
  import FilterPopup from '@/components/popup/filter-popup.vue'
  import HashtagPopup from '@/components/popup/hashtag-popup.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      ItemThumb,
      FilterPopup,
      HashtagPopup,
      Loading
    },
    data: function () {
      return {
        filterPopup: false,
        hashtagPopupOpen: false,
        showItem: {},
        created: false,
        bottomLoadingShow: false,
        upCategorys: [],
        nowCategory: {},
        nextCategorys: [],
        itemLists: [],
        mainColors: [],
        subColors: [],
        itemCount: 0,
        form: {
          ca_id: this.caId,
          title: '',
          gender: '',
          age: [],
          color: [],
          min_price: null,
          max_price: null,
          max_size: 150,
          min_size: 40,
          hash_tag: [],
          orderBy: 'popular',
          offset: 0,
          limit: 30
        },
        posFixed: false,
        position: 0
      }
    },
    props: {
      caId: {
        type: String,
        required: true
      }
    },
    watch: {
      async caId () {
        this.$store.commit('ProgressShow')
        await this.Category()
        this.form.offset = 0
        const res = await this.FetchData()
        await this.ColorListLoad()

        this.itemLists = res.query.items
        this.form.offset = res.query.items.length

        this.$store.commit('ProgressHide')
      },
      '$route.query.filterpop' () {
        if (this.$route.query.filterpop === 1) {
          document.body.style.overflowY = 'hidden'
          this.filterPopup = true
        } else if (this.$route.query.filterpop === undefined) {
          document.body.style.overflowY = 'auto'
          this.filterPopup = false
        }
      }
    },
    async created () {
      delete this.form.filterpop
      window.addEventListener('scroll', this.InfiniteLoad)
      window.addEventListener('scroll', this.OrderByScroll)

      this.$store.commit('ProgressShow')
      this.$router.replace({ name: 'product-list', query: this.form })
      await this.SetForm()
      await this.Category()
      await this.ColorListLoad()

      this.form.offset = 0
      const res = await this.FetchData()

      this.itemLists = res.query.items
      this.form.offset = res.query.items.length

      this.created = true

      if (this.$store.state.beforUrl.includes('/product/view/') && this.position !== 0) {
        document.documentElement.scrollTop = this.$store.state.position
        this.$store.commit('ResetPosition')
        this.position = 0
      }

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
      async FetchData () {
        this.form.ca_id = this.caId

        try {
          const params = this.form

          if (this.$store.state.beforUrl.includes('/product/view/') && this.$store.state.offset && this.$store.state.position !== 0) {
            params.limit = this.$store.state.offset
            this.position = this.$store.state.position
          }

          const res = (await this.$http.get(this.$APIURI + 'items/search_cateby', { params })).data
          if (res.state === 1) {
            this.itemCount = res.query.count

            return res
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async Category () {
        try {
          const res = (await this.$http.get(this.$APIURI + 'category/level_search', { params: { id: this.caId } })).data

          if (res.state === 1) {
            this.upCategorys = res.query.up_category
            this.nowCategory = res.query.now_categorys
            this.nextCategorys = res.query.next_categorys

            if (this.nowCategory.ca_use !== 1) {
              this.$http.get(this.$APIURI + 'category/main_list').then(res => {
                const response = res.data
                if (response.state !== 1) {
                  if (response.state === 0) {
                    this.$store.commit('CategorySet', [])
                  }
                  console.log(response.msg)
                } else {
                  this.$store.commit('CategorySet', response.query)
                }
              }).catch(e => {
                console.log(e)
              })

              const result = await this.WarningAlert('해당 카테고리는 삭제된 카테고리 입니다.')

              if (result) {
                this.$router.go(-1)
              }
            }
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async Submit () {
        this.$store.commit('ProgressShow')

        this.form.offset = 0
        delete this.form.filterpop
        const res = await this.FetchData()
        this.itemLists = res.query.items
        this.itemCount = res.query.count
        this.form.offset += res.query.items.length

        this.$router.replace({ name: 'product-list', query: this.form })

        this.$store.commit('ProgressHide')
      },
      async ColorListLoad () {
        try {
          const res = (await this.$http.get(this.$APIURI + 'color')).data

          if (res.state === 1) {
            this.mainColors = res.query.main_colors
            this.subColors = res.query.sub_colors
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      SetForm () {
        if (this.$route.query.gender) {
          this.form.gender = this.$route.query.gender
        }
        this.form.min_price = Number(this.$route.query.min_price) || null
        this.form.max_price = Number(this.$route.query.max_price) || null
        this.form.min_size = Number(this.$route.query.min_size) || 20
        this.form.max_size = Number(this.$route.query.max_size) || 150
        this.form.orderBy = this.$route.query.orderBy || 'popular'
        this.form.offset = this.$route.query.offset || 0
        this.form.limit = this.$route.query.limit || 30

        if (this.$route.query.age) {
          if (typeof (this.$route.query.age) === 'string') {
            this.form.age = this.$route.query.age.split()
          } else {
            this.form.age = [...new Set(this.$route.query.age)] || []
          }
        }

        if (this.$route.query.color) {
          if (typeof (this.$route.query.color) === 'string') {
            this.form.color = this.$route.query.color.split()
          } else {
            this.form.color = [...new Set(this.$route.query.color)] || []
          }
        }

        if (this.$route.query.hash_tag) {
          if (typeof (this.$route.query.hash_tag) === 'string') {
            this.form.hash_tag = this.$route.query.hash_tag.split()
          } else {
            this.form.hash_tag = [...new Set(this.$route.query.hash_tag)] || []

            const idx = this.form.hash_tag.indexOf('')
            if (idx <= -1) this.form.hash_tag.splice(idx, 1)
          }
        }
      },
      InputHashTag (hashTag) {
        this.form.hash_tag.push(hashTag)
        this.form.hash_tag = [...new Set(this.form.hash_tag)]
        this.Submit()
      },
      DeleteTag (tag) {
        const idx = this.form.hash_tag.indexOf(tag)
        if (idx > -1) this.form.hash_tag.splice(idx, 1)

        this.Submit()
      },
      async InfiniteLoad () {
        this.position = document.documentElement.scrollTop
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 50) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 50) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.itemCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          const res = await this.FetchData()

          this.itemLists = this.itemLists.concat(res.query.items)
          this.form.offset += res.query.items.length
          this.$store.commit('ViewOffsetSet', this.form.offset)
          this.bottomLoadingShow = false
        }
      },
      FilterPopupOpen () {
        document.body.style.overflowY = 'hidden'
        const form = this.form
        form.filterpop = 1

        this.$router.push({ name: 'product-list', query: { filterpop: 1 } })
      },
      FilterPopupClose () {
        document.body.style.overflowY = 'auto'
        delete this.form.filterpop
        this.$router.replace({ name: 'product-list', query: {} })
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
      OrderByScroll () {
        if (window.pageYOffset > document.getElementById('product_sorting_area').offsetTop) {
          this.posFixed = true
        } else if (window.pageYOffset < document.getElementById('product_sorting_area').offsetTop) {
          this.posFixed = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
