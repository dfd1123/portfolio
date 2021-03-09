<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/login"
            center="text"
            :centerText="__('register_agree.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr bt_15px">{{__('register_agree.label_1')}}</label>
                    <button
                        type="button"
                        class="ok_box all_agree"
                        v-bind:class="{active: agreeAll}"
                        @click="toggleCheckAll"
                    >{{__('register_agree.agree_all')}}</button>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line bt_15px">
                    <label class="label_hr">{{__('register_agree.label_2')}}</label>
                </div>
                <div class="form_line agree_box">
                    <ul class="agree_ul">
                        <li>
                            <label class="control">
                                <input
                                    type="checkbox"
                                    id="reg_agree1"
                                    name="reg_agree1"
                                    v-model="check1"
                                />
                                <div class="indicator"></div>
                                <div class="in_text">
                                    <p>{{__('register_agree.check_1')}}</p>
                                </div>
                            </label>
                        </li>
                        <li>
                            <label class="control">
                                <input
                                    type="checkbox"
                                    id="reg_agree2"
                                    name="reg_agree2"
                                    v-model="check2"
                                />
                                <div class="indicator"></div>
                                <div class="in_text">
                                    <p>{{__('register_agree.check_2')}}</p>
                                </div>
                            </label>
                        </li>
                        <li>
                            <label class="control">
                                <input
                                    type="checkbox"
                                    id="reg_agree3"
                                    name="reg_agree3"
                                    v-model="check3"
                                />
                                <div class="indicator"></div>
                                <div class="in_text">
                                    <p>{{__('register_agree.check_3')}}</p>
                                </div>
                            </label>
                        </li>
                        <li>
                            <label class="control">
                                <input
                                    type="checkbox"
                                    id="reg_agree4"
                                    name="reg_agree4"
                                    value="1"
                                    autocomplete="off"
                                    v-model="check4"
                                />
                                <div class="indicator"></div>
                                <div class="in_text">
                                    <p>{{__('register_agree.check_4')}}</p>
                                </div>
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="ps_text">
                    <label class="ps_title">{{__('register_agree.warning')}}</label>
                    <p class="ps_p">{{__('register_agree.warning_1')}}</p>
                </div>
            </div>
            <footer-component
                :buttonText="__('register_agree.register')"
                v-on:buttonClick="registerButtonClick"
                :active="agreeAll"
            ></footer-component>
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";

export default {
    beforeRouteEnter(to, from, next) {
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent
    },
    data() {
        return {
            check1: false,
            check2: false,
            check3: false,
            check4: false
        };
    },
    computed: {
        agreeAll() {
            return this.check1 && this.check2 && this.check3 && this.check4;
        }
    },
    methods: {
        toggleCheckAll() {
            if (!this.agreeAll) {
                this.check1 = true;
                this.check2 = true;
                this.check3 = true;
                this.check4 = true;
            } else {
                this.check1 = false;
                this.check2 = false;
                this.check3 = false;
                this.check4 = false;
            }
        },
        async registerButtonClick() {
            if (this.agreeAll) {
                this.$router.replace({
                    name: "register",
                    params: { agreeAll: this.agreeAll }
                });
            }
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 49px;
}

.scroll_area {
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: #000;
    height: 100%;
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

.ok_box {
    border: 0;
    width: 100%;
    padding: 12px 0;
    color: #969696;
    font-weight: 600;
    font-size: 13px;
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

.sd_box .form_line {
    width: 100%;
    margin-bottom: 15px;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.ai_wrapper .bt_15px {
    margin-bottom: 15px;
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

.agree_ul li {
    padding: 7px 0px 2px 0px;
    border-bottom: 1px solid #f0f0eb;
}

.agree_ul li:last-child {
    border: 0;
}

.agree_ul .control {
    width: 100%;
    height: 20px;
    position: relative;
    display: inline-block;
    margin-bottom: 5px;
    color: #0747ad;
    font-weight: 600;
    font-size: 14px;
    z-index: 5;
}

.sd_box .form_line label {
    width: 80%;
    display: inline-block;
    margin-bottom: 5px;
    color: #0747ad;
    letter-spacing: -0.5px;
    font-weight: 600;
    font-size: 14px;
}

.agree_ul .control input {
    display: none;
}

.agree_ul .control .indicator {
    width: 18px;
    height: 18px;
    background-color: white;
    border-radius: 50px;
    border: 1px solid #dcdcdc;
    position: absolute;
    top: 0;
    left: 0;
}

.control input:checked ~ .indicator:after {
    display: block;
}

.indicator:after {
    left: 5.2px;
    top: 2.3px;
    width: 3px;
    height: 6px;
    border: solid #2E87C8;
    border-width: 0 2.5px 2.5px 0;
    transform: rotate(45deg);
    content: "";
    position: absolute;
    display: none;
    box-sizing: content-box;
}

.in_text {
    float: left;
    padding-left: 6.5%;
}

.agree_ul .control .in_text p {
    position: absolute;
    top: 0;
    padding-left: 15px;
    line-height: 20px;
    color: #5a5a5a;
    font-size: 13px;
}

.agree_ul .control input:checked ~ .indicator:after {
    display: block;
}

.ai_wrapper .ps_text {
    margin: 0 auto;
    margin-top: 8%;
    padding: 5px 5px;
}

.ai_wrapper .ps_text .ps_title {
    width: 100%;
    font-weight: 600;
    color: #2E87C8;
    font-size: 12px;
    margin-bottom: 0;
}

.ai_wrapper .ps_text .ps_p {
    width: 100%;
    font-weight: 600;
    color: #5a5a5a;
    font-size: 11px;
    line-height: 18px;
    letter-spacing: -1px;
}

.ai_wrapper .ps_text .ps_p:nth-child(2) {
    margin-top: 3%;
}

.all_agree.active {
    background: #2E87C8;
    color: #fff;
}
</style>
