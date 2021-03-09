<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:-->
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <service-tab-component />

            <div class="page-contents-wrapper">
                <div class="sub-page-cs">
                    <section class="sub-page-cs-01">
                        <h3 class="none">
                            FAQ
                        </h3>

                        <div class="cs-board">
                            <div
                                v-for="(faq, index) in faqList"
                                :key="faq.faq_id"
                                class="cs-board-tab"
                            >
                                <input
                                    :id="`check-notice-tab-${faqList.length - index}`"
                                    type="checkbox"
                                    name="check-notice-tab"
                                    class="none-input"
                                >

                                <label
                                    :for="`check-notice-tab-${faqList.length - index}`"
                                    class="_label"
                                >
                                    <span class="_num">{{ faqList.length - index }}</span>
                                    <span class="_subject">{{ faq.faq_question }}</span>
                                </label>

                                <div class="cs-board-tab-view">
                                    <p class="_contents">
                                        {{ faq.faq_answer }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>
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

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        ServiceTabComponent,
        NoticePopupComponent,
        LicenseInfoPopup
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isLicensePopupVisible: false,
            lcens_name: "",
            noticePopupText: "",
            faqList: []
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user"
        })
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                this.faqList = await this.$http
                    .get(`/api/faqs`)
                    .then(response => {
                        return response.data;
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
        terminationLicense() {
            this.noticePopupText = "준비중입니다";
            this.isNoticePopupVisible = true;
        }
    }
};
</script>

<style lang="scss">
</style>
