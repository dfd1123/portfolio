<template>
  <div id="mypage_edit" class="page page--hd_default">
    <header-component hd-title="회원정보 보기" :hd-theme-ylw="true" @privButtonClick="privButtonClick" />
    <div class="page_container pd_bt_nav">
      <transition appear name="slide-fade">
        <div class="contents contents--1st">
          <!-- 기본정보 수정 -->
          <article class="edit_info_article edit_info_article--default">
            <h2>기본정보</h2>
            <fieldset>
              <legend class="visually-hidden">기본정보 수정</legend>
              <div class="form_item">
                <label for="#" class="form_label">이메일</label>
                <div class="form_input_group">
                  <p class="form_input">email@email.com</p>
                </div>
              </div>
              <!-- <div class="form_item">
                <label for="#" class="form_label">휴대폰 인증</label>
                <div class="form_input_group">
                  <p class="form_input">{{ phoneNum }}</p>
                  <input
                    type="button"
                    class="small_btn01 btn_clear_to_theme active"
                    value="재인증"
                    @click="editPhoneNumButton"
                  />
                </div>
              </div>-->
            </fieldset>
          </article>
          <!-- end -->
          <!-- 상세정보 수정 -->
          <article class="edit_info_article edit_info_article--detail">
            <h2>상세정보</h2>
            <fieldset>
              <legend class="visually-hidden">상세정보 수정</legend>
              <div class="form_item">
                <label for="#" class="form_label">연령</label>
                <div class="form_input_group">
                  <p class="form_input">{{ birth }}</p>
                </div>
              </div>
              <div class="form_item">
                <label for="#" class="form_label">해당사항</label>
                <div class="form_input_group">
                  <p class="form_input">{{ type }}</p>
                </div>
              </div>
              <div class="form_item"></div>
            </fieldset>
          </article>
          <!-- end -->
        </div>
      </transition>
      <!-- <transition appear name="slide-fade">
        <div class="contents contents--2nd" v-show="isEditPhoneNum">
          <fieldset class="page_field">
            <legend class="visually-hidden">휴대폰 재인증</legend>
            <div class="form_item">
              <label for="#" class="form_label">휴대폰 번호입력</label>
              <div class="form_input_group form_btn_input_group">
                <input
                  v-model="phoneNum"
                  type="text"
                  class="form_input mb-1"
                  placeholder="010-0000-0000"
                  :readonly="isVerifyMsgSent"
                />
                <input
                  :class="{ active : phoneNum }"
                  type="button"
                  value="인증번호 받기"
                  class="small_btn01 btn_theme_to_gray"
                  @click="sendVerifyMsg"
                  :disabled="!phoneNum"
                />
              </div>
            </div>
            <transition name="slide-fade">
              <div class="form_item" v-show="isVerifyMsgSent">
                <label for="#" class="form_label">인증번호 입력</label>
                <div class="form_input_group">
                  <input
                    v-model="msgCertifyCode"
                    type="number"
                    class="form_input form_input--none_num"
                    placeholder="인증번호를 입력하세요."
                  />
                </div>
              </div>
            </transition>
            <input
              :class="{ active : msgCertifyCode }"
              type="submit"
              value="완료"
              class="wide_btn btn_clear_to_theme step_btn"
              :disabled="!msgCertifyCode"
              @click="recertifyPhone"
            />
          </fieldset>
        </div>
      </transition>-->
    </div>
    <footer-navigation :mypage-on="true"></footer-navigation>
  </div>
</template>

<script>

export default {
  name: 'MypageEdit',
  data () {
    return {
      birth: '1993년생',
      type: '수험생/학부모'
    }
  },
  computed: {
  },
  methods: {
    privButtonClick () {
      window.history.back()
    }
  }
}
</script>

<style lang="scss" scoped>
#mypage_edit {
  .page_container {
    overflow-x: hidden;
  }
  .contents {
    @include position($t: 0, $l: 0);
    height: calc(100% - #{$g-page_footer_nav_height});
    padding: 0;
    overflow-y: scroll;
    background-color: white;
  }
  .edit_info_article {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #e6e6e6;
    & > h2 {
      @include remFont("18px", $weight: bold, $color: $theme-02);
    }
    &:last-child {
      border-bottom: 0;
    }
  }
  .contents--1st .form_item {
    position: relative;
    padding: 0;
    margin: 10px 0;
    .form_label {
      @include setFont(14px, $color: #5a5a5a);
      @include position($t: 50%, $l: 0);
      @include translate($x: 0, $y: -50%);
      width: 90px;
      color: #5a5a5a;
    }
    .small_btn01 {
      @include position($t: 50%, $r: 0);
      @include translate($x: 0, $y: -50%);
      height: 1.75rem;
      font-size: 12px;
      padding: 0 19px;
      box-shadow: none;
    }
    .form_input,
    .form_select {
      @include setFont(14px);
      border-bottom: 0;
      margin-bottom: 0;
      min-height: 2rem;
      padding: 0.25rem 7px 0.25rem 6rem;
    }
  }
  .contents--2nd {
    @include zindex("overlay");
    height: 100%;
    padding: 0 2rem;
    .step_btn {
      @include btButton;
    }
  }
}
</style>
