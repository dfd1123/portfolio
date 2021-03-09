<template>
    <div id="trst-main-wrapper">
        <header-component
            leftButton="menu"
            v-on:leftMenuButtonClick="isSideMenuVisible = true"
            v-on:logoClick="fetchData"
            v-on:langButtonClick="isLangSelectVisible = true;"
            :main-header="true"
            :main-header-active="isHideChangeBar == false"
        ></header-component>
        <div class="trst-container" @scroll="scrollEvent">
            <!-- main ) hd-section -->
            <div class="hd-section">
                <div class="hd-section-inner">
                    <div class="main-toggle-coin"  ref="wallet_list">
                        <input 
                            id="toggleTru"
                            type="radio" 
                            class="coin-toggle coin-toggle-left" 
                            name="coin-toggle"
                            @click="selectCoin('tru')"
                            :checked="$store.state.selectedCoin == 'tru' ? true : false"
                        />
                        <label for="toggleTru" class="coin-toggle-btn">tru</label>
                        <input 
                            id="toggleEth"
                            type="radio" 
                            class="coin-toggle coin-toggle-right" 
                            name="coin-toggle" 
                            @click="selectCoin('eth')"
                            :checked="$store.state.selectedCoin == 'eth' ? true : false"
                        />
                        <label for="toggleEth" class="coin-toggle-btn">eth</label>
                    </div>
                    <section class="main-user-status">
                        <h2 class="select-coin-status">
                            <b>{{parseFloat(SelectedCoinBalance).toFixed(2)}}</b>
                            <strong>{{ $store.state.selectedCoin }}</strong>
                        </h2>
                        <span class="other-coin-status" v-if="$store.state.selectedCoin === 'tru'">
                            <strong>1 TRU</strong>
                            <b></b>
                            <strong>{{ $store.state.detail.tru_per_eth }} ETH</strong>
                        </span>
                    </section>
                </div>

                <div id="main-btn-group" class="main-btn-group">
                    <input
                        @click="$router.replace('/wallet_receive')"
                        class="main-btn" 
                        type="button" 
                        :value="__('home.deposit')"
                    >
                    <input
                        @click="$router.replace('/wallet_send')"
                        class="main-btn" 
                        type="button" 
                        :value="__('home.withdraw')"
                    >
                    <input
                        id="buyPage_btn"
                        @click="$router.replace('/wallet_buy_new')" 
                        class="main-btn" 
                        type="button" 
                        :value="__('home.buy_order')"
                    >
                </div>
            </div>
            <!-- END main ) hd-section -->
            <!-- main ) history-section -->
            <div class="history-section" v-bind:class="{hidden: !isHistoryVisible}">
                <div class="history-period-bar">
                    <input
                        type="hidden"
                        id="history_days"
                        value="7"
                    />
                    <h6>{{__('home.trade_term')}}</h6>
                    <input 
                        class="main-btn--period" 
                        @click="increaseAndUpdateHistory(30)"
                        :class="{active:increaseAndUpdateHistory == 1}" 
                        type="button" 
                        :value="__('home.plus_one_month')"
                    />
                    <input 
                        class="main-btn--period" 
                        @click="increaseAndUpdateHistory(14)"
                        :class="{active:increaseAndUpdateHistory == 2}" 
                        type="button" 
                        :value="__('home.plus_two_week')"
                    />
                    <input 
                        class="main-btn--period" 
                        @click="increaseAndUpdateHistory(7)"
                        :class="{active:increaseAndUpdateHistory == 3}" 
                        type="button" 
                        :value="__('home.plus_one_week')"
                    />
                </div>
                <table class="main-history-list">
                    <tbody
                        v-if="$store.state.selectedCoinHistorys.length > 0"
                        id="history_tbody"
                    >
                        <tr 
                            v-for="history in $store.state.selectedCoinHistorys"
                            :key="history.id"
                            class="trans-list"
                        >
                            <!-- 상태 -->
                            <th v-if="history.sign === '+' && history.status === 'receive'" class="trans-status">
                                <img src="/images/trst-images/icon/icon_deposit.svg" alt="deposit icon">
                                <h5 class="get">{{__('home.deposit')}}</h5>    
                            </th>
                            <th v-else-if="history.sign === '-' && history.status === 'send'" class="trans-status">
                                <img src="/images/trst-images/icon/icon_withdrawal.svg" alt="deposit icon">
                                <h5 class="send">{{__('home.withdraw')}}</h5>    
                            </th>
                            <th v-else-if="history.sign === '-' && history.status === 'wait'" class="trans-status">
                                <img src="/images/trst-images/icon/icon_wait.svg" alt="deposit icon">
                                <h5 class="send-wait">출금 대기중</h5>    
                            </th>
                            <th v-else-if="history.sign === '-' && history.status === 'fail'" class="trans-status">
                                <img src="/images/trst-images/icon/icon_cancel.svg" alt="deposit icon">
                                <h5 class="send-fail">출금 실패</h5>    
                            </th>
                            <th v-else-if="history.sign === '+' && history.status === 'buy'" class="trans-status">
                                <img src="/images/trst-images/icon/icon_buy.svg" alt="deposit icon">
                                <h5 class="buy">{{__('home.buy_order')}}</h5>    
                            </th>
                            <th v-else-if="history.sign === '-' && history.status === 'sell'" class="trans-status">
                                <img src="/images/trst-images/icon/icon_sell.svg" alt="deposit icon">
                                <h5 class="sell">{{__('home.sell_order')}}</h5>    
                            </th>
                            <td v-else>
                                <img />
                                <h5 class="send">{{__('home.self')}}</h5>
                            </td>
                            <!-- END 상태 -->

                            <!-- 상세내용 -->
                            <td class="trans-detail">
                                <b>{{history.from === '__external' ? __('home.external') : history.from}}</b>
                                <i v-if="history.from && history.to" class="fal fa-long-arrow-right"></i>
                                <b>{{history.to === '__external' ? __('home.external') : history.to}}</b>
                                <span>{{toMoment(history.date).format('YYYY-MM-DD HH:mm:ss')}}</span>
                            </td>
                            <!-- END 상세내용 -->

                            <!-- 수량 -->
                            <td class="trans-amount">
                                <span 
                                    v-if="history.sign === '+'"
                                    class="get"
                                >
                                    {{history.sign}}{{history.amount}}
                                </span>
                                <span
                                    v-else-if="history.sign === '-'"
                                    class="send"
                                >
                                    {{history.sign}}{{history.amount}}
                                </span>
                                <span v-else>{{history.sign}}{{history.amount}}</span>
                            </td>
                            <!-- END 수량 -->

                        </tr>
                    </tbody>
                    <tbody v-else id="history_tbody">
                        <tr>
                            <td colspan="3" class="nothing-trans-list">
                                <img src="/images/trst-images/icon/icon_empty_list.svg" alt="empty list" />
                                <p>{{__('home.no_history')}}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <lang-select-component :visible.sync="isLangSelectVisible"></lang-select-component>
            <home-side-menu-component :visible.sync="isSideMenuVisible"></home-side-menu-component>
        </div>
        <div :class="{ disable : isHideChangeBar }" class="sub-hd-section-bar">
            <div class="now-coin">
                <img :src="`/images/coin/${$store.state.selectedCoinInfo.symbol.toUpperCase()}.png`" alt="coin image">
                <h3><span v-html="$store.state.selectedCoin == 'tru' ? '트러스톤' : __('coin.eth')"></span> / <strong>{{ $store.state.selectedCoin }}</strong></h3>
            </div>
            <input 
                class="change-coin-btn" 
                type="button" 
                :value="'tru'"
                @click="changeSelectedCoin()"
            >
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import LangSelectComponent from "../components/common/LangSelectComponent";
import HomeSideMenuComponent from "../components/HomeSideMenuComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";
import isVisible from "is-element-visible";

    export default {
        beforeRouteEnter(to, from, next) {
            if (!localStorage.passportToken) {
                return next("/");
            }
            if (
                from.path === "/login" ||
                from.path === "/wallet_send_status" ||
                from.path === "/wallet_buy" ||
                from.path === "/wallet_sell"
            ) {
                return next(async vm => {
                    await vm.fetchData();
                });
            }

            if (from.path === "/user_secret_key_verify") {
                return next(async vm => {
                    try {
                        if (vm.$store.state.payScanData !== "") {
                            await vm.requestPay();
                        }
                    } finally {
                        vm.$store.commit("updatePayScanData", "");
                    }
                });
            }

            next();
        },
        beforeRouteLeave(to, from, next) {
            if (from.path === "/home") {
                this.$store.commit(
                    "updateWalletListScrollLeft",
                    this.$refs.wallet_list.scrollLeft
                );
            }
            next();
        },
        components: {
            "header-component": HeaderComponent,
            "footer-component": FooterComponent,
            "lang-select-component": LangSelectComponent,
            "home-side-menu-component": HomeSideMenuComponent,
            "system-notice-component": SystemNoticeComponent
        },
        beforeCreate() {
            this.$store.commit("progressComponentHide");

            this.$EventBus.$on("pay-scan-result", async event => {
                this.$store.commit("updatePayScanData", event.data);

                this.$router.replace({
                    name: "user_secret_key_verify",
                    params: {
                        backName: "home",
                        proceedName: "home"
                    }
                });
            });
        },
        mounted() {
            this.$refs.wallet_list.scrollLeft = this.$store.state.walletListScrollLeft;
        },
        beforeDestroy() {
            this.$EventBus.$off("pay-scan-result");
        },
        data() {
            return {
                isHideChangeBar: true,
                isVisible: true,
                isLangSelectVisible: false,
                isPopupVisible: false,
                isSideMenuVisible: false,
                isMyPropertyVisible: false,
                isHistoryVisible: true,
                systemNoticeMessage: "",
                systemNoticeIcon: "",
                elWalletList: null

            };
        },
        computed: {
            isOrderableCoin() {
                return (
                    this.$store.state.orderableCoins.find(
                        coin => coin.symbol === this.$store.state.selectedCoin
                    ) !== undefined
                );
            },
            SelectedCoinBalance(){
                return this.$store.state.selectedCoinInfo.balance;
            }
        },
        methods: {
            async fetchData() {
                try {
                    this.$store.commit("progressComponentShow");

                    this.$store.commit("selectedCoinHistoryTotalDuration", 14);

                    const datas = (await Promise.all([
                        axios.get(`/api/detail`),
                        axios.get(
                            `/api/wallet/info/${this.$store.state.selectedCoin}`
                        ),
                        axios.get(`/api/wallet/history`, {
                            params: {
                                symbol: this.$store.state.selectedCoin,
                                days: this.$store.state
                                    .selectedCoinHistoryTotalDuration
                            }
                        })
                    ])).map(response => {
                        return response.data;
                    });

                    this.$store.commit("detail", datas[0]);
                    this.$store.commit("selectedCoinInfo", datas[1]);
                    this.$store.commit("selectedCoinHistorys", datas[2]);
                } finally {
                    this.$store.commit("progressComponentHide");
                }
            },
        async selectCoin(symbol) {
            try {
                if (this.$store.state.selectedCoin === symbol) {
                    return;
                }

                this.$store.commit("progressComponentShow");

                this.$store.commit(
                    "selectedCoinInfo",
                    (await axios.get(`/api/wallet/info/${symbol}`)).data
                );
                this.$store.commit("selectedCoin", symbol);

                const index = this.$store.state.favors.findIndex(
                    item => item.symbol === this.$store.state.selectedCoin
                );

                this.$store.commit("updateFavors", {
                    index,
                    value: {
                        ...this.$store.state.favors[index],
                        ...{
                            balance: this.$store.state.selectedCoinInfo.balance
                        }
                    }
                });

                this.$store.commit("selectedCoinHistoryTotalDuration", 14);
                this.$store.commit(
                    "selectedCoinHistorys",
                    (await axios.get(`/api/wallet/history`, {
                        params: {
                            symbol: this.$store.state.selectedCoin,
                            days: this.$store.state
                                .selectedCoinHistoryTotalDuration
                        }
                    })).data
                );
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        async getSelectedCoinHistory() {
            try {
                this.$store.commit("progressComponentShow");

                this.$store.commit(
                    "selectedCoinHistorys",
                    (await axios.get(`/api/wallet/history`, {
                        params: {
                            symbol: this.$store.state.selectedCoin,
                            days: this.$store.state
                                .selectedCoinHistoryTotalDuration
                        }
                    })).data
                );
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        async increaseAndUpdateHistory(duration) {
            this.$store.commit(
                "selectedCoinHistoryTotalDuration",
                this.$store.state.selectedCoinHistoryTotalDuration + duration
            );

            await this.getSelectedCoinHistory();
        },
        async requestPay() {
            if (this.$route.params.verify === true) {
                try {
                    this.$store.commit("progressComponentShow");

                    const queryString = this.$store.state.payScanData.split(
                        "?"
                    )[1];
                    const parsed = new URLSearchParams(queryString);

                    await axios.post(`/api/wallet/pay`, {
                        coin: parsed.get("cointype"),
                        amount: parsed.get("amount"),
                        pay_order_id: parsed.get("pay_order_id")
                    });

                    this.systemNoticeMessage = this.__("home.pay_ok");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                } catch (e) {
                    this.systemNoticeMessage = this.__("home.pay_error");
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";

                    if (e.response) {
                        const response = e.response.data;
                        if (response.error === "Insufficient Balance") {
                            this.systemNoticeMessage = this.__(
                                "home.pay_insufficient_balance"
                            );
                        } else if (response.error === "Order Not Found") {
                            this.systemNoticeMessage = this.__(
                                "home.pay_order_not_found"
                            );
                        }
                    }
                } finally {
                    await this.fetchData();
                    this.isPopupVisible = true;
                    this.$store.commit("progressComponentHide");
                }
            }
        },
        toMoment(date) {
            return moment(date).add(9, "hours");
        },
        scrollEvent (){
            this.isHideChangeBar = isVisible(document.getElementById("main-btn-group"));
        }
        }
    }
</script>

<style scoped>
html{
    box-shadow: 1px 1px 11px rgba(0,0,0,0.2);
}

#trst-main-wrapper {
    height: 100%;
}

.trst-container {
    padding: 0;
    width: 100%;
    height: 100%;
    min-height: 100%;
    overflow-x: hidden;
    overflow-y: scroll;
}

::-webkit-scrollbar{
    display: none;
}

.hd-section {
    padding-top: 2.815rem;
    padding-bottom: 2.5em;
    text-align: center;
    color: white;
    background: linear-gradient(to bottom, rgb(100, 225, 150), rgb(25, 180, 170));
    height: 300px;
    position: relative;
    z-index: 1;
}

.hd-section-inner {
    height: 100%;
    width: 100%;
    position: relative;
    display: table;
}

.main-user-status {
    font: 1em/1.6 'Spoqa Han Sans', 'Sans-serif';
    display: table-cell;
    vertical-align: middle;
    width: 100%;
    text-align: center;
    opacity: 0;
    transform: translateY(2rem);
    animation: verticalAni2 0.8s 1 0.5s;
    animation-timing-function: cubic-bezier(0.51, 0.92, 0.24, 1.15);
    animation-fill-mode: both;
}

.select-coin-status{
    font-size: 1.88em;
    font-weight: 500;
    letter-spacing: 0;
    padding: 45px 0 3px;
    margin-bottom: 0;
}

.select-coin-status > b, 
.select-coin-status > strong{
    font-weight: inherit;
}

.select-coin-status > strong{
    padding-left: 2px;
    text-transform: uppercase;
}

.other-coin-status{
    display: inline-block;
    width: 100%;
    font-weight: 300;
    letter-spacing: 0;
    opacity: 0.7;
}

.other-coin-status > b,
.other-coin-status > strong{
    font-weight: inherit;
}

.other-coin-status > b:before{
    content: '= ';
}

.other-coin-status > strong{
    padding-left: 5px;
    text-transform: uppercase;
}

.main-toggle-coin {
    position: absolute;
    top: 0;
    left: 50%;
    z-index: 1;
    transform: translateX(-50%);
    top: 20px;
    width: 135px;
    border: 1px solid rgba(255,255,255,0.6);
    background-color: rgba(255,255,255,0.11);
    border-radius: 50px;
    height: 32px;
    box-shadow: 0 6px 9px rgba(0, 68, 68, 0.12), 0 12px 12px rgba(0, 68, 68, 0.05) inset;
    
    opacity: 0;
    transform: translateX(-50%) translateY(1rem);
    animation: verticalAni1 1s 1 0.2s;
    animation-timing-function: cubic-bezier(0.51, 0.92, 0.24, 1.15);
    animation-fill-mode: both;
}

.coin-toggle {
    display: none;
}

.coin-toggle-btn{
    margin-bottom: 0;
    width: 50%;
    float: left;
    text-align: center;
    height: 100%;
    line-height: 29px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.8);
    font-weight: 400;
    letter-spacing: 0;
    position: relative;
    transition: background 600ms ease, color 300ms ease;
}

.coin-toggle-btn:after {
    display: inline-block;
    content: '';
    height: 100%;
    position: absolute;
    top: 0;
    width: 100%;
    border-radius: 50px;
    background-color: white;
    left: 0;
    z-index: -1;
    transition: left 200ms cubic-bezier(0.77, 0, 0.175, 1);
}

.coin-toggle-left + .coin-toggle-btn:after{
    left: 100%;
}

.coin-toggle-right + .coin-toggle-btn:after{
    left: -100%;
}

.coin-toggle:checked + label{
    color: #3A6E64;
}

.coin-toggle:checked + label:after{
    left: 0;
}

.main-btn-group {
    background-color: rgba(0, 90, 80, 0.2);
    height: 2.5em;
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    display: flex;
    padding: 0.7em 0;
}

.main-btn-group .main-btn {
    flex: 1;
    background: transparent;
    color: white;
    border: 0;
    border-radius: 0;
    box-shadow: none;
    font-weight: 300;
    font-size: 0.95em;
}

.main-btn-group .main-btn:nth-child(2) {
    border-right: 1px solid rgba(255, 255, 255, 0.3);
    border-left: 1px solid rgba(255, 255, 255, 0.3);
}

.main-btn-group .main-btn:last-child{
    border-right: 0;
}

.main-btn-group .main-btn:active{
    color: rgba(255,255,255,0.7);
}

.history-section{
    z-index: 1;
    position: relative;
}

.history-period-bar {
    background-color: #F5F5F5;
    text-align: right;
    position: relative;
    padding: 10px 13px;
}

.history-period-bar > h6 {
    display: inline-block;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 15px;
    margin-bottom: 0;
    font-size: 12px;
    font-weight: 300;
    color: #505050;
}

.main-btn--period {
    font-size: 12px;
    font-weight: 400;
    color: #505050;
    background-color: white;
    border: 0;
    border-radius: 30px;
    height: 24px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    margin: 0 3px;
    border: 1px solid white;
}

.main-btn--period.active{
    transition: all 0.2s;
    border: 1px solid #64E196;
}

.main-btn--period:active{
    transition: all 0.8s;
    border: 1px solid #64E196;
}

.main-history-list {
    width: 100%;
}

.nothing-trans-list{
    padding: 50px 0;
}

.nothing-trans-list > img{
    display: block;
    margin: 0 auto 13px;
}

.nothing-trans-list > p{
    color: #C8DCDC;
    text-align: center;
    font-weight: 500;
    font-size: 1.25em;
}

@media all and (min-width: 320px) and (max-width: 360px) {
    .main-history-list{
        font-size: 13.5px;
    }
}

.main-history-list .trans-list {
    border-bottom: 1px solid #EBF0F0;
    text-align: center;
    color: #505050;
    padding: 10px 0;
    height: 3.5em;
}

.main-history-list .trans-list:last-child{
    border-bottom: 0;
}

.main-history-list .trans-status {
    text-align: left;
    vertical-align: middle;
    font-size: 0.88em;
    padding-left: 15px;
    width: 30%;
}

.main-history-list .trans-status > img, 
.main-history-list .trans-status > h5 {
    display: inline-block;
    vertical-align: middle;
}

.main-history-list .trans-status > img{
    margin-right: 3px;
    width: 1.55em;
}

.main-history-list .trans-status > h5 {
    margin: 0;
    font-size: inherit;
    font-weight: 400;
}

.main-history-list .trans-detail {
    text-align: inherit;
    font-size: 0.8em;
    vertical-align: middle;
}

.main-history-list .trans-detail > i {
    padding: 0 5px;
}

.main-history-list .trans-detail > span {
    display: inline-block;
    width: 100%;
    letter-spacing: 0.2px;
    font-size: 0.85em;
    color: #AAAAAA;
    font-weight: 100;
    padding-top: 5px;
}

.main-history-list .trans-amount {
    font-weight: 400;
    font-size: 0.88em;
    font-weight: normal;
    text-align: right;
    vertical-align: middle;
    letter-spacing: 0.2px;
    padding-right: 13px;
    width: 30%;
}

.main-history-list .trans-amount > span {
    font-size: inherit;
}

.main-history-list .get {
    color: #6265e3;
}

.main-history-list .send {
    color: #ffa000;
}

.main-history-list .send-wait {
    color: #2DA804;
}

.main-history-list .send-fail {
    color: #969696;
}

.main-history-list .buy {
    color: #00ACDF;
}

.main-history-list .sell {
    color: #d965e3;
}

.sub-hd-section-bar{
    background-color: #282828;
    position: fixed;
    top: 2.815rem;
    left: 50%;
    z-index: 1;
    height: 45px;
    width: 100%;
    max-width: 500px;
    transform: translateX(-50%);
    color: white;
    box-shadow: 0 3px 10px #C3D7D7;
    text-align: right;
    transition: all 0.2s;
    opacity: 1;
    pointer-events: auto;
}

.sub-hd-section-bar.disable{
    top: 2.5rem;
    opacity: 0;
    pointer-events: none;
}

.sub-hd-section-bar .now-coin {
    display: inline-block;
    position: absolute;
    top: 50%;
    left: 13px;
    transform: translateY(-50%);
    text-align: left;
}

.sub-hd-section-bar .now-coin > img {
    width: 25px;
    margin-right: 5px;
}

.sub-hd-section-bar .now-coin > h3{
    margin: 0;
    font-weight: 400;
    display: inline-block;
    vertical-align: middle;
    font-size: 1rem;
}

.sub-hd-section-bar .now-coin strong{
    font-weight: inherit;
    text-transform: uppercase;
}

.change-coin-btn {
    width: 60px;
    height: 100%;
    background-color: transparent;
    border: 0;
    text-transform: uppercase;
    border-radius: 0;
    color: #64E196;
    font-size: 13px;
    background-image: url(/images/trst-images/icon/icon_change_coin.svg);
    background-position: left 50%;
    background-repeat: no-repeat;
}

.change-coin-btn:active{
    opacity: 0.8;
}

@keyframes verticalAni1 {
    100%{
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

@keyframes verticalAni2 {
    100%{
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
