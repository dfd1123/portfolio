<template>
  <div id="dg-product-review-detail-wrapper">
    <div class="dg-content_center">
      <section id="product_review">
        <div class="review-detail_title clear_both">
          <button
            type="button"
            class="_back_btn"
            @click="$router.push('/review/list/'+itemId)"
          >
            뒤로가기
          </button>
          <div class="review_img_box">
            <img
              v-if="ConvertImage(review.item_images).length > 0"
              :src="storageUrl + ConvertImage(review.item_images)[0]"
              :alt="review.item_name"
            >
            <img
              v-else
              src="/images/img/thumbnail.png"
              :alt="review.item_name"
            >
          </div>
          <h2>
            <b>{{ review.item_name }}</b><br>
            <span>{{ review.company_name }}</span>
          </h2>
        </div>
        <!-- review detail, comment -->
        <div class="review-detail_and_comment_wrap">
          <section class="product_review_detail _review_wrap">
            <h3 class="dg_blind">
              리뷰보기
            </h3>
            <div class="_review">
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
                  <div :class="'in-star-group star-0'+(review.rating || 0).toFixed(0)">
                    <i class="_img"></i>
                    <span class="_rate"></span>
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
                  [구매옵션] {{ OptionFormat(review) }}
                </div>
                <div class="_review_write_text">
                  <!-- 20-03-31 -->
                  <!-- ._review_write_images_wrap 에 들어가는 ._review_write_images 수: 최대 5개 -->
                  <div class="_review_write_images_wrap">
                    <div
                      v-if="ConvertImage(review.image).length > 0"
                      class="_review_write_images"
                    >
                      <img
                        v-for="(image, index) in ConvertImage(review.image)"
                        :data-index="index"
                        :key="'review-image'+index"
                        v-gallery="'review-images'"
                        :src="storageUrl + image"
                        alt="프로필 이미지"
                      >
                    </div>
                  </div>
                  <!-- END 20-03-31 -->
                  <p v-html="review.review_body">
                  </p>
                </div>
              </div>
              <!-- review write E -->
            </div>
          </section>
          <section class="product_review_comment">
            <!-- 댓글 개수 표시는 h3:after 로 제어 -->
            <h3>댓글<span>({{ NumberFormat(form.count) }})</span></h3>
            <div class="_comment_page over-y_scroll">
              <!-- 댓글이 있을 때 -->
              <div
                v-if="reviewComments.length > 0"
                class="_fill"
              >
                <!-- comment layout -->
                <div
                  v-for="comment in reviewComments"
                  :key="'comment'+comment.id"
                  class="_comment"
                >
                  <div class="symbol-circle">
                    <img
                      v-if="ConvertImage(comment.user_image).length > 0"
                      :src="ConvertImage(comment.user_image)[0].includes('http')?ConvertImage(comment.user_image)[0]:storageUrl + ConvertImage(comment.user_image)[0]"
                      alt="프로필 이미지"
                      class="_imgtag"
                    >
                    <img
                      v-else
                      src="/images/img/basic_profile/01.png"
                      alt="프로필 이미지"
                      class="_imgtag"
                    >
                  </div>
                  <div class="_comment_text">
                    <span>{{ comment.name }}</span>
                    <div
                      class="_comment_desc"
                      v-html="comment.review_body"
                    >
                    </div>
                  </div>
                  <div class="_date">
                    <span>{{ $moment(comment.created_at).format('YYYY-MM-DD') }}</span>
                  </div>
                </div>
                <!-- comment layout E -->
              </div>
              <!-- 댓글이 있을 때 E -->
              <!-- 댓글이 없을 때 -->
              <div
                v-else
                class="_empty"
              >
                <div class="_img_box">
                  <img
                    src="/images/icon/empty_comment.svg"
                    alt="등록된 댓글이 없습니다."
                  >
                </div>
                <div class="_text">
                  등록된 댓글이 없습니다.<br>첫 댓글을 남겨주세요!
                </div>
              </div>
              <!-- 댓글이 없을 때 E -->
            </div>
            <div
              v-if="$store.state.user.name"
              class="_comment_write_wrap"
            >
              <input
                type="text"
                class="_comment_write"
                placeholder="댓글을 남겨주세요"
                v-model="commentContents"
                @keyup.enter="CommentSubmit"
              >
              <button
                type="button"
                class="square-btn-dark _comment_sign_up"
                @click="CommentSubmit"
              >
                등록
              </button>
            </div>
          </section>
        </div>
        <!-- review detail, comment E -->
      </section>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        review: {},
        reviewComments: [],
        form: {
          review_id: null,
          limit: 5,
          offset: 0,
          count: 0
        },
        commentContents: null
      }
    },
    props: {
      reviewId: {
        type: String,
        required: true
      },
      itemId: {
        type: String,
        required: true
      }
    },
    async created () {
      this.form.review_id = Number(this.reviewId)

      this.$store.commit('ProgressShow')

      await this.ReviewLoad()
      const res = await this.CommentLoad()
      this.reviewComments = res.review_comments
      this.form.count = res.count
      this.form.offset += res.review_comments.length

      this.$store.commit('ProgressHide')
    },
    methods: {
      async ReviewLoad () {
        const params = {
          review_id: this.reviewId
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'review/view', { params })).data

          if (res.state === 1) {
            this.review = res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async CommentLoad () {
        const params = this.form

        try {
          const res = (await this.$http.get(this.$APIURI + 'review_comment/list', { params })).data

          if (res.state === 1) {
            return res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async CommentMoreLoad () {
        const res = await this.CommentLoad()
        this.reviewComments = this.reviewComments.concat(res.review_comments)
        this.form.count = res.count
        this.form.offset += res.review_comments.length
      },
      async CommentSubmit () {
        if (!this.$cookies.isKey('access_token')) {
          const result = await this.WarningAlert('로그인을 하셔야 댓글을 남길 수 있습니다.')

          if (result) {
            this.$router.push('/login')
          }
        } else {
          const params = {
            item_id: this.itemId,
            review_id: this.reviewId,
            review_body: this.commentContents
          }

          try {
            this.$store.commit('ProgressShow')

            const res = (await this.$http.post(this.$APIURI + 'review_comment', params)).data

            if (res.state === 1) {
              this.SuccessAlert('댓글이 등록되었습니다.')

              this.$store.commit('ProgressShow')
              this.form.offset = 0
              const res = await this.CommentLoad()
              this.reviewComments = res.review_comments
              this.form.count = res.count
              this.form.offset += res.review_comments.length
              this.$store.commit('ProgressHide')

              const params = {
                target_mem_id: this.review.writer_id,
                not_type: 'review',
                not_content_id: this.review.id,
                not_message: this.$store.state.user.nickname + '님이 회원님의 구매후기에 댓글을 작성하셨습니다.',
                not_url: window.location.protocol + '//' + window.location.host + '/review/' + this.itemId + '/view/' + this.reviewId
              }
              this.NotifyStore(params)
            } else {
              this.WarningAlert(res.msg)
            }
          } catch (e) {
            console.log(e)
          } finally {
            this.commentContents = null
            this.$store.commit('ProgressHide')
          }
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
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
