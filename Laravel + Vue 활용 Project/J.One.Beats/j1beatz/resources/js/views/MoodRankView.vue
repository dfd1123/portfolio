<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <div class="page-contents-wrapper">
                <!--TODO:분위기선택 차트-->
                <div class="sub-page-gnb02-2">
                    <div class="sub-page-title">
                        <span class="sub-page-title-category">분위기</span>
                        <h2 class="sub-page-title-name">
                            {{ currentMood.mood_title }}
                        </h2>
                        <span
                            v-if="lastUpdateTimes.length > 0"
                            class="sub-page-title-time"
                        >
                            <b>{{ lastUpdateTimes[0] }}</b> <em>{{ lastUpdateTimes[1] }}</em> 업데이트
                        </span>
                    </div>

                    <div class="in-title">
                        <h3 class="in-title-name">
                            장르선택
                        </h3>
                        <span class="in-title-ment">한 가지만 선택가능합니다.</span>
                    </div>

                    <div class="main-mood-genre-choice">
                        <input
                            v-for="genre in genreItems"
                            :key="genre.cate_id"
                            v-model="genre.checked"
                            type="checkbox"
                            class="none-input"
                        >

                        <ul>
                            <li
                                v-for="genre in genreItems"
                                :key="genre.cate_id"
                                class="mood-genre-choice-btn main-genre"
                                :class="{active: genre.checked}"
                            >
                                <label @click="toggleSelectedGenre(genre)">
                                    <span>{{ genre.cate_title }}</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="in-title in-title-tab_ver">
                        <div class="tab-menu tab-menu-not-alink">
                            <ul>
                                <li
                                    :class="{active: beatOrder === 'rank'}"
                                    class="tab-menu-list"
                                    @click="beatOrder = 'rank'"
                                >
                                    인기순
                                </li>
                                <li
                                    :class="{active: beatOrder === 'latest'}"
                                    class="tab-menu-list"
                                    @click="beatOrder = 'latest'"
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
                        height="450"
                        :beats="beatChartList"
                        :rank="true"
                        scroll-theme="minimal-dark"
                        @beatListUpdate="loadBeatChartList"
                        @scrollAtBottom="scrollAtBottom"
                    />
                </div>
                <!--TODO:END 분위기선택 차트-->
            </div>

            <footer-component style="margin-top: 0px" />
        </section>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import moment from "moment";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import BeatListComponent from "../components/common/BeatListComponent";

const DEFAULT_LIMIT = 100;

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
        BeatListComponent
    },
    data() {
        return {
            isScrollAtBottom: false,
            id: Number(this.$route.params.id),
            genreItems: [],
            selectedGenre: {},
            beatChartTitle: "",
            beatChartList: [],
            beatChartFilters: [],
            lastUpdateTimes: [],
            beatOrder: "rank",
            limit: DEFAULT_LIMIT
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            moods: "moods",
            genres: "genres",
            currentPlayingBeat: "currentPlayingBeat"
        }),
        currentMood() {
            if (this.moods.length === 0) {
                return {};
            }

            const mood = this.moods.find(mood => mood.mood_id === this.id);
            return mood;
        }
    },
    watch: {
        $route() {
            this.id = Number(this.$route.params.id);
            this.beatChartList = [];
            this.limit = DEFAULT_LIMIT;
            this.fetchData();
        },
        genres() {
            this.updateGenreItems();
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
        },
        beatChartList() {
            this.lastUpdateTimes = this.getLastUpdateTimes();
        },
        async beatOrder() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                this.limit = DEFAULT_LIMIT;
                this.beatChartList = [];
                await this.loadBeatChartList();
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

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

                if (!this.currentMood) {
                    this.$router.push("/404");
                    return;
                }

                this.updateGenreItems();

                await this.loadBeatChartList();
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        updateGenreItems() {
            this.genreItems = this.genres.map(genre => {
                return { ...genre, ...{ checked: false } };
            });
        },
        async toggleSelectedGenre(selectedGenre) {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                if (selectedGenre.checked) {
                    selectedGenre.checked = false;
                    this.selectedGenre = {};
                } else {
                    this.genreItems.forEach(genreItems => {
                        genreItems.checked = false;
                    });
                    selectedGenre.checked = true;
                    this.selectedGenre = selectedGenre;
                }

                this.beatChartList = [];
                this.limit = DEFAULT_LIMIT;
                await this.loadBeatChartList();
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async loadBeatChartList() {
            const req =
                this.beatOrder === "latest"
                    ? "realtime_latest"
                    : "realtime_top";

            const result = await this.$http
                .get(`/api/beats`, {
                    params: {
                        req,
                        mood_id: this.id,
                        cate_id: this.selectedGenre.cate_id,
                        limit: this.limit
                    }
                })
                .then(response => {
                    return response.data;
                });

            this.beatChartList = [
                ...new Map(
                    this.beatChartList
                        .concat(result)
                        .map(item => [item["beat_id"], item])
                ).values()
            ];
            this.isScrollAtBottom = result.length < this.limit;
        },
        getLastUpdateTimes() {
            const currentTime = moment();
            return [
                currentTime.format("YYYY-MM-DD"),
                currentTime.format("HH:mm")
            ];
        },
        async scrollAtBottom() {
            if (!this.isScrollAtBottom) {
                this.isScrollAtBottom = true;
                try {
                    this.$store.commit("updateIsGlobalLoading", true);

                    this.limit = this.limit + DEFAULT_LIMIT;
                    await this.loadBeatChartList();
                } finally {
                    this.$store.commit("updateIsGlobalLoading", false);
                }
            }
        }
    }
};
</script>

<style lang="scss">
.main-mood-genre-choice .mood-genre-choice-btn.active {
    margin-bottom: 10px;
    background-color: #7e2aff;
    border-color: #7e2aff;
}
.main-mood-genre-choice .mood-genre-choice-btn.active label span {
    color: #fff;
}
</style>
