<template>
  <section class="_qna_wrap">
    <h3>상품문의<span>({{ NumberFormat(qnaCount) }})</span></h3>
    <button
      type="button"
      class="in-more _with_title"
      @click="$emit('popup-event')"
    >
      <span>상품문의 작성하기</span>
      <img
        src="/images/btn/btn_write.svg"
        alt="btn"
      >
    </button>
    <!-- 상품문의가 있을 때 -->
    <!--
              tr._board_title와 tr._board_hidden이 한 묶음.
              ._board_title 클릭시 ._board_hidden슬라이드
              답변이 달리지 않은 상태: td._answer > div._wait
              답변이 달린 상태:td._answer > div._complete
             -->
    <div
      v-if="qnas.length > 0"
      class="_board _fill"
    >
      <ul class="_board_layout _fill">
        <li
          v-for="(qna, index) in qnas"
          :key="'qna'+index"
          class="_inquire_content"
        >
          <input
            type="checkbox"
            :id="'qna'+index"
            class="_board_title_wrap display_none"
            :disabled="qna.secret === 1 && qna.q_id !== $store.state.user.id && sellerId !== $store.state.user.id"
          >
          <label
            :for="'qna'+index"
            class="_board_title _answer"
          >
            <div
              v-if="qna.status === 0"
              class="_btn _wait"
            >답변대기</div>
            <div
              v-else
              class="_btn _complete"
            >답변완료</div>
            <div :class="['_title', {'unlock':qna.secret === 0 || qna.q_id === $store.state.user.id || sellerId === $store.state.user.id}]">{{ qna.subject }}</div>
            <span class="_writer">{{ qna.q_name }}</span>
            <span class="_date">{{ $moment(qna.q_datetime).format('YYYY-MM-DD') }}</span>
          </label>
          <div class="_board_content">
            <div class="_desc clear_both">
              <span class="qna-icon">Q</span>
              <div
                class="qna-text"
                v-html="qna.question"
                style="display:inline-block;"
              ></div>
            </div>
            <div
              v-if="qna.status === 1"
              class="_desc _desc_answer clear_both"
            >
              <span class="qna-icon">A</span>
              <div
                class="qna-text"
                v-html="qna.answer"
                style="display:inline-block;"
              ></div>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <!-- 상품문의가 있을 때 E -->
    <!-- 상품문의가 없을 때 -->
    <div
      v-else
      class="_empty"
    >
      <div class="_img_box">
        <img
          src="/images/icon/empty_inquiry.svg"
          alt="등록된 문의가 없습니다."
        >
      </div>
      <div class="_text">
        등록된 문의가 없습니다.
      </div>
    </div>
    <div
      v-if="qnaCount > qnaLimit"
      class="_view_more"
    >
      <router-link :to="'/item_qna/list/'+itemId">
        상품문의 더보기
      </router-link>
    </div>
    <!-- 상품문의가 없을 때 E -->
    <!--
    <Pagination
      :items="qnas"
      :item-cnt="qnaCount"
      :page-size="qnaLimit"
      :initial-page="qnaPage"
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
      qnas: {
        type: Array,
        required: true
      },
      qnaCount: {
        type: Number,
        required: true
      },
      qnaLimit: {
        type: Number,
        required: true
      },
      qnaPage: {
        type: Number,
        required: true
      },
      sellerId: {
        type: Number,
        default: null
      },
      itemId: {
        type: String,
        required: true
      }
    },
    watch: {
      qnaPage () {
        this.currentPage = this.qnaPage
      }
    },
    mounted () {
      /*
      if (this.qnaCount > this.qnaLimit) {
        this.$refs.pagination.SetPage(this.currentPage, false)
      }
      */
    },
    methods: {
      OnChangePage (currentPage) {
        this.currentPage = currentPage
        this.$emit('qnas-load', currentPage)
        // this.fetchData()
      }
    }
  }
</script>

<style lang="scss" scoped>
  ._qna_wrap ._board_layout ._title.unlock {
    padding-left: 100px;
    background-image: none;
  }
</style>
