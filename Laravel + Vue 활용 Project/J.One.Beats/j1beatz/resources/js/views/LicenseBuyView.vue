<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section
            id="license-buy"
            class="section sub-section"
        >
            <header-component ver="bg_gray_ver" />

            <!--TODO:이용권정보, 장바구니, 고객센터, 마이페이지일 때 아래 제목스타일 추가-->
            <div class="sub-page-large-title-group">
                <div class="sub-page-large-title _cart">
                    <h2 class="sub-page-large-title-name">
                        이용권 구매
                    </h2>
                </div>
                <div class="sub-page-cart-procedure">
                    <ul>
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
                                checked="checked"
                            >
                            <input
                                id="check-order-beat-pay_type"
                                type="checkbox"
                                class="none-input"
                                checked="checked"
                            >

                            <div class="sub-page-order-ing">
                                <div class="sub-page-order-ing-tab _order_list">
                                    <label for="check-order-beat-order_list">
                                        <span class="in-title">
                                            <span class="in-title-name">구매 이용권</span>
                                        </span>
                                    </label>
                                </div>

                                <div
                                    class="sub-page-order-ing-tab-view tab-view-order_list"
                                    data-mcs-theme="minimal-dark"
                                >
                                    <div class="cart_chart_list">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="th-license">
                                                        <div>라이센스</div>
                                                    </th>
                                                    <th class="th-price">
                                                        <div>가격</div>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>

                                        <table>
                                            <tbody>
                                                <tr v-if="selectedLicense.lcens_type === 1">
                                                    <td class="td-license">
                                                        <div class="">
                                                            <span
                                                                class="thumb-mini"
                                                                style="background-image: url(/img/imgs/img_ticket_basic.jpg);"
                                                            ><span class="none">썸네일</span></span>
                                                            <div class="td-license">
                                                                <span
                                                                    style="cursor: pointer"
                                                                    @click="isLicensePopupVisible = true"
                                                                >{{ selectedLicense.lcens_name }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="td-price">
                                                        <div><b>{{ Number(selectedLicense.lcens_price).toLocaleString() }}</b><em>원</em></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

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
                                        <!--
                                        <input
                                            id="check-pay_type-2"
                                            v-model="pgType"
                                            type="radio"
                                            name="pay_type"
                                            class="none-input"
                                            value="1"
                                            :disabled="isPerchaceStarted"
                                        >
                                        -->
                                        <!--
                                        <input
                                            id="check-pay_type-3"
                                            v-model="pgType"
                                            type="radio"
                                            name="pay_type"
                                            class="none-input"
                                            value="2"
                                            :disabled="isPerchaceStarted"
                                        >
                                        -->
                                        <ul>
                                            <li class="tab-view-pay_type-list tab-view-pay_type-list-1">
                                                <label for="check-pay_type-1">
                                                    <span class="_icon" />
                                                    <span class="_label">신용카드 결제</span>
                                                </label>
                                            </li>
                                            <!--
                                            <li class="tab-view-pay_type-list tab-view-pay_type-list-2">
                                                <label for="check-pay_type-2">
                                                    <span class="_icon" />
                                                    <span class="_label">무통장 입금</span>
                                                </label>
                                            </li>
                                            -->
                                            <!--
                                            <li class="tab-view-pay_type-list tab-view-pay_type-list-3">
                                                <label for="check-pay_type-3">
                                                    <span class="_icon" />
                                                    <span class="_label">휴대폰 결제</span>
                                                </label>
                                            </li>
                                            -->
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="buy_agree">
                                <div class="agree_list">
                                    <input
                                        id="license_buy_agree_check01"
                                        v-model="agree1"
                                        type="checkbox"
                                        class="input-style-01"
                                        :disabled="isPerchaceStarted"
                                    >
                                    <label for="license_buy_agree_check01" />
                                    <label
                                        for="license_buy_agree_check01"
                                        class="buy_agree_ment"
                                    ><i>30일마다 자동결제</i>되는 것에 동의합니다.</label>
                                </div>
                                <div class="agree_list">
                                    <input
                                        id="license_buy_agree_check02"
                                        v-model="agree2"
                                        type="checkbox"
                                        class="input-style-01"
                                        :disabled="isPerchaceStarted"
                                    >
                                    <label for="license_buy_agree_check02" />
                                    <label
                                        for="license_buy_agree_check02"
                                        class="buy_agree_ment"
                                    ><i>상기 결제 내용 및 하단 안내 내용</i>을 확인하였습니다.</label>
                                </div>
                                <div class="agree_list">
                                    <input
                                        id="license_buy_agree_check03"
                                        v-model="agree3"
                                        type="checkbox"
                                        class="input-style-01"
                                        :disabled="isPerchaceStarted"
                                    >
                                    <label for="license_buy_agree_check03" />
                                    <label
                                        for="license_buy_agree_check03"
                                        class="buy_agree_ment"
                                    >이용약관을 확인하였습니다.</label>
                                    <em
                                        class="license_buy_agree_check03_view"
                                        @click="showTerm('pay_terms_service')"
                                    >이용약관 보기</em>
                                </div>
                            </div>

                            <div class="sub-page-cart-price_info">
                                <span class="price-total-pay">
                                    <em>총 결제금액</em>
                                    <b>{{ Number(selectedLicense.lcens_price).toLocaleString() }}</b><span>원</span>
                                </span>
                            </div>

                            <div class="sub-page-cart-btn-group">
                                <button
                                    type="button"
                                    class="btn btn-outline"
                                    @click="$router.push('/license-info', () => {})"
                                >
                                    뒤로가기
                                </button>
                                <button
                                    type="button"
                                    class="btn"
                                    @click="buyLicense"
                                >
                                    {{ isPerchaceStarted ? '결제 완료' : '이용권 주문' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <license-info-popup
            :visible.sync="isLicensePopupVisible"
        />

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

        <terms-and-conditions-info-popup
            :visible.sync="isTacPopupVisible"
        >
            <!-- eslint-disable-next-line vue/no-v-html -->
            <div v-html="termText" />
        </terms-and-conditions-info-popup>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import LicenseInfoPopup from "../components/common/LicenseInfoPopup";
import TermsAndConditionsInfoPopup from "../components/common/TermsAndConditionsInfoPopup";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
        LicenseInfoPopup,
        TermsAndConditionsInfoPopup
    },
    data() {
        return {
            isLicensePopupVisible: false,
            isNoticePopupVisible: false,
            isTacPopupVisible: false,
            isPerchaceStarted: false,
            noticePopupMessage: "",
            licenseType: Number(this.$route.query.lcens_type),
            licenses: [],
            selectedLicense: {},
            pgType: null,
            agree1: false,
            agree2: false,
            agree3: false,
            terms: {},
            termText: ""
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
        isAgreeAll() {
            return this.agree1 && this.agree2 && this.agree3;
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                this.licenses = await this.$http
                    .get(`/api/licenses`)
                    .then(response => {
                        return response.data;
                    });

                this.selectedLicense = this.licenses.find(license => {
                    return license.lcens_type === this.licenseType;
                });

                if (!this.selectedLicense) {
                    alert("오류발생");
                    this.$router.push("/license-info", () => {});
                } else {
                    this.terms = await this.$http
                        .get(`/api/terms`)
                        .then(response => response.data[0]);
                }
            } catch (e) {
                console.log(e);
                this.$router.push("/license-info", () => {});
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async buyLicense() {
            if (!this.isUser) {
                return;
            }

            if (this.isPerchaceStarted) {
                return this.$router.push({
                    name: "license-buy-result",
                    params: {
                        lcens_type: this.licenseType,
                        lo_pg_type: this.pgType
                    }
                });
            }

            if (!this.isPgTypeSelected) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "결제 수단을 선택해주시기 바랍니다";
                return;
            }

            if (!this.isAgreeAll) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "모든 필수 동의 및 확인사항에 <br> 동의하셔야 합니다";
                return;
            }

            // 구매 절차 진행
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                const res = await this.$http.post("/api/payletter", {
                    action: "store",
                    req: "license",
                    lcens_id: this.selectedLicense.lcens_id,
                    lo_pg_type: this.pgType
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
        },
        showTerm(termName) {
            this.termText = this.terms[termName];
            this.isTacPopupVisible = true;
        }
    }
};
</script>

<style lang="scss">
    #license-buy {
        .sub-page-cart-procedure-list:nth-child(2)::after {
            content: none;
        }

        .sub-page-order-ing-tab._pay_type:before {
            content: "2";
        }

        .cart_chart_list table td.td-license span {
            opacity: 1;
        }

        #check-order-beat-order_list:checked ~ .sub-page-order-ing .tab-view-order_list {
            height: 12.25em;
            max-height: 12.25em;
            overflow-y: auto;
        }
    }
</style>
