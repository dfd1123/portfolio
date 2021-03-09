<template>
  <!-- * 마이페이지 헤더 -->
  <div class="l-mypage-hd">
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
              src="/images/img/basic_profile/01.png"
              alt="default profile img"
              class="_imgtag"
            >
          </div>
          <!-- ※ 회원등급에 따라서 lv-01, lv-02, lv-03, lv-04, lv-05 클래스 바뀜 -->
          <span :class="'_grade lv-0'+$store.state.user.level"></span>
          <!-- ※ 회원등급에 따라서 lv-01, lv-02, lv-03, lv-04, lv-05 클래스 바뀜 END -->
        </figure>
        <h3 class="_name">
          <b>{{ $store.state.user.name }}</b><span>님의 등급은</span> <i>{{ LevelName($store.state.user.level) }}</i><span>입니다.</span>
        </h3>
        <div>
          <label
            for="profile_img_change"
            class="rounded-btn-outline"
          >
            프로필 사진 변경
          </label>
          <input
            type="file"
            id="profile_img_change"
            class="display_none"
            value=""
            ref="profile_img"
            accept="image/x-png,image/gif,image/jpeg"
            @change="ProfileImgChange()"
          >
        </div>
      </div>

      <ul class="in-panels">
        <li class="_list">
          <span>쿠폰</span>
          <router-link to="/mypage/mycoupon">
            {{ NumberFormat($store.state.mypageInfo.couponCount || 0) }}개
          </router-link>
        </li>
        <li class="_list">
          <span>주문완료</span>
          <router-link to="/mypage/order/history">
            {{ NumberFormat($store.state.mypageInfo.orderComplete || 0) }}건
          </router-link>
        </li>
        <li class="_list">
          <span>취소/환불</span>
          <router-link to="/mypage/cancel/history">
            {{ NumberFormat($store.state.mypageInfo.orderCancel || 0) }}건
          </router-link>
        </li>
      </ul>
    </div>
  </div>
  <!-- * 마이페이지 헤더 -->
</template>

<script>
  export default {
    data: function () {
      return {
        profileImage: []
      }
    },
    mounted () {
      this.MypageInfo()
    },
    methods: {
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
      async ProfileImgChange () {
        let profileImages = []
        profileImages.push(this.$refs.profile_img.files[0])
        const formData = new FormData()

        formData.append('_method', 'put')

        await profileImages.forEach((image, index) => {
          formData.append('profile_img[]', image)
        })

        try {
          const res = (await this.$http.post(this.$APIURI + 'users/profile_img', formData, {
            headers: {
              'Content-type': 'application/json; multipart/form-data;'
            }
          })).data

          if (res.state === 1) {
            this.$store.commit('UserStoreInfor', res.query)
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
