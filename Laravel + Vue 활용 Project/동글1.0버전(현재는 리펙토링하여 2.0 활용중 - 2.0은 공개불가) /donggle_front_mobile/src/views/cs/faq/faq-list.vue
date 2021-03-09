<template>
  <div
    id="dg-cs-fnq-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <!-- 활성화된 메뉴 _list 에 active 추가 -->
      <article class="in-nav-section">
        <ul>
          <li class="_list">
            <router-link to="/notices">
              공지사항
            </router-link>
          </li>
          <li class="_list active">
            <router-link to="/faqs">
              자주묻는 질문
            </router-link>
          </li>
          <li class="_list">
            <router-link to="/qnas">
              1:1 문의
            </router-link>
          </li>
          <li class="_list">
            <router-link to="/events">
              이벤트
            </router-link>
          </li>
        </ul>
      </article>

      <div class="l-con-area">
        <!--
          등록된 글이 없을 때: ._fnq_wrap ._empty 에 .display_none 삭제,
                               ._fnq_wrap ._fill 에 .display_none
         -->
        <!-- 1) 자주 묻는 질문 -->
        <div class="l-con-article">
          <section class="_cs_wrap _fnq_wrap">
            <div class="_page_title_wrap">
              <h2>
                고객센터
                <button
                  type="button"
                  class="_back_btn"
                  @click="$router.go(-1)"
                >
                  뒤로가기
                </button>
              </h2>
            </div>
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
                    class="_board_title_wrap display_none"
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
                    src="/images/mobile/icon/empty_board_m.svg"
                    alt="등록된 자주뭍는 질문이 없습니다."
                  >
                </div>
                <div class="_text">
                  등록된 자주뭍는 질문이 없습니다.
                </div>
              </div>
            </div>
          </section>
        </div>
        <!-- 1) 자주 묻는 질문 E -->
      </div>
    </div>
  </div>
</template>

<script>
  export default {
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
