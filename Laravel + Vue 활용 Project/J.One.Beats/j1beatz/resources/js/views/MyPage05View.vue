<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:-->
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <my-page-tab-component />

            <div class="page-contents-wrapper">
                <div class="sub-page-mypage">
                    <div class="sub-page-mypage-06">
                        <div class="sub-page-form-panel">
                            <div class="in-title in-title-mini_ver">
                                <h3 class="in-title-name">
                                    비밀번호
                                </h3>
                                <span class="in-title-ment">6~20자 영문 대소문자, 숫자, 특수문자 중 2가지 이상 조합</span>
                            </div>

                            <div class="sub-input_form">
                                <input
                                    v-model="password"
                                    type="password"
                                    placeholder="password"
                                >
                            </div>

                            <div
                                class="sub-input_form _pw-alert"
                                :class="[passwordConfirmAlert]"
                            >
                                <input
                                    v-model="confirm"
                                    type="password"
                                    placeholder="password"
                                >
                            </div>

                            <div class="in-title in-title-mini_ver">
                                <h3 class="in-title-name">
                                    닉네임
                                </h3>
                            </div>

                            <div class="sub-input_form">
                                <input
                                    v-model="nickname"
                                    type="text"
                                    placeholder="사용하실 닉네임을 입력하세요."
                                >
                            </div>

                            <div class="in-title in-title-mini_ver">
                                <h3 class="in-title-name">
                                    휴대폰
                                </h3>
                            </div>

                            <div class="sub-input_form _re-certify">
                                <button
                                    v-if="!isTryPhoneCertifyCheck"
                                    class="btn btn-certify"
                                    @click="phoneCertifyCheck"
                                >
                                    {{ !isPhoneCertifyChecked ? '휴대폰 인증' : '휴대폰 재인증' }}
                                </button>
                                <button
                                    v-else
                                    class="btn btn-certify"
                                    :class="{active: isTryPhoneCertifyCheck}"
                                    @click="phoneCertifyCheck"
                                >
                                    인증 확인
                                </button>
                            </div>

                            <button
                                type="button"
                                class="btn btn-complete"
                                @click="updateCheck"
                            >
                                정보변경
                            </button>
                            <button
                                class="btn btn-complete"
                                style="background-color: red; opacity: 0.75;"
                                @click="unregistbtn"
                            >
                                회원탈퇴
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isPopupVisible"
        >
            <div class="popup-layer-txt">
                {{ popupMessage }}
            </div>
        </notice-popup-component>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import MyPageTabComponent from "../components/my-page/MyPageTabComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import { checkPassword } from "../lib/common";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        MyPageTabComponent,
        NoticePopupComponent
    },
    data() {
        return {
            isPopupVisible: false,
            isPhoneCertifyChecked: false,
            isTryPhoneCertifyCheck: false,
            popupMessage: "",
            password: "",
            confirm: "",
            nickname: "",
            phone: "",
            mobileVerifyCode: ""
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user"
        }),
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
    methods: {
        async updateCheck() {
            if (this.password !== "" && !this.passwordCheck()) {
                return;
            }

            const params = Object.fromEntries(
                [
                    ["user_pw", this.password],
                    ["user_nick", this.nickname],
                    ["user_mobile", this.phone],
                    ["verify_code", this.mobileVerifyCode]
                ].filter(([, /*key*/ value]) => Boolean(value))
            );

            if (Object.entries(params).length === 0) {
                this.popupMessage = "변경할 사항이 없습니다";
                this.isPopupVisible = true;
                return;
            }

            try {
                await this.$http.post("/api/info", params);
                this.popupMessage = "수정 되었습니다";
                this.isPopupVisible = true;
                $(".btn.btn-01").click(function() {
                    location.reload();
                });
            } catch (e) {
                this.popupMessage = "정보수정 중 오류가 발생했습니다";
                this.isPopupVisible = true;
                console.log(e);
                $(".btn.btn-01").click(function() {
                    return false;
                });
            }
        },
        async phoneCertifyCheck() {
            if (this.isPhoneCertifyChecked) {
                this.isPhoneCertifyChecked = false;
            } else {
                if (!this.isTryPhoneCertifyCheck) {
                    try {
                        const res = await this.$http.post("/api/mobile/auth", {
                            req: "mobile_request"
                        });
                        const info = JSON.parse(res.data);
                        this.mobileVerifyCode = info.verify_code;

                        window.open(
                            info.online_url,
                            "_blank",
                            "width=#, height=#"
                        );

                        this.isTryPhoneCertifyCheck = true;
                    } catch (e) {
                        this.popupMessage =
                            "휴대폰 실명인증 중 오류가 발생했습니다";
                        this.isPopupVisible = true;
                        this.isTryPhoneCertifyCheck = false;
                        console.log(e);
                    }
                } else {
                    try {
                        const res = await this.$http.post("/api/mobile/auth", {
                            req: "mobile_verify",
                            verify_code: this.mobileVerifyCode
                        });
                        const info = JSON.parse(res.data);

                        this.phone = info.mobile_no

                        this.popupMessage = "휴대폰 실명인증이 완료되었습니다";
                        this.isPopupVisible = true;
                        this.isTryPhoneCertifyCheck = false;
                        this.isPhoneCertifyChecked = true;
                    } catch (e) {
                        this.popupMessage =
                            "휴대폰 실명인증 중 오류가 발생했습니다";
                        this.isPopupVisible = true;
                        this.isTryPhoneCertifyCheck = false;
                        console.log(e);
                    }
                }
            }
        },
        passwordCheck() {
            if (!this.password) {
                this.popupMessage = "비밀번호를 입력하세요";
                this.isPopupVisible = true;
                return false;
            }

            if (!this.confirm) {
                this.popupMessage = "비밀번호 확인을 입력하세요";
                this.isPopupVisible = true;
                return false;
            }

            if (!this.isPasswordSame) {
                this.popupMessage = "비밀번호 확인이 일치하지 않습니다";
                this.isPopupVisible = true;
                return false;
            }
            if (checkPassword(this.password) === -1) {
                this.popupMessage =
                    "비밀번호는 6자리 ~ 20자리 이내로 입력해주세요";
                this.isPopupVisible = true;
                return false;
            }

            if (checkPassword(this.password) === -2) {
                this.popupMessage = "비밀번호는 공백없이 입력해주세요";
                this.isPopupVisible = true;
                return false;
            }

            if (checkPassword(this.password) === -3) {
                this.popupMessage =
                    "비밀번호는 6~20자 영문 대소문자, 숫자, 특수문자 중 2가지 이상을 조합하여 입력해주세요";
                this.isPopupVisible = true;
                return false;
            }

            return true;
        },
        unregistbtn() {
            this.popupMessage = "탈퇴 승인이 이루어지면 탈퇴됩니다";
            this.isPopupVisible = true;
        }
    }
};
</script>

<style lang="scss">
</style>
