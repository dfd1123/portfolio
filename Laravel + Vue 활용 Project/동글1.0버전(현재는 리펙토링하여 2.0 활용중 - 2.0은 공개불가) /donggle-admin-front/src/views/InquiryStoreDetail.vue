<template>
  <div id="app">
    <layout-header
      title="스토어 문의 상세보기"
      :button-right="status === 0 ? '답변완료' : ''"
      @button-right-click="saveAnswer"
      :storeInquiryDt=true
    >
      <template v-slot:before-button-right>
        <input
          type="button"
          class="rounded-square-btn btn-mild"
          value="목록으로"
          @click="$router.push('/inquiry-store-list')"
        />
      </template>
      <template v-slot:before-button-right-mobile>
        <input
          type="button"
          class="mobile-btn-theme"
          value="목록으로"
          @click="$router.push('/inquiry-store-list')"
        />
      </template>
    </layout-header>

    <!-- contents -->
    <div
      id="admin-container"
      class="detail-container"
    >
      <div class="wrapper">
        <div id="page-inquiry-prdt-detail-wrap">
          <div class="grid-line-group">
            <div class="panel-default">
              <div class="form-wrapper">
                <fieldset class="form-container type-03 not-mobile">
                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >문의자</label>
                    <div class="form-align-input">
                      <p class="in-text">{{qName}}</p>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >문의자 이메일</label>
                    <div class="form-align-input">
                      <p class="in-text">{{qEmail}}</p>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >문의자 전화번호</label>
                    <div class="form-align-input">
                      <p class="in-text">{{qHp}}</p>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >문의 날짜</label>
                    <div class="form-align-input">
                      <div class="in-text">{{qDatetime}}</div>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >제목</label>
                    <div class="form-align-input">
                      <div class="in-text">{{subject}}</div>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >문의내용</label>
                    <div class="form-align-input">
                      <p class="in-text in-text-200">{{question}}</p>
                    </div>
                  </div>

                  <div class="form-align">
                    <label
                      for="#"
                      class="form-align-tit"
                    >답변</label>
                    <div class="form-align-input">
                      <div
                        v-if="status === 0"
                        class="temp-div in-editor unreset"
                      >
                        <ckeditor
                          v-if="isPcEditorVisible"
                          :editor="common.editor"
                          v-model="common.editorData"
                          :config="common.editorConfig"
                        ></ckeditor>
                      </div>
                      <div
                        v-else
                        class="temp-div in-answer"
                      >
                        <p
                          v-html="common.editorData"
                          class="_answer unreset"
                        ></p>
                      </div>
                    </div>
                  </div>
                </fieldset>
                <fieldset id="mobile-only-container" class="mobile-only">
                  <ul class="mobile-detail-list">
                    <li class="content-divide-line user-info">
                      <dl class="clearfix">
                        <dt class="_tit">문의자</dt>
                        <dd class="_txt">{{qName}}</dd>
                      </dl>
                      <dl class="clearfix">
                        <dt class="_tit">문의자 이메일</dt>
                        <dd class="_txt">{{qEmail}}</dd>
                      </dl>
                      <dl class="clearfix">
                        <dt class="_tit">문의자 전화번호</dt>
                        <dd class="_txt">{{qHp}}</dd>
                      </dl>
                      <dl class="clearfix">
                        <dt class="_tit">문의 날짜</dt>
                        <dd class="_txt">{{qDatetime}}</dd>
                      </dl>
                    </li>
                    <li class="content-divide-line user-question">
                      <dl class="clearfix">
                        <dt class="_tit">제목</dt>
                        <dd class="_txt">{{subject}}</dd>
                      </dl>
                    </li>
                    <li class="content-divide-line user-question">
                      <dl class="clearfix">
                        <dt class="_tit">문의내용</dt>
                        <dd class="_txt">{{question}}</dd>
                      </dl>
                    </li>
                    <li class="content-divide-line my-answer">
                      <dl class="clearfix">
                        <dt class="_tit">답변</dt>
                        <dd class="_txt">
                          <!-- 상태에 따라 하나만 보임 -->
                          <template v-if="status !== null">
                            <div
                              v-if="status === 0"
                              class="temp-div in-editor unreset"
                            >
                              <ckeditor
                                v-if="isMobileEditorVisible"
                                :editor="common.editor"
                                v-model="common.editorData"
                                :config="common.editorConfig"
                              ></ckeditor>
                            </div>
                            <div
                              v-else
                              class="temp-div in-answer"
                            >
                              <p
                                v-html="common.editorData"
                                class="_answer unreset"
                              ></p>
                            </div>
                          </template>
                        </dd>
                      </dl>
                    </li>
                  </ul>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </div>

      <layout-footer />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
