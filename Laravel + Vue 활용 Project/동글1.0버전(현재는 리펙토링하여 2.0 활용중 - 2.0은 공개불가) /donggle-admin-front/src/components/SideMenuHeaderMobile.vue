<template>
  <!-- 모바일 메뉴 헤더 -->
  <section class="in-mobile-gnb-hd show-mobile">
    <div class="in-store-profile">
      <a
        v-if="!_isEmpty(store)"
        href="#"
        @click.prevent="goToPage"
      >
        <figure class="symbol-circle">
          <img
            src="/images/img/profile_admin.jpg"
            alt="profile image"
          />
        </figure>
        <h2 class="in-name">{{store.company_name}}</h2>
        <img
          class="in-btn-img"
          src="/images/btn/btn_more.svg"
          alt="btn more"
        />
      </a>
    </div>

    <label
      for="mobile_show_gnb"
      class="icon-close-btn"
    >닫기</label>
    <div class="inner-snb-group">
      <a
        href="#"
        class="icon-home-btn in-icon-btn"
        @click.prevent="$router.push('/main')"
      >
        <i class="hide-text">홈</i>
      </a>
      <!-- 새 알림 있으면 클래스 목록에 active 추가 -->
      <a
        href="#"
        v-if="isConfirm"
        class="icon-alarm-btn in-icon-btn"
        :class="{active: isNewNotification}"
        @click.prevent="$router.push('/alarm-view')"
      >
        <i class="hide-text">알림</i>
      </a>
      <!-- 새 문의 있으면 클래스 목록에 active 추가 -->
      <a
        href="#"
        v-if="isConfirm"
        class="icon-contact-btn in-icon-btn"
        @click.prevent="$router.push('/inquiry-store-list')"
      >
        <i class="hide-text">문의</i>
      </a>
    </div>

    <div class="in-bottom-btns clearfix">
      <a
        href="#"
        class="link-cs-btn"
        @click.prevent="$router.push('/cs')"
      >고객센터</a>
      <a
        href="#"
        @click.prevent="logout"
      >로그아웃</a>
    </div>
  </section>
  <!-- END모바일 메뉴 헤더 -->
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'SideMenuHeaderMobile',
  computed: {
    ...mapGetters([
      'isNewNotification',
      'store'
    ])
  },
  methods: {
    ...mapActions([
      'logoutUser'
    ]),
    async logout () {
      await this.logoutUser()

      window.location.replace('/')
    },
    goToPage () {
      this.NativePopup('https://dong-gle.co.kr/store/' + this.store.store_id)
    }
  }
}
</script>

<style>
</style>
