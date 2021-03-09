<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            :leftButtonRoute="{name: 'customer_service', params: { page: 'notice'}}"
            center="text"
            :centerText="__('customer_service.notice')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor cs_container" v-show="isLoaded">
            <div class="cs_tap cs_detail ntc_detail">
                <p class="title">{{notice.title}}</p>
                <p class="data_admin">
                    <span>{{notice.id}}</span>
                    <span>
                        <img src="/images/data_icon.svg" alt="data-icon" />
                        {{toMoment(notice.created).format('YYYY-MM-DD HH:mm:ss')}}&nbsp;&nbsp;{{__('customer_service.view')}}&nbsp;{{notice.view}}
                    </span>
                    <span>
                        <img src="/images/writer_icon.svg" alt="writer-icon" />{{__('customer_service.admin')}}
                    </span>
                </p>
            </div>
            <div class="container_tap detail_con" v-html="notice.description"></div>
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
            notice: {}
        };
    },
    async created() {
        try {
            this.$store.commit("progressComponentShow");

            this.notice = (await axios.get(
                `/api/notices/${this.$route.params.id}`
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

.cs_tap {
    width: 100%;
    background: white;
    border-bottom: 1px solid #f1f1f1;
    height: auto;
    position: absolute;
    top: 45px;
    z-index: 1;
}

.cs_tap.cs_detail.ntc_detail {
    height: 55px;
    z-index: 2;
}

.cs_tap.cs_detail {
    background: #fafafa;
}

.cs_tap.cs_detail.ntc_detail .title {
    height: unset;
    line-height: 0;
    padding: 20px 20px 9px;
    font-weight: bold;
}

.title {
    font-size: 12px;
    color: #5a5a5a;
    line-height: 18px;
    word-break: break-all;
    padding-left: 30px;
}

.data_admin {
    font-size: 9px;
    color: #969696;
    margin-top: 4px;
    padding-left: 30px;
}

.cs_tap.cs_detail.ntc_detail .data_admin {
    padding-left: 20px;
}

.cs_tap.cs_detail.ntc_detail .data_admin span:nth-child(1) {
    color: #2E87C8;
}

.cs_tap.cs_detail.ntc_detail .data_admin span:nth-child(2) {
    padding: 0 7px;
}

.cs_tap.cs_detail.ntc_detail .data_admin span img {
    width: 9px;
    position: relative;
    top: 1px;
    margin-right: 2.5px;
    vertical-align: inherit;
}

.cs_tap.cs_detail.ntc_detail .data_admin span:nth-child(3) {
    padding-right: 7px;
}

.cs_tap.cs_detail.ntc_detail .data_admin span img {
    width: 9px;
    position: relative;
    top: 1px;
    margin-right: 2.5px;
}

.ai_wrapper .container_tap.detail_con {
    padding-top: 55px;
    background: #fff;
    padding: 70px 20px 20px;
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
