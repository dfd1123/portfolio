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
                                <p><b>결제요청이 완료되었습니다.</b></p>

                                <span>주문하신 비트는 <b>마이페이지>주문내역</b>에서 확인하실 수 있습니다.</span>
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
                                    <template v-if="pgType === 1">
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
                                    결제수단이 '무통장 입금'일 경우, 입금기한내에 입금처리가 되어야 주문접수가 완료되며 기간내 입금을 하지 않는 경우, 주문이 취소됩니다.<br>입금 후, 결제 승인이 완료되면 마이페이지 > 주문내역에서 다운로드 할 수 있습니다.
                                </p>
                            </div>

                            <beat-select-list-component
                                height="500"
                                title="주문비트리스트"
                                :beats="buyBeatList"
                                :show-select-button="false"
                                :show-delete-button="false"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
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
        BeatSelectListComponent
    },
    data() {
        return {
            isLicensePopupVisible: false,
            buyBeats: this.$route.params.buyBeats,
            buyBeatList: [],
            pgType: Number(this.$route.params.pgType)
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user"
        }),
        pgTypeName() {
            if (this.pgType === 0) {
                return "신용카드 결제";
            }

            if (this.pgType === 1) {
                return "무통장 입금";
            }

            if (this.pgType === 2) {
                return "휴대폰 결제";
            }

            return "";
        }
    },
    mounted() {
        this.buyBeatList = this.buyBeats;
        this.$store.commit("updateCartlist", []);
    }
};
</script>

<style lang="scss">
#beat-buy {
    .sub-page-order-ing-tab._pay_type:before {
        content: "2";
    }
}
</style>
