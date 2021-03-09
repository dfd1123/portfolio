<template>
    <div class="ai_wrapper top_0">
        <header-component
            center="text"
            :centerText="__('customer_service.qna_create_title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="icon_box top_5vh top_30vh security_ok">
                <span class="state_img">
                    <img src="images/icon/pop_wd_complete.png" alt="icon" />
                </span>
                <span v-if="status === 'created'" class="ment_1">
                    <strong>{{__('customer_service.qna_status_ment1_created')}}</strong>
                </span>
                <span v-else-if="status === 'edited'" class="ment_1">
                    <strong>{{__('customer_service.qna_status_ment1_edited')}}</strong>
                </span>
                <span v-else-if="status === 'deleted'" class="ment_1">
                    <strong>{{__('customer_service.qna_status_ment1_deleted')}}</strong>
                </span>
                <span
                    v-if="status === 'created'"
                    class="ment_2 line_h"
                    v-html="__('customer_service.qna_status_ment2_created')"
                ></span>
                <span
                    v-else-if="status === 'edited'"
                    class="ment_2 line_h"
                    v-html="__('customer_service.qna_status_ment2_edited')"
                ></span>
                <span
                    v-else-if="status === 'deleted'"
                    class="ment_2 line_h"
                    v-html="__('customer_service.qna_status_ment2_deleted')"
                ></span>
            </div>
            <footer-component
                :buttonText="__('customer_service.list')"
                v-on:buttonClick="listButtonClick"
            ></footer-component>
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent
    },
    data() {
        return {
            status: this.$route.params.status
        };
    },
    methods: {
        listButtonClick() {
            this.$router.replace({
                name: "customer_service",
                params: { page: "qna" }
            });
        },
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

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: #000;
    height: 100%;
}

.scroll_area {
    overflow-x: hidden;
    overflow-y: auto;
}
.ai_wrapper .icon_box.top_5vh.security_ok {
    padding-top: 15vh;
}

.icon_box {
    position: relative;
    text-align: center;
    height: auto;
    margin-bottom: 7px;
}

.state_img {
    width: 100%;
    display: inline-block;
    vertical-align: baseline;
}

.state_img img {
    width: 62px;
    vertical-align: baseline;
}

.sd_box .icon_box .state_img,
.ment_1 {
    width: 100%;
    float: left;
    display: inline-block;
}

.ment_1 {
    font-size: 17px;
    color: #5a5a5a;
    padding: 10px 0;
    font-weight: 600;
    float: left;
    display: inline-block;
}

.ment_1 strong {
    color: #2E87C8;
}

.ment_1 {
    font-size: 15px;
    color: #5a5a5a;
    padding: 10px 0;
    font-weight: 600;
    float: left;
    display: inline-block;
}

.ment_2 {
    font-size: 12px;
    font-weight: bold;
    color: #8b8b8b;
    width: 100%;
    display: inline-block;
}

.ment_2.line_h {
    line-height: 20px;
}

b,
strong {
    font-weight: 700;
}
</style>
