<template>
  <section class="_review_wrap _sell_review_wrap">
    <h3>구매후기<span>({{ NumberFormat(reviewCount) }})</span></h3>
    <button
      type="button"
      class="in-more _with_title _write_review"
      v-if="$store.state.user.id"
      @click="$router.push('/mypage/review/write')"
    >
      <span>구매후기 작성하기</span>
      <img
        src="/images/btn/btn_write.svg"
        alt="btn"
      >
    </button>
    <!-- 리뷰가 있을 때 -->
    <div v-if="reviews.length > 0">
      <div
        v-for="review in reviews"
        :key="'review'+review.id"
        class="_review _fill"
        @click="$router.push('/review/'+itemId+'/view/'+review.id)"
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
            <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 -->
            <div :class="'in-star-group star-0'+review.rating.toFixed(0)">
              <i class="_img"></i>
              <span class="_rate">{{ review.rating.toFixed(1) }}</span>
              <b class="_rate_txt"></b>
            </div>
            <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 END -->
            <div class="_review_writer_info">
              <span class="_name">{{ review.nickname }}</span>
              <span class="_date">{{ $moment(review.created_at).format('YYYY-MM-DD') }}</span>
              <span class="_number">NO.{{ review.id }}</span>
            </div>
          </div>
        </div>
        <!-- review writer E -->
        <!-- review write -->
        <div class="_review_write_wrap clear_both">
          <div class="_review_write_product">
            [구매옵션] {{ OptionText(review.option_subject, review.item_option) }}
          </div>
          <div :class="['_review_write_text clear_both', {'_with_img': ConvertImage(review.image).length > 0}]">
            <div
              v-if="ConvertImage(review.image).length > 0"
              class="_review_write_image"
            >
              <img
                :src="storageUrl + ConvertImage(review.image)[0]"
                alt="상품 리뷰 사진"
              >
            </div>
            <p v-html="review.review_body">
            </p>
          </div>
        </div>
        <!-- review write E -->
      </div>
    </div>
    <!-- 리뷰가 있을 때 E -->

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

    <div
      v-if="reviewCount > reviewLimit"
      class="_view_more"
    >
      <router-link :to="'/review/list/'+itemId">
        후기 더보기
      </router-link>
    </div>
    <!--
    <Pagination
      :items="reviews"
      :item-cnt="reviewCount"
      :page-size="reviewLimit"
      :initial-page="reviewPage"
      ref="pagination"
      @changePage="OnChangePage"
    />
    -->
  </section>
</template>

<script>
  // import Pagination from '@/components/pagination/pagination.vue'

  export default {
    components: {
      // Pagination
    },
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
      },
      itemId: {
        type: String,
        required: true
      }
    },
    watch: {
      reviewPage () {
        this.currentPage = this.reviewPage
      }
    },
    mounted () {
      /*
      if (this.reviewCount > this.reviewLimit) {
        this.$refs.pagination.SetPage(this.reviewPage, false)
      }
      */
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
