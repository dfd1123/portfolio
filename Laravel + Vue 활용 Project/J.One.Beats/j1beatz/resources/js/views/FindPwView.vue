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

                    <!-- FIXME: 비밀번호찾기 과정 첫번째 -->
                    <div
                        class="sub-page-auth-find_pw__before"
                    >
                        <div class="sub-page-auth-panel">
                            <div class="in-title in-title-mini_ver">
                                <h3 class="in-title-name">
                                    이메일 아이디
                                </h3>
                                <span class="in-title-ment">회원가입 시, 인증받은 이메일 아이디를 입력해주세요.</span>
                            </div>

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
                                        <option value="aicdss.com">
                                            aicdss.com
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

                            <button
                                type="button"
                                :class="{active: emailId && emailDomain}"
                                class="btn btn-complete"
                                @click="verifyEmailSend"
                            >
                                인증메일 {{ isVerifyEmailSent ? '재전송' : '전송' }}
                            </button>
                        </div>
                    </div>
                    <!-- FIXME: 비밀번호찾기 과정 첫번째 END -->
                </div>
            </div>

            <footer-component />
        </section>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isNoticePopupVisible"
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

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent
    },
    data() {
        return {
            isEmailDomainDirectInput: false,
            isVerifyEmailSent: false,
            isNoticePopupVisible: false,
            noticePopupMessage: "",
            emailId: "",
            emailDomainSelect: "",
            emailDomainInput: ""
        };
    },
    computed: {
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
        async verifyEmailSend() {
            if (!this.emailId) {
                this.noticePopupMessage = "아이디를 입력하세요";
                this.isNoticePopupVisible = true;
                return;
            }

            if (!this.emailDomain) {
                this.noticePopupMessage = "도메인을 입력하세요";
                this.isNoticePopupVisible = true;
                return;
            }

            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.post("/api/password/find/request", {
                    user_email: this.userEmail
                });

                this.isVerifyEmailSent = true;
                this.noticePopupMessage =
                    "비밀번호 찾기 인증 이메일이 전송되었습니다. <br> 이메일 인증을 완료해주시기 바랍니다";
                this.isNoticePopupVisible = true;
            } catch (e) {
                console.log(e.response);
                if (e.response && e.response.status == 422) {
                    this.noticePopupMessage =
                        "이메일이 잘못되었거나 없는 이메일입니다. <br> 확인 후 다시 입력해주시기 바랍니다";
                    this.isNoticePopupVisible = true;
                    return;
                }

                this.noticePopupMessage =
                    "비밀번호 찾기 과정 중 오류가 발생했습니다";
                this.isNoticePopupVisible = true;
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
</style>
