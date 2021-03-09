<template>
  <!-- 상품문의 작성 팝업 -->
  <div class="_popup_wrapper _write_ppopup_wrapper qna_write_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          상품문의 작성
          <button
            type="button"
            class="_close_btn"
            @click="Close"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <!-- 상품 종류 E -->
        <div class="_product_view">
          <div class="_img_box">
            <img
              v-if="ConvertImage(item.images).length > 0"
              :src="storageUrl + ConvertImage(item.images)[0]"
              :alt="item.title"
            >
            <img
              v-else
              src="/images/img/thumbnail.png"
              alt="상품 이미지"
            >
          </div>
          <div class="_text">
            <p class="_title">
              {{ item.title }}
            </p>
            <p class="_option">
              판매자: <router-link :to="'/store/'+item.store_id">
                <b>{{ item.company_name }}</b>
              </router-link>
            </p>
          </div>
        </div>
        <!-- 상품 종류 E -->
        <div class="_input_wrap">
          <div class="_secret_letter">
            <input
              type="checkbox"
              id="secret"
              class="dg-input-checkbox display_none"
              v-model="form.secret"
            >
            <label
              for="secret"
              class="dg-input-checkbox_label"
            ></label>
            <label
              for="secret"
              class="dg-input-checkbox_text"
            >비밀글</label>
          </div>
          <div
            v-show="!$store.state.user.id"
            class="dg_write write_mail"
          >
            <label
              for="q_email"
              class="dg_blind"
            >이메일</label>
            <input
              type="email"
              id="q_email"
              class="input_text_box"
              placeholder="이메일아이디"
              v-model="form.q_email"
            >
            <p class="input_desc">
              이메일을 입력하시면 답변 등록 시 답변이 이메일로 전송됩니다.
            </p>
          </div>
          <div
            v-show="!$store.state.user.id"
            class="dg_write write_tel"
          >
            <label
              for="q_hp"
              class="dg_blind"
            >휴대폰번호</label>
            <input
              type="tel"
              id="q_hp"
              class="input_text_box"
              placeholder="휴대폰번호"
              maxlength="13"
              v-model="form.q_hp"
            >
            <p class="input_desc">
              휴대폰번호를 입력하시면 답변등록 알림이 SMS로 전송됩니다.
            </p>
          </div>
          <div class="dg_write write_title">
            <label
              for="subject"
              class="dg_blind"
            >제목</label>
            <input
              type="tel"
              id="subject"
              class="input_text_box"
              placeholder="제목"
              maxlength="255"
              v-model="form.subject"
            >
          </div>
        </div>
        <!-- 에디터 대신 삽입 -->
        <div class="_edter_wrap">
          <textarea
            class="_editer"
            v-model="form.question"
          ></textarea>
        </div>
        <!-- 에디터 대신 삽입 E -->
        <div class="dg-dubble_btn_wrap clear_both">
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn"
            @click="Close"
          >
            닫기
          </button>
          <button
            type="button"
            class="dg-btn_gra dg-dubble_btn"
            @click="Submit()"
          >
            작성완료
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- 상품문의 작성 팝업 E -->
</template>

<script>
  export default {
    data: function () {
      return {
        form: {
          item_id: this.item.item_id,
          q_email: null,
          q_name: null,
          q_hp: null,
          secret: 0,
          subject: null,
          question: null
        }
      }
    },
    props: {
      item: {
        type: Object,
        required: true
      }
    },
    created () {
      if (this.$store.state.user.id) {
        this.form.q_name = this.$store.state.user.name
        this.form.q_email = this.$store.state.user.email
        this.form.q_hp = this.$store.state.user.mobile_number
      }
    },
    methods: {
      Validation () {
        if (!this.form.q_name || this.form.q_name === '') {
          this.WarningAlert('문의자명을 입력하세요!')
          return false
        }

        if (!this.form.q_email || this.form.q_email === '') {
          this.WarningAlert('연락 받으실 이메일 주소를 입력하세요!')
          return false
        }

        if (!this.form.q_hp || this.form.q_hp === '') {
          this.WarningAlert('연락 받으실 휴대폰 번호를 입력하세요!')
          return false
        }

        if (!this.form.subject || this.form.subject === '') {
          this.WarningAlert('문의 제목을 입력하세요!')
          return false
        }

        if (!this.form.question || this.form.question === '') {
          this.WarningAlert('문의 내용을 입력하세요!')
          return false
        }

        return true
      },
      async Submit () {
        if (this.Validation()) {
          this.form.item_id = this.item.item_id
          const res = (await this.$http.post(this.$APIURI + 'item_qna', this.form)).data

          if (res.query) {
            this.SuccessAlert('QnA 등록 성공!!')
            this.Close()

            const params = {
              target_mem_id: this.item.seller_id,
              not_type: 'item_qna',
              not_content_id: res.query,
              not_message: this.$store.state.user.nickname + '님 께서 상품문의를 남기셨습니다.',
              not_url: 'https://store.dong-gle.co.kr/inquiry-product-detail/' + res.query
            }

            this.NotifyStore(params)
          }
        }
      },
      Close () {
        this.form = {
          item_id: this.item.item_id,
          q_email: this.$store.state.user.email,
          q_name: this.$store.state.user.nickname,
          q_hp: this.$store.state.user.mobile_number,
          secret: 0,
          subject: null,
          question: null
        }
        this.$emit('close-event')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
