<template>
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
  >
    <ItemThumb
      v-for="itemList in itemLists"
      :key="itemList.item_id"
      :item-list="itemList"
    />
  </slick>
</template>

<script>
  import ItemThumb from '@/components/thumbnail/item-thumb.vue'
  import Slick from 'vue-slick'

  export default {
    components: {
      ItemThumb,
      Slick
    },
    props: {
      className: {
        type: String,
        default: ''
      },
      itemLists: {
        type: Array,
        required: true
      },
      slickOptions: {
        type: Object,
        required: true
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
          this.$refs.slick.create(this.slickOptions)
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

<style lang="scss">
  .dg-clothes-list-card {
    padding: 0 6px;
    padding-bottom: 35px;
  }
  .slick-track {
    position: relative;
    top: 0;
    left: 0;
    display: block;
    margin-left: 0;
    margin-right: 0;
  }
</style>
