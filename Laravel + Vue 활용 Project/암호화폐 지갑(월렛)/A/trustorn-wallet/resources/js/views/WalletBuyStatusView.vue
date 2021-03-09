<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('wallet_buy.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <div class="icon_box">
                <span class="state_img">
                    <img :src="statusIcon" alt="icon" />
                </span>
                <span class="ment_1">{{ firstMent }}</span>
                <span class="ment_2">{{ secondMent }}</span>
            </div>
            <div class="sd_box sd_box_shadow">
                <div class="form_line">
                    <table class="info_table">
                        <tbody>
                            <tr>
                                <td class="label_td">구매코인</td>
                                <td class="td_2 send_value">트러스톤/TRU</td>
                            </tr>
                            <tr>
                                <td class="label_td">구매수량</td>
                                <td class="td_2">{{BuyAmount}}<strong>TRU</strong></td>
                            </tr>
                            <tr>
                                <td class="label_td">구매금액</td>
                                <td class="td_2">{{EthAmount}}<strong>ETH</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <footer-component
            :buttonText="__('user_security_waiting.home')"
            v-on:buttonClick="okButtonClick"
        ></footer-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";

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
        "footer-component": FooterComponent
    },
    data(){
        return{
            buyAmount:0,
            ethAmount:0,
            isCompleted: false,
            isPopupVisible: false,
            isSuccess: false,
            systemNoticeMessage: "",
            systemNoticeIcon: ""
        }
    },
    async mounted() {
        if (this.$route.params.verify !== true) {
            this.$router.replace("/wallet_buy_new");
            return;
        }

        await this.buyCoinRequest();
    },
    computed: {
        statusIcon(){
            return this.isCompleted === true 
            ? '/images/trst-images/icon/icon_complete.svg' 
            : '/images/trst-images/icon/icon_ing_buy.svg'
        },
        firstMent(){
            return this.isCompleted === true 
            ? '구매 완료'
            : '구매 중'
        },
        secondMent(){
            return this.isCompleted === true 
            ? '2000-11-11 00:00:00'
            : '-'
        },
        BuyAmount(){
            return this.buyAmount;
        },
        EthAmount(){
            return this.ethAmount;
        }
    },
    methods: {
        async buyCoinRequest(){
            try {
                this.$store.commit("progressComponentShow");

                // 구매요청
                const buy_result = await axios.post('/api/wallet/buy/tru', {
                    symbol: this.$store.state.walletBuyStatusViewData.selectedCoin,
                    amount: this.$store.state.walletBuyStatusViewData.buyAmount
                }).data;

                this.buyAmount = buy_result.buyAmount;
                this.ethAmount = buy_result.ethAmount;

                this.systemNoticeMessage = this.__(
                    "wallet_buy.buy_ok"
                );
                this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                this.isSuccess = true;
                this.isCompleted = true;
            } catch (e) {
                this.systemNoticeMessage = this.__(
                    "wallet_buy.error_occur"
                );
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.isSuccess = false;

                if (e.response) {
                    const response = e.response.data;
                    if (response.error === "is_numeric") {
                        this.systemNoticeMessage = this.__(
                            "wallet_buy.is_numeric"
                        );
                    } else if (response.error === "over_balance") {
                        this.systemNoticeMessage = this.__(
                            "wallet_buy.over_balance"
                        );
                    } else if (response.error === "result_fail") {
                    }
                }
            } finally {
                this.$store.commit("updateWalletBuyStatusViewData", {
                    symbol: "tru",
                    amount: 0,
                });
                this.isPopupVisible = true;
                this.$store.commit("progressComponentHide");
            }
        },
        okButtonClick(){
            this.$router.replace("/");
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
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

.info_table .td_2 strong{
    color: #19B4AA;
    font-weight: 500;
    padding-left: 5px;
}
</style>
