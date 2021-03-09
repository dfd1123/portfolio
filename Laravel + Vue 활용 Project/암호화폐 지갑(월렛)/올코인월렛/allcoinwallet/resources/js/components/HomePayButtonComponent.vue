<template>
    <div id="QR_code_scan_btn" @click="payButtonClick">
        <i class="fal fa-qrcode"></i>
        <p>PAY</p>
    </div>
</template>

<script>
export default {
    components: {},
    data() {
        return {};
    },
    methods: {
        payButtonClick() {
            this.$EventBus.$emit("pay-scan-request");
        }
    }
};
</script>

<style scoped>
#QR_code_scan_btn {
    background: #1d324f;
    border-radius: 50%;
    width: 65px;
    height: 65px;
    position: fixed;
    bottom: 15px;
    right: 12px;
    z-index: 1;
    color: #49d094;
    text-align: center;
    padding: 15.5px 0;
    box-shadow: 0 3px 13px rgba(0, 69, 191, 0.5);
    -moz-box-shadow: 0 3px 13px rgba(0, 69, 191, 0.5);
    -webkit-box-shadow: 0 3px 13px rgba(0, 69, 191, 0.5);
    transition: all ease 0.1s;
}

#QR_code_scan_btn:active {
    bottom: 30px;
    box-shadow: 0 0 35px rgba(0, 69, 191, 0.5);
    -moz-box-shadow: 0 0 35px rgba(0, 69, 191, 0.5);
    -webkit-box-shadow: 0 0 35px rgba(0, 69, 191, 0.5);
}

#QR_code_scan_btn i {
    font-size: 23px;
    font-weight: 500;
}

#QR_code_scan_btn p {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.5px;
    padding-top: 3px;
}
</style>