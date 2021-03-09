<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:-->
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <my-page-tab-component />

            <div
                v-infinite-scroll="scrollAtBottom"
                infinite-scroll-immediate-check="false"
                class="page-contents-wrapper"
            >
                <div class="sub-page-mypage">
                    <div class="sub-page-mypage-02">
                        <div class="mypage-02-option-bar">
                            <input
                                id="check-order-history-all"
                                v-model="period"
                                type="radio"
                                name="order-history-period"
                                class="none-input"
                                value="1"
                            >
                            <input
                                id="check-order-history-week1"
                                v-model="period"
                                type="radio"
                                name="order-history-period"
                                class="none-input"
                                value="2"
                            >
                            <input
                                id="check-order-history-week2"
                                v-model="period"
                                type="radio"
                                name="order-history-period"
                                class="none-input"
                                value="3"
                            >
                            <input
                                id="check-order-history-month1"
                                v-model="period"
                                type="radio"
                                name="order-history-period"
                                class="none-input"
                                value="4"
                            >
                            <input
                                id="check-order-history-month3"
                                v-model="period"
                                type="radio"
                                name="order-history-period"
                                class="none-input"
                                value="5"
                            >

                            <ul class="_history_period_list01">
                                <li
                                    class="_all"
                                    @click="dateSearch(0)"
                                >
                                    <label for="check-order-history-all">전체</label>
                                </li>
                                <li
                                    class="_week1"
                                    @click="dateSearch(1, 'weeks')"
                                >
                                    <label for="check-order-history-week1">1주일</label>
                                </li>
                                <li
                                    class="_week2"
                                    @click="dateSearch(2, 'weeks')"
                                >
                                    <label for="check-order-history-week2">2주일</label>
                                </li>
                                <li
                                    class="_month1"
                                    @click="dateSearch(1, 'months')"
                                >
                                    <label for="check-order-history-month1">1개월</label>
                                </li>
                                <li
                                    class="_month3"
                                    @click="dateSearch(3, 'months')"
                                >
                                    <label for="check-order-history-month3">3개월</label>
                                </li>
                            </ul>

                            <div class="_history_period_list02">
                                <label>기간입력</label>
                                <input
                                    id="datepicker1"
                                    type="text"
                                    class="_history_period_start"
                                    placeholder="yyyy-mm-dd"
                                >
                                <span>~</span>
                                <input
                                    id="datepicker2"
                                    type="text"
                                    class="_history_period_end"
                                    placeholder="yyyy-mm-dd"
                                >
                            </div>
                        </div>

                        <div class="in-title">
                            <h3 class="in-title-name">
                                총 결제 주문내역 (
                                <b>{{ beatorderList.length }}</b>곡)
                            </h3>
                        </div>

                        <div class="cart_chart_list">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="th-song">
                                            <div>곡/프로듀서</div>
                                        </th>
                                        <th class="th-order_data">
                                            <div>주문일자</div>
                                        </th>
                                        <th class="th-pay_type">
                                            <div>결제수단</div>
                                        </th>
                                        <th class="th-price">
                                            <div>가격</div>
                                        </th>
                                        <th class="th-file_type">
                                            <div>파일형태</div>
                                        </th>
                                        <th class="th-download">
                                            <div>다운로드</div>
                                        </th>
                                    </tr>
                                </thead>
                            </table>

                            <table>
                                <tbody>
                                    <tr
                                        v-for="beatorder in beatorderList"
                                        :key="beatorder.bo_id"
                                    >
                                        <td class="td-song">
                                            <div class="c-song-title">
                                                <span
                                                    class="thumb-mini"
                                                    :style="{'background-image': `url(/fdata/beathumb/${beatorder.beat_thumb})`}"
                                                >
                                                    <span class="none">썸네일</span>
                                                </span>
                                                <p class="c-song-title-info">
                                                    <b>{{ beatorder.beat_title }}</b>
                                                    <br>
                                                    <span>{{ beatorder.prdc_nick }}</span>
                                                </p>
                                            </div>
                                        </td>
                                        <td class="td-order_data">
                                            <div>
                                                <span>{{ toMoment(beatorder.created_at) }}</span>
                                            </div>
                                        </td>
                                        <td class="td-pay_type">
                                            <div v-if="beatorder.po_pg_type == 0">
                                                신용카드 결제
                                            </div>
                                            <div v-else-if="beatorder.po_pg_type == 1">
                                                가상계좌 결제
                                            </div>
                                            <div v-else-if="beatorder.po_pg_type == 2">
                                                휴대폰 결제
                                            </div>
                                        </td>
                                        <td class="td-price">
                                            <div>
                                                <b>{{ beatorder.beat_price }}</b>
                                                <em>원</em>
                                            </div>
                                        </td>
                                        <td class="td-file_type">
                                            <!--
                                            <div
                                                v-if="beatorder.mp3 === 1 && beatorder.wav === 1"
                                            >
                                                mp3, wav
                                            </div>
                                            <div v-else-if="beatorder.mp3 === 1">
                                                mp3
                                            </div>
                                            -->
                                            <div v-if="beatorder.wav === 1">
                                                wav
                                            </div>
                                        </td>
                                        <td class="td-download">
                                            <div v-if="beatorder.state === 0">
                                                결제취소
                                            </div>
                                            <div v-else-if="beatorder.state === 1">
                                                결제대기
                                            </div>
                                            <template v-else>
                                                <template v-if="beatorder.available === true">
                                                    <!-- <button
                                                        v-if="beatorder.mp3 === 1"
                                                        type="button"
                                                        class="btn"
                                                        @click="download(beatorder, 'mp3')"
                                                    >mp3 다운로드</button> -->
                                                    <button
                                                        v-if="beatorder.wav === 1"
                                                        type="button"
                                                        class="btn"
                                                        @click="download(beatorder, 'wav')"
                                                    >
                                                        wav 다운로드
                                                    </button>
                                                </template>
                                                <template v-else>
                                                    <div>기간종료</div>
                                                </template>
                                            </template>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <license-info-popup :visible.sync="isLicensePopupVisible" />
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import MyPageTabComponent from "../components/my-page/MyPageTabComponent";
import LicenseInfoPopup from "../components/common/LicenseInfoPopup";
import moment from "moment";
import { saveAs } from "file-saver";
import infiniteScroll from "vue-infinite-scroll";

