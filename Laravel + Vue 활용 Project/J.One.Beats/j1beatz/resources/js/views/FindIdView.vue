<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <div class="sub-page-auth">
                    <div class="sub-page-auth-title">
                        <h2>아이디 찾기</h2>
                    </div>

                    <!-- FIXME: 아이디찾기 과정 첫번째 -->
                    <div
                        v-if="findStep === 1"
                        class="sub-page-auth-find_id__before"
                    >
                        <div class="sub-page-auth-panel">
                            <div class="in-title in-title-mini_ver">
                                <h3 class="in-title-name">
                                    휴대폰 실명인증
                                </h3>
                                <span class="in-title-ment">회원가입 시, 인증받은 휴대폰 번호로 인증해 주세요.</span>
                            </div>

                            <button
                                v-if="!isTryPhoneCertifyCheck"
                                class="btn btn-certify active"
                                @click="phoneCertifyCheck"
                            >
                                {{ !isPhoneCertifyChecked ? '휴대폰 인증' : '휴대폰 재인증' }}
                            </button>
                            <button
                                v-else
                                class="btn btn-certify active"
                                @click="phoneCertifyCheck"
                            >
                                인증 확인
                            </button>

                            <button
                                class="btn btn-complete"
                                :class="{active: isPhoneCertifyChecked}"
                                :disabled="!isPhoneCertifyChecked"
                                @click="verifyComplete"
                            >
                                인증완료
                            </button>
                        </div>
                    </div>
                    <!-- FIXME: 아이디찾기 과정 첫번째 END -->

                    <!-- FIXME: 아이디찾기 과정 두번째 -->
                    <div
                        v-if="findStep === 2"
                        class="sub-page-auth-find_id__after"
                    >
                        <div class="sub-page-auth-panel">
                            <div class="certify-good">
                                <img
                                    src="img/icon/certify-good.svg"
                                    alt="certify complete"
                                >
                                <p><b>휴대폰 인증</b>이 완료되었습니다.</p>
                            </div>

                            <div class="member-mail-guide">
                                <span>제이원비츠 회원아이디</span>
                                <p>{{ email }}</p>
                            </div>

                            <button
                                class="btn"
                                @click="$router.push('/login')"
                            >
                                로그인 하기
                            </button>
                        </div>
                    </div>
                    <!-- FIXME: 아이디찾기 과정 두번째 END -->
                </div>
            </div>
            <!--TODO:END-->

            <footer-component />
        </section>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isPopupVisible"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="popupMessage"
            />
        </notice-popup-component>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
    },
    data() {
        return {
            isPopupVisible: false,
            isPhoneCertifyChecked: false,
            isTryPhoneCertifyCheck: false,
            popupMessage: "",
            findStep: 1,
            email: '',
            mobileVerifyCode: ""
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user"
        })
    },
    methods: {
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

                        if (!info.user_email) {
                            this.popupMessage = "가입된 사용자 정보가 없습니다";
                            this.isPopupVisible = true;
                            this.isTryPhoneCertifyCheck = false;
                            return;
                        }

                        this.email = info.user_email;

                        this.isTryPhoneCertifyCheck = false;
                        this.isPhoneCertifyChecked = true;

                        this.popupMessage = "휴대폰 실명인증이 완료되었습니다";
                        this.isPopupVisible = true;
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
        verifyComplete() {
            this.findStep = 2;
        }
    }
};
</script>

<style lang="scss">
</style>
