<template>
  <!--
    헤더 팝업은 여기에만 구현되어있음
    .in-snb-hidden
  -->
  <!-- header -->
  <header id="admin-header">
    <!-- 상단고정 헤더 -->
    <div id="header-hd">
      <div class="show-pc">
        <!-- 상단 side navigation -->
        <section class="hd-snb-bar">
          <div class="in-store-profile">
            <a
              v-if="!_isEmpty(store)"
              href="#"
              @click.prevent="goToPage"
            >
              <figure class="symbol-circle">
                <img
                  :src="_get(JSON.parse(store.profile_img), 0, null)?storagePath(_get(JSON.parse(store.profile_img), 0, null)):'/images/img/profile_admin.jpg'"
                  alt="profile image"
                />
              </figure>
              <h2 class="in-name">{{store.company_name || '신규판매자님 환영합니다! 스토어정보를 입력해 주신 후 승인심사가 완료되면 판매가능합니다.'}}</h2>
              <img
                class="in-btn-img"
                src="/images/btn/btn_more.svg"
                alt="btn more"
              />
            </a>
          </div>

          <div class="in-snb-group">
            <div
              v-if="isConfirm"
              class="in-snb-list"
            >
              <span
                v-on-clickaway="alarmAway"
                class="in-snb-name"
                @click="isAlarmHover = !isAlarmHover"
              >알림</span>

              <!-- 알림 팝업 -->
              <div
                class="in-snb-hidden"
                :style="{display: isAlarmHover ? 'block' : ''}"
              >
                <h2>알림</h2>

                <template v-if="notifications.length > 0">
                  <div class="hd-alarm-wrap">
                    <div
                      v-for="notification in notifications"
                      :key="notification.not_id"
                      class="hd-alarm"
                      :class="{new: !Boolean(notification.not_read_datetime)}"
                      @click="clickNotification(notification)"
                    >
                      <figure class="symbol-circle">
                        <img
                          v-if="notification.store_profile_img"
                          :src="_get(JSON.parse(notification.store_profile_img), 0, null)?storagePath(_get(JSON.parse(notification.store_profile_img), 0, null)):'/images/img/profile_admin.jpg'"
                          alt="profile image"
                        />
                        <img
                          v-else-if="notification.profile_img"
                          :src="_get(JSON.parse(notification.profile_img), 0, null)?_get(JSON.parse(notification.profile_img), 0, null):storagePath(_get(JSON.parse(notification.profile_img), 0, null))"
                          alt="profile image"
                        />
                        <img
                          v-else-if="notification.from_name"
                          src="/images/img/basic_profile/01.png"
                          alt="profile image"
                        />
                        <img
                          v-else
                          src="/images/img/profile_admin.png"
                          alt="profile image"
                        />
                      </figure>
                      <div class="hd-alarm-desc_wrap">
                        <div class="hd-alarm-desc">
                          <div
                            class="_from"
                            v-if="notification.from_storename"
                          >{{notification.from_storename}}</div>
                          <div
                            class="_from"
                            v-else-if="notification.from_name"
                          >{{notification.from_name}}</div>
                          <div
                            class="_from"
                            v-else
                          >동글 고객센터</div>
                          <div class="_date">{{(notification.not_datetime || '').split(' ')[0]}}</div>
                          <div class="_text">{{notification.not_message}}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <!-- 알림 없을 때 -->
                </template>

                <div class="hd-alrim-allview">
                  <a
                    href="#"
                    @click.prevent="$router.push('/alarm-view')"
                  >+ 전체보기</a>
                </div>
              </div>
            </div>
            <a
              href="#"
              v-if="isConfirm"
              class="in-snb-list"
              @click.prevent="$router.push('/inquiry-store-list')"
            >
              <span class="in-snb-name">스토어 문의</span>
            </a>
            <div class="in-snb-list">
              <span
                v-on-clickaway="csAway"
                class="in-snb-name"
                @click="isCsHover = !isCsHover"
              >고객센터</span>

              <!-- cs 링크 팝업 -->
              <div
                class="in-snb-hidden cs-content-wrap"
                :style="{display: isCsHover ? 'block' : ''}"
              >
                <div class="cs-content">
                  <a
                    href="#"
                    @click.prevent="$router.push('/cs-notice-main')"
                  >공지사항</a>
                  <a
                    href="#"
                    @click.prevent="$router.push('/cs-faq')"
                  >자주 묻는 질문</a>
                  <a
                    href="#"
                    @click.prevent="$router.push('/cs-support')"
                  >관리자 1:1 문의</a>
                </div>
              </div>
            </div>
            <div class="in-snb-list">
              <input
                type="button"
                value="로그아웃"
                @click="logout"
              />
            </div>
          </div>
        </section>
        <!-- END 상단 side navigation -->

        <!-- 상단 타이틀바 -->
        <section
          v-if="title !== null"
          class="hd-title-bar"
        >
          <div class="in-page-name">
            <h3 class="in-main">
              <slot name="title-message-before" />
              <span>{{title}}</span>
              <slot name="title-message" />
            </h3>
          </div>

          <div
            class="in-page-btn-group"
            :class="[{'progress_wait': progressShow}]"
          >
            <slot name="before-button-right" />
            <input
              v-if="buttonRight"
              type="button"
              class="rounded-square-btn"
              :class="buttonRightThemes"
              :value="buttonRight"
              @click="$emit('button-right-click')"
            />
          </div>
        </section>
        <!-- END 상단 타이틀바 -->
      </div>

      <div class="show-mobile">
        <!-- 모바일 상단 타이틀바 -->
        <div class="hd-title-bar">
          <div class="hd-title-bar-inner">
            <div class="in-left-btn">
              <label
                for="mobile_show_gnb"
                class="in-label-btn"
              >
                <span class="hbg"></span>
                <span class="hbg"></span>
                <span class="hbg"></span>
              </label>
            </div>

            <slot name="after-button-left-mobile" />

            <div
              class="in-page-name"
              :class="{ ta_left : storeInquiryDt }"
            >
              <h3
                v-if="titleMobile !== null"
                class="in-main"
              >
                <span v-if="titleMobile">{{titleMobile}}</span>
                <span v-else>{{title}}</span>
                <slot name="title-message" />
              </h3>

              <h3
                v-else
                class="in-main"
              >
                <a
                  href="#"
                  @click.prevent
                >
                  <img
                    src="/images/logo/logo_header_bk.svg"
                    alt="image"
                    class="in-logo"
                  />
                </a>
              </h3>
            </div>

            <div
              class="in-page-btn-group"
              :class="[{'progress_wait': progressShow}]"
            >
              <slot name="before-button-right-mobile" />
              <input
                v-if="buttonRight"
                type="button"
                class="mobile-btn-theme"
                :value="buttonRight"
                @click="$emit('button-right-click')"
              />
            </div>
          </div>
        </div>
        <!-- END 모바일 상단 타이틀바 -->
      </div>
    </div>
    <!-- END 상단고정 헤더 -->

    <!-- 왼쪽 global navigation -->
    <input
      id="mobile_show_gnb"
      class="none"
      type="checkbox"
    />
    <label
      for="mobile_show_gnb"
      class="mobile-overlay show-mobile"
    ></label>

    <div id="header-gnb">
      <!-- 메뉴 상단 헤더 -->
      <side-menu-header />
      <!-- END 메뉴 상단 헤더 -->

      <!-- 모바일 메뉴 헤더 -->
      <side-menu-header-mobile />
      <!-- END 모바일 메뉴 헤더 -->

      <!-- 메뉴 -->
      <side-menu />
      <!-- END 메뉴 -->
    </div>
    <!-- END 왼쪽 global navigation -->
  </header>
  <!-- END header -->
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { mixin as clickaway } from 'vue-clickaway'

