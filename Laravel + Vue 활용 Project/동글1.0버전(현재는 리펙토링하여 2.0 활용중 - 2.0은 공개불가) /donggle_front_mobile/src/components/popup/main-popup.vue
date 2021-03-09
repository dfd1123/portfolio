<template>
  <!-- 메인 팝업 -->
  <div
    v-if="isVisible"
    class="_popup_wrapper _main_popup_wrapper"
    :style="{
      display: isVisible ? 'block' : '',
      opacity: isVisible ? 1 : 0,
      'z-index': 9999,
      top: '0%'
    }"
  >
    <div
      class="_bg"
      @click="close"
    ></div>
    <div class="_popup_wrap">
      <div class="_popup_content">
        <!-- 이미지가 들어올 영역 -->
        <img
          :src="storageUrl + image"
          alt="popup image"
          @click.prevent="linkClick"
        >
      </div>
      <!-- 상단 닫기버튼 -->
      <button
        type="button"
        class="_popup_close_btn"
        @click.prevent="close"
      ></button>
      <!-- 하단 오늘하루 보이지 않기 버튼 -->
      <div class="_popup_close_today">
        <input
          type="checkbox"
          :id="'close_today' + _uid"
          class="dg-input-checkbox display_none"
          @click.prevent
        >
        <!--
        <label
          :for="'close_today' + _uid"
          class="dg-input-checkbox_label"
        ></label>
        -->
        <label
          :for="'close_today' + _uid"
          class="dg-input-checkbox_text"
          @click="nomore"
        >오늘 하루동안 보지 않기</label>
      </div>
    </div>
  </div>
  <!-- 메인 팝업 E -->
</template>

<script>
  export default {
    props: {
      popupId: {
        type: Number,
        required: true
      },
      image: {
        type: String,
        required: true
      },
      link: {
        type: String,
        default: ''
      }
    },
    data () {
      return {
        isVisible: true
      }
    },
    created () {
      document.body.style.overflowY = 'hidden'
      const nomore = this.$cookies.get('nomore-popup' + '-' + this.popupId)
      if (nomore) {
        document.body.style.overflowY = 'auto'
        this.isVisible = false
      }
    },
    methods: {
      close () {
        document.body.style.overflowY = 'auto'
        this.isVisible = false
      },
      nomore () {
        this.$cookies.set('nomore-popup' + '-' + this.popupId, true, '1d')
        this.close()
      },
      linkClick () {
        if (this.link.toLowerCase().startsWith('http')) {
          this.NativePopup(this.link)
        } else if (this.link.startsWith('/')) {
          this.$router.push(this.link)
        }
      }
    }
  }
</script>

<style lang="scss">
</style>
