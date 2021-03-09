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
                <h3 class="hd-user-name">{{$store.state.detail.user.fullname}}</h3>
                <span class="hd-user-email">{{$store.state.detail.user.email}}</span>
            </div>
            <div class="info-sdbox-group">
                <div class="info_form sd_box">
                    <div class="form_line">
                        <div class="in_form_line">
                            <label class="label_hr info_label">{{__('user_info.personal_code')}}</label>
                            <p class="info_text">#{{$store.state.detail.user.id}}</p>
                        </div>
                    </div>
                </div>
                <div class="info_form sd_box">
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
                            <p class="info_text">··········</p>
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
                            <p class="info_text">····</p>
                        </div>
                    </div>
                </div>
                <div class="info_form sd_box">
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
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        next();
    },
    components: {
        "header-component": HeaderComponent
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
    padding-top: 2.815rem;
}

.bgcolor{
    background-color: #FAFAFA;
}

.info-sdbox-group{
    padding: 30px 20px 0;
}

.info_box {
    background: linear-gradient(to bottom, rgb(100, 225, 150), rgb(25, 180, 170));
    padding: 50px 20px;
    height: auto;
    text-align: left;
    color: white;
}

.info_box .hd-user-name{
    font-weight: 500;
    font-size: 26px;
}

.info_box .hd-user-email{
    font-weight: 300;
    letter-spacing: 0.2px;
    font-size: 1rem;
}

.ai_wrapper .sd_box {
    width: 100%;
    background-color: white;
    border-radius: 0;
    box-shadow: 0px 8px 12px rgba(195, 215, 215, 0.28);
    margin: 0 auto;
    position: relative;
    top: 0;
    padding: 15px 15px;
    margin-bottom: 15px;
}

.sd_box .form_line{
    overflow: hidden;
}

.sd_box .form_line .label_hr {
    width: 100%;
    margin-bottom: 5px;
    color: #003E5A;
    font-weight: 400;
    font-size: 14px;
    margin-bottom: 8px;
    float: left;
    width: unset;
    margin-bottom: 0;
}

.sd_box .form_line .info_btn {
    float: right;
    border: 0;
    font-size: 12px;
    color: #FFB400;
    background-color: transparent;
    font-weight: 400;
    border-radius: 0;
    padding: 0;
    margin-left: 7px;
}

.sd_box .form_line .info_text {
    float: right;
    color: #505050;
    font-size: 14px;
    margin-top: 0;
    margin-bottom: 0;
    letter-spacing: 0.3px;
}

.sd_box .form_line .info_text i{
    font-style: normal;
}
</style>
