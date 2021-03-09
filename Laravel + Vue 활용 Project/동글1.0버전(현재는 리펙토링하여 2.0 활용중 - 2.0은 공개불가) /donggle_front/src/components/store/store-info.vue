<template>
  <div class="dg-store-profile-card">
    <!-- [1] 로고 -->
    <span class="symbol-circle">
      <img
        v-if="ConvertImage(store.profile_img).length > 0"
        :src="storageUrl + ConvertImage(store.profile_img)[0]"
        :alt="store.brandname + ' 동글 프로필'"
        class="_imgtag"
      >
      <img
        v-else
        src="/images/img/basic_profile/01.png"
        alt="brand logo"
        class="_imgtag"
      >
    </span>
    <!-- [1] E -->

    <!-- [2] 스토어 기본소개 -->
    <dl class="in-detail_top">
      <dt class="_brandname">
        <span class="_name">{{ store.brandname }}</span>
        <span class="_stars display_none">
          <img
            src="/images/icon/icon_store_review.svg"
            alt="star icon"
          ><span class="_rate">{{ (store.rating || 0).toFixed(1) }}</span>
        </span>
      </dt>
      <dd class="_brandintro">
        <p v-html="store.intro">
        </p>
      </dd>
    </dl>
    <!-- [2] E -->

    <!-- [3] 버튼 -->
    <ul class="in-detail_middle">
      <!-- ※ 즐겨찾기 추가했을 시, active 적용한 상태 -->
      <li
        :class="{'active': store.like_yn === 1}"
        @click="StoreLike"
      >
        <img
          src="/images/icon/icon_fav-off.svg"
          alt="favorite icon"
          class="icon icon--off"
        >
        <img
          src="/images/icon/icon_fav-on.svg"
          alt="favorite icon"
          class="icon icon--on"
        >
        <span>즐겨찾기</span>
      </li>
      <!-- ※ 즐겨찾기 추가했을 시, active 적용한 상태 END -->
      <li
        class="_btn"
        @click="$router.push('/store/'+store.store_id+'/qna')"
      >
        <img
          src="/images/icon/icon_contact.svg"
          alt="contact icon"
        >
        <span>스토어 문의</span>
      </li>
      <li
        class="_btn"
        v-clipboard="copyData"
        @success="SuccessAlert('url을 복사하였습니다.')"
        @error="ErrorAlert('url을 복사에 실패하였습니다.')"
      >
        <img
          src="/images/icon/icon_share.svg"
          alt="contact icon"
        ><span>공유하기</span>
      </li>
    </ul>
    <!-- [3] E -->

    <!-- [4] 즐찾 및 판매중상품 갯수 -->
    <ul class="_etc_info">
      <li class="_list">
        <span class="_label">즐겨찾기</span>
        <p class="_txt">
          {{ NumberFormat(store.likes || 0) }} 명
        </p>
      </li>
      <li class="_list">
        <span class="_label">판매중인 상품</span>
        <p class="_txt">
          {{ NumberFormat(itemCnt || 0) }} 개
        </p>
      </li>
    </ul>
    <!-- [4] E -->
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        copyData: window.location.href
      }
    },
    props: {
      store: {
        type: Object,
        required: true
      },
      itemCnt: {
        type: Number,
        required: true
      }
    },
    methods: {
      async StoreLike () {
        const params = {
          store_id: this.store.store_id
        }

        if (this.store.like_yn === 0) {
          try {
            const res = (await this.$http.post(this.$APIURI + 'sellerlike', params)).data

            if (res.state === 1) {
              if (res.query) {
                this.store.like_yn = 1
                this.store.likes += 1
                this.SuccessAlert('즐겨찾기 업체로 등록되었습니다.')
              }
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        } else {
          params._method = 'delete'

          try {
            const res = (await this.$http.post(this.$APIURI + 'sellerlike/delete', params)).data

            if (res.state === 1) {
              if (res.query) {
                this.store.like_yn = 0
                this.store.likes -= 1
                this.SuccessAlert('즐겨찾기 업체에서 삭제되었습니다.')
              }
            } else {
              console.log(res.msg)
            }
          } catch (e) {
            console.log(e)
          }
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
