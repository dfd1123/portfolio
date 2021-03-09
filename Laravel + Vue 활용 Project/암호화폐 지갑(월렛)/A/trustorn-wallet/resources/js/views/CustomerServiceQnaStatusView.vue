<template>
    <div class="ai_wrapper">
        <header-component
            center="text"
            :centerText="__('customer_service.qna_create_title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <div class="icon_box">
                <span class="state_img">
                    <img src="images/trst-images/icon/icon_complete.svg" alt="icon" />
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
                    class="ment_2"
                    v-html="__('customer_service.qna_status_ment2_created')"
                ></span>
                <span
                    v-else-if="status === 'edited'"
                    class="ment_2"
                    v-html="__('customer_service.qna_status_ment2_edited')"
                ></span>
                <span
                    v-else-if="status === 'deleted'"
                    class="ment_2"
                    v-html="__('customer_service.qna_status_ment2_deleted')"
                ></span>
            </div>
        </div>
        <footer-component
            :buttonText="__('customer_service.list')"
            v-on:buttonClick="listButtonClick"
        ></footer-component>
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
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}

.bgcolor{
    background-color: #FAFAFA;
}

.trst-container{
    display: table;
    width: 100%;
}

.trst-container .icon_box{
    display: table-cell;
    vertical-align: middle;
}
</style>
