<template>
  <div id="mypage_main" class="page page--hd_default">
    <header-component hd-title="마이페이지" :priv-button="false" :hd-theme-ylw="true" />
    <div class="page_container pd_bt_nav">
      <transition appear name="slide-fadeup">
        <div class="contents">
          <!-- 마이페이지 메뉴1 (사진수정) -->
          <div class="mypage_menu mypage_menu--01">
            <div class="user_profile_panel">
              <input
                ref="file1"
                id="profile_pic"
                type="file"
                accept=".jpg, .jpeg, .png, .webp"
                style="display: none"
                @change="editProfileImg"
              />
              <label for="profile_pic" class="user_profile_picture">
                <figure class="pic_circle" ref="img_wrap">
                  <img
                    v-img-orientation-changer
                    ref="img_file1"
                    :src="`/fdata/user_thumb/${user.usr_thumb}`"
                    class="preview"
                    v-if="user.usr_thumb"
                  />
                  <!-- 이미지 설정한게 없을 때 -->
                  <img
                    v-img-orientation-changer
                    ref="img_file1"
                    src="/assets/images/pics/profile_img_null.svg"
                    v-else
                  />
                  <!-- end -->
                </figure>
                <i class="upload_btn">
                  <img src="/assets/images/btn/btn_profile.svg" />
                </i>
              </label>
              <dl>
                <dt>{{ this.user.usr_name }}</dt>
                <dd>{{ userAge+'세' }}</dd>
                <dd>{{ this.user.usr_extra.user_type }}</dd>
                <dd class="color_theme-02">{{ this.user.usr_email }}</dd>
              </dl>
            </div>
          </div>
          <!-- end -->
          <!-- 마이페이지 메뉴2 (로그아웃)) -->
          <div class="mypage_menu mypage_menu--02">
            <a href="#" @click.prevent="logout">로그아웃</a>
          </div>
          <!-- end -->
          <!-- 마이페이지 메뉴3 (알림설정) -->
          <div class="mypage_menu mypage_menu--03">
            <span>모든 알림 무시</span>
            <div class="form_item">
              <div class="form_set_alarm" :class="{ activeon : setMute }">
                <button
                  type="button"
                  class="in_option in_option--on"
                  @click="toggleAlarmMute(true)"
                >
                  <b>on</b>
                </button>
                <button
                  type="button"
                  class="in_option in_option--off"
                  @click="toggleAlarmMute(false)"
                >
                  <b>off</b>
                </button>
              </div>
            </div>
          </div>
          <!-- end -->
          <!-- 마이페이지 메뉴4~7 -->
          <ul class="mypage_menu_group">
            <li class="mypage_menu mypage_menu--title">
              <h2>고객센터</h2>
            </li>
            <li class="mypage_menu mypage_menu--04 mypage_menu--arrow">
              <router-link to="/mypage/notice">공지사항</router-link>
            </li>
            <!--
            <li class="mypage_menu mypage_menu--05">
              <span>버전정보</span>
              <span class="etc_info color_theme-02">
                현재 1.0.0 /
                <i>최신 5.0.0</i>
              </span>
            </li>
            -->
            <li class="mypage_menu mypage_menu--06 mypage_menu--arrow">
              <router-link to="/mypage/terms-cs">서비스 이용약관</router-link>
            </li>
            <li class="mypage_menu mypage_menu--07 mypage_menu--arrow">
              <router-link to="/mypage/terms-info">개인정보 취급방침</router-link>
            </li>
          </ul>
          <!-- end -->
          <!-- 마이페이지 메뉴8 (회원탈퇴) -->
          <div class="mypage_menu mypage_menu--08">
            <router-link to="/mypage/secession">회원탈퇴</router-link>
          </div>
          <!-- end -->
        </div>
      </transition>
    </div>
    <footer-navigation :mypage-on="true"></footer-navigation>
  </div>
</template>

<script>
import get from 'lodash/get'
import { mapGetters, mapActions } from 'vuex'
import Vue from 'vue'
import VueImgOrientationChanger from 'vue-img-orientation-changer'
import loadImage from 'blueimp-load-image'
Vue.use(VueImgOrientationChanger)

