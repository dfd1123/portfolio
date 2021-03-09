<template>
  <section class="bast-store-wrap">
    <h2 class="dg_blind">
      {{ store.brandname }}
    </h2>
    <ul>
      <li>
        <div class="_store_delegate_img_box">
          <img
            v-if="ConvertImage(store.image).length > 0"
            :src="storageUrl+ConvertImage(store.image)[0]"
            :alt="store.brandname + '스토어 사진'"
          >
          <img
            v-else
            src="/images/img/thumbnail.png"
            alt="스토어 사진"
          >
        </div>
        <!-- 1. 스토어 소개 -->
        <!--
                  스토어 소개와 상품정렬은 스크롤시 상단에 고정됨
                  ._store_profill 과 .dg-list-lineup_wrap에 .scroll
                -->
        <div class="_store_profill">
          <div class="symbol-circle">
            <img
              v-if="ConvertImage(store.profile_img).length > 0"
              :src="storageUrl+ConvertImage(store.profile_img)[0]"
              :alt="store.brandname + ' 동글 프로필'"
              class="_imgtag"
            >
            <img
              v-else
              src="/images/img/basic_profile/01.png"
              alt="brand logo"
              class="_imgtag"
            >
          </div>
          <h3>{{ store.brandname }}</h3>
          <div
            class="_desc"
            v-html="store.intro"
          >
          </div>
          <div class="store_info_wrap clear_both">
            <div
              class="store_info store_info-01"
              style="width:50%;"
            >
              <span>판매중</span> <b>{{ NumberFormat(itemCnt || 0) }}개</b>
            </div>
            <button
              type="button"
              :class="['store_info store_info-03', {'active': store.like_yn === 1}]"
              style="width:50%;"
              @click="StoreLike"
            >
              <span>즐겨찾기</span> <b>{{ NumberFormat(store.likes || 0) }}명</b>
              <!-- 클릭시 store_info-03 에 .active -->
            </button>
          </div>
        </div>
        <!-- 1. 스토어 소개 E -->
      </li>
    </ul>
  </section>
</template>

<script>
  export default {
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
