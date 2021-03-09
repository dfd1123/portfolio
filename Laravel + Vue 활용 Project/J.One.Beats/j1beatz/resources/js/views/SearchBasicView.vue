<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component
                @searchAgain="searchAgain"
            />

            <div
                v-infinite-scroll="scrollAtBottom"
                infinite-scroll-immediate-check="false"
                class="page-contents-wrapper"
            >
                <!--TODO:장르선택 차트-->
                <div class="sub-page-gnb02-3">
                    <div class="sub-page-title">
                        <span class="sub-page-title-category">검색</span>
                        <h2 class="sub-page-title-name">
                            <b>'{{ keyword }}'</b> 검색결과
                        </h2>
                    </div>

                    <beat-list-component
                        height="100%"
                        :beats="beatChartList"
                        scroll-theme="minimal-dark"
                        @beatListUpdate="loadBeatChartList"
                    />
                </div>
                <!--TODO:END 장르선택 차트-->
            </div>

            <footer-component />
        </section>
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import BeatListComponent from "../components/common/BeatListComponent";
import infiniteScroll from "vue-infinite-scroll";

const DEFAULT_LIMIT = 100;

export default {
    directives: { infiniteScroll },
    components: {
        HeaderComponent,
        FooterComponent,
        BeatListComponent
    },
    data() {
        return {
            isScrollAtBottom: false,
            keyword: "",
            beatChartList: [],
            offset: 0,
            limit: DEFAULT_LIMIT
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user",
            currentPlayingBeat: "currentPlayingBeat"
        })
    },
    watch: {
        $route() {
            this.keyword = this.$route.query.keyword;
            this.searchStart();
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
    mounted() {
        this.keyword = this.$route.query.keyword;
        if (this.keyword === "") {
            this.$router.push("/404");
        }
        this.searchStart();
    },
    methods: {
        async searchStart() {
            try {
                this.beatChartList = [];
                this.offset = 0;
                this.limit = DEFAULT_LIMIT;
                this.$store.commit("updateIsGlobalLoading", true);
                await this.loadBeatChartList();
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        searchAgain(keyword) {
            this.keyword = keyword;
            this.searchStart();
        },
        async loadBeatChartList() {
            try {
                const result = await this.$http
                    .get(`/api/beats`, {
                        params: {
                            req: "list_by_keyword",
                            keyword: this.keyword,
                            offset: this.offset,
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
                this.offset = this.offset + result.length;
            } catch (e) {
                console.log(e);
            }
        },
        async scrollAtBottom() {
            if (!this.isScrollAtBottom) {
                this.isScrollAtBottom = true;
                try {
                    this.$store.commit("updateIsGlobalLoading", true);
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
</style>
