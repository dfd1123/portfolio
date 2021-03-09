<template>
  <div id="app">
    <div
      v-show="$store.state.progressShow"
      :class="['progress_wrap', {'active':$store.state.progressShow}]"
    >
      <div class="progress_con">
        <Loading />
      </div>
    </div>
    <Header v-if="mainHeader" />
    <router-view />
    <Footer />
  </div>
</template>

<script>
  import Header from '@/components/mobile/common/header/header.vue'
  import Footer from '@/components/mobile/common/footer/footer.vue'
  import Loading from '@/components/mobile/common/loading/loading.vue'

  export default {
    components: {
      Header,
      Footer,
      Loading
    },
    data: function () {
      return {
        mainHeader: true
      }
    },
    created () {
      this.HeaderSetting()
    },
    updated () {
      this.HeaderSetting()
    },
    methods: {
      HeaderSetting () {
        const subHdUrls = [
          'login',
          'register',
          'find'
        ]

        const urlPath = this.$route.fullPath.split('/')

        if (subHdUrls.indexOf(urlPath[1]) !== -1) {
          this.mainHeader = false
        } else {
          this.mainHeader = true
        }
      }
    }
  }
</script>

<style lang="scss" >
  html {
  }
</style>
