<template>
  <div id="dg-product-qna-detail-wrapper">
    <div class="dg-content_center">
      <section id="product_qna">
        <div class="review-detail_title clear_both">
          <router-link
            :to="'/item_qna/list/' + item.item_id"
            class="_back_btn"
          >
            뒤로가기
          </router-link>
          <div class="review_img_box">
            <img
              v-if="ConvertImage(item.images).length > 0"
              :src="storageUrl + ConvertImage(item.images)[0]"
              :alt="item.title"
            >
            <img
              v-else
              src="/images/img/thumbnail.png"
              :alt="item.title"
            >
          </div>
          <h2>
            <b>{{ item.title }}</b><br>
            <span>{{ item.company_name }}</span>
          </h2>
        </div>
        <!-- qna detail -->
        <div class="l-con-article clear_both product_qna_contents">
          <section class="_cs_wrap _support_wrapper _support_content_wrapper">
            <h3 class="_with_profill">
              <div class="symbol-circle">
                <img
                  v-if="ConvertImage(qna.profile_img).length > 0"
                  :src="qna.profile_img.includes('http')?ConvertImage(qna.profile_img)[0]:storageUrl+ConvertImage(qna.profile_img)[0]"
                  :alt="qna.nickname"
                  class="_imgtag"
                >
                <img
                  v-else
                  src="/images/img/thumbnail.png"
                  :alt="qna.nickname"
                  class="_imgtag"
                >
              </div>
              <span>{{ qna.nickname }}
              </span>
            </h3>
            <div class="_board">
              <!-- 등록된 문의가 있을 때 -->
              <ul
                v-if="qna.id"
                class="_board_layout _cs_layout _question_fill"
              >
                <li class="_cs_content _support_content">
                  <div class="_support_content_wrap">
                    <div class="_board_title_label _support_content_title">
                      <span class="_name">{{ qna.q_name }}</span>
                      <span class="_date">{{ $moment(qna.q_datetime).format('YYYY-MM-DD hh:mm:ss') }}</span>
                      <span class="_title">{{ qna.subject }}</span>
                    </div>
                    <div
                      class="_support_content_desc"
                      v-html="qna.question"
                    >
                    </div>
                  </div>
                </li>
                <li class="_cs_content _support_content _support_answer_content _no_response">
                  <div class="_support_content_wrap">
                    <div class="_board_title_label _support_content_title">
                      <span class="_name">{{ item.company_name }}</span>
                      <span
                        class="_date"
                        v-if="qna.answer"
                      >{{ $moment(qna.a_datetime).format('YYYY-MM-DD hh:mm:ss') }}</span>
                      <span
                        class="_title"
                        v-if="qna.answer"
                      >답변완료</span>
                      <span
                        class="_title"
                        v-else
                      >답변 대기중</span>
                    </div>
                    <div
                      class="_support_content_desc"
                      v-if="qna.answer"
                      v-html="qna.answer"
                    >
                    </div>
                    <div
                      class="_support_content_desc"
                      v-else
                    >
                      <p class="answer_desc">
                        답변 대기 중입니다.
                      </p>
                    </div>
                  </div>
                </li>
              </ul>
              <!-- 등록된 문의가 있을 때 E -->
              <!-- 등록된 문의가 없을 때  -->
              <ul
                v-else
                class="_board_layout _cs_layout _question_empty_a display_none"
              >
                <li>등록된 문의가 없습니다.</li>
              </ul>
              <!-- 등록된 문의가 없을 때 E -->
            </div>
          </section>
        </div>
        <!-- qna detail E -->
      </section>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        item: {},
        qna: {}
      }
    },
    props: {
      itemId: {
        type: String,
        required: true
      },
      qnaId: {
        type: String,
        required: true
      }
    },
    async created () {
      this.$store.commit('ProgressShow')

      try {
        const [res1, res2] = (await Promise.all([
          this.$http.get(this.$APIURI + 'items/view', { params: { item_id: this.itemId } }),
          this.$http.get(this.$APIURI + 'item_qna/view', { params: { id: this.qnaId } })
        ])).map(res => res.data)

        if (res1.state !== 1) {
          console.log(res1.msg)
        }

        if (res2.state !== 1) {
          console.log(res2.msg)
        }

        this.item = res1.query.item
        this.qna = res2.query

        if (this.item.item_id !== this.qna.item_id) {
          alert('잘못된 접근 입니다.')
          this.$router.push('/item_qna/list/' + this.item.item_id)
        }

        if (this.qna.password) {
          if (this.$store.state.user.name) {
            if (this.$store.state.user.id !== this.qna.q_id && this.$store.state.user.id !== this.qna.a_id) {
              alert('이 상품문의 글은 비밀글 입니다.')
              this.$router.push('/item_qna/list/' + this.item.item_id)
            }
          } else {
            alert('이 상품문의 글은 비밀글 입니다.')
            this.$router.push('/item_qna/list/' + this.item.item_id)
          }
        }
      } catch (e) {
        console.log(e)
      } finally {
        this.$store.commit('ProgressHide')
      }
    },
    methods: {

    }
  }
</script>

<style lang="scss" scoped>
</style>
