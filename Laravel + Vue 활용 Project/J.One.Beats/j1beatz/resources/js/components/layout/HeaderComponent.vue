<template>
    <article
        class="j1beatz-hd"
        :class="[ver]"
    >
        <h3 class="none">
            제목 및 아티스트 및 태그 검색하기
        </h3>

        <fieldset
            class="j1beatz-hd-sch-field"
        >
            <input
                v-model="keyword"
                type="text"
                placeholder="제목/아티스트/#태그 검색"
                @keyup.enter="search"
            >
            <button
                ref="button"
                type="button"
                class="sch_btn"
                @click="search"
            >
                <span class="none">검색버튼</span>
            </button>
        </fieldset>

        <div class="j1beatz-hd-cs-field">
            <a
                v-show="!isUser"
                href="#"
                class="login_alink"
                @click.prevent="$router.push('/login', () => {})"
            >로그인</a>
            <div
                v-show="isUser"
                class="j1beatz-hd-div"
            >
                <h3
                    v-if="!isProducer && !isSuspendedProducer"
                    style="cursor: pointer"
                    @click="registerProducerClick"
                >
                    <img
                        src="/img/icon/icon_producer_regist.svg"
                        alt="producer icon"
                        class="producer_icon producer_icon_wh"
                    >
                    <img
                        src="/img/icon/icon_producer_regist_dark.svg"
                        alt="producer icon"
                        class="producer_icon producer_icon_dark"
                    >
                    프로듀서등록
                </h3>
                <h3
                    v-if="isProducer || isSuspendedProducer"
                    style="cursor: pointer"
                    @click="settingProducerClick"
                >
                    <img
                        src="/img/icon/icon_producer_regist.svg"
                        alt="producer icon"
                        class="producer_icon producer_icon_wh"
                    >
                    <img
                        src="/img/icon/icon_producer_regist_dark.svg"
                        alt="producer icon"
                        class="producer_icon producer_icon_dark"
                    >
                    프로듀서설정
                </h3>
                <span
                    class="border-right"
                >|</span>
            </div>
            <h3
                style="cursor:pointer"
                @click.prevent="$router.push('/notice',()=> {})"
            >
                고객센터
            </h3>
            <div class="cs-btns">
                <a
                    href="https://blog.naver.com/j1beatz"
                    target="_blank"
                    class="cs-btn cs-btn-blg"
                >
                    <span class="none">블로그</span>
                </a>
                <a
                    href="https://pf.kakao.com/_axjxdxhC"
                    target="_blank"
                    class="cs-btn cs-btn-kkt"
                >
                    <span class="none">카카오톡</span>
                </a>
                <a
                    href="https://www.instagram.com/j1beatz_official/"
                    class="cs-btn cs-btn-insta"
                    target="_blank"
                >
                    <span class="none">인스타그램</span>
                </a>
                <a
                    href="https://www.youtube.com/channel/UCqOAZzZ87AG4EEgSmVPhnFw"
                    class="cs-btn cs-btn-ytb"
                    target="_blank"
                >
                    <span class="none">유튜브</span>
                </a>
            </div>
        </div>

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
    </article>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import NoticePopupComponent from "../common/NoticePopupComponent";

export default {
    components: {
        NoticePopupComponent
    },
    props: {
        ver: {
            type: String,
            default: ""
        }
    },
    data() {
        return {
            keyword: "",
            isNoticePopupVisible: false,
            noticePopupMessage: ""
        };
    },
    computed: {
        ...mapGetters([
            "isUser",
            "isProducer",
            "isWaitingProducer",
            "isSuspendedProducer"
        ]),
        ...mapState({
            producer: "producer"
        })
    },
    methods: {
        registerProducerClick() {
            if (!this.isWaitingProducer) {
                // 프로듀서 회원가입
                this.$router.push("/register-producer", () => {});
            } else if (this.isWaitingProducer) {
                // 프로듀서 검수중 화면
                this.$router.push("/register-producer-review", () => {});
            }
        },
        settingProducerClick() {
            if (this.isProducer) {
                this.$router.push("/manage-producer-main", () => {});
            } else if (this.isSuspendedProducer) {
                alert("프로듀서 활동이 정지된 상태입니다.");
                // 팝업 띄워서 보여주기 alert("프로듀서 활동이 정지된 상태입니다.");
                // 팝업 띄워서 보여주기
            }
        },
        search() {
            this.$nextTick(() => {
                this.$refs.button.focus();
                this.$refs.button.blur();
            });

            if (this.keyword === "") {
                this.noticePopupMessage = "검색어를 입력해 주시기 바랍니다";
                this.isNoticePopupVisible = true;
                return;
            }

            if (this.keyword.startsWith("#")) {
                if (this.keyword === "#") {
                    this.keyword = "";
                    return;
                }

                if ((this.keyword.match(/#/g) || []).length > 1) {
                    this.noticePopupMessage =
                        "#태그 검색은 하나씩만 가능합니다";
                    this.isNoticePopupVisible = true;
                    return;
                }
            }

            if (this.keyword.startsWith("#")) {
                this.$router.push(
                    { name: "search-tag", query: { keyword: this.keyword } },
                    () => {},
                    () => {
                        this.$emit("searchAgain", this.keyword);
                        this.keyword = "";
                    }
                );
            } else {
                this.$router.push(
                    { name: "search-basic", query: { keyword: this.keyword } },
                    () => {},
                    () => {
                        this.$emit("searchAgain", this.keyword);
                        this.keyword = "";
                    }
                );
            }
        }
    }
};
</script>

<style lang="scss">
// 헤더 맞음
@import "../../../styles/scss/layouts/j1beats-hd";
</style>
