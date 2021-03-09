<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/register_agree"
            center="text"
            :centerText="__('register.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('register.label_1')}}</label>
                    <select
                        id="reg_country_sel"
                        class="w_address"
                        name="reg_country_sel"
                        v-model="country"
                        :disabled="mobileNumberAuthVerify"
                        @change="countrySelected"
                    >
                        <option value="0">{{__('register.select_country')}}</option>
                        <option value="kr">대한민국</option>
                        <option value="jp">日本</option>
                        <option value="ch">中國</option>
                        <option value="spain">España</option>
                        <option value="en">Other Country</option>
                    </select>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('register.label_2')}}</label>
                    <input
                        type="text"
                        :placeholder="__('register.placeholder_fullname')"
                        class="in_input"
                        id="user_name_inp"
                        name="fullname"
                        v-model="fullname"
                        @click="niceCheckAuthOpen"
                        :disabled="mobileNumberAuthVerify"
                    />

                    <!-- nice 정보평가 인증 -->
                    <form name="form_chk" method="post">
                        <input type="hidden" name="m" value="checkplusSerivce" />
                        <input type="hidden" name="EncodeData" :value="encData" />
                    </form>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr mb_3px">{{__('register.label_3')}}</label>
                    <p class="s_text">{{__('register.label_3_1')}}<br>※ If you select 'Other Country', please include your country code in front of your phone number to receive your verification code via SMS.</p>
                    <input
                        type="number"
                        name="phonenumber"
                        class="in_input"
                        placeholder="ex) 01012341234"
                        v-model="mobileNumber"
                        autocomplete="off"
                        :disabled="mobileNumberAuthVerify || mobileNumberAuthCodeSent"
                    />
                    
                    <div class="not_kr_certify not_kr" style>
                        <div class="certify_hd code_form">
                            <input
                                type="number"
                                id="sms_inp_code"
                                class="in_input"
                                autocomplete="off"
                                v-model="mobileNumberAuthCode"
                                :disabled="mobileNumberAuthVerify"
                            />
                            <p class="s_text">{{__('register.label_3_2')}}</p>
                            <span id="ViewTimer">{{countDownTime}}</span>
                            <button
                                type="button"
                                @click="certifyMobileNumberAuthCode"
                                :class="{active: mobileNumberAuthCodeSent}"
                            >{{mobileNumberAuthVerify ? __('register.auth_ok') : __('register.auth_do')}}</button>
                        </div>
                        <div class="certify_bd">
                            <button
                                type="button"
                                id="phone_certify"
                                @click="sendMobileNumberAuthCode"
                                :class="{active: !mobileNumberAuthVerify && (mobileNumber && country !== '0')}"
                                class="outline-btn"
                            >{{mobileNumberAuthCodeSent ? __('register.auth_sms_resend') : __('register.auth_sms_send')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr mb_3px">{{__('register.label_4')}}</label>
                    <p class="s_text">{{__('register.label_4_1')}}</p>
                    <div class="in_form_line max_form email_form">
                        <input
                            type="email"
                            id="email_id_inp"
                            name="email"
                            placeholder="ex) truston@truston.com"
                            v-model="email"
                            :disabled="emailAuthVerify"
                            autocomplete="off"
                            class="in_input"
                        />
                    </div>
                    <div class="not_kr_certify">
                        <div class="certify_hd code_form">
                            <input
                                type="text"
                                id="email_inp_code"
                                class="in_input"
                                autocomplete="off"
                                v-model="emailAuthCode"
                                :disabled="emailAuthVerify"
                            />
                            <p class="s_text">{{__('register.label_4_2')}}</p>
                            <button
                                type="button"
                                @click="certifyEmailAuthCode"
                                :class="{active: emailAuthCodeSent}"
                            >{{emailAuthVerify ? __('register.auth_ok') : __('register.auth_do')}}</button>
                        </div>
                        <div class="certify_bd">
                            <button
                                type="button"
                                id="email_certify"
                                :class="{active: !emailAuthVerify && email}"
                                @click="sendEmailAuthCode"
                                class="outline-btn"
                            >{{emailAuthCodeSent ? __('register.auth_email_resend') : __('register.auth_email_send')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr mb_3px">{{__('register.label_5')}}</label>
                    <p class="s_text">{{__('register.label_5_1')}}</p>
                    <input
                        type="password"
                        :placeholder="__('register.placeholder_password')"
                        id="password_inp"
                        name="password"
                        class="in_input"
                        v-model="password"
                    />
                    <div class="in_form_line pswrd">
                        <input
                            type="password"
                            :placeholder="__('register.placeholder_password_confirm')"
                            id="password_confirm_inp"
                            name="cpassword"
                            class="in_input"
                            v-model="passwordConfirm"
                        />
                        <div
                            v-if="password || passwordConfirm"
                            class="collect"
                            :class="[isPasswordSame ? 'ok' : 'no']"
                        >
                            <p v-if="isPasswordSame" class="ok">{{__('register.same')}}</p>
                            <p v-else class="no">{{__('register.not_same')}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('register.label_6')}}</label>
                    <input
                        type="password"
                        :placeholder="__('register.placeholder_secret_key')"
                        class="in_input"
                        readonly="readonly"
                        v-model="secretKey"
                        @click="showSecretKeyInputView('secretKey')"
                    />
                    <div class="in_form_line sqrty">
                        <input
                            type="password"
                            :placeholder="__('register.placeholder_secret_key_confirm')"
                            class="in_input"
                            readonly="readonly"
                            v-model="secretKeyConfirm"
                            @click="showSecretKeyInputView('secretKeyConfirm')"
                        />
                        <div
                            v-if="secretKey || secretKeyConfirm"
                            class="collect"
                            :class="[isSecretKeyCollect ? 'ok' : 'no']"
                        >
                            <p v-if="isSecretKeyCollect" class="ok">{{__('register.same')}}</p>
                            <p v-else class="no">{{__('register.not_same')}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ps_text">
                <label class="ps_title">{{__('register.warning')}}</label>
                <p class="ps_p">{{__('register.warning_1')}}</p>
            </div>
        </div>
        <footer-component
            :buttonText="__('register.ok')"
            v-on:buttonClick="okButtonClick"
            :active="isReadyToRegister"
        ></footer-component>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :iconSrc="systemNoticeIcon"
            :visible.sync="isPopupVisible"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";
import { clearInterval } from "timers";

export default {
    beforeRouteEnter(to, from, next) {
        
        if (
            from.path !== "/register_agree" &&
            from.path !== "/user_secret_key_input" &&
            from.path !== "/register_empty"
        ) {
            return next("/register_agree");
        }
        

        if (
            from.path !== "/user_secret_key_input" &&
            from.path !== "/register_empty"
        ) {
            return next(vm => {
                vm.$store.commit("updateRegisterViewData", {
                    country: "0"
                });
                vm.fetchState();
            });
        }
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent,
        "system-notice-component": SystemNoticeComponent
    },
    created() {
        if (this.$route.params.kind === "secretKey") {
            this.$store.commit("mergeRegisterViewData", {
                secretKey: this.$route.params.value
            });
        } else if (this.$route.params.kind === "secretKeyConfirm") {
            this.$store.commit("mergeRegisterViewData", {
                secretKeyConfirm: this.$route.params.value
            });
            window.scrollTo(0, document.body.scrollHeight);
        }
        this.fetchState();
    },
    destroyed() {
        window.clearInterval(this.countDownTimer);
    },
    mounted() {
        if (this.$route.params.kind) {
            const container = this.$el.querySelector(".trst-container");
            container.scrollTo(0, container.scrollHeight);
        }
        this.encData = document.head.querySelector(
            'meta[name="enc_data"]'
        ).content;
    },
    data() {
        return {
            isPopupVisible: false,
            systemNoticeIcon: '',
            encData: "",
            systemNoticeMessage: "",
            countDownTimer: null,
            countDownTime: "03:00",
            country: "0",
            fullname: "",
            mobileNumber: "",
            mobileNumberAuthCode: "",
            mobileNumberAuthCodeSent: false,
            mobileNumberAuthVerify: false,
            email: "",
            emailAuthCode: "",
            emailAuthCodeSent: false,
            emailAuthVerify: false,
            password: "",
            passwordConfirm: "",
            secretKey: "",
            secretKeyConfirm: ""
        };
    },
    computed: {
        isPasswordSame() {
            return (
                this.password &&
                this.passwordConfirm &&
                this.password === this.passwordConfirm
            );
        },
        isPasswordCollect() {
            return (
                this.password &&
                this.password.length >= 8 &&
                this.password.length <= 15
            );
        },
        isSecretKeyCollect() {
            return (
                this.secretKey &&
                this.secretKeyConfirm &&
                this.secretKey === this.secretKeyConfirm
            );
        },
        isReadyToRegister() {
            return (
                this.country &&
                this.fullname &&
                this.mobileNumber &&
                this.email &&
                this.isPasswordCollect &&
                this.isSecretKeyCollect &&
                this.emailAuthVerify &&
                this.mobileNumberAuthVerify
            );
        }
    },
    methods: {
        storeState() {
            this.$store.commit("updateRegisterViewData", {
                country: this.country,
                fullname: this.fullname,
                mobileNumber: this.mobileNumber,
                mobileNumberAuthVerify: this.mobileNumberAuthVerify,
                email: this.email,
                emailAuthVerify: this.emailAuthVerify,
                password: this.password,
                passwordConfirm: this.passwordConfirm,
                secretKey: this.secretKey,
                secretKeyConfirm: this.secretKeyConfirm
            });
        },
        fetchState() {
            this.country = this.$store.state.registerViewData.country;
            this.fullname = this.$store.state.registerViewData.fullname;
            this.mobileNumber = this.$store.state.registerViewData.mobileNumber;
            this.mobileNumberAuthVerify = this.$store.state.registerViewData.mobileNumberAuthVerify;
            this.email = this.$store.state.registerViewData.email;
            this.emailAuthVerify = this.$store.state.registerViewData.emailAuthVerify;
            this.password = this.$store.state.registerViewData.password;
            this.passwordConfirm = this.$store.state.registerViewData.passwordConfirm;
            this.secretKey = this.$store.state.registerViewData.secretKey;
            this.secretKeyConfirm = this.$store.state.registerViewData.secretKeyConfirm;
        },
        countrySelected(event) {
            /*
            if (event.target.value === "kr") {
                this.mobileNumber = "";
            }
            */
        },
        showSecretKeyInputView(kind) {
            this.storeState();
            this.$router.replace({
                name: "user_secret_key_input",
                params: {
                    backName: "register",
                    proceedName: "register",
                    kind: kind
                }
            });
        },
        niceCheckAuthOpen() {
            /*
            if (this.country === "kr" && !this.mobileNumberAuthVerify) {
                this.storeState();
                this.$router.replace("/register_empty");
            }
            */
        },
        async sendMobileNumberAuthCode() {
            if (this.country === "0") {
                this.systemNoticeMessage = this.__("register.notice_1");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
            } else if (!this.mobileNumber) {
                this.systemNoticeMessage = this.__("register.notice_2");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
            } else {
                if (!this.mobileNumberAuthVerify) {
                    try {
                        this.$store.commit("progressComponentShow");

                        await axios.post("/api/sms/verify/request", {
                            mobile_number: this.mobileNumber,
                            country: this.__("LANG")
                        });

                        this.countDownStart(3 * 60 * 1000, () => {
                            this.mobileNumberAuthCodeSent = false;
                        });

                        this.systemNoticeMessage = this.__("register.notice_13");
                        this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                        this.isPopupVisible = true;

                        this.mobileNumberAuthCodeSent = true;
                    } catch (e) {
                        this.systemNoticeMessage = this.__("register.error_1");
                        this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";

                        if (e.response) {
                            const response = e.response.data;

                            if (response.error === "already_exists") {
                                this.systemNoticeMessage = this.__(
                                    "register.error_6"
                                );
                            }
                        }
                        this.isPopupVisible = true;
                        this.mobileNumberAuthCodeSent = false;
                    } finally {
                        this.$store.commit("progressComponentHide");
                    }
                }
            }
        },
        async certifyMobileNumberAuthCode() {
            if (this.mobileNumberAuthCodeSent && !this.mobileNumberAuthVerify) {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.post("/api/sms/verify/certify", {
                        mobile_number: this.mobileNumber,
                        verify_code: this.mobileNumberAuthCode
                    });

                    this.systemNoticeMessage = this.__("register.notice_14");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                    this.isPopupVisible = true;

                    this.mobileNumberAuthVerify = true;
                    this.mobileNumberAuthCodeSent = false;
                    this.mobileNumberAuthCode = "";
                    this.countDownReset(3 * 60 * 1000);
                } catch (e) {
                    this.systemNoticeMessage = this.__("register.notice_3");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                    this.isPopupVisible = true;
                    this.mobileNumberAuthVerify = false;
                } finally {
                    this.$store.commit("progressComponentHide");
                }
            }
        },
        async sendEmailAuthCode() {
            if (!this.email) {
                this.systemNoticeMessage = this.__("register.notice_4");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
            } else {
                if (!this.emailAuthVerify) {
                    try {
                        this.$store.commit("progressComponentShow");

                        await axios.post("/api/email/verify/request", {
                            email: this.email,
                            country: this.__("LANG")
                        });

                        this.systemNoticeMessage = this.__("register.notice_15");
                        this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                        this.isPopupVisible = true;

                        this.emailAuthCodeSent = true;
                    } catch (e) {
                        this.systemNoticeMessage = this.__("register.error_1");
                        this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";

                        if (e.response) {
                            const response = e.response.data;

                            if (response.error === "invalid_format") {
                                this.systemNoticeMessage = this.__(
                                    "register.error_2"
                                );
                            } else if (response.error === "already_exists") {
                                this.systemNoticeMessage = this.__(
                                    "register.error_3"
                                );
                            }
                        }
                        this.emailAuthCodeSent = false;
                        this.isPopupVisible = true;
                    } finally {
                        this.$store.commit("progressComponentHide");
                    }
                }
            }
        },
        async certifyEmailAuthCode() {
            if (this.emailAuthCodeSent && !this.emailAuthVerify) {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.post("/api/email/verify/certify", {
                        email: this.email,
                        verify_code: this.emailAuthCode
                    });

                    this.systemNoticeMessage = this.__("register.notice_16");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                    this.isPopupVisible = true;

                    this.emailAuthVerify = true;
                    this.emailAuthCodeSent = false;
                    this.emailAuthCode = "";
                } catch (e) {
                    this.systemNoticeMessage = this.__("register.error_1");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";

                    if (e.response) {
                        const response = e.response.data;

                        if (response.error === "invalid_code") {
                            this.systemNoticeMessage = this.__(
                                "register.error_4"
                            );
                        } else if (response.error === "certify_not_exists") {
                            this.systemNoticeMessage = this.__(
                                "register.error_5"
                            );
                        }
                    }

                    this.emailAuthVerify = false;
                    this.isPopupVisible = true;
                } finally {
                    this.$store.commit("progressComponentHide");
                }
            }
        },
        countDownStart(time, callback) {
            if (this.countDownTimer !== null) {
                window.clearInterval(this.countDownTimer);
            }

            let duration = moment.duration(time, "milliseconds");
            this.countDownTime = moment
                .utc(duration.asMilliseconds())
                .format("mm:ss");

            this.countDownTimer = window.setInterval(() => {
                duration = moment.duration(duration - 1000, "milliseconds");
                if (duration.asMilliseconds() === 0) {
                    window.clearInterval(this.countDownTimer);
                    callback();
                }
                this.countDownTime = moment
                    .utc(duration.asMilliseconds())
                    .format("mm:ss");
            }, 1000);
        },
        countDownReset(time) {
            window.clearInterval(this.countDownTimer);
            let duration = moment.duration(time, "milliseconds");
            this.countDownTime = moment
                .utc(duration.asMilliseconds())
                .format("mm:ss");
            this.countDownTimer = null;
        },
        async okButtonClick() {
            if (this.country === "0") {
                this.systemNoticeMessage = this.__("register.notice_5");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
                return;
            }

            if (!this.fullname) {
                this.systemNoticeMessage = this.__("register.notice_6");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
                return;
            }

            if (!this.mobileNumberAuthVerify) {
                this.systemNoticeMessage = this.__("register.notice_7");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
                return;
            }

            if (!this.emailAuthVerify) {
                this.systemNoticeMessage = this.__("register.notice_8");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
                return;
            }

            if (!this.isPasswordCollect) {
                this.systemNoticeMessage = this.__("register.notice_9");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
                return;
            }

            if (!this.isPasswordSame) {
                this.systemNoticeMessage = this.__("register.notice_10");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
                return;
            }

            if (!this.secretKey) {
                this.systemNoticeMessage = this.__("register.notice_11");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
                return;
            }

            if (!this.isSecretKeyCollect) {
                this.systemNoticeMessage = this.__("register.notice_12");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isPopupVisible = true;
                return;
            }

            if (this.isReadyToRegister) {
                try {
                    this.$store.commit("progressComponentShow");

                    const response = await axios.post("/api/register", {
                        fullname: this.fullname,
                        email: this.email,
                        country: this.country,
                        mobile_number: this.mobileNumber,
                        password: this.password,
                        secret_key: this.secretKey
                    });

                    console.log(response.data);

                    const token = response.data.token;
                    localStorage.passportToken = token;
                    axios.defaults.headers.common[
                        "Authorization"
                    ] = `Bearer ${token}`;

                    this.$router.replace("/");
                } catch (e) {
                    this.systemNoticeMessage = this.__("register.error_1");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                    this.isPopupVisible = true;
                    this.$store.commit("progressComponentHide");
                }
            }
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
    padding-bottom: 3.315rem;
}

.trst-container .sd_box{
    margin: 10px 0 30px;
}

.sd_box .form_line .w_address {
    width: 100%;
    font-size: 0.95rem;
    border: 0;
    border-bottom: 1px solid #dcdcdc;
    padding: 5px 0;
    word-break: break-all;
    color: #505050;
    line-height: normal;
    background-color: white;
    font-weight: 300;
    outline: none;
    border-radius: 0;
}

.ai_wrapper .s_text {
    font-size: 11px;
    color: #A0AAAA;
    padding-top: 8px;
    padding: 7px 0 13px;
    font-weight: 200;
    letter-spacing: -0.2px;
    line-height: 1.5;
}

.not_kr_certify .certify_hd {
    position: relative;
    margin-top: 10px;
}

.not_kr_certify .certify_hd span {
    position: absolute;
    top: 10px;
    right: 87px;
    font-size: 14px;
    font-weight: 600;
}

.not_kr_certify .certify_bd button {
    width: 100%;
    padding: 12px 0;
    font-size: 13px;
    color: #b4b4b4;
    font-weight: 300;
    border: none;
    background: #dcdcdc;
}

.not_kr_certify .certify_bd button.active {
    color: #fff;
    background: #49d094;
}

.not_kr_certify .certify_bd button.outline-btn{
    border: 1px solid #19B4AA;
    background-color: white;
    color: #19B4AA;
    font-weight: 400;
}

.not_kr_certify .certify_bd button.outline-btn.active{
    background-color: #19B4AA;
    border-color: #19B4AA;
    color: white;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
}

.max_form {
    width: 100%;
    position: relative;
}

.not_kr_certify .certify_hd {
    position: relative;
}

.not_kr_certify .certify_hd .in_input{
    height: 2.35rem;
    padding: 0;
}

.not_kr_certify .certify_hd button {
    height: 37px;
    position: absolute;
    top: 0;
    right: 0;
    font-size: 12px;
    color: #BEC8C8;
    font-weight: 300;
    border: none;
    background: #EBF0F0;
    line-height: 37px;
    padding: 0px 15px;
}

.not_kr_certify .certify_hd button.active {
    color: #fff;
    background: #FFB400;
}

.max_form.email_form input,
.max_form .confrm_number {
    text-align: left;
    font-size: 14px;
    padding-left: 10px;
}

.max_form.email_form input {
    padding-right: 90px;
}

.max_form input {
    width: 100%;
    text-align: right;
    float: left;
    border: 0;
    font-size: 15px;
    padding-right: 37px;
    height: 30px;
    border-bottom: 1px solid #dcdedc;
}

.certify_hd.code_form input.w_address {
    padding-right: 130px;
}

.collect {
    width: 20px;
    height: 20px;
    border-radius: 50px;
    border: 1px solid transparent;
    position: absolute;
    right: 10px;
    bottom: 7px;
}

.collect:after {
    left: 6.2px;
    top: 3px;
    width: 6px;
    height: 9px;
    border: solid transparent;
    border-width: 0 2.5px 2.5px 0;
    transform: rotate(45deg);
    content: "";
    position: absolute;
}

.collect.ok:after {
    border: solid #0072ff;
    border-width: 0 2px 2px 0;
}

.collect.no:after {
    border: solid #e60000;
    border-width: 0 2.5px 2.5px 0;
}

.collect p {
    position: absolute;
    left: -40px;
    width: 35px;
    font-size: 12px;
    display: block;
    top: 3px;
}

.collect.ok {
    border: 1px solid #0072ff;
}

.collect.no {
    border: 1px solid #e60000;
}

.collect p.ok {
    color: #0072ff;
    left: -30px;
}

.collect p.no {
    color: #e60000;
}
</style>
