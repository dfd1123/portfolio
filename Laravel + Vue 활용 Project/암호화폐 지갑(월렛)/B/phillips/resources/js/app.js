/**
 * jQuery
 */
window.$ = require("jquery");
window.jquery = require("jquery");
window.jQuery = require("jquery");

/**
 * Styles
 */
require("bootstrap");
require("bootstrap/dist/css/bootstrap.min.css");
require("../css/common.css");

// fontawesome-pro
require("@fortawesome/fontawesome-pro/css/fontawesome.min.css");
require("@fortawesome/fontawesome-pro/css/solid.min.css");
require("@fortawesome/fontawesome-pro/css/regular.min.css");
require("@fortawesome/fontawesome-pro/css/light.min.css");

/**
 * axios
 */
// Laravel backend setting
window.axios = require("axios");
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

/**
 *  moment
 */
window.moment = require("moment-mini");

/**
 * Vue Libraries
 */
window.Vue = require("vue");

window.VueRouter = require("vue-router").default;

window.Vuex = require("vuex").default;

Vue.use(VueRouter);

Vue.use(Vuex);

Vue.use(require("vue-sweetalert2").default);

/**
 * Vue Settings
 */
window.$EventBus = new Vue();
Vue.prototype.$EventBus = window.$EventBus;

Vue.mixin({
    methods: {
        // 언어화 기능
        __(key, replace = {}) {
            window.__ = window.__ || {};

            let translation = key
                .split(".")
                .reduce((t, i) => t[i] || null, window.__);

            for (var placeholder in replace) {
                translation = translation.replace(
                    `:${placeholder}`,
                    replace[placeholder]
                );
            }

            return translation;
        }
    }
});

/**
 * Vue Router Settings
 */
const routes = require("./routes/web").default;
const router = new VueRouter({
    routes
});

/**
 * Vuex Settings
 */
const states = require("./store/states").default;
const store = new Vuex.Store(states);

/**
 * Auth
 */
// 이전에 발급받은 토큰이 있으면 axios 헤더에 추가
if (localStorage.passportToken) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${
        localStorage.passportToken
    }`;
}

// axios에서 만료된 토큰으로 데이터 요청 시 세션 만료 표시
Vue.swal.isOpen = false;
axios.interceptors.response.use(
    function(response) {
        return response;
    },
    function(error) {
        if (401 === error.response.status) {
            if (
                localStorage.passportToken ||
                error.response.config.url !== "/api/login"
            ) {
                if (!Vue.swal.isOpen) {
                    Vue.swal.isOpen = true;
                    Vue.swal({
                        title: __.system.swal_session_expired,
                        text: __.system.swal_session_expired_text,
                        type: "warning",
                        onClose: () => {
                            Vue.swal.isOpen = false;
                        }
                    });
                }
            }
            localStorage.removeItem("passportToken");
            axios.defaults.headers.common["Authorization"] = undefined;
            router.replace("/");
        } else if (error.code === "ECONNABORTED") {
            if (!Vue.swal.isOpen) {
                Vue.swal.isOpen = true;
                Vue.swal({
                    title: __.system.swal_connection_timeout,
                    text: __.system.swal_connection_timeout_text,
                    type: "warning",
                    onClose: () => {
                        Vue.swal.isOpen = false;
                    }
                });
            }
            localStorage.removeItem("passportToken");
            axios.defaults.headers.common["Authorization"] = undefined;
            router.replace("/");
        }

        return Promise.reject(error);
    }
);

// 화면 새로고침 시 전역 회원정보가 없으면 로딩
router.beforeEach(async (to, from, next) => {
    if (localStorage.passportToken) {
        if (!store.state.detail.user) {
            try {
                const datas = (await Promise.all([
                    axios.get(`/api/detail`),
                    axios.get(`/api/wallet/info/${store.state.selectedCoin}`),
                    axios.get(`/api/wallet/history`, {
                        params: {
                            symbol: store.state.selectedCoin,
                            days: store.state.selectedCoinHistoryTotalDuration
                        }
                    })
                ])).map(response => {
                    return response.data;
                });

                store.commit("detail", datas[0]);
                store.commit("selectedCoinInfo", datas[1]);
                store.commit("selectedCoinHistorys", datas[2]);
            } catch (e) {
                localStorage.removeItem("passportToken");
                axios.defaults.headers.common["Authorization"] = undefined;
                store.commit("progressComponentHide");
                next("/login");
            }
        }
    }
    next();
});

// axios에서 네트워크 요청 시 전역 로딩 표시
axios.interceptors.request.use(
    function(config) {
        return config;
    },
    function(error) {
        return Promise.reject(error);
    }
);

axios.interceptors.response.use(
    function(response) {
        return response;
    },
    function(error) {
        return Promise.reject(error);
    }
);

/**
 * App Components
 */
const ProgressComponent = require("./components/common/ProgressComponent.vue")
    .default;
const HeaderComponent = require("./components/common/HeaderComponent.vue")
    .default;

/**
 * App Mount
 */
const app = new Vue({
    router,
    store,
    components: {
        "progress-component": ProgressComponent,
        "header-component": HeaderComponent
    },
    beforeCreate() {
        this.$EventBus.$on("push-token-request", event => {
            try {
                window.callPushToken2();
            } catch (e) {}
        });
        this.$EventBus.$on("push-token-result", event => {
            axios
                .put(`/api/detail`, {
                    push_token: event.token
                })
                .then(response => {
                    console.log(response.data);
                })
                .catch(response => {
                    console.log(response.error);
                });
        });
        this.$EventBus.$on("qr-scan-request", event => {
            try {
                window.callAndroid();
            } catch (e) {}
        });
        this.$EventBus.$on("pay-scan-request", event => {
            try {
                window.callAllpay();
            } catch (e) {}
        });
    },
    beforeDestroy() {
        this.$EventBus.$off("push-token-request");
        this.$EventBus.$off("push-token-result");
        this.$EventBus.$off("qr-scan-request");
        this.$EventBus.$off("pay-scan-request");
    }
}).$mount("#app");

// 주기적으로 액세스 토큰 갱신
const refreshInterval = 3 * 60 * 60 * 1000;
const refresh = async () => {
    try {
        if (localStorage.passportToken) {
            const response = await axios.post("/api/refresh");
            const token = response.data.token;

            localStorage.passportToken = token;
            axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        }
    } finally {
        setTimeout(() => refresh(), refreshInterval);
    }
};
setTimeout(() => refresh(), 3000);
