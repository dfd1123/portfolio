<template>
    <div class="ai_wrapper top_0">
        <header-component
            center="text"
            :centerText="__('wallet_send_status.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="icon_box top_5vh">
                <span class="ment_1">
                    <strong>보내기 완료</strong>
                </span>
                <span class="ment_2">{{moment().format('YYYY-MM-DD HH:mm:ss')}}</span>
            </div>
            <div class="sd_box sending">
                <div class="form_line">
                    <table class="info_table">
                        <tbody>
                            <tr>
                                <td class="label_td">{{__('wallet_send_status.send_amount')}}</td>
                                <td
                                    class="send_value"
                                    id="send_value_td"
                                >{{formatNumber($store.state.walletSendViewData.sendAmount)}} <span class="unit">{{$store.state.selectedCoin.toUpperCase()}}</span></td>
                            </tr>
                            <tr>
                                <td class="label_td">지갑주소</td>
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
            <footer-component
                id="footer"
                buttonText="홈으로"
                @buttonClick="$router.replace('/home')"
                style="position: fixed; bottom: 8%;"
            ></footer-component>
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
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";
import {Decimal} from 'decimal.js';

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
        "footer-component": FooterComponent,
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
        moment: moment,
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
                this.systemNoticeIcon = "/images/checkok_icon.svg";
                this.isSuccess = true;
            } catch (e) {
                this.systemNoticeMessage = this.__(
                    "wallet_send_status.error_occur"
                );
                this.systemNoticeIcon = "/images/x_icon.svg";
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
        },
        formatNumber(num, fixed) {
            const parts = Decimal(num)
                .toFixed(fixed)
                .split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            if (Number(parts.join(".")) === 0) {
                parts[0] = "0"; // -0 방지
            }

            return parts.join(".");
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
}

.ai_wrapper.bottom_0 {
    padding-bottom: 0;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: black;
    height: 100%;
}

.icon_box {
    position: relative;
    text-align: center;
    height: auto;
    margin-bottom: 7px;
}

.icon_box.top_5vh {
    top: 4vh;
}

.state_img {
    width: 100%;
    display: inline-block;
}

.state_img img {
    width: 62px;
}

.ment_1 {
    font-size: 15px;
    color: #5a5a5a;
    padding: 10px 0;
    font-weight: 700;
    display: inline-block;
}

.ment_1 strong {
    color: #2E87C8;
}

.ment_2 {
    font-size: 12px;
    color: #8b8b8b;
    width: 100%;
    display: inline-block;
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

.ai_wrapper .sd_box.sending,
.ai_wrapper .buying {
    top: 7.5vh;
    padding: 0;
}

.sd_box .form_line {
    width: 100%;
    height: auto;
}

.ai_wrapper .sd_box.sending .form_line {
    margin-bottom: 25px;
    text-align: center;
}

.info_table {
    width: 100%;
}

.info_table tr {
    width: 100%;
    padding: 20px 0;
    display: inline-block;
    border-bottom: 1px solid #dfdfdf;
}

.info_table tr:last-child {
    border-bottom: 0px solid #dfdfdf;
}

.ai_wrapper .sd_box.sending .form_line .info_table tr .label_td,
.ai_wrapper .buying .form_line .info_table .label_td {
    padding-left: 15px;
    padding-right: 15px;
    font-size: 14px;
    color: #2E87C8;
    font-weight: 600;
    letter-spacing: -1.5px;
}

.send_value {
    font-weight: 600;
}

.send_value .unit {
    color: #2E87C8;
    font-weight: 600;
}

.td_2 {
    color: #5a5a5a;
    font-size: 13px;
    word-break: break-all;
    line-height: 20px;
    padding-right: 10px;
    text-align: left;
}

.ai_wrapper .sd_box.sending .form_line .info_table tr:last-child .label_td {
    padding-left: 15px;
    padding-right: 15px;
    white-space: nowrap;
}

.td_2 {
    color: #5a5a5a;
    font-size: 13px;
    word-break: break-all;
    line-height: 20px;
    padding-right: 10px;
    text-align: left;
}
</style>
