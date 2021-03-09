<template>
    <div id="system_notice" v-show="visible">
        <div class="modal" style="display: block;" @click="noButtonClick()"></div>
        <div class="popup" style="display: block;">
            <span class="icon">
                <img :src="iconSrc">
            </span>
            <span class="popup_title sendcheck">
                <span v-if="message">{{message}}</span>
                <slot v-else></slot>
            </span>
            <p class="popup_btn_hr">
                <span class="popup_btn" @click="noButtonClick">{{__('system.no')}}</span>
                <span class="popup_btn" @click="yesButtonClick">{{__('system.yes')}}</span>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: ["visible", "iconSrc", "message"],
    methods: {
        noButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("noButtonClick");
        },
        yesButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("yesButtonClick");
        }
    }
};
</script>

<style scoped>
#system_notice {
    z-index: 1000;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
.modal {
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.49);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 3;
}

.popup {
    position: fixed;
    top: 50%;
    z-index: 3;
    background-color: white;
    left: 50%;
    width: 90%;
    border-radius: 10px;
    padding: 10px 15px;
    top: 30%;
    margin-top: 0;
    padding-top: 52px;
    text-align: center;
    transform: translateX(-50%);
}

.popup .icon {
    width: 70px;
    position: absolute;
    top: -35px;
    left: 50%;
    transform: translateX(-50%);
}

.popup .icon img{
    width: 100%;
}

.popup .popup_title {
    width: 100%;
    font-size: 0.9rem;
    color: #505050;
    margin: 0;
    font-weight: 400;
    display: inline-block;
}

.popup .popup_btn_hr.align_ct {
    text-align: center;
    display: block;
}

.popup .popup_btn_hr {
    width: 100%;
    display: flex;
    justify-content: space-between;
    margin-top: 7%;
}

.popup .popup_btn.ctbtn {
    float: inherit;
}

.popup .popup_btn {
    width: 49%;
    text-align: center;
    border: 1px solid #dcdcdc;
    border-radius: 10px;
    padding: 15px 0;
    font-size: 1rem;
    color: #333;
    font-weight: 400;
    display: inline-block;
}

.popup_title.sendcheck b {
    font-size: 16px;
    font-weight: 700;
}

.popup_title.sendcheck u {
    text-decoration: none;
    color: #19B4AA;
}
.popup .popup_btn:active{
    background-color: #f5f5f5;
}
</style>
