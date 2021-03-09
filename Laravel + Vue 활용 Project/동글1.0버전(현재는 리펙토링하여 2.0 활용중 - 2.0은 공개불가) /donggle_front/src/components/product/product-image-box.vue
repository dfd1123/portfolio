<template>
  <div class="img-thumbnail">
    <div class="img-thumbnail-big_wrap">
      <img
        v-if="Images.length > 0"
        id="mainImg"
        :src="storageUrl + Images[0]"
        class="main-product-image"
        :alt="item.title+' 이미지'"
      >
      <img
        v-else
        src="/images/img/thumbnail.png"
        class="main-product-image"
        alt="동글 기본 상품 이미지"
      >
    </div>
    <div
      v-if="Images.length > 0"
      class="img-thumbnail-thumbnail_wrap"
    >
      <div
        v-for="(image, index) in Images"
        :key="'image-'+index"
        class="img-thumbnail-thumbnail"
      >
        <img
          :src="storageUrl + image"
          :alt="item.title+' 이미지'"
          @mouseover="ShowImage($event.target.src)"
        >
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        showImage: null
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
        if (this.item.images !== undefined) {
          return this.ConvertImage(this.item.images)
        } else {
          return []
        }
      }
    },
    methods: {
      /*
      Images (images) {
        return JSON.parse(images).responses
      }
      */
      ShowImage (image) {
        this.showImage = image
        document.getElementById('mainImg').src = image
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
