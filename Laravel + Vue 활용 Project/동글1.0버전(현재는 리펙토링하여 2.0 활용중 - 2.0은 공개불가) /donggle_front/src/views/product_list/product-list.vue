<template>
  <!-- content -->
  <div
    id="dg-category-choice-wrapper"
    class="l-page-wrapper"
  >
    <!-- * 카테고리결과 그룹 -->
    <div class="dg-category-result-group">
      <div class="l-con-title-group">
        <h2 class="in-subject">
          카테고리
        </h2>
        <h3 class="in-object">
          {{ CategoryName(nowCategory.ca_name || '-') }}
        </h3>
        <ul
          v-if="upCategorys.length > 0"
          class="in-breadcrumb type01"
        >
          <li class="_list">
            <router-link to="/">
              홈
            </router-link>
            <img
              src="/images/icon/icon_breadcrumb_arrow.svg"
              alt="arrow"
              class="_next"
            >
          </li>
          <li
            v-for="(upCategory, index) in upCategorys"
            :key="'upcate'+index"
            class="_list"
          >
            <!-- TODO: url 주소 바꿔야함! -->
            <router-link :to="'/product/list/'+upCategory.id">
              {{ CategoryName(upCategory.ca_name || '-') }}
            </router-link>
            <img
              src="/images/icon/icon_breadcrumb_arrow.svg"
              alt="arrow"
              class="_next"
            >
          </li>
          <li class="_list active">
            <!-- TODO: url 주소 바꿔야함! -->
            <router-link :to="'/product/list/'+nowCategory.id">
              {{ CategoryName(nowCategory.ca_name || '-') }}
            </router-link>
          </li>
        </ul>
      </div>

      <div class="l-contents-group dg-prdct-sch-related-group">
        <router-link
          v-for="category in nextCategorys"
          :key="'cate'+category.id"
          :to="'/product/list/'+category.id"
          class="rounded-btn-outline"
        >
          {{ CategoryName(category.ca_name || '-') }}
        </router-link>
      </div>
    </div>
    <!-- * 카테고리결과 그룹 E -->

    <!-- * 필터검색 -->
    <FilterWrap
      :prop-form="form"
      :main-colors="mainColors"
      :sub-colors="subColors"
      @search-submit="Submit"
    />
    <!-- * 필터검색 E -->

    <!-- * 상품리스트 구역 -->
    <div class="l-con-area">
      <article class="l-con-article">
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
                v-model="form.orderBy"
                @change="Submit"
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
                v-model="form.orderBy"
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
                v-model="form.orderBy"
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
                v-model="form.orderBy"
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
                v-model="form.orderBy"
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
          <ul
            v-if="itemLists.length > 0"
            class="l-grid-group"
          >
            <li
              v-for="itemList in itemLists"
              :key="'item'+itemList.item_id"
              class="l-grid-list l-col-5"
            >
              <!-- * 옷상품 카드 -->
              <ItemThumb
                :item-list="itemList"
                :url="'/product/list/'+caId"
                :search-yn="true"
                @hash-tag="InputHashTag($event)"
              />
              <!-- * 옷상품 카드 E -->
            </li>
          </ul>
          <div
            v-else
            v-show="created"
            class="non_data"
          >
            <div class="nothing-history">
              <img
                src="/images/icon/empty_recent.svg"
                alt="icon empty"
                class="in-empty-icon"
              >
              <span class="in-empty-ment">검색 결과에 포함되는 상품이<br>없습니다.</span>
            </div>
          </div>
          <div
            class="loading_wrap"
            v-show="bottomLoadingShow"
          >
            <Loading />
          </div>
        </div>
      </article>
    </div>
    <!-- * 상품리스트 구역 E -->
  </div>
  <!-- content E -->
</template>

<script>
  import FilterWrap from '@/components/search/filter/filter-wrap.vue'
  import ItemThumb from '@/components/thumbnail/item-thumb.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      FilterWrap,
      ItemThumb,
      Loading
    },
    data: function () {
      return {
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
      }
    },
    async created () {
      window.addEventListener('scroll', this.InfiniteLoad)

      this.$store.commit('ProgressShow')
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

          const res = (await this.$http.get(this.$APIURI + 'items/search_cateby', { params: this.form })).data
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
        this.form.ca_name = this.$route.query.searchKeyword || ''
        this.form.title = this.$route.query.searchKeyword || ''
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

        if (this.$route.query.searchKeyword) {
          if (this.$route.query.searchKeyword !== '') {
            this.form.hash_tag.push(this.$route.query.searchKeyword)
            this.form.hash_tag = [...new Set(this.form.hash_tag)]
          }
        }
      },
      InputHashTag (hashTag) {
        this.form.hash_tag.push(hashTag)
        this.form.hash_tag = [...new Set(this.form.hash_tag)]
        this.Submit()
      },
      async InfiniteLoad () {
        this.position = document.documentElement.scrollTop
        let top = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop
        let bottomOfWindow = ((top + window.innerHeight) + 50) > document.documentElement.offsetHeight && ((top + window.innerHeight) - 50) < document.documentElement.offsetHeight

        if (bottomOfWindow && this.itemCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          const res = await this.FetchData()

          this.itemLists = this.itemLists.concat(res.query.items)
          this.form.offset += res.query.items.length
          this.$store.commit('ViewOffsetSet', this.form.offset)
          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
  .non_data {
    text-align: center;
    padding: 93px 0;
    font-size: 22px;
    font-weight: bold;
    color: #bbb;
  }
  .dg-prdct-sch-related-group .rounded-btn-outline {
    margin: 4px 3px;
  }
</style>
