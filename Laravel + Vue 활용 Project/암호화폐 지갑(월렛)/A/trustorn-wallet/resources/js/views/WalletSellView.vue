<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('wallet_sell.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="sd_box">
                <div class="form_line coin_choice">
                    <label class="label_hr">{{__('wallet_sell.select_sell_coin')}}</label>
                    <div class="in_form_line" @click="$router.replace('/wallet_sell_coin_select')">
                        <p
                            class="form_title"
                            id="buy_form_title"
                        >{{__(`coin.${$store.state.walletSellCoinSelectViewData.selectedCoin}`)}}({{$store.state.walletSellCoinSelectViewData.selectedCoin.toUpperCase()}})</p>
                        <i class="fas fa-sort-down"></i>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label
                        class="label_hr"
                        id="buy_balance_symbol"
                    >{{__('wallet_sell.own_coin')}}{{$store.state.walletSellCoinSelectViewData.selectedCoin.toUpperCase()}}</label>
                    <div class="in_form_line">
                        <input
                            type="text"
                            placeholder="0"
                            class="buy_input_area"
                            id="buy_balance"
                            :value="balanceAmount"
                            readonly="readonly"
                        />
                        <span
                            class="krw"
                            id="buy_symbol1"
                        >{{$store.state.walletSellCoinSelectViewData.selectedCoin.toUpperCase()}}</span>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line bt_30px">
                    <label class="label_hr">
                        {{__('wallet_sell.sell_amount')}}
                        <p class="s_text">{{__('wallet_sell.sell_notice')}}</p>
                    </label>
                    <div class="in_form_line">
                        <span
                            class="max_btn"
                            @click="maximumSellButtonClick"
                        >{{__('wallet_sell.max')}}</span>
                        <input
                            type="number"
                            placeholder="0"
                            class="buy_input buy_input_area"
                            v-model.number="sellAmount"
                        />
                        <span
                            class="krw top_5"
                            id="buy_symbol2"
                        >{{$store.state.walletSellCoinSelectViewData.selectedCoin.toUpperCase()}}</span>
                    </div>
                </div>
                <span class="estimated">{{__('wallet_sell.estimated')}}</span>
                <div class="form_line">
                    <div class="in_form_line">
                        <label class="label_hr">
                            {{__('wallet_sell.estimated_amount')}}
                            <p class="s_text">{{__('wallet_sell.fee')}}</p>
                        </label>
                        <input
                            type="text"
                            placeholder="0"
                            class="buy_input_area"
                            readonly
                            v-model="estimateSellAmount"
                        />
                        <span class="krw" id="buy_get_symbol">KRW</span>
                    </div>
                </div>
            </div>
            <div class="ps_text">
                <label class="ps_title">{{__('wallet_sell.warning')}}</label>
                <p class="ps_p">{{__('wallet_sell.warning_1')}}</p>
            </div>
        </div>
        <footer-component
            :buttonText="__('wallet_sell.sell')"
            v-on:buttonClick="verify"
            :active="isReadyToSell"
        ></footer-component>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
            :iconSrc="systemNoticeIcon"
            v-on:closeButtonClick="$router.replace('/')"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";

const MAX_AMOUNT = 1000000;
const MIN_AMOUNT = 15000;

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }

        if (from.path === "/home") {
            return next(async vm => {
                vm.$store.commit("updateWalletSellCoinSelectViewData", {
                    selectedCoin: vm.$store.state.selectedCoin,
                    sellAmount: 0
                });

                await vm.fetchCoinBalance();
            });
        }

        if (from.path === "/user_secret_key_verify") {
            return next(async vm => {
                try {
                    if (
                        vm.$route.params.verify === true &&
                        vm.$store.state.walletSellCoinSelectViewData
                            .selectedCoin
                    ) {
                        await vm.sell();
                    }

                    await vm.fetchCoinBalance();
                } finally {
                    vm.$store.commit("updateWalletSellCoinSelectViewData", {
                        selectedCoin:
                            vm.$store.state.walletSellCoinSelectViewData
                                .selectedCoin,
                        sellAmount: 0
                    });
                }
            });
        }

        if (from.path === "/wallet_sell_coin_select") {
            return next(async vm => {
                await vm.fetchCoinBalance();
            });
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
            systemNoticeIcon: "",
            systemNoticeMessage: "",
            isSellAmountChanged: false,
            isEstimating: false,
            isMaximumAmountloaded: false,
            debounceRef: null,
            balanceAmount: 0,
            sellAmount: 0,
            estimateSellAmount: "0"
        };
    },
    watch: {
        sellAmount() {
            if (this.maximumSellAmount <= 0) {
                this.sellAmount = 0;
            } else if (Number(this.sellAmount) > this.maximumSellAmount) {
                this.sellAmount = this.maximumSellAmount;
            } else if (Number(this.sellAmount) <= this.minimumSellAmount) {
                this.estimateSellAmount = "0";
            } else if (Number(this.sellAmount) < 0) {
                this.sellAmount = 0;
            }

            if (!this.isMaximumAmountloaded) {
                this.estimate();
                if (this.isEstimating) {
                    this.isSellAmountChanged = true;
                }
            }
            this.isMaximumAmountloaded = false;
        }
    },
    computed: {
        isReadyToSell() {
            return (
                this.sellAmount &&
                this.sellAmount !== 0 &&
                !this.isLessthanMinimumSellAmount &&
                !this.isMorethanMaximumSellAmount &&
                Number(this.estimateSellAmount.replace(/,/gi, "")) <=
                    this.maximumSellValue &&
                Number(this.estimateSellAmount.replace(/,/gi, "")) >=
                    this.minimumSellValue
            );
        },
        isMorethanMaximumSellAmount() {
            return this.sellAmount > this.maximumSellAmount;
        },
        isLessthanMinimumSellAmount() {
            return this.sellAmount < this.minimumSellAmount;
        },
        maximumSellAmount() {
            return this.balanceAmount;
        },
        minimumSellAmount() {
            return 0;
        },
        maximumSellValue() {
            return MAX_AMOUNT;
        },
        minimumSellValue() {
            return MIN_AMOUNT;
        }
    },
    methods: {
        async fetchCoinBalance() {
            try {
                this.$store.commit("progressComponentShow");

                const coin = (await axios.get(
                    `/api/wallet/info/${
                        this.$store.state.walletSellCoinSelectViewData
                            .selectedCoin
                    }`
                )).data;
                this.balanceAmount = Number(coin.balance);
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        estimate() {
            if (this.debounceRef) {
                clearTimeout(this.debounceRef);
            }

            this.debounceRef = setTimeout(async () => {
                try {
                    if (
                        this.isEstimating ||
                        this.isLessthanMinimumSellAmount ||
                        this.isMorethanMaximumSellAmount
                    ) {
                        return;
                    }
                    this.isEstimating = true;

                    const response = (await axios.post(
                        `/api/wallet/sell/estimate`,
                        {
                            coin: this.$store.state.walletSellCoinSelectViewData
                                .selectedCoin,
                            amount: String(this.sellAmount)
                        }
                    )).data;

                    if (response.orders) {
                        if (Number(this.sellAmount) <= 0) {
                            this.estimateSellAmount = "0";
                        } else {
                            this.estimateSellAmount = Number(
                                response.orders[0].value
                            ).toLocaleString();
                        }
                    }
                } finally {
                    this.isEstimating = false;
                }

                if (this.isSellAmountChanged) {
                    setTimeout(() => {
                        this.estimate();
                    }, 0);
                    this.isSellAmountChanged = false;
                }
            }, 500);
        },
        async maximumSellButtonClick() {
            try {
                this.$store.commit("progressComponentShow");

                const response = (await axios.post(`/api/wallet/buy/estimate`, {
                    coin: this.$store.state.walletSellCoinSelectViewData
                        .selectedCoin,
                    amount: String(MAX_AMOUNT)
                })).data;

                if (response.orders) {
                    const orderAmount = Number(response.orders[0].qty);
                    if (orderAmount <= this.balanceAmount) {
                        this.sellAmount = orderAmount;
                        this.estimateSellAmount = Number(
                            response.orders[0].value
                        ).toLocaleString();
                    } else {
                        const response2 = (await axios.post(
                            `/api/wallet/sell/estimate`,
                            {
                                coin: this.$store.state
                                    .walletSellCoinSelectViewData.selectedCoin,
                                amount: String(this.balanceAmount)
                            }
                        )).data;

                        if (!response2.error) {
                            this.sellAmount = Number(response2.orders[0].qty);
                            this.estimateSellAmount = Number(
                                response2.orders[0].value
                            ).toLocaleString();
                        }
                    }
                }
            } finally {
                this.isMaximumAmountloaded = true;
                this.$store.commit("progressComponentHide");
            }
        },
        verify() {
            if (this.isReadyToSell) {
                this.$store.commit("updateWalletSellCoinSelectViewData", {
                    selectedCoin: this.$store.state.walletSellCoinSelectViewData
                        .selectedCoin,
                    sellAmount: this.sellAmount
                });

                this.$router.replace({
                    name: "user_secret_key_verify",
                    params: {
                        backName: "wallet_sell",
                        proceedName: "wallet_sell"
                    }
                });
            }
        },
        async sell() {
            try {
                this.$store.commit("progressComponentShow");

                const response = (await axios.post(`/api/wallet/sell/execute`, {
                    coin: this.$store.state.walletSellCoinSelectViewData
                        .selectedCoin,
                    amount: String(
                        this.$store.state.walletSellCoinSelectViewData
                            .sellAmount
                    )
                })).data;

                if (response.status === "accepted") {
                    this.$store.commit(
                        "selectedCoin",
                        this.$store.state.walletSellCoinSelectViewData
                            .selectedCoin
                    );
                    this.systemNoticeMessage = this.__("wallet_sell.accepted");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                    this.isPopupVisible = true;
                }
            } catch (e) {
                this.systemNoticeMessage = this.__("wallet_sell.error");
                this.systemNoticeIcon = "/images/x_icon.svg";

                if (e.response) {
                    const response = e.response.data;
                    if (response.error === "rejected") {
                        this.systemNoticeMessage = this.__(
                            "wallet_sell.rejected"
                        );
                    }
                }

                this.isPopupVisible = true;
            } finally {
                this.sellAmount = 0;
                this.$store.commit("progressComponentHide");
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

.ai_wrapper.top_0 {
    padding-top: 45px;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
    margin: 0 auto;
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
    color: #0747ad;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
}

.coin_choice .in_form_line {
    border-bottom: 1px solid #dcdcdc;
    height: 30px;
}

.in_form_line .form_title {
    font-size: 13px;
    color: #5a5a5a;
    padding-left: 10px;
    height: 30px;
    position: absolute;
    left: 0;
    top: 8px;
}

.ai_wrapper .fa-sort-down {
    position: absolute;
    right: 0;
    top: 0;
    padding: 0 5px;
    color: #505050;
    width: initial;
}

.ai_wrapper .in_form_line .fa-sort-down {
    position: absolute;
    right: 0;
    top: 0;
    padding: 0 5px;
    color: #505050;
}

.ai_wrapper input.buy_input_area,
.max_form input {
    width: 100%;
    text-align: right;
    float: left;
    border: 0;
    border-bottom: 1px solid #dcdedc;
    font-size: 15px;
    padding-right: 43px;
    height: 30px;
}

.ai_wrapper .krw {
    text-transform: uppercase;
    color: #0072ff;
    font-size: 12px;
    font-weight: 900;
    height: 30px;
    line-height: 30px;
    position: absolute;
    right: 0;
    padding: 0 5px;
    bottom: 0;
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

.ai_wrapper .s_text {
    font-size: 10px;
    color: #b4b4b4;
    padding-top: 8px;
}

.sd_box .form_line .label_hr p.s_text {
    float: right;
    padding-top: 3px;
}

.ai_wrapper .max_btn {
    font-size: 13px;
    color: white;
    padding: 8px 18px;
    background-color: #0072ff;
    box-sizing: border-box;
    font-weight: 700;
    position: absolute;
    left:0;
    top:0;
}

.ai_wrapper input.buy_input {
    padding-left: 70px;
}

.ai_wrapper .estimated {
    width: 100%;
    background-color: #f7f7f7;
    display: inline-block;
    text-align: center;
    font-size: 10px;
    color: #969696;
    padding: 5px 0;
    margin-bottom: 15px;
}

.sd_box .form_line {
    width: 100%;
    margin-bottom: 15px;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.ai_wrapper .ps_text {
    width: 90%;
    margin: 0 auto;
    margin-top: 8%;
    padding: 5px 5px;
}

label {
    display: block;
    margin: 0;
    padding: 0;
    line-height: 1;
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

.ai_wrapper .ps_text .ps_p:nth-child(2) {
    margin-top: 3%;
}
</style>
