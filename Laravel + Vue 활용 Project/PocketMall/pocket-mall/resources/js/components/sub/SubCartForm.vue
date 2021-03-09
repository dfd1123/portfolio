<template>
  <div class="contect-wrap">
    <form class="customar-write-wrap">
      <fieldset class="fieldset">
        <legend class="none">고객정보</legend>
        <div class="write_form">
          <label class="none" for="req_name">이름</label>
          <input
            type="text"
            id="req_name"
            class="_write"
            placeholder="이름을 입력해주세요"
            v-model="form.req_name"
          />
        </div>
        <div class="write_form">
          <label class="none" for="req_tel">연락처</label>
          <input type="tel" id="req_tel" class="_write" placeholder="연락처" v-model="form.req_tel" />
        </div>
        <div class="write_form">
          <label class="none" for="req_email">이메일</label>
          <input
            type="text"
            id="req_email"
            class="_write"
            placeholder="이메일"
            v-model="form.req_email"
          />
        </div>
        <div class="write_form">
          <label class="none" for="req_company">회사명</label>
          <input
            type="text"
            id="req_company"
            class="_write"
            placeholder="회사명"
            v-model="form.req_company"
          />
        </div>
        <div class="write_form form-date_picker">
          <label class="none" for="req_date">진행 가능 일자</label>
          <date-picker
            :input-props="inputProps"
            :masks="{ input: ['YYYY-MM-DD']}"
            v-model="selectedDate"
            :mode="mode"
            :min-date="new Date()"
          />
        </div>
        <div class="write_form">
          <label class="none" for="req_contents">기타 요청사항</label>
          <textarea
            id="req_contents"
            class="_write contect-etc"
            placeholder="기타 문의사항을 남겨주세요"
            v-model="form.req_contents"
          ></textarea>
        </div>
        <div>
          <button type="button" @click="Submit" class="rounded-button btn-bluegradi">최종문의 접수하기</button>
        </div>
      </fieldset>
    </form>
  </div>
</template>

<script>
export default {
  name: "sub-card-form",
  data() {
    return {
      form: this.formData,
      mode: "single",
      selectedDate: null,
      inputProps: {
        class: "_write _date",
        placeholder: "미팅 및 통화 가능 날짜"
      }
    };
  },
  props: {
    formData: {
      type: Object,
      required: true
    }
  },
  methods: {
    Submit() {
      console.log(this.form);
      this.form.req_date = this.$moment(this.selectedDate).format("YYYY-MM-DD");
      this.$emit("submit", this.form);
    }
  }
};
</script>

<style scoped>
::v-deep .vc-popover-content-wrapper{
    position: absolute !important;
    transform: translate(0,-100%) !important;
    top: 0 !important;
    left: 0 !important;
    margin: 0 auto !important;
}

::v-deep .customar-write-wrap .write_form.form-date_picker > span{
  position: relative;
}

::v-deep .vc-popover-caret.direction-bottom.align-left{
    transform: translateY(-50%) rotate(-135deg) !important;
}

::v-deep .vc-popover-caret.direction-top.align-left{
    transform: translateY(-50%) rotate(-135deg) !important;
}

::v-deep .vc-popover-caret.direction-bottom{
  top: 100% !important;
  }

.form-date_picker span._date {
  padding: 0;
  background: none;
  display: block;
  border: none;
}

.form-date_picker ._date > input {
  display: block;
  width: 100%;
  height: auto;
  border: 1px solid #dddfe7;
  border-radius: 10px;
  padding: 17px 20px;
  outline: 0px none;
  box-sizing: border-box;
  resize: none;
}

.rounded-button {
  height: 51px;
  border-radius: 8px;
  box-shadow: 0 10px 15px rgba(11, 50, 137, 0.19);
  font-size: 1em;
}
</style>