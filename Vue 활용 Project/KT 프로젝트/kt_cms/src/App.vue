<template>
  <div id="app">
    <div
      v-if="!isLogin"
      id="wrap"
    >
      <Header />
      <section id="dBody">
        <SubMenu />
        <section id="contents">
          <router-view />
        </section>
        <Loading />
        <Footer />
      </section>
    </div>
    <div v-else>
      <router-view />
    </div>
  </div>
</template>

<script>
  import Header from '@/components/common/Header.vue'
  import SubMenu from '@/components/common/SubMenu.vue'
  import Footer from '@/components/common/Footer.vue'
  import Loading from '@/components/loading/Loading.vue'

  export default {
    components: {
      Header,
      SubMenu,
      Footer,
      Loading
    },
    computed: {
      isLogin () {
        return this.$route.path === '/login'
      }
    }
  }
</script>
<style lang="stylus"></style>
