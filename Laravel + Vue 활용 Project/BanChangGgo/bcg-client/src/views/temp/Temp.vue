<template>
  <div id="temp_page">
    <section>
      <h2>기본기능</h2>
      <ul>
        <li class="active">
          <router-link to="/login">
            로그인 (화면완료)
            <b>기능완료</b>
          </router-link>
        </li>
        <li class="active">
          <router-link to="/register">
            회원가입 (화면완료)
            <b>기능완료</b>
          </router-link>
        </li>
        <li class="active">
          <router-link to="/findpw">비밀번호찾기 (화면완료)</router-link>
        </li>
      </ul>
    </section>
    <section>
      <h2>main</h2>
      <ul>
        <li class="active">
          <router-link to="/">메인화면(메디플래너) (화면완료)</router-link>
        </li>
      </ul>
    </section>
    <section>
      <h2>기능1 - 메디플래너 관련</h2>
      <ul>
        <li class="active">
          <router-link to="/plan/question">
            <!-- <i>시간多소요</i> -->
            건강사항 작성하기 (화면완료)
          </router-link>
        </li>
        <li class="active">
          <router-link to="/plan/add">플랜추가 (화면완료)</router-link>
        </li>
        <li class="active">
          <router-link to="/plan/info">플랜보기/수정 (화면완료)</router-link>
        </li>
      </ul>
    </section>
    <section>
      <h2>기능2 - 건강리포트 관련</h2>
      <ul>
        <li class="active">
          <router-link to="/health/status">나의 건강상태 (화면완료))</router-link>
        </li>
        <li class="active">
          <router-link to="/health/report">
            <!-- <i>시간多소요</i>  -->
            건강리포트 (화면완료)
          </router-link>
        </li>
        <li class="active">
          <router-link to="/health/report-more">건강리포트 더보기 (화면완료)</router-link>
        </li>
        <li class="active">
          <router-link to="/health/product">건강상품 구매링크 (화면완료)</router-link>
        </li>
        <li class="active">
          <router-link to="/health/content">건강관련컨텐츠 (화면완료)</router-link>
        </li>
      </ul>
    </section>
    <section>
      <h2>기능2 - 마이페이지</h2>
      <ul>
        <li class="active">
          <router-link to="/mypage">마이페이지 (화면완료)</router-link>
        </li>
      </ul>
    </section>
    <!-- <section>
      <input type="button" value="싱글버튼팝업" />
    </section>-->
    <loading-component></loading-component>
  </div>
</template>

<script>
export default {
  name: 'tempPage',
  methods: {
    singlePopup () { }
  }
}
</script>

<style lang="scss" scoped>
#temp_page {
  padding: 0;
  section {
    margin: 10px 0 20px;
  }
  h2 {
    color: $theme-03;
    font-weight: 800;
    font-size: 20px;
  }
  li {
    display: block;
    width: 100%;
    margin: 5px 0;
    color: #272720;
    padding-left: 10px;
  }
  li a {
    color: #272720;
    font-weight: 500;
  }
  li.active a {
    background-color: rgba($sky-blue-01, 0.5);
  }
  li.active02 a {
    background-color: rgba($theme-03, 0.7);
  }
  li b {
    font-weight: 800;
    color: $red-01;
  }
  li i {
    font-weight: 800;
  }
}
</style>
