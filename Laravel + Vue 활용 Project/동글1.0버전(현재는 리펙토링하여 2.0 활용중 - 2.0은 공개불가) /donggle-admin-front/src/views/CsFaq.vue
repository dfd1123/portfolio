<template>
  <div id="app">
    <layout-header :title="null" title-mobile="자주묻는 질문" />

    <!-- contents -->
    <div id="admin-container" class="cs-container">
      <div class="wrapper">
        <!-- page content -->
        <div id="page-cs-faq-wrap" class="cs-content-wrap">
          <div class="grid-line-group clearfix">
            <cs-side-menu />

            <!-- cs content -->
            <div class="panel-default-container">
              <section class="_cs_con_wrap _cs_board_wrap">
                <div class="panel-default-title type_02 show-pc">
                  <h3>자주 묻는 질문</h3>
                </div>

                <template v-if="faqs !== null">
                  <div v-if="faqs.length > 0" class="panel-default">
                    <ul class="_board_layout">
                      <li
                        v-for="(faq, index) in faqs"
                        :key="faq.id"
                        class="_cs_content cs_faq_content"
                      >
                        <input
                          type="checkbox"
                          v-uniq-id="`faq-${index}`"
                          class="_board_title_box none"
                        />
                        <label v-uniq-for="`faq-${index}`" class="_board_title_label">
                          <span class="_title">{{faq.question}}</span>
                        </label>
                        <div class="_board_hidden">
                          <div class="_board_content">
                            <div class="_desc">
                              <p class="re_title">{{faq.question}}</p>
                              <p v-html="faq.answer" class="_desc_01"></p>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <div v-else class="nothing-history">
                    <div class="in-empty-icon show-pc">
                      <img src="/images/icon/empty_board.svg" alt="등록된 자주 묻는 질문이 없습니다." />
                    </div>
                    <div class="in-empty-icon show-mobile">
                      <img src="/images/icon/empty_board_m.svg" alt="등록된 자주 묻는 질문이 없습니다." />
                    </div>
                    <div class="in-empty-ment">등록된 자주 묻는 질문이 없습니다.</div>
                  </div>
                </template>
              </section>
            </div>
            <!-- cs content E -->
          </div>
        </div>
        <!-- page content E -->
      </div>
      <layout-footer class="show-pc" />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
export default {
  name: 'CsFaq',
  data () {
    return {
      faqs: null
    }
  },
  async created () {
    await this.fetchData()
  },
  methods: {
    async fetchData () {
      await this.searchClick()
    },
    async searchClick () {
      try {
        this.loading(true)

        const data = await this.$axios
          .get('/api/store/faq/list', {
            params: {}
          })
          .then(this.normalOrError)
          .then(this.resultOrError)

        this.faqs = data
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    }
  }
}
</script>
