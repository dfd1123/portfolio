<template>
  <div
    id="dg-mypage-set-account-wrapper"
    class="mypage-set-account-wrapper"
  >
    <!-- 쿠폰적용상품 팝업 -->
    <AccountPopup
      :class="{'posFixed': accountPopup}"
      @close-popup="AccountPopupClose"
    />
    <!-- 쿠폰적용상품 팝업 E -->
    <!-- * 마이페이지 헤더 -->
    <div class="_page_title_wrap">
      <h2>
        내 정보 관리
        <button
          type="button"
          class="_back_btn"
          @click="$router.go(-1)"
        >
          뒤로가기
        </button>
      </h2>
      <div class="second_title clear_both">
        <router-link to="/mypage/info">
          내 정보 관리
        </router-link>
        <router-link
          to="/mypage/payment/info"
          class="_long"
        >
          카드 및 환불계좌 관리
        </router-link>
        <router-link to="/mypage/level_guide">
          회원등급
        </router-link>
      </div>
    </div>
    <!-- * 마이페이지 헤더 -->

    <div class="l-mypage-contents card_center">
      <!-- 1) 카드 및 환불계좌 관리 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-35">
            <h2 class="in-subject dg_blind">
              카드 및 환불계좌 관리
            </h2>
          </div>

          <div>
            <ul class="l-grid-group">
              <li class="l-grid-list">
                <!-- * 카드~계좌 등록카드 -->
                <div class="dg-account-set-card _set-buy-card">
                  <h4 class="in-name">
                    결제 카드 등록
                  </h4>
                  <p class="in-caution">
                    간편하게 카드를 등록하고 결제하세요.
                  </p>
                  <div class="in-state">
                    <!-- ※ 정보 없을 때 -->
                    <span class="_nothing">정보가 없습니다.</span>
                    <!-- ※ 정보 없을 때 END -->
                    <!-- ※ 정보 있을 때 -->
                    <p class="_thing clear_both display_none">
                      <span class="_bank">기업은행카드</span>
                      <span class="_name">일상의 기쁨 신용카드</span>
                      <span class="_num">1234-****--1234-1234</span>
                    </p>
                    <!-- ※ 정보 있을 때 END -->
                  </div>
                  <!-- ※ 등록완료됐을 경우 변경으로 텍스트 변경해야함 -->
                  <button
                    type="button"
                    class="in-full-button"
                  >
                    <img
                      src="/images/mobile/btn/btn_arrow_wh.svg"
                      alt="화살표"
                    >
                    <p>등록</p>
                  </button>
                  <!-- ※ 등록완료됐을 경우 변경으로 텍스트 변경해야함 END -->
                </div>
                <!-- * 카드~계좌 등록카드 -->
              </li>

              <li class="l-grid-list">
                <!-- * 카드~계좌 등록카드 -->
                <div class="dg-account-set-card _set-return-card">
                  <h4 class="in-name">
                    {{ this.$store.state.user.account_number?'환불 계좌 변경':'환불 계좌 등록' }}
                  </h4>
                  <p class="in-caution">
                    계좌를 등록하셔야 환불을 받으실 수 있습니다. <br><br>
                    계좌이체만 해당 됩니다.
                  </p>
                  <div class="in-state">
                    <!-- ※ 정보 있을 때 -->
                    <p
                      v-if="this.$store.state.user.account_number"
                      class="_thing clear_both"
                    >
                      <span class="_bank">{{ (this.$store.state.user.account_bank || '-').split('/')[1] }}</span>
                      <span class="_num">{{ this.$store.state.user.account_number || '-' }}</span>
                      <span class="_name">{{ this.$store.state.user.account_name || '-' }}</span>
                    </p>
                    <!-- ※ 정보 없을 때 -->
                    <span
                      v-else
                      class="_nothing"
                    >정보가 없습니다.</span>
                    <!-- ※ 정보 없을 때 END -->
                    <!-- ※ 정보 있을 때 END -->
                  </div>
                  <!-- ※ 등록완료됐을 경우 변경으로 텍스트 변경해야함 -->
                  <button
                    type="button"
                    class="in-full-button"
                    @click="AccountPopupOpen"
                  >
                    <img
                      src="/images/mobile/btn/btn_arrow_wh.svg"
                      alt="화살표"
                    >
                    <p>
                      {{ this.$store.state.user.account_number?'변경':'등록' }}
                    </p>
                  </button>
                  <!-- ※ 등록완료됐을 경우 변경으로 텍스트 변경해야함 END -->
                </div>
                <!-- * 카드~계좌 등록카드 -->
              </li>
            </ul>
          </div>
        </article>
      </div>
      <!-- 1) 카드 및 환불계좌 관리 E -->
    </div>
  </div>
</template>

<script>
  import AccountPopup from '@/components/popup/account-popup.vue'
  export default {
    components: {
      AccountPopup
    },
    data: function () {
      return {
        accountPopup: false,
        account: {
          account_bank: this.$store.state.user.account_bank,
          account_number: this.$store.state.user.account_number,
          account_name: this.$store.state.user.account_name
        }
      }
    },
    methods: {
      AccountPopupOpen () {
        this.accountPopup = true
        document.body.style.overflowY = 'hidden'
      },
      AccountPopupClose () {
        this.accountPopup = false
        document.body.style.overflowY = 'auto'
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
