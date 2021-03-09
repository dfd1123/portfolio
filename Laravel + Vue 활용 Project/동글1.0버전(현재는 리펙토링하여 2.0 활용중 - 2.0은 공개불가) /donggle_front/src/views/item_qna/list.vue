<template>
  <div id="dg-product-review-detail-wrapper">
    <div class="dg-content_center">
      <section id="product_review">
        <div
          class="review-detail_title clear_both"
          @click="$router.push('/product/view/'+item.item_id)"
        >
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
          <h2>
            <b>{{ item.title || '-' }}</b><br>
            <span>{{ item.company_name || '-' }}</span>
          </h2>
        </div>
        <!-- review detail, comment -->
        <div class="review-detail_and_comment_wrap">
          <!-- 문의 -->
          <div class="_review_wrap _deffrent_review_wrap">
            <h3>이 상품의 다른 문의</h3>
            <!-- 문의가 있을 때 1페이지에 5개씩 보이기 -->
            <ul
              v-if="qnas.length > 0"
              class="_review _fill"
              style="border-bottom: none;padding: 0;"
            >
              <li
                class="_inquire_content clear_both"
                v-for="qna in qnas"
                :key="'qna'+qna.id"
                @click="$router.push({path:'/item_qna/view/'+ qna.item_id + '/' + qna.id, query: form})"
              >
                <div class="_qna_title_wrap">
                  <div class="review_img_box">
                    <img
                      v-if="ConvertImage(qna.profile_img).length > 0"
                      :src="qna.profile_img.includes('http')?ConvertImage(qna.profile_img)[0]:storageUrl+ConvertImage(qna.profile_img)[0]"
                      :alt="qna.nickname"
                    >
                    <img
                      v-else
                      src="/images/img/profile_admin.jpg"
                      :alt="qna.nickname"
                    >
                  </div>
                  <div class="_title_box">
                    <div
                      class="_title"
                      style="margin-left:10px;"
                    >
                      {{ qna.subject }}
                    </div>
                    <span class="_badge _wait">답변대기</span>
                    <span class="_badge _check">답변완료</span>
                    <span class="_date">{{ $moment(qna.q_datetime).format('YYYY-MM-DD hh:mm:ss') }}</span>
                  </div>
                </div>
              </li>
            </ul>
            <!-- 문의가 있을 때 E -->
            <!-- 문의가 없을 때 -->
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
                등록된 문의가 없습니다.
              </div>
            </div>
            <!-- 문의가 없을 때 E -->
            <Pagination
              :items="qnas"
              :item-cnt="form.count"
              :page-size="form.limit"
              :initial-page="form.page"
              ref="pagination"
              @changePage="OnChangePage"
            />
          </div>
          <!-- 문의 E -->
        </div>
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
      const res2 = await this.QnaLoad()
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
      async QnaLoad () {
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
  .review-detail_title {
    cursor: pointer;
  }
  .review-detail_and_comment_wrap {
    border-bottom: none;
  }
  ._inquire_content {
    border-bottom: 1px solid #f0f0f0;
    height: 60px;
  }
  ._qna_title_wrap {
    height: 60px;
    .review_img_box {
      float: left;
      width: 40px;
      height: 40px;
      margin: 10px;
      border: 1px solid #f5f5f5;
      border-radius: 50%;
      overflow: hidden;
      text-align: center;
      > img {
        vertical-align: middle;
      }
    }
    ._title_box {
      position: relative;
      float: left;
      width: calc(100% - 60px);
      height: 100%;
      ._writer {
        position: absolute;
        top: 50%;
        left: 0px;
        transform: translateY(-50%);
        font-size: 0.9375em;
      }
      ._title {
        @include ellipse(1, 60px);

        margin: 0px 6em 0px 5em;
      }
      ._badge {
        position: absolute;
        top: 50%;
        right: 11em;
        width: 79px;
        height: 38px;
        line-height: 38px;
        transform: translateY(-50%);
        border-radius: 5px;
        text-align: center;
        font-size: 0.75em;
        font-weight: 300;
      }
      ._wait {
        border: 1px solid #f0f0f0;
        background-color: #f0f0f0;
        color: #b4b4b4;
      }
      ._check {
        border: 1px solid #fc4ead;
        background-color: white;
        color: #fc4ead;
      }
      ._date {
        position: absolute;
        top: 50%;
        right: 0px;
        transform: translateY(-50%);
        font-size: 0.75em;
        font-weight: 300;
        color: #787878;
      }
    }
  }
</style>
