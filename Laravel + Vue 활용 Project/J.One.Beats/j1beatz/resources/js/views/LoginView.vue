<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <div class="sub-page-auth sub-page-auth-login">
                    <div class="sub-page-auth-title">
                        <h2>로그인</h2>
                        <span class="highlight">지금 회원가입 하면, 스트리밍 1개월 무료!</span>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div class="auth-input_form _mail">
                            <input
                                v-model="emailId"
                                type="text"
                                placeholder="아이디"
                            >
                            <span>@</span>
                            <div class="domain_grouping">
                                <input
                                    v-model="emailDomainInput"
                                    type="text"
                                    class="direct_input"
                                    :style="{visibility: isEmailDomainDirectInput ? 'visible' : 'hidden'}"
                                    placeholder="직접 입력"
                                >
                                <select
                                    v-model="emailDomainSelect"
                                    name="domain"
                                    class="select-style"
                                    :style="{width: isEmailDomainDirectInput ? '15%' : '100%'}"
                                    @change="emailDomainSelectChanged"
                                >
                                    <option value="">
                                        도메인 선택
                                    </option>
                                    <option value="naver.com">
                                        naver.com
                                    </option>
                                    <option value="nate.com">
                                        nate.com
                                    </option>
                                    <option value="daum.net">
                                        daum.net
                                    </option>
                                    <option value="gmail.com">
                                        gmail.com
                                    </option>
                                    <option
                                        v-if="!isEmailDomainDirectInput"
                                        value=" "
                                    >
                                        직접입력
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="auth-input_form">
                            <input
                                v-model="password"
                                type="password"
                                placeholder="비밀번호"
                                @keyup.enter="login"
                            >
                        </div>

                        <button
                            type="submit"
                            class="btn"
                            @click="login"
                        >
                            로그인
                        </button>

                        <div class="auth-login_option">
                            <div class="auto-login-group">
                                <input
                                    id="auto-login"
                                    type="checkbox"
                                    class="input-style-01"
                                >
                                <!--<label for="auto-login">자동 로그인</label>-->
                            </div>

                            <ul class="id-pw-join-group">
                                <li>
                                    <a
                                        href="sub-auth-find_id.html"
                                        @click.prevent="$router.push('/find-id')"
                                    >아이디 찾기</a>
                                </li>
                                <li>
                                    <a
                                        href="sub-auth-find_pw.html"
                                        @click.prevent="$router.push('/find-pw')"
                                    >비밀번호 찾기</a>
                                </li>
                                <li class="highlight">
                                    <a
                                        href="#"
                                        @click.prevent="$router.push('/register')"
                                    >회원가입</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <footer-component />
        </section>

        <notice-popup-component
            title-text="로그인 실패"
            :visible.sync="isLoginFailPopupVisible"
        >
            <div class="popup-layer-txt">
                아이디나 비밀번호가 잘못되었습니다.<br>다시 시도해주시기 바랍니다.
            </div>
        </notice-popup-component>

        <notice-popup-component
            title-text="이메일 인증"
            :visible.sync="isEmailVerifyPopupVisible"
        >
            <h4 class="popup-layer-title">
                이메일 인증
            </h4>
            <div class="member-mail-guide">
                <p>{{ userEmail }}</p>
            </div>
            <div class="popup-layer-txt _small">
                입력하신 이메일로 인증메일이 전송되었습니다.<br>전송된 메일에서 인증완료 버튼을 누르시면 인증이 완료됩니다.
            </div>
        </notice-popup-component>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isCheckInputFailPopupVisible"
        >
            <div class="popup-layer-txt">
                {{ checkInputFailMessage }}
            </div>
        </notice-popup-component>

        <!--권한관련 페이지는 footer 없음-->
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import HeaderComponent from "../components/layout/HeaderComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import FooterComponent from "../components/layout/FooterComponent";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent
    },
    data() {
        return {
            isLoginFailPopupVisible: false,
            isEmailVerifyPopupVisible: false,
            isCheckInputFailPopupVisible: false,
            isEmailDomainDirectInput: false,
            isTryLogin: false,
            emailId: "",
            emailDomainInput: "",
            emailDomainSelect: "",
            password: ""
        };
    },
    computed: {
        checkInputFailMessage() {
            if (!this.emailId) {
                return "아이디를 입력하세요";
            }

            if (!this.emailDomain) {
                return "도메인을 입력하세요";
            }

            if (!this.password) {
                return "비밀번호를 입력하세요";
            }

            return false;
        },
        userEmail() {
            return this.emailId + "@" + this.emailDomain;
        },
        emailDomain() {
            if (this.isEmailDomainDirectInput) {
                return this.emailDomainInput;
            } else {
                return this.emailDomainSelect;
            }
        }
    },
    methods: {
        emailDomainSelectChanged() {
            if (this.emailDomain === " ") {
                this.isEmailDomainDirectInput = true;
                this.emailDomainSelect = "none";
                this.emailDomainInput = "";
            } else {
                this.isEmailDomainDirectInput = false;
            }
        },
        async login() {
            if (this.isTryLogin) {
                return;
            }

            if (this.checkInputFailMessage) {
                this.isCheckInputFailPopupVisible = true;
                return;
            }

            try {
                this.isTryLogin = true;
                this.$store.commit("updateIsGlobalLoading", true);

                const token = await this.$http
                    .post("/api/login", {
                        user_email: this.userEmail,
                        user_pw: this.password
                    })
                    .then(response => response.data.token);

                localStorage.laravel_token = token;
                this.$http.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${token}`;

                const [{ user, producer }, playlist] = await Promise.all([
                    this.$http.get("/api/info"),
                    this.$http.get(`/api/playlists`)
                ]).then(responses => responses.map(response => response.data));

                this.$store.commit("updateUser", user);
                this.$store.commit("updateProducer", producer);
                this.$store.commit("updatePlaylist", playlist);

                const redirect = this.$route.params.redirect;
                if (redirect) {
                    this.$router.replace({
                        name: redirect.name,
                        query: redirect.query,
                        params: redirect.params
                    });
                } else {
                    this.$router.replace({ path: "/" });
                }
            } catch (e) {
                if (e.response) {
                    const response = e.response.data;
                    if (response.code === "-1") {
                        this.isEmailVerifyPopupVisible = true;
                        this.password = "";
                        return;
                    }
                }

                localStorage.removeItem("laravel_token");
                this.$http.defaults.headers.common["Authorization"] = undefined;

                this.isLoginFailPopupVisible = true;
                this.password = "";
                console.log(e);
            } finally {
                this.isTryLogin = false;
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
</style>
