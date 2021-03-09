<template>
  <div class="main-popup">
    <!-- <div class="popup_bg" onclick="popup_close()"></div> -->
    <div class="popup_bg" @click="ClosePopup"></div>
    <div class="popup_content_wrapper">
      <!-- <button class="_close_btn m_close_btn" @click="ClosePopup">닫기</button> -->
      <!-- 상세보기 팝업영역 -->
      <!--<video-popup v-if="item.option?item.option.op_video_url:item.video_url"></video-popup>-->
      <detail-popup
        :intro="item.option?item.option.op_intro:defaultPopup"
        :mintro="item.option?item.option.op_m_intro:item.m_intro"
      ></detail-popup>
      <!-- END 상세보기 팝업영역 -->
    </div>
    <!-- pc 팝업버튼d -->
    <div class="popup_button_wrapper">
      <button class="_close_btn" @click="ClosePopup">닫기</button>
      <div class="popup_button_wrap clearfix">
        <button class="_icon_btn _icon_cart_btn" v-if="item.option" @click="PopupInAddItem">
          장바구니
          <br class="m-none" />담기
        </button>
        <button
          class="_icon_btn _icon_talk_btn"
          v-if="item.option"
          @click="GotoUrl('https://open.kakao.com/o/s3Il3lIb')"
        >
          실시간
          <br class="m-none" />문의
        </button>
        <button 
        v-else
        class="_icon_btn _icon_talk_btn _icon_talk_btn_default"
        @click="GotoUrl('https://open.kakao.com/o/s3Il3lIb')"
        >
          바로 문의하기
        </button>
      </div>
    </div>
    <!-- pc 팝업버튼 E -->
  </div>
</template>

<script>
export default {
  name: "main-popup-component",
  data() {
    return {
      videoUrl: null,
      intro: "",
      defaultPopup:
        '<figure class="image"><img src="assets/images/default/defalut_popup.png" alt="img"></figure>'
    };
  },
  props: {
    item: {
      type: Object,
      required: true
    },
    popup: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    item() {
      if (this.item.option) {
        this.videoUrl = this.item.option.op_video_url;
        this.intro = this.item.option.op_intro || "";
      } else {
        this.videoUrl = this.item.video_url;
        this.intro = this.item.intro || "";
      }
    }
  },
  methods: {
    PopupInAddItem() {
      if (this.item.option) {
        this.$store.commit("CartAdd", this.item);
      }

// 장바구니 팝업버튼 클릭이벤트
      if (!this.IsMobile()) {
        this.$emit("ClosePopup");
      } else{
        this.$emit("update:popup", true);
        this.$emit("ClosePopup");
      }
    },
    ClosePopup() {
      this.$emit("ClosePopup");
    },
    GotoUrl(url) {
      window.open(url);
    }
  }
};
</script>

<style scoped>
</style>