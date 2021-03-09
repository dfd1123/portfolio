<template>
  <footer class="page_footer_navigation">
    <router-link to="/">
      <div class="nav_button">
        <img
          v-show="homeOn"
          src="/assets/images/icons/icon_navi-01_on.svg"
          alt="메디플래너 아이콘"
          class="nav_icon nav_icon--on"
        />
        <img
          v-show="!homeOn"
          src="/assets/images/icons/icon_navi-01_off.svg"
          alt="메디플래너 아이콘"
          class="nav_icon nav_icon--on"
        />
        <span class="nav_name" :class="{ active: homeOn }">메디플래너</span>
      </div>
    </router-link>
    <router-link to="/health/status">
      <div class="nav_button">
        <img
          v-show="reportOn"
          src="/assets/images/icons/icon_navi-02_on.svg"
          alt="건강리포트 아이콘"
          class="nav_icon nav_icon--on"
        />
        <img
          v-show="!reportOn"
          src="/assets/images/icons/icon_navi-02_off.svg"
          alt="건강리포트 아이콘"
          class="nav_icon nav_icon--on"
        />
        <span class="nav_name" :class="{ active: reportOn }">건강리포트</span>
      </div>
    </router-link>
    <router-link to="/mypage">
      <div class="nav_button">
        <img
          v-show="mypageOn"
          src="/assets/images/icons/icon_navi-03_on.svg"
          alt="마이페이지 아이콘 on"
          class="nav_icon nav_icon--on"
        />
        <img
          v-show="!mypageOn"
          src="/assets/images/icons/icon_navi-03_off.svg"
          alt="마이페이지 아이콘 on"
          class="nav_icon nav_icon--on"
        />
        <span class="nav_name" :class="{ active: mypageOn }">마이페이지</span>
      </div>
    </router-link>
  </footer>
</template>

<script>
export default {
  name: 'footer-navigation',
  props: {
    homeOn: {
      type: Boolean,
      default: false
    },
    reportOn: {
      type: Boolean,
      default: false
    },
    mypageOn: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../styles/scss/layouts/_footer.scss";
</style>
