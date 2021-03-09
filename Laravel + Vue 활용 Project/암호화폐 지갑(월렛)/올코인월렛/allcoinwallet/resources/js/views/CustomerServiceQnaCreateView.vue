<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            :leftButtonRoute="{name: 'customer_service', params: { page: 'qna'}}"
            center="text"
            :centerText="__('customer_service.qna_create_title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">{{__('customer_service.qna_create_label1')}}</label>
                    <input
                        type="text"
                        class="w_address"
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
    padding-top: 45px;
}

.ai_wrapper.top_0 {
    padding-top: 45px;
}

.ai_wrapper.bottom_0 {
    padding-bottom: 0;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: #fafafa;
    height: 100%;
}

.scroll_area {
    overflow-x: hidden;
    overflow-y: auto;
}

.ai_wrapper .sd_box {
    width: 90%;
    height: auto;
    background-color: white;
    border-radius: 5px;
    box-shadow: 0px 5px 20px rgba(0, 69, 191, 0.2);
    margin: 0 auto;
    position: relative;
    top: 2vh;
    padding: 15px 15px;
    margin-bottom: 15px;
}

.sd_box .form_line:last-child {
    margin-bottom: 0;
}

.sd_box .form_line .label_hr {
    width: 100%;
    display: inline-block;
    margin-bottom: 5px;
    color: #0747ad;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 8px;
}

.sd_box .form_line .w_address {
    width: 100%;
    font-size: 14px;
    border: 0;
    border-bottom: 1px solid #dcdcdc;
    padding: 5px 10px;
    word-break: break-all;
    color: #5a5a5a;
    line-height: 25px;
}

.sd_box.q_write textarea {
    width: 100%;
    border: 0;
    border-bottom: 1px solid #c8c8c8;
    height: 270px;
    padding: 0 10px;
    box-sizing: border-box;
    padding-top: 5px;
    font-size: 14px;
}

textarea {
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
}

:focus {
    outline: 0;
}
</style>
