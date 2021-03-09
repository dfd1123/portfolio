<template>
  <div class="dg-reg-style_wrap">
    <input
      type="checkbox"
      :id="'style'+styleList.style_id"
      :checked="selectIds.includes(styleList.style_id)"
      @change="CheckedEvent(JSON.parse(styleList.style_tag), styleList.style_id)"
      class="dg-reg-style-input display_none"
    >
    <label
      :for="'style'+styleList.style_id"
      class="label-box"
    >
      <div class="dg-reg-style dg-reg-style_01">
        <img
          alt="예시"
          class="dg-reg-style-img"
          :src="storageUrl+ConvertImage(styleList.style_img)[0]"
        >
        <div class="cover_black"></div>
        <div class="dg-style-logo_badge"></div>
      </div>
      <div
        class="_hashtag_wrap"
        style="bottom: 0;background: transparent;"
      >
        <h2 style="margin-bottom: 5px;text-align: center;color: #ffffff;font-weight: 600;font-size: 1.125em;-webkit-user-select: none;-moz-user-select: none;-o-user-select: none;-ms-user-select: none;user-select: none;">{{ styleList.ca_name }}
        </h2>
        <span
          v-for="(tag, index) in JSON.parse(styleList.style_tag)"
          :key="index"
          class="round-hashtag"
          style="margin: 0 1px;background-color: rgba(247, 247, 247, 0.4);"
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
  .dg-reg-style-input:checked ~ .label-box .cover_black {
    background-color: rgba(0, 0, 0, 0.7);
  }
  .dg-reg-style-input:checked ~ .label-box .logo_badge {
    display: block;
  }
</style>
