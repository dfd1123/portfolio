<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            centerText="비밀번호 찾기"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <!-- before ) 비밀번호 찾기 과정 -->
            <div class="complt_before" v-if="step == 1">
                <!-- 1단계 ) 이메일 아이디 입력 -->
                <div class="sd_box">
                    <div class="form_line">
                        <label class="label_hr mb_3px">{{__('register.label_4')}}</label>
                        <p class="s_text">로그인 이메일 아이디 메일함에 전송된 링크를 통해<br>비밀번호를 변경합니다.</p>
                        <div class="in_form_line email_form">
                            <input
                                type="email"
                                name="email"
                                placeholder="ex) ex) truston@truston.com"
                                class="in_input"
                            />
                        </div>
                        <div class="certify_bd">
                            <button
                                type="button"
                                class="outline-btn"
                                v-html="__('register.auth_email_send')"
                            ></button>
                        </div>
                    </div>
                </div>
                <!-- 1단계 end -->
                <!-- 2단계 비밀번호 재설정 -->
                <div class="sd_box">
                    <div class="form_line">
                        <label class="label_hr mb_3px">{{__('register.label_5')}}</label>
                        <p class="s_text">{{__('register.label_5_1')}}</p>
                        <input
                            type="password"
                            v-model="password"
                            class="in_input"
                            :placeholder="__('register.placeholder_password')"
                        />
                        <div class="in_form_line pswrd">
                            <input
                                type="password"
                                v-model="passwordConfirm"
                                class="in_input"
                                :placeholder="__('register.placeholder_password_confirm')"
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
                <!-- 2단계 end -->
            </div>
            <!-- END before -->
            <!-- after ) 비밀번호 변경 완료 -->
            <div class="complt_after" v-else>
                <div class="complt_after_inner">
                    <img src="/images/trst-images/icon/icon_complete.svg" alt="complete">
                    <h4>비밀번호 변경 완료</h4>
                    <p>회원님의 비밀번호가 변경되었습니다.<br>다시 로그인하세요.</p>
                </div>
            </div>
            <!-- END after -->
        </div>
        <!-- 비밀번호 재설정할 때 보임 -->
        <footer-component
            :buttonText="__('register.ok')"
        ></footer-component>
        <!-- end -->
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
        data () {
            return{
                step: 2,
                password: "",
                passwordConfirm: ""
            }
        },
        computed: {
            isPasswordSame(){
                return (
                    this.password &&
                    this.passwordConfirm &&
                    this.password === this.passwordConfirm
                );
            }
        }
    }
</script>

<style scoped>
.ai_wrapper{
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
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
