<template>
  <div id="dg-mypage-grade-wrapper" class="mypage-grade-wrapper">
    <MyPageHeader />
    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 회원등급 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-35">
            <h2 class="in-subject">회원등급</h2>
          </div>

          <div class="l-contents-group">
            <!-- .my_level 에 클래스따라서 등급 변화 .level01 .level02 .level03 -->
            <div :class="'clear_both my_level level0'+$store.state.user.level">
              <div class="content-wrap">
                <div class="img_box">
                  <i class="_img"></i>
                </div>
                <span class="_text">
                  현재 고객님의 등급은
                  <b class="_name"></b>입니다.
                </span>
              </div>
            </div>

            <aside class="grade_info">
              <h3>회원등급 안내</h3>
              <div class="_grade_list_wrap">
                <ul class="clear_both">
                  <li class="level_info">
                    <div class="_level _level01 clear_both">
                      <div class="img_box">
                        <img src="/images/img/grade/level_01_guest_medal.png" alt="게스트" />
                      </div>
                      <div class="text_box">
                        <h4>게스트</h4>
                        <p>동글 가입 회원</p>
                        <p>월 정액 회원 미등록</p>
                        <p class="_pink">열람 가능/ 결제 불가능</p>
                      </div>
                    </div>
                  </li>
                  <li class="level_info">
                    <div class="_level _level02 clear_both">
                      <div class="img_box">
                        <img src="/images/img/grade/level_02_buyer_medal.png" alt="정기구독회원" />
                      </div>
                      <div class="text_box">
                        <h4>정기구독회원</h4>
                        <p>동글 가입 회원</p>
                        <p>월 정액 회원 등록</p>
                        <p class="_pink">열람 가능/ 결제 가능</p>
                      </div>
                    </div>
                  </li>
                  <li class="level_info">
                    <div class="_level _level03 clear_both">
                      <div class="img_box">
                        <img src="/images/img/grade/level_03_special_medal.png" alt="1달이용회원" />
                      </div>
                      <div class="text_box">
                        <h4>1달이용회원</h4>
                        <p>동글 가입 회원</p>
                        <p>월 정액 회원 미등록</p>
                        <p>1개월 구매권 등록</p>
                        <p>(금액에 상관없이 1개월간 결제가능)</p>
                        <p class="_pink">열람 가능/ 1개월 결제 가능</p>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </aside>
            <div
              class="dg-button-wrap dg-button-wrap--single dg-subs-cancel-btn-wrap"
              v-if="$store.state.user.level > 1 && $store.state.user.regular_end"
            >
              <button class="dg-subs-cancel-btn" type="button" @click="PayCancel">구독 취소하기</button>
            </div>
          </div>
        </article>
      </div>
      <!-- 1) 회원등급 E -->
    </div>
  </div>
</template>

<script>
import MyPageHeader from "@/components/mypage/header.vue";
import MyPageMenu from "@/components/mypage/menu.vue";

export default {
  components: {
    MyPageHeader,
    MyPageMenu
  },
  methods: {
    async PayCancel() {
      const result = await this.Confirm("정말 구독을 취소하시겠습니까?");

      if (result) {
        try {
          const res = (await this.$http.post(this.$APIURI + "payple/terminate"))
            .data;

          if (res.state === 1) {
            this.SuccessAlert(
              "구독을 취소하였습니다.\n나중에 꼭 다시 찾아주세요~"
            );
          } else {
            this.WarningAlert(res.msg);
          }
        } catch (e) {
          console.log(e);
        }
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.dg-subs-cancel-btn-wrap {
  justify-content: flex-end;

  .dg-subs-cancel-btn {
    color: #848484;
    background: #e5e5e5;
    border: 0;
    border-radius: 30px;
    font-size: 15px;
    letter-spacing: -0.25px;
    font-weight: 300;
    padding: 10px 20px;
    font-size: 14px;
    cursor: pointer;
    outline: none;

    &:hover {
      background: darken(#e5e5e5, 5%);
    }

    &:active {
      background: darken(#e5e5e5, 7%);
    }
  }
}
</style>
