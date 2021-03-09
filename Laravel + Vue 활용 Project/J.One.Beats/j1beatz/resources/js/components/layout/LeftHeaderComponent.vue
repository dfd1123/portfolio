<template>
    <header>
        <input
            id="check-header-btn"
            type="checkbox"
            class="none-input"
        >
        <!-- 좌측고정 네비게이션 -->
        <div
            id="header"
            class="header"
        >
            <h1 class="logo">
                <a
                    href="#"
                    @click.prevent="$router.push('/main', () => {})"
                >
                    <img
                        src="/img/logo/logo__wh.svg"
                        alt="j1beats logo"
                        style="width: 180px;"
                    >
                </a>
            </h1>

            <div
                class="header-scroll-area"
            >
                <div
                    id="gnb01"
                    class="gnb01"
                >
                    <nav
                        id="gnb1_navigation"
                        class="gnb01-nav"
                    >
                        <ul>
                            <li
                                class="gnb01-list"
                                :class="{'active-msg': isUserIndicator}"
                            >
                                <a
                                    href="#"
                                    @click.prevent
                                >
                                    <span class="icon btn_01_mypage" />
                                    <span class="tt">마이페이지</span>
                                </a>
                            </li>
                            <li class="gnb01-list">
                                <a
                                    href="#"
                                    @click.prevent
                                >
                                    <span class="icon btn_02_cart" />
                                    <span class="tt">장바구니</span>
                                </a>
                            </li>
                            <li class="gnb01-list">
                                <a
                                    href="#"
                                    @click.prevent
                                >
                                    <span class="icon btn_03_follow" />
                                    <span class="tt">팔로우</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <div class="gnb01-lnb">
                        <!-- 마이페이지 -->
                        <div
                            v-if="isUserMenuUpdated"
                            class="gnb01-lnb-con __mypage"
                        >
                            <!-- CASE 1) 비회원인 경우 -->
                            <div
                                v-show="!isUser"
                                class="case_1"
                            >
                                <a
                                    href="#"
                                    class="btn btn-01"
                                    role="button"
                                    @click.prevent="$router.push('/login', () => {})"
                                >로그인</a>
                                <ul class="lnb01 before_login">
                                    <li>
                                        <a
                                            href="#"
                                            role="button"
                                            @click.prevent="$router.push('/register', () => {})"
                                        >회원가입</a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            role="button"
                                            @click.prevent="$router.push('/license-info', () => {})"
                                        >이용권 정보</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- end CASE 1 -->

                            <!-- CASE 2) 회원인 경우 -->
                            <div
                                v-show="isUser"
                                class="case_2"
                            >
                                <div class="my_info">
                                    <p class="my_id">
                                        {{ user.user_email }}
                                    </p>
                                    <div
                                        v-if="user.license.lcens_type === undefined"
                                        class="my_using"
                                    >
                                        사용중인 이용권이 없습니다.
                                        <br>
                                        <button
                                            type="button"
                                            class="btn"
                                            @click.prevent="$router.push('/license-info', () => {})"
                                        >
                                            이용권 구매하기
                                        </button>
                                    </div>
                                    <div
                                        v-else
                                        class="my_using"
                                    >
                                        사용중인 이용권: {{ user.license.lcens_name }}
                                    </div>
                                </div>
                                <ul class="lnb01 after_login">
                                    <li>
                                        <a
                                            href="#"
                                            @click.prevent="$router.push('/my-page-01', () => {})"
                                        >이용권 관리</a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            @click.prevent="$router.push('/my-page-02', () => {})"
                                        >주문내역</a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            @click.prevent="$router.push('/my-page-03', () => {})"
                                        >마이앨범</a>
                                    </li>
                                    <li :class="{'active-msg': isNewAlarmMsg}">
                                        <a
                                            href="#"
                                            @click.prevent="$router.push('/my-page-04', () => {})"
                                        >알림 메세지</a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            @click.prevent="$router.push('/my-page-05', () => {})"
                                        >나의 정보관리</a>
                                    </li>
                                    <li>
                                        <a
                                            href="#"
                                            @click.prevent="logout"
                                        >로그아웃</a>
                                    </li>
                                </ul>
                            </div>
                        <!-- end CASE 2 -->
                        </div>
                        <!-- end 마이페이지 -->

                        <!-- 장바구니 -->
                        <div
                            v-if="isCartMenuUpdated"
                            class="gnb01-lnb-con __cart"
                        >
                            <!-- CASE 1) 로그인 안했을 때 -->
                            <div
                                v-if="!isUser"
                                class="case_1 no_item"
                            >
                                <span>로그인이 필요한 기능입니다.</span>
                            </div>
                            <!-- end CASE 1 -->

                            <!-- CASE 1 - 2) 장바구니 화면일 때 -->
                            <div
                                v-else-if="this.$route.name === 'beat-cart' || this.$route.name === 'beat-buy' || this.$route.name === 'beat-result'"
                                class="case_1 no_item"
                            >
                                <span>현재 장바구니 화면이 열려있습니다.</span>
                            </div>
                            <!-- CASE 1 - 2) 장바구니 화면일 때 -->

                            <!-- CASE 2) 로그인 안했을 때 -->
                            <div
                                v-else-if="cartlist.length === 0"
                                class="case_1 no_item"
                            >
                                <span>담은 곡이 없습니다.</span>
                            </div>
                            <!-- end CASE 2 -->

                            <!-- CASE 3) 장바구니 담겼을 때 -->
                            <div
                                v-else
                                class="case_2"
                            >
                                <div class="more_info">
                                    <span class="left">최근 담은 곡</span>
                                    <span class="right">
                                        <a
                                            href="#"
                                            @click.prevent="$router.push('/beat-cart', () => {})"
                                        >자세히 ></a>
                                    </span>
                                </div>
                                <ul class="lnb02 beat-ul">
                                    <li
                                        v-for="(cart,index) in recentCartlist"
                                        :key="cart.cart_id"
                                        class="beat-ul-list"
                                    >
                                        <input
                                            :id="`check-orderlist${index}`"
                                            v-model="cart.checked"
                                            type="checkbox"
                                            class="input-style-02"
                                        >
                                        <label :for="`check-orderlist${index}`">
                                            <span class="none">장바구니 리스트 담기</span>
                                        </label>
                                        <label>
                                            <img
                                                :src="`/fdata/beathumb/${cart.beat_thumb}`"
                                                alt="thumnail image"
                                                class="thumb-mini"
                                                :style="{'background-image': `url(/fdata/beathumb/${cart.beat_thumb})`}"
                                            >
                                            <span class="song_title">{{ cart.beat_title }}</span>
                                            <small>{{ cart.prdc_nick }}</small>
                                        </label>
                                    </li>
                                </ul>
                                <div class="btns">
                                    <input
                                        type="button"
                                        class="btn btn-02"
                                        value="선택삭제"
                                        @click="removeSelectedRecentCart"
                                    >
                                    <a
                                        href="#"
                                        class="btn btn-01"
                                        role="button"
                                        @click.prevent="$router.push('/beat-cart', () => {})"
                                    >구매하기</a>
                                </div>
                            </div>
                            <!-- end CASE 3 -->
                        </div>
                        <!-- end 장바구니 -->

                        <!-- 팔로우 -->
                        <div
                            v-if="isFollowMenuUpdated"
                            class="gnb01-lnb-con __flw"
                        >
                            <!-- CASE 1) 로그인 안했을 때 -->
                            <div
                                v-if="!isUser"
                                class="pd_no no_item"
                            >
                                <span>로그인이 필요한 기능입니다.</span>
                            </div>
                            <!-- end CASE 1 -->

                            <!-- CASE 2) 프로듀서 없을 때 -->
                            <div
                                v-else-if="followlist.length === 0"
                                class="pd_no no_item"
                            >
                                <span>팔로우한 프로듀서가 없습니다.</span>
                            </div>
                            <!-- end CASE 2 -->

                            <!-- CASE 3) 프로듀서 있을 때 -->
                            <div
                                v-else
                                class="pd_yes"
                            >
                                <div class="more_info">
                                    <span class="left">
                                        총
                                        <em>{{ followlist.length }}</em>명의 프로듀서
                                    </span>
                                </div>
                                <ul
                                    id="follower-scroll"
                                    class="lnb02"
                                    :style="[followlist.length > 5 ?{height: '360px', 'overflow-y': 'scroll'} : {}]"
                                >
                                    <li
                                        v-for="follow in followlist"
                                        :key="follow.follow_id"
                                    >
                                        <a
                                            href="#"
                                            @click.prevent="$router.push(`/producer-info/${follow.prdc_id}`, () => {})"
                                        >
                                            <img
                                                :src="`/fdata/mkrthumb/${follow.prdc_img}`"
                                                class="thumb-mini"
                                            >
                                            <span class="pd_name">{{ follow.prdc_nick }}</span>
                                        </a>
                                    </li>
                                <!-- <li class="active-new">
                                    <a href="#">
                                        <img
                                            src="/img/example/ex_fliker.jpg"
                                            alt="thumnail image"
                                            class="thumb-mini"
                                            style="background-image: url(img/example/ex_fliker.jpg);"
                                        />
                                        <span class="pd_name">fliker (Feat.EK)</span>
                                    </a>
                                </li>-->
                                </ul>
                            </div>
                            <!-- end CASE 3 -->
                        </div>
                    </div>
                </div>

                <div class="gnb02">
                    <nav class="gnb02-nav">
                        <ul>
                            <li class="gnb02-list">
                                <a
                                    href="#"
                                    @click.prevent="$router.push('/rank')"
                                >실시간 TOP 100</a>
                            </li>

                            <li
                                id="mood-menu"
                                class="gnb02-list gnb02-list-more"
                            >
                                <input
                                    id="mood_check"
                                    type="checkbox"
                                    class="none-input"
                                >
                                <label for="mood_check">분위기</label>
                                <ul
                                    v-if="isMoodMenuUpdated"
                                    id="lnb_mood"
                                    class="gnb02-lnb _scroll-area"
                                    data-mcs-theme="minimal"
                                >
                                    <li
                                        v-for="mood in moods"
                                        :key="mood.mood_id"
                                    >
                                        <a
                                            href="#"
                                            @click.prevent="$router.push(`/mood-rank/${mood.mood_id}`, () => {})"
                                        >{{ mood.mood_title }}</a>
                                    </li>
                                </ul>
                            </li>

                            <li
                                id="genre-menu"
                                class="gnb02-list gnb02-list-more"
                            >
                                <input
                                    id="genre_check"
                                    type="checkbox"
                                    class="none-input"
                                >
                                <label for="genre_check">장르</label>
                                <ul
                                    v-if="isGenreMenuUpdated"
                                    id="lnb_genre"
                                    class="gnb02-lnb _scroll-area"
                                    data-mcs-theme="minimal"
                                >
                                    <li
                                        v-for="genre in genres"
                                        :key="genre.cate_id"
                                    >
                                        <a
                                            href="#"
                                            @click.prevent="$router.push(`/genre-rank/${genre.cate_id}`, () => {})"
                                        >{{ genre.cate_title }}</a>
                                    </li>
                                </ul>
                            </li>

                            <li class="gnb02-list">
                                <a
                                    href="#"
                                    @click.prevent="$router.push('/producer-rank', () => {})"
                                >프로듀서</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <label
                for="check-header-btn"
                class="header-btn"
            >
                <img
                    src="/img/btn/btn-header-menu.svg"
                    alt="header menu ribbon"
                >
            </label>
        </div>
        <!-- //좌측고정 네비게이션 -->

        <notice-popup-component
            title-text="알림"
            :visible.sync="isNoticePopupVisible"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="noticePopupMessage"
            />
        </notice-popup-component>
    </header>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import NoticePopupComponent from "../common/NoticePopupComponent";

