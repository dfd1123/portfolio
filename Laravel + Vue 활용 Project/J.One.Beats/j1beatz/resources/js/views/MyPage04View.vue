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
                    <div class="sub-page-mypage-05">
                        <div class="sub-page-mypage-contents-inner">
                            <div v-if="isLoaded" class="sub-page-alarm-group">
                                <div v-if="alarmList.length == 0" class="sub-page-alarm-msg __no">
                                    <span class="_title">수신된 메세지가 없습니다.</span>
                                    <p class="_txt">
                                        <b>마이페이지 > 알림 메세지</b>는 제이원비츠에서 회원님께 알리는 개인 메세지입니다.
                                    </p>
                                </div>

                                <!-- <div class="sub-page-alarm-msg sub-page-alarm-msg-new __yes">
                                    <span class="_title">제이원비츠 운영자</span>
                                    <p class="_txt">
                                        회원님의 비밀번호가 2000 . 00 . 00 00 : 00 : 00 에 수정되었습니다.
                                        <br />비밀번호는 6개월 마다 주기적인 변경을 권장합니다.
                                    </p>
                                    <span class="_data">2000. 00. 00 00:00:00</span>
                                </div>-->
                                <div v-for="alarm in alarmList" v-else :key="alarm.alarm_id">
                                    <div
                                        v-if="toMoment(alarm.created_at) >= moment"
                                        class="sub-page-alarm-msg sub-page-alarm-msg-new __yes"
                                    >
                                        <span class="_title">제이원비츠 운영자</span>
                                        <p class="_txt">{{ alarm.alarm_content }}</p>
                                        <span class="_data">{{ toMoment(alarm.created_at) }}</span>
                                    </div>
                                    <div v-else class="sub-page-alarm-msg __yes">
                                        <span class="_title">제이원비츠 운영자</span>
                                        <p class="_txt">{{ alarm.alarm_content }}</p>
                                        <span class="_data">{{ toMoment(alarm.created_at) }}</span>
                                    </div>
                                </div>
                                <!-- <div class="sub-page-alarm-msg __yes">
                                    <span class="_title">제이원비츠 운영자</span>
                                    <p class="_txt">
                                        <b>마이페이지 > 알림 메세지</b>는 제이원비츠에서 회원님께 알리는 개인 메세지입니다.
                                    </p>
                                    <span class="_data">2000. 00. 00 00:00:00</span>
                                </div>-->
                            </div>
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
            isScrollAtBottom: false,
            isLoaded: false,
            isLicensePopupVisible: false,
            isAlarmLoading: false,
            alarmList: [],
            moment: "",
            offset: 0,
            limit: 10
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            alarms: "alarms"
        })
    },
    async created() {
        await this.loadBeatChartList();
        this.isLoaded = true;
        this.moment = this.toMoment(moment().subtract(3, "days"));
    },
    methods: {
        async loadBeatChartList() {
            try {
                const result = await this.$http
                    .get(`/api/alarms`, {
                        params: {
                            req:'default',
                            offset: this.offset,
                            limit: this.limit
                        }
                    })
                    .then(response => {
                        return response.data;
                    });
                this.alarmList = [
                    ...new Map(
                        this.alarmList
                            .concat(result)
                            .map(item => [item["alarm_id"], item])
                    ).values()
                ];
                this.isScrollAtBottom = result.length < this.limit;
                this.offset = this.offset + result.length;
            } catch (e) {
                console.log(e);
            }
        },
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD HH:mm");
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

<style lang="scss">
</style>
