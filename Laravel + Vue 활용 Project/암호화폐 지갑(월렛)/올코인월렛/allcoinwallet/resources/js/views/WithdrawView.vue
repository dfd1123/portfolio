<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('withdraw.title')"
            rightButton="home"
        ></header-component>

        <div class="ai_container bgcolor scroll_area">
            <div class="sd_box out_info_box">
                <div class="form_line">
                    <label class="label_hr bt_15px">{{__('withdraw.label1')}}</label>
                    <div class="in_form_line keep_form info_line">
                        <p class="in_line">
                            <span
                                class="label"
                            >{{$store.state.detail.security.account_bank || __('withdraw.unregistered')}}</span>
                            <span class="account">{{$store.state.detail.security.account_num || ''}}</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="sd_box out_prc_box">
                <div class="form_line">
                    <label class="label_hr">{{__('withdraw.label2')}}</label>
                    <div class="in_form_line">
                        <input
                            type="text"
                            class="buy_input_area"
                            id="withdraw_balance"
                            :value="$store.state.asset.cash_asset"
                            readonly
                        />
                        <span class="krw top_5">KRW</span>
                    </div>
                </div>
            </div>
            <div class="sd_box out_prc_box">
                <div class="form_line">
                    <label class="label_hr">{{__('withdraw.label3')}}</label>
                    <div class="in_form_line">
                        <span
                            class="max_btn"
                            @click="withdrawAmount = maximumWithdrawAmount"
                        >{{__('withdraw.max')}}</span>
                        <input
                            type="number"
                            class="buy_input buy_input_area"
                            v-model.number="withdrawAmount"
                        />
                        <span class="krw top_5">KRW</span>
                    </div>
                    <div class="in_form_line keep_form info_line">
                        <span class="keep_value_tt">
                            {{__('withdraw.withdraw_fee')}}
                            <u>{{__('withdraw.withdraw_tax_incl')}}</u>
                        </span>
                        <p
                            class="keep_value_form"
                            id="copy_susu_keyup"
                        >{{this.$store.state.asset.cash_withdraw_fee}}</p>
                        <span class="krw">KRW</span>
                    </div>
                    <div class="in_form_line keep_form info_line">
                        <span class="keep_value_tt">
                            {{__('withdraw.withdraw_amount')}}
                            <u>{{__('withdraw.withdraw_tax_incl')}}</u>
                        </span>
                        <p class="keep_value_form">{{totalAmountToLocaleString}}</p>
                        <span class="krw">KRW</span>
                    </div>
                </div>
            </div>

            <div class="sd_box inout_history" :style="{height: historyVisible ? '544px' : '36px'}">
                <div class="form_line">
                    <label class="label_hr">
                        {{__('withdraw.label4')}}
                        <i
                            v-if="historyVisible"
                            class="far fa-angle-down fold"
                            @click="historyVisible = false"
                        ></i>
                        <i v-else class="far fa-angle-up fold" @click="historyVisible = true"></i>
                    </label>
                </div>
                <div class="inout_wrapper">
                    <ul class="sch_box">
                        <li
                            :class="{active: selectedHistoryType === 'all'}"
                            @click="selectedHistoryType = 'all'"
                        >{{__('withdraw.all')}}</li>
                        <li
                            :class="{active: selectedHistoryType === 'deposit'}"
                            @click="selectedHistoryType = 'deposit'"
                        >{{__('withdraw.deposit')}}</li>
                        <li
                            :class="{active: selectedHistoryType === 'withdraw'}"
                            @click="selectedHistoryType = 'withdraw'"
                        >{{__('withdraw.withdraw')}}</li>
                    </ul>
                    <ul class="inout_title inout_item">
                        <li>{{__('withdraw.inout')}}</li>
                        <li>{{__('withdraw.amount')}}</li>
                        <li>{{__('withdraw.status')}}</li>
                        <li>{{__('withdraw.date')}}</li>
                    </ul>
                    <ul class="inout_table inout_item">
                        <li v-for="history in filteredHistories" :key="history.id" class="li">
                            <ul class="inout_table_li">
                                <li>
                                    <p
                                        v-if="history.type === 'deposite'"
                                        class="in_color"
                                    >{{__('withdraw.deposit')}}</p>
                                    <p
                                        v-else-if="history.type === 'withdraw'"
                                        class="out_color"
                                    >{{__('withdraw.withdraw')}}</p>
                                </li>
                                <li>
                                    <p>
                                        <span>
                                            {{Number(history.amount).toLocaleString()}}
                                            <span>KRW</span>
                                        </span>
                                    </p>
                                </li>
                                <li>
                                    <p
                                        v-if="history.status === 'withdraw_request'"
                                        class="cancelable_request"
                                    >
                                        {{__('withdraw.request_withdraw')}}
                                        <button
                                            type="button"
                                            class="out_cancel"
                                            @click="cancelRequestButtonClick(history)"
                                        >{{__('withdraw.request_cancel')}}</button>
                                    </p>
                                    <p
                                        v-else-if="history.status === 'deposite_request'"
                                        class="cancelable_request"
                                    >
                                        {{__('withdraw.request_waiting')}}
                                        <button
                                            type="button"
                                            class="in_cancel"
                                            @click="cancelRequestButtonClick(history)"
                                        >{{__('withdraw.request_cancel')}}</button>
                                    </p>
                                    <p
                                        v-else-if="history.status === 'withdraw_cancel'"
                                    >{{__('withdraw.withdraw_cancel')}}</p>
                                    <p
                                        v-else-if="history.status === 'deposite_cancel'"
                                    >{{__('withdraw.deposite_cancel')}}</p>
                                    <p
                                        v-else-if="history.status === 'withdraw_reject'"
                                    >{{__('withdraw.withdraw_reject')}}</p>
                                    <p
                                        v-else-if="history.status === 'deposite_reject'"
                                    >{{__('withdraw.deposite_reject')}}</p>
                                    <p
                                        v-else-if="history.status === 'confirm' && history.type === 'withdraw'"
                                    >{{__('withdraw.confirm_withdraw')}}</p>
                                    <p
                                        v-else-if="history.status === 'confirm' && history.type === 'deposite'"
                                    >{{__('withdraw.confirm_deposit')}}</p>
                                </li>
                                <li>
                                    <p>{{toMoment(history.updated).format('YYYY-MM-DD')}}</p>
                                    <p>{{toMoment(history.updated).format('HH:mm')}}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <footer-component
            :buttonText="__('withdraw.request_withdraw')"
            :active="isReadyToWithdraw"
            v-on:buttonClick="withdrawButtonClick"
        ></footer-component>
        <system-notice-component
            :message="systemNoticeMessage"
            :iconSrc="systemNoticeIcon"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
        ></system-notice-component>
        <system-confirm-component
            :message="systemConfirmMessage"
            :iconSrc="systemConfirmIcon"
            :visible.sync="isConfirmVisible"
            v-on:yesButtonClick="cancelRequest"
        ></system-confirm-component>
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
        next(vm => {
            if (Number(vm.$store.state.detail.security.status) < 3) {
                return next("/");
            }
        });
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
            historyVisible: false,
            systemNoticeIcon: "",
            systemNoticeMessage: "",
            systemConfirmIcon: "",
            systemConfirmMessage: "",
            withdrawAmount: '',
            selectedHistoryType: "all",
            selectedHistory: null,
            histories: []
        };
    },
    async created() {
        await this.fetchData();
    },
    watch: {
        withdrawAmount() {
            if (this.maximumWithdrawAmount <= 0) {
                this.withdrawAmount = 0;
            } else if (
                Number(this.withdrawAmount) > this.maximumWithdrawAmount
            ) {
                this.withdrawAmount = this.maximumWithdrawAmount;
            } else if (Number(this.withdrawAmount) < 0) {
                this.withdrawAmount = 0;
            }
        }
    },
    computed: {
        isReadyToWithdraw() {
            return (
                this.withdrawAmount &&
                this.withdrawAmount !== 0 &&
                this.withdrawAmount <=
                    Number(
                        this.$store.state.asset.cash_asset.replace(/,/gi, "")
                    )
            );
        },
        filteredHistories() {
            if (this.selectedHistoryType === "all") {
                return this.histories;
            } else if (this.selectedHistoryType === "deposit") {
                return this.histories.filter(history => {
                    return history.type === "deposite";
                });
            } else if (this.selectedHistoryType === "withdraw") {
                return this.histories.filter(history => {
                    return history.type === "withdraw";
                });
            }
        },
        maximumWithdrawAmount() {
            return (
                Number(this.$store.state.asset.cash_asset.replace(/,/gi, "")) -
                Number(
                    this.$store.state.asset.cash_withdraw_fee.replace(/,/gi, "")
                )
            );
        },
        totalWithdrawAmount() {
            return (
                Number(this.withdrawAmount) +
                Number(
                    this.$store.state.asset.cash_withdraw_fee.replace(/,/gi, "")
                )
            );
        },
        totalAmountToLocaleString() {
            const str = Number(this.totalWithdrawAmount).toLocaleString();
            return str === "0" ? "" : str;
        }
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("progressComponentShow");

                this.histories = (await axios.get(
                    `/api/wallet/cash_history`
                )).data;

                this.$store.commit(
                    "asset",
                    (await axios.get(`/api/wallet/asset`)).data
                );
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        async withdrawButtonClick() {
            if (this.isReadyToWithdraw) {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.post(`/api/wallet/cash_withdraw`, {
                        amount: this.withdrawAmount,
                        symbol: "krw"
                    });
                    this.withdrawAmount = '';

                    this.systemNoticeIcon = "/images/checkok_icon.svg";
                    this.systemNoticeMessage = this.__(
                        "withdraw.withdraw_message"
                    );
                    this.systemConfirmIcon = "/images/checkok_icon.svg";
                } catch (e) {
                    this.systemNoticeIcon = "/images/x_icon.svg";
                    this.systemNoticeMessage = this.__(
                        "withdraw.withdraw_already_exists"
                    );

                    if (e.response) {
                        const response = e.response.data;
                        if (response.error === "over_limit") {
                            this.systemNoticeMessage = this.__(
                                "withdraw.withdraw_max_error"
                            );
                        }
                    }
                } finally {
                    this.isPopupVisible = true;
                    await this.fetchData();
                    this.$store.commit("progressComponentHide");
                }
            }
        },
        cancelRequestButtonClick(history) {
            if (history.type === "deposite") {
                this.systemConfirmIcon = "/images/receive_check.svg";
                this.systemConfirmMessage = this.__(
                    "withdraw.deposit_cancel_ask"
                );
            } else if (history.type === "withdraw") {
                this.systemConfirmIcon = "/images/send_check.svg";
                this.systemConfirmMessage = this.__(
                    "withdraw.withdraw_cancel_ask"
                );
            }
            this.selectedHistory = history;
            this.isConfirmVisible = true;
        },
        async cancelRequest() {
            try {
                this.$store.commit("progressComponentShow");

                await axios.post(`/api/wallet/cash_cancel`, {
                    id: this.selectedHistory.id,
                    type: this.selectedHistory.type === "deposite" ? 0 : 1 //0이면 입금, 1이면 출금
                });

                this.systemNoticeIcon = "/images/checkok_icon.svg";
                this.systemNoticeMessage = this.__("withdraw.cancel_ok");
                this.isPopupVisible = true;

                await this.fetchData();
            } finally {
                this.selectedHistory = null;
                this.$store.commit("progressComponentHide");
            }
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
    padding-top: 43px;
    margin-bottom: -53px;
    padding-bottom: 52px;
}

