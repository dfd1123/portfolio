<template>
    <div class="ai_wrapper">
        <header-component
            center="text"
            :centerText="__('wallet_send_status.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <div class="icon_box">
                <span class="state_img">
                    <img src="/images/trst-images/icon/icon_ing.svg" alt="icon" />
                </span>
                <span class="ment_1">
                    {{__('wallet_send_status.ment_1_1')}}&nbsp;{{__('wallet_send_status.ment_1_2')}}
                </span>
                <span class="ment_2">{{__('wallet_send_status.ment_2')}}</span>
            </div>
            <div class="sd_box sd_box_shadow">
                <div class="form_line">
                    <table class="info_table">
                        <tbody>
                            <tr>
                                <td class="label_td">{{__('wallet_send_status.send_amount')}}</td>
                                <td
                                    class="td_2 send_value"
                                    id="send_value_td"
                                >{{$store.state.walletSendViewData.sendAmount}} {{$store.state.selectedCoin.toUpperCase()}}</td>
                            </tr>
                            <tr>
                                <td class="label_td">{{__('wallet_send_status.coin_kind')}}</td>
                                <td
                                    class="td_2"
                                    id="send_address_td"
                                >{{__(`coin.${$store.state.selectedCoin}`)}}({{$store.state.selectedCoin.toUpperCase()}})</td>
                            </tr>
                            <tr>
                                <td class="label_td">{{__('wallet_send_status.recipient')}}</td>
                                <td
                                    v-if="$store.state.walletSendViewData.selectedUser.id"
                                    class="td_2"
                                >
                                    <p>{{$store.state.walletSendViewData.selectedUser.fullname}}</p>
                                    <p>{{cenceredEmail}}</p>
                                    <p>{{$store.state.walletSendViewData.selectedUser.address}}</p>
                                </td>
                                <td
                                    v-else-if="$store.state.walletSendViewData.externalAddress"
                                    class="td_2"
                                >
                                    <p>{{$store.state.walletSendViewData.externalAddress}}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
            :iconSrc="systemNoticeIcon"
            v-on:closeButtonClick="closeButtonClick"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }

        if (from.path !== "/user_secret_key_verify") {
            return next("/");
        }
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "system-notice-component": SystemNoticeComponent
    },
    data() {
        return {
            isPopupVisible: false,
            isSuccess: false,
            systemNoticeMessage: "",
            systemNoticeIcon: ""
        };
    },
    async mounted() {
        if (this.$route.params.verify !== true) {
            this.$router.replace("/wallet_send");
            return;
        }

        await this.coinSendRequest();
    },
    computed: {
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
        async coinSendRequest() {
            try {
                this.$store.commit("progressComponentShow");

                // 송금요청
                await axios.post(`/api/wallet/send`, {
                    symbol: this.$store.state.walletSendStatusViewData.symbol,
                    amt: this.$store.state.walletSendStatusViewData.amount,
                    address: this.$store.state.walletSendStatusViewData.address
                });

                this.systemNoticeMessage = this.__(
                    "wallet_send_status.send_ok"
                );
                this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                this.isSuccess = true;
            } catch (e) {
                this.systemNoticeMessage = this.__(
                    "wallet_send_status.error_occur"
                );
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isSuccess = false;

                if (e.response) {
                    const response = e.response.data;
                    if (response.error === "check_address") {
                        this.systemNoticeMessage = this.__(
                            "wallet_send_status.check_address"
                        );
                    } else if (response.error === "over_balance") {
                        this.systemNoticeMessage = this.__(
                            "wallet_send_status.over_balance"
                        );
                    } else if (response.error === "under_fee") {
                        this.systemNoticeMessage = this.__(
                            "wallet_send_status.under_fee"
                        );
                    } else if (response.error === "result_fail") {
                    }
                }
            } finally {
                this.$store.commit("updateWalletSendStatusViewData", {
                    symbol: "",
                    amount: 0,
                    address: ""
                });
                this.isPopupVisible = true;
                this.$store.commit("progressComponentHide");
            }
        },
        closeButtonClick() {
            if (!this.isSuccess) {
                this.$router.replace("/wallet_send");
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
}

.bgcolor{
    background-color: #FAFAFA;
}

.trst-container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.info_table {
    margin-top: 20px;
    font-size: 0.8rem;
    width: 100%;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .info_table{
        font-size: 0.7rem;
    }
}

.info_table tr {
    width: 100%;
    border-bottom: 1px solid #EBF0F0;
    height: 47px;
}

.info_table tr:last-child{
    border-bottom: 0;
}

.info_table tr .send_value {
    color: #19B4AA;
    font-weight: 500;
}

.info_table .label_td {
    white-space: nowrap;
    vertical-align: top;
    color: #003E5A;
    font-weight: 500;
    line-height: 1.8;
    padding-left: 15px;
    padding-top: 12px;
    width: 27%;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .info_table .label_td {
        width: 31%;
    }
}

.info_table .td_2 {
    color: #5a5a5a;
    word-break: break-all;
    line-height: 1.8;
    text-align: left;
    font-weight: 400;
    letter-spacing: 0;
    padding-top: 12px;
    padding-bottom: 12px;
    padding-right: 15px;
}
</style>
