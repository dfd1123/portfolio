<template>
  <div id="app">
    <layout-header title="구매후기 상세보기" />

    <!-- contents -->
    <div
      id="admin-container"
      class="detail-container"
    >
      <div class="wrapper">
        <!-- <div id="page-inquiry-prdt-detail-wrap"> -->
        <div id="page-manage-review-detail-wrap">
          <div class="grid-line-group">
            <div class="panel-default">
              <div class="form-wrapper">
                <fieldset class="form-container type-03 not-mobile">
                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >작성자</label>
                    <div class="form-align-input">
                      <p class="in-text">{{review.nickname}}</p>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >작성 날짜</label>
                    <div class="form-align-input">
                      <p class="in-text">{{this.$moment(review.created_at).format('YYYY년 MM월 DD일 hh:mm:ss')}}</p>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >후기 No.</label>
                    <div class="form-align-input">
                      <div class="in-text">{{review.id}}</div>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >상품명</label>
                    <div class="form-align-input">
                      <div class="date-product-md">
                        <figure class="in-thumb">
                          <img
                            :src="_get(JSON.parse(review.item_images), 0, null)?storagePath(_get(JSON.parse(review.item_images), 0, null)):'/images/img/thumbnail.png'"
                            alt="product image"
                          />
                        </figure>
                        <span class="in-word">{{review.item_name}}</span>
                      </div>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >후기내용</label>
                    <div class="form-align-input">
                      <div class="date-product-review">
                        <!-- TODO: 사진들어갈 영역.. -->
                        <div
                          v-if="_get(JSON.parse(review.image), 0, null)"
                          class="in-img-wrap"
                        >
                          <figure
                            v-for="(image, index) in JSON.parse(review.image)"
                            :key="'image'+index"
                            class="in-thumb"
                          >
                            <img
                              :src="storagePath(image)"
                              alt="product image"
                            >
                          </figure>
                        </div>
                        <!-- TODO: 글들어갈 영역... -->
                        <div
                          class="in-text"
                          v-html="review.review_body"
                        >

                        </div>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset class="mobile-only">
                  <ul class="mobile-detail-list">
                    <li class="content-divide-line user-info">
                      <dl class="clearfix">
                        <dt class="_tit">작성자</dt>
                        <dd class="_txt">{{review.nickname}}</dd>
                      </dl>
                      <dl class="clearfix">
                        <dt class="_tit">작성날짜</dt>
                        <dd class="_txt">{{this.$moment(review.created_at).format('YYYY년 MM월 DD일 hh:mm:ss')}}</dd>
                      </dl>
                      <dl class="clearfix">
                        <dt class="_tit">후기 No.</dt>
                        <dd class="_txt">{{review.id}}</dd>
                      </dl>
                    </li>
                    <li class="content-divide-line prdt-name">
                      <dl class="clearfix">
                        <dt class="_tit">상품명</dt>
                        <dd class="_txt">
                          <div class="date-product-md">
                            <figure class="in-thumb">
                              <img
                                :src="_get(JSON.parse(review.item_images), 0, null)?storagePath(_get(JSON.parse(review.item_images), 0, null)):'/images/img/thumbnail.png'"
                                alt="product image"
                              />
                            </figure>
                            <span class="in-word">{{review.item_name}}</span>
                          </div>
                        </dd>
                      </dl>
                    </li>
                    <li class="content-divide-line desc-with-img">
                      <dl class="clearfix">
                        <dt class="_tit">후기내용</dt>
                        <dd class="_txt">
                          <!-- TODO: 사진들어갈 영역 사진은 스와이프로 넘어갑니다 -->
                          <div
                            v-if="_get(JSON.parse(review.image), 0, null)"
                            class="in-img-wrap"
                          >
                            <figure
                              v-for="(image, index) in JSON.parse(review.image)"
                              :key="'image'+index"
                              class="in-thumb"
                            >
                              <img
                                :src="storagePath(image)"
                                alt="product image"
                              >
                            </figure>
                          </div>
                          <!-- TODO: 글들어갈 영역... -->
                          <div
                            class="in-text"
                            v-html="review.review_body"
                          >
                          </div>
                          <!-- TODO: 글들어갈 영역... -->
                        </dd>
                      </dl>
                    </li>
                  </ul>
                </fieldset>
                <!-- 댓글영역 -->
                <fieldset class="form-container type-02 write-comment-wrapper">
                  <legend class="_title">댓글<span class="_number">(<span>{{reviewComments.length}}</span>)</span></legend>
                  <!-- 댓글이 있을 때 -->
                  <div v-if="reviewComments.length > 0">
                    <ul class="comment-view-wrap">
                      <!-- 댓글 영역 -->
                      <li
                        v-for="comment in reviewComments"
                        :key="'comment'+comment.id"
                        class="comment-wrapper"
                      >
                        <div class="comment-wrap clearfix">
                          <!-- comment profill -->
                          <div class="in-comment-profill">
                            <div class="in-profile-image">
                              <div class="symbol-circle">
                                <img
                                  :src="_get(JSON.parse(comment.user_image), 0, null)?storagePath(_get(JSON.parse(comment.user_image), 0, null)):'/images/img/basic_profile/01.png'"
                                  alt="프로필이미지"
                                  class="_imgtag"
                                />
                              </div>
                            </div>
                            <div class="in-profill-text">
                              <p class="_name">{{comment.name}}</p>
                              <p class="_date">{{$moment(comment.created_at).format('YYYY-MM-DD')}}</p>
                            </div>
                          </div>
                          <!-- comment profill E -->
                          <div
                            class="comment_desc"
                            v-html="comment.review_body.replace(/(?:\r\n|\r|\n)/g, '<br />')"
                          >
                          </div>
                          <div class="connent_delete_btn">
                            <button
                              type="button"
                              class="rounded-square-xs-btn btn-outline-navy del-btn not-mobile"
                              @click="CommentDelete(comment.id)"
                            >삭제</button>
                            <button
                              type="button"
                              class="del-btn mobile-only"
                              @click="CommentDelete(comment.id)"
                            >삭제</button>
                          </div>
                        </div>
                        <!-- comment wrap E -->
                      </li>
                    </ul>
                    <div
                      v-if="form.offset !== form.count"
                      class="view-more-btn"
                    >
                      <button
                        type="button"
                        @click="CommentMoreLoad"
                      >
                        <span>+댓글 더보기</span>
                      </button>
                    </div>
                  </div>
                  <!-- 댓글이 없을 때 -->
                  <div
                    v-else
                    class="nothing-history"
                  >
                    <img
                      src="/images/icon/empty_board_m.svg"
                      alt="icon empty"
                      class="in-empty-icon"
                    />
                    <span class="in-empty-ment">내역이 없습니다.</span>
                  </div>
                  <!-- 댓글 폼 -->
                  <div class="form-align clearfix comment-write-area-wrap">
                    <textarea
                      placeholder="댓글을 작성하세요"
                      class="comment-write-area"
                      v-model="commentContents"
                    ></textarea>
                    <input
                      type="submit"
                      value="등록"
                      class="rounded-square-btn btn-theme submit-btn not-mobile"
                      @click="CommentStore"
                    >
                    <input
                      type="submit"
                      value="등록"
                      class="submit-btn mobile-only"
                      @click="CommentStore"
                    >
                  </div>
                </fieldset>
                <!-- END 댓글영역 -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <layout-footer />

    </div>
    <!-- END contents -->
  </div>
