<template>
  <div
    id="dg-cs-notice-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <!-- 활성화된 메뉴 _list 에 active 추가 -->
      <CsGnb />

      <div class="l-con-area">
        <!--
    _fill     공지사항이 있을 때
    _empty    공지사항이 없을 때  ._cs_wrap ._empty에 .display_none삭제

    ._cs_wrap ._empty.display_none
   -->
        <!-- 1) 공지사항 -->
        <div class="l-con-article">
          <section class="_cs_wrap _notice_wrap">
            <h3>공지사항</h3>
            <div class="_board">
              <!-- 등록된 공지사항이 있을 때 -->
              <ul
                v-if="noticeLists.length > 0"
                class="_board_layout _cs_layout _fill"
              >
                <li
                  v-for="(noticeList, index) in noticeLists"
                  :key="'notice' + index"
                  class="_cs_content"
                >
                  <div class="_board_title_label">
                    <span class="_number">{{ noticeList.id }}</span>
                    <span class="_title">{{ noticeList.title }}</span>
                    <span class="_date">{{ $moment(noticeList.created_at).format('YYYY-MM-DD') }}</span>
                  </div>
                  <router-link
                    :to="'/notices/'+noticeList.id+'?page='+currentPage"
                    class="link"
                  />
                </li>
              </ul>
              <!-- 등록된 공지사항이 없을 때 -->
              <div
                v-else
                class="_empty"
              >
                <div class="_img_box">
                  <img
                    src="/images/icon/empty_board.svg"
                    alt="등록된 게시글이 없습니다."
                  >
                </div>
                <div class="_text">
                  등록된 게시글이 없습니다.
                </div>
              </div>
              <!-- 등록된 공지사항이 없을 때 E -->
              <!-- 페이지 -->
              <!--
                숫자가 3자리수가 되면
                ._notice_wrap ._pager > a 에 padding:0px
               -->
              <Pagination
                :items="noticeLists"
                :item-cnt="allCount"
                :page-size="pageSize"
                :initial-page="currentPage"
                ref="pagination"
                @changePage="OnChangePage"
              />
              <!-- 페이지 E -->
            </div>
            <!-- 등록된 공지사항이 있을 때 E -->
          </section>
        </div>
        <!-- 1) 공지사항 E -->
      </div>
    </div>
  </div>
</template>

<script>
  import CsGnb from '@/components/cs/gnb.vue'
  import Pagination from '@/components/pagination/pagination.vue'

  export default {
    components: {
      CsGnb,
      Pagination
    },
    data: function () {
      return {
        allCount: 0,
        pageSize: 15,
        currentPage: Number(this.$route.query.page) || 1,
        noticeLists: []
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.NoticeLoad()
      this.$refs.pagination.SetPage(this.currentPage, false)

      this.$store.commit('ProgressHide')
    },
    methods: {
      async NoticeLoad () {
        const params = {
          page_size: this.pageSize,
          page: this.currentPage
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'notice/list', { params })).data
          if (res.state === 1) {
            this.noticeLists = res.query.notices
            this.currentPage = Number(res.query.page)
            this.allCount = res.query.count
          } else {
            console.log('공지사항 리스트 가져오기 실패')
          }
        } catch (e) {
          console.log(e)
        }
      },
      OnChangePage (currentPage) {
        this.currentPage = currentPage
        this.NoticeLoad()
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