export default {
    directives: { infiniteScroll },
    components: {
        HeaderComponent,
        FooterComponent,
        MyPageTabComponent,
        LicenseInfoPopup
    },
    data() {
        return {
            isLicensePopupVisible: false,
            beatorderList: [],
            date: "",
            startDate: "",
            endDate: "",
            period: 2,
            offset: 0,
            limit: 10
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user"
        })
    },
    mounted() {
        $.datepicker.setDefaults({
            dateFormat: "yy-mm-dd",
            prevText: "이전 달",
            nextText: "다음 달",
            monthNames: [
                "1월",
                "2월",
                "3월",
                "4월",
                "5월",
                "6월",
                "7월",
                "8월",
                "9월",
                "10월",
                "11월",
                "12월"
            ],
            monthNamesShort: [
                "1월",
                "2월",
                "3월",
                "4월",
                "5월",
                "6월",
                "7월",
                "8월",
                "9월",
                "10월",
                "11월",
                "12월"
            ],
            dayNames: ["일", "월", "화", "수", "목", "금", "토"],
            dayNamesShort: ["일", "월", "화", "수", "목", "금", "토"],
            dayNamesMin: ["일", "월", "화", "수", "목", "금", "토"],
            showMonthAfterYear: true,
            yearSuffix: "년"
        });

        const today = moment().format("YYYY-MM-DD");
        this.startDate = today;
        this.endDate = today;

        $("#datepicker1")
            .datepicker()
            .on("change", e => {
                if (moment(e.target.value, "YYYY-MM-DD", true).isValid()) {
                    $("#datepicker2").datepicker(
                        "option",
                        "minDate",
                        e.target.value
                    );
                    this.startDate = e.target.value;
                    this.period = 0;
                    this.loadBeatOrderList();
                }
            })
            .datepicker("setDate", today);

        $("#datepicker2")
            .datepicker()
            .on("change", e => {
                if (moment(e.target.value, "YYYY-MM-DD", true).isValid()) {
                    $("#datepicker1").datepicker(
                        "option",
                        "maxDate",
                        e.target.value
                    );
                    this.endDate = e.target.value;
                    this.period = 0;
                    this.loadBeatOrderList();
                }
            })
            .datepicker("setDate", today);

        this.dateSearch(1, "weeks");
    },
    methods: {
        async loadBeatOrderList() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                const result = await this.$http
                    .get(`/api/beatorders`, {
                        params: {
                            req: "list",
                            start_date: this.startDate,
                            end_date: this.endDate,
                            offset : this.offset,
                            limit : this.limit
                        }
                    })
                    .then(response => {
                        return response.data;
                    });
                this.beatorderList = [
                    ...new Map(
                        this.beatorderList
                            .concat(result)
                            .map(item => [item["po_id"], item])
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
        async download(beatorder, extention) {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http
                    .get(`/file/down/${extention}/${beatorder.po_id}`, {
                        header: {
                            Accept: "audio/mpeg"
                        },
                        responseType: "arraybuffer"
                    })
                    .then(response => {
                        // response.data is an empty object
                        saveAs(
                            new Blob([response.data]),
                            `${beatorder.beat_title}.${extention}`
                        );
                    });
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD HH:mm");
        },
        dateSearch(number, string) {
            if (number == 0) {
                this.startDate = null;
                this.endDate = null;
            } else {
                this.startDate = moment()
                    .subtract(number, string)
                    .format("YYYY-MM-DD");
                this.endDate = moment().format("YYYY-MM-DD");
            }
            this.loadBeatOrderList();
        },
        async scrollAtBottom() {
            if (!this.isScrollAtBottom) {
                this.isScrollAtBottom = true;
                this.loadBeatOrderList();
            }
        }
    }
};
</script>
<style lang="scss">
</style>
