<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <!-- FIXME: 회원가입 과정 첫번째 -->
                <div
                    class="sub-page-auth sub-page-auth-join__form"
                    :style="{display: registerStep === 1 ? 'block' : 'none'}"
                >
                    <div class="sub-page-auth-title">
                        <h2>회원가입</h2>
                        <span>제이원비츠 신규 회원가입</span>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div class="in-title in-title-mini_ver">
                            <h3 class="in-title-name">
                                이메일 아이디
                            </h3>
                        </div>

                        <div class="auth-input_form _mail">
                            <input
                                v-model="emailId"
                                type="text"
                                placeholder="아이디"
                                :disabled="isEmailDuplicateChecked"
                            >
                            <span>@</span>
                            <div class="domain_grouping">
                                <input
                                    v-model="emailDomainInput"
                                    type="text"
                                    class="direct_input"
                                    :style="{visibility: isEmailDomainDirectInput ? 'visible' : 'hidden'}"
                                    :disabled="isEmailDuplicateChecked"
                                    placeholder="직접 입력"
                                >
                                <select
                                    v-model="emailDomainSelect"
                                    name="domain"
                                    class="select-style"
                                    :style="{width: isEmailDomainDirectInput ? '15%' : '100%'}"
                                    :disabled="isEmailDuplicateChecked"
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
                            <button
                                type="button"
                                class="btn btn-certify-mini"
                                :class="{active: isEmailInputed}"
                                :disabled="!isEmailInputed"
                                @click="emailDuplicateCheck"
                            >
                                {{ !isEmailDuplicateChecked ? '중복검사' : '수정하기' }}
                            </button>
                        </div>

                        <div class="in-title in-title-mini_ver">
                            <h3 class="in-title-name">
                                비밀번호
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

                        <div class="in-title in-title-mini_ver">
                            <h3 class="in-title-name">
                                닉네임
                            </h3>
                        </div>

                        <div class="auth-input_form">
                            <input
                                v-model="nickname"
                                type="text"
                                placeholder="사용하실 닉네임을 입력하세요."
                            >
                        </div>

                        <div class="in-title in-title-mini_ver">
                            <h3 class="in-title-name">
                                휴대폰 실명인증
                            </h3>
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

                        <div class="agree-align">
                            <label class="_label">
                                <input
                                    id="check-1month-free-ok"
                                    v-model="checkFreeMonthForNew"
                                    type="checkbox"
                                    class="input-style-01"
                                >
                                <label for="check-1month-free-ok"><span class="none">1개월무료스트리밍 이용 동의</span></label>
                                <span class="highlight">신규회원 1개월 무료 스트리밍 이용권을 받습니다.</span>
                            </label>
                        </div>

                        <div class="agree-align">
                            <label class="_label">
                                <input
                                    id="check-all-agree"
                                    :checked="isAgreeAll"
                                    type="checkbox"
                                    class="input-style-01"
                                    @change="toggleAgreeAll"
                                >
                                <label for="check-all-agree"><span class="none">모든 약관에 동의</span></label>
                                <span>모든 약관에 동의 합니다.</span>
                            </label>
                        </div>

                        <ul class="access-terms">
                            <li>
                                <label class="_label">
                                    <input
                                        id="check-this-agree1"
                                        v-model="agree1"
                                        type="checkbox"
                                        class="input-style-01"
                                    >
                                    <label for="check-this-agree1"><span class="none">첫번째 이용약관에 동의</span></label>
                                    <b>(필수)</b><span>만 14세 이상입니다.</span>
                                </label>
                            </li>
                            <li>
                                <label class="_label">
                                    <input
                                        id="check-this-agree2"
                                        v-model="agree2"
                                        type="checkbox"
                                        class="input-style-01"
                                    >
                                    <label for="check-this-agree2"><span class="none">두번째 이용약관에 동의</span></label>
                                    <b>(필수)</b><span>서비스 이용약관</span>
                                </label>
                                <span
                                    class="more"
                                    @click="showTerm('terms_service')"
                                >전문보기</span>
                            </li>
                            <li>
                                <label class="_label">
                                    <input
                                        id="check-this-agree3"
                                        v-model="agree3"
                                        type="checkbox"
                                        class="input-style-01"
                                    >
                                    <label for="check-this-agree3"><span class="none">세번째 이용약관에 동의</span></label>
                                    <b>(필수)</b><span>유료서비스 이용약관</span>
                                </label>
                                <span
                                    class="more"
                                    @click="showTerm('pay_terms_service')"
                                >전문보기</span>
                            </li>
                            <li>
                                <label class="_label">
                                    <input
                                        id="check-this-agree4"
                                        v-model="agree4"
                                        type="checkbox"
                                        class="input-style-01"
                                    >
                                    <label for="check-this-agree4"><span class="none">네번째 이용약관에 동의</span></label>
                                    <b>(필수)</b><span>개인정보처리방침</span>
                                </label>
                                <span
                                    class="more"
                                    @click="showTerm('privacy_policy')"
                                >전문보기</span>
                            </li>
                            <li>
                                <label class="_label">
                                    <input
                                        id="check-this-agree5"
                                        v-model="checkInfoEmailSend"
                                        type="checkbox"
                                        class="input-style-01"
                                    >
                                    <label for="check-this-agree5"><span class="none">다섯번째 이용약관에 동의</span></label>
                                    <b>(선택)</b><span>이메일을 통해 다양한 이벤트, 프로모션, 광고를 받아보겠습니다.</span>
                                </label>
                            </li>
                        </ul>

                        <button
                            type="button"
                            class="btn btn-complete"
                            :class="{active: isReadyToRegister}"
                            @click="registerCheck"
                        >
                            다음단계로
                        </button>
                    </div>
                </div>
                <!-- FIXME: 회원가입 과정 첫번째 END -->

                <!-- FIXME: 회원가입 과정 두번째 (분위기 선택 추가) -->
                <div
                    class="sub-page-auth sub-page-auth-join__mood-choice"
                    :style="{display: registerStep === 2 ? 'block' : 'none'}"
                >
                    <div class="sub-page-auth-title">
                        <h2>회원가입</h2>
                        <span>아티스트 회원가입</span>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div class="mood-choice-container">
                            <h4 class="con_tit">
                                원하시는 비트의 <b>분위기</b>를 선택해주세요.<span>(최대 3개 선택 가능)</span>
                            </h4>

                            <div
                                id="mood-select"
                                class="mood-choice-slider"
                                data-mcs-theme="minimal"
                            >
                                <ul class="mood-choice__group">
                                    <li
                                        v-for="(mood, index) in moodItems"
                                        :key="mood.mood_id"
                                        class="mood-choice__list"
                                        :class="{active: mood.checked}"
                                        @click="toggleSelectedMood(mood)"
                                    >
                                        <div
                                            class="_bg"
                                            :style="{
                                                'background-image': `url(img/imgs/mood_${padPrefix(
                                                    index + 1,
                                                    2,
                                                    '0'
                                                )}.jpg)`
                                            }"
                                        >
                                            <span>{{ mood.mood_title }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="btn_group">
                            <button
                                class="btn"
                                @click="register"
                            >
                                회원가입 완료
                            </button>
                        </div>
                    </div>
                </div>
                <!-- FIXME: 회원가입 과정 두번째 (분위기 선택 추가) END -->

                <!-- FIXME: 회원가입 과정 세번째 -->
                <div
                    class="sub-page-auth sub-page-auth-join__complete"
                    :style="{display: registerStep === 3 ? 'block' : 'none'}"
                >
                    <div class="sub-page-auth-title">
                        <h2>회원가입</h2>
                        <span>제이원비츠 신규 회원가입</span>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div class="certify-good">
                            <img
                                src="/img/icon/certify-good.svg"
                                alt="certify complete"
                            >
                            <p><b>회원가입</b>이 완료되었습니다.</p>

                            <span>신규회원 1달 무료 스트리밍 이용권 제공에 동의하신 회원님은,<br><b>마이페이지>이용권관리</b>에서 확인할 수 있습니다.</span>
                            <br>
                        </div>

                        <div class="member-mail-guide">
                            <span>제이원비츠 회원아이디</span>
                            <p>{{ userEmail }}</p>
                        </div>

                        <span><br>로그인 하면 이메일 인증을 위한 인증메일이 발송됩니다.</span>

                        <button
                            class="btn"
                            @click="$router.push('/login', () => {})"
                        >
                            로그인 하기
                        </button>
                    </div>
                </div>
                <!-- FIXME: 회원가입 과정 세번째 END -->
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

        <terms-and-conditions-info-popup
            :visible.sync="isTacPopupVisible"
        >
            <!-- eslint-disable-next-line vue/no-v-html -->
            <div v-html="termText" />
        </terms-and-conditions-info-popup>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import TermsAndConditionsInfoPopup from "../components/common/TermsAndConditionsInfoPopup";
import { padPrefix, checkPassword } from "../lib/common";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
        TermsAndConditionsInfoPopup
    },
    data() {
        return {
            isPopupVisible: false,
            isTacPopupVisible: false,
            isEmailDuplicateChecked: false,
            isTryEmailDuplicateCheck: false,
            isPhoneCertifyChecked: false,
            isTryPhoneCertifyCheck: false,
            isEmailDomainDirectInput: false,
            isTryRegister: false,
            popupMessage: "",
            registerStep: 1,
            emailId: "",
            emailDomainInput: "",
            emailDomainSelect: "",
            mobileVerifyCode: "",
            password: "",
            confirm: "",
            nickname: "",
            username: "",
            phone: "",
            checkFreeMonthForNew: false,
            checkInfoEmailSend: false,
            agree1: false,
            agree2: false,
            agree3: false,
            agree4: false,
            agree5: false,
            moodItems: [],
            genreItems: [],
            terms: {},
            termText: ""
        };
    },
    computed: {
        ...mapState({
            moods: "moods",
            genres: "genres"
        }),
        isEmailInputed() {
            return this.emailId && this.emailDomain ? true : false;
        },
        isPasswordSame() {
            return (
                this.password && this.confirm && this.password === this.confirm
            );
        },
        isAgreeAll() {
            return (
                this.agree1 &&
                this.agree2 &&
                this.agree3 &&
                this.agree4 &&
                this.agree5
            );
        },
        isAgreeMustAll() {
            return this.agree1 && this.agree2 && this.agree3 && this.agree4;
        },
        isReadyToRegister() {
            return (
                this.isEmailInputed &&
                this.isPasswordSame &&
                checkPassword(this.password) > 0 &&
                this.nickname !== "" &&
                this.isPhoneCertifyChecked &&
                this.isEmailDuplicateChecked &&
                this.isAgreeMustAll
            );
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
        },
        selectedMoods() {
            return this.moodItems.filter(mood => {
                return mood.checked;
            });
        }
    },
    watch: {
        moods() {
            this.updateMoodItems();
        },
        genres() {
            this.updateGenreItems();
        }
    },
    async created() {
        if (this.moods.length === 0) {
            await this.$http.get(`/api/moods`).then(response => {
                if (this.moods.length === 0 && response.data.length !== 0) {
                    this.$store.commit("updateMoods", response.data);
                }
            });
        }
        this.updateMoodItems();

        if (this.genres.length === 0) {
            await this.$http.get(`/api/categorys`).then(response => {
                if (this.genres.length === 0 && response.data.length !== 0) {
                    this.$store.commit("updateGenres", response.data);
                }
            });
        }
        this.updateGenreItems();

        this.terms = await this.$http
            .get(`/api/terms`)
            .then(response => response.data[0]);
    },
    mounted() {
        this.$nextTick(() => {
            this.$RENDER_JQUERY_mCustomScrollbar("#mood-select", "minimal");
        });
    },
    methods: {
        padPrefix,
        emailDomainSelectChanged() {
            if (this.emailDomain === " ") {
                this.isEmailDomainDirectInput = true;
                this.emailDomainSelect = "none";
                this.emailDomainInput = "";
            } else {
                this.isEmailDomainDirectInput = false;
            }
        },
        toggleAgreeAll() {
            if (!this.isAgreeAll) {
                this.agree1 = true;
                this.agree2 = true;
                this.agree3 = true;
                this.agree4 = true;
                this.checkInfoEmailSend = true;
            } else {
                this.agree1 = false;
                this.agree2 = false;
                this.agree3 = false;
                this.agree4 = false;
                this.checkInfoEmailSend = false;
            }
        },
        async emailDuplicateCheck() {
            if (this.isEmailDuplicateChecked) {
                this.isEmailDuplicateChecked = false;
            } else {
                if (this.isTryEmailDuplicateCheck) {
                    return;
                }

                try {
                    this.isTryEmailDuplicateCheck = true;

                    await this.$http.post("/api/email/check/duplicate", {
                        user_email: this.userEmail
                    });

                    this.isEmailDuplicateChecked = true;
                    this.popupMessage = "사용가능한 이메일입니다";
                    this.isPopupVisible = true;
                } catch (e) {
                    this.isEmailDuplicateChecked = false;
                    this.popupMessage = "중복된 이메일입니다";
                    this.isPopupVisible = true;
                    console.log(e);
                } finally {
                    this.isTryEmailDuplicateCheck = false;
                }
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

                        if (info.user_email) {
                            this.popupMessage = "이미 사용중인 번호입니다";
                            this.isPopupVisible = true;
                            this.isTryPhoneCertifyCheck = false;
                            return;
                        }

                        this.username = info.real_name;
                        this.phone = info.mobile_no;

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
        registerCheck() {
            if (!this.emailId) {
                this.popupMessage = "아이디를 입력하세요";
                this.isPopupVisible = true;
                return;
            }

            if (!this.emailDomain) {
                this.popupMessage = "도메인을 입력하세요";
                this.isPopupVisible = true;
                return;
            }

            if (!this.isEmailDuplicateChecked) {
                this.popupMessage = "이메일 중복체크가 필요합니다";
                this.isPopupVisible = true;
                return;
            }

            if (!this.password) {
                this.popupMessage = "비밀번호를 입력하세요";
                this.isPopupVisible = true;
                return;
            }

            if (!this.confirm) {
                this.popupMessage = "비밀번호 확인을 입력하세요";
                this.isPopupVisible = true;
                return;
            }

            if (!this.isPasswordSame) {
                this.popupMessage = "비밀번호 확인이 일치하지 않습니다";
                this.isPopupVisible = true;
                return;
            }

            if (checkPassword(this.password) === -1) {
                this.popupMessage =
                    "비밀번호는 6자리 ~ 20자리 이내로 입력해주세요";
                this.isPopupVisible = true;
                return;
            }

            if (checkPassword(this.password) === -2) {
                this.popupMessage = "비밀번호는 공백없이 입력해주세요";
                this.isPopupVisible = true;
                return;
            }

            if (checkPassword(this.password) === -3) {
                this.popupMessage =
                    "비밀번호는 6~20자 영문 대소문자, 숫자, 특수문자 중 2가지 이상을 조합하여 입력해주세요";
                this.isPopupVisible = true;
                return;
            }

            if (!this.nickname) {
                this.popupMessage = "닉네임을 입력하세요";
                this.isPopupVisible = true;
                return;
            }

            if (!this.isPhoneCertifyChecked) {
                this.popupMessage = "휴대폰 실명인증이 필요합니다";
                this.isPopupVisible = true;
                return;
            }

            if (!this.isAgreeMustAll) {
                this.popupMessage = "모든 필수 이용약관에 동의하셔야 합니다";
                this.isPopupVisible = true;
                return;
            }

            this.registerStep = 2;
        },
        async register() {
            if (this.isTryRegister) {
                return;
            }

            try {
                this.isTryRegister = true;
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.post("/api/register", {
                    user_email: this.userEmail,
                    user_pw: this.password,
                    user_nick: this.nickname,
                    user_name: this.username, // 실명인증 후 받아옴
                    user_mobile: this.phone, // 실명인증 후 받아옴
                    verify_code: this.mobileVerifyCode, // 실명인증 시 생성
                    type: this.selectType,
                    check_free_month_for_new: this.checkFreeMonthForNew,
                    check_info_email_send: this.checkInfoEmailSend,
                    mood_s_selects: this.selectedMoods.map(mood => mood.mood_id)
                });

                this.registerStep = 3;
            } catch (e) {
                this.popupMessage = "회원가입 중 오류가 발생했습니다";
                this.isPopupVisible = true;
                this.registerStep = 1;
                console.log(e);
            } finally {
                this.isTryRegister = false;
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        updateMoodItems() {
            this.moodItems = this.moods.map(mood => {
                return { ...mood, ...{ checked: false } };
            });
        },
        updateGenreItems() {
            this.genreItems = this.genres.map(genre => {
                return { ...genre, ...{ checked: false } };
            });
        },
        toggleSelectedMood(selectedMood) {
            if (!selectedMood.checked) {
                if (this.selectedMoods.length < 3) {
                    selectedMood.checked = true;
                }
            } else {
                selectedMood.checked = false;
            }
        },
        showTerm(termName) {
            this.termText = this.terms[termName];
            this.isTacPopupVisible = true;
        }
    }
};
</script>

<style lang="scss">
</style>
