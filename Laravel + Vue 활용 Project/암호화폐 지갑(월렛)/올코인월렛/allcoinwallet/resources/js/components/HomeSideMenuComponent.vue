<template>
    <div class="side_menu" v-bind:class="{ active: visible }">
        <div class="overlap" @click="$emit('update:visible', false)"></div>
        <div class="side_menu_wrap">
            <div class="sm_hd">
                {{__('side_menu.cs')}}
                <div class="side_menu_fold" @click="$emit('update:visible', false)">
                    <i class="fal fa-chevron-left"></i>
                </div>
            </div>
            <ul>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'notice'}})"
                    >
                        <img src="/images/08_support_icon_01.svg" alt="notice" />
                        {{__('side_menu.notice')}}
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'faq'}})"
                    >
                        <img src="/images/08_support_icon_02.svg" alt="faq" />
                        {{__('side_menu.faq')}}
                    </a>
                </li>
                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'qna'}})"
                    >
                        <img src="/images/08_support_icon_03.svg" alt="qna" />
                        {{__('side_menu.qna')}}
                    </a>
                </li>

                <li>
                    <a
                        @click.prevent="$router.replace({name: 'customer_service', params: { page: 'tos'}})"
                    >
                        <img src="/images/08_support_icon_04.svg" alt="tos" />
                        {{__('side_menu.tos')}}
                    </a>
                </li>
                <li>
                    <a class="disabled">
                        <img src="/images/08_support_icon_05.svg" alt="ver" />
                        {{__('side_menu.ver')}}
                        <span>ver {{__("VERSION")}}</span>
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
    background: #fff;
    height: 100%;
    z-index: 10;
    transition: left ease 0.3s;
}

.side_menu_wrap div.sm_hd {
    font-size: 16px;
    color: #5d5d5d;
    text-align: center;
    padding: 13px 0;
    font-weight: 600;
    border-bottom: 1px solid #f0f0f0;
}

.side_menu_wrap ul li {
    border-bottom: 1px solid #f0f0f0;
    position: relative;
}

.side_menu_wrap ul li a {
    display: block;
    font-size: 15px;
    color: #5d5d5d;
    background: #fff;
    padding: 17px 0;
    padding-left: 49px;
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
}

.side_menu_wrap ul li a span {
    position: absolute;
    top: 20px;
    right: 14px;
    font-size: 11px;
}

.side_menu_wrap ul li a:active {
    background: #f5f5f5 !important;
    font-weight: 600 !important;
    color: #0072ff !important;
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
    background: #1d324f;
    border-top: 1px solid #f0f0f0;
    padding: 17px 0;
    font-size: 16px;
    color: #fff;
}
</style>
