<template>
  <div>
    <div class="dg-filter-search-list">
      <h3 class="in-label">
        해시태그
      </h3>
      <div class="in-section">
        <span class="_hash">
          <input
            type="text"
            class="init-input"
            v-model="inputHash"
            @input="Validation()"
            @keyup.enter="InputHashTag()"
          ></span>
        <input
          type="button"
          class="square-btn-outline"
          value="추가"
          @click="InputHashTag()"
        >
      </div>
    </div>

    <div class="dg-filter-search-list dg-filter-search-list--refresh">
      <span
        class="in-refresh"
        @click="AllDeleteTag()"
      >
        <img
          src="/images/btn/btn_refresh.svg"
          alt="refresh button"
        >
        <span>전체해제</span>
      </span>
      <div class="in-section">
        <span
          v-for="(hash, index) in HashTags"
          :key="'hash'+index"
          class="rounded-btn-outline"
        >
          <span># {{ hash }}</span>
          <img
            src="/images/btn/btn_cancle_tag.svg"
            alt="cancel tag"
            @click="DeleteTag(hash)"
          >
        </span>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        inputHash: '',
        dataHash: this.hashTag
      }
    },
    props: {
      hashTag: {
        type: Array,
        default: () => {
          return []
        },
        required: true
      }
    },
    computed: {
      HashTags () {
        return this.dataHash
      }
    },
    methods: {
      InputHashTag () {
        const idx = this.dataHash.indexOf(this.inputHash)
        if (idx <= -1) this.dataHash.push(this.inputHash)
        console.log(this.dataHash)
        this.inputHash = ''
        this.$emit('input', this.dataHash)
        this.$emit('search-submit')
      },
      DeleteTag (tag) {
        const idx = this.dataHash.indexOf(tag)
        if (idx > -1) this.dataHash.splice(idx, 1)

        this.$emit('input', this.dataHash)
        this.$emit('search-submit')
      },
      AllDeleteTag () {
        this.dataHash = []
        this.$emit('input', this.dataHash)
        this.$emit('search-submit')
      },
      Validation () {
        if (this.inputHash.indexOf(',') !== -1 || this.inputHash.indexOf(' ') !== -1) {
          this.inputHash = this.inputHash.replace(',', '')
          this.inputHash = this.inputHash.replace(' ', '')
          this.InputHashTag()
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
