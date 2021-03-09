<template>
  <div id="app">
    <layout-header :title="null" title-mobile="관리자 1:1문의">
      <template v-slot:before-button-right-mobile>
        <input
          type="button"
          class="mobile-btn-theme"
          value="1:1문의작성"
          @click="popup.isVisible = true"
        />
      </template>
    </layout-header>

    <!-- contents -->
    <div id="admin-container" class="cs-container">
      <div class="wrapper">
        <!-- page content -->
        <div id="page-cs-support-wrap" class="cs-content-wrap">
          <div class="grid-line-group clearfix">
            <cs-side-menu />

            <!-- cs content - pc -->
            <div class="panel-default-container show-pc">
              <section class="_cs_con_wrap _cs_title_con_wrap">
                <div class="panel-default-title type_02 show-pc">
                  <h3>
                    관리자 1:1문의
                    <!-- @@ 누르면 문의 작성하기 팝업 등장 -->
                    <button class="_write_popup_btn" @click="popup.isVisible = true">문의하기</button>
                  </h3>
                </div>

                <template v-if="qnas !== null">
                  <div v-if="qnas.length > 0" class="panel-default">
                    <ul class="_board_layout y-scroll">
                      <li
                        v-for="qna in qnas"
                        :key="qna.id"
                        class="_cs_content _support_content _support_quastion clearfix"
                        :class="[selectedQna && (qna.id === selectedQna.id) ? 'active' : '']"
                        @click="selectedQna = qna"
                      >
                        <div class="_title_wrap">
                          <div class="in-store-profile">
                            <div class="symbol-circle wi_48">
                              <img src="/images/img/profile_admin.jpg" alt="동글 로고" class="_imgtag" />
                            </div>
                          </div>
                          <div class="_board_title_label">
                            <span class="_name">동글 관리자 고객센터</span>
                            <span v-if="qna.status === 0" class="_check _wait">답변대기</span>
                            <span v-else-if="qna.status === 1" class="_check">답변완료</span>
                            <span
                              class="_date"
                            >{{qna.q_datetime ? qna.q_datetime.split(' ')[0] : '-'}}</span>
                            <span class="_title">{{qna.subject || '-'}}</span>
                          </div>
                        </div>
                      </li>
                      <button
                        v-show="pagination.count && pagination.hasNext"
                        type="button"
                        class="more-view-btn"
                        @click="showMoreButtonClick"
                      >
                        <img
                          src="/images/icon/arrow_bt_navy.svg"
                          alt="아래 화살표"
                          class="in-arrow-icon"
                        />
                        <span>더보기</span>
                      </button>
                    </ul>
                  </div>
                  <div v-else class="nothing-history">
                    <div class="in-empty-icon">
                      <img src="/images/icon/empty_inquiry.svg" alt="등록된 문의가 없습니다." />
                    </div>
                    <div class="in-empty-ment">등록된 문의가 없습니다.</div>
                  </div>
                </template>
              </section>

              <section class="_cs_con_wrap _cs_desc_con_wrap">
                <div class="panel-default-title type_03">
                  <h3 class="_with_profill">
                    <div class="in-store-profile">
                      <div class="symbol-circle">
                        <img src="/images/img/profile_admin.jpg" alt="동글 로고" class="_imgtag" />
                      </div>
                    </div>
                    <span>동글 관리자 고객센터</span>
                  </h3>
                </div>

                <template v-if="qnas !== null && selectedQna">
                  <div v-if="qnas.length > 0" class="panel-default">
                    <ul v-if="selectedQna" class="_board_layout y-scroll">
                      <!-- 질문내용 -->
                      <li class="_cs_content _support_content _support_answer">
                        <div class="_support_desc_wrap _quastion">
                          <div class="_board_title_label _answer_title">
                            <div class="_info">
                              <span class="_name">{{store.company_name}}</span>
                              <span
                                class="_date"
                              >{{_get((selectedQna.q_datetime || '').split(' '), 0) || '-'}}</span>
                            </div>
                            <div class="re_title">
                              <span>{{selectedQna.subject}}</span>
                            </div>
                          </div>
                          <div v-html="selectedQna.question" class="_support_desc"></div>
                        </div>
                      </li>
                      <!-- END 질문내용 -->

                      <!-- 답변대기 -->
                      <li
                        v-if="selectedQna.status === 0"
                        class="_cs_content _support_content _support_answer"
                      >
                        <div class="_support_desc_wrap">
                          <div class="_board_title_label _answer_title">
                            <div class="_info">
                              <span class="_name">동글 관리자 고객센터</span>
                            </div>
                            <div class="re_title">
                              <span>답변 대기중</span>
                            </div>
                          </div>
                          <div class="_support_desc">
                            <p class="answer_desc _wite">답변 대기 중입니다.</p>
                          </div>
                        </div>
                      </li>
                      <!-- END 답변대기 -->

                      <!-- 답변완료 -->
                      <li
                        v-else-if="selectedQna.status === 1"
                        class="_cs_content _support_content _support_answer"
                      >
                        <div class="_support_desc_wrap">
                          <div class="_board_title_label _answer_title">
                            <div class="_info">
                              <span class="_name">동글 관리자 고객센터</span>
                              <span
                                class="_date"
                              >{{_get((selectedQna.a_datetime || '').split(' '), 0) || '-'}}</span>
                            </div>
                            <div class="re_title">
                              <span>답변 완료</span>
                            </div>
                          </div>
                          <div v-html="selectedQna.answer" class="_support_desc"></div>
                        </div>
                      </li>
                      <!-- END 답변완료 -->
                    </ul>
                  </div>

                  <div v-else class="nothing-history">
                    <div class="in-empty-icon">
                      <img src="/images/icon/empty_inquiry.svg" alt="등록된 문의가 없습니다." />
                    </div>
                    <div class="in-empty-ment">등록된 문의가 없습니다.</div>
                  </div>
                </template>
              </section>
            </div>
            <!-- cs content - pc E -->

            <!-- cs content - mobile -->
            <div class="panel-default-container show-mobile">
              <section class="_cs_con_wrap _cs_board_wrap">
                <div class="panel-default-title type_02 show-pc">
                  <h3>관리자 1:1 문의</h3>
                </div>
                <template v-if="qnas !== null">
                  <div v-if="qnas.length > 0" class="panel-default">
                    <ul v-for="qna in qnas" :key="qna.id" class="_board_layout">
                      <li class="_cs_content cs_faq_content">
                        <input
                          type="checkbox"
                          v-uniq-id="`qna-${qna.id}`"
                          class="_board_title_box none"
                        />
                        <label v-uniq-for="`qna-${qna.id}`" class="_title_wrap">
                          <div class="in-store-profile">
                            <div class="symbol-circle">
                              <img src="/images/img/profile_admin.jpg" alt="동글 로고" class="_imgtag" />
                            </div>
                          </div>
                          <div class="_board_title_label">
                            <span class="_title">{{qna.subject || '-'}}</span>
                            <span class="_name">동글 관리자 고객센터</span>
                            <span
                              class="_date"
                            >{{qna.q_datetime ? qna.q_datetime.split(' ')[0] : '-'}}</span>
                            <span v-if="qna.status === 0" class="_check _wait">답변대기</span>
                            <span v-else-if="qna.status === 1" class="_check">답변완료</span>
                          </div>
                        </label>
                        <div class="_board_hidden">
                          <div class="_board_content">
                            <div class="_desc">
                              <p class="re_title">{{qna.subject || '-'}}</p>
                              <p class="_desc_q">{{qna.question || '-'}}</p>
                              <p class="_desc_a _wite">답변 대기중</p>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                    <button
                      v-show="pagination.count && pagination.hasNext"
                      type="button"
                      class="more-view-btn"
                      @click="showMoreButtonClick"
                    >
                      <img src="/images/icon/arrow_bt_navy.svg" alt="아래 화살표" class="in-arrow-icon" />
                      <span>더보기</span>
                    </button>
                  </div>
                  <div v-else class="nothing-history">
                    <div class="in-empty-icon">
                      <img src="/images/icon/empty_inquiry.svg" alt="등록된 관리자 1:1 문의가 없습니다." />
                    </div>
                    <div class="in-empty-ment">등록된 관리자 1:1 문의가 없습니다.</div>
                  </div>
                </template>
              </section>
            </div>
            <!-- cs content - mobile E -->

            <!-- @@ 문의 작성하기 팝업 popup-inquiry-write.html -->
            <div
              class="popup-container bgc-none"
              :style="{display: popup.isVisible ? 'block' : 'none'}"
            >
              <div class="popup-bg"></div>
              <div class="popup-content-wrap">
                <div class="popup-hd">
                  <h1 class="popup-tit">관리자 1:1 문의 작성</h1>
                  <button class="icon-close-btn-wh" @click="popup.isVisible = false">닫기</button>
                </div>
                <!-- content -->
                <div class="popup-content popup-write-con">
                  <section class="popup-content-profill">
                    <h2 class="_with_profill clearfix">
                      <div class="in-store-profile">
                        <div class="symbol-circle">
                          <img src="/images/img/profile_admin.jpg" alt="동글 로고" class="_imgtag" />
                        </div>
                        <span class="_name">동글 관리자 고객센터</span>
                      </div>
                    </h2>
                  </section>
                  <section class="popup-content-title">
                    <h2 class="none">문의사항</h2>
                    <div class="in-desc">
                      <input type="text" v-model="write.subject" placeholder="제목" />
                    </div>
                  </section>
                  <section class="popup-content-detail">
                    <h2 class="none">상세내용</h2>
                    <div class="in-desc">
                      <textarea v-model="write.question" class="_txt" placeholder="내용"></textarea>
                    </div>
                  </section>
                  <div class="popup-btn-wrap">
                    <button
                      class="square-md-btn btn-outline-gray close-btn"
                      @click="popup.isVisible = false"
                    >닫기</button>
                    <button class="square-md-btn btn-gradient" @click.prevent="saveQuestion">작성완료</button>
                  </div>
                </div>
                <!-- content E -->
              </div>
            </div>
            <!-- 문의 작성하기 팝업 E -->
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
  name: 'CsSupport',
  data () {
    return {
      qnas: null,
      selectedQna: null,
      popup: {
        isVisible: false
      },
      write: {
        subject: null,
        question: null
      },
      pagination: {
        hasNext: true,
        limit: 10,
        page: 1,
        count: null
      }
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
          .get('/api/store/qna/list', {
            params: {
              page_size: this.pagination.limit,
              page: this.pagination.page
            }
          })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(this.updateCursor)

        this.qnas = (this.qnas || []).concat(data.qnas)
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async saveQuestion () {
      try {
        this.loading(true)

        if (!this.write.subject) {
          alert('제목을 입력해 주시기 바랍니다')
          return
        }

        if (!this.write.question) {
          alert('내용을 입력해 주시기 바랍니다')
          return
        }

        await this.$axios
          .post('/api/store/qna', {
            subject: this.write.subject,
            question: this.write.question
          })
          .then(this.normalOrError)
          .then(this.alertIfMessage)

        this.popup.isVisible = false

        this.qnas = []
        this.resetCursor()
        await this.searchClick()
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async showMoreButtonClick () {
      if (this.moveCursor()) {
        await this.searchClick()
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#page-cs-support-wrap ._support_answer {
  padding: 19px;
}
</style>
