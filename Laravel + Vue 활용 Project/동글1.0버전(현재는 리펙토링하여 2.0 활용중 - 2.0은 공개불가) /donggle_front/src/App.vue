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
    <router-view v-if="this.$store.state.routerAlive"/>
    <Footer />
    <QuickMenu v-if="mainHeader" />
  </div>
</template>

<script>
  import Header from '@/components/common/header/header.vue'
  import Footer from '@/components/common/footer/footer.vue'
  import Loading from '@/components/common/loading/loading.vue'

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
          'find',
          'subscribe'
        ]

        const urlPath = this.$route.fullPath.split('/')

        if (subHdUrls.indexOf(urlPath[1]) !== -1) {
          this.mainHeader = false
        } else {
          this.mainHeader = true
        }
      },
      MakeId (length) {
        var result = ''
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
        var charactersLength = characters.length
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength))
        }
        return result
      }
    }
  }
</script>

<style lang="scss" >
  html {
  }
</style>
