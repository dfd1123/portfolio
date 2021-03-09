<template>
  <div
    id="dg-cs-notice-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <div class="l-con-area">
        <!-- 1) 게시글 -->
        <div class="l-con-article">
          <section class="_cs_wrap _post_wrap">
            <div class="_page_title_wrap">
              <h2>
                공지사항
                <button
                  type="button"
                  class="_back_btn"
                  @click="$router.go(-1)"
                >
                  뒤로가기
                </button>
              </h2>
            </div>
            <div class="_board _post_board">
              <!-- 보드 레이아웃 -->
              <div class="_board_layout">
                <!-- cs content -->
                <div class="_cs_content _post_content">
                  <div class="_board_title_label">
                    <span class="_title">{{ notice.title || '-' }}</span>
                    <span class="_number">{{ notice.id }}</span>
                    <span class="_date">{{ $moment(notice.created_at).format('YYYY-MM-DD') }}</span>
                  </div>
                  <div class="_board_content">
                    <div class="_desc">
                      <p
                        class="_desc_01"
                        v-html="notice.body"
                      >
                      </p>
                    </div>
                    <article
                      v-if="ConvertImage(notice.files).length > 0"
                      class="_attached_file_wrap"
                    >
                      <h4>첨부파일 다운로드</h4>
                      <button
                        v-for="(file, index) in ConvertImage(notice.files)"
                        :key="'file'+index"
                        class="_attached_file"
                        @click="Download(file)"
                      >
                        첨부파일명.jpg
                      </button>
                    </article>
                  </div>
                </div>
                <!-- cs content E -->
              </div>
              <!-- 보드 레이아웃 E -->
            </div>
          </section>
        </div>
        <!-- 1) 게시글 E -->
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        notice: {}
      }
    },
    props: {
      id: {
        type: String,
        required: true
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.NoticeLoad()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async NoticeLoad () {
        const params = {
          id: Number(this.id)
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'notice/show', { params })).data

          if (res.state === 1) {
            this.notice = res.query.notice
          } else {
            console.log('공지사항 정보 가져오기 실패!')
          }
        } catch (e) {
          console.log(e)
        }
      },
      Download (file) {
        this.NativePopup(this.storageUrl + file)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
