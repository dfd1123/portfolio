<template>
    <div id="system_notice" v-show="visible">
        <div class="modal" style="display: block;" @click="closeButtonClick()"></div>
        <div class="popup" style="display: block;">
            <span class="icon">
                <img :src="iconSrc">
            </span>
            <span class="popup_title sendcheck">{{message}}</span>
            <p class="popup_btn_hr align_ct">
                <span class="popup_btn ctbtn" @click="closeButtonClick">{{closeText}}</span>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        visible: {},
        message: {},
        closeText: {},
        iconSrc: {
            default: "/images/x_icon.svg"
        }
    },
    data() {
        return {};
    },
    methods: {
        closeButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("closeButtonClick");
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
    margin-left: -45%;
    width: 90%;
    border-radius: 10px;
    padding: 10px 15px;
    top: 31%;
    margin-top: 0;
}

.icon {
    width: 62px;
    margin-top: 20vh;
}

.popup .icon img {
    position: absolute;
    top: -35px;
    left: 50%;
    margin-left: -35px;
    width: 70px;
}

.popup .popup_title {
    text-align: center;
    width: 100%;
    float: left;
    font-size: 14px;
    color: #5a5a5a;
    letter-spacing: -1px;
    margin: 50px 0 20px 0;
    font-weight: 600;
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
    font-size: 14px;
    font-weight: 700;
    transition-duration: 0.5s;
    display: inline-block;
}
</style>
