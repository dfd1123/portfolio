<template>
    <div id="trst-login-page">
        <header-component
            leftButton=""
            v-on:leftMenuButtonClick="isSideMenuVisible = true"
            v-on:logoClick="fetchData"
            v-on:langButtonClick="isLangSelectVisible = true;"
            :main-header="true"
            :main-header-active="isHideChangeBar == false"
        ></header-component>
        <!-- 로그인페이지 -->
        <div class="trst-container">
            <div class="trst-inner">
                <div class="contents">
                    <img src="/images/trst-images/logo/login.svg">
                    <fieldset class="login_con">
                        <div class="auth-form" :class="{ active : isEmailActive || email }">
                            <label for="#" class="mini_label" v-html="__('login.placeholder_id')"></label>
                            <input 
                                type="email" 
                                name="email"
                                v-model="email"
                                autocomplete="off"
                                class="in_input"
                                @focus="isEmailActive = true"
                                @blur="isEmailActive = email ? true : false"
                            />
                        </div>
                        <div class="auth-form" :class="{ active : isPwActive || password }">
                            <label for="#" class="mini_label" v-html="__('login.placeholder_pw')"></label>
                            <input
                                type="password"
                                name="password"
                                v-model="password"
                                v-on:keyup.enter="login"
                                class="in_input"
                                @focus="isPwActive = true"
                                @blur="isPwActive = password ? true : false"
                            />
                        </div>
                        <input type="button" class="trst-login-btn" :value="__('login.login')" @click="login">
                    </fieldset>
                    <p class="login_options">
                        <input type="button" :value="__('login.register')" @click="$router.replace({ path: '/register_agree' })">
                        <!--<input type="button" value="비밀번호 찾기">-->
                    </p>
                </div>
            </div>
        </div>
        <!-- end 로그인페이지 -->
        <lang-select-component :visible.sync="isLangSelectVisible"></lang-select-component>
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
            isEmailActive: false,
            isPwActive: false,
            email: "",
            password: "",
            isPopupVisible: false,
            isLangSelectVisible: false
        };
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
#header .hd_center, #header button.hd_left{
    display:none !important;
}

#trst-login-page{
    background: linear-gradient(to bottom, #64E196, #19B4AA);
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
    text-align: center;
    overflow-y: scroll;
}

@media all and (min-width: 320px) and (max-width: 350px){
    #trst-login-page{
        font-size: 14px;
    }
}

#trst-login-page #header{
    background: transparent;
    border-bottom: 0;
}

#trst-login-page .trst-container{
    width: 100%;
    height: 100%;
    display: table;
    padding: 0;
}

#trst-login-page .trst-inner{
    display: table-cell;
    vertical-align: middle;
    position: relative;
    overflow-x: hidden;
}

#trst-login-page .trst-inner:before{
    content: '';
    width: 100%;
    height: 100%;
    position: absolute;
    top: 50%;
    left: 0;
    z-index: 0;
    background: linear-gradient(to top, #64E196, #19B4AA);
    display: inline-block;
    opacity: 0.4;
    transform: translate(0, -50%) skewX(126deg);
}

@media all and (min-width: 320px) and (max-width: 350px){
    #trst-login-page{
        font-size: 14px;
    }
}

#trst-login-page .contents{
    padding: 0 1.25rem;
}

#trst-login-page .contents > img{
    width: 131px;
    margin-bottom: 35px;
    position: relative;
    z-index: 1;
    opacity: 0;
    transform: translateY(3rem);
    animation: verticalAni 1s 1 0.1s;
    animation-timing-function: cubic-bezier(0.51, 0.92, 0.24, 1.15);
    animation-fill-mode: both;
}

.login_con {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    position: relative;
    overflow: hidden;
    padding: 25px 15px 12px;
    z-index: 1;
    opacity: 0;
    transform: translateY(2rem);
    animation: verticalAni 1s 1 0.2s;
    animation-timing-function: cubic-bezier(0.51, 0.92, 0.24, 1.15);
    animation-fill-mode: both;
}

.login_con .auth-form {
    width: 100%;
    position: relative;
    padding: 6px 0 0;
    margin-bottom: 20px;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .login_con .auth-form {
        margin-bottom: 18px;
    }
}

.auth-form .mini_label {
    position: absolute;
    top: 50%;
    left: 0;
    z-index: 0;
    margin-bottom: 0;
    font-size: 0.95em;
    color: white;
    transform: translateY(-50%);
    letter-spacing: -0.2px;
    transition: all 0.3s;
}

.auth-form .in_input {
    position: relative;
    z-index: 1;
    width: 100%;
    background-color: transparent;
    border-radius: 0;
    border: 0;
    border-bottom: 1px solid rgba(255,255,255,0.6);
    box-shadow: none;
    padding: 0;
    line-height: normal;
    font-size: 0.95em;
    color: white;
    letter-spacing: 0.5px;
    font-weight: 300;
    height: 2.3em;
}

.auth-form .in_input:focus{
    border-color: white;
}

.auth-form.active .mini_label{
    transition: all 0.3s;
    font-size: 12px;
    top: 0;
    left: 0;
    font-weight: 200;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .auth-form.active .mini_label{
        font-size: 10px;
    }
}

.trst-login-btn {
    width: 100%;
    background-color: white;
    border: 0;
    border-radius: 8px;
    height: 45px;
    color: #19B4AA;
    font-size: 1.15em;
    font-weight: 500;
    letter-spacing: -0.2px;
    margin-top: 1.5em;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .trst-login-btn{
        margin-top: 1em;
    }
}

.trst-login-btn:active{
    background-color: rgba(255,255,255,0.8);
}

.login_options{
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    text-align: right;
    padding: 20px 13px;
}

.login_options > input{
    position: relative;
    z-index: 1;
    color: white;
    background-color: transparent;
    border-radius: 0;
    border: 0;
    letter-spacing: -0.2px;
    font-size: 0.93em;
    font-weight: 300;
    box-shadow: none;
    opacity: 0;
    transform: translateY(1rem);
    animation: verticalAni 1s 1 0.2s;
    animation-timing-function: cubic-bezier(0.51, 0.92, 0.24, 1.15);
    animation-fill-mode: both;
}

.login_options > input:active{
    color: rgba(255,255,255,0.8);
}

@keyframes verticalAni {
    100%{
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
