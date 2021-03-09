<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/user_info"
            center="text"
            :centerText="__('user_security_waiting.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor account_box">
            <div class="icon_box top_5vh">
                <span class="state_img">
                    <img src="/images/mail_ing_icon.svg" alt="icon" />
                </span>
                <span class="ment_1">
                    <strong>{{__('user_security_waiting.ment_1')}}</strong>
                </span>
                <span class="ment_2">{{__('user_security_waiting.ment_2')}}</span>
            </div>

            <div class="sd_box btn_sd_box sending">
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
    padding-top: 45px;
    margin-bottom: -53px;
    padding-bottom: 53px;
}

.ai_container {
    background-color: #fafafa;
    height: 100%;
}

.bgcolor {
    background-color: #fafafa;
    height: 100%;
}

.scroll_area {
    overflow-y: scroll;
}

.account_box .top_box {
    width: 95%;
    height: auto;
    margin: 0 auto;
    position: relative;
    top: 0;
    padding-top: 20px;
}

.account_box .top_box .hr_line {
    width: 100%;
    display: inline-block;
    margin-bottom: 3%;
    font-size: 12px;
    color: #5a5a5a;
}

.account_box .top_box .hr_line:first-child u {
    text-decoration: none;
    font-weight: 900;
}

.account_box .top_box .hr_line:first-child strong {
    color: #0072ff;
}

.account_box .top_box .hr_line {
    width: 100%;
    display: inline-block;
    margin-bottom: 3%;
    font-size: 12px;
    color: #5a5a5a;
}

.account_box .top_box .hr_line.step {
    font-size: 20px;
    color: #0045ab;
    font-weight: 600;
    margin-bottom: 5%;
    position: relative;
}

.account_box .top_box .hr_line.step span:nth-child(2) {
    position: absolute;
    right: 0;
    font-size: 13px;
    color: #5a5a5a;
}

.account_box .top_box .hr_line.step span:nth-child(2) u {
    color: #0072ff;
    text-decoration: none;
    font-size: 16px;
}

.account_box .top_box .gage {
    width: 100%;
    height: 2px;
    background-color: #e6e6e6;
    position: relative;
    float: left;
    margin-bottom: 10px;
}

.account_box .top_box .gage.per_50:after {
    width: 50%;
}

.account_box .top_box .gage:after {
    content: "";
    position: absolute;
    width: 20%;
    height: 10px;
    background: linear-gradient(to right, #0072ff, #49d094);
    top: -3px;
}

.account_box .top_box .gage.left:after {
    left: 0;
}

.account_box .top_box .gage.right:after {
    right: 0;
}

.account_box .top_box .gage_p {
    width: 100%;
    margin-top: 10px;
    float: left;
    padding: 0;
    margin: 0;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.account_box .top_box .gage_p li {
    float: left;
    width: 50%;
    text-align: center;
    font-size: 10px;
    color: #c8c8c8;
}

.account_box .top_box .gage_p li.now {
    color: #5a5a5a;
}

.account_box .top_box .gage_p li.now {
    color: #5a5a5a;
}

.account_box .top_box .gage_p li {
    float: left;
    width: 50%;
    text-align: center;
    font-size: 10px;
    color: #c8c8c8;
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
    font-weight: 600;
    float: left;
    width: 100%;
    display: inline-block;
}

.ment_1 strong {
    color: #0747ad;
}

.ment_2 {
    font-size: 12px;
    color: #5a5a5a;
    width: 100%;
    display: inline-block;
}

.ai_wrapper .sd_box.sending,
.ai_wrapper .buying {
    top: 7.5vh;
    padding: 0;
}

.ai_wrapper .sd_box.btn_sd_box {
    padding: 0;
    border-radius: 0;
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

.ai_wrapper .sd_box.sending .form_line {
    margin-bottom: 25px;
    text-align: center;
    padding: 12px;
}

.mail_box p:nth-child(1) {
    font-size: 15px;
    color: #0072ff;
    font-weight: 600;
    padding: 20px 0;
}

.mail_box p:nth-child(2) {
    font-size: 12px;
    color: #5a5a5a;
    font-weight: 600;
    padding: 0 0 20px 0;
}

.mail_box p:nth-child(3),
.ai_wrapper .mail_box p:nth-child(4),
.w_plus .key_notice {
    font-size: 11px;
    color: #b4b4b4;
    line-height: 16px;
    padding: 0 10px;
    text-align: center;
    width: 100%;
}
</style>