export default {
  name: 'LayoutHeader',
  mixins: [clickaway],
  props: {
    title: {
      type: String,
      default: ''
    },
    titleMobile: {
      type: String,
      default: ''
    },
    buttonRight: {
      type: String,
      default: ''
    },
    buttonRightThemes: {
      type: Array,
      default: () => ['btn-theme']
    },
    storeInquiryDt: {
      type: Boolean,
      default: null
    }
  },
  data () {
    return {
      isAlarmHover: false,
      isCsHover: false
    }
  },
  computed: {
    ...mapGetters([
      'progressShow',
      'store',
      'notificationList'
    ]),
    notifications () {
      return this.notificationList.slice(0, 4)
    }
  },
  methods: {
    ...mapActions([
      'logoutUser',
      'readNotification'
    ]),
    alarmAway (e) {
      if (this.isAlarmHover) {
        this.isAlarmHover = false
      }
    },
    csAway (e) {
      if (this.isCsHover) {
        this.isCsHover = false
      }
    },
    async logout () {
      await this.logoutUser()

      window.location.replace('/')
    },
    clickNotification (item) {
      if (!item.not_read_datetime) {
        item.not_read_datetime = true
        this.readNotification(item)
      }

      this.NativePopup(item.not_url)
    },
    goToPage () {
      console.log('')
      if (this.store.confirm) {
        if (process.env.VUE_APP_ENV === 'LOCAL') {
          this.NativePopup('http://localhost:8080/store/' + this.store.store_id)
        } else {
          this.NativePopup(process.env.VUE_APP_PRODC_URI.replace('store.', '') + '/store/' + this.store.store_id)
        }
      } else {
        alert('스토어 입점 신청 승인이 되어야 스토어홈으로 이동할 수 있습니다.')
      }
    }
  }
}
</script>

<style lang="scss" scoped>
  .progress_wait {
    pointer-events: none;
    opacity: 0.3;
  }
  @media all and (max-width: 360px) {
    // 스토어문의 상세보기페이지 모바일버전 hd-title-bar
    .in-page-name.ta_left {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      left: 59px;
    }
  }
  @media all and (max-width: 320px) {
    // 스토어문의 상세보기페이지 모바일버전 hd-title-bar
    .in-page-name.ta_left {
      left: 27px;
    }
  }
</style>
