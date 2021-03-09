<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <!--TODO:이용권정보, 장바구니, 고객센터, 마이페이지일 때 아래 제목스타일 추가-->
            <div class="sub-page-large-title-group">
                <div class="sub-page-large-title _cart">
                    <h2 class="sub-page-large-title-name">
                        장바구니
                    </h2>
                </div>
                <div class="sub-page-cart-procedure">
                    <ul>
                        <li class="sub-page-cart-procedure-list active">
                            <img
                                src="img/icon/cart-none.svg"
                                alt="cart none"
                                class="no-active"
                            >
                            <img
                                src="img/icon/cart-active.svg"
                                alt="cart none"
                                class="active"
                            >
                            <span>장바구니</span>
                        </li>
                        <li class="sub-page-cart-procedure-list">
                            <img
                                src="img/icon/paygo-none.svg"
                                alt="cart none"
                                class="no-active"
                            >
                            <img
                                src="img/icon/paygo-active.svg"
                                alt="cart none"
                                class="active"
                            >
                            <span>결제진행</span>
                        </li>
                        <li class="sub-page-cart-procedure-list">
                            <img
                                src="img/icon/paycomplt-none.svg"
                                alt="cart none"
                                class="no-active"
                            >
                            <img
                                src="img/icon/paycomplt-active.svg"
                                alt="cart none"
                                class="active"
                            >
                            <span>결제완료</span>
                        </li>
                    </ul>
                </div>
            </div>
            <!--TODO:END-->

            <div class="page-contents-wrapper">
                <div class="sub-page-cart">
                    <div class="sub-page-cart-01">
                        <div class="sub-page-cart-contents-inner">
                            <beat-select-list-component
                                title="주문 비트 리스트"
                                :beats="beatSelectList"
                                @selectedRowChange="selectedRowChange"
                                @rowRemove="rowRemove"
                            />

                            <!-- FIXME:사전고지사항 추가(체크해야지 주문할 수 있습니다) -->
                            <div class="buy_agree">
                                <input
                                    id="buy_agree_check"
                                    v-model="isAgree1"
                                    type="checkbox"
                                    class="input-style-01"
                                >
                                <label for="buy_agree_check" />
                                <label
                                    for="buy_agree_check"
                                    class="buy_agree_ment"
                                >비트는 상품특성상 <i>취소 및 환불은 불가</i>합니다. 구매에 동의하시겠습니까? <br><em>(이용권을 취소하시려면 마이페이지 > 이용권 관리 에서 해지해주세요.)</em></label>
                            </div>
                            <!-- FIXME:END -->

                            <div class="sub-page-cart-price_info">
                                <span class="price-total-pay">
                                    <em>총 결제금액</em>
                                    <b>{{ Number(totalAmount).toLocaleString() }}</b><span>원</span>
                                </span>
                            </div>

                            <div class="sub-page-cart-btn-group">
                                <button
                                    type="button"
                                    class="btn btn-outline"
                                    @click="removeSelectedBeats"
                                >
                                    선택비트 삭제
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-outline"
                                    @click="$router.push('/main')"
                                >
                                    쇼핑 계속하기
                                </button>
                                <button
                                    type="button"
                                    class="btn"
                                    @click="buySelectedBeats"
                                >
                                    선택비트 주문
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <confirm-popup-component
            title-text="확인"
            :visible.sync="isConfirmPopupVisible"
            :right-button-text="confirmPopupRightText"
            @rightButtonClick="confirmAction"
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

        <down-info-popup
            :visible.sync="isDownPopupVisible"
            @agreeclick="nextStep"
        />
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";
import LicenseInfoPopup from "../components/common/LicenseInfoPopup";
import BeatSelectListComponent from "../components/common/BeatSelectListComponent";
import DownInfoPopup from "../components/common/DownInfoPopup";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
        ConfirmPopupComponent,
        LicenseInfoPopup,
        BeatSelectListComponent,
        DownInfoPopup,
    },
    data() {
        return {
            isLicensePopupVisible: false,
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            isAgree1: false,
            confirmPopupMessage: "",
            confirmPopupRightText: "",
            confirmAction: () => {},
            noticePopupMessage: "",
            licenseType: this.$route.params.licenseType,
            selectedBeats: [],
            beatSelectList: [],
            isDownPopupVisible: false,
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user"
        }),
        totalAmount() {
            if (this.selectedBeats.length > 0) {
                return this.selectedBeats.reduce((total, beat) => {
                    return (total += beat.beat_price);
                }, 0);
            }

            return 0;
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                this.beatSelectList = await this.$http
                    .get(`/api/carts`, {
                        params: {
                            req: "cart"
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
        },
        buySelectedBeats() {
            if (!this.isUser) {
                return;
            }

            // 선택된 곡이 있는지 없는지 체크 등등
            if (this.selectedBeats.length === 0) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "선택된 곡이 없습니다";
                return;
            }

            if (!this.isAgree1) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "비트 구매 안내사항에 동의하셔야 합니다";
                return;
            }
            this.isDownPopupVisible = true;
        },
        nextStep(){
            this.$router.push({
                name: "beat-buy",
                params: { buyBeats: this.selectedBeats.slice(0) }
            });
        },
        removeSelectedBeats() {
            if (!this.isUser) {
                return;
            }

            // 선택된 곡이 있는지 없는지 체크 등등
            if (this.selectedBeats.length === 0) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "선택된 곡이 없습니다";
                return;
            }

            // 선택된 곡 물어보고 삭제
            this.confirmPopupMessage =
                "정말로 선택된 비트를 <br> 장바구니에서 <br> 삭제하시겠습니까?";
            this.confirmPopupRightText = "선택비트 삭제";
            this.confirmAction = async () => {
                try {
                    this.$store.commit("updateIsGlobalLoading", true);

                    await this.$http.patch("/api/carts/collection", {
                        action: "destroy",
                        cart_ids: this.selectedBeats.map(beat => beat.cart_id)
                    });
                    this.$store.commit("updateCartlist", []);
                    await this.fetchData();

                    this.isNoticePopupVisible = true;
                    this.noticePopupMessage =
                        "선택된 비트를 장바구니에서 삭제했습니다";
                } catch (e) {
                    this.isNoticePopupVisible = true;
                    this.noticePopupMessage =
                        "선택된 비트를 장바구니에서 삭제하는 중 오류가 발생했습니다";
                    console.log(e);
                } finally {
                    this.$store.commit("updateIsGlobalLoading", false);
                }
            };
            this.isConfirmPopupVisible = true;
        },
        selectedRowChange(selectedBeats) {
            this.selectedBeats = selectedBeats;
        },
        async rowRemove(beat) {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.delete(`/api/carts/${beat.cart_id}`);
                this.$store.commit("updateCartlist", []);
                await this.fetchData();
            } catch (e) {
                console.log(e);
            } finally {
                this.confirmAction = () => {};
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
</style>
