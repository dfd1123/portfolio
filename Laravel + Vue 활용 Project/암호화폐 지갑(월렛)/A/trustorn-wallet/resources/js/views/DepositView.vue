<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('deposit.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor scroll_area">
            <div class="sd_box in_info_box">
                <div class="form_line">
                    <label class="label_hr bt_15px">{{__('deposit.label1')}}</label>
                    <p>
                        <span>
                            {{__('deposit.bank_info1')}}
                            <b>{{__('deposit.bank_info2')}}</b>
                        </span>
                        <span>{{__('deposit.bank_info3')}}</span>
                    </p>
                    <span class="s_text">{{__('deposit.info1')}}</span>
                    <span class="s_text">{{__('deposit.info2')}}</span>
                    <span class="s_text">{{__('deposit.info3')}}</span>
                </div>
            </div>
            <div class="sd_box in_prc_box">
                <div class="form_line">
                    <label class="label_hr">{{__('deposit.label2')}}</label>
                    <div class="in_form_line">
                        <input
                            type="number"
                            class="buy_input_area"
                            v-model.number="depositAmount"
                        />
                        <span class="krw">KRW</span>
                    </div>
                    <div class="in_form_line keep_form info_line">
                        <span class="keep_value_tt">{{__('deposit.deposit_amount')}}</span>
                        <p class="keep_value_form">{{totalAmountToLocaleString}}</p>
                        <span class="krw">KRW</span>
                    </div>
                </div>
            </div>

            <div class="sd_box inout_history" :style="{height: historyVisible ? '544px' : '36px'}">
                <div class="form_line">
                    <label class="label_hr">
                        {{__('deposit.label3')}}
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
                        >{{__('deposit.all')}}</li>
                        <li
                            :class="{active: selectedHistoryType === 'deposit'}"
                            @click="selectedHistoryType = 'deposit'"
                        >{{__('deposit.deposit')}}</li>
                        <li
                            :class="{active: selectedHistoryType === 'withdraw'}"
                            @click="selectedHistoryType = 'withdraw'"
                        >{{__('deposit.withdraw')}}</li>
                    </ul>
                    <ul class="inout_title inout_item">
                        <li>{{__('deposit.inout')}}</li>
                        <li>{{__('deposit.amount')}}</li>
                        <li>{{__('deposit.status')}}</li>
                        <li>{{__('deposit.date')}}</li>
                    </ul>
                    <ul class="inout_table inout_item">
                        <li v-for="history in filteredHistories" :key="history.id" class="li">
                            <ul class="inout_table_li">
                                <li>
                                    <p
                                        v-if="history.type === 'deposite'"
                                        class="in_color"
                                    >{{__('deposit.deposit')}}</p>
                                    <p
                                        v-else-if="history.type === 'withdraw'"
                                        class="out_color"
                                    >{{__('deposit.withdraw')}}</p>
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
                                        {{__('deposit.request_withdraw')}}
                                        <button
                                            type="button"
                                            class="out_cancel"
                                            @click="cancelRequestButtonClick(history)"
                                        >{{__('deposit.request_cancel')}}</button>
                                    </p>
                                    <p
                                        v-else-if="history.status === 'deposite_request'"
                                        class="cancelable_request"
                                    >
                                        {{__('deposit.request_waiting')}}
                                        <button
                                            type="button"
                                            class="in_cancel"
                                            @click="cancelRequestButtonClick(history)"
                                        >{{__('deposit.request_cancel')}}</button>
                                    </p>
                                    <p
                                        v-else-if="history.status === 'withdraw_cancel'"
                                    >{{__('deposit.withdraw_cancel')}}</p>
                                    <p
                                        v-else-if="history.status === 'deposite_cancel'"
                                    >{{__('deposit.deposite_cancel')}}</p>
                                    <p
                                        v-else-if="history.status === 'withdraw_reject'"
                                    >{{__('deposit.withdraw_reject')}}</p>
                                    <p
                                        v-else-if="history.status === 'deposite_reject'"
                                    >{{__('deposit.deposite_reject')}}</p>
                                    <p
                                        v-else-if="history.status === 'confirm' && history.type === 'withdraw'"
                                    >{{__('deposit.confirm_withdraw')}}</p>
                                    <p
                                        v-else-if="history.status === 'confirm' && history.type === 'deposite'"
                                    >{{__('deposit.confirm_deposit')}}</p>
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
            :buttonText="__('deposit.request_deposit')"
            :active="isReadyToDeposit"
            v-on:buttonClick="depositButtonClick"
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
            depositAmount: "",
            selectedHistoryType: "all",
            selectedHistory: null,
            histories: []
        };
    },
    async created() {
        await this.fetchData();
    },
    computed: {
        isReadyToDeposit() {
            return (
                this.depositAmount &&
                this.depositAmount !== 0 &&
                this.depositAmount >= 5000
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
        totalAmountToLocaleString() {
            const str = Number(this.depositAmount).toLocaleString();
            return str === "0" ? "" : str;
        }
    },
    watch: {
        depositAmount() {
            if (this.depositAmount === "") {
                return;
            }

            if (Number(this.depositAmount) < 0) {
                this.depositAmount = 0;
            }
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
        async depositButtonClick() {
            if (this.isReadyToDeposit) {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.post(`/api/wallet/cash_deposite`, {
                        amount: this.depositAmount
                    });
                    this.depositAmount = "";

                    this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                    this.systemNoticeMessage = this.__(
                        "deposit.deposit_message"
                    );
                    this.systemConfirmIcon = "/images/trst-images/icon/icon_check.svg";
                    this.isPopupVisible = true;
                } catch (e) {
                    this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                    this.systemNoticeMessage = this.__(
                        "deposit.deposit_already_exists"
                    );
                    this.isPopupVisible = true;
                } finally {
                    await this.fetchData();
                    this.$store.commit("progressComponentHide");
                }
            } else {
                this.systemNoticeIcon = "/images/trst-images/icon/icon_x.svg";
                this.systemNoticeMessage = this.__("deposit.deposit_min_error");
                this.isPopupVisible = true;
            }
        },
        cancelRequestButtonClick(history) {
            if (history.type === "deposite") {
                this.systemConfirmIcon = "/images/trst-images/icon/icon_receivecheck.svg";
                this.systemConfirmMessage = this.__(
                    "deposit.deposit_cancel_ask"
                );
            } else if (history.type === "withdraw") {
                this.systemConfirmIcon = "/images/trst-images/icon/icon_sendcheck.svg";
                this.systemConfirmMessage = this.__(
                    "deposit.withdraw_cancel_ask"
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

                this.systemNoticeIcon = "/images/trst-images/icon/icon_check.svg";
                this.systemNoticeMessage = this.__("deposit.cancel_ok");
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
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}

.ai_wrapper.top_0 {
    padding-top: 45px;
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

.ai_wrapper .sd_box.in_prc_box {
    padding-bottom: 5px;
}

.sd_box .form_line {
    width: 100%;
    height: auto;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

label {
    display: block;
    margin: 0;
    padding: 0;
    line-height: 1;
}

.sd_box .form_line label {
    width: 80%;
    display: inline-block;
    margin-bottom: 5px;
    color: #0747ad;
    letter-spacing: -0.5px;
    font-weight: 600;
    font-size: 14px;
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

.sd_box.in_info_box p {
    width: 100%;
    padding: 10px 0;
    text-align: center;
    font-size: 13px;
    background: #f6f6f6;
    color: #5a5a5a;
    letter-spacing: -0.05rem;
    margin-bottom: 3px;
}

.sd_box.in_info_box p span {
    display: inline-block;
    margin: 3px 0;
    width: 100%;
}

.sd_box.in_info_box p span:nth-child(2) {
    font-size: 12px;
}

.ai_wrapper .s_text {
    font-size: 10px;
    color: #b4b4b4;
    padding-top: 8px;
}

.sd_box.in_info_box .s_text {
    width: 100%;
    display: inline-block;
    color: red !important;
}

.in_form_line {
    width: 100%;
    display: inline-block;
    position: relative;
    margin-top: 5px;
}

.ai_wrapper input.buy_input_area {
    width: 100%;
    text-align: right;
    float: left;
    border: 0;
    font-size: 15px;
    padding-right: 37px;
    height: 30px;
}

.ai_wrapper input.buy_input_area,
.max_form input {
    width: 100%;
    text-align: right;
    float: left;
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

.sd_box.in_prc_box .info_line {
    margin-top: 8px;
}

.keep_form .keep_value_tt {
    font-size: 13px;
    padding: 0 10px;
    color: #5a5a5a;
    font-weight: 600;
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
