<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('register_agree.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('register_agree.label_1')}}</label>
                    <button
                        type="button"
                        class="ok_box all_agree"
                        v-bind:class="{active: agreeAll}"
                        @click="toggleCheckAll"
                    >{{__('register_agree.agree_all')}}</button>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line agree_box">
                    <label class="label_hr">{{__('register_agree.label_2')}}</label>
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
                                <p>{{__('register_agree.check_1')}}</p>
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
                                <p>{{__('register_agree.check_2')}}</p>
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
                                <p>{{__('register_agree.check_3')}}</p>
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
                                <p>{{__('register_agree.check_4')}}</p>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="ps_text">
                <label class="ps_title">{{__('register_agree.warning')}}</label>
                <p class="ps_p">{{__('register_agree.warning_1')}}</p>
            </div>
        </div>
        <footer-component
            :buttonText="__('register_agree.register')"
            @buttonClick="registerButtonClick"
            active-color="linear-gradient(to right, #64E196, #19B4AA)"
            :active="agreeAll"
        ></footer-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";

export default {
    beforeRouteEnter(to, from, next) {
        /*
        if (from.path !== "/register_kind") {
            next("/register_kind");
        }
        */
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
                    params: { selected: "allcoin" }
                });
                /*
                if (this.$route.params.selected === "allcoin") {
                    this.$router.replace({
                        name: "register",
                        params: { selected: "allcoin" }
                    });
                } else if (this.$route.params.selected === "cointouse") {
                    this.$router.replace({
                        name: "register_existing",
                        params: { selected: "cointouse" }
                    });
                }
                */
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
    padding-bottom: 3.315rem;
}

.ok_box {
    border: 0;
    width: 100%;
    padding: 11px 0;
    color: #BEC8C8;
    font-weight: 300;
    font-size: 0.85rem;
    background-color: #EBF0F0;
}

.agree_ul li {
    padding: 8px 0;
    border-bottom: 1px solid #EBF0F0;
}

.agree_ul li:last-child {
    border: 0;
}

.agree_ul .control {
    width: 100%;
    height: auto;
    position: relative;
    display: inline-block;
    margin-bottom: 0;
    z-index: 1;
    line-height: 1.5;
    padding-left: 26px;
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
    top: 50%;
    left: 0;
    transform: translateY(-50%);
    transition: all 0.2s;
}

.control input:checked ~ .indicator:after {
    display: block;
}

.control input:checked ~ .indicator{
    background-color: #1E1E1E;
    border-color: #1E1E1E;
    transition: all 0.2s;
}

.indicator:after {
    left: 5px;
    top: 2px;
    width: 4px;
    height: 8px;
    border: solid #64E196;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
    content: "";
    position: absolute;
    display: none;
    box-sizing: content-box;
}

.agree_ul .control p {
    color: #505050;
    font-size: 0.81rem;
    font-weight: 300;
}

.agree_ul .control input:checked ~ .indicator:after {
    display: block;
}

.all_agree.active {
    background: #19B4AA;
    color: #fff;
}
</style>
