<template>
  <div class="dg-main-recmnd-list">
    <div class="dg-main-recmnd-card">
      <figure class="dg-main-recmnd-thumbnail">
        <router-link
          :to="'/store/'+storeList.store_id"
          class="in-image"
          v-if="ConvertImage(storeList.image || []).length === 0"
          style="background-image: url(/images/img/thumbnail.png);"
        />
        <router-link
          :to="'/store/'+storeList.store_id"
          class="in-image"
          v-else
          :style="'background-image: url('+storageUrl+ConvertImage(storeList.image || [])[0]+');'"
        />
      </figure>
      <div class="dg-main-recmnd-detail">
        <router-link
          :to="'/store/'+storeList.store_id"
          class="in-logo clear_both"
        >
          <img
            v-if="ConvertImage(storeList.profile_img || []).length === 0"
            src="/images/img/thumbnail.png"
            alt="brand logo"
          >
          <img
            v-else
            :src="storageUrl+ConvertImage(storeList.profile_img || [])[0]"
            alt="brand logo"
          >
        </router-link>
        <dl class="in-detail_top">
          <dt class="_brandname">
            <router-link :to="'/store/'+storeList.store_id">
              {{ storeList.brandname }}
            </router-link>
          </dt>
          <dd class="_brandintro">
            <p v-html="storeList.intro">
            </p>
          </dd>
        </dl>
        <ul class="in-detail_middle">
          <li :class="['_b_favorite', {'active':storeList.like_yn === 1}]">
            <button
              type="button"
              @click="StoreLike"
            >
              <img
                v-if="storeList.like_yn === 1"
                src="/images/icon/icon_fav-on.svg"
                alt="favorite icon"
              >
              <img
                v-else
                src="/images/icon/icon_fav-off.svg"
                alt="favorite icon"
              >
              <span v-if="storeList.like_yn === 1">즐겨찾기 삭제</span>
              <span v-else>즐겨찾기</span>
            </button>
          </li>
          <li class="_b_inquiry">
            <router-link :to="'/store/'+storeList.store_id+'/qna'">
              <img
                src="/images/icon/icon_contact.svg"
                alt="contact icon"
              ><span>스토어
                문의</span>
            </router-link>
          </li>
        </ul>
        <router-link
          class="in-more"
          :to="'/store/'+storeList.store_id"
        >
          <span>스토어로 가기</span>
          <img
            src="/images/btn/btn_more.svg"
            alt="btn"
          >
        </router-link>
      </div>
    </div>
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
</style>
