<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:-->
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <!--TODO:이용권정보, 장바구니, 고객센터, 마이페이지일 때 아래 제목스타일 추가-->
            <div class="sub-page-large-title-group">
                <div class="sub-page-large-title _cart">
                    <h2 class="sub-page-large-title-name">
                        이용권 정보
                    </h2>
                </div>
            </div>
            <!--TODO:END-->

            <div class="page-contents-wrapper">
                <div class="sub-page-cart">
                    <div class="sub-page-ticket-info">
                        <div class="in-title">
                            <h3 class="in-title-name">
                                자동결제 정기이용권
                            </h3>
                        </div>

                        <div class="ticket-info ticket-info-01">
                            <figure>
                                <figcaption>무제한<br>스트리밍</figcaption>
                            </figure>
                            <dl>
                                <dd class="_ticket_desc">
                                    재생시간 제한없는 스트리밍 서비스
                                </dd>
                                <dt>매월 3,600원</dt>
                                <dd class="_info_detail">
                                    - 보이스 태그 포함 재생
                                </dd>
                                <dd class="_info_detail">
                                    - 다운로드 불가
                                </dd>
                                <dd class="_info_detail">
                                    - 개인사용 제한
                                </dd>
                            </dl>
                            <button
                                type="button"
                                class="btn"
                                :disabled="isUser && user.license.lcens_type === 1 "
                                @click="buyLicense(1)"
                            >
                                {{ isUser && user.license.lcens_type === 1 ? '이용중' : '구매하기' }}
                            </button>
                        </div>

                        <div class="in-title">
                            <h3 class="in-title-name">
                                비트 구매
                            </h3>
                        </div>

                        <div class="ticket-info ticket-info-02">
                            <figure>
                                <figcaption>deluxe</figcaption>
                            </figure>
                            <dl>
                                <dd class="_ticket_desc">
                                    다운로드 가능한 음원구매 이용권
                                </dd>
                                <dt>한 곡당 20,000원 <em>부터</em></dt>
                                <dd class="_info_detail">
                                    - wav 파일 제공
                                </dd>
                                <dd class="_info_detail">
                                    - 보이스 태그 제거
                                </dd>
                                <dd class="_info_detail">
                                    - 음원발매, 공연 등 수익활동 가능
                                </dd>
                                <dd class="_info_detail">
                                    - 리믹스 및 편곡 불가능
                                </dd>
                            </dl>
                        </div>

                        <div class="in-title">
                            <h3 class="in-title-name">
                                음원이용범위
                            </h3>
                        </div>

                        <div class="ticket-info-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th />
                                        <th>FREE</th>
                                        <th>deluxe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>가격</td>
                                        <td>무료</td>
                                        <td>최소 20,000원 ~</td>
                                    </tr>
                                    <tr>
                                        <td>이용기간</td>
                                        <td>제한 없음</td>
                                        <td>제한 없음</td>
                                    </tr>
                                    <tr>
                                        <td>제공파일</td>
                                        <td>mp3</td>
                                        <td>wav</td>
                                    </tr>
                                    <tr>
                                        <td>보이스 태그</td>
                                        <td>O</td>
                                        <td>X</td>
                                    </tr>
                                    <tr>
                                        <td>온라인 배포(공유)</td>
                                        <td>O</td>
                                        <td>O</td>
                                    </tr>
                                    <tr>
                                        <td>2차적 저작물 작성</td>
                                        <td>O</td>
                                        <td>O</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            온라인 배포를 통한
                                            <br>수익창출
                                        </td>
                                        <td>X</td>
                                        <td>O</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            음원을 이용한
                                            <br>수익성 음반 발매
                                        </td>
                                        <td>X</td>
                                        <td>O</td>
                                    </tr>
                                    <tr>
                                        <td>수익성 공연</td>
                                        <td>X</td>
                                        <td>O</td>
                                    </tr>
                                    <tr>
                                        <td>수익성 방송</td>
                                        <td>X</td>
                                        <td>O</td>
                                    </tr>
                                    <tr>
                                        <td>리믹스 및 편곡</td>
                                        <td>X</td>
                                        <td>X</td>
                                    </tr>
                                    <tr>
                                        <td>독점사용</td>
                                        <td>
                                            X
                                            <br>(플랫폼 회원에게 지속 판매 가능)
                                        </td>
                                        <td>
                                            X
                                            <br>(플랫폼 회원에게 지속 판매 가능)
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

        <confirm-popup-component
            title-text="알림"
            :visible.sync="isConfirmPopupVisible"
            right-button-text="로그인 하기"
            @rightButtonClick="$router.push('/login')"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="`<b>로그인</b>이 필요한 서비스입니다.<br>로그인 하시겠습니까?`"
            />
        </confirm-popup-component>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        ConfirmPopupComponent
    },
    data() {
        return {
            isConfirmPopupVisible: false
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user"
        })
    },
    methods: {
        buyLicense(type) {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            this.$router.push({ path: "/license-buy", query: { lcens_type: type } });
        }
    }
};
</script>

<style lang="scss">
.ticket-info-02 dl dt em{
    font-size:0.7em;
    color:#7e2aff;
}
</style>
