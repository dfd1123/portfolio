<template>
  <div>
    <div
      class="_page_title_wrap"
      style="padding:0"
    >
      <h2>
        상품 문의 하기
        <a
          href="#"
          class="_back_btn"
          @click.prevent="$router.go(-1)"
        >뒤로가기</a>
      </h2>
    </div>
    <div class="top_title_wrap clear_both">
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
      <h2 class="_prdt_name">
        <b class="_name">{{ item.title }}</b><br>
        <span class="_option">{{ item.company_name }}</span>
      </h2>
    </div>
    <div class="l-con-article clear_both product_qna_contents">
      <section class="_cs_wrap _support_wrapper _support_content_wrapper">
        <!-- <h3 class="_with_profill">
          <div class="symbol-circle">
            <img
              v-if="ConvertImage(item.seller_img).length > 0"
              :src="storageUrl + ConvertImage(item.seller_img)[0]"
              :alt="item.company_name"
              class="_imgtag"
            >
            <img
              v-else
              src="/images/img/thumbnail.png"
              :alt="item.company_name"
            >
          </div>
          <span>{{ item.company_name }}
          </span>
        </h3> -->
        <div class="_board">
          <!-- 등록된 문의가 있을 때 -->
          <ul
            v-if="qna.id"
            class="_board_layout _cs_layout _question_fill"
          >
            <li class="_cs_content _support_content">
              <div class="_support_content_wrap">
                <div
                  class="_board_title_label _support_content_title"
                  style="padding: 6px 70px;"
                >
                  <span
                    class="_name"
                    style="display: inline-block;position: unset;"
                  >{{ qna.nickname }}</span>
                  <span
                    class="_date"
                    style="display: inline-block;position: unset;padding: 0 5px;"
                  >{{ $moment(qna.q_datetime).format('YYYY-MM-DD hh:mm:ss') }}</span>
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
                <div class="_board_title_label _support_content_title _answer_content_title">
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
.top_title_wrap {
  position: fixed;
  top: 50px;
  left: 0px;
  width: 100%;
  height: 50px;
  border-bottom: 1px solid #f0f0f0;
  padding-left: 20px;
  background-color: white;
  z-index: 10;
  .review_img_box {
    float: left;
    width: 40px;
    height: 40px;
    margin-top: 5px;
    border: 1px solid #f5f5f5;
    border-radius: 8px;
    overflow: hidden;
    text-align: center;
    > img{
      vertical-align: middle;
    }
  }
  ._prdt_name {
    float: left;
    // 프로필 40px
    width: calc(100% - 40px);
    height: 100%;
    padding: 5px 10px;
    ._name {
      font-size: 1.125em;
      font-weight: 500;
    }
    ._option {
      font-size: 0.688em;
      font-weight: 300;
      color: #787878;
    }
  }
}

._board_layout {
  ._cs_content {
    border-bottom: 0px none;
  }
  ._name {
    left: 70px;
    &:before {
      right: -4px;
    }
  }
  ._date {
    left: calc(74px + 3em);
  }
}
.product_qna_contents {
  padding-top: 100px;
  ._board_title_label {
    padding: 12px 70px;
    background-image: none;
    &:before{
      content:'q';
      display: block;
      position: absolute;
      top: 10px;
      left: 20px;
      width: 40px;
      height: 40px;
      line-height: 40px;
      border-radius: 50%;
      background-color: #333;
      text-align: center;
      text-transform: uppercase;
      font-size: 1.125em;
      font-weight: 600;
      color: #fff;
    }
  }
  ._answer_content_title {
    &:before {
      content:'a';
    }
    ._name:before {
      content: none;
    }
  }
  ._support_content_desc {
    min-height: 250px;
    margin: 0px 20px;
    padding: 20px;
    background-color: #fafafa;
    font-size: 0.813em;
    font-weight: 200;
    color: #787878;
  }
}
</style>
