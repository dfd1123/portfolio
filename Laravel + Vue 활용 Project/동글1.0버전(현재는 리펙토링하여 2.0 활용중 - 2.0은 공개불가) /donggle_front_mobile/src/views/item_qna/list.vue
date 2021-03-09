<template>
  <div
    id="dg-product-qna-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <div class="l-con-area">
        <!--
          등록된 문의가 없을 때: ._question_fill 에 .display_none
          등록된 문의가 있을 때: ._question_empty 와 ._question_empty_a 에 .display_none
          답변이 있을 때: ._no_response 에 display_none
          답변이 없을 때: ._response 에 display_none
          1:1문의 클릭시 ._support_title 에 .active 추가
          답변이 없을 시 ._support_answer_content 에 ._no_answer 추가

          ._support_title_wrapper ._write_popup_btn
          클릭시
          ._cs_support_write_popup_wrapper
          remove class .display_none
         -->
        <!-- 1) 1:1문의 -->
        <div class="l-con-article clear_both">
          <section class="_cs_wrap _fnq_wrap _support_wrap _support_title_wrapper">
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
            <div class="review-detail_title clear_both">
              <div class="review_img_box">
                <img
                  v-if="ConvertImage(item.images || '[]').length > 0"
                  :src="storageUrl + ConvertImage(item.images)[0]"
                  :alt="item.title || '-'"
                >
                <img
                  v-else
                  src="/images/img/thumbnail.png"
                  :alt="item.title || '-'"
                >
              </div>
              <h2 class="_tit">
                <b>{{ item.title || '-' }}</b><br>
                <span>{{ item.company_name || '-' }}</span>
              </h2>
            </div>
            <div class="_board">
              <!-- 등록된 문의가 있을 때 -->
              <ul
                v-if="qnas.length > 0"
                class="_board_layout _cs_layout _fill"
              >
                <li
                  v-for="qna in qnas"
                  :key="'qna'+qna.id"
                  class="_cs_content clear_both"
                  @click="$router.push({path:'/item_qna/view/'+ qna.item_id + '/' + qna.id, query: form})"
                >
                  <div class="symbol-circle">
                    <img
                      v-if="ConvertImage(qna.profile_img).length > 0"
                      :src="qna.profile_img.includes('http')?ConvertImage(qna.profile_img)[0]:storageUrl+ConvertImage(qna.profile_img)[0]"
                      alt="동글 로고"
                      class="_imgtag"
                    >
                    <img
                      v-else
                      src="/images/img/profile_admin.jpg"
                      alt="동글 로고"
                      class="_imgtag"
                    >
                  </div>
                  <div class="_board_title_label">
                    <span class="_title">
                      {{ qna.subject }}</span>
                    <div class="_writer_wrap">
                      <span class="_name">{{ qna.nickname }}</span>
                      <span class="_date">{{ $moment(qna.q_datetime).format('YYYY-MM-DD hh:mm:ss') }}</span>
                    </div>
                    <span
                      class="_check"
                      style="background-color: #ffffff;border-color: #fc4ead;color: #fc4ead;"
                      v-if="qna.answer"
                    >답변완료</span>
                    <span
                      class="_check"
                      v-else
                    >답변대기</span>
                  </div>
                </li>
              </ul>
              <!-- 등록된 문의가 있을 때 E -->
              <!-- 등록된 문의가 없을 때 -->
              <div
                v-else
                class="_empty"
              >
                <div class="_img_box">
                  <img
                    src="/images/mobile/icon/empty_board_m.svg"
                    alt="등록된 문의가 없습니다."
                  >
                </div>
                <div class="_text">
                  등록된 문의가 없습니다.
                </div>
              </div>
              <!-- 등록된 문의가 없을 때 E -->
              <div
                class="_view_more"
                v-if="qnas.length !== form.count"
                @click="QnaMore(form.page + 1)"
              >
                <button>문의 더보기</button>
              </div>
            </div>
          </section>
        </div>
        <!-- 1) 1:1문의 E -->
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        item: {},
        qnas: [],
        form: {
          item_id: null,
          limit: 30,
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

      const res1 = (await this.$http.get(this.$APIURI + 'items/view', { params: { item_id: this.itemId } })).data
      const res2 = await this.QnaLoad(1)
      this.item = res1.query.item
      this.qnas = res2.item_qna
      this.form.count = res2.count

      this.$router.replace({ name: 'item-qna-list', query: this.form })

      this.$store.commit('ProgressHide')
    },
    mounted () {
      if (this.form.count > this.form.limit) {
        this.$refs.pagination.SetPage(this.form.page, false)
      }
    },
    methods: {
      async QnaLoad (page) {
        this.form.page = page
        const params = this.form

        try {
          const res = (await this.$http.get(this.$APIURI + 'item_qna/item_qa', { params })).data

          if (res.state === 1) {
            return res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async QnaMore (page) {
        const res = await this.QnaLoad(page)

        this.qnas.push(res.item_qna)
        this.form.count = res.count
      },
      async OnChangePage (currentPage) {
        this.form.page = currentPage

        this.$store.commit('ProgressShow')

        const res = await this.QnaLoad()
        this.reviews = res.reviews
        this.form.count = res.count

        this.$store.commit('ProgressHide')
      }
    }
  }
</script>

<style lang="scss" scoped>
  #dg-product-qna-wrapper {
    padding-top: 110px;
    ._writer_wrap {
      position: absolute;
      bottom: 12px;
      left: 11px;
      width: 100%;
      ._name,
      ._date {
        position: relative;
        bottom: 0;
        left: 0;
      }
      ._name:before {
        right: -4px;
      }
      ._date {
        margin-left: 8px;
      }
    }
  }
</style>
