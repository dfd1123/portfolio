<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <!-- <button v-on:click="beatpage" class="registbtn">비트관리</button>
                <button class="registbtn">정산</button>
                <button class="registbtn">마이페이지</button>-->
                <div class="box">
                    <h1 class="title-text">
                        프로듀서 메뉴
                    </h1>
                    <div
                        class="external-event"
                        style="background-color:#16162d"
                        @click.prevent="$router.push(`manage-producer-beat`, () => {});"
                    >
                        <div class="event-title">
                            비트 관리
                        </div>
                    </div>
                    <div
                        class="external-event"
                        style="background-color:#16162d"
                        @click.prevent="$router.push(`manage-producer-settlement-main`, () => {});"
                    >
                        <div class="event-title">
                            정산 관리
                        </div>
                    </div>
                    <div
                        class="external-event"
                        style="background-color:#6256a9"
                        @click.prevent="$router.push(`manage-producer-my-page-main`, () => {});"
                    >
                        <div class="event-title">
                            마이 페이지
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
            <div class="popup-layer-txt">
                {{ noticePopupText }}
            </div>
        </notice-popup-component>

        <confirm-popup-component
            title-text="확인"
            :visible.sync="isConfirmPopupVisible"
            right-button-text="알림 텍스트"
            @rightButtonClick="confirmClick"
        >
            <div class="popup-layer-txt">
                {{ confirmPopupText }}
            </div>
        </confirm-popup-component>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";

export default {
    ...mapGetters(["isUser", "isProducer"]),
    ...mapState({
        user: "user",
        producer: "producer",
        moods: "moods",
        genres: "genres"
    }),
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
        ConfirmPopupComponent
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            noticePopupText: "",
            confirmPopupText: ""
        };
    },
    methods: {
        confirmClick() {
            this.noticePopupText = "wow";
            alert();
        },
        beatpage() {
            this.$router.push("manage-producer-beat", () => {});
        }
    }
};
</script>

<style lang="scss" scoped>
.popup-layer-txt {
    color: red;
}
.registbtn {
    display: inline-block;
    width: 30%;
    height: 8em;
    color: #000;
    font-size: 5em;
    line-height: normal;
    vertical-align: middle;
    background-color: #fdfdfd;
    cursor: pointer;
    border: 1px solid #7e2aff;
    border-radius: 0.25em;
    margin-bottom: 2em;
}
.title-text{
    margin: 0 0.5% 1.5%;
    font-size: 2.2em;
    color: #33333399;
    position:relative;
}

.box {
    padding: 30px 20px;
    border-radius: 5px;
    float: none !important;
    box-shadow: none;
    position: relative;
}
.external-event {
    padding: 20px 20px;
    font-weight: bold;
    margin-bottom: 20px;
    border-radius: 2px;
    color: #fff;
    width: 48%;
    display: block;
    margin: 0 0.5% 1.5%;
    cursor: pointer;
    color:#fff;
}
.event-title {
    font-size: 2em;
    font-weight: bold;
    line-height: 60px;
    color:inherit;
}
</style>
