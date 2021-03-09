<template>
  <div
    id="dg-cs-fnq-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <!-- 활성화된 메뉴 _list 에 active 추가 -->
      <CsGnb />

      <div class="l-con-area">
        <!--
          등록된 글이 없을 때: ._fnq_wrap ._empty 에 .display_none 삭제,
                               ._fnq_wrap ._fill 에 .display_none
         -->
        <!-- 1) 자주 묻는 질문 -->
        <div class="l-con-article">
          <section class="_cs_wrap _fnq_wrap">
            <h3>자주 묻는 질문</h3>
            <div class="_board">
              <!-- 등록된 자주 묻는 질문이 있을 때 -->
              <ul
                v-if="faqs.length > 0"
                class="_board_layout _cs_layout _fill"
              >
                <li
                  v-for="(faq,index) in faqs"
                  :key="'faq'+index"
                  class="_cs_content"
                >
                  <input
                    type="checkbox"
                    :id="'faq_'+faq.id"
                    class="_board_title_wrap"
                  >
                  <label
                    :for="'faq_'+faq.id"
                    class="_board_title_label"
                  >
                    <span class="_title">{{ faq.question }}</span>
                  </label>
                  <div class="_board_hidden">
                    <div class="_board_content">
                      <div
                        class="_desc"
                        v-html="faq.answer"
                      >
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
              <!-- 등록된 자주 묻는 질문이 있을 때 E -->
              <!-- 등록된 자주 묻는 질문이 없을 때 -->
              <div
                v-else
                class="_empty"
              >
                <div class="_img_box">
                  <img
                    src="/images/icon/empty_board.svg"
                    alt="등록된 게시글이 없습니다."
                  >
                </div>
                <div class="_text">
                  등록된 게시글이 없습니다.
                </div>
              </div>
              <!-- 등록된 자주 묻는 질문이 없을 때 E -->
            </div>
          </section>
        </div>
        <!-- 1) 자주 묻는 질문 E -->
      </div>
    </div>
  </div>
</template>

<script>
  import CsGnb from '@/components/cs/gnb.vue'
  export default {
    components: {
      CsGnb
    },
    data: function () {
      return {
        faqs: []
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      this.faqs = (await this.$http.get(this.$APIURI + 'faq/list')).data.query
      this.$store.commit('ProgressHide')
    }
  }
</script>

<style lang="scss" scoped>
</style>
