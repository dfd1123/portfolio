<template>
  <div id="app">
    <div
      id="admin-container"
      class="auth-container"
    >
      <div class="wrapper">
        <!-- page content -->
        <div
          id="page-register-comple-wrap"
          class="page-auth-wrap"
        >
          <div class="panel-default">
            <div class="form-wrapper">
              <div class="auth-logo-wrap">
                <h2 class="auth-hd-logo">donggle</h2>
                <p class="_sub_title">동글 입점스토어 관리자</p>
              </div>
            </div>

            <!-- content -->
            <section class="auth-layout">
              <img
                src="/images/icon/icon_complete.svg"
                alt="완료 아이콘"
                class="comple-icon"
              />
              <h2 class="comple-ment">
                축하합니다,
                <b>{{user.name}}</b>님!
                <br />동글 회원가입이 완료되었습니다.
              </h2>

              <div class="dg-popup_alert_text">
                이 계정으로 동글몰과 가맹점페이지 모두 사용 가능합니다.
              </div>

              <div class="dg-btn-wrap">
                <button
                  type="button"
                  class="square-md-btn auth-btn go-to-btn _full"
                  @click.prevent="$router.push('/')"
                >홈으로</button>
              </div>
            </section>
            <!-- content E -->
          </div>
        </div>
        <!-- page content E -->
      </div>
      <layout-footer />
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegisterComplete'
}
</script>

<style lang="scss" scoped>
  #page-register-comple-wrap {
    .auth-layout {
      text-align: center;
    }

    .comple-ment {
      font-size: 20px;
      font-weight: 400;
      color: $primary-color;
      letter-spacing: -0.8px;
      margin-bottom: 35px;

      @media all and (max-width: 576px) {
        & {
          font-size: 16px;
        }
      }

      b {
        color: $primary-color-01;
      }
    }

    .comple-icon {
      margin: 50px auto 30px;
      display: block;

      @media all and (max-width: 576px) {
        & {
          margin: 60px auto 20px;
        }
      }
    }

    .go-to-btn {
      transition: 0.3s;
      border: 1px solid #333;

      @media all and (min-width: 1024px) {
        &:hover {
          background-color: darken(white, 5%);
        }
      }

      &:active {
        background-color: darken(white, 8%);
      }
    }
  }

  .dg-popup_alert_text {
    padding: 20px 0px;
    font-size: 0.75em;
    font-weight: 300;
    text-align: center;
    line-height: 1.6;
  }
</style>
