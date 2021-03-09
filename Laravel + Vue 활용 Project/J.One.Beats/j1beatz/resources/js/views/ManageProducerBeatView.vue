<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div
                v-infinite-scroll="scrollAtBottom"
                infinite-scroll-immediate-check="false"
                class="page-contents-wrapper"
            >
                <button class="registbtn" @click="registpage">비트등록</button>

                <div class="in-title in-title-mini_ver">
                    <h1>내가 올린 비트 목록</h1>
                    <div>
                        <table class="type11">
                            <thead>
                                <tr>
                                    <th>제목</th>
                                    <th>카테고리</th>
                                    <th>분위기</th>
                                    <th>가격</th>
                                    <th>비고</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="beat in beatChartList" :key="beat.beat_id">
                                    <td>{{ beat.beat_title }}</td>
                                    <td>{{ beat.cate_title }}</td>
                                    <td>{{ beat.mood_title }}</td>
                                    <td>{{ beat.beat_price }}</td>
                                    <td>
                                        <button
                                            class="detailbtn"
                                            @click.prevent="$router.push(`/manage-producer-beat-detail/${beat.beat_id}`, () => {})"
                                        >상세 페이지</button>
                                        <button
                                            v-if="beat.beat_free ==0"
                                            class="detailbtn"
                                            @click="updateStateBeat(`${beat.beat_id}`,`${beat.beat_free}`)"
                                        >무료 ON</button>
                                        <button
                                            v-else
                                            class="detailbtn"
                                            @click="updateStateBeat(`${beat.beat_id}`,`${beat.beat_free}`)"
                                        >무료 OFF</button>
                                        <button
                                            class="deletebtn"
                                            @click="deleteBeat(`${beat.beat_id}`)"
                                        >비트 삭제</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <notice-popup-component title-text="알림" :visible.sync="isNoticePopupVisible">
            <div class="popup-layer-txt">{{ noticePopupText }}</div>
        </notice-popup-component>

        <confirm-popup-component
            title-text="확인"
            :visible.sync="isConfirmPopupVisible"
            right-button-text="알림 텍스트"
            @rightButtonClick="confirmClick"
        >
            <div class="popup-layer-txt">{{ confirmPopupText }}</div>
        </confirm-popup-component>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";
import infiniteScroll from "vue-infinite-scroll";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
        ConfirmPopupComponent
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            noticePopupText: "",
            confirmPopupText: "",
            isBeatChartLoading: false,
            beatChartList: [],
            offset: 0,
            limit: 10
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
        await this.loadBeatChartList();
    },
    methods: {
        confirmClick() {
            this.noticePopupText = "wow";
            alert();
        },
        registpage() {
            this.$router.push("manage-producer-beat-regist", () => {});
        },
        async loadBeatChartList() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                const result = await this.$http
                    .get(`/api/beats`, {
                        params: {
                            req: "prdc_list",
                            prdc_id: this.producer.prdc_id,
                            offset : this.offset,
                            limit : this.limit
                        }
                    })
                    .then(response => {
                        return response.data;
                    });
                this.beatChartList = [
                    ...new Map(
                        this.beatChartList
                            .concat(result)
                            .map(item => [item["beat_id"], item])
                    ).values()
                ];
                this.isScrollAtBottom = result.length < this.limit;
                this.offset = this.offset + result.length;
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async deleteBeat(beat_id) {
            try {
                await this.$http.put(`/api/beats/${beat_id}`, {
                    req: "beatdelete"
                });
                await this.loadBeatChartList();
            } catch (e) {
                console.log(e);
            }
        },
        async updateStateBeat(beat_id, beat_free) {
            try {
                await this.$http.put(`/api/beats/${beat_id}`, {
                    req: "beatfree",
                    beat_free: beat_free
                });
                await this.loadBeatChartList();
            } catch (e) {
                console.log(e);
            }
        },
        async scrollAtBottom() {
            if (!this.isScrollAtBottom) {
                this.isScrollAtBottom = true;
                this.loadBeatChartList();
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
    background: #6256a9;
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
    margin-bottom: 2em;
}
.detailbtn {
    padding: 1em;
    border: 1px solid #6256a9;
    background-color: #fff;
    border-radius: 0.7em;
    cursor: pointer;
}
.deletebtn {
    padding: 1em;
    border: none;
    background-color: red;
    border-radius: 0.7em;
    color: #fff;
    cursor: pointer;
}
</style>
