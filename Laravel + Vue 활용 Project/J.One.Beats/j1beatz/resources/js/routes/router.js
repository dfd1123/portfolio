import VueRouter from "vue-router";
import store from "../stores/store";
import mixin from "../mixin";
//import TestView from "../views/TestView";

// 사용자 화면
import MainView from "../views/MainView";
//import LoginView from "../views/LoginView";
//import RegisterView from "../views/RegisterView";
//import FindIdView from "../views/FindIdView";
//import FindPwView from "../views/FindPwView";
//import ChangePwView from "../views/ChangePwView";
//import RegisterProducerView from "../views/RegisterProducerView";
//import RegisterProducerReviewView from "../views/RegisterProducerReviewView";
//import ProducerInfoView from "../views/ProducerInfoView";
//import BeatInfoView from "../views/BeatInfoView";

// 이용권 구매
//import LicenseInfoView from "../views/LicenseInfoView";
//import LicenseBuyView from "../views/LicenseBuyView";
//import LicenseBuyResultView from "../views/LicenseBuyResultView";

// 비트 구매
//import BeatCartView from "../views/BeatCartView";
//import BeatBuyView from "../views/BeatBuyView";
//import BeatBuyResultView from "../views/BeatBuyResultView";

// 결제완료 팝업 화면
//import PerchacedPopupView from "../views/PerchacedPopupView";

// 휴대폰 실명인증 팝업 화면
//import MobileAuthPopupView from "../views/MobileAuthPopupView";

// 순위, 검색
//import RankView from "../views/RankView";
//import MoodRankView from "../views/MoodRankView";
//import GenreRankView from "../views/GenreRankView";
//import ProducerRankView from "../views/ProducerRankView";
//import SearchBasicView from "../views/SearchBasicView";
//import SearchTagView from "../views/SearchTagView";

// 마이페이지
//import MyPage01View from "../views/MyPage01View";
//import MyPage02View from "../views/MyPage02View";
//import MyPage03View from "../views/MyPage03View";
//import MyPage03_1View from "../views/MyPage03_1View";
//import MyPage04View from "../views/MyPage04View";
//import MyPage05View from "../views/MyPage05View";

//고객센터
//import NoticeView from "../views/NoticeView";
//import FaqView from "../views/FaqView";
//import QnaView from "../views/QnaView";

// 프로듀서 설정 화면
//import ManageProducerMainView from "../views/ManageProducerMainView";
//import ManageProducerBeatView from "../views/ManageProducerBeatView";
//import ManageProducerBeatRegistView from "../views/ManageProducerBeatRegistView";
//import ManageProducerBeatDetailView from "../views/ManageProducerBeatDetailView";
//import ManageProducerMyPageMainView from "../views/ManageProducerMyPageMainView";
//import ManageProducerMyPageUpdateView from "../views/ManageProducerMyPageUpdateView";
//import ManageProducerSettlementMainView from "../views/ManageProducerSettlementMainView";

