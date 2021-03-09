<template>
  <section class="_review_wrap _sell_review_wrap">
    <h3 class="dg_blind">
      구매후기
    </h3>
    <!-- 구매후기 작성하기 팝업 오픈 -->
    <button
      class="in-more _with_title _write_review"
      @click="$router.push('/mypage/review/write')"
    >
      <span>구매후기 작성하기</span>
    </button>

    <!-- 리뷰가 있을 때: 처음에 5개 보임. 더보기 누르면 5개가 추가로 펼쳐짐 -->
    <div
      v-if="reviews.length > 0"
      class="_fill"
    >
      <div
        v-for="review in reviews"
        :key="'review'+review.id"
        class="_review"
        @click="$router.push('/review/'+review.item_id + '/view/'+review.id)"
      >
        <!-- review writer -->
        <div class="_review_writer clear_both">
          <div class="symbol-circle">
            <img
              v-if="ConvertImage(review.user_image).length > 0"
              :src="ConvertImage(review.user_image)[0].includes('http')?ConvertImage(review.user_image)[0]:storageUrl+ConvertImage(review.user_image)[0]"
              :alt="review.nickname"
              class="_imgtag"
            >
            <img
              v-else
              src="/images/img/thumbnail.png"
              alt="noimage"
              class="_imgtag"
            >
          </div>
          <div class="_review_product_wrap">
            <div class="_review_writer_info">
              <span class="_name">{{ review.nickname }}</span>
              <span class="_number">NO.{{ review.id }}</span>
              <span class="_date">{{ $moment(review.created_at).format('YYYY-MM-DD') }}</span>
              <span class="_comment">{{ NumberFormat(review.comment_cnt) }}</span>
            </div>
            <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 -->
            <div :class="'in-star-group star-0'+review.rating.toFixed(0)">
              <i class="_img"></i>
              <span class="_rate"></span>
            </div>
            <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 END -->
          </div>
        </div>
        <!-- review writer E -->
        <!-- review write -->
        <div class="_review_write_wrap clear_both">
          <div class="_review_write_product">
            [구매옵션] {{ OptionText(review.option_subject, review.item_option) }}
          </div>
          <div class="_review_write_text clear_both _with_img">
            <div
              class="_review_write_image"
              v-if="ConvertImage(review.image).length > 0"
            >
              <div
                class="_review_write_image"
              >
                <img
                  :src="storageUrl+ConvertImage(review.image)[0]"
                  alt="상품 리뷰 사진"
                >
              </div>
            </div>
            <p v-html="review.review_body">
            </p>
          </div>
        </div>
        <!-- review write E -->
      </div>
      <div
        v-if="reviewCount > reviewLimit"
        class="_view_more"
      >
        <router-link to="#">
          후기 더보기
        </router-link>
      </div>
      <!-- 리뷰가 있을 때 E -->
    </div>

    <!-- 리뷰가 없을 때 -->
    <div
      v-else
      class="_empty"
    >
      <div class="_img_box">
        <img
          src="/images/icon/empty_review.svg"
          alt="등록된 후기가 없습니다."
        >
      </div>
      <div class="_text">
        등록된 후기가 없습니다.
      </div>
    </div>
    <!-- 리뷰가 없을 때 E -->
  </section>
</template>

<script>
  export default {
    data: function () {
      return {
        currentPage: this.qnaPage
      }
    },
    props: {
      reviews: {
        type: Array,
        required: true
      },
      reviewCount: {
        type: Number,
        required: true
      },
      reviewLimit: {
        type: Number,
        required: true
      },
      reviewPage: {
        type: Number,
        required: true
      }
    },
    watch: {
      reviewPage () {
        this.currentPage = this.reviewPage
      }
    },
    mounted () {
      if (this.reviewCount > this.reviewLimit) {
        this.$refs.pagination.SetPage(this.reviewPage, false)
      }
    },
    methods: {
      OnChangePage (currentPage) {
        this.currentPage = currentPage
        this.$emit('reviews-load', currentPage)
        // this.fetchData()
      },
      OptionText (subject, option) {
        let text = ''
        subject = subject.split(',')
        option = option.split(',')

        for (let i = 0; i < subject.length; i++) {
          text += subject[i] + ':' + option[i]
          if (i !== (subject.length - 1)) {
            text += ' / '
          }
        }

        return text
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
