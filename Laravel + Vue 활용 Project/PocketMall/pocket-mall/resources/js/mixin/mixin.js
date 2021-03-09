import Vue from "vue";

const mixin = {
    data() {
        return {
            productLoadingShow: false,
            loadingShow: false,
            openQuestion: false
        };
    },
    methods: {
        ConvertImage(jsonImg) {
            let imgs = [];
            if (jsonImg) {
                imgs = JSON.parse(jsonImg);
            }

            return imgs;
        },
        IsMobile() {
            const UserAgent = navigator.userAgent;
            if (
                UserAgent.match(
                    /iPhone|iPad|iPod|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i
                ) != null ||
                UserAgent.match(/LG|SAMSUNG|Samsung/) != null
            ) {
                return true;
            } else {
                return false;
            }
        },
        NumberFormat(number) {
            let parts = number.toString().split(".");
            return (
                parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") +
                (parts[1] ? "." + parts[1] : "")
            );
        },
        async SuccessAlert(text) {
            let resultVal = false;
            await this.$swal({
                text: text,
                type: "success"
            }).then(result => {
                resultVal = result.value;
            });

            return resultVal;
        },
        ProductLoading(status) {
            this.productLoadingShow = status;
        },
        NotifiDefault(text) {
            Vue.$toast.default(text);
        },
        NotifiError(text) {
            Vue.$toast.error(text);
        },
        NotifiSuccess(text) {
            Vue.$toast.success(text);
        },
        WarningAlert(text) {
            this.$swal({
                html: text,
                type: "warning",
                icon: "warning",
                showConfirmButton: false,
                timer: 1500,
                customClass: {
                    content: "custom-warning-content",
                    icon: "custom-warning-icon"
                }
            }).then(result => { });
        }
    }
};

Vue.mixin(mixin);
export default mixin;
