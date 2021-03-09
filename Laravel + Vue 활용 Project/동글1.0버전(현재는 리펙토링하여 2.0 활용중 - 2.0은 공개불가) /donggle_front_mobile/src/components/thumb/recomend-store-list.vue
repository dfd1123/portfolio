<template>
  <div class="recommend-store">
    <router-link :to="'/store/'+storeList.store_id">
      <div class="_store_delegate_img_box">
        <img
          v-if="ConvertImage(storeList.image).length > 0"
          :src="storageUrl+ConvertImage(storeList.image)[0]"
          :alt="storeList.brandname + '대표 이미지'"
        >
        <img
          v-else
          src="/images/img/thumbnail.png"
          alt="스토어 이미지 없음(동글)"
        >
      </div>
      <div class="_store_profill">
        <div class="symbol-circle">
          <img
            v-if="ConvertImage(storeList.profile_img).length > 0"
            :src="storageUrl+ConvertImage(storeList.profile_img)[0]"
            :alt="storeList.brandname + '프로필 이미지'"
            class="_imgtag"
          >
          <img
            v-else
            src="/images/img/basic_profile/01.png"
            alt="스토어 프로필 이미지 없음(동글)"
            class="_imgtag"
          >
        </div>
        <h3>{{ storeList.brandname }}</h3>
        <div
          class="_desc"
          v-html="storeList.intro"
        >
        </div>
        <div class="_store_profill_btn_wrap _btn_wrap clear_both">
          <button
            class="rounded-btn-outline icon_btn icon_btn_left"
            v-ripple
            @click="StoreLike"
          >
            <img
              v-if="storeList.like_yn === 1"
              src="/images/icon/icon_fav-on.svg"
              alt="북마크"
            >
            <img
              v-else
              src="/images/icon/icon_fav-off.svg"
              alt="북마크"
            >
            <span>즐겨찾기</span>
          </button>
          <button
            class="rounded-btn-outline icon_btn icon_btn_right"
            v-ripple
            @click="$router.push('/store/'+storeList.store_id+'/qna')"
          >
            <img
              src="/images/icon/icon_contact.svg"
              alt="문의"
            >
            <span>스토어문의</span>
          </button>
        </div>
      </div>
    </router-link>
  </div>
</template>

<script>
  export default {
    props: {
      storeList: {
        type: Object,
        required: true
      }
    },
    computed: {
      StoreImage () {
        if (this.storeList.image !== null) {
          return JSON.parse(this.storeList.image)
        }

        return []
      },
      StoreProfileImage () {
        if (this.storeList.profile_img !== null) {
          return JSON.parse(this.storeList.profile_img)
        }

        return []
      }
    },
    methods: {
      async StoreLike () {
        const params = {
          store_id: this.storeList.store_id
        }

        if (this.storeList.like_yn === 0) {
          try {
            const res = (await this.$http.post(this.$APIURI + 'sellerlike', params)).data

            if (res.state === 1) {
              if (res.query) {
                this.storeList.like_yn = 1
                this.storeList.likes += 1
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
                this.storeList.like_yn = 0
                this.storeList.likes -= 1
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
._store_profill_btn_wrap{
  &:after{
    clear: both;
    content: '';
    display: block;
  }
  .icon_btn_left{
    width: 50%;
    float: left;
    border-right: 0;
  }
  .icon_btn_right{
    width: 50%;
    float: right;
  }
}
</style>
