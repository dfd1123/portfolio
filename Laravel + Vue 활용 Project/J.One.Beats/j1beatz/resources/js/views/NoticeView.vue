<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:-->
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <service-tab-component />

            <div
                v-infinite-scroll="scrollAtBottom"
                infinite-scroll-immediate-check="false"
                class="page-contents-wrapper"
            >
                <div class="sub-page-cs">
                    <section class="sub-page-cs-01">
                        <h3 class="none">공지사항</h3>

                        <div class="cs-sch-field">
                            <input
                                v-model="keyword"
                                type="text"
                                placeholder="검색어를 입력하세요."
                                @input="keywordIME = $event.target.value"
                            />
                            <button type="button">
                                <span class="none">검색버튼</span>
                            </button>
                        </div>

                        <div class="cs-board">
                            <div
                                v-for="(notice, index) in filteredNotices"
                                :key="notice.notice_id"
                                class="cs-board-tab"
                            >
                                <input
                                    :id="`check-notice-tab-${index+1}`"
                                    v-model="notice.checked"
                                    type="checkbox"
                                    name="check-notice-tab"
                                    class="none-input"
                                />

                                <label
                                    :for="`check-notice-tab-${index+1}`"
                                    class="_label"
                                >
                                    <span class="_num">{{ index+1 }}</span>
                                    <span class="_subject">{{ notice.notice_title }}</span>
                                </label>

                                <div class="cs-board-tab-view">
                                    <p class="_contents">{{ notice.notice_content }}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <footer-component />
        </section>

        <notice-popup-component title-text="알림" :visible.sync="isNoticePopupVisible">
            <div class="popup-layer-txt">{{ noticePopupText }}</div>
        </notice-popup-component>

        <license-info-popup :visible.sync="isLicensePopupVisible" />
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import ServiceTabComponent from "../components/service-center/ServiceTabComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import LicenseInfoPopup from "../components/common/LicenseInfoPopup";
import moment from "moment";
import infiniteScroll from "vue-infinite-scroll";

export default {
    directives: { infiniteScroll },
    components: {
        HeaderComponent,
        FooterComponent,
        ServiceTabComponent,
        NoticePopupComponent,
        LicenseInfoPopup
    },
    data() {
        return {
            isScrollAtBottom: false,
            isNoticePopupVisible: false,
            isLicensePopupVisible: false,
            lcens_name: "",
            noticePopupText: "",
            noticeList: [],
            keyword: "",
            keywordIME: "",
            offset: 0,
            limit: 10
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user"
        }),
        filteredNotices() {
            return this.noticeList.filter(notice => {
                return (
                    notice.notice_title
                        .toLowerCase()
                        .includes(this.keyword.toLowerCase()) ||
                    notice.notice_content
                        .toLowerCase()
                        .includes(this.keyword.toLowerCase()) ||
                    notice.notice_title
                        .toLowerCase()
                        .includes(this.keywordIME.toLowerCase()) ||
                    notice.notice_content
                        .toLowerCase()
                        .includes(this.keywordIME.toLowerCase())
                );
            });
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                const result = await this.$http
                    .get(`/api/notices`, {params : {
                        offset: this.offset,
                        limit: this.limit
                    }})
                    .then(response => {
                        return response.data;
                    })
                    .then(data => {
                        return data.map(notice => ({
                            ...notice,
                            ...{ checked: false }
                        }));
                    });
                this.noticeList = [
                    ...new Map(
                        this.noticeList
                            .concat(result)
                            .map(item => [item["notice_id"], item])
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
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD HH:mm");
        },
        async scrollAtBottom() {
            if (!this.isScrollAtBottom) {
                this.isScrollAtBottom = true;
                this.fetchData();
            }
        }
    }
};
</script>

<style lang="scss">
</style>
