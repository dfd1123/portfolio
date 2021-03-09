<template>
  <!-- 해시태그 팝업 -->
  <div :class="['_hashlist-con', {'active':hashtagPopupOpen}]">
    <div
      class="_hashtag_bg"
      @click="$emit('popup-close')"
    ></div>
    <div class="_hashtag_wrap">
      <h3>해시태그</h3>
      <div class="hash-detail_title clear_both">
        <div class="hash-title_img_box">
          <img
            v-if="ConvertImage(showItem.images).length === 0"
            src="/images/img/thumbnail.png"
            :alt="showItem.title"
          >
          <img
            v-else
            :src="storageUrl+ConvertImage(showItem.images)[0]"
            :alt="showItem.title"
          >
        </div>
        <h4>
          <b>{{ showItem.title || '-' }}</b><br>
          <span>{{ showItem.company_name || '-' }} </span>
        </h4>
      </div>
      <div class="pink_tag_wrap">
        <button
          v-for="(hashTag, index) in HashTags"
          :key="index"
          class="rounded-list rounded-list-s"
          type="button"
          @click="HashTagSubmit(hashTag)"
        >
          <span class="_hashtag_txt">{{ hashTag }}</span>
        </button>
      </div>
      <div
        class="_view_more _hashtag_close"
        v-ripple
      >
        <button
          type="button"
          @click="$emit('popup-close')"
        >
          닫기
        </button>
      </div>
    </div>
  </div>
  <!-- hashtag popup E -->
</template>

<script>
  export default {
    props: {
      hashtagPopupOpen: {
        type: Boolean,
        default: false
      },
      showItem: {
        type: Object,
        default: () => {
          return {}
        }
      },
      url: {
        type: String,
        default: '/total/search'
      },
      searchYn: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      HashTags () {
        if (this.showItem.hash_tag) {
          return this.showItem.hash_tag.split(',')
        }

        return []
      }
    },
    methods: {
      HashTagSubmit (hashTag) {
        if (this.$route.name === 'total-search') {
          this.HashTagAdd(hashTag)
        } else {
          this.$router.push(this.url + '?hash_tag=' + hashTag)
        }
      },
      HashTagAdd (hashTag) {
        console.log('awdawd')
        this.$emit('input-hashtag', hashTag)
        this.$emit('popup-close')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
