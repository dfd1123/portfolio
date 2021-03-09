import Vue from "vue";
import Vuex from "vuex";
import mixin from "../mixin/mixin";
import createPersistedState from "vuex-persistedstate";

import VueToast from "vue-toast-notification";
import "vue-toast-notification/dist/theme-default.css";
import "vue-toast-notification/dist/theme-sugar.css";

Vue.use(Vuex);
Vue.use(VueToast);

export default new Vuex.Store({
    state: {
        categorys: [],
        carts: [],
        itemOptions: [],
        totalPrice: 0,
        openQuestion: false
    },
    plugins: [createPersistedState()],
    mutations: {
        CategorySet(state, categorys) {
            state.categorys = categorys;
        },
        CartAdd(state, item) {
            if (item && !state.carts.includes(item)) {
                let duple = false;
                state.carts.forEach(cart => {
                    if (cart.option.op_id === item.option.op_id) {
                        duple = true;
                        mixin.methods.NotifiError(
                            "이미 중복된 상품이 있습니다."
                        );
                    }
                });

                if (!duple) {
                    state.carts.push(item);
                    state.itemOptions.push(item.option);

                    let tempTotalPrice = 0;
                    state.carts.forEach(cart => {
                        tempTotalPrice += cart.total_price;
                    });

                    if (!mixin.methods.IsMobile()) {
                        mixin.methods.NotifiSuccess(
                            "장바구니에 추가되었습니다."
                        );
                    }

                    state.totalPrice = tempTotalPrice;
                }
            }
        },
        CartDel(state, arg) {
            const itemToFind = state.itemOptions.find(function(item) {
                return item.op_id === arg;
            });

            const idx = state.itemOptions.indexOf(itemToFind);

            const delItemPrice =
                state.itemOptions[idx].sale_price +
                state.itemOptions[idx].op_sale_price;

            if (idx > -1) {
                state.itemOptions.splice(idx, 1);
                state.carts.splice(idx, 1);
            }

            mixin.methods.NotifiDefault("삭제되었습니다.");

            state.totalPrice = state.totalPrice - delItemPrice;
        },
        CartReset(state) {
            state.carts = [];
            state.itemOptions = [];
            state.totalPrice = 0;
        },
        QuestionCompTrue(state){
            state.openQuestion = true
        },
        QuestionCompFalse(state){
            state.openQuestion = false
        }
    },
    actions: {},
    modules: {},
    strict: false
});
