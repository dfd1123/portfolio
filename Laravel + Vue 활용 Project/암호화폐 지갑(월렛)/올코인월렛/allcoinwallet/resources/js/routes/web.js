import LoginView from "../views/LoginView.vue";
// import RegisterKindView from "../views/RegisterKindView.vue";
// import RegisterAgreeView from "../views/RegisterAgreeView";
// import RegisterView from "../views/RegisterView.vue";
// import RegisterEmptyView from "../views/RegisterEmptyView.vue";
// import RegisterExistingView from "../views/RegisterExistingView";
import HomeView from "../views/HomeView.vue";
import UserInfoView from "../views/UserInfoView.vue";
import UserPasswordChangeView from "../views/UserPasswordChangeView.vue";
import UserSecretKeyChangeView from "../views/UserSecretKeyChangeView.vue";
import UserSecretKeyVerifyView from "../views/UserSecretKeyVerifyView.vue";
import UserSecretKeyInputView from "../views/UserSecretKeyInputView.vue";
import UserSecurityDocumentView from "../views/UserSecurityDocumentView.vue";
import UserSecurityAccountView from "../views/UserSecurityAccountView.vue";
import UserSecurityWaitingView from "../views/UserSecurityWaitingView.vue";
import WalletAddView from "../views/WalletAddView.vue";
import WalletSendView from "../views/WalletSendView.vue";
import WalletFindUserView from "../views/WalletFindUserView.vue";
import WalletInputAddressView from "../views/WalletInputAddressView.vue";
import WalletReceiveView from "../views/WalletReceiveView.vue";
import WalletBuyView from "../views/WalletBuyView.vue";
import WalletBuyCoinSelectView from "../views/WalletBuyCoinSelectView.vue";
import WalletSendStatusView from "../views/WalletSendStatusView.vue";
import WalletSellView from "../views/WalletSellView.vue";
import WalletSellCoinSelectView from "../views/WalletSellCoinSelectView.vue";
import DepositView from "../views/DepositView.vue";
import WithdrawView from "../views/WithdrawView.vue";
// import CustomerServiceView from "../views/CustomerServiceView.vue";
// import CustomerServiceNoticeView from "../views/CustomerServiceNoticeView.vue";
// import CustomerServiceQnaCreateView from "../views/CustomerServiceQnaCreateView.vue";
// import CustomerServiceQnaStatusView from "../views/CustomerServiceQnaStatusView.vue";
// import CustomerServiceQnaView from "../views/CustomerServiceQnaView.vue";
// import CustomerServiceTosView from "../views/CustomerServiceTosView.vue";
import TestView from "../views/TestView.vue";

export default [
    {
        name: "test",
        path: "/test",
        component: TestView
    },
    {
        path: "/",
        redirect: "/login"
    },
    {
        name: "login",
        path: "/login",
        component: LoginView
    },
    {
        name: "register_kind",
        path: "/register_kind",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_register' */ "../views/RegisterKindView.vue"
            )
    },
    {
        name: "register_agree",
        path: "/register_agree",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_register' */ "../views/RegisterAgreeView"
            )
    },
    {
        name: "register",
        path: "/register",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_register' */ "../views/RegisterView.vue"
            )
    },
    {
        name: "register_empty",
        path: "/register_empty",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_register' */ "../views/RegisterEmptyView.vue"
            )
    },
    {
        name: "register_existing",
        path: "/register_existing",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_register' */ "../views/RegisterExistingView"
            )
    },
    {
        name: "home",
        path: "/home",
        component: HomeView
    },
    {
        name: "user_info",
        path: "/user_info",
        component: UserInfoView
    },
    {
        name: "user_password_change",
        path: "/user_password_change",
        component: UserPasswordChangeView
    },
    {
        name: "user_secret_key_change",
        path: "/user_secret_key_change",
        component: UserSecretKeyChangeView
    },
    {
        name: "user_secret_key_verify",
        path: "/user_secret_key_verify",
        component: UserSecretKeyVerifyView
    },
    {
        name: "user_secret_key_input",
        path: "/user_secret_key_input",
        component: UserSecretKeyInputView
    },
    {
        name: "user_security_document",
        path: "/user_security_document",
        component: UserSecurityDocumentView
    },
    {
        name: "user_security_account",
        path: "/user_security_account",
        component: UserSecurityAccountView
    },
    {
        name: "user_security_waiting",
        path: "/user_security_waiting",
        component: UserSecurityWaitingView
    },
    {
        name: "wallet_add",
        path: "/wallet_add",
        component: WalletAddView
    },
    {
        name: "wallet_send",
        path: "/wallet_send",
        component: WalletSendView
    },
    {
        name: "wallet_find_user",
        path: "/wallet_find_user",
        component: WalletFindUserView
    },
    {
        name: "wallet_input_address",
        path: "/wallet_input_address",
        component: WalletInputAddressView
    },
    {
        name: "wallet_receive",
        path: "/wallet_receive",
        component: WalletReceiveView
    },
    {
        name: "wallet_buy",
        path: "/wallet_buy",
        component: WalletBuyView
    },
    {
        name: "wallet_buy_coin_select",
        path: "/wallet_buy_coin_select",
        component: WalletBuyCoinSelectView
    },
    {
        name: "wallet_send_status",
        path: "/wallet_send_status",
        component: WalletSendStatusView
    },
    {
        name: "wallet_sell",
        path: "/wallet_sell",
        component: WalletSellView
    },
    {
        name: "wallet_sell_coin_select",
        path: "/wallet_sell_coin_select",
        component: WalletSellCoinSelectView
    },
    {
        name: "deposit",
        path: "/deposit",
        component: DepositView
    },
    {
        name: "withdraw",
        path: "/withdraw",
        component: WithdrawView
    },
    {
        name: "customer_service",
        path: "/customer_service",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_customer_service' */ "../views/CustomerServiceView.vue"
            )
    },
    {
        name: "customer_service_notice",
        path: "/customer_service_notice",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_customer_service' */ "../views/CustomerServiceNoticeView.vue"
            )
    },
    {
        name: "customer_service_qna_create",
        path: "/customer_service_qna_create",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_customer_service' */ "../views/CustomerServiceQnaCreateView.vue"
            )
    },
    {
        name: "customer_service_qna_status",
        path: "/customer_service_qna_status",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_customer_service' */ "../views/CustomerServiceQnaStatusView.vue"
            )
    },
    {
        name: "customer_service_qna",
        path: "/customer_service_qna",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_customer_service' */ "../views/CustomerServiceQnaView.vue"
            )
    },
    {
        name: "customer_service_tos",
        path: "/customer_service_tos",
        component: () =>
            import(
                /* webpackChunkName: 'js/group_customer_service' */ "../views/CustomerServiceTosView.vue"
            )
    }
];
