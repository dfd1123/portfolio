<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/user_info"
            center="text"
            :centerText="__('user_password_change.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('user_password_change.change_pw')}}</label>
                    <input
                        type="password"
                        :placeholder="__('user_password_change.placeholder_change_pw')"
                        class="w_address"
                        v-model="newPassword"
                    />
                    <p class="s_text">{{__('user_password_change.s_text')}}</p>
                    <div class="in_form_line pswrd2">
                        <input
                            type="password"
                            :placeholder="__('user_password_change.placeholder_confirm_pw')"
                            class="w_address top_13"
                            v-model="confirmPassword"
                        />
                        <div
                            v-if="isShowCollect"
                            class="collect"
                            :class="[isPasswordConfirmValid ? 'ok' : 'no']"
                        >
                            <p
                                v-show="isPasswordConfirmValid"
                                class="ok"
                            >{{__('user_password_change.same')}}</p>
                            <p
                                v-show="!isPasswordConfirmValid"
                                class="no"
                            >{{__('user_password_change.not_same')}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <footer-component
                :buttonText="__('user_password_change.edit_ok')"
                v-on:buttonClick="changePassword"
                :active="isReadyToChange"
            ></footer-component>
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent
    },
    data() {
        return {
            newPassword: "",
            confirmPassword: ""
        };
    },
    computed: {
        isShowCollect() {
            return this.newPassword || this.confirmPassword;
        },
        isPasswordConfirmValid() {
            return (
                this.newPassword &&
                this.confirmPassword &&
                this.newPassword === this.confirmPassword
            );
        },
        isReadyToChange() {
            return (
                this.newPassword &&
                this.confirmPassword &&
                this.newPassword === this.confirmPassword
            );
        }
    },
    methods: {
        async changePassword() {
            if (this.isReadyToChange) {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.put(`/api/detail`, {
                        password_new: this.newPassword
                    });

                    this.$swal({
                        type: "success",
                        text: this.__("user_password_change.pw_changed")
                    });
                } catch (e) {
                    this.$swal({
                        type: "error",
                        text: this.__("user_password_change.invalid_pw")
                    });
                    this.password = "";
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
    padding-top: 48px;
}

.bgcolor {
    background-color: #000;
    height: 100%;
}

.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    background:#fff;
    border-radius: 5px;
    margin: 0 auto;
    position: relative;
    top: 2vh;
    padding: 15px 15px;
    margin-bottom: 15px;
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
    border-radius: 0;
}

.ai_wrapper .s_text {
    font-size: 10px;
    color: #b4b4b4;
    padding-top: 8px;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
}

.in_form_line.pswrd2 input {
    margin-top: 2px;
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

.collect p {
    position: absolute;
    left: -40px;
    width: 35px;
    font-size: 12px;
    display: block;
    top: 3px;
}

.collect p.no {
    color: #e60000;
}

.collect p.ok {
    color: #0072ff;
    left: -30px;
}

.collect.no {
    border: 1px solid #e60000;
}

.collect.no:after {
    border: solid #e60000;
    border-width: 0 2.5px 2.5px 0;
}

.collect.ok {
    border: 1px solid #0072ff;
}

.collect.ok:after {
    border: solid #0072ff;
    border-width: 0 2px 2px 0;
}
</style>
