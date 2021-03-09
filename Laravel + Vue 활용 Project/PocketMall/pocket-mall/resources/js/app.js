require("./bootstrap");
import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import moment from "moment";
import VueMomentJS from "vue-momentjs";
import "./axios/axios";
import "./mixin/mixin";
import "./lib/ComponentLoader";
import Calendar from "v-calendar/lib/components/calendar.umd";
import DatePicker from "v-calendar/lib/components/date-picker.umd";

import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-default.css";
import "vue-toast-notification/dist/theme-sugar.css";
import "./../style/components/swal.css";

window.Vue = Vue;
Vue.prototype.$bus = new Vue();

Vue.component("calendar", Calendar);
Vue.component("date-picker", DatePicker);
Vue.use(VueMomentJS, moment);
Vue.use(VueSweetalert2);
Vue.use(VueToast);

// 새로고침시 CartReset
console.log(window.location.pathname);
if (!window.location.pathname.includes("check-cart")) {
    store.commit("CartReset");
}

store.commit("QuestionCompFalse");

new Vue({
    router,
    store,
    components: {
        Calendar,
        DatePicker
    },
    render: h => h(App)
}).$mount("#app");
