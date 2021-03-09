<template>
  <!-- content -->
  <div
    id="dg-product-search-wrapper"
    class="l-page-wrapper"
  >
    <!-- * 검색결과 알림 -->
    <div class="dg-prdct-sch-tit-group">
      <h2 class="in-title">
        {{ (form.searchKeyword !== '')? "'"+form.searchKeyword+"'":'전체' }} 검색결과
      </h2>
      <span class="in-text">총 <b>{{ NumberFormat(itemCount) }}</b>건의 상품이 검색되었습니다.</span>

      <ul class="in-breadcrumb type01">
        <li class="_list">
          <a href="/">홈</a>
          <img
            src="/images/icon/icon_breadcrumb_arrow.svg"
            alt="arrow"
            class="_next"
          >
        </li>
        <li class="_list active">
          <!-- TODO: url 주소 바꿔야하면 바꿔야함! -->
          <a href="/total/search">검색결과</a>
        </li>
      </ul>
    </div>
    <!-- * 검색결과 알림 E -->

    <!-- * 연관카테고리 그룹 -->
    <div
      class="dg-prdct-sch-related-group"
      v-if="categorys.length !== 0 && created"
    >
      <div class="l-con-title-group type01">
        <h2 class="in-subject">
          검색된 카테고리
        </h2>
      </div>

      <div class="l-contents-group dg-prdct-sch-related-group">
        <router-link
          v-for="category in categorys"
          :key="'cate'+category.id"
          :to="'/product/list/'+category.id"
          class="rounded-btn-outline"
        >
          {{ category.ca_name }}
        </router-link>
      </div>
    </div>
    <!-- * 연관카테고리 그룹 E -->

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
            <input
              type="radio"
              id="popular"
              class="sorting-checkbox display_none"
              value="popular"
              v-model="form.orderBy"
              @change="Submit"
            >
            <li class="_type">
              <label
                for="popular"
                class="word"
              >
                인기순
              </label>
            </li>
            <input
              type="radio"
              id="new"
              class="sorting-checkbox display_none"
              value="new"
              v-model="form.orderBy"
              @change="Submit"
            >
            <li class="_type">
              <label
                for="new"
                class="word"
              >
                신상품순
              </label>
            </li>
            <input
              type="radio"
              id="lowPrice"
              class="sorting-checkbox display_none"
              value="lowPrice"
              v-model="form.orderBy"
              @change="Submit"
            >
            <li class="_type">
              <label
                for="lowPrice"
                class="word"
              >
                낮은 가격순
              </label>
            </li>
            <input
              type="radio"
              id="highPrice"
              class="sorting-checkbox display_none"
              value="highPrice"
              v-model="form.orderBy"
              @change="Submit"
            >
            <li class="_type">
              <label
                for="highPrice"
                class="word"
              >
                높은 가격순
              </label>
            </li>
            <input
              type="radio"
              id="review"
              class="sorting-checkbox display_none"
              value="review"
              v-model="form.orderBy"
              @change="Submit"
            >
            <li class="_type">
              <label
                for="review"
                class="word"
              >
                상품평 순
                <input
                  type="radio"
                  id="rating"
                  class="display_none"
                  value="rating"
                  v-model="form.orderBy"
                  @change="Submit"
                >
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
                :id="'item'+itemList.item_id"
                :item-list="itemList"
                :search-yn="true"
                @hash-tag="InputHashTag($event)"
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
        itemCount: 0,
        created: false,
        bottomLoadingShow: false,
        categorys: [],
        itemLists: [],
        mainColors: [],
        subColors: [],
        form: {
          searchKeyword: this.$route.query.searchKeyword || '',
          ca_name: '',
          title: '',
          gender: '',
          age: [],
          color: [],
          min_price: null,
          max_price: null,
          max_size: 150,
          min_size: 20,
          hash_tag: [],
          orderBy: 'popular',
          offset: 0,
          limit: 30
        },
        position: 0
      }
    },
    async created () {
      window.addEventListener('scroll', this.InfiniteLoad)

      this.$store.commit('ProgressShow')

      await this.SetForm()
      this.form.offset = 0
      await this.SearchCategory()
      const res = await this.SearchList()
      this.itemLists = res.query.items
      this.form.offset += res.query.items.length
      this.itemCount = res.query.count

      await this.ColorListLoad()

      this.created = true
      if (this.$store.state.beforUrl.includes('/product/view/') && this.position !== 0) {
        document.documentElement.scrollTop = this.$store.state.position
        this.$store.commit('ResetPosition')
        this.position = 0
      }

      this.$store.commit('ProgressHide')

      this.SearchKeywordStore()
    },
    beforeDestroy () {
      this.$store.commit('SavePosition', this.position)
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
    },
    watch: {
      '$route.query.searchKeyword' () {
        this.form.searchKeyword = this.$route.query.searchKeyword
        this.Submit()
        this.SearchKeywordStore()
      }
    },
    methods: {
      async SearchList () {
        try {
          const params = this.form

          if (this.$store.state.beforUrl.includes('/product/view/') && this.$store.state.offset && this.$store.state.position !== 0) {
            params.limit = this.$store.state.offset
            this.position = this.$store.state.position
          }

          const res = (await this.$http.get(this.$APIURI + 'items/search_list', { params })).data
          if (res.state === 1) {
            return res
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async SearchCategory () {
        try {
          const res = (await this.$http.get(this.$APIURI + 'category/search_cate', { params: this.form })).data
          if (res.state === 1) {
            this.categorys = res.query.categorys
          } else {
            console.log(res, res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async Submit () {
        this.$store.commit('ProgressShow')

        this.form.offset = 0
        delete this.form.filterpop
        await this.SearchCategory()
        const res = await this.SearchList()
        this.itemLists = res.query.items
        this.itemCount = res.query.count
        this.form.offset += res.query.items.length

        this.$router.replace({ name: 'total-search', query: this.form })

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
            // this.form.hash_tag.push(this.$route.query.searchKeyword)
            // this.form.hash_tag = [...new Set(this.form.hash_tag)]
          }
        }
      },
      InputHashTag (hashTag) {
        this.form.hash_tag.push(hashTag)
        this.form.hash_tag = [...new Set(this.form.hash_tag)]

        this.Submit()
      },
      async SearchKeywordStore () {
        const params = {
          pp_word: this.$route.query.searchKeyword
        }

        try {
          const res = (await this.$http.post(this.$APIURI + 'popular', params)).data

          if (res.state === 1) {

          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async InfiniteLoad () {
        this.position = document.documentElement.scrollTop
        let top = (document.documentElement && document.documentElement.scrollTop) || document.body.scrollTop
        let bottomOfWindow = ((top + window.innerHeight) + 50) > document.documentElement.offsetHeight && ((top + window.innerHeight) - 50) < document.documentElement.offsetHeight

        if (bottomOfWindow && this.itemCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          const res = await this.SearchList()
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
