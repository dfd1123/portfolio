<template>
  <div id="dg-product-review-detail-wrapper">
    <div class="dg-content_center">
      <section id="product_review">
        <div
          class="review-detail_title clear_both"
          @click="$router.go(-1)"
        >
          <div class="review_img_box">
            <img
              v-if="ConvertImage(item.images || '[]').length > 0"
              :src="storageUrl + ConvertImage(item.images)[0]"
              :alt="reviews[0].item_name"
            >
            <img
              v-else
              src="/images/img/thumbnail.png"
              :alt="item.title || '-'"
            >
          </div>
          <h2>
            <b>{{ item.title || '-' }}</b><br>
            <span>{{ item.company_name || '-' }}</span>
          </h2>
        </div>
        <!-- review detail, comment -->
        <div class="review-detail_and_comment_wrap">
          <!-- 구매후기 -->
          <section class="_review_wrap _deffrent_review_wrap">
            <h3>이 상품의 다른 구매후기</h3>
            <!-- 리뷰가 있을 때 1페이지에 5개씩 보이기 -->
            <div
              v-if="reviews.length > 0"
              class="_review _fill"
            >
              <div
                v-for="review in reviews"
                :key="'review'+review.id"
                @click="$router.push({path:'/review/'+item.item_id+'/view/'+review.id, query: form})"
              >
                <!-- review writer -->
                <div class="_review_writer clear_both">
                  <div class="symbol-circle">
                    <img
                      v-if="ConvertImage(review.user_image).length > 0"
                      :src="storageUrl + ConvertImage(review.user_image)[0]"
                      alt="프로필 이미지"
                      class="_imgtag"
                    >
                    <img
                      v-else
                      src="/images/img/thumbnail.png"
                      alt="프로필 이미지"
                      class="_imgtag"
                    >
                  </div>
                  <div class="_review_product_wrap">
                    <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 -->
                    <div :class="'in-star-group star-0'+review.rating.toFixed(0)">
                      <i class="_img"></i>
                      <span class="_rate"></span>
                      <b class="_rate_txt"></b>
                    </div>
                    <!-- ※ 별 점수에 따라 star-05, star-04, star-03, star-02, star-01 클래스 추가 END -->
                    <div class="_review_writer_info">
                      <span class="_name">{{ review.nickname }}</span>
                      <span class="_number">NO.{{ review.id }}</span>
                      <span class="_date">{{ $moment(review.created_at).format('YYYY-MM-DD') }}</span>
                      <span class="_comment">{{ NumberFormat(review.comment_cnt) }}</span>
                    </div>
                  </div>
                </div>
                <!-- review writer E -->
                <!-- review write -->
                <div class="_review_write_wrap clear_both">
                  <div class="_review_write_product">
                    [구매옵션] {{ OptionFormat(review) }}
                  </div>
                  <div class="_review_write_text">
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
            <Pagination
              :items="reviews"
              :item-cnt="form.count"
              :page-size="form.limit"
              :initial-page="form.page"
              ref="pagination"
              @changePage="OnChangePage"
            />
          </section>
          <!-- 구매후기 E -->
        </div>
        <!-- review detail, comment E -->
      </section>
    </div>
  </div>
</template>

<script>
  import Pagination from '@/components/pagination/pagination.vue'

  export default {
    components: {
      Pagination
    },
    data: function () {
      return {
        item: {},
        reviews: [],
        form: {
          item_id: null,
          limit: 15,
          page: Number(this.$route.query.page) || 1,
          count: 0
        }
      }
    },
    props: {
      itemId: {
        type: String,
        required: true
      }
    },
    async created () {
      if (this.itemId === 'undefined') {
        this.$router.push('/')
      }

      this.form.item_id = Number(this.itemId)

      this.$store.commit('ProgressShow')

      const res = await this.ReviewLoad()
      this.item = res.item
      this.reviews = res.reviews
      this.form.count = res.count

      this.$router.replace({ name: 'review-list', query: this.form })

      this.$store.commit('ProgressHide')
    },
    mounted () {
      if (this.form.count > this.form.limit) {
        this.$refs.pagination.SetPage(this.form.page, false)
      }
    },
    methods: {
      async ReviewLoad () {
        const params = this.form

        try {
          const res = (await this.$http.get(this.$APIURI + 'review/item_review', { params })).data

          if (res.state === 1) {
            return res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      OptionFormat (review) {
        if (review.item_option) {
          const options = review.item_option.split(',')
          const optionSubject = review.option_subject.split(',')
          const productOption = []

          for (let i = 0; i < optionSubject.length; i++) {
            productOption.push(optionSubject[i] + ' : ' + options[i])
          }

          return productOption.join('/')
        }
      },
      async OnChangePage (currentPage) {
        this.form.page = currentPage

        this.$store.commit('ProgressShow')

        const res = await this.ReviewLoad()
        this.reviews = res.reviews
        this.form.count = res.count

        this.$store.commit('ProgressHide')
      }
    }
  }
</script>

<style lang="scss" scoped>
  .review-detail_title {
    cursor: pointer;
  }
  .review-detail_and_comment_wrap {
    border-bottom: none;
  }
</style>
