<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/wallet_send"
            center="text"
            :centerText="__('wallet_input_address.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('wallet_input_address.input_address')}}</label>
                    <input
                        type="text"
                        class="w_address"
                        :placeholder="__('wallet_input_address.placeholder_input_address')"
                        v-model="inputAddress"
                    />
                </div>
            </div>
            <footer-component
                :buttonText="__('wallet_input_address.input_ok')"
                v-on:buttonClick="selectButtonClick"
                :active="inputAddress !== ''"
            ></footer-component>
        </div>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
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
            inputAddress: ""
        };
    },
    computed: {
        systemNoticeMessage() {
            return this.__("wallet_input_address.invalid_address");
        }
    },
    methods: {
        async selectButtonClick() {
            if (!this.inputAddress) {
                this.isPopupVisible = true;
            } else {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.post(`/api/wallet/address/verify`, {
                        symbol: this.$store.state.selectedCoin,
                        address: this.inputAddress
                    });

                    const users = (await axios.get(`/api/users`, {
                        params: {
                            symbol: this.$store.state.selectedCoin,
                            address: this.inputAddress
                        }
                    })).data;

                    if (users.length > 0) {
                        this.$store.commit("mergeWalletSendViewData", {
                            selectedUser: users[0],
                            selectedSearch: "input"
                        });
                    } else {
                        this.$store.commit("mergeWalletSendViewData", {
                            selectedUser: {},
                            selectedSearch: "input",
                            externalAddress: this.inputAddress
                        });
                    }

                    this.$router.replace("/wallet_send");
                } catch (e) {
                    this.isPopupVisible = true;
                } finally {
                    this.$store.commit("progressComponentHide");
                }
            }
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 48px;
}

.bgcolor {
    background-color: #000;
    height: 100%;
    padding-top: 10px;
}

.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    background-color: white;
    border-radius: 5px;
    margin: 0 auto;
    position: relative;
    top: 2vh;
    padding: 15px 15px;
    padding-bottom: 30px;
    margin-bottom: 15px;
}

.sd_box .form_line {
    width: 100%;
    height: auto;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.sd_box .form_line .label_hr {
    width: 100%;
    display: inline-block;
    margin-bottom: 5px;
    color: #2E87C8;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.sd_box .form_line .w_address {
    width: 100%;
    font-size: 14px;
    border: 0;
    border-bottom: 1px solid #dcdcdc;
    padding: 5px 10px;
    word-break: break-all;
    color: #000;
    line-height: 25px;
}

.w_address {
    border-radius: 0;
}

#footer button{
    position: fixed;
    bottom: 9%;
    left: 5%;
    width: 90%;
    margin: 0 auto;
}
</style>
