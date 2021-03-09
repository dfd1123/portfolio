<template>
  <!-- footer -->
  <footer id="dg-ft">
    <div class="dg-content_center">
      <!-- footer top -->
      <div class="dg-ft-top_wrap clear_both">
        <h2>고객센터<br>{{ company.company_tel }}</h2>
        <div class="dg-ft-sales-time">
          <small>
            영업시간 : 10:00~19:00(주말, 공휴일 제외)<br>
            점심시간 : 12:00~13:30
          </small>
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
      <!-- footer top E -->

      <!-- footer bottom -->
      <div class="dg-ft-bot_wrap clear_both">
        <div class="dg-ft-logo">
          logo
        </div>
        <div class="dg-ft-company_info">
          회사명 : {{ company.company_name }} | 대표이사 : {{ company.ceo_name }} | 사업자등록번호 : {{ company.buisness_number }} |
          통신판매업신고 : {{ company.cyber_trade_number }} | 호스팅사업자 : {{ company.company_name }}<br>
          주소 : ({{ company.post_num }}) {{ company.addr1 }} {{ company.addr2 }}
        </div>
        <div class="dg-ft-link_wrap">
          <router-link to="/privacy/policy">
            개인정보처리방침
          </router-link>
          <router-link to="/use/term">
            이용약관
          </router-link>
          <!--
          <a href="#">회사소개</a>
          <a href="#">입점문의</a>
          -->
        </div>
      </div>
      <!-- footer bottom E -->
    </div>
  </footer>
  <!-- footer E -->
</template>

<script>
  export default {
    data: function () {
      return {
        company: {}
      }
    },
    created () {
      this.company = this.$store.state.company
    }
  }
</script>

<style lang="scss" scoped></style>