.ai_wrapper.top_0 {
    padding-top: 45px;
}

.scroll_area {
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: #fafafa;
    height: 100%;
}

.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 5px 20px rgba(0, 69, 191, 0.2);
    margin: 0 auto;
    position: relative;
    top: 2vh;
    padding: 15px;
    margin-bottom: 15px;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.sd_box .form_line {
    width: 100%;
    height: auto;
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

.info_line {
    width: 100%;
    height: 30px;
    background-color: #f6f6f6;
    line-height: 30px;
    margin: 10px 0 15px;
    padding-left: 10px;
    font-size: 15px;
    color: #5a5a5a;
    font-weight: 500;
}

.keep_form {
    margin: 5px 0;
}

.sd_box.out_info_box .info_line .in_line {
    font-size: 13px;
    font-weight: 600;
}

.sd_box.out_info_box .label {
    color: #0072ff;
    margin-right: 5px;
}

.label,
.label.label-default {
    background-color: #9e9e9e;
}

.sd_box.out_info_box .info_line .account {
    font-size: 15px;
}

.label {
    border-radius: 1px;
    padding: 0.3em 0.6em;
}

.label {
    display: inline;
    padding: 0.6em 0.6em 0.3em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25em;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
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

.ai_wrapper .max_btn {
    font-size: 13px;
    color: #fff;
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

.keep_form .keep_value_tt {
    font-size: 13px;
    color: #5a5a5a;
    font-weight: 600;
    padding: 0;
}

.sd_box.out_prc_box .in_form_line .keep_value_tt u {
    font-size: 12px;
    text-decoration: none;
}

.sd_box.out_prc_box .in_form_line:nth-child(3) {
    background: 0 0;
    font-weight: 400;
}

.keep_form .keep_value_form {
    position: absolute;
    top: 0;
    padding: 0 49px;
    text-align: right;
    width: 100%;
}

.ai_wrapper .sd_box.inout_history {
    padding: 10px 0;
    overflow: hidden;
    height: 36px;
    margin-bottom: 30px;
    transition: height ease-in-out 0.3s;
}

.sd_box.inout_history .form_line .label_hr {
    margin-bottom: 0;
    padding-left: 15px;
}

.sd_box.inout_history .form_line .label_hr .fold {
    position: absolute;
    right: 20px;
    font-size: 20px;
    top: 9px;
    color: #ffa000;
}

.sd_box.inout_history .inout_wrapper {
    position: relative;
}

.sd_box.inout_history .sch_box {
    width: 93%;
}

.sd_box.inout_history .sch_box li:nth-child(3) {
    border-right: 1px solid #dcdcdc;
}

.sch_box {
    width: 90%;
    margin: 0 auto;
    background-color: #fff;
    position: relative;
    top: 2.5vh;
}

.sch_box li {
    float: left;
    width: 50%;
    border: 1px solid #dcdcdc;
    padding: 10px;
    background-color: #fff;
    text-align: center;
    font-size: 13px;
    color: #5a5a5a;
}

.sch_box li.active {
    border: 1px solid #0072ff;
    color: #222;
}

.sd_box.inout_history .sch_box li {
    width: 33.33%;
    border-right: 0;
}

.sd_box.inout_history .sch_box li.active {
    border: 2px solid #0072ff;
    padding: 9px 10px;
}

.sd_box.inout_history .inout_wrapper[data-v-11c47c94] {
    position: relative;
}

.sd_box .form_line {
    width: 100%;
    margin-bottom: 15px;
}

.sd_box.inout_history .form_line .label_hr {
    margin-bottom: 0;
    padding-left: 15px;
}

.sd_box.inout_history .inout_wrapper {
    position: relative;
}

.sd_box.inout_history .sch_box {
    width: 93%;
}

.sch_box li {
    float: left;
    width: 50%;
    border: 1px solid #dcdcdc;
    padding: 10px;
    background-color: #fff;
    text-align: center;
    font-size: 13px;
    color: #5a5a5a;
}

.sch_box li.active {
    border: 1px solid #0072ff;
    color: #222;
}

.sd_box.inout_history .sch_box li {
    width: 33.33%;
    border-right: 0;
}

.sd_box.inout_history .sch_box li.active {
    border: 2px solid #0072ff;
    padding: 9px 10px;
}

.sd_box.inout_history .inout_title {
    top: 75px;
    background: #f2f2f2;
    font-size: 11px;
    padding: 5px 0;
    color: #5a5a5a;
    position: absolute;
    font-size: 12px;
    border-bottom: 1px solid #dcdcdc;
}

.sd_box.inout_history .inout_item {
    width: 100%;
    text-align: center;
    position: absolute;
    border-top: 1px solid #dcdcdc;
}

.sd_box.inout_history .inout_title li {
    float: left;
    height: 100%;
}

.sd_box.inout_history .inout_title li:nth-child(1),
.inout_table .inout_table_li li:nth-child(1) {
    width: 15%;
}

.sd_box.inout_history .inout_title li:nth-child(2),
.inout_table .inout_table_li li:nth-child(2) {
    width: 35%;
}

.sd_box.inout_history .inout_title li:nth-child(3),
.inout_table .inout_table_li li:nth-child(3) {
    width: 26%;
}

.sd_box.inout_history .inout_title li:nth-child(4),
.inout_table .inout_table_li li:nth-child(4) {
    width: 24%;
}

.sd_box.inout_history .inout_item {
    width: 100%;
    text-align: center;
    position: absolute;
    border-top: 1px solid #dcdcdc;
}

.sd_box.inout_history .inout_table {
    top: 98px;
    font-size: 13px;
    background: #fff;
    height: 405px;
    overflow: scroll;
    overflow-x: hidden;
    color: #333;
}

.sd_box.inout_history .inout_table .li,
.sd_box.inout_history .inout_table_li {
    width: 100%;
    height: 70px;
    border-bottom: 1px solid #dcdcdc;
}

.sd_box.inout_history .inout_table .li,
.sd_box.inout_history .inout_table_li {
    width: 100%;
    height: 70px;
    border-bottom: 1px solid #dcdcdc;
}

.sd_box.inout_history .inout_table_li > li {
    float: left;
    border-right: 1px solid #dcdcdc;
    height: 100%;
}

.sd_box.inout_history .inout_title li:nth-child(1),
.inout_table .inout_table_li li:nth-child(1) {
    width: 15%;
}

.inout_table .inout_table_li li:nth-child(1),
.inout_table .inout_table_li li:nth-child(2) {
    line-height: 70px;
}

.inout_table .out_color {
    color: #ffa000;
    font-weight: 600;
}

.inout_table .in_color {
    color: #6265e3;
    font-weight: 600;
}

.inout_table u {
    text-decoration: none;
    margin-left: 3px;
}

.sd_box.inout_history .inout_table_li > li {
    float: left;
    border-right: 1px solid #dcdcdc;
    height: 100%;
}

.sd_box.inout_history .inout_title li:nth-child(3),
.inout_table .inout_table_li li:nth-child(3) {
    width: 26%;
}

.inout_table .inout_table_li li:nth-child(3),
.inout_table .inout_table_li li:nth-child(4) {
    padding: 28px 0;
}

.sd_box.inout_history .inout_title li:nth-child(4),
.inout_table .inout_table_li li:nth-child(4) {
    width: 24%;
}

.inout_table .inout_table_li li:nth-child(4) {
    padding: 18px 0;
    line-height: 18px;
    border-right: 0;
}

.inout_table button {
    border: 0;
    border-radius: 5px;
    font-size: 12px;
    padding: 2px 9.5px;
    background: #fff;
    margin-top: 7px;
    display: inline-block;
}

.inout_table button.in_cancel {
    border: 1px solid #6265e3;
    color: #6265e3;
}

.inout_table button.out_cancel {
    border: 1px solid #ffa000;
    color: #ffa000;
}

.cancelable_request {
    margin-top: -12px;
}
</style>
