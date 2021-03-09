<template>
  <div id="dg-mypage-default-wrapper">
    <!-- * 마이페이지 헤더 -->
    <div class="l-mypage-hd">
      <h2>
        <span>마이페이지</span>
        <label
          for="profile_img_change"
          class="_profill_btn"
        >
          <input
            type="file"
            id="profile_img_change"
            class="display_none"
            value
            ref="profile_img"
            accept="image/x-png, image/gif, image/jpeg"
            @change="ProfileImgChange()"
          >
        </label>
        <button
          type="button"
          class="_alrim_btn"
          @click="ArlimOpen"
        >
          알림
        </button>
      </h2>
      <div class="l-mypage-hd-inner">
        <div class="in-profile">
          <figure class="_pic">
            <div class="symbol-circle">
              <img
                v-if="ConvertImage($store.state.user.profile_img).length > 0"
                v-gallery
                :src="$store.state.user.profile_img.includes('http')?ConvertImage($store.state.user.profile_img)[0]:storageUrl + ConvertImage($store.state.user.profile_img)[0]"
                alt="default profile img"
                class="_imgtag"
              >
              <img
                v-else
                src="/images/img/thumbnail.png"
                alt="default profile img"
                class="_imgtag"
              >
            </div>
            <!-- ※ 회원등급에 따라서 lv-01, lv-02, lv-03, lv-04, lv-05 클래스 바뀜 -->
            <span :class="'_grade lv-0'+$store.state.user.level"></span>
            <!-- ※ 회원등급에 따라서 lv-01, lv-02, lv-03, lv-04, lv-05 클래스 바뀜 END -->
          </figure>
          <h3 class="_name">
            <b>{{ $store.state.user.name }}</b>
            <span>님의 등급은</span>
            <i>{{ LevelName($store.state.user.level) }}</i>
            <span>입니다.</span>
            <router-link
              to="/mypage/level_guide"
              class="grade_info"
            >
              등급안내>
            </router-link>
          </h3>
        </div>

        <ul class="in-panels">
          <li class="_list">
            <span>쿠폰</span>
            <router-link
              to="/mypage/mycoupon"
              class="mypage-btn mypage-btn04"
            >
              {{ NumberFormat($store.state.mypageInfo.couponCount || 0) }}개
            </router-link>
          </li>
          <li class="_list">
            <span>주문완료</span>
            <router-link
              to="/mypage/order/history"
              class="mypage-btn mypage-btn01"
            >
              {{ NumberFormat($store.state.mypageInfo.orderComplete || 0) }}건
            </router-link>
          </li>
          <li class="_list">
            <span>취소/환불</span>
            <router-link
              to="/mypage/cancel/history"
              class="mypage-btn mypage-btn01"
            >
              {{ NumberFormat($store.state.mypageInfo.orderCancel || 0) }}건
            </router-link>
          </li>
        </ul>
      </div>
    </div>
    <!-- * 마이페이지 헤더 -->

    <div class="l-mypage-contents">
      <div class="mypage-btn-wrap clear_both">
        <router-link
          to="/mypage/order/history"
          class="mypage-btn mypage-btn01"
          v-ripple
        >
          주문배송
        </router-link>
        <router-link
          to="/mypage/review/write"
          class="mypage-btn mypage-btn02"
          v-ripple
        >
          나의 구매후기
        </router-link>
        <router-link
          to="/mypage/like/list"
          class="mypage-btn mypage-btn03"
          v-ripple
        >
          관심리스트
        </router-link>
        <router-link
          to="/mypage/mycoupon"
          class="mypage-btn mypage-btn04"
          v-ripple
        >
          쿠폰
        </router-link>
        <router-link
          to="/mypage/info"
          class="mypage-btn mypage-btn05"
          v-ripple
        >
          내 정보
        </router-link>
        <router-link
          to="/notices"
          class="mypage-btn mypage-btn06"
          v-ripple
        >
          고객센터
        </router-link>
      </div>

      <div class="link-title">
        <router-link
          to="/cart"
          v-ripple
        >
          장바구니
        </router-link>
        <router-link
          to="/mypage/like/list"
          v-ripple
        >
          내가 찜한 상품
        </router-link>
        <router-link
          to="/mypage/recent/list"
          v-ripple
        >
          최근 본 상품
        </router-link>
        <router-link
          to="/mypage/company_info"
          v-ripple
        >
          회사정보
        </router-link>
        <a
          href="#"
          @click.prevent="PolicyPopupOpen"
        >개인정보처리방침</a>
        <a
          href="#"
          @click.prevent="TermPopupOpen"
        >이용약관</a>
        <a
          href="#"
          @click.prevent
          class="guide-version"
        >
          버전안내
          <span class="in-version">1.0.0</span>
        </a>
      </div>

      <div>
        <button
          class="logout_btn"
          @click="Logout()"
        >
          <img
            src="/images/btn/btn_logout.svg"
            alt="로그아웃"
          >
          <span>로그아웃</span>
        </button>
      </div>
    </div>
    <ArlimPopup
      :class="{'posFixed': arlimPopup}"
      :arlim-lists="arlimLists"
      :limit="arlimForm.limit"
      @load-more="ArlimMoreLoad()"
      @reload="ArlimReload()"
      @close-popup="ArlimClose"
    />
    <PrivacyPolicy
      :class="{'posFixed': policyPopup}"
      @popup-close="PolicyPopupClose"
    />
    <UseTerm
      :class="{'posFixed': termPopup}"
      @popup-close="TermPopupClose"
    />
  </div>
