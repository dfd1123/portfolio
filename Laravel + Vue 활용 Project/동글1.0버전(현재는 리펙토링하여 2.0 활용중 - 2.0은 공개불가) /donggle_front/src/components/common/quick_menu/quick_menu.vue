<template>
  <aside id="_mini_quick_menu">
    <div class="_img_box">
      <img
        src="/images/badge/badge_3.svg"
        alt="logo"
      >
    </div>
    <h2 class="_title">
      #QUICK
    </h2>
    <ul class="_mini_quick_menu_btn_wrap">
      <li>
        <router-link
          to="/mypage/recent/list"
          class="_mini_quick_menu_btn"
        >
          최근 본 상품
        </router-link>
      </li>
      <li>
        <router-link
          to="/cart"
          class="_mini_quick_menu_btn"
        >
          장바구니
        </router-link>
      </li>
      <li>
        <router-link
          to="/mypage/like/list"
          class="_mini_quick_menu_btn"
        >
          찜한 상품
        </router-link>
      </li>
      <li>
        <router-link
          to="/mypage"
          class="_mini_quick_menu_btn"
        >
          마이페이지
        </router-link>
      </li>
      <li>
        <router-link
          to="/notices"
          class="_mini_quick_menu_btn"
        >
          고객센터
        </router-link>
      </li>
      <li>
        <a
          href="#"
          class="_mini_quick_menu_btn _mini_quick_menu_btn_arrow _mini_quick_menu_btn_top"
          v-scroll-to="'#app'"
        >위로</a>
      </li>
      <li>
        <a
          href="#"
          class="_mini_quick_menu_btn _mini_quick_menu_btn_arrow _mini_quick_menu_btn_bottom"
          v-scroll-to="'#dg-ft'"
        >아래로</a>
      </li>
    </ul>
  </aside>
</template>

<script>
  export default {

  }
</script>

<style lang="scss" scoped>
</style>
