<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <div class="in-title in-title-mini_ver">
                    <h1>내가 올린 비트 상세 화면</h1>
                    <div>
                        <table class="type11">
                            <tbody>
                                <tr>
                                    <th>곡 제목</th>
                                    <td colspan="2">
                                        {{ beatinfo[0].beat_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>카테고리</th>
                                    <td colspan="2">
                                        {{ beatinfo[0].cate_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>분위기</th>
                                    <td colspan="2">
                                        {{ beatinfo[0].mood_title }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>곡 시간</th>
                                    <td colspan="2">
                                        {{ beatinfo[0].beat_time }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>태그</th>
                                    <td colspan="2">
                                        {{ beatinfo[0].beat_tag }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>가격</th>
                                    <td colspan="2">
                                        {{ beatinfo[0].beat_price }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>좋아요</th>
                                    <td colspan="2">
                                        {{ beatinfo[0].beat_like }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>생성일</th>
                                    <td colspan="2">
                                        {{ beatinfo[0].created_at }}
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
        <!--/confirm-popup-component-->
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
//import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (Number.isInteger(Number(to.params.id))) {
            next();
        } else {
            next("/404");
        }
    },
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent
        //ConfirmPopupComponent
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            noticePopupText: "",
            confirmPopupText: "",
            isBeatChartLoading: false,
            beatinfo: [],
            id: Number(this.$route.params.id)
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            producer: "producer",
            beatItems: []
        })
    },
    async created() {
        await this.loadBeatDetail();
    },
    methods: {
        async loadBeatDetail() {
            this.$store.commit("updateIsGlobalLoading", true);
            try {
                this.beatinfo = await this.$http
                    .get(`/api/beats`, {
                        params: {
                            req: "prdc_detail",
                            prdc_id: this.producer.prdc_id,
                            beat_id: this.id
                        }
                    })
                    .then(response => {
                        return response.data;
                    });
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
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
}
.registbtn {
        display: inline-block;
        padding: 0.5em 0.75em;
        color: #000;
        font-size: inherit;
        line-height: normal;
        vertical-align: middle;
        background-color: #fdfdfd;
        cursor: pointer;
        border: 1px solid #7e2aff;
        border-radius: 0.25em;
        margin-bottom:2em;
    }
</style>
