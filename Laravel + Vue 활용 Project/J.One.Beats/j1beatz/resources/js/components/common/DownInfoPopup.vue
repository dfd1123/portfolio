<template>
    <div v-show="visible">
        <!-- 팝업 레이어 -->
        <div class="black-overlay" style="display: initial" />
        <div class="popup-layer">
            <!-- 이용권 정보 팝업 -->
            <div class="popup-ticketinfo popup-02">
                <h4 class="popup-layer-title _another">이용권 정보</h4>
                <div class="popup-layer-txt">
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
                                        <br />수익창출
                                    </td>
                                    <td>X</td>
                                    <td>O</td>
                                </tr>
                                <tr>
                                    <td>
                                        음원을 이용한
                                        <br />수익성 음반 발매
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
                                        <br />(플랫폼 회원에게 지속 판매 가능)
                                    </td>
                                    <td>
                                        X
                                        <br />(플랫폼 회원에게 지속 판매 가능)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-1em">
                            <button
                                class="btn agree-btn"
                                ref="button"
                                type="button"
                                @click="agreeclick"
                            >확인하였습니다</button>
                        </div>
                    </div>
                </div>
                <button
                    ref="button"
                    type="button"
                    class="popup-layer-close-btn"
                    @click="closeButtonClick"
                >
                    <span class="none">팝업 닫기버튼</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
    props: {
        visible: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {};
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user"
        })
    },
    watch: {
        visible() {
            if (this.visible) {
                this.$nextTick(() => {
                    this.$refs.button.focus();
                    this.$refs.button.blur();
                });
            }
        }
    },
    methods: {
        closeButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("closeButtonClick");
        },
        agreeclick() {
            this.$emit("update:visible", false);
            this.$emit("agreeclick");
        }
    }
};
</script>

<style lang="scss">
.mt-1em {
    margin-top: 1em;
}
.agree-btn {
    border-radius: 30px;
    line-height: 45px;
    height: 45px;
    background-color: #7e2aff;
    color: #fff;
    font-size: 0.95rem;
    margin-bottom: 0;
}
</style>
