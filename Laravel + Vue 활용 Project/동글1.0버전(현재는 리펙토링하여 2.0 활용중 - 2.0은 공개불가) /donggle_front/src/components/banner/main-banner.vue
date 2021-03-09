<template>
  <!-- 배너 -->
  <div
    v-if="mainBanners.length > 0"
    class="dg-main-banner-area"
  >
    <slick
      ref="slick"
      :options="slickOption"
      @afterChange="handleAfterChange"
      @beforeChange="handleBeforeChange"
      @breakpoint="handleBreakpoint"
      @destroy="handleDestroy"
      @edge="handleEdge"
      @init="handleInit"
      @reInit="handleReInit"
      @setPosition="handleSetPosition"
      @swipe="handleSwipe"
      @lazyLoaded="handleLazyLoaded"
      @lazyLoadError="handleLazeLoadError"
      class="main-banner-slider"
    >
      <div
        v-for="mainBanner in mainBanners"
        :key="mainBanner.id"
      >
        <a
          :href="mainBanner.bn_url"
          class="clear_both"
          :target="mainBanner.bn_new_win===0?'_blank':'_self'"
        >
          <img
            :src="storageUrl+ConvertImage(mainBanner.bn_img)"
            :alt="mainBanner.bn_alt"
          >
        </a>
      </div>
    </slick>
  </div>
  <div
    v-else
    class="dg-main-banner-area"
  >
    <div v-if="created">
      <router-link
        to="#"
        class="clear_both"
      >
        <img
          src="/images/example/banner01_web.jpg"
          alt="mainBanner"
        >
      </router-link>
    </div>
  </div>
  <!-- 배너 E -->
</template>

<script>
  import Slick from 'vue-slick'
  export default {
    components: {
      Slick
    },
    data: function () {
      return {
        created: false,
        mainBanners: [],
        slickOption: { // 추천 스토어 리스트들에서 작동되는 slick-slider option Object
          infinite: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          mobileFirst: true,
          arrows: true,
          dots: true,
          autoplay: true,
          autoplaySpeed: 7000,
          fade: true,
          swipe: true,
          centerMode: true
        }
      }
    },
    async created () {
      try {
        const res = (await this.$http.get(this.$APIURI + 'banner/main')).data
        let arr = []
        res.query.forEach(el => {
          if (this.$moment().isBetween(el.bn_begin_time, el.bn_end_time)) {
            if (el.bn_img) {
              arr.push(el)
            }
          }
        })
        this.mainBanners = arr
      } catch (e) {
        console.log(e)
      }

      this.created = true
    },
    // All slick methods can be used too, example here
    beforeUpdate () {
      if (this.$refs.slick) {
        this.$refs.slick.destroy()
      }
    },
    updated () {
      this.$nextTick(function () {
        if (this.mainBanners.length > 0) {
          // this.$refs.slick.create(this.slickOptions)
        }
      })
    },
    methods: {
      next () {
        this.$refs.slick.next()
      },

      prev () {
        this.$refs.slick.prev()
      },

      reInit () {
        // Helpful if you have to deal with v-for to update dynamic lists
        this.$nextTick(() => {
          this.$refs.slick.reSlick()
        })
      },

      // Events listeners
      handleAfterChange (event, slick, currentSlide) {
        // console.log('handleAfterChange', event, slick, currentSlide)
      },
      handleBeforeChange (event, slick, currentSlide, nextSlide) {
        // console.log('handleBeforeChange', event, slick, currentSlide, nextSlide)
      },
      handleBreakpoint (event, slick, breakpoint) {
        // console.log('handleBreakpoint', event, slick, breakpoint)
      },
      handleDestroy (event, slick) {
        // console.log('handleDestroy', event, slick)
      },
      handleEdge (event, slick, direction) {
        // console.log('handleEdge', event, slick, direction)
      },
      handleInit (event, slick) {
        // console.log('handleInit', event, slick)
      },
      handleReInit (event, slick) {
        // console.log('handleReInit', event, slick)
      },
      handleSetPosition (event, slick) {
        // console.log('handleSetPosition', event, slick)
      },
      handleSwipe (event, slick, direction) {
        // console.log('handleSwipe', event, slick, direction)
      },
      handleLazyLoaded (event, slick, image, imageSource) {
        // console.log('handleLazyLoaded', event, slick, image, imageSource)
      },
      handleLazeLoadError (event, slick, image, imageSource) {
        // console.log('handleLazeLoadError', event, slick, image, imageSource)
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dg-main-banner-area {
    background: #fafafa;
  }
</style>
