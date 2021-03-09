<template>
  <div class="dg-reg-style">
    <input
      type="checkbox"
      :id="'style'+styleList.style_id"
      :checked="selectIds.includes(styleList.style_id)"
      @change="CheckedEvent(JSON.parse(styleList.style_tag), styleList.style_id)"
    >
    <label
      :for="'style'+styleList.style_id"
      class="label-box"
    >
      <img
        alt="예시"
        class="dg-reg-style-img"
        :src="storageUrl + ConvertImage(styleList.style_img)[0]"
      >
      <div class="cover_black"></div>
      <div class="dg-style-logo_badge"></div>
      <div class="dg-reg-style-desc">
        <p class="dg-reg-style_title">
          {{ styleList.ca_name }}
        </p>
        <span
          v-for="(tag, index) in JSON.parse(styleList.style_tag)"
          :key="index"
          class="round-hashtag"
        >#{{ tag }}</span>
      </div>
    </label>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        isChecked: false,
        hashTags: [],
        uniqueHashTags: [],
        selectIds: this.styleIds
      }
    },
    props: {
      styleList: {
        type: Object,
        required: true
      },
      styleIds: {
        type: Array,
        default: () => {
          return []
        }
      }
    },
    watch: {
      styleIds () {
        this.selectIds = this.styleIds
      }
    },
    methods: {
      CheckedEvent (hashTags, id) {
        this.$emit('select-event', hashTags, id)
      },
      ArrayDeleteVal (array) {
        for (let i = 0; i < array.length; ++i) {
          for (let j = 0; j < this.hashTags.length; ++j) {
            if (array[i] === this.hashTags[j]) {
              this.hashTags.splice(j--, 1)
              break
            }
          }
        }
      },
      AllCancel () {
        this.uniqueHashTags = []
        this.hashTags = []
        this.isChecked = false
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
