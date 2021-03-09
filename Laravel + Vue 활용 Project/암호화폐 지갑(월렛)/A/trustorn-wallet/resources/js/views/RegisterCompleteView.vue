<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('register.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <div class="icon_box">
                <span class="state_img">
                    <img src="/images/trst-images/icon/icon_complete.svg" alt="icon" />
                </span>
                <span class="ment_1">
                    {{__('register.title')}}&nbsp;{{__('register.ok')}}
                </span>
                <span class="ment_2">TRUSTON Wallet 회원가입을 환영합니다.</span>
            </div>
            <div class="sd_box sd_box_shadow">
                <table class="info_table">
                    <tbody>
                        <tr>
                            <th colspan="2">{{__('user_info.title')}}</th>
                        </tr>
                        <tr>
                            <th class="label_th">{{__('user_info.name')}}</th>
                            <td class="contents_td">사용자명</td>
                        </tr>
                        <tr>
                            <th class="label_th">{{__('user_info.account_id')}}</th>
                            <td class="contents_td">trust@truston.com</td>
                        </tr>
                        <tr>
                            <th class="label_th">{{__('user_info.mobile_number')}}</th>
                            <td class="contents_td">01012345678</td>
                        </tr>
                        <tr>
                            <th class="label_th">{{__('user_info.personal_code')}}</th>
                            <td class="contents_td">#1234</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <footer-component
            :buttonText="__('user_security_waiting.home')"
            v-on:buttonClick="okButtonClick"
        ></footer-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";

    export default {
        components: {
            "header-component": HeaderComponent,
            "footer-component": FooterComponent
        },
        methods: {
            okButtonClick(){
               this.$router.replace("/home");
            }
        }
    }
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
}

.bgcolor{
    background-color: #FAFAFA;
}

.trst-conatiner {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.trst-container .icon_box{
    margin-bottom: 20px;
}

.info_table {
    font-size: 0.8rem;
    width: 100%;
    color: #505050;
    font-weight: 400;
}

.info_table tr {
    width: 100%;
    border-bottom: 1px solid #EBF0F0;
}

.info_table tr:first-child th{
    padding: 13px 0;
    text-align: center;
    font-size: 0.9rem;
    font-weight: 500;
    color: #003E5A;
}

.info_table tr:last-child{
    border-bottom: 0;
}

.info_table .label_th {
    color: #003E5A;
    font-weight: 500;
    padding: 19px 13px;
    vertical-align: top;
    width: 30%;
}

.info_table .contents_td {
    white-space: nowrap;
    vertical-align: middle;
    line-height: 1.8;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .info_table .label_th {
        width: 33%;
    }
}
</style>
