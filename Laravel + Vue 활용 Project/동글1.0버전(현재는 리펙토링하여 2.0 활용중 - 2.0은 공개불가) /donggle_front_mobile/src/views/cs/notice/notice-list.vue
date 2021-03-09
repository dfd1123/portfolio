<template>
  <div
    id="dg-cs-notice-wrapper"
    class="dg-cs-wrapper"
  >
    <div class="l-cs-contents">
      <!-- 활성화된 메뉴 _list 에 active 추가 -->
      <article class="in-nav-section">
        <ul class="clear_both">
          <li class="_list active">
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
          <li class="_list">
            <router-link to="/events">
              이벤트
            </router-link>
          </li>
        </ul>
      </article>

      <div class="l-con-area">
        <!--
    _fill     공지사항이 있을 때
    _empty    공지사항이 없을 때  ._cs_wrap ._empty에 .display_none삭제

    ._cs_wrap ._empty.display_none

    ._board_pager : pagination 자리
   -->
        <!-- 1) 공지사항 -->
        <div class="l-con-article">
          <section class="_cs_wrap _notice_wrap ">
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
                    <span class="_title">{{ noticeList.title }}</span>
                    <span class="_number">{{ noticeList.id }}</span>
                    <span class="_date">{{ $moment(noticeList.created_at).format('YYYY-MM-DD') }}</span>
                  </div>
                  <router-link
                    :to="'/notices/'+noticeList.id"
                    class="_link"
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
                    src="/images/mobile/icon/empty_board_m.svg"
                    alt="등록된 공지사항이 없습니다."
                  >
                </div>
                <div class="_text">
                  등록된 공지사항이 없습니다.
                </div>
              </div>
              <!-- 등록된 공지사항이 없을 때 E -->
              <div
                class="loading_wrap"
                v-show="bottomLoadingShow"
              >
                <Loading />
              </div>
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
  import Loading from '@/components/common/loading/loading.vue'

  export default {
    components: {
      Loading
    },
    data: function () {
      return {
        allCount: 0,
        pageSize: 30,
        offset: 0,
        noticeLists: [],
        bottomLoadingShow: false
      }
    },
    async created () {
      this.$store.commit('ProgressShow')
      const res = await this.NoticeLoad()
      this.noticeLists = res.notices
      this.offset += res.notices.length
      this.allCount = res.count

      this.$store.commit('ProgressHide')
    },
    methods: {
      async NoticeLoad () {
        const params = {
          page_size: this.pageSize,
          offset: this.offset
        }

        try {
          const res = (await this.$http.get(this.$APIURI + 'notice/list', { params })).data
          if (res.state === 1) {
            this.$router.replace({ name: 'notice-list', query: params })

            return res.query
          } else {
            console.log('공지사항 리스트 가져오기 실패')
          }
        } catch (e) {
          console.log(e)
        }
      },
      async NoticeMoreLoad () {
        const res = await this.NoticeLoad()
        this.noticeLists = this.noticeLists.concat(res.notices)
        this.offset += res.notices.length
      },
      async InfiniteLoad () {
        let bottomOfWindow = ((document.documentElement.scrollTop + window.innerHeight) + 10) > document.getElementById('app').offsetHeight && ((document.documentElement.scrollTop + window.innerHeight) - 10) < document.getElementById('app').offsetHeight

        if (bottomOfWindow && this.itemCount !== this.form.offset && !this.bottomLoadingShow) {
          this.bottomLoadingShow = true
          await this.NoticeMoreLoad()
          this.bottomLoadingShow = false
        }
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
