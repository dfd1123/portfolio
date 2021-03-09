<template>
  <div class="dg-favorite-store-card">
    <figure
      v-if="ConvertImage(store.profile_img).length > 0"
      class="in-thumbnail"
      :style="'background-image: url('+storageUrl+ConvertImage(store.image)[0]+');'"
    >
      <span class="_logo clear_both">
        <img
          :src="storageUrl+ConvertImage(store.profile_img)[0]"
          alt="brand logo"
          class="_imgtag"
        >
      </span>
    </figure>
    <figure
      v-else
      class="in-thumbnail"
      style="background-image: url(/images/img/thumbnail.png);"
    >
      <span class="_logo clear_both">
        <img
          src="/images/img/basic_profile/01.png"
          alt="brand logo"
          class="_imgtag"
        >
      </span>
    </figure>
    <dl class="in-detail_top">
      <dt class="_brandname">
        <router-link :to="'/store/'+store.store_id">
          {{ store.brandname }}
        </router-link>
      </dt>
      <dd class="_brandintro">
        <p v-html="store.intro || '소개글 없음'">
        </p>
      </dd>
    </dl>
    <ul class="in-detail_middle">
      <li @click="StoreLike">
        <img
          src="/images/icon/icon_fav-on.svg"
          alt="favorite icon"
        >
        <span v-if="store.like_yn === 1">즐겨찾기 삭제</span>
        <span v-else>즐겨찾기</span>
      </li>
      <li>
        <router-link :to="'/store/'+store.store_id+'/qna'">
          <img
            src="/images/icon/icon_contact.svg"
            alt="contact icon"
          ><span>스토어 문의</span>
        </router-link>
      </li>
    </ul>
    <button
      type="button"
      class="rounded-btn-dark"
      @click="$router.push('/store/'+store.store_id)"
    >
      스토어로 가기
    </button>
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
  ._imgtag {
    width: 100%;
    vertical-align: middle;
    background-color: #fff;
  }
</style>
