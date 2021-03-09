<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:-->
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <my-page-tab-component />

            <div class="page-contents-wrapper">
                <div class="sub-page-mypage">
                    <div class="sub-page-mypage-01">
                        <div class="sub-page-mypage-contents-inner">
                            <div class="sub-page-ticket-info01">
                                <p class="sub-page-ticket-info01-name">
                                    <b>{{ user.user_nick }}</b>님의 현재 이용권
                                </p>
                                <p
                                    v-if="!user.license.lcens_type"
                                    class="sub-page-ticket-info01-status"
                                >
                                    사용중인 이용권이 없습니다.
                                </p>
                                <p
                                    v-else
                                    class="sub-page-ticket-info01-status"
                                >
                                    {{ user.license.lcens_name }}
                                </p>
                            </div>

                            <div class="in-title">
                                <h3 class="in-title-name">
                                    자동결제 정기 이용권
                                </h3>
                                <span
                                    v-if="user.license.lo_pg_type === 0"
                                    class="in-title-right"
                                    @click="toggleLicense"
                                >{{ user.license.autopay === 1 ? '결제해지' : '정기결제' }}</span>
                                <span
                                    v-else-if="user.license.lo_pg_type === 3"
                                    class="in-title-right"
                                >1개월 무료이용중</span>
                            </div>

                            <div class="sub-page-ticket-info02">
                                <div
                                    v-if="isUser && !user.license.lcens_type"
                                    class="ticket-box-nothing ticket-box"
                                >
                                    <button
                                        type="button"
                                        class="btn btn-01"
                                        @click="$router.push('/license-info')"
                                    >
                                        이용권 구매하기
                                    </button>
                                </div>

                                <div
                                    v-if="isUser && user.license.lcens_type === 1"
                                    class="ticket-box-yes ticket-box"
                                >
                                    <div class="ticket-box-name_data_group">
                                        <span class="ticket-box-name">무제한 스트리밍</span>

                                        <ul class="ticket-box-data-group">
                                            <li class="ticket-box-data_list">
                                                <span class="_label">구매일자</span>
                                                <span class="_data">{{ toMoment(user.license.reg_at) }}</span>
                                            </li>
                                            <li class="ticket-box-data_list">
                                                <span class="_label">이용기간</span>
                                                <span class="_data">{{ toMoment(user.license.end_at) }}</span>
                                            </li>

                                            <li class="ticket-box-data_list">
                                                <span class="_label">결제설정</span>
                                                <span class="_data">{{ user.license.autopay === 0 ? '결제해지' : '정기결제' }}</span>
                                            </li>
                                        </ul>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn"
                                        @click="isLicensePopupVisible = true"
                                    >
                                        이용권 정보
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <confirm-popup-component
            title-text="알림"
            :visible.sync="isConfirmPopupVisible"
            right-button-text="확인"
            @rightButtonClick="updateAutopay"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="confirmPopupMessage"
            />
        </confirm-popup-component>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isNoticePopupVisible"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="noticePopupMessage"
            />
        </notice-popup-component>

        <license-info-popup
            :visible.sync="isLicensePopupVisible"
        />
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import MyPageTabComponent from "../components/my-page/MyPageTabComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";
import LicenseInfoPopup from "../components/common/LicenseInfoPopup";
import moment from "moment";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        MyPageTabComponent,
        ConfirmPopupComponent,
        LicenseInfoPopup
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            isLicensePopupVisible: false,
            noticePopupMessage: "",
            confirmPopupMessage: "",
            lcens_name: ""
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user"
        })
    },
    methods: {
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD HH:mm");
        },
        toggleLicense() {
            if (this.user.license.autopay === 0) {
                this.confirmPopupMessage =
                    "결제해지중인 이용권을 다시 <br> 정기결제 하시겠습니까?";
                this.isConfirmPopupVisible = true;
            } else if (this.user.license.autopay === 1) {
                this.confirmPopupMessage =
                    "가입하신 이용권을 해지하시겠습니까? <br> 이미 결제하신 이용권은 <br> 당월까지만 이용가능하며, <br> 익월분부터 정기결제가 해지됩니다.";
                this.isConfirmPopupVisible = true;
            }
        },
        async updateAutopay() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.put(
                    `/api/license_orders/${this.user.license.lo_id}`,
                    {
                        autopay: this.user.license.autopay === 0 ? 1 : 0
                    }
                );

                const { user, producer } = await this.$http
                    .get("/api/info")
                    .then(response => response.data);

                this.$store.commit("updateUser", user);
                this.$store.commit("updateProducer", producer);

                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "변경이 완료되었습니다";
            } catch (e) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "변경 중 오류가 발생했습니다";
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
</style>
