<template>
  <!-- footer -->
  <footer id="dg-ft">
    <div class="dg-ft-wrap">
      <!-- footer top -->
      <div class="dg-ft-top_wrap clear_both">
        <h2>고객센터<br>{{ company.company_tel }}</h2>
        <div class="dg-ft-sales-time">
          <small>
            영업시간 : AM 10:00~PM 19:00(주말, 공휴일 제외)<br>
            점심시간 : AM 12:00~PM 13:30
          </small>
        </div>
      </div>
      <!-- footer top E -->

      <div class="dg-ft-middle_wrap clear_both">
        <div class="dg-ft-logo">
          logo
        </div>
        <div class="dg-ft-link_icon_wrap clear_both">
          <a
            href="#"
            class="link_icon link_icon_tube"
          >youtube</a>
          <a
            href="#"
            class="link_icon link_icon_talk"
          >kakaotalk</a>
          <a
            href="#"
            class="link_icon link_icon_inst"
          >insargram</a>
          <a
            href="#"
            class="link_icon link_icon_face"
          >facebook</a>
          <a
            href="#"
            class="link_icon link_icon_blog"
          >blog</a>
          <a
            href="#"
            class="link_icon link_icon_twitter"
          >twitter</a>
        </div>
      </div>
      <!-- footer bottom -->
      <div class="dg-ft-bot_wrap clear_both">
        <div class="dg-ft-company_info">
          회사명 : {{ company.company_name || '-' }} | 대표이사 : {{ company.ceo_name || '-' }} | 사업자등록번호 : {{ company.buisness_number || '-' }} |
          통신판매업신고 : {{ company.cyber_trade_number || '-' }} | 호스팅사업자 : {{ company.company_name || '-' }}<br>
          주소 : ({{ company.post_num || '-' }}) {{ company.addr1 || '-' }} {{ company.addr2 || '-' }}
        </div>
        <div class="dg-ft-link_wrap">
          <a
            href="#"
            @click="PolicyPopupOpen"
          >
            개인정보처리방침
          </a>
          <a
            href="#"
            @click="TermPopupOpen"
          >
            이용약관
          </a>
          <!--
          <a href="#">회사소개</a>
          <a href="#">입점문의</a>
          -->
        </div>
      </div>
      <!-- footer bottom E -->
    </div>
    <PrivacyPolicy
      :class="{'posFixed': policyPopup}"
      @popup-close="PolicyPopupClose"
    />
    <UseTerm
      :class="{'posFixed': termPopup}"
      @popup-close="TermPopupClose"
    />
  </footer>
  <!-- footer E -->
</template>

<script>
  import PrivacyPolicy from '@/components/popup/policy.vue'
  import UseTerm from '@/components/popup/term.vue'

  export default {
    components: {
      PrivacyPolicy,
      UseTerm
    },
    data: function () {
      return {
        company: {},
        policyPopup: false,
        termPopup: false
      }
    },
    created () {
      this.company = this.$store.state.company
    },
    methods: {
      PolicyPopupOpen () {
        document.body.style.overflowY = 'hidden'
        this.policyPopup = true
      },
      PolicyPopupClose () {
        document.body.style.overflowY = 'auto'
        this.policyPopup = false
      },
      TermPopupOpen () {
        document.body.style.overflowY = 'hidden'
        this.termPopup = true
      },
      TermPopupClose () {
        document.body.style.overflowY = 'auto'
        this.termPopup = false
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
