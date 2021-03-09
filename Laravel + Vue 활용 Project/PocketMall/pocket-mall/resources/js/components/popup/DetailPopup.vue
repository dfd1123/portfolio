<template>
  <div class="popup_content_wrap">
    <div class="popup_con2">
      <!-- <h2>배너 상세 분석</h2> -->
      <div class="clearfix mobile" v-if="this.IsMobile()" v-html="mintro || ''"></div>
      <div class="clearfix pc" v-else v-html="intro || ''"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "detail-popup",
  props: {
    intro: {
      type: String,
      required: true
    },
    mintro: {
      type: String
    }
  },
  mounted() {
    document.querySelectorAll("oembed[url]").forEach(element => {
      iframely.load(element, element.attributes.url.value);
    });
  }
};
</script>

<style scoped>
</style>