export default {
  name: 'MyPageMain',
  data () {
    return {
      setMute: false,
      files: {}
    }
  },
  created () {
    this.setMute = Boolean(get(this.user.usr_extra, 'alarm_mute', false))
  },
  computed: {
    ...mapGetters([
      'user',
      'planList'
    ]),
    userAge () {
      const time = this.$moment().format('YYYY')
      const userBirthInfo = this.user.usr_extra.born_year

      const userAge = time - userBirthInfo + 1
      return userAge
    }
  },
  methods: {
    ...{ get },
    ...mapActions(['updateUser', 'getPlan']),
    async logout () {
      // 알림 해제
      this.planList.forEach((item) => {
        if (!Number(item.id)) {
          window.$EventBus.$emit('reset-alarm-request', {
            id: item.id
          })
        }
      })

      localStorage.removeItem('token')
      localStorage.removeItem('bcg_confirm_restriction')
      window.location.reload()
    },
    imageFileChange (name) {
      const fileInput = this.$refs[name]

      const { files } = fileInput
      if (files && files.length) {
        const fr = new FileReader()

        fr.onload = () => {
          const tag = this.$refs[`img_${name}`]
          if (tag.tagName.toLowerCase() === 'img') {
            tag.src = fr.result
          } else {
            tag.style['background-image'] = `url(${fr.result})`
          }
        }
        fr.readAsDataURL(files[0])
      }
    },
    editProfileImg (e) {
      const file = e.target.files[0]
      const imgWrap = this.$refs.img_wrap
      loadImage(file, async (img) => {
        imgWrap.removeChild(imgWrap.firstChild)
        imgWrap.appendChild(img)

        if (img) {
          try {
            const editData = new FormData()
            editData.append('file1', await this.compressImage(file))

            await this.updateUser(editData)
          } catch (e) {
            console.log(e)
          }
        }
      }, {
        orientation: true
      })
    },
    async toggleAlarmMute (state) {
      this.setMute = state

      try {
        await this.updateUser({
          usr_extra: JSON.stringify({ ...this.user.usr_extra, ...{ alarm_mute: state } })
        })

        await this.getPlan()
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#mypage_main {
  .contents {
    padding: 0 0 2rem;
    background-color: #f5f5f5;
    overflow-y: scroll;
  }
  .mypage_menu_group {
    padding: 20px 0;
    background-color: white;
    margin: 10px 0;
    h2 {
      @include remFont("18px", $weight: bold, $color: $theme-02);
      margin-bottom: 12px;
    }
  }
  .mypage_menu_group--pd0 {
    padding: 0;
  }
  .mypage_menu_group .mypage_menu {
    margin: 0;
    &:last-child {
      border-bottom: 0;
    }
  }
  .mypage_menu {
    padding: 0 1.5rem;
    background-color: white;
    border-bottom: 1px solid #e6e6e6;
    margin: 10px 0;
    a {
      @include remFont("14px", $color: $gray-01);
      width: 100%;
      display: inline-block;
      padding: 14px 0;
    }
    &--arrow a {
      @include bgStyle(100%, 50%, 10px);
      background-image: url("/assets/images/btn/btn_arrow_ylw.svg");
    }
  }
  .mypage_menu--01 {
    margin: 0;
    padding: 1.25rem 1.5rem;
  }
  .mypage_menu--02 {
    margin-top: 0;
    text-align: center;
  }
  .mypage_menu--03 {
    .form_item {
      padding: 0;
      float: right;
    }
    .form_item .form_set_alarm:before {
      width: 50px;
    }
    .form_item .form_set_alarm.activeon:before {
      right: 50px;
    }
    .form_set_alarm {
      width: 100px;
      height: 25px;
      position: relative;
      font-size: 11px;
    }
  }
  .mypage_menu--05,
  .mypage_menu--03 {
    @include remFont("14px", $color: $gray-01);
    padding: 14px 1.5rem;
    span {
      font-weight: 400;
    }
    .etc_info {
      float: right;
    }
    .etc_info i {
      @include setFont(inherit, $color: $gray-03, $weight: 400);
    }
  }
  .user_profile_panel {
    & > dl dd {
      @include remFont("12px");
    }
    .user_profile_picture {
      @include bgStyle(50%, 50%, 65%);
      z-index: 1;
      border-radius: 50%;
      font-size: 12px;
      background-image: url(/assets/images/logos/logo_bcg_character_dark.svg);
      background-color: #ebeef0;
    }
    .user_profile_picture .pic_circle {
      background-color: transparent;
    }
  }
}
</style>
