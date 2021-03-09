<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('user_security_waiting.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <div class="complt_after">
                <div class="complt_after_inner">
                    <img src="/images/trst-images/icon/icon_complete.svg" alt="complete">
                    <h4>{{__('user_security_waiting.title')}} {{__('register.ok')}}</h4>
                    <p>회원님은 모든 인증이 완료되었습니다.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";

    export default {
        components: {
            "header-component": HeaderComponent
        }
    }
</script>

<style scoped>
.ai_wrapper{
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
}

.trst-container{
    padding: 0;
}

.complt_before{
    width: 100%;
    height: 100%; 
    padding: 20px 1.25rem;
}

.complt_after{
    width: 100%;
    height: 100%; 
    padding: 20px 1.25rem;
    background-color: #FAFAFA;
    display: table;
}

.complt_after_inner{
    display: table-cell;
    vertical-align: middle;
    text-align: center;
}

.complt_after_inner > img{
    width: 50%;
    max-width: 100px;
    margin-bottom: -5px;
}

.complt_after_inner > h4{
    font-size: 1.45rem;
    font-weight: 400;
    color: #1E1E1E;
}

.complt_after_inner > p{
    font-size: 0.8rem;
    font-weight: 400;
    color: #505050;
    line-height: 1.4;
}

.ai_wrapper .s_text {
    font-size: 11px;
    color: #A0AAAA;
    padding-top: 8px;
    padding: 10px 0 13px;
    font-weight: 200;
    letter-spacing: -0.2px;
    line-height: 1.5;
}

.email_form{
    margin-bottom: 15px;
}

.certify_bd button {
    width: 100%;
    padding: 12px 0;
    font-size: 13px;
    color: #b4b4b4;
    font-weight: 300;
    border: none;
    background: #dcdcdc;
}

.certify_bd button.outline-btn{
    border: 1px solid #19B4AA;
    background-color: white;
    color: #19B4AA;
    font-weight: 400;
}

.certify_bd button.outline-btn.active{
    background-color: #19B4AA;
    border-color: #19B4AA;
    color: white;
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
