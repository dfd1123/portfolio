<template>
  <div
    id="dg-store-inquiry-wrapper"
    class="dg-store-wrapper"
  >
    <div class="l-store-contents l-store-qna-contents">
      <!-- 활성화된 메뉴 _list 에 active 추가 -->

      <div class="l-con-area">
        <!--
          등록된 문의가 없을 때: ._question_fill 에 .display_none
          등록된 문의가 있을 때: ._question_empty 와 ._question_empty_a 에 .display_none
          답변이 있을 때: ._no_response 에 display_none
          답변이 없을 때: ._response 에 display_none
          왼쪽 1:1 문의 영역의 높이가 576px 넘길 때 ._question_wrap 에 .over-y_scroll 추가
          1:1문의 클릭시 ._support_title 에 .active 추가
          답변이 없을 시 ._support_answer_content 에 ._no_answer 추가

          ._support_title_wrapper > ._write_popup_btn
          클릭시
          ._cs_support_write_popup_wrapper
          remove class .display_none
         -->
        <!-- 1) 1:1문의 -->
        <div class="l-con-article clear_both">
          <section class="_cs_wrap _support_wrapper _support_title_wrapper">
            <h3>
              스토어 문의
              <button
                type="button"
                class="_write_popup_btn"
                @click="OpenWritePopup('create')"
              >
                문의하기
              </button>
            </h3>
            <!-- 1:1문의 작성 팝업 -->
            <QnaPopup
              v-show="openWritePopup"
              :subject="form.subject"
              :question="form.question"
              :kind="popupKind"
              @close-popup="CloseWritePopup"
              @submit="Submit"
            />
            <!-- 1:1문의 작성 팝업 E -->
            <div class="_board over-y_scroll">
              <!-- 등록된 문의가 있을 때 -->
              <ul
                v-if="qnas.length > 0"
                v-infinite-scroll="QnaLoad"
                :infinite-scroll-disabled="busy"
                :infinite-scroll-distance="limit"
                class="_board_layout _cs_layout _question_wrap _question_fill"
              >
                <li
                  v-for="qna in qnas"
                  :key="'qna'+qna.id"
                  :class="['_cs_content _support_title',{'active':activeEl === qna.id}]"
                  @click="ShowQna(qna.id)"
                >
                  <div class="symbol-circle">
                    <img
                      v-if="ConvertImage(qna.profile_img).length > 0"
                      :src="qna.profile_img.includes('http')?ConvertImage(qna.profile_img)[0]:storageUrl+ConvertImage(qna.profile_img)[0]"
                      :alt="qna.nickname + '로고'"
                      class="_imgtag"
                    >
                    <img
                      v-else
                      src="/images/img/thumbnail.png"
                      alt="동글 기본 이미지 로고"
                      class="_imgtag"
                    >
                  </div>
                  <div class="_board_title_label">
                    <span class="_name">{{ qna.nickname }}</span>
                    <span
                      v-if="qna.status === 0"
                      class="_check _wait"
                    >답변대기</span>
                    <span
                      v-else-if="qna.status === 1"
                      class="_check _end"
                    >답변완료</span>
                    <span class="_date">{{ qna.q_datetime }}</span>
                    <span class="_title">{{ qna.subject }}</span>
                  </div>
                </li>
              </ul>
              <!-- 등록된 문의가 있을 때 E -->
              <!-- 등록된 문의가 없을 때 -->
              <ul
                v-else
                class="_board_layout _cs_layout _question_wrap _question_empty"
              >
                <li>등록된 문의가 없습니다.</li>
              </ul>
              <!-- 등록된 문의가 없을 때 E -->
            </div>
          </section>

          <section class="_cs_wrap _support_wrapper _support_content_wrapper">
            <h3 class="_with_profill">
              <div class="symbol-circle">
                <img
                  v-if="ConvertImage(store.profile_img).length > 0"
                  :src="store.profile_img.includes('http')?ConvertImage(store.profile_img)[0]:storageUrl + ConvertImage(store.profile_img)[0]"
                  alt="동글 로고"
                  class="_imgtag"
                >
                <img
                  v-else
                  src="/images/img/thumbnail.png"
                  alt="스토어 로고(동글)"
                  class="_imgtag"
                >
              </div>
              <router-link :to="'/store/'+storeId">
                {{ store.brandname }}
              </router-link>
            </h3>
            <div class="_board over-y_scroll">
              <!-- 등록된 문의가 있을 때 -->
              <ul
                v-if="qnas.length > 0 && activeEl !== 0"
                class="_board_layout _cs_layout _question_fill"
              >
                <li class="_cs_content _support_content _support_quastion_content">
                  <div class="_support_content_wrap clear_both">
                    <div class="_board_title_label _support_content_title">
                      <span class="_name">{{ showQna.nickname }}</span>
                      <span class="_date">{{ $moment(showQna.q_datetime).format('YYYY-MM-DD') }}</span>
                      <span class="_title">{{ showQna.subject }}</span>
                    </div>
                    <div
                      class="_support_content_desc"
                      v-html="showQna.question"
                    >
                    </div>
                    <div class="dg-edit-del-btn-wrap">
                      <button
                        type="button"
                        v-if="showQna.status === 0"
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
                </li>
                <li
                  v-if="showQna.status === 0"
                  class="_cs_content _support_content _support_answer_content _no_response"
                >
                  <div class="_support_content_wrap">
                    <div class="_board_title_label _support_content_title">
                      <span class="_name">{{ store.brandname }}</span>
                      <span class="_title">답변 대기중</span>
                    </div>
                    <div class="_support_content_desc">
                      <p class="answer_desc">
                        답변 대기 중입니다.
                      </p>
                    </div>
                  </div>
                </li>
                <li
                  v-else
                  class="_cs_content _support_content _support_answer_content _response "
                >
                  <div class="_support_content_wrap">
                    <div class="_board_title_label _support_content_title">
                      <span class="_name">{{ store.brandname }}</span>
                      <span class="_date">{{ $moment(showQna.a_datetime).format('YYYY-MM-DD') }}</span>
                      <span class="_title">[{{ showQna.subject }}] 질문에 대한 답변입니다.</span>
                    </div>
                    <div
                      class="_support_content_desc"
                      v-html="showQna.answer"
                    >
                    </div>
                  </div>
                </li>
              </ul>
              <!-- 등록된 문의가 있을 때 E -->
              <ul
                v-else-if="qnas.length > 0 && activeEl === 0"
                class="_board_layout _cs_layout _question_empty_a"
              >
                <li>표시할 문의가 없습니다.</li>
              </ul>
              <!-- 등록된 문의가 없을 때  -->
              <ul
                v-else-if="qnas.length === 0"
                class="_board_layout _cs_layout _question_empty_a"
              >
                <li>등록된 문의가 없습니다.</li>
              </ul>
              <!-- 등록된 문의가 없을 때 E -->
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
  import infiniteScroll from 'vue-infinite-scroll'

  export default {
    directives: { infiniteScroll },
    components: {
      QnaPopup
    },
    data: function () {
      return {
        store: {},
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
        busy: false
      }
    },
    props: {
      storeId: {
        type: String,
        required: true
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.QnaLoad()
      await this.StoreInfo()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async StoreInfo () {
        const params = {
          id: this.storeId
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'seller/view', { params })).data
          if (res.state === 1) {
            this.store = res.query
          }
        } catch (e) {
          console.log(e)
        }
      },
      async QnaLoad () {
        this.busy = true

        const params = {
          store_id: this.storeId,
          kind: 'question',
          limit: this.limit,
          offset: this.offset
        }
        try {
          const res = (await this.$http.get(this.$APIURI + 'store_qna', { params })).data

          if (res.state === 1) {
            this.qnas = res.query.qnas
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
        const params = this.form
        params.store_id = this.storeId
        try {
          const res = (await this.$http.post(this.$APIURI + 'store_qna', params)).data

          if (res.state === 1 && res.query) {
            this.SuccessAlert('스토어 문의 등록 완료')

            const params = {
              target_mem_id: this.store.uid,
              not_type: 'store_qna',
              not_content_id: this.storeId,
              not_message: this.$store.state.user.nickname + '님 께서 스토어 문의를 남기셨습니다.',
              not_url: 'https://store.dong-gle.co.kr/inquiry-store-list'
            }

            this.NotifyStore(params)
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
          const res = (await this.$http.put(this.$APIURI + 'store_qna/question', params)).data

          if (res.state === 1 && res.query === 1) {
            this.SuccessAlert('1:1문의 수정 완료')
            await this.QnaLoad()
            this.activeEl = 0
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async QnaDelete () {
        const result = await this.Confirm('정말 해당 문의를 삭제하실건가요?')
        if (result) {
          try {
            this.$store.commit('ProgressShow')
            const res = (await this.$http.delete(this.$APIURI + 'store_qna/' + this.showQna.id)).data

            if (res.state === 1 && res.query === 1) {
              this.SuccessAlert('1:1문의 삭제 완료')
              this.offset = 0
              await this.QnaLoad()
              this.activeEl = 0
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          } finally {
            this.$store.commit('ProgressHide')
          }
        }
      },
      OpenWritePopup (kind) {
        this.popupKind = kind

        if (kind === 'edit') {
          this.form.subject = this.showQna.subject
          this.form.question = this.showQna.question
        }
        this.openWritePopup = true
      },
      CloseWritePopup () {
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
      async Submit (form, kind) {
        this.form = form
        this.popupKind = kind
        if (this.Validation()) {
          this.openWritePopup = false
          this.$store.commit('ProgressShow')

          if (this.popupKind === 'create') {
            await this.QnaStore()
          } else {
            await this.QnaUpdate()
          }
          this.offset = 0
          await this.QnaLoad()

          this.$store.commit('ProgressHide')
          this.form.subject = null
          this.form.question = null
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
