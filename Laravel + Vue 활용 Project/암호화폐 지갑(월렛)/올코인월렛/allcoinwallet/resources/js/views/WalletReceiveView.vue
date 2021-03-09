<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('wallet_receive.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container">
            <div class="sd_box">
                <label class="box_title">{{__('wallet_receive.ment_1')}}</label>
                <div class="qr_code">
                    <span class="code_img">
                        <img id="change_receive_qrcode" :src="receiveAddressQR" />
                    </span>
                </div>
                <div class="form_line">
                    <label class="label_hr">{{__('wallet_receive.ment_2')}}</label>
                    <p
                        class="w_address"
                        id="change_receive_qraddress"
                    >{{$store.state.selectedCoinInfo.address}}</p>
                </div>
            </div>
        </div>
        <button ref="clipboard" id="clipboard" :data-clipboard-text="$store.state.selectedCoinInfo.address" />
        <footer-component
            :buttonText="__('wallet_receive.copy')"
            @buttonClick="copy"
        ></footer-component>
         <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";
import ClipboardJS from "clipboard";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }

        next(vm => {
            if (!vm.$store.state.selectedCoinInfo.address) {
                next("/home");
            }
        });
    },
    data() {
        return {
            isPopupVisible: false,
            systemNoticeMessage: "",
            clipboard: null
        };
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent,
        "system-notice-component": SystemNoticeComponent
    },
    computed: {
        receiveAddressQR() {
            return `https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=${
                this.$store.state.selectedCoinInfo.address
            }&choe=UTF-8`;
        }
    },
    mounted() {
        this.clipboard = new ClipboardJS("#clipboard");
    },
    beforeDestroy() {
        this.clipboard.destroy();
    },
    methods: {
        copy() {
            this.$refs.clipboard.click();
            this.systemNoticeMessage = this.__("wallet_receive.copy_ok");
            this.isPopupVisible = true;
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 45px;
    margin-bottom: -53px;
    padding-bottom: 52px;
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
    top: 2vh;
    padding: 15px 15px;
    margin-bottom: 15px;
}

.sd_box .box_title {
    width: 100%;
    text-align: center;
    display: inline-block;
    font-size: 12px;
    color: #5a5a5a;
    float: left;
    margin-bottom: 45px;
    letter-spacing: -0.5px;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    margin-top: 10%;
}

label {
    display: block;
    margin: 0;
    padding: 0;
    line-height: 1;
}

.sd_box .qr_code {
    width: 100%;
    height: 150px;
    float: left;
    text-align: center;
    margin-bottom: 45px;
}

.sd_box .qr_code .code_img {
    width: 150px;
    height: 150px;
    background-color: white;
    margin: 0 auto;
    display: block;
}

.sd_box .qr_code .code_img img {
    width: 100%;
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

.sd_box .form_line .w_address {
    width: 100%;
    font-size: 14px;
    border: 0;
    border-bottom: 1px solid #dcdcdc;
    padding: 5px 10px;
    word-break: break-all;
    color: #5a5a5a;
    line-height: 25px;
}

.w_address {
    border-radius: 0;
}
</style>
