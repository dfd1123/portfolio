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

                    <div class="search-tag-result-list">
                        <ul>
                            <li
                                v-for="beat in beatList"
                                :key="beat.beat_id"
                                class="result-card"
                                @click="$router.push(`/beat-info/${beat.beat_id}`)"
                            >
                                <figure
                                    class="tag-album-thumb"
                                    :style="{'background-image': `url(/fdata/beathumb/${beat.beat_thumb})`}"
                                >
                                    <figcaption class="none">
                                        앨범 썸네일
                                    </figcaption>
                                </figure>
                                <dl>
                                    <dt>{{ beat.beat_title }}</dt>
                                    <dd class="_pd_name">
                                        {{ beat.prdc_nick }}
                                    </dd>
                                    <dd class="_tags">
                                        <span
                                            v-for="(tag, index) in parseBeatTags(beat.beat_tag)"
                                            :key="index"
                                        >{{ tag }}</span>
                                    </dd>
                                </dl>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--TODO:END 장르선택 차트-->
            </div>

            <footer-component />
        </section>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import infiniteScroll from "vue-infinite-scroll";

const DEFAULT_LIMIT = 100;

export default {
    directives: { infiniteScroll },
    components: {
        HeaderComponent,
        FooterComponent
    },
    data() {
        return {
            isScrollAtBottom: false,
            keyword: "",
            beatList: [],
            offset: 0,
            limit: DEFAULT_LIMIT
        };
    },
    watch: {
        $route() {
            this.keyword = this.$route.query.keyword;
            this.searchStart();
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
                this.beatList = [];
                this.offset = 0;
                this.limit = DEFAULT_LIMIT;
                this.$store.commit("updateIsGlobalLoading", true);
                await this.loadBeatList();
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        searchAgain(keyword) {
            this.keyword = keyword;
            this.searchStart();
        },
        async loadBeatList() {
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

                this.beatList = [
                    ...new Map(
                        this.beatList
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
        parseBeatTags(tags) {
            if (!tags) {
                return [];
            }

            return tags
                .split("#")
                .filter(x => x)
                .map(x => x.trim());
        },
        async scrollAtBottom() {
            if (!this.isScrollAtBottom) {
                this.isScrollAtBottom = true;
                try {
                    this.$store.commit("updateIsGlobalLoading", true);
                    await this.loadBeatList();
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
