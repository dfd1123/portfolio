<template>
  <div class="in-section _hash_wrap">
    <div class="_hash">
      <input
        type="text"
        class="init-input"
        v-model="inputHash"
        @input="Validation()"
        @keyup.enter="InputHashTag()"
      >
      <input
        type="button"
        class="square-btn"
        @click="InputHashTag()"
      >
    </div>
    <div>
      <!-- 인풋으로 추가된 해시태그들이 보이는 곳. 각 ._hashtag_plus 을 누르면 삭제됨 -->
      <p class="hash-list">
        목록
      </p>
      <button
        class="_hashtag_plus"
        v-for="(hash, index) in HashTags"
        :key="'hash'+index"
      >
        {{ hash }}
      </button>
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
    watch: {
      hashTag () {
        this.dataHash = this.hashTag
      }
    },
    methods: {
      InputHashTag () {
        const idx = this.dataHash.indexOf(this.inputHash)
        if (idx <= -1) this.dataHash.push(this.inputHash)
        this.inputHash = ''
        this.$emit('input', this.dataHash)
      },
      DeleteTag (tag) {
        const idx = this.dataHash.indexOf(tag)
        if (idx > -1) this.dataHash.splice(idx, 1)

        this.$emit('input', this.dataHash)
      },
      AllDeleteTag () {
        this.dataHash = []
        this.$emit('input', this.dataHash)
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
