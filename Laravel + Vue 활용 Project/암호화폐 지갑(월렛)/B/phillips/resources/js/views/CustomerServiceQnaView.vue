<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            :leftButtonRoute="{name: 'customer_service', params: { page: 'qna'}}"
            center="text"
            :centerText="__('customer_service.qna')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor cs_container direct_ok_area" v-show="isLoaded">
            <div :class="{half_area: qna.answered }">
                <div class="cs_tap cs_detail ntc_detail">
                    <p class="title">{{qna.title}}</p>
                    <p class="data_admin">
                        <span>{{qna.id}}</span>
                        <span>
                            <img src="/images/data_icon.svg" alt="data-icon" />
                            {{toMoment(qna.created).format('YYYY-MM-DD HH:mm:ss')}}
                        </span>
                        <span>
                            <img src="/images/writer_icon.svg" alt="writer-icon" />
                        </span>
                        <span
                            v-if="qna.answered === 0"
                            class="status wait"
                        >{{__('customer_service.qna_under')}}</span>
                        <span
                            v-if="qna.answered === 1"
                            class="status ok"
                        >{{__('customer_service.qna_ok')}}</span>
                    </p>
                </div>
                <div class="container_tap detail_con">{{qna.description}}</div>
            </div>
            <div v-for="comment in qna.comments" :key="comment.id" class="half_area">
                <div class="cs_tap cs_detail ntc_detail">
                    <p class="title">안녕하세요. Phillips Pay 운영담당자입니다.</p>
                    <p class="data_admin">
                        <span>{{comment.id}}</span>
                        <span>
                            <img src="/images/data_icon.svg" alt="data-icon" />
                            {{toMoment(comment.created).format('YYYY-MM-DD HH:mm:ss')}}
                        </span>
                        <span>
                            <img src="/images/writer_icon.svg" alt="writer-icon" />
                            {{__('customer_service.admin')}}
                        </span>
                    </p>
                </div>
                <div class="container_tap detail_con" v-html="comment.description"></div>
            </div>
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
            qna: {}
        };
    },
    async created() {
        try {
            this.$store.commit("progressComponentShow");

            this.qna = (await axios.get(
                `/api/qnas/${this.$route.params.id}`
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
    padding-top: 43px;
    margin-bottom: -53px;
}

.ai_wrapper.top_0 {
    padding-top: 45px;
}

.ai_wrapper .cs_container.direct_ok_area {
    overflow: scroll;
    overflow-x: hidden;
}

.ai_wrapper .cs_container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.bgcolor {
    background-color: #fafafa;
    height: 100%;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.direct_ok_area .half_area {
    width: 100%;
    position: relative;
}

.direct_ok_area .half_area:nth-child(1) {
    height: 40%;
}

.direct_ok_area .half_area:nth-child(2) {
    height: 60%;
}

.cs_tap {
    width: 100%;
    background: white;
    border-bottom: 1px solid #f1f1f1;
    height: auto;
    position: absolute;
    top: 0;
    z-index: 1;
}

.cs_tap.cs_detail {
    background: #fafafa;
}

.cs_tap.cs_detail.ntc_detail {
    height: 55px;
    z-index: 2;
}

.title {
    font-size: 12px;
    color: #5a5a5a;
    line-height: 18px;
    word-break: break-all;
    padding-left: 30px;
}

.cs_tap.cs_detail.ntc_detail .title {
    height: unset;
    line-height: 0;
    padding: 20px 20px 9px;
    font-weight: bold;
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
}

.cs_tap.cs_detail.ntc_detail .data_admin span:nth-child(3) {
    padding-right: 7px;
}

.status.wait {
    color: red;
}

.status.ok {
    color: #0072ff;
}

.ai_wrapper .container_tap {
    width: 100%;
    height: 100%;
    position: absolute;
    padding-top: 46.5px;
    background: #fafafa;
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
</style>
