<template>
    <!-- 메인 컨텐츠 -->
    <div
        id="wrap"
    >
        <section class="section sub-section">
            <header-component />

            <div
                v-infinite-scroll="scrollAtBottom"
                infinite-scroll-immediate-check="false"
                class="page-contents-wrapper"
            >
                <!--TODO:프로듀서목록리스트-->
                <div class="sub-page-gnb02-4">
                    <div class="sub-page-title">
                        <h2 class="sub-page-title-name">
                            프로듀서
                        </h2>
                        <span class="sub-page-title-time">
                            <b>{{ updated_at }}</b> 업데이트
                        </span>
                    </div>

                    <div class="in-title in-title-tab_ver">
                        <div class="tab-menu tab-menu-not-alink">
                            <ul>
                                <li
                                    class="tab-menu-list"
                                    :class="{active: orderby === 'rank'}"
                                    @click="orderChange('rank')"
                                >
                                    랭크순
                                </li>
                                <li
                                    class="tab-menu-list"
                                    :class="{active: orderby === 'like'}"
                                    @click="orderChange('like')"
                                >
                                    좋아요순
                                </li>
                                <li
                                    class="tab-menu-list"
                                    :class="{active: orderby === 'follow'}"
                                    @click="orderChange('follow')"
                                >
                                    팔로우순
                                </li>
                                <li
                                    class="tab-menu-list"
                                    :class="{active: orderby === 'desc'}"
                                    @click="orderChange('desc')"
                                >
                                    구매순
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

                    <div class="producer-card-list">
                        <ul>
                            <li
                                v-for="producer in producerList"
                                :key="producer.prdc_id"
                                class="producer-card"
                            >
                                <a
                                    href="#"
                                    @click.prevent
                                >
                                    <div
                                        class="producer-card-thumbnail"
                                        :style="{
                                            'background-image': `url(/fdata/mkrthumb/${producer.prdc_img})`
                                        }"
                                        @click="producerDetail(producer.prdc_id)"
                                    >
                                        <span class="none">프로듀서 썸네일</span>
                                        <span class="producer-card-ranking" />
                                    </div>
                                    <p
                                        class="producer-card-name"
                                        @click="producerDetail(producer.prdc_id)"
                                    >{{ producer.prdc_nick }}</p>
                                    <ul class="producer-card-summing_up">
                                        <li>
                                            <b>like</b>
                                            <em>{{ prefixNum(producer.prdc_like) }}</em>
                                        </li>
                                        <li>
                                            <b>follow</b>
                                            <em>{{ prefixNum(producer.prdc_follow) }}</em>
                                        </li>
                                        <li>
                                            <b>buy</b>
                                            <em>{{ prefixNum(producer.prdc_buy) }}</em>
                                        </li>
                                    </ul>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--TODO:END 프로듀서목록리스트-->
            </div>

            <footer-component />
        </section>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import { prefixNum } from "../lib/common";
import moment from "moment";
import infiniteScroll from "vue-infinite-scroll";

export default {
    directives: { infiniteScroll },
    components: {
        HeaderComponent,
        FooterComponent
    },
    data() {
        return {
            isScrollAtBottom: false,
            updated_at: "",
            orderby: "rank",
            producerList: [],
            offset: 0,
            limit: 100
        };
    },
    computed: {},
    async created() {
        await this.loadProducerList();
        this.updated();
    },
    methods: {
        prefixNum,
        async loadProducerList() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                const result = await this.$http
                    .get(`/api/producers`, {
                        params: {
                            req: "orderby_prdc",
                            orderby: this.orderby,
                            offset: this.offset,
                            limit: this.limit
                        }
                    })
                    .then(response => {
                        return response.data;
                    });

                this.producerList = [
                    ...new Map(
                        this.producerList
                            .concat(result)
                            .map(item => [item["prdc_id"], item])
                    ).values()
                ];
                this.isScrollAtBottom = result.length < this.limit;
                this.offset = this.offset + result.length;
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async orderChange(by) {
            this.orderby = by;
            this.producerList = [];
            this.offset = 0;
            this.limit = 100;
            this.loadProducerList();
        },
        updated() {
            this.updated_at = moment().format("YYYY. MM. DD HH:mm");
        },
        producerDetail(prdc_id) {
            this.$router.push("/producer-info/" + prdc_id);
        },
        async scrollAtBottom() {
            if (!this.isScrollAtBottom) {
                this.isScrollAtBottom = true;
                this.loadProducerList();
            }
        }
    }
};
</script>

<style lang="scss">
</style>
