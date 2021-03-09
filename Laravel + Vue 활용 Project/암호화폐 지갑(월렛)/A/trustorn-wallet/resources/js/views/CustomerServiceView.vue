<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="tabNames[headerTitleSelected]"
            v-on:centerTextClick="fetchData"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor cs_container">
            <tabs v-on:tabIndexSelected="tabSelected">
                <tab :name="tabNames.notice" :selected="initialSelected === 'notice'">
                    <ul class="list_area">
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
                    <div class="container_tab">
                        <ul class="faq_area">
                            <div v-for="faq in faqs" :key="faq.id" class="faq_container">
                                <li class="faq_list" @click="accordionItemClick(faq)">
                                    <span class="icon">Q</span>
                                    <span class="title">{{faq.q}}</span>
                                    <span class="arrow">
                                        <i v-if="faq.open" class="far fa-angle-down down_arrow"></i>
                                        <i v-else class="far fa-angle-up down_arrow"></i>
                                    </span>
                                </li>
                                <li class="list_con" :class="{active: faq.open}" v-html="faq.a">
                                </li>
                            </div>
                        </ul>
                    </div>
                </tab>
                <tab :name="tabNames.qna" :selected="initialSelected === 'qna'">
                    <div class="container_tab">
                        <div class="direct_area direct_area_btn">
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
                        </div>
                        <div class="direct_area direct_area_con">
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
                                                <span class="createdby">{{qna.createdby}}</span>
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
                                    >
                                        <img src="/images/trst-images/icon/icon_empty_list.svg" alt="empty">
                                        <p>{{__('customer_service.qna_empty')}}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </tab>
                <tab :name="tabNames.tos" :selected="initialSelected === 'tos'">
                    <div class="container_tab">
                        <div class="pol_area">
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
    padding-top: 2.815rem;
}

.cs_container a {
    text-decoration: none;
}

.container_tab {
    height: 100%;
    width: 100%;
}

.list_area,
.faq_area {
    margin: 0;
    width: 100%;
    height: 100%;
    background-color: white;
    padding: 0;
    overflow: scroll;
}

.list_area li {
    border-bottom: 1px solid #EBF0F0;
    width: 100%;
    display: table;
    background: #fff;
    height: 57px;
}

.list_area li > a{
    display: table-cell;
    vertical-align: middle;
    position: relative;
    padding-left: 60px;
}

.list_area li:active{
    background-color: #F5F5F5;
}

.list_area .num {
    color: #49d094;
    font-size: 14px;
    position: absolute;
    top: 50%;
    left: 18px;
    font-weight: bold;
    transform: translateY(-50%);
}

.list_area .title {
    font-size: 12px;
    color: #505050;
    font-weight: 400;
    word-break: break-all;
}

.list_area .data_admin {
    font-size: 9px;
    color: #BEC8C8;
    margin-top: 7px;
    font-weight: 300;
    letter-spacing: 0;
}

.faq_container {
    overflow: hidden;
}

.faq_list {
    border-bottom: 1px solid #EBF0F0;
    padding: 18px 40px 18px 45px;
    width: 100%;
    position: relative;
    background: #fff;
    cursor: pointer;
}

.faq_list .icon {
    font-size: 16px;
    color: #49d094;
    font-weight: bold;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 18px;
}

.faq_list .title {
    width: 100%;
    color: #505050;
    font-size: 0.85em;
    line-height: 1.4;
    font-weight: 400;
}

.faq_list .arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 18px;
}

.faq_list .arrow .down_arrow {
    color: #ffa000;
    font-weight: 300;
    font-size: 21px;
}

.faq_area .list_con {
    width: 100%;
    background-color: #F5F5F5;
    padding: 13px 40px;
    border-bottom: 1px solid #EBF0F0;
    float: left;
    margin-top: -100%;
    color: #505050;
    font-size: 12px;
    word-break: break-all;
    font-size: 0.85em;
    line-height: 1.5;
    font-weight: 400;
}

.faq_area .list_con.active {
    margin-top: 0px;
}

.direct_area {
    width: 100%;
    position: relative;
    height: 74px;
    padding: 14px 15px;
    background-color: #F5F5F5;
}

.direct_area_btn > a {
    width: 100%;
    height: 100%;
    color: #005A50;
    font-weight: 400;
    font-size: 15px;
    display: flex;
    align-items: center;
    padding: 0 15px;
    background-color: white;
    box-shadow: 0 8px 14px rgba(195, 215, 215, 0.35);
    position: relative;
}

.direct_area_btn > a .right {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 12px;
    color: #FFB400;
    font-size: 18px;
}

.direct_area_con {
    height: calc(100% - 74px);
    padding: 0;
    overflow: hidden;
}

.direct_area_con .form_line {
    height: 100%;
}

.direct_area_con .label_hr {
    width: 100%;
    background-color: white;
    margin-bottom: 0;
    height: 60px;
    line-height: 60px;
    padding: 0 18px;
    font-weight: 500;
    font-size: 18px;
    padding-top: 5px;
    letter-spacing: -0.4px;
}

.direct_area_con .list_area {
    height: calc(100% - 60px);
    overflow-x: hidden;
    overflow-y: scroll;
    padding-bottom: 0;
}

.direct_area_con .non_data {
    text-align: center;
    padding: 29px 10px !important;
    line-height: 38px;
    background: none;
    border: none;
    text-align: center;
}

.direct_area_con .non_data p{
    font-weight: 400;
    color: #c3d7d7;
    font-size: 14px;
}

.direct_area_con .data_admin .createdby {
    padding: 0 7px;
}

.direct_area_con .data_admin .status.ok {
    color: #0072ff;
    font-weight: 400;
}

.direct_area_con .data_admin .status.wait {
    color: red;
    font-weight: 400;
}

.ai_wrapper .pol_area {
    width: 100%;
    border-radius: 0;
    padding: 0;
}
</style>
