<template>
    <div class="side_menu" v-bind:class="{ active: visible }">
        <div class="side_menu_wrap">
            <div class="side_menu_hd">
                <img src="/images/trst-images/logo/sidemenu.svg" alt="logo" class="side_menu_hd_logo">
                <div class="side_menu_hd_inner_con">
                    <h5>{{$store.state.detail.user.fullname || ''}}</h5>
                    <span>{{$store.state.detail.user.email || ''}}</span>
                </div>
            </div>
            <div class="side_menu_fold" @click="$emit('update:visible', false)">
                <img src="/images/trst-images/btn/btn_menu_close.svg" alt="close btn">
            </div>
            <ul>
                <li>
                    <a
                        @click.prevent="$router.replace('/user_info')"
                    >
                        <img src="/images/trst-images/icon/icon_01-my.svg" alt="my info" />
                        <span>{{__('home.user_info')}}</span>
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace('/user_benefit')"
                    >
                        <img src="/images/trst-images/icon/icon_02-profits.svg" alt="profits" />
                        <span>{{__('revenue.revenue_view')}}</span>
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'notice'}})"
                    >
                        <img src="/images/trst-images/icon/icon_03-notice.svg" alt="notice" />
                        <span>{{__('side_menu.notice')}}</span>
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'qna'}})"
                    >
                        <img src="/images/trst-images/icon/icon_04-inquire.svg" alt="qna" />
                        <span>{{__('side_menu.qna')}}</span>
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'faq'}})"
                    >
                        <img src="/images/trst-images/icon/icon_05-faq.svg" alt="faq" />
                        <span>{{__('side_menu.faq')}}</span>
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace('/company_info')"
                    >
                        <img src="/images/trst-images/icon/icon_06-etc.svg" alt="etc" />
                        <span>{{__('revenue.other_infor')}}</span>
                    </a>
                </li>
            </ul>

            <div class="side_menu_options">
                <button type="button" class="side_menu_option_btn disabled">
                    <img src="/images/trst-images/icon/icon_ver.svg" alt="ver icon">
                    <span>{{__("VERSION")}}</span>
                </button>
                <button type="button" class="side_menu_option_btn" @click="logout">
                    <img src="/images/trst-images/icon/icon_log.svg" alt="ver icon">
                    {{__('system.logout')}}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["visible"],
    data() {
        return {};
    },
    methods: {
        async logout() {
            try {
                this.$store.commit("progressComponentShow");

                await axios.get("/api/logout");
                localStorage.removeItem("passportToken");
                axios.defaults.headers.common["Authorization"] = undefined;
                this.$swal({
                    type: "success",
                    text: this.__("system.logout_success"),
                    allowOutsideClick: false
                });
                this.$router.replace("/");
            } finally {
                this.$store.commit("progressComponentHide");
                this.$store.commit("reset");
            }
        }
    }
};
</script>

<style scoped>
.side_menu {
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
    overflow: hidden;
}

.side_menu_hd{
    display: inline-block;
    background: linear-gradient(to right, rgb(100, 225, 150), rgb(25, 180, 170));
    height: 83px;
    width: 100%;
    margin-bottom: 50px;
}

.side_menu_hd_logo{
    width: 110px;
    position: absolute;
    top: 11px;
    left: 16px;
}

.side_menu_hd_inner_con{
    background-color: white;
    width: calc(100% - 32px);
    margin: 0 auto;
    position: relative;
    top: 50px;
    box-shadow: 0 8px 14px rgba(195, 215, 215, 0.3);
    padding: 20px;
    color: #505050;
    font-weight: 400;
}

.side_menu_hd_inner_con > h5{
    font-size: 15px;
    display: inline-block;
    font-weight: inherit;
    margin: 0;
}

.side_menu_hd_inner_con > span{
    font-size: 13px;
    color: #003E5A;
    letter-spacing: 0;
    float: right;
    position: relative;
    top: 2px;
}

.side_menu.active {
    z-index: 11;
}

.side_menu.active .side_menu_wrap {
    left: 0;
}

.side_menu_wrap {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    background: #fff;
    height: 100%;
    z-index: 10;
    transition: left ease 0.3s;
}

.side_menu_wrap ul li a {
    display: block;
    font-size: 1.25rem;
    color: #505050;
    background: #fff;
    padding: 18px;
    text-decoration: none;
    font-weight: 400;
}

.side_menu_wrap ul li a img {
    position: relative;
    vertical-align: text-bottom;
    display: inline-block;
    margin-right: 10px;
}

.side_menu_wrap ul li a span{
    display: inline-block;
    vertical-align: middle;
}

.side_menu_fold {
    padding: 0px 10px;
    position: absolute;
    width: initial;
    top: 15px;
    right: 9px;
}

.side_menu_wrap ul li a:active {
    color: #19B4AA;
}

.side_menu_wrap .side_menu_options {
    position: absolute;
    bottom: 20px;
    left: 0;
    width: 100%;
    padding: 0 13px;
    border: none;
    display: flex;
}

.side_menu_wrap .side_menu_option_btn{
    flex: 1;
    margin: 0 3px;
    height: 40px;
    border: 1px solid #EBF0F0;
    color: #505050;
    font-weight: 400;
    background-color: white;
    font-size: 14px;
    box-shadow: none;
    -webkit-appearance: none;
    -webkit-border-radius: 0;
    transition: all 0.2s;
}

.side_menu_wrap .side_menu_option_btn:active{
    background-color: #f5f5f5;
    transition: all 0.2s;
}

.side_menu_wrap .side_menu_option_btn > img{
    display: inline-block;
    vertical-align: middle;
    margin-right: 2px;
}
.side_menu_wrap .side_menu_option_btn > span{
    display: inline-block;
    vertical-align: middle;
}

.side_menu_wrap .side_menu_option_btn:last-child > img{
    position: relative;
    top: -2px;
}

.side_menu_wrap .side_menu_option_btn.disabled {
    pointer-events: none;
    cursor: default;
}
</style>
