<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            :leftButtonRoute="{name: 'customer_service', params: { page: 'tos'}}"
            center="text"
            :centerText="__('customer_service.tos')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor cs_container" v-show="isLoaded">
            <div class="container_tap detail_con" v-html="content"></div>
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        next();
    },
    components: {
        "header-component": HeaderComponent
    },
    data() {
        return {
            isLoaded: false,
            content: ""
        };
    },
    async created() {
        try {
            this.$store.commit("progressComponentShow");

            this.content = (await axios.get(
                `/api/tos/${this.$route.params.type}/${__.LANG}`
            )).data;
            this.isLoaded = true;
        } finally {
            this.$store.commit("progressComponentHide");
        }
    },
    methods: {
        toMoment(timestamp) {
            return moment(Number(timestamp) * 1000);
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 45px;
}

.ai_wrapper.top_0 {
    padding-top: 45px;
}

.ai_wrapper.bottom_0 {
    padding-bottom: 0;
}

.bgcolor {
    background-color: #fafafa;
    height: 100%;
}

.ai_wrapper .container_tap.detail_con {
    padding-top: 55px;
    background: #fff;
    padding: 20px 20px 20px;
    color: #5a5a5a;
    font-size: 12px;
    line-height: 20px;
    overflow: scroll;
    overflow-x: hidden;
    display: block;
}

.ai_wrapper .container_tap {
    width: 100%;
    height: calc(100% - 45px);
    position: absolute;
    padding-top: 46.5px;
    background: #fafafa;
}
</style>
