<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <div
                class="page-contents-wrapper"
            >
                <div class="in-title in-title-mini_ver">
                    <h1>정산 관리</h1>
                    <div>
                        <table class="type11">
                            <tbody>
                                <tr>
                                    <th>총 수익</th>
                                    <td colspan="2">
                                        {{ total.count || 0 }} 원
                                    </td>
                                </tr>
                                <tr>
                                    <th>정산액</th>
                                    <td colspan="2">
                                        {{ already.count || 0 }} 원
                                    </td>
                                </tr>
                                <tr>
                                    <th>정산 가능 총액</th>
                                    <td colspan="2">
                                        {{ posible.count || 0 }} 원
                                    </td>
                                </tr>
                                <tr>
                                    <th>무료 / 유료 <br>판매 횟수</th>
                                    <td colspan="2">
                                        {{ downCnt.free_count || 0 }} 회 / {{ downCnt.paid_count || 0 }} 회
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h1>비트별 정산 가능액</h1>
                    <h3>(수수료 20%)</h3>
                    <div>
                        <table class="type11">
                            <thead>
                                <tr>
                                    <th>판매번호</th>
                                    <th>판매일</th>
                                    <th>제목</th>
                                    <th>카테고리</th>
                                    <th>분위기</th>
                                    <th>판매금액</th>
                                    <th>수수료</th>
                                    <th>정산가능액</th>
                                    <th>정산신청일</th>
                                    <th>정산완료일</th>
                                    <th>설정</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="beat in orderList"
                                    :key="beat.beat_id"
                                >
                                    <td>#{{ beat.po_id }}</td>
                                    <td>{{ beat.created_at? toMoment(beat.created_at) : '' }}</td>
                                    <td style="text-overflow:ellipsis;">
                                        {{ beat.beat_title }}
                                    </td>
                                    <td>{{ beat.cate_title }}</td>
                                    <td>{{ beat.mood_title }}</td>
                                    <td>{{ beat.beat_price }} 원</td>
                                    <td>{{ beat.fee }} 원</td>
                                    <td>{{ beat.total }} 원</td>
                                    <td>{{ beat.po_reg_dt? toMoment(beat.po_reg_dt) : '' }}</td>
                                    <td>{{ beat.po_cpl_dt? toMoment(beat.po_cpl_dt) : '' }}</td>
                                    <td>
                                        <button
                                            v-if="beat.po_state === 0"
                                            class="registbtn"
                                            @click="registRevenue(beat.po_id)"
                                        >
                                            정산하기
                                        </button>
                                        <span v-else-if="beat.po_state === 1">
                                            정산중
                                        </span>
                                        <span v-else-if="beat.po_state === 2">
                                            정산완료
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isNoticePopupVisible"
        >
            <div class="popup-layer-txt">
                {{ noticePopupText }}
            </div>
        </notice-popup-component>

        <div class="popup-layer-txt">
            {{ confirmPopupText }}
        </div>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import moment from "moment";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            noticePopupText: "",
            confirmPopupText: "",
            isBeatChartLoading: false,
            total: "",
            posible: "",
            already: "",
            orderList: [],
            downCnt: []
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            producer: "producer"
        })
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                const getTotal = this.$http
                    .get(`/api/beatorders`, {
                        params: {
                            req: "revenue",
                            sub: "total"
                        }
                    })
                    .then(response => {
                        return response.data.length > 0 ? response.data[0] : {};
                    });

                const getAlready = this.$http
                    .get(`/api/beatorders`, {
                        params: {
                            req: "revenue",
                            sub: "already"
                        }
                    })
                    .then(response => {
                        return response.data.length > 0 ? response.data[0] : {};
                    });

                const getPossible = this.$http
                    .get(`/api/beatorders`, {
                        params: {
                            req: "revenue",
                            sub: "posible"
                        }
                    })
                    .then(response => {
                        return response.data.length > 0 ? response.data[0] : {};
                    });

                const getDownload = this.$http
                    .get(`/api/beatorders`, {
                        params: {
                            req: "fp_downloadCnt"
                        }
                    })
                    .then(response => {
                        return response.data.length > 0 ? response.data[0] : {};
                    });

                const getOrder = this.$http
                    .get(`/api/beatorders`, {
                        params: {
                            req: "revenue_at_beat",
                            prdc_id: this.producer.prdc_id
                        }
                    })
                    .then(response => {
                        return response.data;
                    });

                const infos = await Promise.all([
                    getTotal,
                    getAlready,
                    getPossible,
                    getDownload,
                    getOrder
                ]);

                this.total = infos[0];
                this.already = infos[1];
                this.posible = infos[2];
                this.downCnt = infos[3];
                this.orderList = infos[4];
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async registRevenue(po_id) {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.put(`/api/beatorders/registRevenue`, {
                    po_id: po_id
                });

                await this.fetchData();

                alert("정산신청 되었습니다");
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD");
        }
    }
};
</script>

<style lang="scss" scoped>
.popup-layer-txt {
    color: red;
}
table.type11 {
    border-collapse: separate;
    border-spacing: 1px;
    text-align: center;
    line-height: 1.5;
    margin: 20px 10px;
}
table.type11 th {
    width: 155px;
    padding: 10px;
    font-weight: bold;
    vertical-align: top;
    color: #fff;
    background: #6256a9 ;
}
table.type11 td {
    width: 155px;
    padding: 10px;
    vertical-align: top;
    border-bottom: 1px solid #ccc;
    background: #eee;
    white-space: nowrap;
}
.registbtn {
    display: inline-block;
    padding: 0em 0.75em;
    color: #000;
    font-size: inherit;
    line-height: normal;
    vertical-align: middle;
    background-color: #fdfdfd;
    cursor: pointer;
    border: 1px solid #7e2aff;
    border-radius: 0.25em;
}
</style>
