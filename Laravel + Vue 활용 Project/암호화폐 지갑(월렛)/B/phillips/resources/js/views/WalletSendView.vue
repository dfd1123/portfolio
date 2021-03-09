<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('wallet_send.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('wallet_send.send_amount')}}</label>
                    <div class="in_form_line keep_form info_line">
                        <span class="keep_value_tt">{{__('wallet_send.own_amount')}}</span>
                        <p
                            class="keep_value_form"
                            id="change_send_balance"
                        >{{$store.state.selectedCoinInfo.balance}}</p>
                        <span
                            class="krw"
                            id="change_send_symbol1"
                        >{{$store.state.selectedCoinInfo.symbol.toUpperCase()}}</span>
                    </div>
                    <div class="in_form_line max_form">
                        <span
                            class="max_btn"
                            @click="sendAmount = $store.state.selectedCoinInfo.balance"
                        >{{__('wallet_send.max')}}</span>
                        <input
                            type="number"
                            placeholder="0"
                            id="send_coin_amt"
                            v-model.number="sendAmount"
                        />
                        <span
                            class="krw top_5"
                            id="change_send_symbol2"
                        >{{$store.state.selectedCoinInfo.symbol.toUpperCase()}}</span>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('wallet_send.search_address')}}</label>
                    <div class="in_form_line">
                        <ul class="sch_btn">
                            <li
                                v-bind:class="{active: $store.state.walletSendViewData.selectedSearch === 'qr'}"
                                @click="callQrScanner"
                            >
                                <p class="icon">
                                    <img src="/images/qrcode.svg" alt="qrcode" />
                                </p>
                                <span>{{__('wallet_send.search_qr')}}</span>
                            </li>
                            <li
                                class="direct_adr_sch"
                                v-bind:class="{active: $store.state.walletSendViewData.selectedSearch === 'input'}"
                                @click="$router.replace('/wallet_input_address')"
                            >
                                <p class="icon">
                                    <img src="/images/direct_t.svg" alt="input" />
                                </p>
                                <span>{{__('wallet_send.search_input')}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('wallet_send.receiver_info')}}</label>
                    <div class="receiver_info">
                        <p
                            v-if="$store.state.walletSendViewData.selectedSearch === ''"
                            class="s_text"
                            id="data_addressinfo_no"
                        >{{__('wallet_send.info_empty')}}</p>

                        <table v-else class="user_info active" id="data_addressinfo_exist">
                            <tbody v-if="$store.state.walletSendViewData.selectedUser.id">
                                <tr class="data_addressinfo_exist_tr">
                                    <td class="label_td">{{__('wallet_send.name')}}</td>
                                    <td class="td_2" id="change_send_fullname">
                                        {{$store.state.walletSendViewData.selectedUser.fullname}}
                                        <strong>(#{{$store.state.walletSendViewData.selectedUser.id}})</strong>
                                    </td>
                                </tr>
                                <tr class="data_addressinfo_exist_tr">
                                    <td class="label_td">{{__('wallet_send.id')}}</td>
                                    <td
                                        class="td_2"
                                        id="change_send_email"
                                    >{{cenceredEmail}}</td>
                                </tr>
                                <tr>
                                    <td class="label_td">{{__('wallet_send.address')}}</td>
                                    <td
                                        class="td_2 wordbrk"
                                        id="change_send_contactaddr"
                                    >{{$store.state.walletSendViewData.selectedUser.address}}</td>
                                </tr>
                            </tbody>
                            <tbody v-else-if="$store.state.walletSendViewData.externalAddress">
                                <tr>
                                    <td class="label_td">{{__('wallet_send.address')}}</td>
                                    <td
                                        class="td_2 wordbrk"
                                        id="change_send_contactaddr"
                                    >{{$store.state.walletSendViewData.externalAddress}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="ps_text">
                <label class="ps_title">{{__('wallet_send.warning')}}</label>
                <p class="ps_p">{{__('wallet_send.warning_1')}}</p>
                <p class="ps_p">{{__('wallet_send.warning_2')}}</p>
            </div>
            <footer-component :buttonText="__('wallet_send.send')" v-on:buttonClick="sendButtonClick"></footer-component>
        </div>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
            :closeButtonClick="popupCloseButtonClick"
        ></system-notice-component>
        <system-confirm-component
            iconSrc="/images/buy_icon.svg"
            :visible.sync="isConfirmVisible"
            v-on:yesButtonClick="showSecretKeyVerifyView"
        >
            <span
                v-if="$store.state.walletSendViewData.selectedUser.id"
            >{{$store.state.walletSendViewData.selectedUser.fullname}}(#{{$store.state.walletSendViewData.selectedUser.id}}) {{__('wallet_send.samani')}}</span>
            <span
                v-else-if="$store.state.walletSendViewData.externalAddress"
            >{{$store.state.walletSendViewData.externalAddress}} <br/> {{__('wallet_send.address_de')}}</span>
            <br />
            <b>{{sendAmount}}</b>
            <u>PPC</u>를
            <br />
            {{__('wallet_send.send_confirm')}}
        </system-confirm-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";
import SystemConfirmComponent from "../components/common/SystemConfirmComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        if (from.path === "/home") {
            return next(vm => {
                vm.$store.commit("updateWalletSendViewData", {
                    selectedUser: { address: "" },
                    selectedSearch: "",
                    externalAddress: "",
                    sendAmount: 0
                });
            });
        }
        if (from.path !== "/home" && to.path === "/wallet_send") {
            return next(vm => {
                vm.$data.sendAmount =
                    vm.$store.state.walletSendViewData.sendAmount;
            });
        }
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent,
        "system-notice-component": SystemNoticeComponent,
        "system-confirm-component": SystemConfirmComponent
    },
    data() {
        return {
            isPopupVisible: false,
            isConfirmVisible: false,
            sendAmount: 0
        };
    },
    beforeCreate() {
        this.$store.commit("updateWalletSendStatusViewData", {
            symbol: "",
            amount: 0,
            address: ""
        });

        this.$EventBus.$on("qr-scan-result", async event => {
            try {
                this.$store.commit("progressComponentShow");

                this.$store.commit("updateWalletSendStatusViewData", {
                    symbol: "",
                    amount: 0,
                    address: ""
                });

                await axios.post(`/api/wallet/address/verify`, {
                    symbol: this.$store.state.selectedCoin,
                    address: event.address
                });

                const users = (await axios.get(`/api/users`, {
                    params: {
                        symbol: this.$store.state.selectedCoin,
                        address: event.address
                    }
                })).data;

                if (users.length > 0) {
                    this.$store.commit("mergeWalletSendViewData", {
                        selectedUser: users[0],
                        selectedSearch: "qr"
                    });
                } else {
                    this.$store.commit("mergeWalletSendViewData", {
                        selectedUser: {},
                        selectedSearch: "qr",
                        externalAddress: event.address
                    });
                }
            } catch (e) {
                this.isInvalidAddressScan = true;
                this.isPopupVisible = true;
            } finally {
                this.$store.commit("progressComponentHide");
            }
        });
    },
    beforeDestroy() {
        this.$store.commit("mergeWalletSendViewData", {
            sendAmount: this.sendAmount
        });

        this.$EventBus.$off("qr-scan-result");
    },
    computed: {
        isSendZeroOrLessAmount() {
            return this.sendAmount <= 0;
        },
        isSendInsufficientAmount() {
            return this.sendAmount > this.$store.state.selectedCoinInfo.balance;
        },
        systemNoticeMessage() {
            if (this.isSendInsufficientAmount) {
                return this.__("wallet_send.insufficient_amount");
            }
            if (this.isSendZeroOrLessAmount) {
                return this.__("wallet_send.zero_or_less");
            }
            if (this.$store.state.walletSendViewData.selectedSearch === "") {
                return this.__("wallet_send.need_address");
            }
            if (this.isInvalidAddressScan) {
                return this.__("wallet_send.invalid_address");
            }
            return "";
        },
        cenceredEmail() {
            if (this.$store.state.walletSendViewData.selectedUser.email) {
                const splited = this.$store.state.walletSendViewData.selectedUser.email.split(
                    "@"
                );
                return (
                    splited[0].slice(0, 2) +
                    "*".repeat(splited[0].length - 2) +
                    "@" +
                    splited[1]
                );
            }
            return "";
        }
    },
    methods: {
        async sendButtonClick() {
            if (
                this.isSendInsufficientAmount ||
                this.isSendZeroOrLessAmount ||
                this.$store.state.walletSendViewData.selectedSearch === ""
            ) {
                this.isPopupVisible = true;
            } else {
                this.isConfirmVisible = true;
            }
        },
        showSecretKeyVerifyView() {
            const address = this.$store.state.walletSendViewData.selectedUser.id
                ? this.$store.state.walletSendViewData.selectedUser.address
                : this.$store.state.walletSendViewData.externalAddress;

            const symbol = this.$store.state.selectedCoinInfo.symbol;

            this.$store.commit("updateWalletSendStatusViewData", {
                symbol: symbol,
                amount: this.sendAmount,
                address: address
            });

            this.$router.replace({
                name: "user_secret_key_verify",
                params: {
                    backName: "wallet_send",
                    proceedName: "wallet_send_status"
                }
            });
        },
        callQrScanner() {
            // 폰 바코드 스캐너 요청
            this.$EventBus.$emit("qr-scan-request");
        },
        popupCloseButtonClick() {
            if (this.isInvalidAddressScan) {
                this.isInvalidAddressScan = false;
            }
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 48px;
}

.bgcolor {
    background-color: #000;
    height: 100%;
}

.scroll_area {
    overflow-x: hidden;
    overflow-y: auto;
}

.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    margin: 0 auto;
    position: relative;
    top: 2vh;
    padding: 15px 9px;
    margin-bottom: 15px;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.sd_box .form_line .label_hr {
    width: 100%;
    display: inline-block;
    color: #fff;
    font-weight: 400;
    font-size: 16px;
    border-bottom: 2px solid #fff;
    padding-bottom: 7px;
    margin-bottom: 0;
}

.sd_box .form_line .w_address {
    width: 100%;
    font-size: 14px;
    border: 0;
    border-bottom: 1px solid #dcdcdc;
    padding: 5px 10px;
    word-break: break-all;
    color: #8b8b8b;
    line-height: 25px;
    border-radius: 0;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
}

.info_line {
    width: 100%;
    height: 30px;
    line-height: 30px;
    margin: 10px 0 15px 0;
    font-size: 15px;
    color: #8b8b8b;
    font-weight: 500;
}

.max_form {
    width: 100%;
    position: relative;
}

.keep_form {
    margin: 5px 0;
}

.keep_form .keep_value_tt {
    font-size: 13px;
    color: #5a5a5a;
    font-weight: 600;
}

.keep_form .keep_value_form {
    position: absolute;
    top: 0;
    padding: 0 53px;
    padding-right: 32px;
    text-align: right;
    width: 100%;
    border-bottom:1px solid #8b8b8b;
}

.ai_wrapper .krw {
    text-transform: uppercase;
    color: #2E87C8;
    font-size: 12px;
    font-weight: 400;
    height: 30px;
    line-height: 30px;
    position: absolute;
    right: 0;
    padding: 0 5px;
    bottom: 0;
}

.ai_wrapper .max_btn {
    font-size: 13px;
    color: white;
    padding: 6px 15px;
    background-color: #2E87C8;
    box-sizing: border-box;
    font-weight: 700;
    position: absolute;
    left: 0;
    top: -3px;
}

input.buy_input_area,
.max_form input {
    width: 100%;
    text-align: right;
    float: left;
    border: 0;
    font-size: 15px;
    padding-right: 43px;
    height: 30px;
    background:transparent;
    border-bottom: 1px solid #8B8B8B;
    color: #8b8b8b;
}

.sch_btn {
    width: 100%;
    margin-top: 10px;
}

.sch_btn li {
    float: left;
    text-align: center;
    margin-right: 7%;
}

.sch_btn li p {
    width: 4rem;
    height: 4rem;
    background-color: #333333;
    border-radius: 100px;
    margin: 0 auto;
    margin-bottom: 2%;
}

.sch_btn li.active p {
    background-color: #2E87C8;
}

.sch_btn li p img {
    width: 1.8rem;
    margin-top: 27%;
}

.sch_btn li span {
    text-align: center;
    display: inline-block;
    width: 100%;
    color: #fff;
    font-size: 11px;
    font-weight: 200;
    padding-top: 9px;
}

.receiver_info {
    width: 100%;
    text-align: center;
    font-size: 11px;
    padding: 20px 0;
    color: #8b8b8b;
    font-weight: 600;
    letter-spacing: -0.5px;
}

.ai_wrapper .s_text {
    font-size: 13px;
    color: #b4b4b4;
    font-weight:400;
    padding-top: 0px;
    text-align: left;
}

.hide {
    display: none !important;
}

.user_info tr {
    border-bottom: 0;
    padding: 10px 0;
}

.user_info td {
    color: #5a5a5a;
    font-size: 11px;
}

.user_info .label_td {
    display: table-cell;
    width: 25%;
    padding: 10px 10px;
    display: table-cell;
}

.td_2 {
    color: #5a5a5a;
    font-size: 13px;
    word-break: break-all;
    line-height: 20px;
    padding-right: 10px;
    text-align: left;
}

.user_info td strong {
    color: #2E87C8;
    font-weight: 600;
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
    color: #2E87C8;
    font-size: 12px;
    display: block;
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
