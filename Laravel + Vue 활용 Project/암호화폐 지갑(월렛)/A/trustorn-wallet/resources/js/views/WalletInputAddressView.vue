<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/wallet_send"
            center="text"
            :centerText="__('wallet_input_address.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('wallet_input_address.input_address')}}</label>
                    <input
                        type="text"
                        class="in_input"
                        :placeholder="__('wallet_input_address.placeholder_input_address')"
                        v-model="inputAddress"
                    />
                </div>
            </div>
        </div>
        <footer-component
            :buttonText="__('wallet_input_address.input_ok')"
            v-on:buttonClick="selectButtonClick"
            :active="inputAddress !== ''"
        ></footer-component>
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
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}
</style>
