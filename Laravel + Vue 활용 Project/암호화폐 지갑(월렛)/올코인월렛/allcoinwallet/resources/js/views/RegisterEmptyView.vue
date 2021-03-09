<template>
    <div style="width: 100%; height: 100%;">
        <header-component
            leftButton="back"
            leftButtonRoute="/register"
            center="text"
            centerText="회원가입"
            rightButton="home"
        ></header-component>
        <iframe
            name="nicecheck"
            frameborder="0"
            border="0"
            style="width:100vw; height: calc(100vh - 44px); position: fixed; top: 44px; left: 0; right: 0; bottom: 0;"
        ></iframe>
        <!-- nice 정보평가 인증 -->
        <form name="form_chk" method="post">
            <input type="hidden" name="m" value="checkplusSerivce" />
            <input type="hidden" name="EncodeData" :value="encData" />
        </form>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";

export default {
    beforeRouteEnter(to, from, next) {
        next();
    },
    components: {
        "header-component": HeaderComponent
    },
    data() {
        return {
            encData: ""
        };
    },
    beforeCreate() {
        this.$EventBus.$on("nice-check-result", event => {
            this.$store.commit("progressComponentShow");
            setTimeout(() => {
                if (event.status === "1") {
                    this.$store.commit("mergeRegisterViewData", {
                        fullname: event.message.name,
                        mobileNumber: event.message.mobile_no,
                        mobileNumberAuthVerify: true
                    });
                }
                this.$store.commit("progressComponentHide");
                this.$router.replace("/register");
            }, 1500);
        });
    },
    mounted() {
        this.encData = document.head.querySelector(
            'meta[name="enc_data"]'
        ).content;

        this.$store.commit("progressComponentShow");
        setTimeout(() => {
            document.form_chk.action =
                "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
            document.form_chk.target = "nicecheck";
            document.form_chk.submit();
            setTimeout(() => {
                this.$store.commit("progressComponentHide");
            }, 1500);
        }, 250);
    },
    beforeDestroy() {
        this.$EventBus.$off("nice-check-result");
    }
};
</script>

<style scoped>
body,
html {
    width: 100%;
    height: 100%;
    overflow: hidden;
}

* {
    padding: 0;
    margin: 0;
}
</style>