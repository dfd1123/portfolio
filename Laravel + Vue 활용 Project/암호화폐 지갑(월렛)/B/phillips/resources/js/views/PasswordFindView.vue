<template>
    <div class="ai_wrapper top_0">
        <header-component
            center="text"
            centerText="비밀번호찾기"
            rightButton="home"
            leftButton="back"
            leftButtonRoute="/login"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="icon_box top_5vh">
                <span class="ment_1">
                    <strong>이메일 인증</strong>
                </span>
                <span class="ment_2"></span>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('register.label_4')}}</label>
                    <div class="in_form_line max_form email_form">
                        <input
                            type="email"
                            id="email_id_inp"
                            name="email"
                            placeholder="ex) phillips@gmail.com"
                            v-model="email"
                            :disabled="emailAuthVerify"
                            autocomplete="off"
                        />
                    </div>
                    <p class="s_text">회원가입 시, 인증받은 이메일 아이디를 입력하세요</p>
                    <div class="not_kr_certify">
                        <div class="certify_hd code_form">
                            <input
                                type="text"
                                id="email_inp_code"
                                class="w_address"
                                autocomplete="off"
                                v-model="emailAuthCode"
                                :disabled="emailAuthVerify"
                            />
                            <p class="s_text">{{__('register.label_4_2')}}</p>
                            <button
                                type="button"
                                :class="{active: emailAuthCodeSent}"
                                @click="certifyEmailAuthCode"
                            >{{emailAuthVerify ? __('register.auth_ok') : __('register.auth_do')}}</button>
                        </div>
                        <div class="certify_bd">
                            <button
                                type="button"
                                id="email_certify"
                                :class="{active: !emailAuthVerify && email}"
                                @click="sendEmailAuthCode"
                            >{{emailAuthCodeSent ? __('register.auth_email_resend') : __('register.auth_email_send')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--
            <footer-component
                id="footer"
                buttonText="홈으로"
                @buttonClick="$router.replace('/home')"
                style="position: fixed; bottom: 8%;"
            ></footer-component>
            -->
        </div>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
            :iconSrc="systemNoticeIcon"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";

export default {
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent,
        "system-notice-component": SystemNoticeComponent
    },
    data() {
        return {
            isPopupVisible: false,
            isSuccess: false,
            systemNoticeMessage: "",
            systemNoticeIcon: "",
            email: "",
            emailAuthCode: "",
            emailAuthCodeSent: false,
            emailAuthVerify: false
        };
    },
    computed: {},
    methods: {
        async sendEmailAuthCode() {
            if (!this.email) {
                this.systemNoticeMessage = this.__("register.notice_4");
                this.systemNoticeIcon = "/images/x_icon.svg";
                this.isPopupVisible = true;
            } else {
                if (!this.emailAuthVerify) {
                    try {
                        this.$store.commit("progressComponentShow");

                        await axios.post("/api/password/find/request", {
                            email: this.email,
                            country: this.__("LANG")
                        });

                        this.systemNoticeMessage = this.__(
                            "register.notice_15"
                        );
                        this.systemNoticeIcon = "/images/checkok_icon.svg";
                        this.isPopupVisible = true;

                        this.emailAuthCodeSent = true;
                    } catch (e) {
                        this.systemNoticeMessage = this.__("register.error_1");
                        this.systemNoticeIcon = "/images/x_icon.svg";

                        if (e.response) {
                            const response = e.response.data;

                            if (response.error === "invalid_format") {
                                this.systemNoticeMessage = this.__(
                                    "register.error_2"
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

                    const response = await axios.post(
                        "/api/password/find/certify",
                        {
                            email: this.email,
                            verify_code: this.emailAuthCode
                        }
                    );

                    const token = response.data.token;

                    localStorage.passportToken = token;
                    axios.defaults.headers.common[
                        "Authorization"
                    ] = `Bearer ${token}`;

                    this.$router.replace({ path: "/user_password_change" });
                } catch (e) {
                    this.systemNoticeMessage = this.__("register.error_1");
                    this.systemNoticeIcon = "/images/x_icon.svg";

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
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 45px;
    margin-bottom: -53px;
}

.ai_wrapper.bottom_0 {
    padding-bottom: 0;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: black;
    height: 100%;
}

.icon_box {
    position: relative;
    text-align: center;
    height: auto;
    margin-bottom: 7px;
}

.icon_box.top_5vh {
    top: 4vh;
}

.state_img {
    width: 100%;
    display: inline-block;
}

.state_img img {
    width: 62px;
}

.ment_1 {
    font-size: 15px;
    color: #5a5a5a;
    padding: 10px 0;
    font-weight: 700;
    display: inline-block;
}

.ment_1 strong {
    color: #2E87C8;
}

.ment_2 {
    font-size: 12px;
    color: #8b8b8b;
    width: 100%;
    display: inline-block;
}

.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0px 5px 20px rgba(0, 69, 191, 0.2);
    margin: 0 auto;
    position: relative;
    top: 2vh;
    padding: 15px 15px;
    margin-bottom: 15px;
}

.ai_wrapper .sd_box.sending,
.ai_wrapper .buying {
    top: 7.5vh;
    padding: 0;
}

.info_table {
    width: 100%;
}

.info_table tr {
    width: 100%;
    padding: 20px 0;
    display: inline-block;
    border-bottom: 1px solid #dfdfdf;
}

.info_table tr:last-child {
    border-bottom: 0px solid #dfdfdf;
}

.ai_wrapper .sd_box.sending .form_line .info_table tr .label_td,
.ai_wrapper .buying .form_line .info_table .label_td {
    padding-left: 15px;
    padding-right: 15px;
    font-size: 14px;
    color: #2E87C8;
    font-weight: 600;
    letter-spacing: -1.5px;
}

.send_value {
    font-weight: 600;
}

.send_value .unit {
    color: #2E87C8;
    font-weight: 600;
}

.td_2 {
    color: #5a5a5a;
    font-size: 13px;
    word-break: break-all;
    line-height: 20px;
    padding-right: 10px;
    text-align: left;
}

.ai_wrapper .sd_box.sending .form_line .info_table tr:last-child .label_td {
    padding-left: 15px;
    padding-right: 15px;
    white-space: nowrap;
}

.td_2 {
    color: #5a5a5a;
    font-size: 13px;
    word-break: break-all;
    line-height: 20px;
    padding-right: 10px;
    text-align: left;
}


.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    background-color: white;
    border-radius: 5px;
    margin: 0 auto;
    position: relative;
    top: 2vh;
    padding: 15px 15px;
    margin-bottom: 15px;
    overflow:hidden;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.sd_box .form_line .label_hr {
    width: 100%;
    display: inline-block;
    margin-bottom: 5px;
    color: #2E87C8;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.sd_box .form_line .w_address {
    width: 100%;
    font-size: 14px;
    border: 0;
    border-bottom: 1px solid #dcdcdc;
    padding: 5px 10px;
    word-break: break-all;
    color: #5a5a5a;
    line-height: 25px;
    background-color: white;
}

.w_address {
    border-radius: 0;
}

.ai_wrapper .s_text {
    font-size: 10px;
    color: #b4b4b4;
    padding-top: 8px;
}

.not_kr_certify .certify_hd {
    position: relative;
}

.not_kr_certify .certify_hd span {
    position: absolute;
    top: 13px;
    right: 87px;
    font-size: 14px;
    font-weight: 600;
}

.not_kr_certify .certify_hd button {
    height: 30px;
    position: absolute;
    top: 6px;
    right: 0;
    font-size: 12px;
    color: #b4b4b4;
    font-weight: 600;
    border: none;
    background: #dcdcdc;
    line-height: 30px;
    padding: 0 15px;
}

.not_kr_certify .certify_hd button.active {
    color: #fff;
    background: #2E87C8;
}

.not_kr_certify .certify_bd {
    margin-bottom: -15px;
    margin-left: -15px;
    margin-right: -15px;
    margin-top: 13px;
}

.not_kr_certify .certify_bd button {
    width: 100%;
    padding: 12px 0;
    font-size: 13px;
    color: #b4b4b4;
    font-weight: 600;
    border: none;
    background: #dcdcdc;
}

.not_kr_certify .certify_bd button.active {
    color: #fff;
    background: #2E87C8;
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

.ai_wrapper .ps_text {
    width: 90%;
    margin: 0 auto;
    margin-top: 8%;
    padding: 5px 5px;
}

.ai_wrapper .ps_text .ps_title {
    width: 100%;
    font-weight: 600;
    color: #2E87C8;
    font-size: 12px;
}

.ai_wrapper .ps_text .ps_p {
    width: 100%;
    font-weight: 600;
    color: #ddd;
    font-size: 11px;
    line-height: 18px;
    letter-spacing: -1px;
}

.ai_wrapper .ps_text .ps_p:nth-child(2) {
    margin-top: 3%;
}

</style>