const router = new VueRouter({
    mode: "history",
    routes: [
        /*{
            path: "/test",
            component: TestView
        },*/
        {
            path: "/",
            redirect: "main"
        },
        {
            path: "/main",
            name: "main",
            component: MainView,
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/login",
            name: "login",
            //component: LoginView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/login_view' */ "../views/LoginView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (store.getters.isUser) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/register",
            name: "register",
            //component: RegisterView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/register-view' */ "../views/RegisterView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: false,
                usePlaylist: false
            }
        },
        {
            path: "/find-id",
            name: "find-id",
            //component: FindIdView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/find-id-view' */ "../views/FindIdView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: false,
                usePlaylist: false
            }
        },
        {
            path: "/find-pw",
            name: "find-pw",
            //component: FindPwView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/find-pw-view' */ "../views/FindPwView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: false,
                usePlaylist: false
            }
        },
        {
            path: "/change-pw",
            name: "change-pw",
            //component: ChangePwView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/change-pw-view' */ "../views/ChangePwView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (store.getters.isUser) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/register-producer",
            name: "register-producer",
            //component: RegisterProducerView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/register-producer-view' */ "../views/RegisterProducerView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (
                    store.getters.isProducer ||
                    store.getters.isWaitingProducer ||
                    store.getters.isRejectedProducer ||
                    store.getters.isSuspendedProducer
                ) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/register-producer-review",
            name: "register-producer-review",
            //component: RegisterProducerReviewView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/register-producer-review-view' */ "../views/RegisterProducerReviewView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (
                    !store.getters.isWaitingProducer &&
                    !store.getters.isRejectedProducer
                ) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/producer-info/:id",
            name: "producer-info",
            //component: ProducerInfoView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/producer-info-view' */ "../views/ProducerInfoView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/beat-info/:id",
            name: "beat-info",
            //component: BeatInfoView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/beat-info-view' */ "../views/BeatInfoView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/license-info",
            name: "license-info",
            //component: LicenseInfoView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/license-info-view' */ "../views/LicenseInfoView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/license-buy",
            name: "license-buy",
            //component: LicenseBuyView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/license-buy-view' */ "../views/LicenseBuyView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            }
        },
        {
            path: "/license-buy-result",
            name: "license-buy-result",
            //component: LicenseBuyResultView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/license-buy-result-view' */ "../views/LicenseBuyResultView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            }
        },
        {
            path: "/beat-cart",
            name: "beat-cart",
            //component: BeatCartView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/beat-cart-view' */ "../views/BeatCartView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            }
        },
        {
            path: "/beat-buy",
            name: "beat-buy",
            //component: BeatBuyView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/beat-buy-view' */ "../views/BeatBuyView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            }
        },
        {
            path: "/beat-buy-result",
            name: "beat-buy-result",
            //component: BeatBuyResultView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/beat-buy-result-view' */ "../views/BeatBuyResultView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            }
        },
        {
            path: "/rank",
            name: "rank",
            //component: RankView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/rank-view' */ "../views/RankView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/mood-rank/:id",
            name: "mood-rank",
            //component: MoodRankView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/mood-rank-view' */ "../views/MoodRankView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/genre-rank/:id",
            name: "genre-rank",
            //component: GenreRankView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/genre-rank-view' */ "../views/GenreRankView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/producer-rank",
            name: "producer-rank",
            //component: ProducerRankView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/producer-rank-view' */ "../views/ProducerRankView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/search-basic",
            name: "search-basic",
            //component: SearchBasicView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/search-basic-view' */ "../views/SearchBasicView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/search-tag",
            name: "search-tag",
            //component: SearchTagView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/search-tag-view' */ "../views/SearchTagView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        // 마이페이지
        {
            path: "/my-page-01",
            name: "my-page-01",
            //component: MyPage01View,
            component: () =>
                import(
                    /* webpackChunkName: 'js/my-page-01-view' */ "../views/MyPage01View"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/my-page-02",
            name: "my-page-02",
            //component: MyPage02View,
            component: () =>
                import(
                    /* webpackChunkName: 'js/my-page-02-view' */ "../views/MyPage02View"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/my-page-03",
            name: "my-page-03",
            //component: MyPage03View,
            component: () =>
                import(
                    /* webpackChunkName: 'js/my-page-03-view' */ "../views/MyPage03View"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/my-page-03-1/:id",
            name: "my-page-03-1",
            //component: MyPage03_1View,
            component: () =>
                import(
                    /* webpackChunkName: 'js/my-page-03-1-view' */ "../views/MyPage03_1View"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/my-page-04",
            name: "my-page-04",
            //component: MyPage04View,
            component: () =>
                import(
                    /* webpackChunkName: 'js/my-page-04-view' */ "../views/MyPage04View"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/my-page-05",
            name: "my-page-05",
            //component: MyPage05View,
            component: () =>
                import(
                    /* webpackChunkName: 'js/my-page-05-view' */ "../views/MyPage05View"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        //고객센터
        {
            path: "/notice",
            name: "notice",
            //component: NoticeView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/notice-view' */ "../views/NoticeView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/faq",
            name: "faq",
            //component: FaqView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/faq-view' */ "../views/FaqView"
                ),
            meta: {
                requiresAuth: false,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        {
            path: "/qna",
            name: "qna",
            //component: QnaView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/qna-view' */ "../views/QnaView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: true,
                usePlaylist: true
            }
        },
        // 프로듀서 설정 화면
        {
            path: "/manage-producer-main",
            name: "manage-producer-main",
            //component: ManageProducerMainView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/manage-producer-main-view' */ "../views/ManageProducerMainView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (!store.getters.isProducer) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/manage-producer-beat",
            name: "manage-producer-beat",
            //component: ManageProducerBeatView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/manage-producer-beat-view' */ "../views/ManageProducerBeatView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (!store.getters.isProducer) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/manage-producer-beat-regist",
            name: "manage-producer-beat-regist",
            //component: ManageProducerBeatRegistView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/manage-producer-beat-regist-view' */ "../views/ManageProducerBeatRegistView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (!store.getters.isProducer) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/manage-producer-beat-detail/:id",
            name: "manage-producer-beat-detail",
            //component: ManageProducerBeatDetailView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/manage-producer-beat-detail-view' */ "../views/ManageProducerBeatDetailView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (!store.getters.isProducer) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/manage-producer-my-page-main",
            name: "manage-producer-my-page-main",
            //component: ManageProducerMyPageMainView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/manage-producer-my-page-main-view' */ "../views/ManageProducerMyPageMainView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (!store.getters.isProducer) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/manage-producer-my-page-update",
            name: "manage-producer-my-page-update",
            //component: ManageProducerMyPageUpdateView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/manage-producer-my-page-update-view' */ "../views/ManageProducerMyPageUpdateView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (!store.getters.isProducer) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/manage-producer-settlement-main",
            name: "manage-producer-settlement-main",
            //component: ManageProducerSettlementMainView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/manage-producer-settlement-main-view' */ "../views/ManageProducerSettlementMainView"
                ),
            meta: {
                requiresAuth: true,
                usePlaybar: false,
                usePlaylist: false
            },
            beforeEnter: (to, from, next) => {
                if (!store.getters.isProducer) {
                    next("main");
                } else {
                    next();
                }
            }
        },
        {
            path: "/perchaced-popup",
            name: "perchaced-popup",
            //component: PerchacedPopupView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/perchaced-popup-view' */ "../views/PerchacedPopupView"
                ),
            meta: {
                requiresAuth: true
            }
        },
        {
            path: "/mobile-auth-popup",
            name: "mobile-auth-popup",
            //component: MobileAuthPopupView,
            component: () =>
                import(
                    /* webpackChunkName: 'js/mobile-auth-popup-view' */ "../views/MobileAuthPopupView"
                ),
            meta: {
                requiresAuth: false
            }
        },
        // 404 Page
        {
            path: "*",
            component: () => {
                // 라라벨 라우팅에 있는 /404 페이지로 이동
                window.location.replace("/404");
                // 아니면 SPA 404 페이지 만들어서 띄우기
            }
        }
    ]
});

router.beforeEach(async (to, from, next) => {
    // 사용자 인증 정보 체크
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (from.name !== null) {
            await mixin.methods.__info();
        }

        // 로그인된 사용자가 아니면 로그인 화면으로 이동
        if (!store.getters.isUser) {
            router.push({
                name: "login",
                params: {
                    redirect: { ...to }
                }
            });
        }

        return next();
    }

    if (from.matched.some(record => record.meta.requiresAuth)) {
        if (from.name !== null) {
            await mixin.methods.__info();
        }

        return next();
    }

    next();
});

router.afterEach(to => {
    // 화면 별 재생바 숨김/보이기
    store.commit(
        "updateIsPlaybarVisible",
        to.matched.some(record => record.meta.usePlaybar)
    );

    // 화면 별 재생목록 숨김/보이기 (비회원은 무조건 숨김)
    store.commit(
        "updateIsPlaylistVisible",
        to.matched.some(record => record.meta.usePlaylist)
    );

    setTimeout(() => {
        window.scrollTo(0, 0);
    });
});

// Vue Router에서 동적 로딩 실패 시 새로고침
router.onError(error => {
    if (/Loading chunk .* failed./i.test(error.message)) {
        window.location.reload();
        return;
    }

    throw error;
});

export default router;
