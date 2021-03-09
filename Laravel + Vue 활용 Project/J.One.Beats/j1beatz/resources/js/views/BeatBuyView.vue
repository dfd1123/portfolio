<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section
            id="beat-buy"
            class="section sub-section"
        >
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
                        <li class="sub-page-cart-procedure-list">
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
                        <li class="sub-page-cart-procedure-list active">
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
                    <div class="sub-page-cart-02">
                        <div class="sub-page-cart-contents-inner">
                            <input
                                id="check-order-beat-order_list"
                                type="checkbox"
                                class="none-input"
                                checked
                            >
                            <input
                                id="check-order-beat-pay_type"
                                type="checkbox"
                                class="none-input"
                                checked
                            >

                            <div class="sub-page-order-ing">
                                <div class="sub-page-order-ing-tab _order_list">
                                    <label for="check-order-beat-order_list">
                                        <span class="in-title">
                                            <span class="in-title-name">주문 비트 리스트</span>
                                        </span>
                                    </label>
                                </div>

                                <beat-select-list-component
                                    class="sub-page-order-ing-tab-view tab-view-order_list"
                                    height="24.5em"
                                    :beats="buyBeatList"
                                    :show-select-button="false"
                                    :show-delete-button="false"
                                />


                                <div class="sub-page-order-ing-tab _pay_type">
                                    <label for="check-order-beat-pay_type">
                                        <span class="in-title">
                                            <span class="in-title-name">결제 수단</span>
                                        </span>
                                    </label>
                                </div>

                                <div class="sub-page-order-ing-tab-view tab-view-pay_type">
                                    <div class="tab-view-pay_type-group">
                                        <input
                                            id="check-pay_type-1"
                                            v-model="pgType"
                                            type="radio"
                                            name="pay_type"
                                            class="none-input"
                                            value="0"
                                            :disabled="isPerchaceStarted"
                                        >
                                        <input
                                            id="check-pay_type-2"
                                            v-model="pgType"
                                            type="radio"
                                            name="pay_type"
                                            class="none-input"
                                            value="1"
                                            :disabled="isPerchaceStarted"
                                        >
                                        <input
                                            id="check-pay_type-3"
                                            v-model="pgType"
                                            type="radio"
                                            name="pay_type"
                                            class="none-input"
                                            value="2"
                                            :disabled="isPerchaceStarted"
                                        >
                                        <ul>
                                            <li class="tab-view-pay_type-list tab-view-pay_type-list-1">
                                                <label for="check-pay_type-1">
                                                    <span class="_icon" />
                                                    <span class="_label">신용카드 결제</span>
                                                </label>
                                            </li>
                                            <li class="tab-view-pay_type-list tab-view-pay_type-list-2">
                                                <label for="check-pay_type-2">
                                                    <span class="_icon" />
                                                    <span class="_label">가상계좌 결제</span>
                                                </label>
                                            </li>
                                            <li class="tab-view-pay_type-list tab-view-pay_type-list-3">
                                                <label for="check-pay_type-3">
                                                    <span class="_icon" />
                                                    <span class="_label">휴대폰 결제</span>
                                                </label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

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
                                    @click="$router.push('/beat-cart')"
                                >
                                    뒤로가기
                                </button>
                                <button
                                    type="button"
                                    class="btn"
                                    @click="buySelectedBeats()"
                                >
                                    {{ isPerchaceStarted ? '결제 완료' : '선택비트 주문' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

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
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import BeatSelectListComponent from "../components/common/BeatSelectListComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (
            Array.isArray(to.params.buyBeats) &&
            to.params.buyBeats.length > 0
        ) {
            next();
        } else {
            next("/main");
        }
    },
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
        BeatSelectListComponent
    },
    data() {
        return {
            isLicensePopupVisible: false,
            isNoticePopupVisible: false,
            isPerchaceStarted: false,
            noticePopupMessage: "",
            buyBeats: this.$route.params.buyBeats,
            buyBeatList: [],
            pgType: null
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user"
        }),
        isPgTypeSelected() {
            return this.pgType ? true : false;
        },
        totalAmount() {
            if (this.buyBeats.length > 0) {
                return this.buyBeats.reduce((total, beat) => {
                    return (total += beat.beat_price);
                }, 0);
            }

            return 0;
        }
    },
    mounted() {
        this.buyBeatList = this.buyBeats;
    },
    methods: {
        async buySelectedBeats() {
            if (!this.isUser) {
                return;
            }

            if (this.isPerchaceStarted) {
                return this.$router.push({
                    name: "beat-buy-result",
                    params: {
                        buyBeats: this.buyBeats.map(beat => ({ ...beat })),
                        pgType: this.pgType
                    }
                });
            }

            if (!this.isPgTypeSelected) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "결제 수단을 선택해주시기 바랍니다";
                return;
            }

            // 구매 절차 진행
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                const res = await this.$http.post("/api/payletter", {
                    action: "store",
                    req: "beat",
                    cart_ids: this.buyBeats.map(beat => beat.cart_id),
                    po_pg_type: this.pgType
                });

                window.open(
                    JSON.parse(res.data).online_url,
                    "_blank",
                    "width=#, height=#"
                );
                this.isPerchaceStarted = true;
            } catch (e) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "이미 결제 대기중인 건이 있거나 <br> 결제 중 오류가 발생했습니다";
                this.isPerchaceStarted = false;
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
#beat-buy {
    .sub-page-order-ing-tab._pay_type:before {
        content: "2";
    }

    #check-order-beat-order_list:checked ~ .sub-page-order-ing .tab-view-order_list {
        overflow-y: hidden;
    }
}
</style>
