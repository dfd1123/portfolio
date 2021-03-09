<template>
    <div class="ai_wrapper">
        <header-component v-on:langButtonClick="isPopupVisible = true;"></header-component>
        <div class="ai_container">
            <div class="login_wrap" :style="{ background: `url('${backgroundImage}') no-repeat` }">
                <h2>LOGIN</h2>
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
                        <button
                            type="button"
                            @click="login"
                        >{{__('login.login')}}</button>
                    </div>
                </div>
            </div>
        </div>
        <lang-select-component :visible.sync="isPopupVisible"></lang-select-component>
        <footer-component
            :buttonText="__('login.register')"
            active-color="#49d094"
            @buttonClick="$router.replace({ path: '/register_kind' })"
        ></footer-component>
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
                ? "/images/login_bg_kr.svg"
                : "/images/login_bg_jp.svg";
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
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${token}`;

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
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.login_wrap {
    background-size: 100%;
    min-height: 100%;
    padding-top: 71%;
    text-align: center;
}

.login_wrap h2 {
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
    color: #fff;
    font-size: 20px;
    font-weight: bold;
    letter-spacing: 5px;
    padding-bottom: 16px;
    line-height: 1.1;
}

.login_con {
    width: 86%;
    max-width: 500px;
    margin: 0 auto;
    background: #fff;
    border-radius: 13px;
    padding-top: 30px;
    padding-bottom: 80px;
    box-shadow: rgba(0, 69, 191, 0.5) 0px 2px 26px;
    position: relative;
    overflow: hidden;
}

.login_con .inp_div {
    padding: 0 25px;
    padding-top: 10px;
}

.login_con .inp_div input {
    width: 100%;
    border: none;
    border-bottom: 1px solid #c8c8c8;
    font-size: 14px;
    color: #5a5a5a;
    height: 35px;
    padding: 0 10px;
    border-radius: 0;
}

.login_con .inp_div {
    padding: 0 25px;
    padding-top: 10px;
}

.login_con .submit_btn {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
}

.login_con .submit_btn button {
    background: #1d324f;
    border: none;
    color: #fff;
    width: 100%;
    height: 40px;
    font-size: 15px;
}
</style>
