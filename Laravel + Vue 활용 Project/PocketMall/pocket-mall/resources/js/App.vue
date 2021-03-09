<template>
  <div>
    <div id="app">
      <header-component :class="{ active : scrollHd && !openNav && !$store.state.openQuestion }" @openNav="ClickNavigation" @openQuestion="$store.commit('QuestionCompFalse')"></header-component>
      <div class="wrapper">
        <router-view />
      </div>
      <nav-component :class="{ opened_nav : openNav }"></nav-component>
      <question-component :class="{ opened_nav : $store.state.openQuestion }" />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      openNav: false,
      scrollHd: null,
      scrollHeight: null
    };
  },
  mounted() {
    window.addEventListener("scroll", this.HdScrollActive);
  },
  methods: {
    ClickNavigation() {
      this.openNav = !this.openNav;
    },
    HdScrollActive() {
      const nowScroll = window.scrollY;

      if (this.IsMobile()) {
        this.scrollHeight = 50;
      } else {
        this.scrollHeight = 80;
      }

      nowScroll > this.scrollHeight
        ? (this.scrollHd = true)
        : (this.scrollHd = false);
    }
  }
};
</script>

<style>
</style>