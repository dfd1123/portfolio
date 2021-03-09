/* eslint-disable require-atomic-updates */
// import preval from "preval.macro";
import axios from "axios";
import Vue from "vue";
import VueRouter from "vue-router";
import Vuex from "vuex";
import ClipboardJS from "clipboard";

/**
 * jQuery and jQuery plugins are not 'require' here. use <script> tags in '/resources/views/app.blade.php'
 *
 * [WARNING]
 * jQuery and jQuery plugins are not optional !!!
 */

/**
 * IE Polyfill
 */
//NodeList.prototype.forEach
if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = Array.prototype.forEach;
}

/**
 * Top Styles
 * 최상단에 로딩하기 위해 여기서 로딩
 */
require("swiper/dist/css/swiper.css");

require("../styles/scss/j1beat-ui.scss");

require("../styles/scss/components/beatz_chart.scss");
require("../styles/scss/components/cart_chart.scss");
require("../styles/scss/components/input_checkbox.scss");
require("../styles/scss/components/input_range.scss");
require("../styles/scss/components/j1beatz_popup.scss");
require("../styles/scss/components/mood_genre_btns.scss");
require("../styles/scss/components/producer_card.scss");
require("../styles/scss/components/result_card.scss");
require("../styles/scss/components/sub_page_form_style.scss");
require("../styles/scss/components/sub_page_large_title.scss");
require("../styles/scss/components/loading.scss");

require("../styles/scss/pages/main-page.scss");
require("../styles/scss/pages/sub-page-auth.scss");
require("../styles/scss/pages/sub-page-cart.scss");
require("../styles/scss/pages/sub-page-cs.scss");
require("../styles/scss/pages/sub-page-mypage.scss");
require("../styles/scss/pages/sub-page.scss");

/**
 * Vue
 */
window.Vue = Vue;
Vue.use(VueRouter);
Vue.use(Vuex);
Vue.prototype.$bus = new Vue();
Vue.prototype.$http = axios;

/**
 * Vue App Global Components
 */
const GlobalLoadingIndicatorComponent = require("./components/common/GlobalLoadingIndicatorComponent.vue")
    .default;

const MyAlbumRegistPopupComponent = require("./components/common/MyAlbumRegistPopupComponent.vue")
    .default;
/**
 * Vue App Layout Components
 */
const LeftHeaderComponent = require("./components/layout/LeftHeaderComponent.vue")
    .default;

const BottomPlaybarComponent = require("./components/layout/BottomPlaybarComponent.vue")
    .default;

const RightPlaylistComponent = require("./components/layout/RightPlaylistComponent.vue")
    .default;

/**
 * Vue Router Instance
 */
const router = require("./routes/router").default;

/**
 * Vue Store Instance
 */
const store = require("./stores/store").default;

/**
 * Vue Mixins
 */
const mixin = require("./mixin").default;
Vue.mixin(mixin);

/**
 * Bottom Styles
 * 최하단에 로딩하기 위해 여기서 로딩
 */
require("../styles/lib/responsive.css");

// axios 헤더 추가
axios.defaults.headers.common["Accept"] = "application/json";
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// axios 요청 시마다 토큰 최신화
axios.interceptors.request.use(config => {
    // 이전에 발급받은 토큰이 있으면
    if (localStorage.laravel_token) {
        config.headers[
            "Authorization"
        ] = `Bearer ${localStorage.laravel_token}`;
    }
    return config;
});

// axios 요청 응답코드가 401(인증안됨) 이면 세션 만료 표시
axios.interceptors.response.use(
    function(response) {
        return response;
    },
    function(error) {
        if (error.response && 401 === error.response.status) {
            if (localStorage.laravel_token) {
                if (
                    error.response.config.url === "/api/heartbeat" ||
                    error.response.config.url === "/api/login" ||
                    error.response.config.url === "/api/refresh"
                ) {
                    return;
                }

                alert("세션이 만료되었습니다. 다시 로그인해주시기 바랍니다.");
                localStorage.removeItem("laravel_token");
                axios.defaults.headers.common["Authorization"] = undefined;

                window.location.replace("/login");
            }
        } else if (error.code === "ECONNABORTED") {
            // alert("네트워크 접속 상태가 불안정합니다.");
        }

        return Promise.reject(error);
    }
);

//액세스 토큰 갱신
/*
const refresh = async () => {
    try {
        if (localStorage.laravel_token) {
            const response = await axios.post("/api/refresh");
            const token = response.data.token;

            localStorage.laravel_token = token;
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        }
    } catch (e) {
        // console.log(e);
    }
};
*/

/**
 * Clipboard Copy Support
 */

/**
 * App Mount
 */
(async () => {
    await mixin.methods.__info();

    new Vue({
        el: "#app",
        router,
        store,
        components: {
            GlobalLoadingIndicatorComponent,
            LeftHeaderComponent,
            BottomPlaybarComponent,
            RightPlaylistComponent,
            MyAlbumRegistPopupComponent
        },
        data() {
            return {
                clipboard: null
            };
        },
        mounted() {
            this.clipboard = new ClipboardJS("#clipboard");
        },
        beforeDestroy() {
            this.clipboard.destroy();
        }
    });
})();