<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section
            id="license-buy-result"
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
                        <li class="sub-page-cart-procedure-list active">
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
                    <div class="sub-page-cart-03">
                        <div class="sub-page-cart-contents-inner">
                            <div class="certify-good">
                                <img
                                    src="img/icon/certify-good.svg"
                                    alt="certify complete"
                                >
                                <p><b>결제가 완료되었습니다.</b></p>

                                <span>구매하신 이용권은 <b>마이페이지>이용권관리</b>에서 확인하실 수 있습니다.</span>
                            </div>

                            <div class="in-title">
                                <h3 class="in-title-name">
                                    결제 정보
                                </h3>
                            </div>

                            <div class="sub-page-cart-complt">
                                <table>
                                    <tr>
                                        <th>결제수단</th>
                                        <td>{{ pgTypeName }}</td>
                                    </tr>
                                    <template v-if="licensePgType === 1">
                                        <tr>
                                            <th>입금하실 은행</th>
                                            <td>국민은행</td>
                                        </tr>
                                        <tr>
                                            <th>입금주 성명</th>
                                            <td>김성명</td>
                                        </tr>
                                        <tr>
                                            <th>무통장 입금 계좌번호</th>
                                            <td>574402-01-257836 (제이원비츠)</td>
                                        </tr>
                                        <tr>
                                            <th>마감시간</th>
                                            <td>2019. 00. 00  00:00까지</td>
                                        </tr>
                                    </template>
                                </table>
                                <p class="caution">
                                    결제수단이 '무통장 입금'일 경우, 입금기한내에 입금처리가 되어야 주문접수가 완료되며 기간내 입금을 하지 않는 경우, 주문이 취소됩니다.<br>입금 후, 결제 승인이 완료되면 이용권이 완료시간부터 즉시 적용됩니다.
                                </p>
                            </div>

                            <div class="in-title">
                                <h3 class="in-title-name">
                                    구매한 이용권
                                </h3>
                            </div>

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
                                        <tr>
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
                                                        >{{ orderedLicense.lcens_name }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="td-price">
                                                <div><b>{{ Number(orderedLicense.lcens_price).toLocaleString() }}</b><em>원</em></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import LicenseInfoPopup from "../components/common/LicenseInfoPopup";

export default {
    beforeRouteEnter(to, from, next) {
        if (
            Number.isInteger(Number(to.params.lcens_type)) ||
            Number.isInteger(Number(to.params.lo_pg_type))
        ) {
            next();
        } else {
            next("/main");
        }
    },
    components: {
        HeaderComponent,
        FooterComponent,
        LicenseInfoPopup
    },
    data() {
        return {
            isLicensePopupVisible: false,
            licenseType: Number(this.$route.params.lcens_type),
            licensePgType: Number(this.$route.params.lo_pg_type),
            orderedLicense: {}
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user"
        }),
        pgTypeName() {
            if (this.licensePgType === 0) {
                return "신용카드 결제";
            }

            if (this.licensePgType === 1) {
                return "무통장 입금";
            }

            if (this.licensePgType === 2) {
                return "휴대폰 결제";
            }

            return "";
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

                this.orderedLicense = this.licenses.find(license => {
                    return license.lcens_type === this.licenseType;
                });

                if (!this.orderedLicense) {
                    alert("오류발생");
                    return this.$router.push("/license-info", () => {});
                }
            } catch (e) {
                console.log(e);
                this.$router.push("/license-info", () => {});
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        buyLicense() {
            if (!this.isUser) {
                return;
            }

            // 구매 절차 진행
            console.log(this.licenseType);
            const success = false;
            if (success) {
                // 구매완료 화면
            } else {
                // 구매실패 팝업
            }
        }
    }
};
</script>

<style lang="scss">
    #license-buy-result {
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
