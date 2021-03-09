<template>
  <div
    id="dg-store-inquiry-wrapper"
    class="dg-store-wrapper"
  >
    <div class="l-store-qna-contents">
      <div class="l-con-area">
        <StoreQnaPopup
          :class="{'posFixed': openWritePopup}"
          :store="store"
          :subject="form.subject"
          :question="form.question"
          :kind="popupKind"
          @close-popup="CloseWritePopup"
          @submit="Submit"
        />
        <!--
          등록된 문의가 없을 때: ._question_fill 에 .display_none
          등록된 문의가 있을 때: ._question_empty 와 ._question_empty_a 에 .display_none
          답변이 있을 때: ._no_response 에 display_none
          답변이 없을 때: ._response 에 display_none
          왼쪽 1:1 문의 영역의 높이가 576px 넘길 때 ._question_wrap 에 .over-y_scroll 추가
          스토어문의 클릭시 ._support_title 에 .active 추가
          답변이 없을 시 ._support_answer_content 에 ._no_answer 추가

          ._support_title_wrapper > ._write_popup_btn
          클릭시
          .store_qna_write_popup_wrapper
          remove class .display_none
         -->
        <!-- 1) 스토어문의 -->
        <div class="l-con-article clear_both">
          <!-- 2. 스토어 문의 답변 -->
          <section class="inquiry_answer_wrap">
            <div class="_page_title_wrap">
              <h2>
                <div class="symbol-circle">
                  <img
                    v-if="ConvertImage(store.profile_img).length > 0"
                    :src="storageUrl+ConvertImage(store.profile_img)[0]"
                    :alt="store.brandname + '로고'"
                    class="_imgtag"
                  >
                  <img
                    v-else
                    src="/images/img/thumbnail.png"
                    alt="스토어 사진"
                    class="_imgtag"
                  >
                </div>
                <router-link :to="'/store/'+qna.store_id">
                  {{ store.brandname }} >
                </router-link>
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
              <!-- 등록된 문의가 있을 때 -->
              <ul class="_board_layout _question_fill">
                <li class="_cs_content _qna_content">
                  <div class="_qna_content_wrap">
                    <div class="_board_title_label _qna_content_title">
                      <!-- ._name 글자만큼 .inquiry_answer_wrap ._board_layout ._date 에 padding-left 변경 -->
                      <span class="_name">{{ qna.q_name }}</span>
                      <span class="_date">{{ $moment(qna.q_datetime).format('YYYY-MM-DD') }}</span>
                      <span class="_title">{{ qna.subject }}</span>
                    </div>
                    <div class="_qna_content_desc">
                      <p v-html="qna.question">
                      </p>
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
                </li>
                <li
                  v-if="qna.status === 0"
                  class="_cs_content _qna_content _qna_answer_content _no_response"
                >
                  <div class="_qna_content_wrap">
                    <div class="_board_title_label _qna_content_title _answer_content_title">
                      <span class="_name">{{ store.brandname }}</span>
                      <span class="_title">답변 대기중</span>
                    </div>
                    <div class="_qna_content_desc">
                      <p class="_rate">
                        답변 대기 중입니다.
                      </p>
                    </div>
                  </div>
                </li>
                <li
                  v-else
                  class="_cs_content _qna_content _qna_answer_content _response"
                >
                  <div class="_qna_content_wrap">
                    <div class="_board_title_label _qna_content_title _answer_content_title">
                      <span class="_name">{{ store.brandname }}</span>
                      <span class="_date">{{ $moment(qna.a_datetime).format('YYYY-MM-DD') }}</span>
                      <span class="_title">[{{ qna.subject }}] 질문에 대한 답변입니다.</span>
                    </div>
                    <div class="_qna_content_desc">
                      <p v-html="qna.answer">
                      </p>
                    </div>
                  </div>
                </li>
              </ul>
              <!-- 등록된 문의가 있을 때 E -->
            </div>
          </section>
          <!-- 2. 스토어 문의 답변 E -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import StoreQnaPopup from '@/components/popup/store-qna-popup.vue'

  export default {
    components: {
      StoreQnaPopup
    },
    data: function () {
      return {
        qna: {},
        store: {},
        form: {
          subject: null,
          question: null
        },
        popupKind: 'edit',
        openWritePopup: false
      }
    },
    props: {
      storeId: {
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
      await this.QnaLoad()
      await this.StoreInfo()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async QnaLoad () {
        try {
          const res = (await this.$http.get(this.$APIURI + 'store_qna/' + this.qnaId)).data

          if (res.state === 1) {
            this.qna = res.query
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      },
      async StoreInfo () {
        const params = {
          id: Number(this.storeId)
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
      async QnaUpdate () {
        const params = this.form
        params.id = this.qna.id

        try {
          const res = (await this.$http.put(this.$APIURI + 'store_qna/question', params)).data

          if (res.state === 1 && res.query === 1) {
            this.SuccessAlert('스토어 문의 수정 완료')
            document.body.style.overflowY = 'auto'
            this.openWritePopup = false
            await this.QnaLoad()
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
            const res = (await this.$http.delete(this.$APIURI + 'store_qna/' + this.qna.id)).data

            if (res.state === 1 && res.query === 1) {
              this.SuccessAlert('스토어 문의 삭제 완료')
              this.$router.go(-1)
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
          this.form.subject = this.qna.subject
          this.form.question = this.qna.question
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
          document.body.style.overflowY = 'auto'
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
