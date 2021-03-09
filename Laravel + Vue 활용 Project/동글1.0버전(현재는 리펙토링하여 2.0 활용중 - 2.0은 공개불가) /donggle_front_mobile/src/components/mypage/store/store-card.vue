<template>
  <div class="dg-favorite-store-card clear_both">
    <div class="symbol-circle">
      <img
        v-if="ConvertImage(store.profile_img).length > 0"
        :src="storageUrl+ConvertImage(store.profile_img)[0]"
        alt="brand logo"
        class="_imgtag"
      >
      <img
        v-else
        src="/images/img/thumbnail.png"
        alt="brand logo"
        class="_imgtag"
      >
    </div>
    <div class="like_store_decswrap">
      <h3>
        <router-link
          :to="'/store/'+store.store_id"
          class="_name"
        >
          {{ store.brandname }}
        </router-link>
      </h3>
      <ul class="in-detail_middle clear_both">
        <li class="_desc _score">
          <div :class="'in-star-group star-0'+store.rating.toFixed(0)">
            <img
              src="/images/icon/icon_review.svg"
              alt="star icon"
              class="_icon"
            >
            <span class="_rate"></span>
          </div>
        </li>
        <li class="_desc _bookmark">
          <button
            type="button"
            class="_bookmark_btn"
            @click="StoreLike"
          >
            <img
              class="_icon"
              src="/images/icon/icon_fav-on.svg"
              alt="favorite icon"
            >
            <span v-if="store.like_yn === 1">즐겨찾기 삭제</span>
            <span v-else>즐겨찾기</span>
          </button>
        </li>
        <li class="_desc _question">
          <router-link :to="'/store/'+store.store_id+'/qna'">
            <img
              class="_icon"
              src="/images/icon/icon_contact.svg"
              alt="contact icon"
            ><span>스토어 문의</span>
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      store: {
        type: Object,
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
        this.$emit('reload')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
