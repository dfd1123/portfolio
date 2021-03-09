<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('wallet_receive.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container">
            <div class="qr_wrapper">
                <label class="box_title">{{__('wallet_receive.ment_1')}}</label>
                <div class="qr_code">
                    <span class="code_img">
                        <img id="change_receive_qrcode" :src="receiveAddressQR" />
                    </span>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('wallet_receive.ment_2')}}</label>
                    <p
                        class="in_input"
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
            iconSrc="/images/trst-images/icon/icon_check.svg"
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
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}

.ai_wrapper .qr_wrapper {
    width: 100%;
    height: auto;
    background: linear-gradient(to bottom, rgb(100, 225, 150), rgb(25, 180, 170));
    box-shadow: 0px 5px 20px #C3D7D7;
    margin: 0 auto 35px;
    padding: 30px 15px;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .ai_wrapper .qr_wrapper{
        font-size: 14px;
    }
}

.qr_wrapper .box_title {
    width: 100%;
    text-align: center;
    display: inline-block;
    font-size: 0.95em;
    color: white;
    letter-spacing: -0.2px;
    font-weight: 400;
    margin-bottom: 20px;
}

.qr_wrapper .qr_code {
    width: 65%;
    min-width: 140px;
    height: auto;
    text-align: center;
    margin-bottom: 45px;
    margin: 0 auto;
    border-radius: 10px;
    margin-bottom: 18px;
    overflow: hidden;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .qr_wrapper .qr_code{
        width: 55%;
    }
}

.qr_wrapper .qr_code .code_img {
    width: 100%;
    background-color: white;
    margin: 0 auto;
    display: block;
}

.qr_wrapper .qr_code .code_img img {
    width: 100%;
}

.trst-container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.sd_box .form_line .in_input {
    height: auto;
    line-height: 1.8;
    font-weight: 400;
    letter-spacing: 0;
}
</style>
