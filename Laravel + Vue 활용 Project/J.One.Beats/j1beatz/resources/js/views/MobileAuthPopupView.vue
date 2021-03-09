<template>
    <div>
        <notice-popup-component
            title-text="알림"
            :visible.sync="isNoticePopupVisible"
            @closeButtonClick="close"
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
import NoticePopupComponent from "../components/common/NoticePopupComponent";

export default {
    components: {
        NoticePopupComponent
    },
    data() {
        return {
            isNoticePopupVisible: false,
            noticePopupMessage: ""
        };
    },
    mounted() {
        document.querySelector("header").hidden = true;
        document.querySelector("body").style.overflow = "hidden";

        this.isNoticePopupVisible = true;
        this.noticePopupMessage =
            "인증완료 후 '인증 확인' 버튼을 <br> 눌러주시기 바랍니다.";
    },
    methods: {
        close() {
            window.close();
        }
    }
};
</script>

<style lang="scss">
</style>
