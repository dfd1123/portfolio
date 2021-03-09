<template>
  <div class="_popup_wrapper _write_ppopup_wrapper qna_write_popup_wrapper _cs_support_write_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          {{ kind === 'create'?'스토어 문의 작성':'스토어 문의 수정' }}
          <button
            type="button"
            class="_close_btn"
            @click="ClosePopup"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <!-- 상품 종류 E -->
        <div class="_product_view _store_view vi">
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
          <div class="_text">
            <p>{{ store.brandname }}</p>
          </div>
        </div>
        <!-- 상품 종류 E -->
        <h5>문의사항</h5>
        <div class="_input_wrap">
          <div class="dg_write write_title">
            <label
              for="subject"
              class="dg_blind"
            >제목</label>
            <input
              type="text"
              id="subject"
              class="input_text_box"
              placeholder="제목"
              maxlength="150"
              v-model="form.subject"
            >
          </div>
        </div>
        <!-- 에디터 대신 삽입 -->
        <div class="_edter_wrap">
          <textarea
            class="_editer"
            id="question"
            v-model="form.question"
            placeholder="내용"
          ></textarea>
        </div>
        <!-- 에디터 대신 삽입 E -->
        <div class="dg-dubble_btn_wrap clear_both">
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn"
            @click="$emit('submit', form, kind)"
          >
            {{ kind === 'create'?'작성완료':'수정완료' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        form: {
          subject: null,
          question: null
        }
      }
    },
    props: {
      subject: {
        type: String,
        default: null
      },
      question: {
        type: String,
        default: null
      },
      kind: {
        type: String,
        default: 'create'
      },
      store: {
        type: Object,
        required: true
      }
    },
    watch: {
      subject () {
        this.form.subject = this.subject
      },
      question () {
        this.form.question = this.question
      }
    },
    methods: {
      ClosePopup () {
        this.form.subject = null
        this.form.question = null

        this.$emit('close-popup')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