</template>

<script>
import ArlimPopup from '@/components/popup/arlim-popup.vue'
import PrivacyPolicy from '@/components/popup/policy.vue'
import UseTerm from '@/components/popup/term.vue'

export default {
  components: {
    ArlimPopup,
    PrivacyPolicy,
    UseTerm
  },
  data: function () {
    return {
      arlimForm: {
        count: 0,
        limit: 10,
        offset: 0
      },
      arlimLists: [],
      arlimPopup: false,
      policyPopup: false,
      termPopup: false
    }
  },
  async created () {
    const res = await this.ArlimLoad()
    this.MypageInfo()

    this.arlimLists = res.notification
    this.arlimForm.count = res.count
    this.arlimForm.offset += res.notification.length
  },
  methods: {
    async ArlimLoad () {
      const params = this.arlimForm

      try {
        const res = (
          await this.$http.get(this.$APIURI + 'notification/list', { params })
        ).data

        if (res.state === 1) {
          return res.query
        } else {
          console.log(res.msg)
        }
      } catch (e) {
        console.log(e)
      }
    },
    async ArlimMoreLoad () {
      if (this.arlimForm.offset === this.arlimForm.count) {
        return false
      }
      const res = await this.ArlimLoad()
      this.arlimLists = this.arlimLists.concat(res.notification)
      this.arlimForm.offset += res.notification.length
    },
    async ArlimReload () {
      const params = {
        offset: 0,
        limit: this.arlimForm.offset
      }

      try {
        const res = (
          await this.$http.get(this.$APIURI + 'notification/list', { params })
        ).data

        if (res.state === 1) {
          this.arlimLists = res.query.notification
          this.arlimForm.count = res.query.count
          this.arlimForm.offset += res.query.notification.length
        } else {
          console.log(res.msg)
        }
      } catch (e) {
        console.log(e)
      }
    },
    async MypageInfo () {
      try {
        const res = (await this.$http.get(this.$APIURI + 'mypage/header')).data

        if (res.state === 1) {
          this.$store.commit('MypageInfoSet', res.query)
        } else {
          console.log(res.msg)
        }
      } catch (e) {
        console.log(e)
      }
    },
    async Logout () {
      const result = await this.Confirm('정말 로그아웃 하시겠습니까?')
      if (result) {
        this.$store.dispatch('Logout')
      }
    },
    async ProfileImgChange () {
      let profileImages = []
      profileImages.push(this.$refs.profile_img.files[0])
      const formData = new FormData()

      formData.append('_method', 'put')

      await profileImages.forEach((image, index) => {
        formData.append('profile_img[]', image)
      })

      try {
        const res = (
          await this.$http.post(this.$APIURI + 'users/profile_img', formData, {
            headers: {
              'Content-type': 'application/json; multipart/form-data;'
            }
          })
        ).data

        if (res.state === 1) {
          this.$store.commit('UserStoreInfor', res.query)
        } else {
          console.log(res.msg)
        }
      } catch (e) {
        console.log(e)
      }
    },
    ArlimOpen () {
      this.arlimPopup = true
      document.body.style.overflowY = 'hidden'
    },
    ArlimClose () {
      this.arlimPopup = false
      document.body.style.overflowY = 'auto'
    },
    PolicyPopupOpen () {
      document.body.style.overflowY = 'hidden'
      this.policyPopup = true
    },
    PolicyPopupClose () {
      document.body.style.overflowY = 'auto'
      this.policyPopup = false
    },
    TermPopupOpen () {
      document.body.style.overflowY = 'hidden'
      this.termPopup = true
    },
    TermPopupClose () {
      document.body.style.overflowY = 'auto'
      this.termPopup = false
    }
  }
}
</script>

<style lang="scss" scoped>
#dg-mypage-default-wrapper {
  overflow: hidden;
  background-color: #f0f0f0;
  min-height: 100vh;
  padding-bottom: 100px;

  .l-mypage-hd {
    margin-bottom: 0;
    padding-bottom: 10px;
    background-color: white;
  }

  .l-mypage-contents {
    background-color: white;
  }

  .link-title > a.guide-version {
    background-image: unset;
    position: relative;

    .in-version {
      position: absolute;
      top: 50%;
      right: 20px;
      transform: translateY(-50%);
      color: #808080;
    }
  }
}
</style>
