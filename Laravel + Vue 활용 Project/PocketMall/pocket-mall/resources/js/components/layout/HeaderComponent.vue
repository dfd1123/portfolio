<template>
  <div class="header">
    <div class="header-inner">
      <h1 class="header-logo" :class="{ none : navChange || $store.state.openQuestion }">
        <a href="/">
          <img src="assets/images/logo/logo_header_wh.svg" alt="포켓컴퍼니 로고" class="in-logo" />
          <img src="assets/images/logo/m_logo_header_wh.svg" alt="포켓컴퍼니 로고" class="m-in-logo" />
        </a>
      </h1>
      <button
        type="button"
        class="m-cart-button"
        @click="$router.push('/check-cart')"
        :class="{ none : navChange || $store.state.openQuestion }"
      >
        <img src="assets/images/icon/icon_cart_btn_wh.svg" alt="장바구니" class="cart-icon" />
        <span
          class="cart-number"
        >{{ $store.state.carts.length > 0 ? '+'+$store.state.carts.length : '' }}</span>
      </button>
      <button
        type="button"
        class="header-nav-button"
        :class="{ active : navChange || $store.state.openQuestion }"
        @click="NavClick"
      >
        <span class="hamburger"></span>
        <span class="hamburger"></span>
        <span class="hamburger"></span>
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: "header-component",
  data() {
    return {
      navChange: false
    };
  },
  methods: {
    NavClick() {
      if(this.$store.state.openQuestion){
        this.$store.commit('QuestionCompFalse');
      }else{
        this.navChange = !this.navChange;
        this.$emit("openNav");
      }
    }
  }
};
</script>

<style scoped>
.header {
  position: fixed;
  width: 100%;
  height: 5rem;
  line-height: 5rem;
  z-index: 5;
  padding: 0 50px;
  z-index: 7000;
}

.header:before {
  content: "";
  width: 100%;
  height: 100%;
  display: inline-block;
  position: absolute;
  top: 0;
  left: 0;
  background: linear-gradient(
    to right,
    rgba(29, 187, 255, 0.8),
    hsla(220, 100%, 56%, 0.8)
  );
  opacity: 0;
  transition: 0.3s;
}

.header.active:before {
  opacity: 1;
  transition: 0.4s;
}

@media all and (max-width: 768px) {
  .header {
    padding: 0 30px;
  }
}

@media all and (max-width: 630px) {
  .header {
    padding: 0px 25px;
    height: 3.85rem;
    line-height: 3.85rem;
  }

  .header.active {
    height: 4rem;
    line-height: 4rem;
  }
}

.header-inner {
  height: 100%;
  position: relative;
}

.header-logo {
  width: 14.688rem;
  display: inline-block;
  vertical-align: middle;
  line-height: 1;
}

.header-logo.none {
  display: none;
}

.header-logo > a {
  width: 100%;
  display: inline-block;
  height: 100%;
}

.header-logo .in-logo {
  float: left;
}

.header-nav-button {
  width: 24px;
  height: 19px;
  position: absolute;
  top: 50%;
  right: 0;
  transform: translateY(-50%);
  background-color: transparent;
  border-radius: 0;
  border: 0;
  padding: 0;
  cursor: pointer;
  outline: none;
}

/* @media all and (max-width: 630px) {
  .header-nav-button.active {
    right: -30px;
  }
} */

.header-nav-button .hamburger {
  height: 3px;
  width: 24px;
  display: block;
  background-color: white;
  border-radius: 30px;

  -webkit-transition: 0.25s margin 0.25s, 0.8s background-color,
    0.25s -webkit-transform;
  transition: 0.25s margin 0.25s, 0.8s background-color, 0.25s -webkit-transform;
  transition: 0.25s margin 0.25s, 0.25s transform, 0.8s background-color;
  transition: 0.25s margin 0.25s, 0.25s transform, 0.8s background-color,
    0.25s -webkit-transform;
}

.header-nav-button .hamburger:nth-child(1) {
  margin-bottom: 5px;
}

.header-nav-button .hamburger:nth-child(3) {
  margin-top: 5px;
}

.header-nav-button.active .hamburger {
  -webkit-transition: 0.25s margin, 1.3s background-color,
    0.25s -webkit-transform 0.5s;
  transition: 0.25s margin, 1.3s background-color, 0.25s -webkit-transform 0.5s;
  transition: 0.25s margin, 0.25s transform 0.5s, 1.3s background-color;
  transition: 0.25s margin, 0.25s transform 0.5s, 1.3s background-color,
    0.25s -webkit-transform 0.5s;
  background-color: black;
}

.header-nav-button.active .hamburger:nth-child(1) {
  margin-top: 8px;
  margin-bottom: -3px;
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
}

.header-nav-button.active .hamburger:nth-child(2) {
  -webkit-transform: rotate(45deg);
  transform: rotate(45deg);
}

.header-nav-button.active .hamburger:nth-child(3) {
  margin-top: -3px;
  -webkit-transform: rotate(135deg);
  transform: rotate(135deg);
}

@media all and (max-width: 630px) {
  .header-nav-button .hamburger {
    width: 22px;
  }

  .header-nav-button .hamburger:nth-child(1) {
    margin-bottom: 4px;
  }

  .header-nav-button .hamburger:nth-child(3) {
    margin-top: 4px;
  }
}
</style>