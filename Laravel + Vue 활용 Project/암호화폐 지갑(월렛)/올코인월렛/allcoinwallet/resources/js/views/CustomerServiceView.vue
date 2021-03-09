<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="tabNames[headerTitleSelected]"
            v-on:centerTextClick="fetchData"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor cs_container">
            <tabs v-on:tabIndexSelected="tabSelected">
                <tab :name="tabNames.notice" :selected="initialSelected === 'notice'">
                    <ul class="sd_box list_area">
                        <li
                            v-for="(notice, index) in $store.state.customerServiceViewData.notices"
                            :key="notice.id"
                        >
                            <a
                                href="javascript:void(0);"
                                @click.prevent="$router.replace({name: 'customer_service_notice', params: { id: notice.id }})"
                            >
                                <div class="num">{{String(index + 1).padStart(3, '0')}}</div>
                                <p class="title">{{notice.title}}</p>
                                <p class="data_admin">
                                    <span>{{toMoment(notice.created).format("YYYY-MM-DD")}}</span>
                                    <span>{{__('customer_service.admin')}}</span>
                                </p>
                            </a>
                        </li>
                    </ul>
                </tab>
                <tab :name="tabNames.faq" :selected="initialSelected === 'faq'">
                    <div class="cs_faq container_tap">
                        <ul class="sd_box faq_area">
                            <div v-for="faq in faqs" :key="faq.id" class="faq_container">
                                <li class="faq_list" @click="accordionItemClick(faq)">
                                    <span>Q</span>
                                    <span class="title">{{faq.q}}</span>
                                    <span>
                                        <i v-if="faq.open" class="far fa-angle-down down_arrow"></i>
                                        <i v-else class="far fa-angle-up down_arrow"></i>
                                    </span>
                                </li>
                                <li class="list_con" :class="{active: faq.open}">
                                    <span v-html="faq.a"></span>
                                </li>
                            </div>
                        </ul>
                    </div>
                </tab>
                <tab :name="tabNames.qna" :selected="initialSelected === 'qna'">
                    <div class="cs_direct container_tap">
                        <div class="sd_box direct_area">
                            <div class="form_line">
                                <ul>
                                    <li class="label_hr">
                                        <a
                                            href="javascript:void(0)"
                                            @click="$router.replace('/customer_service_qna_create')"
                                        >
                                            {{__('customer_service.qna_create')}}
                                            <span
                                                class="right"
                                            >
                                                <i class="far fa-angle-right down_arrow"></i>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="sd_box direct_area">
                            <div class="form_line">
                                <label class="label_hr">{{__('customer_service.qna_my')}}</label>
                                <ul class="list_area">
                                    <li
                                        v-for="(qna, index) in $store.state.customerServiceViewData.qnas"
                                        :key="qna.id"
                                    >
                                        <a
                                            href="javascript:void(0);"
                                            @click.prevent="$router.replace({name: 'customer_service_qna', params: { id: qna.id }})"
                                        >
                                            <div class="num">{{String(index + 1).padStart(3, '0')}}</div>
                                            <p class="title">{{qna.title}}</p>
                                            <p class="data_admin">
                                                <span>{{toMoment(qna.created).format("YYYY-MM-DD HH:mm")}}</span>
                                                <span>{{qna.createdby}}</span>
                                                <span
                                                    v-if="qna.answered === 0"
                                                    class="status wait"
                                                >{{__('customer_service.qna_waiting')}}</span>
                                                <span
                                                    v-if="qna.answered === 1"
                                                    class="status ok"
                                                >{{__('customer_service.qna_ok')}}</span>
                                            </p>
                                        </a>
                                    </li>
                                    <li
                                        v-if="$store.state.customerServiceViewData.qnas.length === 0"
                                        class="non_data"
                                    >{{__('customer_service.qna_empty')}}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </tab>
                <tab :name="tabNames.tos" :selected="initialSelected === 'tos'">
                    <div class="cs_policy container_tap">
                        <div class="sd_box pol_area">
                            <ul class="list_area">
                                <li>
                                    <a
                                        href="javascript:void(0);"
                                        @click.prevent="$router.replace({name: 'customer_service_tos', params: { type: 'use_term' }})"
                                    >
                                        <div class="num">001</div>
                                        <p class="title">{{__('customer_service.tos1')}}</p>
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="javascript:void(0);"
                                        @click.prevent="$router.replace({name: 'customer_service_tos', params: { type: 'private_info_term' }})"
                                    >
                                        <div class="num">002</div>
                                        <p class="title">{{__('customer_service.tos2')}}</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </tab>
            </tabs>
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import TabContainerComponent from "../components/common/TabContainerComponent";
import TabComponent from "../components/common/TabComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        if (
            from.path === "/home" ||
            from.path === "/customer_service_qna_status" ||
            from.path === "/customer_service_qna"
        ) {
            return next(async vm => {
                await vm.fetchData();
            });
        }
        next();
    },
    components: {
        "header-component": HeaderComponent,
        tabs: TabContainerComponent,
        tab: TabComponent
    },
    data() {
        return {
            initialSelected:
                this.$route.params.page ||
                this.$store.state.customerServiceViewData.selected ||
                "notice",
            headerTitleSelected:
                this.$route.params.page ||
                this.$store.state.customerServiceViewData.selected ||
                "notice",
            tabNames: {
                notice: this.__("customer_service.notice"),
                faq: this.__("customer_service.faq"),
                qna: this.__("customer_service.qna"),
                tos: this.__("customer_service.tos")
            },
            faqs: [
                {
                    id: 0,
                    open: false,
                    q: this.__("customer_service.faq_0_q"),
                    a: this.__("customer_service.faq_0_a")
                },
                {
                    id: 1,
                    open: false,
                    q: this.__("customer_service.faq_1_q"),
                    a: this.__("customer_service.faq_1_a")
                },
                {
                    id: 2,
                    open: false,
                    q: this.__("customer_service.faq_2_q"),
                    a: this.__("customer_service.faq_2_a")
                },
                {
                    id: 3,
                    open: false,
                    q: this.__("customer_service.faq_3_q"),
                    a: this.__("customer_service.faq_3_a")
                },
                {
                    id: 4,
                    open: false,
                    q: this.__("customer_service.faq_4_q"),
                    a: this.__("customer_service.faq_4_a")
                },
                {
                    id: 5,
                    open: false,
                    q: this.__("customer_service.faq_5_q"),
                    a: this.__("customer_service.faq_5_a")
                },
                {
                    id: 6,
                    open: false,
                    q: this.__("customer_service.faq_6_q"),
                    a: this.__("customer_service.faq_6_a")
                }
            ],
            qnas: []
        };
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("progressComponentShow");

                const datas = (await Promise.all([
                    axios.get(`/api/notices`),
                    axios.get(`/api/qnas`)
                ])).map(response => {
                    return response.data;
                });

                this.$store.commit("updateCustomerServiceViewData", {
                    selected: this.$route.params.page || "notice",
                    notices: datas[0],
                    qnas: datas[1]
                });
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        tabSelected(index) {
            const selected = Object.keys(this.tabNames)[index];
            if (
                this.$store.state.customerServiceViewData.selected !== selected
            ) {
                this.$store.commit("mergeCustomerServiceViewData", {
                    selected
                });
                this.headerTitleSelected = selected;
            }
        },
        toMoment(timestamp) {
            return moment(Number(timestamp) * 1000).add(9, 'hours');
        },
        accordionItemClick(targetItem) {
            this.faqs = [...this.faqs].map(item => {
                if (item.id === targetItem.id) {
                    item.open = !item.open;
                } else {
                    item.open = false;
                }
                return item;
            });
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

.ai_wrapper .cs_container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.ai_wrapper .sd_box.list_area,
.ai_wrapper .sd_box.faq_area {
    width: calc(100% - 20px);
    margin: 12px auto;
    height: calc(100% - 25px);
    background-color: white;
    border-radius: 0;
    padding: 0;
    overflow: scroll;
    overflow-x: hidden;
    background: #fafafa;
    box-shadow: 0px 5px 20px rgba(0, 69, 191, 0.2);
}

.list_area li {
    border-bottom: 1px solid #c8c8c8;
    width: 100%;
    padding: 13px 20px;
    transition-duration: 0.3s;
    background: #fff;
}

a {
    text-decoration: none;
    color: #333;
}
a {
    margin: 0;
    padding: 0;
    font-size: 100%;
    vertical-align: baseline;
    background: transparent;
}

a:focus,
a:hover {
    color: #009688;
    text-decoration: underline;
}

.list_area li .num,
.num {
    width: 30px;
    height: 30px;
    float: left;
    color: #49d094;
    font-size: 9px;
    padding-top: 4px;
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
    font-weight: 600;
}

.container_tap {
    height: 100%;
    width: 100%;
}

.faq_container {
    overflow: hidden;
}

.faq_list {
    border-bottom: 1px solid #c8c8c8;
    padding: 18px 15px;
    float: left;
    width: 100%;
    position: relative;
    background: #fff;
    cursor: pointer;
    transition-duration: 0.3s;
}

.faq_list span:nth-child(1) {
    font-size: 16px;
    color: #49d094;
    padding-right: 12px;
    padding-top: 2px;
    float: left;
}

.faq_list .title {
    float: left;
    width: 85%;
    line-height: 18px;
    padding-left: 0;
}

.faq_list span:nth-child(3) {
    position: absolute;
    right: 15px;
}

.down_arrow {
    color: #ffa000;
    font-size: 20px;
}

.sd_box.faq_area .list_con {
    width: 100%;
    padding: 13px 15px 13px 40px;
    border-bottom: 0px solid #c8c8c8;
    float: left;
    transition: all ease-in-out 0.7s;
    margin-top: -100%;
}

.sd_box.faq_area .list_con.active {
    border-bottom: 1px solid #c8c8c8;
    transition: all ease-in-out 0.7s;
    margin-top: 0px;
}

.list_con span {
    font-size: 12px;
    line-height: 18px;
    color: #5a5a5a;
    word-break: break-all;
}

.list_con span {
    font-size: 12px;
    line-height: 18px;
    color: #5a5a5a;
    word-break: break-all;
}

.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0px 5px 20px rgba(0, 69, 191, 0.2);
    margin: 0 auto;
    position: relative;
    padding: 15px 15px;
    margin-bottom: 15px;
}

.ai_wrapper .sd_box.direct_area {
    border-radius: 0;
    width: calc(100% - 20px);
    position: relative;
    height: 35px;
    margin-top: 12px;
    padding: 0;
}

.ai_wrapper .sd_box.direct_area:nth-child(1) .label_hr {
    background: #fff;
    transition-duration: 0.3s;
}

.ai_wrapper .sd_box.direct_area:nth-child(1) .label_hr a {
    width: 100%;
    height: 100%;
    color: #0747ad;
    display: block;
    padding-top: 10px;
    padding-left: 15px;
    transition-duration: 0.3s;
}

.ai_wrapper .sd_box.direct_area:nth-child(1) .right {
    position: absolute;
    top: 7px;
    right: 15px;
}

.ai_wrapper .sd_box.direct_area a {
    font-weight: 600;
    font-size: 14px;
}

.ai_wrapper .sd_box.direct_area:nth-child(2) {
    height: calc(100% - 75px);
    padding: 0;
    overflow: hidden;
    background: #fafafa;
}

.ai_wrapper .sd_box .form_line .label_hr {
    width: 100%;
    display: inline-block;
    margin-bottom: 5px;
    color: #0747ad;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.ai_wrapper .sd_box .form_line {
    height: 100%;
}

.ai_wrapper .sd_box.direct_area:nth-child(2) .label_hr {
    padding: 15px 15px 10px 15px;
    background: #fff;
    margin-bottom: 0;
}

.ai_wrapper .sd_box.direct_area:nth-child(2) ul.list_area {
    height: 100%;
    overflow: scroll;
    overflow-x: hidden;
    padding-bottom: 39px;
}

.list_area li {
    border-bottom: 1px solid #c8c8c8;
    width: 100%;
    padding: 13px 20px;
    transition-duration: 0.3s;
    background: #fff;
}

.non_data {
    text-align: center;
    padding: 29px 10px !important;
    line-height: 38px;
    font-size: 16px;
    font-weight: 500;
    color: #b5b5b5;
}

.list_area li.non_data {
    background: none;
    border: none;
    text-align: center;
    font-size: 14px;
    font-weight: 600;
    color: #aaa;
    padding: 35px 10px;
}

.list_area li .data_admin span:nth-child(2) {
    padding: 0 7px;
}

.status.ok {
    color: #0072ff;
}

.status.wait {
    color: red;
}

.ai_wrapper .sd_box.pol_area {
    width: 95%;
    border-radius: 0;
    padding: 0;
    margin-top: 12px;
}

.list_area li {
    border-bottom: 1px solid #c8c8c8;
    width: 100%;
    padding: 13px 20px;
    transition-duration: 0.3s;
    background: #fff;
}

.sd_box.pol_area li {
    padding: 0 20px;
}

.sd_box.pol_area li a {
    display: block;
    padding: 19px 0;
}

a {
    text-decoration: none;
    color: #333;
}
</style>
