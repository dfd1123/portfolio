<template>
  <div id="mypage_notice" class="page page--hd_default">
    <header-component hd-title="공지사항" :hd-theme-ylw="true" @privButtonClick="privButtonClick" />
    <div class="page_container pd_bt_nav">
      <transition appear name="slide-fade">
        <div class="contents">
          <!-- 공지사항 목록 -->
          <div class="notice_group">
            <notice-list
              v-for="notice in noticeList"
              v-bind:noticeList="notice"
              v-bind:key="notice.ntc_no"
            ></notice-list>
          </div>
          <!-- end -->
        </div>
      </transition>
    </div>
    <footer-navigation :mypage-on="true"></footer-navigation>
  </div>
</template>

<script>
export default {
  name: 'MyPageNotice',
  data () {
    return {
      noticeList: []
    }
  },
  created () {
    this.fetchData()
  },
  methods: {
    privButtonClick () {
      this.$router.push('/mypage')
    },
    async fetchData () {
      try {
        await this.$axios.get('/notices').then(response => {
          this.noticeList = response.data
        })
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#mypage_notice {
  .page_container {
    overflow-x: hidden;
  }
  .contents {
    padding: 0;
    overflow-y: scroll;
  }
}
</style>
