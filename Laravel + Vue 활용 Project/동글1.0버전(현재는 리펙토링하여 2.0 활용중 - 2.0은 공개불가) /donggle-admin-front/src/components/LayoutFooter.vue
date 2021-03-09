<template>
  <div id="admin-footer">
    <span class="in-copyright">
      Copyrights ©
      <i>{{this.company.company_name}}</i>
    </span>
    <div class="in-company-info">
      <span>회사명 : {{this.company.company_name}}</span>
      <span>대표이사 : {{this.company.ceo_name}}</span>
      <span>사업자등록번호 : {{this.company.buisness_number}}</span>
      <span>통신판매업신고 : {{this.company.cyber_trade_number}}</span>
      <span>호스팅사업자 : {{this.company.company_name}}</span>
      <span class="in-address">주소 : ({{this.company.post_num}}) {{this.company.addr1}}{{this.company.addr2}}</span>
    </div>
    <div class="snb-btn-wrap">
      <span><a
          href="#"
          @click.prevent="NativePopup('/privacy')"
        >개인정보처리방침</a></span>
      <span><a
          href="#"
          @click.prevent="NativePopup('/term')"
        >이용약관안내</a></span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LayoutFooter'
}
</script>

<style lang="scss">
  .snb-btn-wrap {
    span {
      display: inline-block;
      padding: 4px 5px;
      margin: 0 4px;
    }
  }
</style>
