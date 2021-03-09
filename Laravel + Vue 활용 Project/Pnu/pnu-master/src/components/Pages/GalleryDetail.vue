<template>
<div>
    <div class="row social-image-row gallery">
        <div class="col-6" v-for="(thumb, thumbIndex) in thumbs" :key="`thumb_${thumbIndex}`" @click="onThumbClick(thumbIndex)">
            <img class="img-fluid border-radius" :src="thumb" alt="thumbnail" />
        </div>
    </div>
    <LightGallery :images="images" :index="photoIndex" :disable-scroll="true" @close="handleHide()" />
</div>
</template>

<script>
import {
  LightGallery
} from 'vue-light-gallery'

const images = [
  '/master/assets/img/fruitcake.jpg',
  '/master/assets/img/napoleonshat.jpg',
  '/master/assets/img/tea-loaf.jpg',
  '/master/assets/img/magdalena.jpg',
  '/master/assets/img/marble-cake.jpg',
  '/master/assets/img/parkin.jpg'
]
const thumbs = [
  '/master/assets/img/fruitcake-thumb.jpg',
  '/master/assets/img/napoleonshat-thumb.jpg',
  '/master/assets/img/tea-loaf-thumb.jpg',
  '/master/assets/img/magdalena-thumb.jpg',
  '/master/assets/img/marble-cake-thumb.jpg',
  '/master/assets/img/parkin-thumb.jpg'
]
export default {
  components: {
    LightGallery
  },
  data () {
    return {
      images,
      thumbs,
      isOpen: false,
      photoIndex: null
    }
  },
  methods: {
    onThumbClick (index) {
      this.photoIndex = index
      this.isOpen = true
    },
    handleHide () {
      this.photoIndex = null
      this.isOpen = false
    }
  }
}
</script>
