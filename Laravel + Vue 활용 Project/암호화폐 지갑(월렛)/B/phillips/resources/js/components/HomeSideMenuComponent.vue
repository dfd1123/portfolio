<template>
    <div class="side_menu" v-bind:class="{ active: visible }">
        <div class="overlap" @click="$emit('update:visible', false)"></div>
        <div class="side_menu_wrap">
            <div class="sm_hd">
                마이메뉴
                <div class="side_menu_fold" @click="$emit('update:visible', false)">
                    <i class="fal fa-chevron-left"></i>
                </div>
            </div>
            <ul>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'notice'}})"
                    >
                        {{__('side_menu.notice')}}
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'faq'}})"
                    >
                        {{__('side_menu.faq')}}
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'qna'}})"
                    >
                        {{__('side_menu.qna')}}
                    </a>
                </li>

                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'tos'}})"
                    >
                        {{__('side_menu.tos')}}
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace('/user_info')"
                    >
                        {{__('home.user_info')}}
                    </a>
                </li>
            </ul>

            <button type="button" @click="logout">
                <i class="far fa-power-off"></i>
                {{__('system.logout')}}
            </button>
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

.side_menu.active {
    z-index: 11;
}

.side_menu.active div.overlap {
    display: block;
}

.side_menu div.overlap {
    display: none;
    background: rgba(0, 0, 0, 0.4);
    height: 100%;
    position: relative;
    z-index: 2;
}

.side_menu.active .side_menu_wrap {
    left: 0;
}

.side_menu_wrap {
    position: absolute;
    top: 0;
    left: -100%;
    width: 70%;
    background: #efefef;
    height: 100%;
    z-index: 10;
    transition: left ease 0.3s;
}

.side_menu_wrap div.sm_hd {
    font-size: 16px;
    color: #000;
    text-align: center;
    padding: 13px 0;
    padding-top: 40px;
    font-weight: 600;
    border-bottom: 1px solid #ddd;
    position: relative;
}

.side_menu_wrap ul li {
    border-bottom: 1px solid #ddd;
    position: relative;
}

.side_menu_wrap ul li a {
    display: block;
    font-size: 15px;
    color: #000;
    font-weight: 600;
    background: #efefef;
    padding: 11px 0;
    padding-left: 16px;
    text-decoration: none;
}

.side_menu_wrap ul li a img {
    width: 20px;
    position: absolute;
    top: 13px;
    left: 15px;
}

.side_menu_fold {
    float: right;
    padding: 0px 10px;
    width: initial;
    position: absolute;
    bottom: 13px;
    right: 10px;
    color: #000;
}

.side_menu_wrap ul li a span {
    position: absolute;
    top: 20px;
    right: 14px;
    font-size: 11px;
}

.side_menu_wrap ul li a:active {
    background: #e5e5e5 !important;
    font-weight: 600 !important;
    color: #2E87C8 !important;
}

.side_menu_wrap ul li a.disabled {
    pointer-events: none;
    cursor: default;
}

.side_menu_wrap button {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    border: none;
    background: #efefef;
    border-top: 1px solid #ddd;
    padding: 17px 0;
    font-size: 16px;
    color: #2E87C8;
    font-weight: 700;
}
</style>
