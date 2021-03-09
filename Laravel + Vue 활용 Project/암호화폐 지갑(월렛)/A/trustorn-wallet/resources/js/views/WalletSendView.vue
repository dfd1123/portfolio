<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('wallet_send.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr mb_3px">{{__('wallet_send.send_amount')}}</label>
                    <div class="in_form_line max_form">
                        <input
                            type="number"
                            placeholder="0"
                            id="send_coin_amt"
                            v-model.number="sendAmount"
                        />
                        <span
                            class="unit"
                            id="change_send_symbol2"
                        >{{$store.state.selectedCoinInfo.symbol.toUpperCase()}}</span>
                    </div>
                    <div class="in_form_line keep_form info_line">
                        <span class="keep_value_tt">{{__('wallet_send.own_amount')}}</span>
                        <p
                            class="keep_value_form"
                            id="change_send_balance"
                        >{{$store.state.selectedCoinInfo.balance}}</p>
                        <span
                            id="change_send_symbol1"
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
                                class="user_member_sch"
                                v-bind:class="{active: $store.state.walletSendViewData.selectedSearch === 'user'}"
                                @click="$router.replace('/wallet_find_user')"
                            >
                                <p class="icon">
                                    <img src="/images/trst-images/icon/icon_user.svg" alt="user" />
                                </p>
                                <span>{{__('wallet_send.search_user')}}</span>
                            </li>
                            <li
                                v-bind:class="{active: $store.state.walletSendViewData.selectedSearch === 'qr'}"
                                @click="callQrScanner"
                            >
                                <p class="icon">
                                    <img src="/images/trst-images/icon/icon_qrcode.svg" alt="qrcode" />
                                </p>
                                <span>{{__('wallet_send.search_qr')}}</span>
                            </li>
                            <li
                                class="direct_adr_sch"
                                v-bind:class="{active: $store.state.walletSendViewData.selectedSearch === 'input'}"
                                @click="$router.replace('/wallet_input_address')"
                            >
                                <p class="icon">
                                    <img src="/images/trst-images/icon/icon_direct.svg" alt="input" />
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
                                    >{{$store.state.walletSendViewData.selectedUser.email}}</td>
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
        </div>
        <footer-component :buttonText="__('wallet_send.send')" v-on:buttonClick="sendButtonClick"></footer-component>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
            :closeButtonClick="popupCloseButtonClick"
        ></system-notice-component>
        <system-confirm-component
            iconSrc="/images/trst-images/icon/icon_cart.svg"
            :visible.sync="isConfirmVisible"
            v-on:yesButtonClick="showSecretKeyVerifyView"
        >
            <span
                v-if="$store.state.walletSendViewData.selectedUser.id"
            >{{$store.state.walletSendViewData.selectedUser.fullname}}(#{{$store.state.walletSendViewData.selectedUser.id}}) {{__('wallet_send.samani')}}</span>
            <span
                v-else-if="$store.state.walletSendViewData.externalAddress"
            >{{$store.state.walletSendViewData.externalAddress}} {{__('wallet_send.address_de')}}</span>
            <b>{{sendAmount}}</b>
            <u>{{__(`coin.${$store.state.selectedCoin}`)}}</u>
            {{__('wallet_send.o')}}
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
                params: { backName: "wallet_send", proceedName: "wallet_send_status" }
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
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}

.max_form {
    width: 100%;
    position: relative;
}

.max_form input{
    text-align: left;
    width: 100%;
    border: 0;
    font-size: 1.45rem;
    padding-right: 3.5rem;
    height: 3rem;
    border-bottom: 1px solid #dcdedc;
    color: #505050;
    border-radius: 0;
}

.max_form input::placeholder{
    color: #BEC8C8;
}

.max_form .unit {
    text-transform: uppercase;
    color: #19B4AA;
    font-size: 1.45rem;
    font-weight: 400;
    height: 30px;
    line-height: 30px;
    position: absolute;
    right: 0;
    padding: 0 5px;
    top: 50%;
    transform: translateY(-50%);
}

.keep_form {
    width: 100%;
    margin: 0;
    font-size: 12.5px;
    color: #505050;
    font-weight: 300;
    text-align: left;
    padding: 8px 0;
}

.keep_form .keep_value_form {
    display: inline-block;
    letter-spacing: 0;
    padding: 0 2px 0 5px;
}

.keep_form > span:last-child{
    letter-spacing: 0;
}

.sch_btn {
    width: 100%;
    display: flex;
    justify-content: center;
    margin: 0 auto;
    background-color: #F8F8F8;
    padding: 20px 0;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .sch_btn{
        font-size: 14px;
    }
}

.sch_btn li {
    text-align: center;
    margin: 0 10px;
}

.sch_btn li p {
    width: 4.4em;
    height: 4.4em;
    background-color: white;
    border-radius: 100px;
    position: relative;
    border: 1px solid #E1E1E1;
    box-shadow: 0 4px 10px rgba(195,215,215,0.6);
    margin-bottom: 8px;
}

.sch_btn li.active p {
    border-color: #19B4AA;
}

.sch_btn li p img {
    width: 23px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.sch_btn li span {
    text-align: center;
    display: inline-block;
    width: 70.39px;
    font-size: 0.85em;
    color: #505050;
    font-weight: 400;
}

.receiver_info {
    width: 100%;
    background-color: #F8F8F8;
    text-align: center;
    padding: 20px 13px;
}

.receiver_info .s_text {
    font-size: 0.85rem;
    color: #BEC8C8;
    font-weight: 400;
}

.hide {
    display: none !important;
}

.user_info tr {
    border-bottom: 0;
}

.user_info td {
    font-size: 13px;
    letter-spacing: 0;
    line-height: 1.9;
}

.user_info .label_td {
    color: #003E5A;
    width: 25%;
    display: table-cell;
    vertical-align: top;
    text-align: left;
    font-weight: 500;
}

.user_info .td_2 {
    color: #505050;
    word-break: break-all;
    text-align: left;
    font-weight: 400;
}

.user_info td strong {
    color: #19B4AA;
    font-weight: 500;
}
</style>
