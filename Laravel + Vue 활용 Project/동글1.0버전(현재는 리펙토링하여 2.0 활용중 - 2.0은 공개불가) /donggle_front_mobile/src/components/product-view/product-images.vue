<template>
  <div class="img-thumbnail">
    <div
      v-if="Images.length > 0"
      class="img-thumbnail_wrap img_box"
    >
      <slick
        ref="slick"
        :options="slickOptions"
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
        class="product-images"
      >
        <div
          v-for="(image, index) in Images"
          :key="'image-'+index"
        >
          <img
            :src="storageUrl+image"
            :alt="item.title+' 이미지'"
          >
        </div>
      </slick>
    </div>
    <div
      v-else
      class="img-thumbnail_wrap img_box"
    >
      <div>
        <img
          src="/images/img/thumbnail.png"
          alt="동글 기본 상품 이미지"
        >
      </div>
    </div>
  </div>
</template>

<script>
  import Slick from 'vue-slick'
  export default {
    components: {
      Slick
    },
    data: function () {
      return {
        slickOptions: { // 상품 이미지들이 작동되는 slick-slider option Object
          infinite: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          mobileFirst: true,
          arrows: false,
          dots: true,
          autoplaySpeed: 2000,
          swipe: true,
          adaptiveHeight: false,
          responsive: [
            {
              breakpoint: 769,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                mobileFirst: true,
                arrows: false,
                dots: true
              }
            }
          ]
        }
      }
    },
    props: {
      item: {
        type: Object,
        required: true
      }
    },
    computed: {
      Images () {
        if (this.item.images !== undefined && this.ConvertImage(this.item.images).length > 0) {
          this.reInit()
          return this.ConvertImage(this.item.images)
        } else {
          return []
        }
      }
    },
    // All slick methods can be used too, example here
    beforeUpdate () {
      if (this.$refs.slick) {
        this.$refs.slick.destroy()
      }
    },
    updated () {
      this.$nextTick(function () {
        if (this.$refs.slick) {
          // this.$refs.slick.create(this.slickOptions)
        }
      })
    },
    methods: {
      /*
      Images (images) {
        return JSON.parse(images).responses
      }
      */
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
</style>
