<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section
            class="section sub-section"
        >
            <header-component />

            <div class="page-contents-wrapper">
                <!--TODO:장르선택 차트-->
                <div class="sub-page-gnb02-3">
                    <div
                        v-if="isLoaded"
                        class="producer-profile"
                    >
                        <figure
                            class="producer-profile-thumb"
                            :style="{'background-image': producerInfo.prdc_img ? `url(/fdata/mkrthumb/${producerInfo.prdc_img})` : ''}"
                        >
                            <figcaption class="none">
                                프로듀서 프로필사진
                            </figcaption>
                        </figure>
                        <div class="producer-profile-detail">
                            <dl>
                                <dt>
                                    {{ producerInfo.prdc_nick }}
                                    <button
                                        type="button"
                                        class="btn_info_share"
                                        @click="share"
                                    />
                                </dt>
                                <dd class="_pd_mood_detail">
                                    <b>분위기</b>
                                    <em
                                        v-for="mood in producerMoods"
                                        :key="mood.mood_id"
                                    >{{ mood.mood_title }}</em>
                                </dd>
                                <dd class="_pd_genre">
                                    <b>장르</b>
                                    <em
                                        v-for="genre in producerGenres"
                                        :key="genre.cate_id"
                                    >{{ genre.cate_title }}</em>
                                </dd>
                            </dl>


                            <button
                                class="btn flw-btn"
                                :class="{active: isFollowing}"
                                @click="toggleFollow"
                            >
                                follow
                            </button>

                            <ul class="_pd_summing_up">
                                <li class="_like-li">
                                    {{ prefixNum(producerInfo.prdc_like) }}
                                </li>
                                <li class="_flw-li">
                                    {{ prefixNum(producerInfo.prdc_follow) }}
                                </li>
                                <li class="_buy-li">
                                    {{ prefixNum(producerInfo.prdc_buy) }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="in-title in-title-tab_ver">
                        <div class="tab-menu tab-menu-not-alink">
                            <ul>
                                <li
                                    class="tab-menu-list"
                                    :class="{active: beatOrder === 'rank'}"
                                    @click="loadBeatChartByRank"
                                >
                                    인기순
                                </li>
                                <li
                                    class="tab-menu-list"
                                    :class="{active: beatOrder === 'latest'}"
                                    @click="loadBeatChartByLatest"
                                >
                                    최신순
                                </li>
                            </ul>
                        </div>
                        <!--
                        <span class="in-title-right in-title-right-all_play"><a
                            href="#"
                            role="button"
                        >전체듣기</a></span>
                        -->
                    </div>

                    <beat-list-component
                        height="500"
                        :beats="beatChartList"
                        :show-producer-link="false"
                        @beatListUpdate="beatListUpdate"
                    />
                </div>
                <!--TODO:END 장르선택 차트-->
            </div>

            <footer-component />
        </section>

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

        <confirm-popup-component
            title-text="알림"
            :visible.sync="isConfirmPopupVisible"
            right-button-text="로그인 하기"
            @rightButtonClick="$router.push('/login')"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html=" `<b>로그인</b>이 필요한 서비스입니다.<br>로그인 하시겠습니까?`"
            />
        </confirm-popup-component>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { prefixNum } from "../lib/common";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import BeatListComponent from "../components/common/BeatListComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (Number.isInteger(Number(to.params.id))) {
            next();
        } else {
            next("/404");
        }
    },
    components: {
        HeaderComponent,
        FooterComponent,
        BeatListComponent,
        NoticePopupComponent,
        ConfirmPopupComponent
    },
    data() {
        return {
            isLoaded: false,
            isConfirmPopupVisible: false,
            isNoticePopupVisible: false,
            noticePopupMessage: "",
            isBeatChartLoading: false,
            beatOrder: "rank",
            id: this.$route.params.id,
            producerInfo: {},
            follow: {},
            beatChartList: []
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user",
            moods: "moods",
            genres: "genres",
            currentPlayingBeat: "currentPlayingBeat"
        }),
        isFollowing() {
            return this.follow.follow_id ? true : false;
        },
        producerMoods() {
            if (!this.producerInfo.prdc_id) {
                return [];
            }

            return this.moods.filter(mood =>
                this.producerInfo.mood_json.includes(mood.mood_id)
            );
        },
        producerGenres() {
            if (!this.producerInfo.prdc_id) {
                return [];
            }

            return this.genres.filter(genre =>
                this.producerInfo.cate_json.includes(genre.cate_id)
            );
        }
    },
    watch: {
        async $route() {
            this.id = this.$route.params.id;
            this.beatChartList = [];
            await this.fetchData();
        },
        currentPlayingBeat(newVal, oldVal) {
            if (newVal.beat_id && oldVal.beat_id) {
                if (
                    newVal.beat_id === oldVal.beat_id &&
                    oldVal.bl_state !== newVal.bl_state
                ) {
                    const index = this.beatChartList.findIndex(
                        beat => beat.beat_id === this.currentPlayingBeat.beat_id
                    );

                    if (index !== -1) {
                        this.loadBeatChartList();
                    }
                }
            }
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        prefixNum,
        async fetchData() {
            this.$store.commit("updateIsGlobalLoading", true);
            this.isLoaded = false;

            try {
                if (this.moods.length === 0) {
                    await this.$http.get(`/api/moods`).then(response => {
                        if (
                            this.moods.length === 0 &&
                            response.data.length !== 0
                        ) {
                            this.$store.commit("updateMoods", response.data);
                        }
                    });
                }

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

                await this.fetchProducer();

                if (this.isUser) {
                    this.follow = await this.$http
                        .get(`/api/follows`, {
                            params: {
                                req: "following",
                                prdc_id: this.producerInfo.prdc_id
                            }
                        })
                        .then(response => {
                            return response.data;
                        });
                }

                this.loadBeatChartList();

                this.isLoaded = true;
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async fetchProducer() {
            try {
                this.producerInfo = await this.$http
                    .get(`/api/producers/${this.id}`)
                    .then(response => {
                        if (!response.data.prdc_id) {
                            throw new Error("producerInfo load fail");
                        }
                        return response.data;
                    });
            } catch (e) {
                this.$router.push("/404");
            }
        },
        async toggleFollow() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            this.$store.commit("updateIsGlobalLoading", true);
            try {
                if (this.isFollowing) {
                    await this.$http.delete(
                        `/api/follows/${this.follow.follow_id}`
                    );
                    this.follow = {};
                } else {
                    await this.$http.post("/api/follows", {
                        prdc_id: this.producerInfo.prdc_id
                    });

                    this.follow = await this.$http
                        .get(`/api/follows`, {
                            params: {
                                req: "following",
                                prdc_id: this.producerInfo.prdc_id
                            }
                        })
                        .then(response => {
                            return response.data;
                        });
                }
                this.$store.commit("updateFollowlist", []);
                await this.fetchProducer();
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async loadBeatChartByRank() {
            this.$store.commit("updateIsGlobalLoading", true);
            this.beatOrder = "rank";
            await this.loadBeatChartList();
            this.$store.commit("updateIsGlobalLoading", false);
        },
        async loadBeatChartByLatest() {
            this.$store.commit("updateIsGlobalLoading", true);
            this.beatOrder = "latest";
            await this.loadBeatChartList();
            this.$store.commit("updateIsGlobalLoading", false);
        },
        async loadBeatChartList() {
            try {
                const list = await this.$http
                    .get(`/api/beats`, {
                        params: {
                            req: "by_prdc_id",
                            prdc_id: this.producerInfo.prdc_id
                        }
                    })
                    .then(response => {
                        return response.data;
                    });

                if (this.beatOrder === "rank") {
                    this.beatChartList = list;
                } else if (this.beatOrder === "latest") {
                    this.beatChartList = list.sort(function(a, b) {
                        return b.beat_id - a.beat_id;
                    });
                }
            } catch (e) {
                console.log(e);
            }
        },
        beatListUpdate() {
            this.loadBeatChartList();
            this.fetchProducer();
        },
        async share() {
            const { protocol, hostname } = window.location;
            this.$clipboard(
                `${protocol}//${hostname}/producer-info/${
                    this.producerInfo.prdc_id
                }`
            );

            this.isNoticePopupVisible = true;
            this.noticePopupMessage =
                "해당 프로듀서 링크가 <br> 클립보드에 복사되었습니다. <br> 원하는 곳에 붙여넣으세요";
        }
    }
};
</script>

<style lang="scss">
</style>
