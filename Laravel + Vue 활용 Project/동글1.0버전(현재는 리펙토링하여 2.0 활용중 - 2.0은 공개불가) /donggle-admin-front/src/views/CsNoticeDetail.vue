<template>
  <div id="app">
    <layout-header :title="null" title-mobile="공지사항" class="detail-mobile-hd">
      <template v-slot:after-button-left-mobile>
        <div class="in-page-name">
          <h3 class="in-main">
            <a href="#" class="icon-back-btn" @click.prevent="$router.push('/cs-notice-main')">뒤로가기</a>
          </h3>
        </div>
      </template>
    </layout-header>

    <!-- contents -->
    <div id="admin-container" class="cs-container">
      <div class="wrapper">
        <!-- page content -->
        <div id="page-cs-notice-detail-wrap" class="cs-notice-detail-wrap">
          <div class="grid-line-group clearfix">
            <cs-side-menu class="show-pc" />

            <!-- cs content -->
            <div class="panel-default-container">
              <section class="_cs_con_wrap">
                <!-- 공지사항 게시글 -->
                <div class="panel-default">
                  <template v-if="notice !== null">
                    <!-- 제목 -->
                    <div>
                      <h3 class="cs-notice-detail-title show-mobile">
                        공지사항
                        <a
                          href="#"
                          class="icon-back-btn"
                          @click.prevent="$router.push('/cs-notice-main')"
                        >뒤로가기</a>
                      </h3>
                    </div>
                    <h3 class="_title_wrap clearfix">
                      <a
                        href="#"
                        class="icon-back-btn show-pc"
                        @click.prevent="$router.push('/cs-notice-main')"
                      >뒤로가기</a>
                      <span class="_title">{{notice.title}}</span>
                      <div class="_title_info">
                        <span class="_number">{{String(notice.id).padStart(3, '0')}}</span>
                        <span class="_date">{{notice.created_at}}</span>
                      </div>
                    </h3>
                    <!-- 제목 E -->
                    <div class="_content_wrap">
                      <p v-html="notice.body"></p>

                      <!-- 첨부파일 영역 -->
                      <!-- 첨부파일 개수에 따라 ._content_wrap 의 padding-bottom이 변함 -->
                      <div
                        v-if="notice.file1 && JSON.parse(notice.file1).length > 0"
                        class="file_down"
                      >
                        <h4>첨부파일 다운로드</h4>
                        <!-- 첨부파일 -->
                        <!-- TODO 해당 경로로 다운로드 되는지 확인 필요 -->
                        <label
                          v-for="(file, index) in JSON.parse(notice.file1)"
                          :key="index"
                          class="file-download-btn"
                          @click="window.location.assign(storagePath(file))"
                        >
                          <img class="in-icon" src="/images/icon/icon_folder.svg" alt="folder icon" />
                          <span class="in-filename">{{file.split(/[\/]+/).pop()}}</span>
                        </label>
                        <!-- 첨부파일 E -->
                      </div>
                      <!-- 첨부파일 영역 E -->
                    </div>
                    <!-- 내용 -->
                    <a
                      href="#"
                      class="square-lg-btn type-shadow btn-gradient _list-btn show-pc"
                      @click.prevent="$router.push('/cs-notice-main')"
                    >목록</a>
                  </template>
                </div>
                <!-- 공지사항 게시글 E -->
              </section>
            </div>
            <!-- cs content E -->
          </div>
        </div>
        <!-- page content E -->
      </div>
      <layout-footer class="show-pc" />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
export default {
  name: 'CsNoticeDetail',
  data () {
    return {
      noticeId: this._get(this, '$route.params.id', null),
      notice: null
    }
  },
  async created () {
    await this.fetchData()
  },
  methods: {
    async fetchData () {
      await this.searchClick()
    },
    async searchClick () {
      try {
        this.loading(true)

        const params = {
          id: this.noticeId
        }

        const data = await this.$axios
          .get('/api/store/notice/show', {
            params
          })
          .then(this.normalOrError)
          .then(this.resultOrError)

        this.notice = data.notice
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    }
  }
}
</script>
