import Vue from "vue";
import VueRouter from "vue-router";

import Home from "../views/Home.vue";
import CheckCartPage from '../views/CheckCartPage.vue'
import CheckInvoicePage from '../views/CheckInvoicePage.vue'

Vue.use(VueRouter);

const routerPush = VueRouter.prototype.push;
VueRouter.prototype.push = function push(location) {
    return routerPush.call(this, location).catch(error => error);
};

const routerReplace = VueRouter.prototype.replace;
VueRouter.prototype.replace = function replace(location) {
    return routerReplace.call(this, location).catch(error => error);
};

let routes = [];

routes = [
    { path: "/", redirect: "/home" },
    {
        // 메인페이지
        path: "/home",
        name: "home",
        component: Home,
        meta: { authRequired: false }
    },
    {
        // 장바구니확인 페이지
        path: "/check-cart",
        name: "check-cart",
        component: CheckCartPage
    },
    {
        // 견적서확인 페이지
        path: "/check-invoice",
        name: "check-invoice",
        component: CheckInvoicePage
    }
];

const router = new VueRouter({
    mode: "history",
    routes,
    scrollBehavior: (to, from, savedPosition) => {
        if (savedPosition) {
            return savedPosition;
        } else if (to.hash) {
            return {
                selector: to.hash
            };
        } else {
            return { x: 0, y: 0 };
        }
    }
});

export default router;
