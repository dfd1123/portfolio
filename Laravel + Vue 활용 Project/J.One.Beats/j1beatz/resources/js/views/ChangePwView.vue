<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <div class="sub-page-auth">
                    <div class="sub-page-auth-title">
                        <h2>비밀번호 찾기</h2>
                    </div>

                    <!-- FIXME: 비밀번호찾기 과정 두번째 -->
                    <div
                        v-if="isTokenReceived"
                        class="sub-page-auth-find_pw__after"
                    >
                        <div class="sub-page-auth-panel">
                            <div class="certify-good">
                                <img
                                    src="img/icon/certify-good.svg"
                                    alt="certify complete"
                                >
                                <p><b>이메일 인증</b>이 완료되었습니다.</p>
                            </div>

                            <div class="in-title in-title-mini_ver">
                                <h3 class="in-title-name">
                                    비밀번호 재설정
                                </h3>
                                <span class="in-title-ment">6~20자 영문 대소문자, 숫자, 특수문자 중 2가지 이상 조합</span>
                            </div>

                            <div class="auth-input_form _pw-alert">
                                <input
                                    v-model="password"
                                    type="password"
                                    placeholder="비밀번호 입력"
                                >
                            </div>

                            <div
                                class="auth-input_form _pw-alert"
                                :class="[passwordConfirmAlert]"
                            >
                                <input
                                    v-model="confirm"
                                    type="password"
                                    placeholder="비밀번호 확인"
                                >
                            </div>

                            <button
                                type="button"
                                :class="{active: isTokenReceived && isPasswordSame}"
                                class="btn btn-complete"
                                @click="changePassword"
                            >
                                완료
                            </button>
                        </div>
                    </div>
                    <!-- FIXME: 비밀번호찾기 과정 두번째 END -->
                </div>
            </div>

            <footer-component />
        </section>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isNoticePopupVisible"
            @closeButtonClick="routeByResult"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="noticePopupMessage"
            />
        </notice-popup-component>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import { checkPassword } from "../lib/common";

export default {
    beforeRouteEnter(to, from, next) {
        if (to.query.verify) {
            next();
        } else {
            next("/404");
        }
    },
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent
    },
    data() {
        return {
            isTokenReceived: false,
            isPasswordChanged: false,
            isNoticePopupVisible: false,
            noticePopupMessage: "",
            password: "",
            confirm: "",
            email: ""
        };
    },
    computed: {
        isPasswordSame() {
            return (
                this.password && this.confirm && this.password === this.confirm
            );
        },
        passwordConfirmAlert() {
            if (!this.password || !this.confirm) {
                return "";
            }

            if (this.isPasswordSame) {
                return "_pw-alert-able";
            } else {
                return "_pw-alert-disable";
            }
        }
    },
    async created() {
        await this.verifyPasswordEmail();
    },
    methods: {
        checkPassword,
        async verifyPasswordEmail() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                const response = await this.$http.post(
                    "/api/password/find/verify",
                    {
                        verify_code: this.$route.query.verify
                    }
                );
                const token = response.data.token;

                localStorage.laravel_token = token;
                this.$http.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${token}`;

                this.isTokenReceived = true;
                this.noticePopupMessage =
                    "비밀번호 찾기 이메일 인증이 완료되었습니다 <br> 비밀번호를 변경해주시기 바랍니다";
                this.isNoticePopupVisible = true;
            } catch (e) {
                console.log(e.response);
                if (e.response && e.response.status == 422) {
                    this.noticePopupMessage =
                        "인증 이메일이 만료되었거나 <br> 네트워크 오류입니다. <br> 비밀번호 찾기 인증을 다시 진행해 <br> 주시기 바랍니다";
                    this.isNoticePopupVisible = true;
                    return;
                }

                this.noticePopupMessage =
                    "비밀번호 찾기 인증 과정 중 오류가 발생했습니다";
                this.isNoticePopupVisible = true;
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        passwordCheck() {
            if (!this.password) {
                this.noticePopupMessage = "비밀번호를 입력하세요";
                this.isNoticePopupVisible = true;
                return false;
            }

            if (!this.confirm) {
                this.noticePopupMessage = "비밀번호 확인을 입력하세요";
                this.isNoticePopupVisible = true;
                return false;
            }

            if (!this.isPasswordSame) {
                this.noticePopupMessage = "비밀번호 확인이 일치하지 않습니다";
                this.isNoticePopupVisible = true;
                return false;
            }
            if (checkPassword(this.password) === -1) {
                this.noticePopupMessage =
                    "비밀번호는 6자리 ~ 20자리 이내로 입력해주세요";
                this.isNoticePopupVisible = true;
                return false;
            }

            if (checkPassword(this.password) === -2) {
                this.noticePopupMessage = "비밀번호는 공백없이 입력해주세요";
                this.isNoticePopupVisible = true;
                return false;
            }

            if (checkPassword(this.password) === -3) {
                this.noticePopupMessage =
                    "비밀번호는 6~20자 영문 대소문자, 숫자, 특수문자 중 2가지 이상을 조합하여 입력해주세요";
                this.isNoticePopupVisible = true;
                return false;
            }

            return true;
        },
        async changePassword() {
            if (!this.passwordCheck()) {
                return;
            }

            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.post("/api/info", {
                    user_pw: this.password
                });

                this.isPasswordChanged = true;
                this.noticePopupMessage = "비밀번호가 변경 되었습니다";
                this.isNoticePopupVisible = true;
            } catch (e) {
                this.noticePopupMessage =
                    "비밀번호 변경 중 오류가 발생했습니다";
                this.isNoticePopupVisible = true;
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        routeByResult() {
            if (!this.isTokenReceived) {
                this.$router.push("/find-pw");
                return;
            }

            if (this.isPasswordChanged) {
                window.location.reload();
            }
        }
    }
};
</script>

<style lang="scss">
</style>
