<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:-->
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <service-tab-component />

            <div class="page-contents-wrapper">
                <div class="sub-page-cs">
                    <section class="sub-page-cs-03">
                        <h3 class="none">
                            1:1문의
                        </h3>

                        <div class="cs-ask-category">
                            <label>제목</label>
                            <input
                                id="qna_title"
                                v-model="qna_title"
                                type="text"
                                class="qna_title"
                            >
                        </div>

                        <div class="cs-ask-contents">
                            <textarea
                                id="qna_content"
                                v-model="qna_content"
                                cols="30"
                                rows="8"
                            />
                        </div>
                        <div class="cs-ask-caution">
                            <p class="_caution-info">
                                문의하신 내용에 대한 원인파악 및 원활한 상담을 위해 이메일, 휴대폰 번호, 문의내용 등을 수집합니다.
                                <br>수집된 개인정보는 문의 내용 처리 및 고객 불만의 해결을 위해 사용되며, 관련 법령에 따라 3년간 보관 후 삭제됩니다.
                            </p>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-complete"
                            @click="registQna()"
                        >
                            문의하기
                        </button>

                        <div class="cs-ask-history">
                            <div class="in-title">
                                <h3 class="in-title-name">
                                    나의 문의내역
                                </h3>
                                <!--
                                <span class="in-title-right in-title-right-del">
                                    <a
                                        href="#"
                                        role="button"
                                    >삭제</a>
                                </span>
                                -->
                            </div>

                            <div class="cs-board">
                                <div class="cs-board-thead">
                                    <span class="_check">
                                        <input
                                            id="check-all-ask"
                                            v-model="isCheckAllChecked"
                                            type="checkbox"
                                            class="input-style-01"
                                            @change="toggleCheckAllQnas"
                                        >
                                        <!--
                                        <label for="check-all-ask">
                                            <span class="none">문의 전체선택</span>
                                        </label>
                                        -->
                                    </span>
                                    <span class="_subject">문의내용</span>
                                    <span class="_data">작성일</span>
                                    <span class="_answer">답변여부</span>
                                </div>

                                <div
                                    v-for="(qna,index) in qnaList"
                                    :key="qna.qna_id"
                                    class="cs-board-tab"
                                >
                                    <input
                                        :id="`check-ask-tab-${index}`"
                                        type="checkbox"
                                        name="check-ask-tab"
                                        class="none-input"
                                    >

                                    <label
                                        :for="`check-ask-tab-${index}`"
                                        class="_label"
                                    >
                                        <span class="_check">
                                            <input
                                                :id="`check-this-ask${index}`"
                                                v-model="qna.checked"
                                                type="checkbox"
                                                class="input-style-01"
                                                @change="qnaCheckChanged"
                                            >
                                            <!--
                                            <label :for="`check-this-ask${index}`">
                                                <span class="none">문의 개별선택</span>
                                            </label>
                                            -->
                                        </span>
                                        <span class="_subject">{{ qna.qna_title }}</span>
                                        <span class="_data">{{ qna.created_at }}</span>
                                        <span
                                            v-if="qna.qna_answer ==''||qna.qna_answer == null"
                                            class="_answer __no"
                                        >대기</span>
                                        <span
                                            v-else
                                            class="_answer __yes"
                                        >완료</span>
                                    </label>

                                    <div class="cs-board-tab-view">
                                        <p class="_contents">
                                            {{ qna.qna_content }}
                                        </p>
                                        <div class="_contents answer-contents">
                                            <p class="answer_paragraph">
                                                {{ qna.qna_answer }}
                                            </p>
                                        </div>
                                    </div>
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
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import ServiceTabComponent from "../components/service-center/ServiceTabComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";
import moment from "moment";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        ServiceTabComponent,
        NoticePopupComponent,
        ConfirmPopupComponent
    },
    data() {
        return {
            isCheckAllChecked: false,
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            confirmPopupRightText: "",
            confirmPopupMessage: "",
            confirmAction: () => {},
            lcens_name: "",
            noticePopupText: "",
            qnaList: [],
            selectedQnas: [],
            qna_title: "",
            qna_content: ""
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user"
        })
    },
    watch: {
        selectedQnas() {
            this.isCheckAllChecked =
                this.qnaList.length !== 0 &&
                this.qnaList.length === this.selectedQnas.length;
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                this.qnaList = await this.$http
                    .get(`/api/qnas`, {
                        params: {
                            user_id: this.user.user_id
                        }
                    })
                    .then(response => {
                        return response.data;
                    })
                    .then(data => {
                        return data.map(qna => ({
                            ...qna,
                            ...{ checked: false }
                        }));
                    });
                this.selectedQnas = [];
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        qnaCheckChanged() {
            this.selectedQnas = this.qnaList.filter(qna => qna.checked);
        },
        toggleCheckAllQnas() {
            this.qnaList.forEach(qna => {
                qna.checked = this.isCheckAllChecked;
            });

            this.selectedQnas = this.qnaList.filter(qna => qna.checked);
        },
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD HH:mm");
        },
        async registQna() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                await this.$http
                    .post(`/api/qnas`, {
                        user_id: this.user.user_id,
                        qna_title: this.qna_title,
                        qna_content: this.qna_content
                    })
                    .then(response => {
                        return response.data;
                    });
                this.noticePopupText = "등록되었습니다";
                this.isNoticePopupVisible = true;
                this.fetchData();
                this.qna_title = "";
                this.qna_content = "";
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
.qna_title {
    margin-left: 1em;
    width: 50%;
    border-radius: 10px;
    border: 1px solid #cccccc;
    padding: 3px 15px;
    line-height: 1.7;
}
</style>
