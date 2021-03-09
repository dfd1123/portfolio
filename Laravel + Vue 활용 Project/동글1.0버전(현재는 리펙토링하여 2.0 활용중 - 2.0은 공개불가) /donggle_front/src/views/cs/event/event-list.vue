<template>
  <div
    id="dg-cs-event-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <!-- 활성화된 메뉴 _list 에 active 추가 -->
      <CsGnb />

      <div class="l-con-area">
        <!--
          등록된 글이 없을 때: ._event_wrap ._empty 에 .display_none 삭제,
                              ._event_wrap ._fill 에 .display_none
         -->
        <!-- 1) 이벤트  -->
        <div class="l-con-article">
          <section class="_cs_wrap _event_wrap">
            <h3>이벤트</h3>
            <!-- 등록된 이벤트가 있을 때 -->
            <div class="_board">
              <ul
                v-if="events.length > 0"
                class="_board_layout _cs_layout _fill"
              >
                <li
                  v-for="event in events"
                  :key="'event'+event.id"
                  class="_cs_content"
                >
                  <div class="_board_title_label">
                    <span class="_number">{{ event.id }}</span>
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
                    >종료</span>
                    <span class="_title">{{ event.title }}</span>
                    <span class="_term">{{ $moment(event.start_date).format('YYYY-MM-DD') }} ~ {{ $moment(event.end_date).format('YYYY-MM-DD') }}</span>
                  </div>
                  <router-link
                    :to="'/events/'+event.id+'?page='+currentPage"
                    class="link"
                  />
                </li>
              </ul>
              <!-- 등록된 이벤트가 없을 때 -->
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
              <!-- 등록된 이벤트가 없을 때 E -->
              <!-- 페이지 -->
              <Pagination
                :items="events"
                :item-cnt="allCount"
                :page-size="pageSize"
                :initial-page="currentPage"
                ref="eventpagination"
                @changePage="OnChangePage"
              />
            </div>
            <!-- 등록된 이벤트가 있을 때 E -->
          </section>
        </div>
        <!-- 1) 이벤트 E -->
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
        pageSize: 10,
        currentPage: Number(this.$route.query.page) || 1,
        events: []
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      await this.EventLoad()

      if (this.allCount > this.pageSize) {
        this.$refs.eventpagination.SetPage(this.currentPage, false)
      }

      this.$store.commit('ProgressHide')
    },
    methods: {
      async EventLoad () {
        const params = {
          page_size: this.pageSize,
          page: this.currentPage
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'event/list', { params })).data
          if (res.state === 1) {
            this.events = res.query.events
            this.currentPage = Number(res.query.page)
            this.allCount = res.query.count
          } else {
            console.log('이벤트 리스트 가져오기 실패')
          }
        } catch (e) {
          console.log(e)
        }
      },
      OnChangePage (currentPage) {
        this.currentPage = currentPage
        this.EventLoad()
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
