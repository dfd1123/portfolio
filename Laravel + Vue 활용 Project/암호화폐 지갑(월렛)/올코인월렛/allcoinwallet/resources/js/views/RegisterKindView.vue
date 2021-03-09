<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/"
            center="text"
            :centerText="__('register_kind.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor scroll_area">
            <div class="sd_box register_st in_info_box">
                <div class="form_line">
                    <label class="label_hr">{{__('register_kind.register_kind')}}</label>
                    <p>
                        <span>
                            {{__('register_kind.ment_1_1')}}
                            <br />
                            {{__('register_kind.ment_1_2')}}
                            <br />
                            {{__('register_kind.ment_1_3')}}
                        </span>
                    </p>
                    <ul class="customer_check">
                        <input
                            type="radio"
                            id="allcoin_check"
                            class="check_box"
                            name="act_choice"
                            value="0"
                        />
                        <li class="check_li allcoin_li">
                            <label
                                for="allcoin_check"
                                v-bind:class="{active: selected === 'allcoin'}"
                                @click="selected = 'allcoin'"
                            >
                                <img
                                    :src="selected === 'allcoin' ? 'images/allcoin_icon_checked.svg' : 'images/allcoin_icon.svg'"
                                    alt="allcoin_icon"
                                />
                                <span>{{__('register_kind.allcoinwallet_new')}}</span>
                            </label>
                        </li>
                        <input
                            type="radio"
                            id="cointouse_check"
                            class="check_box"
                            name="act_choice"
                            value="1"
                        />
                        <li class="check_li cointouse_li">
                            <label
                                for="cointouse_check"
                                v-bind:class="{active: selected === 'cointouse'}"
                                @click="selected = 'cointouse'"
                            >
                                <img
                                    :src="selected === 'cointouse' ? 'images/cointouse_icon_checked.svg' : 'images/cointouse_icon.svg'"
                                    alt="cointouse_icon"
                                />
                                <span>{{__('register_kind.cointouse_new')}}</span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="ps_text">
                <label class="ps_title">{{__('register_kind.warning_1')}}</label>
                <p class="ps_p">{{__('register_kind.warning_2')}}</p>
                <p class="ps_p">{{__('register_kind.warning_3')}}</p>
            </div>
        </div>
        <footer-component
            :buttonText="__('register_kind.next')"
            v-on:buttonClick="nextButtonClick"
            :active="selected"
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
    data() {
        return {
            selected: ""
        };
    },
    methods: {
        async nextButtonClick() {
            this.$router.replace({
                name: "register_agree",
                params: { selected: this.selected }
            });
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 45px;
    padding-bottom: 52px;
}

.scroll_area {
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: #fafafa;
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
    top: 13px;
    padding: 15px 15px;
    margin-bottom: 15px;
}

.ai_wrapper .sd_box.register_st {
    width: 94%;
    padding: 15px 0 0 0;
}

.ai_wrapper #allcoin_check:checked ~ .allcoin_li {
    background: #0072ff;
}

.ai_wrapper #cointouse_check:checked ~ .cointouse_li {
    background: #0072ff;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.sd_box .form_line .label_hr {
    width: 100%;
    display: inline-block;
    margin-bottom: 5px;
    color: #0747ad;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.ai_wrapper .sd_box.register_st label {
    padding-left: 15px;
    padding-bottom: 7px;
}

.sd_box.in_info_box p {
    width: 100%;
    padding: 10px 0;
    text-align: center;
    font-size: 13px;
    background: #f6f6f6;
    color: #5a5a5a;
    letter-spacing: -0.05rem;
    margin-bottom: 3px;
}

.sd_box.register_st p span {
    font-size: 11px;
    line-height: 16px;
    text-align: center;
    width: 100%;
    display: inline-block;
}

.sd_box.in_info_box p span {
    display: inline-block;
    margin: 3px 0;
    width: 100%;
}

.ai_wrapper ul.customer_check {
    margin-top: 15px;
}

input[type="radio"] {
    margin: 4px 0 0;
    margin-top: 1px \9;
    line-height: normal;
}

.ai_wrapper ul.customer_check .check_li {
    padding: 18px 0;
    width: 100%;
    text-align: center;
    border-bottom: 1px solid #c8c8c8;
    transition-duration: 0.3s;
    height: 100%;
}

.ai_wrapper ul.customer_check .check_li:nth-child(2) {
    border-top: 1px solid #c8c8c8;
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

.ai_wrapper ul.customer_check .check_li label {
    width: 100%;
    height: 100%;
    display: block;
    padding: 0;
}

.ai_wrapper ul.customer_check .check_li img {
    width: 92px;
    display: inline-block;
}

.ai_wrapper ul.customer_check .check_li span {
    font-size: 13px;
    color: #5a5a5a;
    letter-spacing: -1px;
    display: block;
    margin-top: 12px;
    padding-bottom: 0;
}

input[type="radio"] {
    margin: 4px 0 0;
    line-height: normal;
    box-sizing: border-box;
    padding: 0;
}

.check_box {
    display: none;
}

.ai_wrapper ul.customer_check .check_li img {
    width: 92px;
    display: inline-block;
}

.ai_wrapper ul.customer_check .check_li img {
    width: 92px;
    display: inline-block;
}

.ai_wrapper ul.customer_check .check_li:nth-child(4) img {
    width: 138px;
}

.ai_wrapper ul.customer_check .check_li label.active span {
    color: white;
}

.ai_wrapper .ps_text {
    width: 90%;
    margin: 0 auto;
    margin-top: 8%;
    padding: 5px 5px;
}

.ai_wrapper .ps_text .ps_title {
    width: 100%;
    font-weight: 600;
    color: #0072ff;
    font-size: 12px;
}

.ai_wrapper .ps_text .ps_p {
    width: 100%;
    font-weight: 600;
    color: #5a5a5a;
    font-size: 11px;
    line-height: 18px;
    letter-spacing: -1px;
}

.ai_wrapper .ps_text .ps_p {
    width: 100%;
    font-weight: 600;
    color: #5a5a5a;
    font-size: 11px;
    line-height: 18px;
    letter-spacing: -1px;
}
</style>