</template>

<script>
export default {
  name: 'ManageReviewDetail',
  data: function () {
    return {
      review: {},
      reviewComments: [],
      commentContents: null,
      form: {
        review_id: null,
        limit: 10,
        offset: 0,
        count: 0
      }
    }
  },
  props: {
    reviewId: {
      type: String,
      required: true
    }
  },
  async created () {
    this.form.review_id = this.reviewId
    this.review = await this.ReviewLoad()
    const res = await this.CommentsLoad()
    this.reviewComments = res.review_comments
    this.form.count = res.count
    this.form.offset += res.review_comments.length
  },
  methods: {
    async ReviewLoad () {
      const params = {
        review_id: this.reviewId
      }

      try {
        const res = (await this.$axios.get('/api/review/view', { params })).data

        if (res.state === 1) {
          return res.query
        } else {
          alert(res.msg)
        }
      } catch (e) {
        console.log(e)
      } finally {

      }
    },
    async CommentsLoad () {
      const params = this.form

      try {
        const res = (await this.$axios.get('/api/review_comment/list', { params })).data

        if (res.state === 1) {
          return res.query
        } else {
          alert(res.msg)
        }
      } catch (e) {
        console.log(e)
      } finally {

      }
    },
    async CommentMoreLoad () {
      const res = await this.CommentsLoad()
      this.reviewComments = this.reviewComments.concat(res.review_comments)
      this.form.count = res.count
      this.form.offset += res.review_comments.length
    },
    async CommentStore () {
      const params = {
        item_id: this.review.item_id,
        review_id: this.reviewId,
        review_body: this.commentContents
      }

      try {
        this.loading(true)

        const res = (await this.$axios.post('/api/review_comment', params)).data

        if (res.state === 1) {
          alert('댓글이 등록되었습니다.')

          this.form.offset = 0
          const res = await this.CommentsLoad()
          this.reviewComments = res.review_comments
          this.form.count = res.count
          this.form.offset += res.review_comments.length

          const params = {
            target_mem_id: this.review.writer_id,
            not_type: 'review',
            not_content_id: this.review.id,
            not_message: this.store.brandname + ' 스토어가 회원님의 구매후기에 답글을 남겼습니다.'
          }

          if (process.env.VUE_APP_ENV === 'LOCAL') {
            params.not_url = 'http://localhost:8080/review/' + this.review.item_id + '/view/' + this.reviewId
          } else {
            params.not_url = process.env.VUE_APP_PRODC_URI.replace('store.', '') + '/review/' + this.review.item_id + '/view/' + this.reviewId
          }

          this.NotifyStore(params)
        } else {
          alert(res.msg)
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.commentContents = null
        this.loading(false)
      }
    },
    async CommentDelete (id) {
      let params = {
        comment_id: id,
        item_id: this.review.item_id
      }

      try {
        this.loading(true)

        const res = (await this.$axios.put('/api/store/review_comment/delete', params)).data

        if (res.state === 1) {
          params = {
            review_id: this.reviewId,
            limit: this.form.offset,
            offset: 0
          }

          const res2 = (await this.$axios.get('/api/review_comment/list', { params })).data

          if (res2.state === 1) {
            this.reviewComments = res2.query.review_comments
            this.form.count = res2.query.count
            this.form.offset = res2.query.review_comments.length

            alert('댓글이 삭제 되었습니다.')
          } else {
            alert(res2.msg)
          }
        } else {
          alert(res.msg)
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.loading(false)
      }
    }
  }
}
</script>

<style>
</style>