export default {
    components: {
        NoticePopupComponent
    },
    data() {
        return {
            isTryLogout: false,
            isUpdated: false,
            isUserMenuUpdated: false,
            isCartMenuUpdated: false,
            isFollowMenuUpdated: false,
            isMoodMenuUpdated: false,
            isGenreMenuUpdated: false,
            isNoticePopupVisible: false,
            isNewAlarmMsg: false,
            noticePopupMessage: "",
            recentCartlist: [],
            userMsg: []
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            moods: "moods",
            genres: "genres",
            cartlist: "cartlist",
            followlist: "followlist"
        }),
        isUserIndicator() {
            return this.isNewAlarmMsg;
        },
        selectedRecentCarts() {
            return this.recentCartlist.filter(cart => cart.checked);
        }
    },
    watch: {
        isUser() {
            this.forceUpdates();
        },
        moods() {
            this.updateMoodMenu();
        },
        genres() {
            this.updateGenreMenu();
        },
        cartlist() {
            this.updateCartMenu();
        },
        followlist() {
            this.updateFollowMenu();
        }
    },
    created() {
        this.forceUpdates();
        this.msg();
    },
    async mounted() {
        $(".gnb01-list").on("mouseenter", function(e) {
            const target = $(e.currentTarget);
            const index = target.index();
            const down = $(".gnb01-lnb-con").eq(index);

            target.addClass("active");
            $(".gnb01-list")
                .not(target)
                .removeClass("active");

            $(".gnb01-lnb-con")
                .stop()
                .not(down)
                .hide();

            down.stop().slideDown({
                complete: function() {
                    down.removeAttr("style").show();
                    $(".gnb01-lnb-con").css("height", "auto");
                }
            });
        });

        $("#gnb01").mouseleave(function() {
            $(".gnb01-list").removeClass("active");

            $(".gnb01-lnb-con")
                .stop()
                .slideUp();
        });
    },
    methods: {
        forceUpdates() {
            this.updateUserMenu();
            this.updateCartMenu();
            this.updateFollowMenu();
            this.updateMoodMenu();
            this.updateGenreMenu();
        },
        async updateUserMenu() {
            this.isUserMenuUpdated = false;
            await this.$nextTick();
            this.isUserMenuUpdated = true;
            await this.$nextTick();
        },
        async updateCartMenu() {
            if (this.isUser && this.cartlist.length === 0) {
                await this.$http
                    .get(`/api/carts`, { params: { req: "leftheader" } })
                    .then(response => {
                        if (
                            this.cartlist.length === 0 &&
                            response.data.length !== 0
                        ) {
                            this.$store.commit("updateCartlist", response.data);
                        }
                    });
            }

            this.recentCartlist = this.cartlist.map(cart => ({
                ...cart,
                ...{ checked: false }
            }));

            this.isCartMenuUpdated = false;
            await this.$nextTick();
            this.isCartMenuUpdated = true;
            await this.$nextTick();
        },
        async updateFollowMenu() {
            if (this.isUser && this.followlist.length === 0) {
                await this.$http
                    .get(`/api/follows`, { params: { req: "leftheader" } })
                    .then(response => {
                        if (
                            this.followlist.length === 0 &&
                            response.data.length !== 0
                        ) {
                            this.$store.commit(
                                "updateFollowlist",
                                response.data
                            );
                        }
                    });
            }

            this.isFollowMenuUpdated = false;
            await this.$nextTick();
            this.isFollowMenuUpdated = true;
            await this.$nextTick();
            this.$RENDER_JQUERY_mCustomScrollbar("#follower-scroll", "minimal");
        },
        async updateMoodMenu() {
            if (this.moods.length === 0) {
                await this.$http.get(`/api/moods`).then(response => {
                    if (this.moods.length === 0 && response.data.length !== 0) {
                        this.$store.commit("updateMoods", response.data);
                    }
                });
            }

            this.isMoodMenuUpdated = false;
            await this.$nextTick();
            this.isMoodMenuUpdated = true;
            await this.$nextTick();
            this.$RENDER_JQUERY_mCustomScrollbar("#lnb_mood", "minimal");
        },
        async updateGenreMenu() {
            if (this.genres.length === 0) {
                await this.$http.get(`/api/categorys`).then(response => {
                    if (
                        this.genres.length === 0 &&
                        response.data.length !== 0
                    ) {
                        this.$store.commit("updateGenres", response.data);
                    }
                });
            }

            this.isGenreMenuUpdated = false;
            await this.$nextTick();
            this.isGenreMenuUpdated = true;
            await this.$nextTick();
            this.$RENDER_JQUERY_mCustomScrollbar("#lnb_genre", "minimal");
        },
        async removeSelectedRecentCart() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.patch("/api/carts/collection", {
                    action: "destroy",
                    cart_ids: this.selectedRecentCarts.map(beat => beat.cart_id)
                });
                this.$store.commit("updateCartlist", []);

                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "선택된 비트를 장바구니에서 삭제했습니다";
            } catch (e) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "선택된 비트를 장바구니에서 삭제하는 중 오류가 발생했습니다";
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async logout() {
            if (this.isTryLogout) {
                return;
            }

            try {
                this.isTryLogout = true;

                await this.$http.post("/api/logout");

                localStorage.removeItem("laravel_token");
                this.$http.defaults.headers.common["Authorization"] = undefined;

                window.location.replace("/");
            } catch (e) {
                console.log(e);
            } finally {
                this.isTryLogout = false;
            }
        },
        async msg() {
            try {
                if (this.isUser) {
                    this.userMsg = await this.$http
                        .get(`/api/alarms`, {
                            params: {
                                req: "iscurrent"
                            }
                        })
                        .then(response => {
                            return response.data;
                        });
                    if (this.userMsg.length > 0) {
                        this.isNewAlarmMsg = true;
                    }
                }
            } catch (e) {
                console.log(e);
            }
        }
    }
};
</script>

<style lang="scss">
@import "../../../styles/scss/layouts/left-header";
</style>
