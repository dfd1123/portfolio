<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('wallet_buy.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="sd_box">
                <div class="form_line coin_choice">
                    <label class="label_hr">{{__('wallet_buy.select_buy_coin')}}</label>
                    <div class="in_form_line" @click="$router.replace('/wallet_buy_coin_select')">
                        <p
                            class="form_title"
                            id="buy_form_title"
                        >{{__(`coin.${$store.state.walletBuyCoinSelectViewData.selectedCoin}`)}}({{$store.state.walletBuyCoinSelectViewData.selectedCoin.toUpperCase()}})</p>
                        <i class="fas fa-sort-down"></i>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr" id="buy_balance_symbol">{{__('wallet_buy.own_cash')}}</label>
                    <div class="in_form_line">
                        <input
                            type="text"
                            placeholder="0"
                            class="buy_input_area"
                            id="buy_balance"
                            :value="$store.state.asset.cash_asset"
                            readonly="readonly"
                        />
                        <span class="krw" id="buy_symbol1">KRW</span>
                    </div>
                </div>
            </div>
            <div class="sd_box">
                <div class="form_line bt_30px">
                    <label class="label_hr">
                        {{__('wallet_buy.buy_amount')}}
                        <p class="s_text">{{__('wallet_buy.buy_notice')}}</p>
                    </label>
                    <div class="in_form_line">
                        <span
                            class="max_btn"
                            @click="maximumBuyButtonClick"
                        >{{__('wallet_buy.max')}}</span>
                        <input
                            type="number"
                            placeholder="0"
                            class="buy_input buy_input_area"
                            v-model.number="buyAmount"
                        />
                        <span class="krw top_5" id="buy_symbol2">KRW</span>
                    </div>
                </div>
                <span class="estimated">{{__('wallet_buy.estimated')}}</span>
                <div class="form_line">
                    <div class="in_form_line">
                        <label class="label_hr">
                            {{__('wallet_buy.estimated_amount')}}
                            <p class="s_text">{{__('wallet_buy.fee')}}</p>
                        </label>
                        <input
                            type="text"
                            placeholder="0"
                            class="buy_input_area"
                            readonly
                            v-model="estimateBuyAmount"
                        />
                        <span
                            class="krw"
                            id="buy_get_symbol"
                        >{{$store.state.walletBuyCoinSelectViewData.selectedCoin.toUpperCase()}}</span>
                    </div>
                </div>
            </div>
            <div class="ps_text">
                <label class="ps_title">{{__('wallet_buy.warning')}}</label>
                <p class="ps_p">{{__('wallet_buy.warning_1')}}</p>
            </div>
        </div>
        <footer-component
            :buttonText="__('wallet_buy.buy')"
            v-on:buttonClick="verify"
            :active="isReadyToBuy"
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
            return next(vm => {
                vm.$store.commit("updateWalletBuyCoinSelectViewData", {
                    selectedCoin: vm.$store.state.selectedCoin,
                    buyAmount: 0
                });
            });
        }

        if (from.path === "/user_secret_key_verify") {
            return next(async vm => {
                try {
                    if (
                        vm.$route.params.verify === true &&
                        vm.$store.state.walletBuyCoinSelectViewData.selectedCoin
                    ) {
                        await vm.buy();
                    }
                } finally {
                    vm.$store.commit("updateWalletBuyCoinSelectViewData", {
                        selectedCoin:
                            vm.$store.state.walletBuyCoinSelectViewData
                                .selectedCoin,
                        buyAmount: 0
                    });
                }
            });
        }

        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent,
        "system-notice-component": SystemNoticeComponent
    },
    async created() {
        try {
            this.$store.commit("progressComponentShow");

            this.$store.commit(
                "asset",
                (await axios.get(`/api/wallet/asset`)).data
            );
        } finally {
            this.$store.commit("progressComponentHide");
        }
    },
    data() {
        return {
            isBuyAmountChanged: false,
            isMaximumAmountloaded: false,
            isEstimating: false,
            isPopupVisible: false,
            systemNoticeMessage: "",
            systemNoticeIcon: "",
            debounceRef: null,
            buyAmount: 0,
            estimateBuyAmount: "0"
        };
    },
    watch: {
        buyAmount() {
            if (this.maximumBuyAmount <= 0) {
                this.buyAmount = 0;
            } else if (Number(this.buyAmount) > this.maximumBuyAmount) {
                this.buyAmount = this.maximumBuyAmount;
            } else if (Number(this.buyAmount) < this.minimumBuyAmount) {
                this.estimateBuyAmount = "0";
            } else if (Number(this.buyAmount) < 0) {
                this.buyAmount = 0;
            }

            if (!this.isMaximumAmountloaded) {
                this.estimate();
                if (this.isEstimating) {
                    this.isBuyAmountChanged = true;
                }
            }
            this.isMaximumAmountloaded = false;
        }
    },
    computed: {
        isReadyToBuy() {
            return (
                this.buyAmount &&
                this.buyAmount !== 0 &&
                !this.isLessthanMinimumBuyAmount &&
                !this.isMorethanMaximumBuyAmount &&
                this.buyAmount <=
                    Number(
                        this.$store.state.asset.cash_asset.replace(/,/gi, "")
                    )
            );
        },
        isMorethanMaximumBuyAmount() {
            return this.buyAmount > this.maximumBuyAmount;
        },
        isLessthanMinimumBuyAmount() {
            return this.buyAmount < this.minimumBuyAmount;
        },
        maximumBuyAmount() {
            const cashAsset = Number(
                this.$store.state.asset.cash_asset.replace(/,/gi, "")
            );
            return cashAsset >= MAX_AMOUNT ? MAX_AMOUNT : cashAsset;
        },
        minimumBuyAmount() {
            return MIN_AMOUNT;
        }
    },
    methods: {
        estimate() {
            if (this.debounceRef) {
                clearTimeout(this.debounceRef);
            }

            this.debounceRef = setTimeout(async () => {
                try {
                    if (
                        this.isEstimating ||
                        this.isLessthanMinimumBuyAmount ||
                        this.isMorethanMaximumBuyAmount
                    ) {
                        return;
                    }
                    this.isEstimating = true;

                    const response = (await axios.post(
                        `/api/wallet/buy/estimate`,
                        {
                            coin: this.$store.state.walletBuyCoinSelectViewData
                                .selectedCoin,
                            amount: String(this.buyAmount)
                        }
                    )).data;

                    if (response.orders) {
                        if (Number(this.buyAmount) < this.minimumBuyAmount) {
                            this.estimateBuyAmount = "0";
                        } else {
                            this.estimateBuyAmount = Number(
                                response.orders[0].qty
                            ).toLocaleString();
                        }
                    }
                } finally {
                    this.isEstimating = false;
                }

                if (this.isBuyAmountChanged) {
                    setTimeout(() => {
                        this.estimate();
                    }, 0);
                    this.isBuyAmountChanged = false;
                }
            }, 500);
        },
        async maximumBuyButtonClick() {
            try {
                this.$store.commit("progressComponentShow");

                const response = (await axios.post(`/api/wallet/buy/estimate`, {
                    coin: this.$store.state.walletBuyCoinSelectViewData
                        .selectedCoin,
                    amount: String(this.maximumBuyAmount)
                })).data;

                if (response.orders) {
                    this.buyAmount = Number(response.orders[0].value);
                    this.estimateBuyAmount = Number(
                        response.orders[0].qty
                    ).toLocaleString();
                }
            } finally {
                this.isMaximumAmountloaded = true;
                this.$store.commit("progressComponentHide");
            }
        },
        verify() {
            if (this.isReadyToBuy) {
                this.$store.commit("updateWalletBuyCoinSelectViewData", {
                    selectedCoin: this.$store.state.walletBuyCoinSelectViewData
                        .selectedCoin,
                    buyAmount: this.buyAmount
                });

                this.$router.replace({
                    name: "user_secret_key_verify",
                    params: {
                        backName: "wallet_buy",
                        proceedName: "wallet_buy"
                    }
                });
            }
        },
        async buy() {
            try {
                this.$store.commit("progressComponentShow");

                const response = (await axios.post(`/api/wallet/buy/execute`, {
                    coin: this.$store.state.walletBuyCoinSelectViewData
                        .selectedCoin,
                    amount: String(
                        this.$store.state.walletBuyCoinSelectViewData.buyAmount
                    )
                })).data;

                if (response.status === "accepted") {
                    this.$store.commit(
                        "selectedCoin",
                        this.$store.state.walletBuyCoinSelectViewData
                            .selectedCoin
                    );
                    this.systemNoticeMessage = this.__("wallet_buy.accepted");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                    this.isPopupVisible = true;
                }
            } catch (e) {
                this.systemNoticeMessage = this.__("wallet_buy.error");
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";

                if (e.response) {
                    const response = e.response.data;
                    if (response.error === "rejected") {
                        this.systemNoticeMessage = this.__(
                            "wallet_buy.rejected"
                        );
                    }
                }

                this.isPopupVisible = true;
            } finally {
                this.buyAmount = 0;
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
