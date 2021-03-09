<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('user_info.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor scroll_area">
            <div class="info_box">
                <span
                    class="in_form_line hr_1"
                    style="margin-top: 5px;"
                >{{$store.state.detail.user.fullname}}</span>
                <span class="in_form_line hr_2">{{$store.state.detail.user.email}}</span>
            </div>
            <div class="info_form sd_box">
                <div class="form_line">
                    <div class="in_form_line">
                        <label class="label_hr info_label">{{__('user_info.personal_code')}}</label>
                        <p class="info_text">#{{$store.state.detail.user.id}}</p>
                    </div>
                </div>
            </div>
            <!--
            <div class="info_form sd_box">
                <div class="form_line">
                    <div class="in_form_line">
                        <label class="label_hr info_label">{{__('user_info.account_id')}}</label>
                        <p class="info_text">{{$store.state.detail.user.username}}</p>
                    </div>
                </div>
            </div>
            -->
            <div class="info_form sd_box hide">
                <div class="form_line">
                    <div class="in_form_line">
                        <label class="label_hr info_label">{{__('user_info.mobile_number')}}</label>
                        <p
                            id="user_mobile_number"
                            class="info_text"
                        >{{$store.state.detail.user.mobile_number}}</p>
                    </div>
                </div>
            </div>
            <div class="info_form sd_box">
                <div class="form_line">
                    <div class="in_form_line">
                        <label class="label_hr info_label">{{__('user_info.pw')}}</label>
                        <button
                            id="password_edit"
                            class="info_btn"
                            @click="$router.replace('/user_password_change')"
                        >{{__('user_info.edit')}}</button>
                        <p class="info_text">●●●●●●●●●●</p>
                    </div>
                </div>
            </div>
            <div class="info_form sd_box">
                <div class="form_line">
                    <div class="in_form_line">
                        <label class="label_hr info_label">{{__('user_info.secret_key')}}</label>
                        <button
                            id="securcode_edit_btn"
                            class="info_btn"
                            @click="$router.replace({name: 'user_secret_key_change', params: {redirect: '/user_info'}})"
                        >{{__('user_info.edit')}}</button>
                        <p class="info_text">●●●●●●</p>
                    </div>
                </div>
            </div>
            <div class="info_form sd_box hide">
                <div class="form_line">
                    <div class="in_form_line">
                        <label class="label_hr info_label">{{__('user_info.security_lv')}}</label>
                        <button
                            id="security_edit_btn"
                            class="info_btn"
                            @click="securityChangeClick"
                        >{{securityEditText}}</button>
                        <p class="info_text">
                            <i id="user_security_level">{{$store.state.detail.security.status}}</i>
                            {{__('user_info.step')}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <footer-component :buttonText="__('user_info.logout')" v-on:buttonClick="logout"></footer-component>
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
    async created() {
        await this.fetchData();
    },
    computed: {
        securityEditText() {
            return this.$store.state.detail.security.status === 4
                ? this.__("user_info.done")
                : this.__("user_info.upgrade");
        }
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("progressComponentShow");

                this.$store.commit(
                    "detail",
                    (await axios.get(`/api/detail`)).data
                );
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        async logout() {
            try {
                this.$store.commit("progressComponentShow");

                await axios.get("/api/logout");
                localStorage.removeItem("passportToken");
                axios.defaults.headers.common["Authorization"] = undefined;
                this.$swal({
                    type: "success",
                    text: this.__("system.logout_success"),
                    allowOutsideClick: false
                });
                this.$router.replace("/");
            } finally {
                this.$store.commit("progressComponentHide");
                this.$store.commit("reset");
            }
        },
        securityChangeClick() {
            if (Number(this.$store.state.detail.security.status) <= 2) {
                this.$router.replace("/user_security_document");
            } else if (Number(this.$store.state.detail.security.status) <= 3) {
                this.$router.replace("/user_security_account");
            } else if (
                Number(this.$store.state.detail.security.status) === 3.5
            ) {
                this.$router.replace("/user_security_waiting");
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

.scroll_area {
    overflow-y: scroll;
}

.info_box {
    background-color: #000;
    padding: 20px 0;
    padding-top: 40px;
    width: 90%;
    margin: 0 auto;
    height: auto;
    text-align: center;
    color: white;
    border-bottom: 2px solid #fff;
}

.info_box img {
    width: 48px;
}

.icon_box {
    position: relative;
    text-align: center;
    height: auto;
    margin-bottom: 7px;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
}

.info_box .hr_1 {
    font-weight: 600;
    font-size: 17px;
    margin-top: 15px;
}

.info_box .hr_2 {
    font-weight: 300;
    font-size: 14px;
    margin-top: 13px;
}

.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    border-radius: 5px;
    margin: 0 auto;
    position: relative;
    top: 2vh;
    padding: 10px 0;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
}

.sd_box .form_line .label_hr {
    width: 100%;
    display: inline-block;
    margin-bottom: 5px;
    color: #fff;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.ai_wrapper label.label_hr.info_label {
    float: left;
    width: unset;
    margin-bottom: 0;
}

.info_text {
    float: right;
    color: #fff;
    font-size: 16px;
    margin-top: 0;
    margin-bottom: 0;
}

.info_btn {
    float: right;
    border: 0;
    font-size: 14px;
    color: #2E87C8;
    line-height: 15px;
    height: 15px;
    background-color: transparent;
    font-weight: 600;
}
</style>
