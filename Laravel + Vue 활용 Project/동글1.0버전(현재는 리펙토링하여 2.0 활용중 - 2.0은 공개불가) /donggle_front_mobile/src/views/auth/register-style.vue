<template>
  <div id="regiter_content">
    <RegHeader
      page-name="스타일 선택"
      :style-choice="true"
      :step="3"
      :uniq-hash-tags="uniqHashTags"
      @all-cancel="AllCheckCancel"
      @one-cancel="OneCancel"
    />
    <section
      id="step3"
      class="step_common"
    >
      <h2>
        선호하는 스타일을 선택해주세요.<br>
        고객님의 스타일에 맞춰,<br>
        상품을 추천해드립니다.
      </h2>
      <div class="dg-reg-style_wrapper clear_both">
        <StyleSelectItem
          v-for="(styleList, index) in styleLists"
          :key="index"
          :style-list="styleList"
          :style-ids="styleIds"
          @select-event="StyleSelect"
        />
      </div>
      <div class="btn_write_complete_wrap">
        <button
          type="button"
          class="dg-btn_gra btn_write_complete dg-reg-style_btn"
          @click="Submit()"
        >
          선택완료
        </button>
      </div>
    </section>
  </div>
</template>

<script>
  import RegHeader from '@/components/common/header/reg-header.vue'
  import StyleSelectItem from '@/components/style-select/style-select-item.vue'

  export default {
    data: function () {
      return {
        id: this.$route.query.id || this.$store.state.user.id,
        name: this.$route.query.name || this.$store.state.user.name,
        styleLists: [],
        hashTags: [],
        uniqHashTags: [],
        styleIds: []
      }
    },
    components: {
      RegHeader,
      StyleSelectItem
    },
    created () {
      this.fetchData()
    },
    methods: {
      async fetchData () {
        this.styleLists = (await this.$http.get(this.$APIURI + 'style/list')).data.query
      },
      StyleSelect (hashTag, styleId) {
        if (this.styleIds.includes(styleId)) {
          console.log('awd')
          for (let i = 0; i < hashTag.length; i++) {
            for (let j = 0; j < this.hashTags.length; j++) {
              if (hashTag[i] === this.hashTags[j]) {
                this.hashTags.splice(j, 1)
                j = this.hashTags.length
              }
            }
          }

          const idx = this.styleIds.indexOf(styleId)
          if (idx > -1) this.styleIds.splice(idx, 1)
        } else {
          this.hashTags = this.hashTags.concat(hashTag)
          this.styleIds.push(styleId)
        }

        this.uniqHashTags = [...new Set(this.hashTags)]
      },
      AllCheckCancel () {
        this.uniqHashTags = []
        this.styleIds = []
      },
      OneCancel (tag) {
        for (let i = 0; i < this.hashTags.length; i++) {
          if (tag === this.hashTags[i]) {
            this.hashTags.splice(i, 1)
          }
        }

        this.styleLists.forEach(style => {
          if (this.styleIds.includes(style.style_id)) {
            let status = false
            const hashTag = JSON.parse(style.style_tag)
            for (let i = 0; i < hashTag.length; i++) {
              for (let j = 0; j < this.hashTags.length; j++) {
                if (hashTag[i] === this.hashTags[j]) {
                  status = true
                }
              }
            }

            if (!status) {
              const idx = this.styleIds.indexOf(style.style_id)
              if (idx > -1) this.styleIds.splice(idx, 1)
            }
          }
        })

        this.uniqHashTags = [...new Set(this.hashTags)]
      },
      async Submit () {
        if (this.uniqHashTags.length === 0) {
          this.InfoAlert('스타일을 한개 이상 골라주세요!')
          return false
        }

        if (this.styleIds.length > 3) {
          this.InfoAlert('스타일은 최대 3개까지 선택이 가능합니다.')
          return false
        }

        const params = {
          id: this.id,
          uniqHashTags: this.uniqHashTags
        }
        const res = (await this.$http.put(this.$APIURI + 'users/style', params)).data

        if (res.state === 1) {
          await this.SuccessAlert('회원가입을 성공적으로 완료 하였습니다!\n로그인 후 이용해주세요.')
          this.$router.push('/')
        } else {
          alert(res.msg)
        }
        // this.$router.push({ name: 'register-complete', query: { id: this.id, name: this.name } })
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
