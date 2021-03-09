<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="menu"
            center="text"
            :centerText="'나의지갑'"
            v-on:leftMenuButtonClick="isSideMenuVisible = true"
            v-on:logoClick="fetchData"
            v-on:langButtonClick="isLangSelectVisible = true;"
        ></header-component>
        <div class="ai_container">
            <div class="myinfor_wrap">
                <div class="myinfor_left">
                    <p>
                        <b>{{$store.state.detail.user.fullname || ''}}</b>
                        {{__('home.samano')}}
                        <br />
                        <span>필립스월렛</span>
                    </p>
                </div>
            </div>
            <div class="coin_info_wrap">
                <div class="coin_info_con">
                    <div class="coin_info_hd">
                        <div class="coin_hd_md">
                            <h6 class="bg_img">내 코인 잔액</h6>
                            <h2 id="change_coin_balance">{{$store.state.selectedCoinInfo.balance}}<b>PPC</b></h2>
                            <div>
                                <h6>지갑주소</h6>
                                <p
                                    id="change_coin_address"
                                >{{$store.state.selectedCoinInfo.address}}</p>
                                <div>
                                    <i class="fal fa-copy" @click="copy"></i>
                                    <button ref="clipboard" id="clipboard" :data-clipboard-text="$store.state.selectedCoinInfo.address" style="display:none" />
                                </div>
                            </div>
                        </div>
                        <div class="coin_hd_bt">
                            <ul>
                                <li class="sendPage_btn" @click="$router.replace('/wallet_send')">
                                    <img src="/images/icon/mini_send_top_dark.png" alt />
                                    {{__('home.send')}}
                                </li>
                                <li
                                    class="getsPage_btn"
                                    @click="$router.replace('/wallet_receive')"
                                >
                                    <img src="/images/icon/mini_get_bt_dark.png" alt />
                                    {{__('home.receive')}}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="coin_infor_md">
                        <div class="date_setting_wrap">
                            거래내역
                            <input
                                type="hidden"
                                id="history_days"
                                value="7"
                            />
                            <ul>
                                <li style="width: 100%">
                                    <button
                                        type="button"
                                        @click="isHistoryVisible = true"
                                    >보기</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <lang-select-component :visible.sync="isLangSelectVisible"></lang-select-component>
            <home-side-menu-component :visible.sync="isSideMenuVisible"></home-side-menu-component>
        </div>

        <div id="histoy_panel" :class="{active: isHistoryVisible}">
            <header-component
                center="text"
                :centerText="'거래내역'"
                rightButton="close"
                class="panel-header"
                @closeButtonClick="isHistoryVisible = false"
            ></header-component>
            <div class="ai_container">
                <div class="coin_info_wrap">
                    <div class="date_setting_wrap">
                            조회기간
                            <input
                                type="hidden"
                                id="history_days"
                                value="7"
                            />
                            <ul>
                                <li>
                                    <button
                                        type="button"
                                        @click="increaseAndUpdateHistory(30)"
                                    >{{__('home.plus_one_month')}}</button>
                                </li>
                                <li>
                                    <button
                                        type="button"
                                        @click="increaseAndUpdateHistory(14)"
                                    >{{__('home.plus_two_week')}}</button>
                                </li>
                                <li>
                                    <button
                                        type="button"
                                        @click="increaseAndUpdateHistory(7)"
                                    >{{__('home.plus_one_week')}}</button>
                                </li>
                            </ul>
                        </div>
                    <div class="coin_info_con">
                        <div class="coin_info_hd">
                            <div class="coin_infor_md">
                                <table>
                                    <tbody
                                        v-if="$store.state.selectedCoinHistorys.length > 0"
                                        id="history_tbody"
                                    >
                                        <tr
                                            v-for="history in $store.state.selectedCoinHistorys"
                                            :key="history.id"
                                        >
                                            <td v-if="history.sign === '+' && history.status === 'receive'">
                                                <img src="/images/icon_get.svg" alt="get" />
                                                <span class="get">{{__('home.deposit')}}</span>
                                            </td>
                                            <td
                                                v-else-if="history.sign === '-' && history.status === 'send'"
                                            >
                                                <img src="/images/icon_send.svg" alt="send" />
                                                <span class="send">{{__('home.withdraw')}}</span>
                                            </td>
                                            <td
                                                v-else-if="history.sign === '+' && history.status === 'buy'"
                                            >
                                                <img src="/images/icon_buy.svg" alt="buy" />
                                                <span class="buy">{{__('home.buy_order')}}</span>
                                            </td>
                                            <td
                                                v-else-if="history.sign === '-' && history.status === 'sell'"
                                            >
                                                <img src="/images/icon_sell.svg" alt="sell" />
                                                <span class="sell">{{__('home.sell_order')}}</span>
                                            </td>
                                            <td v-else>
                                                <img />
                                                <span class="send">{{__('home.self')}}</span>
                                            </td>
                                            <td>
                                                <span>
                                                    {{history.from === '__external' ? __('home.external') : history.from}}
                                                    <i>→</i>
                                                    {{history.to === '__external' ? __('home.external') : history.to}}
                                                </span>
                                                <span>{{toMoment(history.date).format('YYYY-MM-DD HH:mm:ss')}}</span>
                                            </td>
                                            <td
                                                v-if="history.sign === '+'"
                                                class="get"
                                            >{{history.sign}}{{history.amount}}</td>
                                            <td
                                                v-else-if="history.sign === '-'"
                                                class="send"
                                            >{{history.sign}}{{history.amount}}</td>
                                            <td v-else>{{history.sign}}{{history.amount}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else id="history_tbody">
                                        <tr>
                                            <td colspan="3" class="non_data">
                                                <img src="/images/icon_sad.svg" alt="sad_icon" />
                                                <br />
                                                {{__('home.no_history')}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <system-notice-component
            :message="systemNoticeMessage"
            :iconSrc="systemNoticeIcon"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import LangSelectComponent from "../components/common/LangSelectComponent";
import HomeSideMenuComponent from "../components/HomeSideMenuComponent";
import HomePayButtonComponent from "../components/HomePayButtonComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";
import ClipboardJS from "clipboard";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
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

        return next(async vm => {
            await vm.fetchData();
        });
    },
    beforeRouteLeave(to, from, next) {
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent,
        "lang-select-component": LangSelectComponent,
        "home-side-menu-component": HomeSideMenuComponent,
        "home-pay-button-component": HomePayButtonComponent,
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
        this.clipboard = new ClipboardJS("#clipboard");
    },
    beforeDestroy() {
        this.clipboard.destroy();
        this.$EventBus.$off("pay-scan-result");
    },
    data() {
        return {
            isVisible: true,
            isLangSelectVisible: false,
            isPopupVisible: false,
            isSideMenuVisible: false,
            isHistoryVisible: false,
            systemNoticeMessage: "",
            systemNoticeIcon: "",
            elWalletList: null,
            clipboard: null
        };
    },
    computed: {
        isOrderableCoin() {
            return (
                this.$store.state.orderableCoins.find(
                    coin => coin.symbol === this.$store.state.selectedCoin
                ) !== undefined
            );
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
                    this.systemNoticeIcon = "/images/checkok_icon.svg";
                } catch (e) {
                    this.systemNoticeMessage = this.__("home.pay_error");
                    this.systemNoticeIcon = "/images/x_icon.svg";

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
        copy() {
            this.$refs.clipboard.click();
            this.systemNoticeIcon = "/images/checkok_icon.svg";
            this.systemNoticeMessage = "주소복사완료";
            this.isPopupVisible = true;
        },
        toMoment(date) {
            return moment(date).add(9, "hours");
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 48px;
    background: #000;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.myinfor_wrap {
    padding: 9px 0;
    text-align: center;
    overflow: hidden;
    position: relative;
    z-index: 1;
    margin-top: 25px;
    margin-bottom: 25px;
}

.myinfor_wrap .myinfor_left {
    float: left;
    padding-left: 28px;
}

.myinfor_wrap p {
    font-size: 16px;
    color: #ccc;
    line-height: 22px;
    padding-top: 12px;
    text-align: left;
    margin-top: 0;
    margin-bottom: 0;
}

.myinfor_wrap p b {
    font-size: 18px;
    color: #fff;
}

.myinfor_wrap p span {
    font-weight: 500;
    color: #ccc;
}

.myinfor_wrap .myinfor_btn {
    float: right;
    padding-right: 15px;
}

.myinfor_btn button {
    background-color: #f8f8f8;
    background-repeat: no-repeat;
    border: none;
    padding: 7px 8px;
    padding-top: 46px;
    font-size: 12px;
    color: #117cff;
    outline: none;
    margin: 0 2px;
}

.myinfor_btn button:first-child {
    background-image: url(/images/button_my_info.svg);
    background-size: 35px;
    background-position: 10px 7px;
}

.myinfor_btn button:last-child {
    background-image: url(/images/button_my_property.svg);
    background-size: 35px;
    background-position: 10px 7px;
}

.wallet_li_wrap {
    margin: 5px 0 95px 0;
    background: #fff;
    overflow-x: scroll;
    position: relative;
    z-index: 1;
}

.wallet_li_wrap ul {
    display: inline-flex;
    padding: 9px 3.5px;
    padding-bottom: 15px;
    width: auto;
    overflow: hidden;
    list-style: none;
    margin: 0;
}

.wallet_li_wrap ul li.major.active {
    border: 1px solid #b446c8;
}

.wallet_li_wrap ul li.minor.active {
    border: 1px solid #829eff;
}

.wallet_li_wrap ul li div:last-child.minor_label {
    background: #829eff;
}

.wallet_li_wrap ul li {
    width: 130px;
    margin: 0 3.5px;
    padding: 10px 7px;
    background-repeat: no-repeat;
    background-position: 7px 10px;
    text-align: right;
    border-radius: 15px;
    box-shadow: rgba(0, 69, 191, 0.2) 0px 3px 10px;
    position: relative;
    float: left;
}

.wallet_li_wrap ul li div:first-child {
    font-size: 11px;
    color: #828282;
    padding: 0 0;
    padding-top: 5px;
    height: 36px;
    width: 83px;
    line-height: 14px;
    float: right;
    letter-spacing: 0.5px;
    display: block;
    overflow: hidden;
}

.wallet_li_wrap ul li div {
    display: block;
    width: 100%;
}

.wallet_li_wrap ul li div:first-child i {
    display: block;
    font-style: normal;
}

.wallet_li_wrap ul li div:nth-child(2) {
    font-size: 15px;
    font-weight: bold;
    color: #5a5a5a;
    padding-top: 3px;
}

.wallet_li_wrap ul li div:last-child {
    position: absolute;
    top: -7px;
    right: 4px;
    color: #fff;
    font-size: 9px;
    width: auto;
    letter-spacing: 0.5px;
    padding: 3px 7px;
    border-radius: 5px;
    text-transform: uppercase;
    vertical-align: middle;
}

.wallet_li_wrap ul li div:last-child.major_label {
    background: #b446c8;
}

.wallet_li_wrap ul li:last-child {
    width: auto !important;
    background: #49d094;
    padding: 0 0;
    overflow: hidden;
    border: none !important;
}

.wallet_li_wrap ul li {
    margin: 0 3.5px;
    text-align: right;
    border-radius: 15px;
    box-shadow: rgba(0, 69, 191, 0.2) 0px 3px 10px;
    position: relative;
    float: left;
}

.wallet_li_wrap ul li:last-child button {
    width: 100%;
    background: #49d094;
    height: 71px;
    border: none;
    padding: 0 15px;
    color: #fff;
    font-size: 12px;
    white-space: nowrap;
}

.wallet_li_wrap ul li:last-child button img {
    width: 27px;
    display: block;
    margin: 0 auto;
    margin-bottom: 8px;
}

.coin_info_wrap {
    min-height: calc(100% - 281px);
}

.coin_info_con {
    border-radius: 6px;
    width: 94%;
    margin: 0 auto;
    overflow: hidden;
    position: relative;
    z-index: 1;
    box-shadow: rgba(0, 69, 191, 0.2) 0px 3px 20px;
}

.coin_info_hd {
    background: #fff;
}

.coin_hd_top {
    padding: 13px 15px;
    display: none;
}

.coin_hd_top img {
    width: 30px;
    margin-right: 8px;
    border: 1px solid #fff;
    border-radius: 50%;
    vertical-align: middle;
}

.coin_hd_top span {
    font-size: 15px;
    color: #fff;
    font-weight: 600;
    vertical-align: middle;
}

.coin_hd_top .change_fold {
    float: right;
    color: #fff;
    margin-top: 6px;
}

.coin_hd_md {
    padding: 10px 15px;
    padding-bottom: 15px;
    color: #000;
    overflow: hidden;
    border-bottom: 1px solid #ddd;
}

.coin_hd_md > div {
    overflow: hidden;
    margin-top: 10px;
}

.coin_hd_md h6{
    margin-bottom: 1px;
    padding-top: 10px;
    font-size: 0.8rem;
    font-weight: bold;
    color: #666;
}

.coin_hd_md h6.bg_img{
    background: url(/images/logo/logo_wordtype.png) no-repeat;
    background-size: 100px;
    background-position: right 2px;
}

.coin_hd_md p {
    float: left;
    font-size: 13px;
    font-weight: 400;
    padding: 6px 0;
    margin: 0;
    letter-spacing: -0.6px;
}

.coin_hd_md div > div {
    float: right;
    padding: 3px;
}

.coin_hd_md > div {
    overflow: hidden;
}

.coin_hd_md h2 {
    font-size: 21px;
    line-height: 1.1;
    margin: 0;
    margin-top: 6px;
}

.coin_hd_md h2 b{
    margin-left: 6px;
}

.coin_hd_bt ul {
    overflow: hidden;
}

.coin_hd_bt ul li {
    float: left;
    width: 50%;
    text-align: center;
    font-size: 14px;
    color: #000;
    font-weight: bold;
    padding: 6px 0;
    letter-spacing: -1px;
    padding-bottom: 8px;
    border-right: 1px solid #ddd;
    vertical-align: middle;
}

.coin_hd_bt ul li:last-child{
    border-right: none;
}

.coin_hd_bt ul li img {
    width: 11px;
    margin: 0 auto;
    vertical-align: middle;
    margin-right: 7px;
    padding: 5px 0;
}

.coin_infor_md {
    background: #fff;
    /* max-height: 1000px; */
    overflow: hidden;
    -webkit-transition: max-height cubic-bezier(0.65, 0.05, 0.36, 1) 0.5s;
    -o-transition: max-height cubic-bezier(0.65, 0.05, 0.36, 1) 0.5s;
    transition: max-height cubic-bezier(0.65, 0.05, 0.36, 1) 0.5s;
}

.coin_infor_md.hidden {
    max-height: 0px;
}

.date_setting_wrap {
    padding: 14px 10px;
    background: #f5f5f5;
    text-align: left;
    color: #646464;
    font-weight: 600;
    position: relative;
    font-size: 13px;
    padding-left: 22px;
}

.date_setting_wrap ul {
    overflow: hidden;
    padding-left: 90px;
    position: absolute;
    top: 6px;
    right: 5px;
    width: 100%;
    text-align: center;
}

.date_setting_wrap ul li {
    width: 33.3%;
    float: left;
    padding: 0 5px;
}

.date_setting_wrap ul li button {
    width: 100%;
    border: 1px solid #dddddd;
    background: #fff;
    border-radius: 10px;
    padding: 7px 0;
    transition: all ease 0.1s;
}

.coin_infor_md table {
    width: 100%;
}

.coin_infor_md .get {
    color: #6265e3;
}

.coin_infor_md .send {
    color: #ffa000;
}

.coin_infor_md table tr {
    border-bottom: 1px solid #f1f1f1;
}

.coin_infor_md table tr td:nth-child(1) img {
    width: 30px;
    vertical-align: middle;
}

.coin_infor_md table tr td {
    width: 33.3%;
    vertical-align: middle;
    padding: 15px 0;
}

.coin_infor_md table tr td:nth-child(1) {
    padding-left: 10px;
}

.coin_infor_md table tr td:nth-child(1) img {
    width: 30px;
    margin-right: 7px;
    vertical-align: middle;
}

.coin_infor_md table tr td:nth-child(1) span {
    font-size: 14px;
    font-weight: bold;
    letter-spacing: -1px;
    vertical-align: middle;
}

.coin_infor_md table tr td:nth-child(2) {
    text-align: center;
}

.coin_infor_md table tr td:nth-child(2) span:first-child {
    display: block;
    font-size: 13px;
    font-weight: bold;
    letter-spacing: -1px;
    color: #5a5a5a;
    margin-bottom: 3px;
}

.coin_infor_md table tr td:nth-child(2) span:first-child i {
    font-style: normal;
    vertical-align: baseline;
    font-size: 9px;
    padding: 0 2px;
}

.coin_infor_md table tr td:nth-child(2) span:last-child {
    display: block;
    font-size: 11px;
    color: #999;
}

.coin_infor_md table tr td:nth-child(3) {
    font-size: 14px;
    font-weight: bold;
    padding-right: 10px;
    text-align: right;
}

.non_data {
    text-align: center;
    padding: 29px 10px !important;
    line-height: 38px;
    font-size: 16px;
    font-weight: 500;
    color: #b5b5b5;
}

#histoy_panel {
    display:block;
    position: fixed;
    top: 100%;
    left:0;
    height: 100%;
    width: 100%;
    z-index: 3;
    transition: top 0.35s ease 0s;
    background-color: black;
}

#histoy_panel.active {
    top: 0;
}

#histoy_panel .panel-header{
    position: absolute;
    top: 0;
    left: 0;
}

#histoy_panel .coin_info_con {
     margin-top: 102px;
     margin-bottom: 12px;
}

#histoy_panel .date_setting_wrap {
    position: absolute;
    top: 48px;
    left: 0;
    right: 0;
    z-index: 4;
}
</style>
