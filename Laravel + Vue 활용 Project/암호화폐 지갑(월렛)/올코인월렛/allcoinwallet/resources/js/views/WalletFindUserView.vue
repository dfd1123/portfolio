<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/wallet_send"
            center="text"
            :centerText="__('wallet_find_user.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor user_sch_area">
            <ul class="sch_box sch-0129">
                <li
                    v-bind:class="{active: selected === 'fullname'}"
                    @click="selected = 'fullname'"
                >{{__('wallet_find_user.search_name')}}</li>
                <li
                    v-bind:class="{active: selected === 'id'}"
                    @click="selected = 'id'"
                >{{__('wallet_find_user.search_code')}}</li>
            </ul>

            <div class="coin_name_sch user_sch">
                <div class="active">
                    <input type="text" id="search_fullname_text" class="name_sch" />
                    <button type="button" id="search_fullname_btn">
                        <i class="far fa-search"></i>
                    </button>
                </div>
                <div>
                    <input
                        type="text"
                        class="code_sch"
                        v-on:input="search = $event.target.value"
                        v-on:keyup.enter="findUser"
                    />
                    <button type="button" id="search_usernum_btn" @click="findUser">
                        <i class="far fa-search"></i>
                    </button>
                </div>
            </div>

            <div class="user_list list">
                <ul v-if="users && users.length > 0" id="search_userlist_ul">
                    <li v-for="user in users" :key="user.id" class="search_li">
                        <div class="touch_area" @click="selectUser(user)">
                            <span class="left_form">
                                <label for="user_wallet0" class="control">
                                    <input
                                        type="radio"
                                        :checked="selectedUser && selectedUser.id === user.id"
                                    />
                                    <div class="indicator"></div>
                                    <div class="in_text">
                                        <p>
                                            <b id="sch_user_fullname0">{{user.fullname}}</b>
                                            <strong id="sch_user_uid0">(#{{user.id}})</strong>
                                        </p>
                                        <p id="sch_user_email0">{{user.email}}</p>
                                    </div>
                                </label>
                            </span>
                        </div>
                        <div
                            class="address_box"
                            :style="{left: selectedAddressId === user.id ? '100px' : ''}"
                        >
                            <span
                                class="slide_btn"
                                style="line-height: 90px;"
                                @click="showUserAddress(user)"
                            >{{__('wallet_find_user.wallet_address')}} &gt;</span>
                            <p>{{user.fullname}} {{`(#${user.id})`}}</p>
                            <p id="sch_user_contactaddr0">{{user.address}}</p>
                        </div>
                    </li>
                </ul>

                <ul v-else-if="users && users.length === 0" class="not_result in_text">
                    <img class="icon" src="/images/icon_sad.svg" alt="no result" />

                    <p class="ment_2">{{__('wallet_find_user.no_result')}}</p>
                </ul>
            </div>
        </div>
        <footer-component
            :buttonText="__('wallet_find_user.select')"
            v-on:buttonClick="selectButtonClick"
            :active="selectedUser"
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
            selected: "fullname",
            search: "",
            users: null,
            selectedUser: null,
            selectedAddressId: null
        };
    },
    computed: {
        systemNoticeMessage() {
            if (!this.selectedUser) {
                return this.__("wallet_find_user.select_user");
            }

            return "";
        }
    },
    methods: {
        async findUser() {
            try {
                this.$store.commit("progressComponentShow");

                const users = (await axios.get(`/api/users`, {
                    params: {
                        [this.selected]: this.search
                    }
                })).data.map(user => {
                    user.address = "";
                    return user;
                });

                this.users = users;
                this.selectedUser = null;
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        selectUser(user) {
            this.selectedUser = user;
            this.selectedAddressId = null;
        },
        async showUserAddress(user) {
            if (this.selectedAddressId === user.id) {
                this.selectedAddressId = null;
            } else {
                if (!user.address) {
                    user.address = await this.getUserAddress(
                        user,
                        this.$store.state.selectedCoin
                    );
                    this.selectedAddressId = user.id;
                } else {
                    this.selectedAddressId = user.id;
                }
            }
        },
        async getUserAddress(user, coin) {
            try {
                this.$store.commit("progressComponentShow");
                const address = (await axios.get(
                    `/api/wallet/address/${coin}/${user.id}`
                )).data;
                return address;
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        async selectButtonClick() {
            if (!this.selectedUser) {
                this.isPopupVisible = true;
            } else {
                if (!this.selectedUser.address) {
                    this.selectedUser.address = await this.getUserAddress(
                        this.selectedUser,
                        this.$store.state.selectedCoin
                    );
                }

                this.$store.commit("mergeWalletSendViewData", {
                    selectedUser: { ...this.selectedUser },
                    selectedSearch: "user"
                });

                this.$router.replace("/wallet_send");
            }
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
    padding-bottom: 52px;
}

.bgcolor {
    background-color: #fafafa;
    height: 100%;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: hidden;
}

.sch_box {
    width: 90%;
    margin: 0 auto;
    background-color: white;
    position: relative;
    top: 2.5vh;
}

.sch_box.sch-0129 {
    margin: 15px auto;
    top: 0;
    display: flex;
}

.sch_box li {
    float: left;
    width: 50%;
    border: 1px solid #dcdcdc;
    padding: 10px 10px;
    background-color: white;
    text-align: center;
    font-size: 13px;
    color: #5a5a5a;
}

.sch_box li.active {
    border: 1px solid #0072ff;
    color: #222;
}

.sch_box li {
    float: left;
    width: 50%;
    border: 1px solid #dcdcdc;
    padding: 10px 10px;
    background-color: white;
    text-align: center;
    font-size: 13px;
    color: #5a5a5a;
}

.coin_name_sch {
    width: 80%;
    height: 40px;
    text-align: center;
    border: 1px solid #dcdcdc;
    margin: 0 auto;
    position: relative;
    top: 4vh;
}

.coin_name_sch.user_sch {
    width: 90%;
    top: 9.5vh;
}

.coin_name_sch.user_sch {
    top: 0;
    margin-bottom: 15px;
}

.coin_name_sch input {
    width: 100%;
    height: 100%;
    border: 0;
    padding: 5px 38px 5px 10px;
    font-size: 14px;
    position: absolute;
    top: 0;
    left: 0;
}

.coin_name_sch.user_sch div.active {
    display: block;
}

.coin_name_sch input {
    width: 100%;
    height: 100%;
    border: 0;
    padding: 5px 38px 5px 10px;
    font-size: 14px;
    position: absolute;
    top: 0;
    left: 0;
}

.coin_name_sch button {
    position: absolute;
    right: 0;
    top: 0;
    padding: 10px 10px;
    color: #0072ff;
    cursor: pointer;
    background: none;
    border: none;
}

.list {
    width: 100%;
    position: relative;
    overflow-x: hidden;
    overflow-y: auto;
    border-top: 1px solid #dcdcdc;
}

.user_list {
    top: 0;
    height: 79.5%;
}

.list ul {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

.list ul li:active {
    background-color: #49d094;
    transition-duration: 0.3s;
    color: white;
}

label {
    display: block;
    margin: 0;
    padding: 0;
    line-height: 1;
}

.control input {
    display: none;
}

.icon {
    width: 62px;
    margin-top: 20vh;
}

.ment_2 {
    font-size: 12px;
    color: #5a5a5a;
    width: 100%;
    display: inline-block;
}

.list ul li {
    box-sizing: border-box;
    border-bottom: 1px solid #dfdfdf;
    font-size: 14px;
    color: #5a5a5a;
    text-align: left;
    background-color: #ffffff;
    transition-duration: 0.3s;
}

.indicator {
    width: 18px;
    height: 18px;
    border-radius: 50px;
    background-color: white;
    border: 1px solid #dcdcdc;
    position: relative;
    float: left;
    top: 15px;
    left: 2%;
}

.indicator:after {
    left: 5.2px;
    top: 2.3px;
    width: 3px;
    height: 6px;
    border: solid #49d094;
    border-width: 0 2.5px 2.5px 0;
    transform: rotate(45deg);
    content: "";
    position: absolute;
    display: none;
    box-sizing: content-box;
}

.control input:checked ~ .indicator:after {
    display: block;
}

.search_li .in_text {
    float: left;
    padding-left: 6.5%;
}

.search_li .in_text p:first-child {
    font-size: 13px;
}

.search_li .in_text p:first-child b {
    font-weight: 600;
}

.search_li .in_text p:first-child strong {
    color: #0072ff;
    font-weight: bolder;
    font-weight: 700;
}

.search_li .in_text p {
    line-height: 25px;
    color: #5a5a5a;
    font-weight: 600;
    font-size: 12px;
}

.address_box {
    width: 100%;
    height: 100%;
    background-color: white;
    position: absolute;
    top: 0;
    left: 100%;
    padding: 20px 0px 10px 3%;
    transition: all 0.3s ease;
}

.address_box p {
    float: left;
    font-size: 11px;
    width: 70%;
}

.address_box p:nth-child(2) {
    margin-bottom: 2%;
    color: #969696;
}

.address_box p:nth-child(3) {
    color: #5a5a5a;
    line-height: 20px;
    word-break: break-all;
}

.user_list ul li .slide_btn {
    line-height: 90px;
    position: absolute;
    left: -100px;
    padding: 0 10px;
    background-color: #f8f8f8;
    height: 100%;
    top: 0;
    color: #0072ff;
    font-weight: 700;
    letter-spacing: -1px;
    box-shadow: 0px 0px 20px rgba(0, 69, 191, 0.2);
    width: 100px;
    text-align: center;
    cursor: pointer;
}

.user_list ul li {
    position: relative;
    overflow: hidden;
}

p {
    margin-top: 0;
    margin-bottom: 0;
}

.search_li .in_text p {
    line-height: 25px;
    color: #5a5a5a;
    font-weight: 600;
    font-size: 12px;
}

.not_result.in_text {
    text-align: center;
}

.touch_area {
    width: calc(100% - 100px);
    height: 90px;
    padding: 20px;
}
</style>
