const DEFAULT_COIN = "ppc";
const ORDERABLE_COINS = [
    { id: 1, symbol: "btc" },
    { id: 2, symbol: "eth" },
    { id: 3, symbol: "dash" },
    { id: 4, symbol: "ltc" },
    { id: 5, symbol: "btg" },
];

function initialState() {
    return {
        isProgressComponentVisible: false,
        detail: {},
        favors: [],
        asset: {},
        selectedCoin: DEFAULT_COIN,
        selectedCoinInfo: {},
        selectedCoinHistorys: [],
        selectedCoinHistoryTotalDuration: 14,
        payScanData: "",
        orderableCoins: ORDERABLE_COINS,
        walletListScrollLeft: 0,
        walletSendViewData: {
            selectedUser: {
                address: ""
            },
            selectedSearch: "",
            externalAddress: "",
            sendAmount: 0
        },
        walletBuyCoinSelectViewData: {
            selectedCoin: DEFAULT_COIN,
            buyAmount: 0
        },
        walletSellCoinSelectViewData: {
            selectedCoin: DEFAULT_COIN,
            sellAmount: 0
        },
        customerServiceViewData: {
            selected: "notice",
            notices: [],
            faqs: [],
            qnas: []
        },
        walletSendStatusViewData: {
            symbol: "",
            amount: 0,
            address: ""
        },
        registerExistingViewData: {
            email: "",
            password: "",
            secretKey: "",
            secretKeyConfirm: ""
        },
        registerViewData: {
            country: "kr",
            fullname: "",
            mobileNumber: "0000",
            mobileNumberAuthVerify: true,
            email: "",
            emailAuthVerify: false,
            password: "",
            passwordConfirm: "",
            secretKey: "",
            secretKeyConfirm: ""
        }
    };
}

export default {
    state: initialState(),
    mutations: {
        reset(state) {
            const init = initialState();
            Object.keys(init).forEach(key => {
                state[key] = init[key];
            });
        },
        progressComponentShow(state) {
            state.isProgressComponentVisible = true;
        },
        progressComponentHide(state) {
            state.isProgressComponentVisible = false;
        },
        detail(state, detail) {
            state.detail = detail;
        },
        favors(state, favors) {
            state.favors = favors;
        },
        updateFavors(state, { index, value }) {
            state.favors.splice(index, 1, value);
        },
        asset(state, asset) {
            state.asset = asset;
        },
        selectedCoin(state, selectedCoin) {
            state.selectedCoin = selectedCoin;
        },
        selectedCoinInfo(state, selectedCoinInfo) {
            state.selectedCoinInfo = selectedCoinInfo;
        },
        selectedCoinHistorys(state, selectedCoinHistorys) {
            state.selectedCoinHistorys = selectedCoinHistorys;
        },
        selectedCoinHistoryTotalDuration(
            state,
            selectedCoinHistoryTotalDuration
        ) {
            state.selectedCoinHistoryTotalDuration = selectedCoinHistoryTotalDuration;
        },
        updateWalletSendViewData(state, data) {
            state.walletSendViewData = data;
        },
        mergeWalletSendViewData(state, data) {
            state.walletSendViewData = { ...state.walletSendViewData, ...data };
        },
        updateWalletBuyCoinSelectViewData(state, data) {
            state.walletBuyCoinSelectViewData = data;
        },
        updateWalletSellCoinSelectViewData(state, data) {
            state.walletSellCoinSelectViewData = data;
        },
        updateCustomerServiceViewData(state, data) {
            state.customerServiceViewData = data;
        },
        mergeCustomerServiceViewData(state, data) {
            state.customerServiceViewData = {
                ...state.customerServiceViewData,
                ...data
            };
        },
        updateWalletSendStatusViewData(state, data) {
            state.walletSendStatusViewData = data;
        },
        updateRegisterExistingViewData(state, data) {
            state.registerExistingViewData = data;
        },
        updateRegisterViewData(state, data) {
            state.registerViewData = data;
        },
        mergeRegisterViewData(state, data) {
            state.registerViewData = { ...state.registerViewData, ...data };
        },
        updatePayScanData(state, data) {
            state.payScanData = data;
        },
        updateWalletListScrollLeft(state, data) {
            state.walletListScrollLeft = data;
        }
    }
};
