<template>
  <div
    id="dg-cs-support-wrapper"
    class="dg-cs-wrapper"
  >
    <!-- 1:1문의 작성 팝업 -->
    <QnaPopup
      :class="{'posFixed':openWritePopup}"
      :subject="form.subject"
      :question="form.question"
      :kind="popupKind"
      @close-popup="CloseWritePopup"
      @submit="Submit"
    />
    <!-- 1:1문의 작성 팝업 E -->
    <div class="l-cs-contents">
      <!-- 활성화된 메뉴 _list 에 active 추가 -->
      <article class="in-nav-section">
        <ul>
          <li class="_list">
            <router-link to="/notices">
              공지사항
            </router-link>
          </li>
          <li class="_list">
            <router-link to="/faqs">
              자주묻는 질문
            </router-link>
          </li>
          <li class="_list active">
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
                <button
                  type="button"
                  class="_write_popup_btn"
                  @click="OpenWritePopup('create')"
                >
                  문의하기
                </button>
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
                  class="_cs_content"
                >
                  <input
                    type="checkbox"
                    :id="'qna'+qna.id"
                    class="_board_title_wrap display_none"
                  >
                  <label
                    :for="'qna'+qna.id"
                    class="clear_both"
                  >
                    <div class="symbol-circle">
                      <img
                        src="/images/img/profile_admin.jpg"
                        alt="동글 로고"
                        class="_imgtag"
                      >
                    </div>
                    <div class="_board_title_label">
                      <span class="_title">
                        {{ qna.subject }}</span>
                      <span class="_name">동글 고객센터</span>
                      <span class="_date">{{ $moment(showQna.q_datetime).format('YYYY-MM-DD') }}</span>
                      <span
                        v-if="qna.status === 0"
                        class="_check"
                      >답변대기</span>
                      <span
                        v-else
                        class="_check _end"
                      >답변완료</span>
                    </div>
                  </label>
                  <div class="_board_hidden">
                    <div class="_board_content">
                      <div class="_title">
                        {{ qna.subject }}
                      </div>
                      <div class="_desc">
                        <p
                          class="_inquire"
                          v-html="qna.question"
                        ></p>
                        <p
                          v-if="qna.status === 1"
                          class="_answer"
                          v-html="qna.answer"
                        ></p>
                      </div>
                      <div class="dg-edit-del-btn-wrap">
                        <button
                          type="button"
                          v-if="qna.status === 0"
                          @click="OpenWritePopup('edit')"
                          class="rounded-btn-outline btn--edit"
                        >
                          수정
                        </button>
                        <button
                          type="button"
                          @click="QnaDelete()"
                          class="rounded-btn-outline btn--del"
                        >
                          삭제
                        </button>
                      </div>
                    </div>
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
                class="loading_wrap"
                v-show="bottomLoadingShow"
              >
                <Loading />
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
  import QnaPopup from '@/components/popup/qna-popup.vue'
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      QnaPopup,
      Loading
    },
    data: function () {
      return {
        showQna: {},
        qnas: [],
        form: {
          subject: null,
          question: null
        },
        openWritePopup: false,
        activeEl: 0,
        allCount: 0,
        popupKind: 'create',
        limit: 20,
        offset: 0,
        bottomLoadingShow: false
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.QnaLoad()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async QnaLoad () {
        this.busy = true

        const params = {
          limit: this.limit,
          offset: this.offset
        }
        try {
          const res = (await this.$http.get(this.$APIURI + 'qna/list', { params })).data

          if (res.state === 1) {
            this.qnas = this.qnas.concat(res.query.qnas)
            this.allCount = res.query.count
            this.offset += res.query.qnas.length
          } else {
            console.log('qna 리스트 가져오기 실패')
          }
        } catch (e) {
          console.log(e)
        }

        this.busy = false
      },
      async QnaStore () {
        try {
          const res = (await this.$http.post(this.$APIURI + 'qna', this.form)).data

          if (res.state === 1) {
            this.SuccessAlert('1:1문의 등록 완료')
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async QnaUpdate () {
        const params = this.form
        params.id = this.showQna.id

        try {
          const res = (await this.$http.put(this.$APIURI + 'qna/question', params)).data

          if (res.state === 1) {
            this.SuccessAlert('1:1문의 수정 완료')
            this.limit = this.qnas.length
            this.offset = 0
            this.qnas = []
            await this.QnaLoad()
            this.limit = 20
            this.activeEl = 0
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async QnaDelete (id) {
        const result = await this.Confirm('정말 해당 문의를 삭제하실건가요?')
        if (result) {
          try {
            const res = (await this.$http.delete(this.$APIURI + 'qna/' + id)).data

            if (res.state === 1) {
              this.SuccessAlert('1:1문의 삭제 완료')
              this.limit = this.qnas.length
              this.offset = 0
              this.qnas = []
              await this.QnaLoad()
              this.limit = 20
              this.activeEl = 0
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        }
      },
      OpenWritePopup (kind) {
        this.popupKind = kind

        if (kind === 'edit') {
          this.form.subject = this.showQna.subject
          this.form.question = this.showQna.question
        }

        document.body.style.overflowY = 'hidden'
        this.openWritePopup = true
      },
      CloseWritePopup () {
        document.body.style.overflowY = 'auto'
        this.openWritePopup = false
        this.form.subject = null
        this.form.question = null
      },
      ShowQna (id) {
        this.showQna = this.qnas.filter(qna => qna.id === id)[0]

        this.activeEl = this.showQna.id
      },
      Validation () {
        if (this.form.subject === null) {
          this.WarningAlert('문의 제목을 적어주세요!')
          return false
        }

        if (this.form.question === null) {
          this.WarningAlert('문의 내용을 적어주세요!')
          return false
        }

        return true
      },
      async Submit (form) {
        this.form = form
        if (this.Validation()) {
          if (this.popupKind === 'create') {
            document.body.style.overflowY = 'auto'
            this.openWritePopup = false
            this.$store.commit('ProgressShow')
            await this.QnaStore()
          } else {
            await this.QnaUpdate()
          }
          this.offset = 0
          this.qnas = []
          await this.QnaLoad()
          this.form.subject = null
          this.form.question = null
          this.$store.commit('ProgressHide')
        }
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.itemCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          await this.QnaLoad()
          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
