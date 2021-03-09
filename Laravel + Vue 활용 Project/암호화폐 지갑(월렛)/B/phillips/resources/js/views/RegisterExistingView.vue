<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/register_kind"
            center="text"
            :centerText="__('register_existing.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('register_existing.label_1')}}</label>
                    <div class="register_fam_logo">
                        <img src="/images/cointouse_icon.svg" style="width:140px;" />
                    </div>
                    <input
                        type="text"
                        :placeholder="__('register_existing.placeholder_email')"
                        class="w_address"
                        required="required"
                        autocomplete="off"
                        v-model="email"
                    />
                    <div class="in_form_line sqrty">
                        <input
                            type="password"
                            :placeholder="__('register_existing.placeholder_password')"
                            required="required"
                            class="w_address"
                            v-model="password"
                        />
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('register_existing.secret_key')}}</label>
                    <input
                        type="password"
                        :placeholder="__('register_existing.placeholder_secret_key')"
                        class="w_address"
                        readonly="readonly"
                        required="required"
                        v-model="secretKey"
                        @click="showSecretKeyInputView('secretKey')"
                    />
                    <div class="in_form_line sqrty">
                        <input
                            type="password"
                            :placeholder="__('register_existing.placeholder_secret_key_confirm')"
                            class="w_address top_13"
                            readonly="readonly"
                            required="required"
                            v-model="secretKeyConfirm"
                            @click="showSecretKeyInputView('secretKeyConfirm')"
                        />
                        <div
                            v-if="secretKey || secretKeyConfirm"
                            class="collect"
                            :class="[isSecretKeyCollect ? 'ok' : 'no']"
                        >
                            <p v-if="isSecretKeyCollect" class="ok">{{__('register_existing.same')}}</p>
                            <p v-else class="no">{{__('register_existing.not_same')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer-component
            :buttonText="__('register_existing.link')"
            v-on:buttonClick="okButtonClick"
            :active="isAllRegisterInfoFilled"
        ></footer-component>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (
            from.path !== "/register_agree" &&
            from.path !== "/user_secret_key_input"
        ) {
            return next("/register_kind");
        }
        if (to.path !== "/user_secret_key_input") {
            return next(vm => {
                vm.$store.commit("updateRegisterExistingViewData", {});
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
            this.secretKey = this.$route.params.value;
        } else if (this.$route.params.kind === "secretKeyConfirm") {
            this.secretKeyConfirm = this.$route.params.value;
        }
    },
    data() {
        return {
            isPopupVisible: false,
            systemNoticeMessage: "",
            email: this.$store.state.registerExistingViewData.email || "",
            password: this.$store.state.registerExistingViewData.password || "",
            secretKey:
                this.$store.state.registerExistingViewData.secretKey || "",
            secretKeyConfirm:
                this.$store.state.registerExistingViewData.secretKeyConfirm ||
                ""
        };
    },
    computed: {
        isSecretKeyCollect() {
            return (
                this.secretKey &&
                this.secretKeyConfirm &&
                this.secretKey === this.secretKeyConfirm
            );
        },
        isAllRegisterInfoFilled() {
            return this.email && this.password && this.isSecretKeyCollect;
        }
    },
    methods: {
        showSecretKeyInputView(kind) {
            this.$store.commit("updateRegisterExistingViewData", {
                email: this.email,
                password: this.password,
                secretKey: this.secretKey,
                secretKeyConfirm: this.secretKeyConfirm
            });
            this.$router.replace({
                name: "user_secret_key_input",
                params: {
                    backName: "register_existing",
                    proceedName: "register_existing",
                    kind: kind
                }
            });
        },
        async okButtonClick() {
            if (!this.email) {
                this.systemNoticeMessage = this.__(
                    "register_existing.require_1"
                );
                this.isPopupVisible = true;
                return;
            }

            if (!this.password) {
                this.systemNoticeMessage = this.__(
                    "register_existing.require_2"
                );
                this.isPopupVisible = true;
                return;
            }

            if (!this.isSecretKeyCollect) {
                this.systemNoticeMessage = this.__(
                    "register_existing.require_3"
                );
                this.isPopupVisible = true;
                return;
            }

            if (this.isAllRegisterInfoFilled) {
                try {
                    this.$store.commit("progressComponentShow");

                    const response = await axios.post(
                        "/api/register_existing",
                        {
                            email: this.email,
                            password: this.password,
                            secret_key: this.secretKey
                        }
                    );

                    const token = response.data.token;
                    localStorage.passportToken = token;
                    axios.defaults.headers.common[
                        "Authorization"
                    ] = `Bearer ${token}`;

                    this.$router.replace("/");
                } catch (e) {
                    this.$store.commit("progressComponentHide");
                    this.systemNoticeMessage = this.__(
                        "register_existing.warning_1"
                    );
                    this.isPopupVisible = true;
                } finally {
                    this.$store.commit("updateRegisterExistingViewData", {});
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
    padding-top: 43px;
    margin-bottom: -53px;
    padding-bottom: 52px;
}

.ai_wrapper.top_0 {
    padding-top: 45px;
}

.scroll_area {
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: #fafafa;
    height: 100%;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
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

.sd_box .form_line .label_hr {
    width: 100%;
    display: inline-block;
    margin-bottom: 5px;
    color: #0747ad;
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
}

.w_address {
    border-radius: 0;
}

.ai_wrapper .s_text {
    font-size: 10px;
    color: #b4b4b4;
    padding-top: 8px;
}

.register_fam_logo {
    text-align: center;
    padding: 19px 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
}

element.style {
    width: 140px;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
}

.top_13 {
    margin-top: 13px;
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
