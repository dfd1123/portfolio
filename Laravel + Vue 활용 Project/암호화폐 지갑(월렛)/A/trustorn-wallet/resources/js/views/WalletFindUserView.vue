<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/wallet_send"
            center="text"
            :centerText="__('wallet_find_user.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor user_sch_area">
            <div class="sch_info_inp">
                <ul class="sch_box">
                    <li
                        v-bind:class="{active: selected === 'fullname'}"
                        @click="selected = 'fullname'"
                    >{{__('wallet_find_user.search_name')}}</li>
                    <li
                        v-bind:class="{active: selected === 'id'}"
                        @click="selected = 'id'"
                    >{{__('wallet_find_user.search_code')}}</li>
                </ul>
                <div class="user_sch">
                    <div v-bind:class="{active: selected === 'fullname'}">
                        <input 
                            type="search" 
                            id="search_fullname_text" 
                            class="name_sch" 
                            v-on:input="search = $event.target.value"
                            v-on:keyup.enter="findUser" />
                        <button type="button" id="search_fullname_btn">
                            <i class="far fa-search"></i>
                        </button>
                    </div>
                    <div v-bind:class="{active: selected === 'id'}">
                        <input
                            type="search"
                            class="code_sch"
                            v-on:input="search = $event.target.value"
                            v-on:keyup.enter="findUser"
                        />
                        <button type="button" id="search_usernum_btn" @click="findUser">
                            <i class="far fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="user_list">
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
                            <div class="address_box_inner">
                                <span
                                    class="slide_btn"
                                    style="line-height: 90px;"
                                    @click="showUserAddress(user)"
                                >{{__('wallet_find_user.wallet_address')}} <i class="fal fa-chevron-right"></i></span>
                                <p>{{user.fullname}} {{`(#${user.id})`}}</p>
                                <p id="sch_user_contactaddr0">{{user.address}}</p>
                            </div>
                        </div>
                    </li>
                </ul>

                <ul v-else-if="users && users.length === 0" class="not_result in_text">
                    <li>
                        <img class="icon" src="/images/trst-images/icon/icon_empty.svg" alt="no result" />
                        <p class="ment_2">{{__('wallet_find_user.no_result')}}</p>
                    </li>
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
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}

.user_sch_area {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: hidden;
    padding: 0;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .user_sch_area{
        font-size: 15px;
    }
}

.sch_info_inp{
    background: linear-gradient(to right, rgb(100, 225, 150), rgb(25, 180, 170));
    padding: 1.1em 1.2em;
    height: 7.5em;
    box-shadow: 0 3px 12px #C3D7D7;
    z-index: 1;
    position: relative;
}

.sch_box {
    width: 100%;
    height: 2.19em;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
    margin-bottom: 0.95em;
}

.sch_box li {
    float: left;
    width: 50%;
    height: 100%;
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    text-align: center;
    padding: 9px 0;
    font-size: 0.85em;
    font-weight: 400;
}

.sch_box li.active {
    border: 1px solid rgba(255,255,255,1);
    color: white;
}

.user_sch {
    width: 100%;
    height: 2.19em;
    position: relative;
}

.user_sch > div{
    display: none;
    height: 100%;
}

.user_sch > div.active {
    display: block;
}

.user_sch input {
    width: 100%;
    height: 100%;
    border: 0;
    padding: 5px 38px 5px 10px;
    font-size: 14px;
    position: absolute;
    top: 0;
    left: 0;
}

.user_sch button {
    background: none;
    border: none;
    height: 100%;
    text-align: center;
    width: 38px;
    font-size: 1.2em;
    position: absolute;
    right: 0;
    top: 0;
    color: #1E1E1E;
    cursor: pointer;
}

.user_list {
    width: 100%;
    height: calc(100% - 7.5em);
    position: relative;
    overflow-x: hidden;
    overflow-y: auto;
}

.user_list ul {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

.touch_area {
    width: calc(100% - 100px);
    height: 90px;
    padding: 0;
}

.touch_area .left_form{
    display: inline-block;
    width: 100%;
    height: 100%;
}

.touch_area .left_form > label{
    width: 100%;
    height: 100%;
    margin-bottom: 0;
    display: table;
}

.search_li {
    box-sizing: border-box;
    border-bottom: 1px solid #DCDCDC;
    color: #5a5a5a;
    text-align: left;
    position: relative;
}

.search_li .in_text {
    padding-left: 3em;
    display: table-cell;
    vertical-align: middle;
}

.search_li .in_text p:first-child {
    font-size: 1.25em;
    font-weight: 400;
    margin-bottom: 6px;
}

.search_li .in_text p:first-child b {
    font-weight: inherit;
    vertical-align: middle;
}

.search_li .in_text p:first-child strong {
    color: #19B4AA;
    font-weight: inherit;
    font-size: 0.7em;
    letter-spacing: 0;
    vertical-align: middle;
}

.search_li .in_text p:last-child{
    letter-spacing: 0;
    color: #505050;
    font-weight: 300;
    font-size: 0.85em;
}

.indicator {
    width: 18px;
    height: 18px;
    background-color: white;
    border-radius: 50px;
    border: 1px solid #dcdcdc;
    position: absolute;
    top: 50%;
    left: 1.2em;
    transform: translateY(-50%);
    transition: all 0.2s;
}

.indicator:after {
    left: 5px;
    top: 2px;
    width: 4px;
    height: 8px;
    border: solid #64E196;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
    content: "";
    position: absolute;
    display: none;
    box-sizing: content-box;
}

.control input {
    display: none;
}

.control input:checked ~ .indicator:after {
    display: block;
}

.control input:checked ~ .indicator{
    background-color: #1E1E1E;
    border-color: #1E1E1E;
    transition: all 0.2s;
}

.address_box {
    width: calc(100% - 100px);
    height: 100%;
    background-color: white;
    position: absolute;
    top: 0;
    left: 100%;
    padding: 0;
    transition: all 0.3s ease;
    display: table;
}

.address_box .slide_btn {
    position: absolute;
    top: 0;
    left: -100px;
    width: 100px;
    height: 100%;
    background-color: #F5F5F5;
    color: #505050;
    font-weight: 400;
    text-align: center;
    cursor: pointer;
    font-size: 0.85em;
    font-weight: 400;
}

.address_box .slide_btn i{
    font-size: 10px;
    vertical-align: middle;
    display: inline-block;
    position: relative;
    top: -2px;
    font-weight: 500;
    padding: 0 4px;
}

.address_box_inner{
    display: table-cell;
    vertical-align: middle;
}

@media all and (min-width: 320px) and (max-width: 350px){
    .address_box_inner{
        font-size: 15px;
    }
}

.address_box p {
    width: 100%;
    font-size: 0.73em;
    font-weight: 300;
    padding: 0 10px;
}

.address_box p:nth-child(2) {
    margin-bottom: 5px;
    color: #969696;
}

.address_box p:nth-child(3) {
    color: #505050;
    line-height: 1.7;
    word-break: break-all;
    letter-spacing: 0;
}

.not_result {
    text-align: center;
    display: table;
}

.not_result > li{
    display: table-cell;
    vertical-align: middle;
}

.not_result .icon{
    width: 62px;
    margin-bottom: 5px;
}

.not_result .ment_2 {
    font-size: 13px;
    color: #505050;
    width: 100%;
    display: inline-block;
}
</style>
