<template>
  <div
    id="dg-cs-notice-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <div class="l-con-area">
        <!-- 1) 게시글 -->
        <div class="l-con-article">
          <section class="_cs_wrap _event_wrap _post_wrap">
            <div class="_page_title_wrap">
              <h2>
                이벤트
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
                    <span class="_title">{{ event.title || '-' }}</span>
                    <span class="_number">{{ event.id }}</span>
                    <span class="_date">{{ $moment(event.created_at).format('YYYY-MM-DD') }}</span>
                    <span
                      v-if="$moment().isBefore(event.start_date)"
                      class="_check"
                    >예정</span>
                    <span
                      v-else-if="$moment().isBetween(event.start_date, event.end_date)"
                      class="_check _ing"
                    >진행중</span>
                    <span
                      v-else-if="$moment().isAfter(event.end_date)"
                      class="_check"
                    >마감</span>
                  </div>
                  <div class="_board_content">
                    <div
                      class="_desc"
                      v-html="event.body"
                    >
                    </div>
                    <article
                      v-if="ConvertImage(event.file).length > 0"
                      class="_attached_file_wrap display_none"
                    >
                      <h4>첨부파일 다운로드</h4>
                      <button
                        v-for="(file, index) in ConvertImage(event.file)"
                        :key="'file'+index"
                        class="_attached_file"
                        @click="Download(file)"
                      >
                        {{ file.replace('/','') }}
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
        event: {}
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
      await this.EventLoad()
      this.$store.commit('ProgressHide')
    },
    methods: {
      async EventLoad () {
        const params = {
          id: Number(this.id)
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'event/show', { params })).data

          if (res.state === 1) {
            this.event = res.query.event
          } else {
            console.log('이벤트 정보 가져오기 실패!')
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
