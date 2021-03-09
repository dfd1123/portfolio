<template>
  <div id="app">
    <layout-header :title="null" title-mobile="공지사항" />

    <!-- contents -->
    <div id="admin-container" class="cs-container">
      <div class="wrapper">
        <!-- page content -->
        <div id="page-cs-notice-wrap" class="cs-content-wrap">
          <div class="grid-line-group clearfix">
            <cs-side-menu />

            <!-- cs content -->
            <div class="panel-default-container">
              <section class="_cs_con_wrap _cs_notice_wrap">
                <div class="panel-default-title type_02 show-pc">
                  <h3>공지사항</h3>
                </div>

                <template v-if="notices !== null">
                  <div v-if="notices.length > 0" class="panel-default">
                    <ul
                      v-for="notice in notices"
                      :key="notice.id"
                      class="_board_layout _with_pager"
                    >
                      <li class="_cs_content">
                        <div class="_title_label">
                          <span class="_title">{{notice.title}}</span>
                          <span class="_number">{{String(notice.id).padStart(3, '0')}}</span>
                          <span class="_date">{{notice.created_at}}</span>
                        </div>
                        <a
                          href="#"
                          class="_link"
                          @click.prevent="$router.push(`/cs-notice-detail/${notice.id}`)"
                        ></a>
                      </li>
                    </ul>
                    <button
                      v-show="pagination.count && pagination.hasNext"
                      type="button"
                      class="more-view-btn"
                      @click="showMoreButtonClick"
                    >
                      <img src="/images/icon/arrow_bt_navy.svg" alt="아래 화살표" class="in-arrow-icon" />
                      <span>더보기</span>
                    </button>
                  </div>
                  <div v-else class="nothing-history">
                    <div class="in-empty-icon show-pc">
                      <img src="/images/icon/empty_board.svg" alt="등록된 공지사항이 없습니다." />
                    </div>
                    <div class="in-empty-icon show-mobile">
                      <img src="/images/icon/empty_board_m.svg" alt="등록된 공지사항이 없습니다." />
                    </div>
                    <div class="in-empty-ment">등록된 공지사항이 없습니다.</div>
                  </div>
                </template>
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
  name: 'CsNoticeMain',
  data () {
    return {
      notices: null,
      pagination: {
        hasNext: true,
        limit: 10,
        page: 1,
        count: null
      }
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

        const data = await this.$axios
          .get('/api/store/notice/list', {
            params: {
              page_size: this.pagination.limit,
              page: this.pagination.page
            }
          })
          .then(this.normalOrError)
          .then(this.resultOrError)
          .then(this.updateCursor)

        this.notices = (this.notices || []).concat(data.notices)
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async showMoreButtonClick () {
      if (this.moveCursor()) {
        await this.searchClick()
      }
    }
  }
}
</script>
