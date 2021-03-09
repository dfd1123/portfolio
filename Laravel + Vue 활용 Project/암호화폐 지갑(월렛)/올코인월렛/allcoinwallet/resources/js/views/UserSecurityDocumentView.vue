<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/user_info"
            center="text"
            :centerText="__('user_security_document.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor security_area">
            <div class="top_box">
                <p class="hr_line">
                    <u>{{$store.state.detail.user.fullname}}</u>
                    {{__('user_security_document.info_1_1')}}
                    <strong>{{__('user_security_document.info_1_2')}}{{$store.state.detail.security.status}}</strong>
                    {{__('user_security_document.info_1_3')}}
                </p>
                <p class="hr_line step">
                    <span>{{__('user_security_document.step1')}}</span>
                    <span>
                        <u>1</u>/2
                    </span>
                </p>
                <p class="gage per_50 left"></p>
                <ul class="gage_p">
                    <li class="now">{{__('user_security_document.document1')}}</li>
                    <li>{{__('user_security_document.document2')}}</li>
                </ul>
            </div>

            <div class="user_list step_area step4 scroll_area">
                <div class="sd_box bt_15px">
                    <div class="form_line">
                        <label class="label_hr">{{__('user_security_document.user_info')}}</label>
                        <p class="info_line">{{$store.state.detail.user.fullname}}</p>
                        <p class="info_line">{{$store.state.detail.user.mobile_number}}</p>
                    </div>
                </div>
                <div class="sd_box btn_sd_box atch">
                    <div class="form_line pd_10">
                        <label
                            class="label_hr pdyes"
                        >{{__('user_security_document.document1_attach')}}</label>
                        <div class="center">
                            <div class="in_form_line atch_con">
                                <div class="file_input" @click="$refs.file1.click()">
                                    <label class="plus_area" for="img_1">
                                        <img
                                            ref="img_file1"
                                            class="imgimg"
                                            src="#"
                                            alt="your_id_card"
                                            v-show="this.files.file1"
                                        />
                                        <p>+</p>
                                    </label>
                                    <input
                                        type="file"
                                        ref="file1"
                                        class="both_file"
                                        accept="image/*"
                                        style="display: none;"
                                        @change="fileChange('file1')"
                                    />
                                    <input type="hidden" id="img_1_text" />
                                    <p class="img_tt">{{__('user_security_document.document1_pic')}}</p>
                                </div>
                            </div>
                            <div class="in_form_line atch_con">
                                <div class="file_input" @click="$refs.file2.click()">
                                    <label class="plus_area" for="img_2">
                                        <img
                                            ref="img_file2"
                                            src="#"
                                            alt="you_and_your_id_card"
                                            v-show="this.files.file2"
                                        />
                                        <p>+</p>
                                    </label>
                                    <input
                                        type="file"
                                        ref="file2"
                                        class="both_file"
                                        accept="image/*"
                                        style="display: none;"
                                        @change="fileChange('file2')"
                                    />
                                    <input type="hidden" id="img_2_text" />
                                    <p
                                        class="img_tt"
                                    >{{__('user_security_document.document1_pic_holding')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn_footer" v-bind:class="{not_btn: isAllFilesNotUploaded}">
                        <div>{{__('user_security_document.pic_attach_ok')}}</div>
                    </div>
                </div>
                <div class="ps_text btborder">
                    <p class="ps_p">{{__('user_security_document.ps_p1')}}</p>
                    <p class="ps_p">{{__('user_security_document.ps_p2')}}</p>
                </div>
                <div class="ps_text ps_text_2nd">
                    <label class="ps_title">{{__('user_security_document.cautions')}}</label>
                    <p class="ps_p">{{__('user_security_document.ps_p3')}}</p>
                    <p class="ps_p">{{__('user_security_document.ps_p4')}}</p>
                </div>
            </div>
        </div>
        <footer-component
            :buttonText="__('user_security_document.next')"
            v-on:buttonClick="nextButtonClick"
            :active="!isAllFilesNotUploaded"
        ></footer-component>
        <system-notice-component
            :message="__('user_security_document.pic_not_uploaded')"
            :closeText="__('user_security_document.close')"
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
    data() {
        return {
            isPopupVisible: false,
            files: {}
        };
    },
    computed: {
        isAllFilesNotUploaded() {
            return !this.files.file1 || !this.files.file2;
        }
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent,
        "system-notice-component": SystemNoticeComponent
    },
    methods: {
        fileChange(name) {
            const fileInput = this.$refs[name];
            Vue.set(this.files, name, fileInput);

            const { files } = fileInput;
            if (FileReader && files && files.length) {
                const fr = new FileReader();
                fr.onload = () => {
                    this.$refs[`img_${name}`].src = fr.result;
                };
                fr.readAsDataURL(files[0]);
            }
        },
        async nextButtonClick() {
            if (this.isAllFilesNotUploaded) {
                this.isPopupVisible = true;
            } else {
                try {
                    this.$store.commit("progressComponentShow");

                    const formData = new FormData();
                    formData.append("file1", this.files.file1.files[0]);
                    formData.append("file2", this.files.file2.files[0]);

                    await axios.post(
                        "/api/security/setting_document",
                        formData,
                        { headers: { "Content-Type": "multipart/form-data" } }
                    );

                    this.$router.replace("/user_security_account");
                } catch (e) {
                    console.log(e);
                    this.$swal({
                        type: "error",
                        text: this.__("user_security_document.upload_error")
                    });
                } finally {
                    this.files.file1.value = "";
                    this.files.file2.value = "";
                    this.files = {};
                    this.$store.commit("progressComponentHide");
                }
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
    margin-bottom: -53px;
    padding-bottom: 52px;
}

.ai_container {
    background-color: #fafafa;
    height: 100%;
}

.bgcolor {
    background-color: #fafafa;
    height: 100%;
}

.scroll_area {
    overflow-y: scroll;
}

.security_area .top_box {
    width: 95%;
    height: auto;
    margin: 0 auto;
    position: relative;
    top: 0;
    padding-top: 20px;
}

.security_area .top_box .hr_line {
    width: 100%;
    display: inline-block;
    margin-bottom: 3%;
    font-size: 12px;
    color: #5a5a5a;
}

.security_area .top_box .hr_line:first-child u {
    text-decoration: none;
    font-weight: 900;
}

.security_area .top_box .hr_line:first-child strong {
    color: #0072ff;
}

.security_area .top_box .hr_line {
    width: 100%;
    display: inline-block;
    margin-bottom: 3%;
    font-size: 12px;
    color: #5a5a5a;
}

.security_area .top_box .hr_line.step {
    font-size: 20px;
    color: #0045ab;
    font-weight: 600;
    margin-bottom: 5%;
    position: relative;
}

.security_area .top_box .hr_line.step span:nth-child(2) {
    position: absolute;
    right: 0;
    font-size: 13px;
    color: #5a5a5a;
}

.security_area .top_box .hr_line.step span:nth-child(2) u {
    color: #0072ff;
    text-decoration: none;
    font-size: 16px;
}

.security_area .top_box .gage {
    width: 100%;
    height: 2px;
    background-color: #e6e6e6;
    position: relative;
    float: left;
    margin-bottom: 10px;
}

.security_area .top_box .gage.per_50:after {
    width: 50%;
}

.security_area .top_box .gage:after {
    content: "";
    position: absolute;
    width: 20%;
    height: 10px;
    background: linear-gradient(to right, #0072ff, #49d094);
    top: -3px;
}

.security_area .top_box .gage.left:after {
    left: 0;
}

.security_area .top_box .gage.right:after {
    right: 0;
}

.security_area .top_box .gage_p {
    width: 100%;
    margin-top: 10px;
    float: left;
    padding: 0;
    margin: 0;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.security_area .top_box .gage_p li {
    float: left;
    width: 50%;
    text-align: center;
    font-size: 10px;
    color: #c8c8c8;
}

.security_area .top_box .gage_p li.now {
    color: #5a5a5a;
}

.security_area .top_box .gage_p li.now {
    color: #5a5a5a;
}

.security_area .top_box .gage_p li {
    float: left;
    width: 50%;
    text-align: center;
    font-size: 10px;
    color: #c8c8c8;
}

.user_list {
    height: calc(100% - 103px);
}

.user_list.step_area {
    top: 0;
    border-top: 0;
    position: relative;
    width: 100%;
    margin-top: -10px;
    padding-top: 20px;
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
}

.ai_wrapper .bt_15px {
    margin-bottom: 15px;
}

.sd_box .form_line {
    width: 100%;
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

.info_line {
    width: 100%;
    height: 30px;
    background-color: #f6f6f6;
    line-height: 30px;
    margin: 10px 0 15px 0;
    padding-left: 10px;
    font-size: 15px;
    color: #5a5a5a;
    font-weight: 500;
}

.ai_wrapper .sd_box.btn_sd_box {
    padding: 0;
    border-radius: 0;
}

.ai_wrapper .btn_sd_box .form_line.pd_10 {
    padding: 10px 10px;
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

.sd_box .form_line .label_hr.pdyes {
    padding: 5px 0 5px 5px;
}

.center {
    width: 100%;
    text-align: center;
}

.in_form_line.atch_con {
    width: 120px;
    display: inline-block;
    position: relative;
    padding-bottom: 30px;
}

.in_form_line.atch_con:nth-child(1) {
    margin-left: 20px;
    margin-top: 5px;
}

.step_area .file_input {
    display: inline-block;
    width: 80%;
}

.in_form_line.atch_con .file_input {
    width: 100%;
}

.sd_box .form_line label {
    width: 80%;
    display: inline-block;
    margin-bottom: 5px;
    color: #0747ad;
    letter-spacing: -0.5px;
    font-weight: 600;
    font-size: 14px;
}

.plus_area img {
    width: 100%;
    height: 100%;
    float: left;
}

.plus_area p {
    width: 30px;
    height: 30px;
    background: #8adab2;
    color: white;
    font-size: 28px;
    border-radius: 50px;
    text-align: center;
    line-height: 30px;
    margin: 0 auto;
    margin-top: 29px;
    font-weight: 100;
}

p.img_tt {
    font-size: 11px;
    color: #5a5a5a;
    letter-spacing: -0.05rem;
    position: absolute;
    text-align: center;
    width: 100%;
    margin-top: 14px;
    right: 10px;
}

.plus_area {
    width: 100px !important;
    height: 87px;
    background: #49d094;
    display: block !important;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0px 5px 20px rgba(0, 69, 191, 0.2);
    line-height: 1;
}

.ai_wrapper .sd_box.btn_sd_box .btn_footer {
    width: 100%;
    background-color: #49d094;
    text-align: center;
    padding: 10px 0;
}

.ai_wrapper .btn_sd_box .btn_footer.not_btn {
    background-color: #dcdcdc;
}

.ai_wrapper .btn_sd_box .btn_footer.not_btn div {
    color: #b4b4b4;
}

.ai_wrapper .sd_box.btn_sd_box .btn_footer div {
    color: white;
    border: 0;
    background-color: transparent;
    font-weight: 600;
    font-size: 12px;
}

.ai_wrapper .btn_sd_box .btn_footer.not_btn div {
    color: #b4b4b4;
}

.ps_text.btborder {
    border-bottom: 1px solid #e6e6e6;
}

.ai_wrapper .ps_text {
    width: 90%;
    margin: 0 auto;
    margin-top: 8%;
    padding: 5px 5px;
}

.ai_wrapper .ps_text .ps_p {
    width: 100%;
    font-weight: 600;
    color: #5a5a5a;
    font-size: 11px;
    line-height: 18px;
    letter-spacing: -1px;
}

.ai_wrapper .ps_text .ps_p:nth-child(2) {
    margin-top: 3%;
}

.ai_wrapper .ps_text.ps_text_2nd {
    margin-top: 2%;
    margin-bottom: 10px;
}

.ai_wrapper .ps_text .ps_title {
    width: 100%;
    font-weight: 600;
    color: #0072ff;
    font-size: 12px;
}
</style>
