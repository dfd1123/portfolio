<template>
  <div id="dg-mypage-set-account-wrapper">
    <MyPageHeader />

    <div class="l-mypage-contents">
      <MyPageMenu />

      <!-- 1) 카드 및 환불계좌 관리 -->
      <div class="l-con-area">
        <article class="l-con-article">
          <div class="l-con-title-group type01 mb-35">
            <h2 class="in-subject">
              카드 및 환불계좌 관리
            </h2>
          </div>

          <div class="l-contents-group">
            <ul class="l-grid-group">
              <li class="l-grid-list l-col-2">
                <!-- * 카드~계좌 등록카드 -->
                <div class="dg-account-set-card">
                  <img
                    src="/images/icon/icon_card.svg"
                    alt="card icon"
                    class="in-icon"
                  >
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
                    <span class="_thing">
                      <i>삼성카드</i> / <i>TapTapO</i> / <i>1234-****--1234-1234</i>
                    </span>
                    <!-- ※ 정보 있을 때 END -->
                  </div>
                  <!-- ※ 등록완료됐을 경우 변경하기로 텍스트 변경해야함 -->
                  <button
                    type="button"
                    class="in-full-button"
                    onclick="alert('(팝업)카드 등록 팝업')"
                  >
                    등록하기
                  </button>
                  <!-- ※ 등록완료됐을 경우 변경하기로 텍스트 변경해야함 END -->
                </div>
                <!-- * 카드~계좌 등록카드 -->
              </li>

              <li class="l-grid-list l-col-2">
                <!-- * 카드~계좌 등록카드 -->
                <div class="dg-account-set-card">
                  <img
                    src="/images/icon/icon_account.svg"
                    alt="account icon"
                    class="in-icon"
                  >
                  <h4 class="in-name">
                    {{ this.$store.state.user.account_number?'환불 계좌 변경':'환불 계좌 등록' }}
                  </h4>
                  <p class="in-caution">
                    계좌를 등록하셔야 환불을 받으실 수 있습니다.
                  </p>
                  <div class="in-state">
                    <!-- ※ 정보 있을 때 -->
                    <span
                      v-if="this.$store.state.user.account_number"
                      class="_thing"
                    >
                      <i>{{ (this.$store.state.user.account_bank || '-').split('/')[1] }}</i> / <i>{{ this.$store.state.user.account_number || '-' }}</i> / <i>{{ this.$store.state.user.account_name || '-' }}</i>
                    </span>
                    <!-- ※ 정보 있을 때 END -->
                    <!-- ※ 정보 없을 때 -->
                    <span
                      v-else
                      class="_nothing"
                    >정보가 없습니다.</span>
                    <!-- ※ 정보 없을 때 END -->
                  </div>
                  <!-- ※ 등록완료됐을 경우 변경하기로 텍스트 변경해야함 -->
                  <button
                    type="button"
                    class="in-full-button"
                    @click="AccountPopupOpen"
                  >
                    {{ this.$store.state.user.account_number?'변경하기':'등록하기' }}
                  </button>
                  <!-- ※ 등록완료됐을 경우 변경하기로 텍스트 변경해야함 END -->
                </div>
                <!-- * 카드~계좌 등록카드 -->
              </li>
            </ul>
          </div>
        </article>
      </div>
      <!-- 1) 카드 및 환불계좌 관리 E -->
    </div>
    <AccountPopup
      v-show="accountPopup"
      @close-popup="AccountPopupClose"
    />
  </div>
</template>

<script>
  import MyPageHeader from '@/components/mypage/header.vue'
  import MyPageMenu from '@/components/mypage/menu.vue'
  import AccountPopup from '@/components/popup/account-popup.vue'

  export default {
    components: {
      MyPageHeader,
      MyPageMenu,
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
      },
      AccountPopupClose () {
        this.accountPopup = false
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
