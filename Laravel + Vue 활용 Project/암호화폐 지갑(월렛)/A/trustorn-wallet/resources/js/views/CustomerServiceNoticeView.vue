<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            :leftButtonRoute="{name: 'customer_service', params: { page: 'notice'}}"
            center="text"
            :centerText="__('customer_service.notice')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor cs_container scroll_area" v-show="isLoaded">
            <div class="cs_detail">
               <div class="cs_detail_inner">
                    <p class="title">{{notice.title}}</p>
                    <p class="data_admin">
                    <span class="id_num">{{notice.id}}</span>
                    <span class="moment">
                        <img src="/images/icon/data_icon.svg" alt="data-icon" />
                        <i>{{toMoment(notice.created).format('YYYY-MM-DD HH:mm:ss')}}&nbsp;&nbsp;{{__('customer_service.view')}}&nbsp;{{notice.view}}</i>
                    </span>
                    <span class="icon">
                        <img src="/images/icon/writer_icon.svg" alt="writer-icon" />
                        <span>{{__('customer_service.admin')}}</span>
                    </span>
                </p>
               </div>
            </div>
            <div class="detail_con" v-html="notice.description"></div>
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
            return moment(Number(timestamp) * 1000).add(9, 'hours');
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
}

.cs_detail {
    width: 100%;
    background: #FAFAFA;
    border-bottom: 1px solid #EBF0F0;
    height: 65px;
    position: relative;
    display: table;
}

.cs_detail_inner{
    display: table-cell;
    vertical-align: middle;
    padding: 15px 18px;
}

.cs_detail .title {
    font-size: 0.95rem;
    font-weight: 400;
    color: #505050;
    line-height: 1.5;
    word-break: break-all;
}

.cs_detail .data_admin {
    font-size: 11px;
    color: #969696;
    letter-spacing: 0;
    margin-top: 5px;
}

.cs_detail .data_admin .id_num {
    color: #49d094;
    font-weight: bold;
    display: inline-block;
    vertical-align: middle;
}

.cs_detail .data_admin .moment{
    position: relative;
    display: inline-block;
    vertical-align: middle;
    padding: 0 7px;
}

.cs_detail .data_admin img{
    width: 13px;
    vertical-align: middle;
    display: inline-block;
}

.cs_detail .data_admin i{
    font-style: normal;
    vertical-align: middle;
    display: inline-block;
}

.cs_detail .data_admin .icon > span,
.cs_detail .data_admin .status{
    vertical-align: middle;
    display: inline-block;
}

.cs_detail .status.wait {
    color: red;
    font-weight: 400;
}

.cs_detail .status.ok {
    color: #0072ff;
    font-weight: 400;
}

.trst-container .detail_con {
    background: #fff;
    padding: 20px;
    color: #505050;
    font-size: 14px;
    line-height: 1.75;
    overflow-y: scroll;
    overflow-x: hidden;
    display: block;
    font-weight: 400;
}

.cs_container .half_area {
    width: 100%;
    position: relative;
}

.cs_container .half_area:nth-child(1) {
    height: 40%;
    border-bottom: 1px solid #EBF0F0;
}

.cs_container .half_area:nth-child(2) {
    height: 60%;
}
</style>
