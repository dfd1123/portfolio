<template>
  <div class="dg-hd-my_menu">
    <router-link
      v-if="$store.state.user.id === ''"
      to="/login"
      class="logout_menu top_menu top_menu_login"
    >
      로그인
    </router-link>
    <router-link
      v-if="$store.state.user.id === ''"
      to="/register"
      class="logout_menu top_menu top_menu_reg"
    >
      회원가입
    </router-link>
    <span
      v-if="$store.state.user.id !== ''"
      class="login_menu top_menu top_menu_name"
    >
      <b>{{ $store.state.user.name }} </b>
      님<br>
      <span>{{ LevelName($store.state.user.level) }}</span>
    </span>
    <button
      v-if="$store.state.user.id !== ''"
      type="button"
      @click="Logout()"
      class="login_menu top_menu top_menu_logout"
    >
      로그아웃
    </button>
    <router-link
      to="/notices"
      class="top_menu top_menu_cs"
    >
      고객센터
    </router-link>
    <HeadAlrim :head-small="headSmall" />
    <router-link
      to="/mypage"
      class="top_menu top_menu_my"
    >
      마이페이지
    </router-link>
    <router-link
      to="/cart"
      class="top_menu top_menu_order"
    >
      장바구니
    </router-link>
  </div>
</template>

<script>
  import HeadAlrim from '@/components/common/header/alrim/head-alrim.vue'

  export default {
    components: {
      HeadAlrim
    },
    props: {
      headSmall: {
        type: Boolean,
        default: false
      }
    },
    methods: {
      Logout () {
        this.$store.dispatch('Logout')
      }
    }
  }
</script>

<style lang="scss" scoped>
  button {
    background-color: transparent;
    border: none;
    outline: none;
    cursor: pointer;
  }
</style>
