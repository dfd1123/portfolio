<template>
  <!-- 활성화된 메뉴 _list 에 active 추가 -->
  <div class="l-side-navi">
    <div class="l-con-title-group type01 mb-10">
      <h2
        class="in-subject"
        @click="$router.push('/mypage')"
      >
        마이페이지
      </h2>
    </div>
    <article class="in-nav-section">
      <h4 class="_subject">
        주문배송
      </h4>
      <ul>
        <li class="_list">
          <router-link to="/mypage/order/history">
            주문내역 조회
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/mypage/cancel/history">
            취소/환불내역
          </router-link>
        </li>
      </ul>
    </article>
    <article class="in-nav-section">
      <h4 class="_subject">
        나의 구매후기
      </h4>
      <ul>
        <li class="_list">
          <router-link to="/mypage/review/write">
            구매후기 작성하기
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/mypage/review/list">
            나의 구매후기
          </router-link>
        </li>
      </ul>
    </article>
    <article class="in-nav-section">
      <h4 class="_subject">
        관심리스트
      </h4>
      <ul>
        <li class="_list">
          <router-link to="/mypage/like/list">
            찜한 상품
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/mypage/recent/list">
            최근 본 상품
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/mypage/store/like">
            즐겨찾기 스토어
          </router-link>
        </li>
      </ul>
    </article>
    <article class="in-nav-section">
      <h4 class="_subject">
        쿠폰
      </h4>
      <ul>
        <li class="_list">
          <router-link to="/mypage/mycoupon">
            내 쿠폰함
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/mypage/couponzone">
            쿠폰받기
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/mypage/coupon/history">
            쿠폰 사용내역
          </router-link>
        </li>
      </ul>
    </article>
    <article class="in-nav-section">
      <h4 class="_subject">
        내 정보
      </h4>
      <ul>
        <li class="_list">
          <router-link to="/mypage/info">
            내 정보 관리
          </router-link>
        </li>
        <!--
        <li class="_list">
          <router-link to="/mypage/payment/info">
            카드 및 환불계좌 관리
          </router-link>
        </li>
        -->
        <li class="_list">
          <router-link to="/mypage/level_guide">
            회원등급
          </router-link>
        </li>
      </ul>
    </article>
  </div>
</template>

<script>
  export default {

  }
</script>

<style lang="scss" scoped>
</style>
