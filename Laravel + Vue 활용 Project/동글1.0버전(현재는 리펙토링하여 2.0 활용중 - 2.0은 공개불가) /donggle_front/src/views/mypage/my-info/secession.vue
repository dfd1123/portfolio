<template>
  <div id="dg-mypage-secession-wrapper">
    <MyPageHeader />
    <div class="l-mypage-contents">
      <!-- 1) 회원탈퇴 -->
      <div class="l-con-area full">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-15">
            <h2 class="in-subject">
              회원탈퇴
            </h2>
          </div>

          <div class="l-contents-group">
            <!-- * 탈퇴 안내카드 -->
            <div class="dg-secession-card">
              <span class="in-subject">회원탈퇴를 신청하기 전에 확인해주세요.</span>

              <div class="in-con-box in-con-box--desc">
                <p>
                  탈퇴 후 회원정보 및 이용기록은 모두 삭제되며, 다시 복구 할 수 없습니다.<br>
                  작성한 구매후기와 결제 내역은 이용약관과 관련법에 의해 보관됩니다.<br>
                  동일한 SNS계정과 이메일을 사용한 재가입이 불가능합니다.
                </p>
              </div>

              <div class="in-con-box in-con-box--check">
                <span class="in-subject">회원탈퇴를 신청하시겠습니까?</span>
                <input
                  type="checkbox"
                  id="secessionYN"
                  v-model="secessionYN"
                  class="dg-input-checkbox display_none"
                >
                <label
                  for="secessionYN"
                  class="dg-input-checkbox_label"
                ></label>
                <label
                  for="secessionYN"
                  class="word"
                >예, 탈퇴를 신청합니다.</label>
              </div>

              <div class="dg-button-wrap dg-button-wrap--single">
                <button
                  type="button"
                  class="theme-btn-gradient"
                  @click="Submit"
                >
                  동글 탈퇴하기
                </button>
              </div>
            </div>
            <!-- * 탈퇴 안내카드 E -->
          </div>
        </article>
      </div>
      <!-- 1) 회원탈퇴 E -->
    </div>
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'

  export default {
    components: {
      MyPageHeader
    },
    data: function () {
      return {
        secessionYN: false
      }
    },
    methods: {
      async Submit () {
        if (this.secessionYN) {
          let result = await this.Confirm('정말 탈퇴를 하실건가요...?')
          if (result) {
            try {
              const res = (await this.$http.put(this.$APIURI + 'users/secession')).data

              if (res.state === 1) {
                this.$cookies.remove('access_token')
                this.$http.defaults.headers.common['Authorization'] = undefined
                this.$store.commit('UserDeleteInfor')
                this.$store.commit('MypageInfoDelete')

                result = await this.SuccessAlert('회원 탈퇴를 완료하였습니다.\n꼭 돌아오실거라 믿어요! ^^')

                if (result) {
                  this.$router.push('/')
                }
              }
            } catch (e) {
              console.log(e)
            }
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
