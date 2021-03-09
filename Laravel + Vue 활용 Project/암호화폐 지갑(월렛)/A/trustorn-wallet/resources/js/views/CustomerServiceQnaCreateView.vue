<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            :leftButtonRoute="{name: 'customer_service', params: { page: 'qna'}}"
            center="text"
            :centerText="__('customer_service.qna_create_title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('customer_service.qna_create_label1')}}</label>
                    <input
                        type="text"
                        class="in_input"
                        name="title"
                        v-model="title"
                        value
                        :placeholder="__('customer_service.placeholder_label1')"
                    />
                </div>
            </div>
            <div class="sd_box q_write">
                <div class="form_line">
                    <label class="label_hr">{{__('customer_service.qna_create_label2')}}</label>
                    <textarea
                        name="description"
                        rows="8"
                        :placeholder="__('customer_service.placeholder_label2')"
                        v-model="description"
                    ></textarea>
                </div>
            </div>
        </div>
        <footer-component
            :buttonText="__('customer_service.qna_register')"
            v-on:buttonClick="registerButtonClick"
            :active="isReadyToRegister"
        ></footer-component>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
        ></system-notice-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent,
        "system-notice-component": SystemNoticeComponent
    },
    data() {
        return {
            isPopupVisible: false,
            systemNoticeMessage: "",
            title: "",
            description: ""
        };
    },
    computed: {
        isReadyToRegister() {
            return this.title && this.description;
        }
    },
    methods: {
        async registerButtonClick() {
            if (this.isReadyToRegister) {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.post(`/api/qnas`, {
                        title: this.title,
                        description: this.description
                    });

                    this.$router.replace({
                        name: "customer_service_qna_status",
                        params: { status: "created" }
                    });
                } catch (e) {
                    this.systemNoticeMessage = this.__(
                        "customer_service.qna_already_exists"
                    );
                    this.isPopupVisible = true;
                } finally {
                    this.$store.commit("progressComponentHide");
                }
            } else {
                this.systemNoticeMessage = this.__(
                    "customer_service.qna_description_empty"
                );
                this.isPopupVisible = true;
            }
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
}

.trst-container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.q_write textarea {
    width: 100%;
    border: 0;
    border-bottom: 1px solid #c8c8c8;
    height: 270px;
    box-sizing: border-box;
    padding-top: 5px;
    font-size: 15px;
    line-height: 1.8;
    font-weight: 300;
    color: #505050;
    resize: none;
    outline: 0;
    -webkit-appearance: none;
}

.q_write textarea::placeholder{
    color: #BEC8C8;
    font-weight: 400;
}

textarea {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}
</style>
