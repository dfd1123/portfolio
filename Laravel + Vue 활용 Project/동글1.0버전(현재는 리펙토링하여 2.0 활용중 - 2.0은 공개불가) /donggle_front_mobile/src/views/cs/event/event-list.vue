<template>
  <div
    id="dg-cs-event-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <!-- 활성화된 메뉴 _list 에 active 추가 -->
      <article class="in-nav-section">
        <ul>
          <li class="_list">
            <router-link to="/notices">
              공지사항
            </router-link>
          </li>
          <li class="_list">
            <router-link to="/faqs">
              자주묻는 질문
            </router-link>
          </li>
          <li class="_list">
            <router-link to="/qnas">
              1:1 문의
            </router-link>
          </li>
          <li class="_list active">
            <router-link to="/events">
              이벤트
            </router-link>
          </li>
        </ul>
      </article>

      <div class="l-con-area">
        <!--
          등록된 글이 없을 때: ._event_wrap ._empty 에 .display_none 삭제,
                              ._event_wrap ._fill 에 .display_none
          ._board_pager : pagination 자리
         -->
        <!-- 1) 이벤트  -->
        <div class="l-con-article">
          <section class="_cs_wrap _event_wrap">
            <div class="_page_title_wrap">
              <h2>
                고객센터
                <button
                  type="button"
                  class="_back_btn"
                  @click="$router.go(-1)"
                >
                  뒤로가기
                </button>
              </h2>
            </div>
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
                    <span class="_date">{{ $moment(event.start_date).format('YYYY-MM-DD') }} ~ {{ $moment(event.end_date).format('YYYY-MM-DD') }}</span>
                  </div>
                  <router-link
                    :to="'/events/'+event.id"
                    class="_link"
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
                    src="/images/mobile/icon/empty_board_m.svg"
                    alt="등록된 이벤트가 없습니다."
                  >
                </div>
                <div class="_text">
                  등록된 이벤트가 없습니다.
                </div>
              </div>
              <!-- 등록된 이벤트가 없을 때 E -->
              <div
                class="loading_wrap"
                v-show="bottomLoadingShow"
              >
                <Loading />
              </div>
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
  import Loading from '@/components/common/loading/loading.vue'
  export default {
    components: {
      Loading
    },
    data: function () {
      return {
        allCount: 0,
        pageSize: 20,
        offset: 0,
        events: [],
        bottomLoadingShow: false
      }
    },
    async created () {
      this.$store.commit('ProgressShow')

      const res = await this.EventLoad()
      this.events = res.events
      this.allCount = res.count
      this.offset += this.events.length

      this.$store.commit('ProgressHide')
    },
    methods: {
      async EventLoad () {
        const params = {
          page_size: this.pageSize,
          offset: this.offset
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'event/list', { params })).data
          if (res.state === 1) {
            this.$router.replace({ name: 'event-list', query: params })
            return res.query
          } else {
            console.log('이벤트 리스트 가져오기 실패')
          }
        } catch (e) {
          console.log(e)
        }
      },
      async EventMoreLoad () {
        const res = await this.EventLoad()
        this.events = this.events.concat(res.events)
        this.offset += res.events.length
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.itemCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          await this.EventMoreLoad()
          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
