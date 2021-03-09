<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/user_info"
            center="text"
            :centerText="__('user_security_waiting.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <div class="icon_box">
                <span class="state_img">
                    <img src="/images/trst-images/icon/icon_idcard.svg" alt="icon" />
                </span>
                <span class="ment_1">
                    <strong>{{__('user_security_waiting.ment_1')}}</strong>
                </span>
                <span class="ment_2">{{__('user_security_waiting.ment_2')}}</span>
            </div>

            <div class="sd_box sd_box_shadow">
                <div class="form_line mail_box">
                    <p>{{__('user_security_waiting.info_1')}}</p>
                    <p>{{__('user_security_waiting.info_2')}}</p>
                    <p>{{__('user_security_waiting.info_3')}}</p>
                </div>
            </div>
        </div>
        <footer-component
            :buttonText="__('user_security_waiting.home')"
            v-on:buttonClick="$router.replace('/home')"
        ></footer-component>
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
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}

.bgcolor{
    background-color: #FAFAFA;
}

.trst-container .sd_box {
    text-align: center;
    padding: 10px;
    line-height: 1.5;
    padding: 20px 18px;
}

.mail_box p:nth-child(1) {
    font-size: 15px;
    color: #19B4AA;
    font-weight: 400;
}

.mail_box p:nth-child(2) {
    color: #505050;
    padding: 3px 0 10px;
    font-size: 13px;
}

.mail_box p:nth-child(3){
    font-size: 12px;
    color: #b4b4b4;
    padding: 0 10px;
}
</style>
