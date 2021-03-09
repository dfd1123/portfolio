<template>
  <div class="dg-hd-best_search_wrap">
    <small>인기검색어</small>
    <div class="best-search">
      <SlickRanking
        v-if="topTen.length > 0"
        :keywords="topTen"
        :slick-options="schrankOptions"
      />

      <!-- popup best search -->
      <div class="best-search_hover clear_both">
        <h2><span>실시간 인기 검색어</span></h2>
        <ul class="best-search_best best-search_best10">
          <li
            v-for="(keyword, index) in topTen"
            :key="'topTen'+index"
          >
            <router-link :to="'/total/search?searchKeyword='+keyword.pp_word">
              {{ Pad(index+1, 2) }}.{{ keyword.pp_word }}
            </router-link>
          </li>
        </ul>
        <ul
          v-if="nextTen.length > 0"
          class="best-search_best best-search_best20"
        >
          <li
            v-for="(keyword, index) in nextTen"
            :key="'nextTen'+index"
          >
            <router-link :to="'/total/search?searchKeyword='+keyword.pp_word">
              {{ Pad(index+11, 2) }}.{{ keyword.pp_word }}
            </router-link>
          </li>
        </ul>
      </div>
      <!-- popup best search E -->
    </div>
  </div>
</template>

<script>
  import SlickRanking from '@/components/slick/slick-ranking.vue'
  export default {
    components: {
      SlickRanking
    },
    data: function () {
      return {
        keywords: [],
        topTen: [],
        nextTen: [],
        schrankOptions: {
          dots: false,
          vertical: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          verticalSwiping: true,
          autoplay: true,
          autoplaySpeed: 3000,
          infinite: true,
          arrows: false
        }
      }
    },
    created () {
      this.Load()
      setInterval(() => this.Load(), 1000 * 60 * 3)
    },
    methods: {
      async Load () {
        const params = {
          limit: 20
        }
        const res = (await this.$http.get(this.$APIURI + 'popular/list', { params })).data

        this.keywords = res.query

        this.topTen = []
        this.nextTen = []

        this.keywords.forEach((keyword, index) => {
          if (index < 10) {
            this.topTen.push(keyword)
          } else {
            this.nextTen.push(keyword)
          }
        })
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
