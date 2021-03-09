<template>
  <div class="ai_wrapper">
    <header-component v-on:langButtonClick="isPopupVisible = true;"></header-component>
    <div class="ai_container">
      <div class="login_wrap">
        <img src="/images/logo/logo_symbol.png" alt class="login__symbol" />
        <p class="login__ment">
          혁신적인 글로벌 암호화폐거래
          <em>phillips pay</em>
        </p>
        <div class="login_con">
          <div class="inp_div">
            <input
              type="email"
              name="email"
              :placeholder="__('login.placeholder_id')"
              v-model="email"
              autocomplete="off"
            />
          </div>
          <div class="inp_div">
            <input
              type="password"
              name="password"
              v-model="password"
              v-on:keyup.enter="login"
              :placeholder="__('login.placeholder_pw')"
            />
          </div>
          <div class="submit_btn">
            <button type="button" @click="login" :class="{active: isReadyToLogin}">로그인</button>
          </div>
          <div class="login__option">
            <a @click.prevent="$router.replace({ path: '/register_agree' });">회원가입</a>|
            <a @click.prevent="$router.replace({ path: '/password-find' });">비밀번호찾기</a>
          </div>
        </div>
      </div>
    </div>
    <lang-select-component :visible.sync="isPopupVisible"></lang-select-component>
  </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import LangSelectComponent from "../components/common/LangSelectComponent";

export default {
  async beforeRouteEnter(to, from, next) {
    if (localStorage.passportToken) {
      next("/home");
    } else {
      next();
    }
  },
  components: {
    "header-component": HeaderComponent,
    "lang-select-component": LangSelectComponent,
    "footer-component": FooterComponent
  },
  data() {
    return {
      email: "",
      password: "",
      isPopupVisible: false
    };
  },
  computed: {
    backgroundImage() {
      return this.__("LANG") === "kr"
        ? "/images/logo/logo_symbol.png"
        : "/images/logo/logo_symbol.png";
    },
    isReadyToLogin() {
      return this.email && this.password ? true : false;
    }
  },
  methods: {
    async login() {
      try {
        this.$store.commit("progressComponentShow");

        const response = await axios.post("/api/login", {
          email: this.email,
          password: this.password
        });
        const token = response.data.token;

        localStorage.passportToken = token;
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

        // 푸시 토큰 요청
        this.$EventBus.$emit("push-token-request");

        this.$router.replace({ path: "/home" });
      } catch (e) {
        this.$swal({
          type: "error",
          text: this.__("login.error_text")
        });
        this.password = "";
        this.$store.commit("progressComponentHide");
      }
    }
  }
};
</script>

<style scoped>
.ai_wrapper {
  position: relative;
  height: 100%;
  padding-top: 43px;
  margin-bottom: -53px;
  padding-bottom: 52px;
  background: #000;
}

.ai_container {
  min-height: 100%;
  height: 100%;
  overflow-x: hidden;
  overflow-y: auto;
}

.login_wrap {
  background-size: 45% !important;
  background-position: center 60px !important;
  background-color: #000 !important;
  min-height: 100%;
  text-align: center;
}

.login__symbol {
  max-width: 10rem;
  width: 40%;
  margin: 30px auto 15px;
}

.login_wrap p.login__ment {
  font-size: 1em;
  color: #bebebe;
  display: inline-block;
  width: 100%;
  margin-bottom: 15px;
  letter-spacing: -1px;
}

.login_wrap p.login__ment em {
  font-style: normal;
  color: #fff;
  text-transform: uppercase;
  font-weight: normal;
}

.login_con {
  max-width: 30rem;
  margin: 0 auto;
  position: relative;
  overflow: hidden;
}

.login_con .inp_div {
  padding: 0 17px;
}

.login_con .inp_div input {
  -webkit-border-radius: 10px;
  border: 0;
  outline: none;
  -webkit-appearance: none;
  background: #fff;
  font-size: 0.75em;
  width: 100%;
  height: 40px;
  margin-bottom: 12px;
  line-height: normal;
  padding: 5px 15px;
  outline: none;
  border: 1px solid transparent;
}

.login_con .inp_div {
  padding: 0 25px;
  padding-top: 10px;
}

.login_con .submit_btn {
  padding: 0 20px;
}

.login_con .submit_btn button {
  margin-top: 20px;
  border-radius: 10px;
  -webkit-border-radius: 10px;
  border: 0;
  outline: none;
  background-color: #cccccc;
  cursor: pointer;
  font-size: inherit;
  font-weight: normal;
  color: #999999;
  width: 100%;
  height: 40px;
}

.login_con .submit_btn button.active {
  background-color: #2e87c8;
  color: #fff;
}

.login__option {
  font-size: 0.75em;
  color: #444444;
  margin: 15px 0;
  text-align: center;
}

.login__option a {
  color: #cccccc;
  padding: 0 18px;
}
</style>
