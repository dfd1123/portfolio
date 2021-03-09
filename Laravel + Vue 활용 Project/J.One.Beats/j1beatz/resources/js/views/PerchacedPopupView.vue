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

        const value = this.$route.query.value;
        if (Number.isInteger(value) && Number(value) >= 0) {
            window.fbq("track", "Purchase", {
                value: value,
                currency: "KRW"
            });
        }

        this.isNoticePopupVisible = true;
        this.noticePopupMessage =
            "결제 완료 후 '결제 완료' 버튼을 <br> 눌러주시기 바랍니다.";
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
