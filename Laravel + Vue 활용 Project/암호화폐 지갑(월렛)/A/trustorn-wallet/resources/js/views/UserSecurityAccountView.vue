<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/user_info"
            center="text"
            :centerText="__('user_security_account.title')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor security_area">
            <div class="top_box">
                <p class="user-level-guide">
                    <u>{{$store.state.detail.user.fullname}}</u>
                    {{__('user_security_account.info_1_1')}}
                    <strong>{{__('user_security_account.info_1_2')}}{{$store.state.detail.security.status}}</strong>
                    {{__('user_security_account.info_1_3')}}
                </p>
                <p class="security-page-title">
                    <span class="step-title">{{__('user_security_account.step2')}}</span>
                    <span class="step-info">
                        <u>2</u>/2
                    </span>
                </p>
                <ul class="step-guide">
                    <li>{{__('user_security_document.document1')}}</li>
                    <li class="now">{{__('user_security_document.document2')}}</li>
                </ul>
            </div>

            <div class="step_area scroll_area">
                <div class="sd_box">
                    <div class="form_line">
                        <label class="label_hr">{{__('user_security_account.account_info')}}</label>
                        <p class="info_line">{{$store.state.detail.user.fullname}}</p>
                        <div class="in_form_line account in_input">
                            <label
                                class="form_title"
                            >{{__('user_security_account.select_bank')}}</label>
                            <input
                                type="text"
                                name="account_bank"
                                :placeholder="__('user_security_account.placeholder_bank')"
                                v-model="accountBank"
                                required
                            />
                        </div>
                        <div class="in_form_line account in_input">
                            <label
                                class="form_title"
                            >{{__('user_security_account.account_number')}}</label>
                            <input
                                type="text"
                                name="account_number"
                                :placeholder="__('user_security_account.placeholder_account_number')"
                                v-model="accountNum"
                                required
                            />
                        </div>
                    </div>
                </div>
                <div class="sd_box btn_sd_box">
                    <div class="form_line">
                        <label
                            class="label_hr"
                        >{{__('user_security_account.account1_attatch')}}</label>
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
                                        <p><i class="fal fa-plus"></i></p>
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
                                    <p class="img_tt">{{__('user_security_account.account1_pic')}}</p>
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
                                        <p><i class="fal fa-plus"></i></p>
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
                                    >{{__('user_security_account.account1_pic_holding')}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn_footer" v-bind:class="{not_btn: isAllFilesNotUploaded}">
                        {{__('user_security_account.pic_attach_ok')}}
                    </div>
                </div>
                <div class="ps_text">
                    <p class="ps_p">{{__('user_security_account.ps_p1')}}</p>
                    <p class="ps_p">{{__('user_security_account.ps_p2')}}</p>
                    <p class="ps_p">{{__('user_security_account.ps_p3')}}</p>
                </div>
                <div class="ps_text">
                    <label class="ps_title">{{__('user_security_account.cautions')}}</label>
                    <p class="ps_p">{{__('user_security_account.ps_p4')}}</p>
                    <p class="ps_p">{{__('user_security_account.ps_p5')}}</p>
                </div>
            </div>
        </div>
        <footer-component
            :buttonText="__('user_security_account.next')"
            v-on:buttonClick="nextButtonClick"
            :active="!isAllFilesNotUploaded && !isAccountInfoNotFilled"
        ></footer-component>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('user_security_account.close')"
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
        next(vm => {
            if (vm.$store.state.detail.security.status >= 4) {
                vm.$router.replace("/");
            }
        });
    },
    data() {
        return {
            isPopupVisible: false,
            accountNum: "",
            accountBank: "",
            files: {}
        };
    },
    computed: {
        systemNoticeMessage() {
            if (this.isAccountInfoNotFilled) {
                return this.__("user_security_account.account_info_empty");
            }
            if (this.isAllFilesNotUploaded) {
                return this.__("user_security_account.pic_not_uploaded");
            }
            return "";
        },
        isAccountInfoNotFilled() {
            return !this.accountNum || !this.accountBank;
        },
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
            if (this.isAllFilesNotUploaded || this.isAccountInfoNotFilled) {
                this.isPopupVisible = true;
            } else {
                try {
                    this.$store.commit("progressComponentShow");

                    const formData = new FormData();
                    formData.append("file1", this.files.file1.files[0]);
                    formData.append("file2", this.files.file2.files[0]);
                    formData.append("account_num", this.accountNum);
                    formData.append("account_bank", this.accountBank);

                    await axios.post(
                        "/api/security/setting_account",
                        formData,
                        { headers: { "Content-Type": "multipart/form-data" } }
                    );

                    this.$router.replace({
                        name: "user_security_waiting",
                        params: { type: "account_waiting" }
                    });
                } catch (e) {
                    console.log(e);
                    this.$swal({
                        type: "error",
                        text: this.__("user_security_account.upload_error")
                    });
                } finally {
                    this.files.file1.value = "";
                    this.files.file2.value = "";
                    this.accountNum = "";
                    this.accountBank = "";
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
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}

.security_area .top_box {
    width: 100%;
    height: auto;
    margin: 0 auto;
    position: relative;
    top: 0;
    text-align: right;
}

.user-level-guide u {
    text-decoration: none;
    font-weight: 400;
    color: rgb(108, 114, 114);
    letter-spacing: 0;
}

.user-level-guide strong{
    color: #19B4AA;
}

.user-level-guide {
    width: 100%;
    display: inline-block;
    font-size: 13px;
    color: #A0AAAA;
    text-align: left;
}

.security-page-title {
    font-size: 23px;
    color: #1E1E1E;
    font-weight: 600;
    position: relative;
    padding: 20px 0 6px;
    width: 100%;
    display: inline-block;
    text-align: left;
}

.security-page-title .step-info{
    letter-spacing: 0;
    position: absolute;
    top: 25px;
    right: 3px;
    font-size: 0.92rem;
    font-weight: 300;
    color: #787878;
}

.security-page-title .step-info > u{
    text-decoration: none;
    font-weight: bold;
    font-size: 1.1em;
    color: #19B4AA;
}

.step-guide{
    display: inline-block;
}

.step-guide > li{
    display: inline-block;
    margin: 0 3px;
    font-weight: 300;
    color: #A0AAAA;
    font-size: 12px;
}

.step-guide > li.now{
    color: #19B4AA;
    font-weight: 400;
    border-bottom: 2px solid #19B4AA;
    padding-bottom: 3px;
}

.step_area {
    height: calc(100% - 103px);
    top: 0;
    border-top: 0;
    position: relative;
    width: 100%;
}

.account.in_input {
    position: relative;
    padding: 0;
}

.account.in_input .form_title{
    color: #19B4AA;
    font-weight: 400;
    font-size: 0.85rem;
    padding: 0 5px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 0;
    width: 70px;
    margin: 0;
}

.account.in_input > input{
    background-color: transparent;
    border: 0;
    border-radius: 0;
    width: 100%;
    padding-left: 70px;
    height: 100%;
    font-size: 0.9rem;
    color: #505050;
    line-height: normal;
}

.account.in_input > input::placeholder{
    font-weight: 300;
    color: #BEC8C8;
}

.info_line {
    width: 100%;
    height: 35px;
    background-color: #F5F5F5;
    line-height: 35px;
    margin: 6px 0;
    padding-left: 10px;
    font-size: 0.93rem;
    color: #505050;
    font-weight: 400;
    letter-spacing: 0.2px;
}

.btn_sd_box .center {
    width: 100%;
    text-align: center;
    padding: 15px 0 30px;
}

.btn_sd_box .atch_con {
    width: 120px;
    display: inline-block;
    position: relative;
    text-align: center;
}

.atch_con .img_tt {
    font-size: 12px;
    color: #505050;
    text-align: center;
    width: 100%;
    font-weight: 400;
}

.atch_con .plus_area {
    width: 100px;
    height: 87px;
    background: linear-gradient(to bottom, rgb(100, 225, 150), rgb(25, 180, 170));
    display: block;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0px 8px 12px rgba(195, 215, 215, 0.6);
    line-height: 1;
    display: flex;
    align-items: center;
    margin: 0 auto 13px;
}

.atch_con .plus_area img {
    width: 100%;
    height: 100%;
    float: left;
}

.atch_con .plus_area p {
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,0.3);
    color: rgba(255,255,255,0.9);
    font-size: 15px;
    border-radius: 50px;
    text-align: center;
    line-height: 30px;
    margin: 0 auto;
    font-weight: 100;
}

.btn_sd_box .btn_footer {
    width: 100%;
    padding: 9px 0;
    font-size: 13px;
    color: #b4b4b4;
    text-align: center;
    font-weight: 300;
    border: none;
    border: 1px solid #19B4AA;
    font-weight: 400;
    color: #19B4AA;
    transition: all 0.15s;
    background-color: white;
    opacity: 1;
}

.btn_sd_box .btn_footer.not_btn {
    opacity: 0.3;
}
</style>
