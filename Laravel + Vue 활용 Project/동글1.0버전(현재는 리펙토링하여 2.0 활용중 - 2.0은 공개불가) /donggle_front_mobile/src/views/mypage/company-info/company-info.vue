<template>
  <div id="dg-mypage-company-info-wrapper">
    <div class="clear_both _page_title_wrap">
      <h2>회사정보</h2>
      <a
        href="#"
        class="_back_btn"
        @click.prevent="$router.push('/mypage')"
      >뒤로가기</a>
    </div>
    <div class="company-info-container">
      <h2 class="company-name">
        {{ company.company_name || '-' }}
      </h2>
      <p class="company-ceo">
        대표이사 {{ company.ceo_name || '-' }}
      </p>
      <div class="company-info-group">
        <dl class="in-list mb-10">
          <dt class="in-dt">
            주소
          </dt>
          <dd class="in-dd">
            ({{ company.post_num || '-' }}) {{ company.addr1 || '-' }} {{ company.addr2 || '-' }}
          </dd>
        </dl>
        <dl class="in-list">
          <dt class="in-dt">
            영업시간
          </dt>
          <dd class="in-dd">
            AM 10:00 ~ PM 19:00(주말, 공휴일 제외)
          </dd>
        </dl>
        <dl class="in-list">
          <dt class="in-dt">
            점심시간
          </dt>
          <dd class="in-dd">
            AM 12:00 ~ PM 13:30
          </dd>
        </dl>
      </div>
      <div class="company-info-group">
        <dl class="in-list">
          <dt class="in-dt">
            사업자등록번호
          </dt>
          <dd class="in-dd">
            {{ company.buisness_number }}
          </dd>
        </dl>
        <dl class="in-list">
          <dt class="in-dt">
            통신판매업신고
          </dt>
          <dd class="in-dd">
            {{ company.cyber_trade_number || '-' }}
          </dd>
        </dl>
        <dl class="in-list">
          <dt class="in-dt">
            호스팅사업자
          </dt>
          <dd class="in-dd">
            {{ company.company_name || '-' }}
          </dd>
        </dl>
      </div>
    </div>
  </div>
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

<style lang="scss" scoped>
#dg-mypage-company-info-wrapper {
  min-height: 100vh;

  ._page_title_wrap {
    position: relative;
  }
  ._back_btn {
    z-index: 99;
  }

  .company-info-container {
    padding: 15px;
  }

  .company-name {
    font-size: 1.25em;
    padding-bottom: 10px;
    color: #fc4ead;
  }

  .company-ceo {
    color: #5d5d5d;
    font-size: 0.935em;
  }

  .company-info-group {
    margin: 20px 0;
    color: #736678;
    font-size: 0.875em;
  }

  .company-info-group .in-list {
    position: relative;
    display: flex;
    margin-bottom: 7px;

    &.mb-10{
    margin-bottom: 20px;
  }
  }

  .company-info-group .in-list:last-child{
    margin-bottom: 0;
  }

  .company-info-group .in-dd {
    vertical-align: top;
    display: inline-block;
    opacity: 0.7;
  }

  .company-info-group .in-dt {
    vertical-align: top;
    display: inline-block;
    margin-right: 7px;
  }

  @media all and (max-width: 320px){
    .company-info-container{
      font-size: 15px;
    }
    .company-info-group .in-dd{
      font-size: 0.9em;
    }
  }
}

</style>