import isVisible from 'is-element-visible'
import CKEditor from '@ckeditor/ckeditor5-vue'
import '@ckeditor/ckeditor5-build-classic/build/translations/ko'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

export default {
  name: 'InquiryStoreDetail',
  components: {
    ckeditor: CKEditor.component
  },
  data () {
    return {
      isPcEditorVisible: false,
      isMobileEditorVisible: false,
      storeQnaId: this._get(this, '$route.params.id', null),
      storeQna: null,
      qName: null,
      qEmail: null,
      qHp: null,
      qDatetime: null,
      itemTitle: null,
      subject: null,
      question: null,
      images: null,
      status: null,
      common: {
        editor: ClassicEditor,
        editorData: '',
        editorConfig: {
          language: 'ko',
          ckfinder: {
            uploadUrl: this.baseUrl() + '/api' + '/Ckfinder/image_upload'
          },
          image: {
            toolbar: [
              'imageTextAlternative',
              '|',
              'imageStyle:alignLeft',
              'imageStyle:full',
              'imageStyle:alignRight'
            ],
            styles: ['full', 'alignLeft', 'alignRight']
          }
        }
      }
    }
  },
  created () {
    this.fetchData()
    this.toggle = this._throttle(this.toggleEditors, 100)
  },
  mounted () {
    window.addEventListener('resize', this.toggle)
  },
  beforeDestroy () {
    window.removeEventListener('resize', this.toggle)
  },
  methods: {
    toggleEditors (e) {
      this.$nextTick(() => {
        const mobile = document.getElementById('mobile-only-container')

        if (isVisible(mobile)) {
          this.isMobileEditorVisible = true
          this.isPcEditorVisible = false
        } else {
          this.isMobileEditorVisible = false
          this.isPcEditorVisible = true
        }
      })
    },
    async fetchData () {
      await this.searchClick()
      this.toggle()
    },
    async searchClick () {
      try {
        this.loading(true)

        const params = {
          id: this.storeQnaId
        }

        const data = await this.$axios
          .get('/api/store/store_qna/view', {
            params
          })
          .then(this.normalOrError)
          .then(this.resultOrError)

        if (!data) {
          alert('잘못된 데이터입니다')
          return this.$router.push('/inquiry-store-list')
        }

        this.storeQna = data
        this.qName = data.q_name
        this.qEmail = data.q_email
        this.qHp = data.q_hp
        this.qDatetime = data.q_datetime
        this.subject = data.subject
        this.question = data.question
        this.status = data.status
        if (data.answer) {
          this.common.editorData = data.answer
        }
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async saveAnswer () {
      try {
        this.loading(true)

        if (!this.common.editorData) {
          alert('답변 내용을 입력해주세요')
          return
        }

        let params = {
          _method: 'put',
          id: this.storeQnaId,
          answer: this.common.editorData
        }

        await this.$axios
          .post('/api/store/store_qna/answer', params)
          .then(this.normalOrError)
          .then(this.resultOrError)

        await this.searchClick()

        params = {
          target_mem_id: this.storeQna.q_id,
          not_type: 'store_qna',
          not_content_id: this.storeQnaId,
          not_message: this.store.brandname + ' 스토어가 문의에 답글을 남겼습니다.',
          not_url: null
        }

        if (process.env.VUE_APP_ENV === 'LOCAL') {
          params.not_url = 'http://localhost:8080/store/' + this.storeQna.store_id + '/qna'
        } else {
          params.not_url = process.env.VUE_APP_PRODC_URI.replace('store.', '') + '/store/' + this.storeQna.store_id + '/qna'
        }

        this.NotifyStore(params)
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

<style lang="scss" scoped>
  ::v-deep .unreset {
    @import "node_modules/unreset-css/unreset";

    ol {
      list-style: decimal;
    }

    ul {
      list-style: disc;
    }

    li {
      list-style: inherit;
    }
  }

  ::v-deep {
    .ck-editor__editable_inline {
      min-height: 300px;
    }

    .ck.ck-toolbar.ck-toolbar_grouping > .ck-toolbar__items {
      flex-wrap: wrap;
    }
  }
</style>
