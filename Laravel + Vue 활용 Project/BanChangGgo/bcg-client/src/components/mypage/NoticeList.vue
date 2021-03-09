<template>
  <div>
    <!-- 공지사항 제목 -->
    <article :class="{ active : showType }" class="subjt_article" @click="showContents()">
      <span>{{ noticeList.ntc_reg_dt }}</span>
      <h2>{{ noticeList.ntc_title }}</h2>
    </article>
    <!-- end -->
    <!-- 공지사항 내용 -->
    <div class="subjt_article_contents" v-show="showType">
      <p>{{ noticeList.ntc_content }}</p>
    </div>
    <!-- end -->
  </div>
</template>

<script>
export default {
  name: 'notice-list',
  props: ['noticeList'],
  data () {
    return {
      showType: false
    }
  },
  methods: {
    showContents () {
      this.showType = !this.showType
    }
  }
}
</script>

<style lang="scss" scoped>
.subjt_article {
  @include bgStyle(95%, 50%, 15px);
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #f0f0f0;
  background-image: url(/assets/images/btn/btn_arrow_spread.svg);
  & > span {
    @include setFont(13px, $weight: 400, $color: #969696);
  }
  & > h2 {
    @include setFont(14px, $weight: 400, $color: $gray-01);
    @include ellipsis(1, 24px);
    width: 85%;
  }
}
.subjt_article.active {
  background-image: url(/assets/images/btn/btn_arrow_fold.svg);
}
.subjt_article_contents {
  padding: 1rem 1.25rem;
  background-color: #f5f5f5;
  @include setFont(13px, $weight: 400, $color: $gray-01);
}
</style>
