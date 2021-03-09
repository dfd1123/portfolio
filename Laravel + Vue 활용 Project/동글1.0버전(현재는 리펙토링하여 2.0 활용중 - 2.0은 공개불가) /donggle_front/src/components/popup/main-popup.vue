<template>
  <!-- 메인 팝업 -->
  <div
    v-if="isVisible"
    class="_popup_wrapper _main_popup_wrapper"
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
          style="cursor: pointer;"
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
          :checked="checked"
          @change="nomore($event.target.value)"
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
        isVisible: true,
        checked: false
      }
    },
    created () {
      const nomore = this.$cookies.get('nomore-popup' + '-' + this.popupId)
      if (nomore) {
        this.isVisible = false
      }
    },
    methods: {
      close () {
        this.isVisible = false
      },
      nomore (status) {
        if (status) {
          this.checked = true
          this.$cookies.set('nomore-popup' + '-' + this.popupId, true, '1d')
          this.close()
        } else {
          this.checked = false
        }
      },
      linkClick () {
        if (this.link.toLowerCase().startsWith('http')) {
          window.open(this.link, '_blank')
        } else if (this.link.startsWith('/')) {
          this.$router.push(this.link)
        }
      }
    }
  }
</script>

<style lang="scss">
  .dg-input-checkbox_text {
    font-size: 1em !important;
  }
</style>
