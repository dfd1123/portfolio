<template>
  <div id="dg-product-review-detail-wrapper">
    <section
      id="product_review"
      class="clear_both _page_title_wrap"
    >
      <h2>
        이 상품의 다른 구매 후기
        <button
          type="button"
          class="_back_btn"
          @click="$router.go(-1)"
        >
          뒤로가기
        </button>
      </h2>
      <!-- review detail, comment -->
      <div class="review-detail_and_comment_wrap">
        <!-- 구매후기 -->
        <section class="_review_wrap _deffrent_review_wrap">
          <div
            v-if="reviews.length > 0"
            class="_reviews _fill"
          >
            <div
              v-for="review in reviews"
              :key="'review'+review.id"
              class="_review"
              @click="$router.push({path:'/review/'+itemId+'/view/'+review.id, query: form})"
            >
              <!-- review writer -->
              <div class="_review_writer clear_both">
                <div class="symbol-circle">
                  <img
                    v-if="ConvertImage(review.user_image).length > 0"
                    :src="ConvertImage(review.user_image)[0].includes('http')?ConvertImage(review.user_image)[0]:storageUrl+ConvertImage(review.user_image)[0]"
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
                  {{ OptionFormat(review) }}
                </div>
                <div class="_review_write_text clear_both _with_img">
                  <div
                    v-if="ConvertImage(review.image).length > 0"
                    class="_review_write_image"
                  >
                    <img
                      :src="storageUrl+ConvertImage(review.image)[0]"
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
            class="loading_wrap"
            v-show="bottomLoadingShow"
          >
            <Loading />
          </div>
          <!-- 리뷰가 있을 때 E -->
        </section>
        <!-- 구매후기 E -->
      </div>
      <!-- review detail, comment E -->
    </section>
  </div>
</template>

<script>
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      Loading
    },
    data: function () {
      return {
        reviews: [],
        form: {
          item_id: null,
          limit: 15,
          offset: 0,
          count: 0
        },
        bottomLoadingShow: false
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
      window.addEventListener('scroll', this.InfiniteLoad)

      this.$store.commit('ProgressShow')

      const res = await this.ReviewLoad()
      this.reviews = res.reviews
      this.form.count = res.count
      this.form.offset += res.reviews.length

      this.$router.replace({ name: 'review-list', query: this.form })

      this.$store.commit('ProgressHide')
    },
    destroyed () {
      window.removeEventListener('scroll', this.InfiniteLoad)
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
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.itemCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          const res = await this.ReviewLoad()

          this.reviews = this.reviews.concat(res.reviews)
          this.form.offset += res.reviews.length
          this.$router.replace({ name: 'review-list', query: this.form })
          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
