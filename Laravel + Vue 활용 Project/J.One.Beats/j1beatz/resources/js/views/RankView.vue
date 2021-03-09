<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component
                ver="dark_ver"
            />

            <!--TODO:TOP100보여주는화면일때만 아래 제목스타일 추가-->
            <div class="sub-page-title sub-page-title-top100">
                <h2 class="sub-page-title-name">
                    실시간 top 100
                </h2>
                <span
                    v-if="lastUpdateTimes.length > 0"
                    class="sub-page-title-time"
                ><b>{{ lastUpdateTimes[0] }}</b> <em>{{ lastUpdateTimes[1] }}</em> 업데이트
                </span>

                <div class="sub-page-top100-category">
                    <span
                        v-if="selectedType === 'mood'"
                        class="_now_category"
                    >분위기별</span>
                    <span
                        v-if="selectedType === 'genre'"
                        class="_now_category"
                    >장르별</span>
                    <ul class="_category_list">
                        <li>
                            <button
                                type="button"
                                @click="selectedType = 'mood'"
                            >
                                분위기별
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                @click="selectedType = 'genre'"
                            >
                                장르별
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <!--TODO:END-->

            <div class="page-contents-wrapper">
                <!--TODO:실시간TOP100 차트-->
                <div class="sub-page-gnb02-1">
                    <div class="mood-genre-choice">
                        <div class="mood-genre-choice-inner">
                            <ul class="mood-genre-choice-list">
                                <li
                                    v-for="filter in filterItems"
                                    :key="filter.id"
                                    class="mood-genre-choice-btn"
                                    :class="{active: filter.checked}"
                                >
                                    <label
                                        @click="selectFilter(filter)"
                                    >
                                        <span>{{ filter.title }}</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <beat-list-component
                        height="80vmin"
                        :title="beatChartTitle"
                        :beats="beatChartList"
                        :rank="true"
                        scroll-theme="minimal-dark"
                        @beatListUpdate="loadBeatChartList"
                    />
                </div>
                <!--TODO:END 실시간TOP100 차트-->
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

export default {
    components: {
        FooterComponent,
        HeaderComponent,
        BeatListComponent
    },
    data() {
        return {
            selectedType: "mood",
            filterItems: [],
            beatChartTitle: "",
            beatChartList: [],
            lastUpdateTimes: []
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
        selectedFilter() {
            const found = this.filterItems.find(filter => {
                return filter.checked;
            });

            if (!found) {
                return {
                    id: null,
                    title: ""
                };
            }

            return { ...found };
        },
        paramName() {
            if (this.selectedType === "mood") {
                return "mood_id";
            }

            if (this.selectedType === "genre") {
                return "cate_id";
            }

            return "";
        }
    },
    watch: {
        async selectedType() {
            this.updateFilterItems();
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                await this.loadBeatChartList();
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
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

                this.updateFilterItems();
                await this.loadBeatChartList();
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        updateFilterItems() {
            if (this.selectedType === "mood") {
                this.filterItems = this.moods.map(mood => {
                    return {
                        id: mood.mood_id,
                        title: mood.mood_title,
                        checked: false
                    };
                });
            }

            if (this.selectedType === "genre") {
                this.filterItems = this.genres.map(genre => {
                    return {
                        id: genre.cate_id,
                        title: genre.cate_title,
                        checked: false
                    };
                });
            }
        },
        async loadBeatChartList() {
            try {
                this.beatChartList = await this.$http
                    .get(`/api/beats`, {
                        params: {
                            req: "realtime_top",
                            [this.paramName]: this.selectedFilter.id
                        }
                    })
                    .then(response => {
                        return response.data;
                    });
                this.beatChartTitle = this.selectedFilter.title || "전체";
            } catch (e) {
                console.log(e);
            }
        },
        async selectFilter(filter) {
            if (filter.checked === true) {
                filter.checked = false;
            } else {
                this.filterItems.forEach(filterItem => {
                    filterItem.checked = false;
                });
                filter.checked = true;
            }

            try {
                this.$store.commit("updateIsGlobalLoading", true);
                await this.loadBeatChartList();
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        getLastUpdateTimes() {
            const currentTime = moment();
            return [
                currentTime.format("YYYY-MM-DD"),
                currentTime.format("HH:mm")
            ];
        }
    }
};
</script>

<style lang="scss">
</style>
