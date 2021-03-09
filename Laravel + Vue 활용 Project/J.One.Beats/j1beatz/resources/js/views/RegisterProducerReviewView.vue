<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <div
                    class="sub-page-auth sub-page-auth-pd-profile __complete"
                >
                    <div class="sub-page-auth-title">
                        <h2>프로듀서 프로필 검수</h2>
                        <span>제이원비츠 프로듀서 검수</span>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div v-if="isRejectedProducer">
                            <div class="certify-good">
                                <img
                                    src="/img/icon/icon_refuse.svg"
                                    alt="certify complete"
                                >
                                <p>프로듀서 프로필 <b>등록이 거절</b>되었습니다.</p>

                                <span>운영자의 프로필 검수 결과 제출하신 프로필이 거절처리 되었습니다.</span>
                                <br>
                                <span>거절 사유를 검토하신 뒤 거절된 프로필을 삭제하고 프로듀서 등록 화면에서</span>
                                <br>
                                <span>프로듀서 프로필을 다시 제출해주시기 바랍니다. </span>
                            </div>
                        </div>
                        <div v-else>
                            <div class="certify-good">
                                <img
                                    src="/img/icon/icon_wait.svg"
                                    alt="certify complete"
                                >
                                <p>제출하신 프로듀서 프로필을 <b>검수 중</b>입니다.</p>

                                <span>운영자의 프로필 검수 후, 프로듀서 회원 활동이 가능합니다.</span>
                                <br>
                                <span>프로필 검수 결과에 따라 등록이 거절될 수 있습니다.</span>
                            </div>

                            <p class="certify-good">
                                <span>프로필 제출: </span><span>{{ fromNow(producer.created_at) }}</span>
                            </p>
                        </div>

                        <div
                            class="pd-profile-complt"
                        >
                            <figure :style="{'background-image': `url(/fdata/mkrthumb/${producer.prdc_img})`}">
                                <figcaption class="none">
                                    프로듀서 썸네일
                                </figcaption>
                            </figure>

                            <dl v-show="isLoaded">
                                <dt>{{ producer.prdc_nick }}</dt>
                                <dd>
                                    <b>분위기</b>
                                    <em
                                        v-for="mood in producerMoods"
                                        :key="mood.mood_id"
                                    >{{ mood.mood_title }}</em>
                                </dd>
                                <dd>
                                    <b>장르</b>
                                    <em
                                        v-for="genre in producerGenres"
                                        :key="genre.cate_id"
                                    >{{ genre.cate_title }}</em>
                                </dd>
                            </dl>
                        </div>

                        <div v-if="isRejectedProducer">
                            <div
                                class="pd-profile-complt"
                                style="background-color: transparent;"
                            >
                                <div style="text-align: inherit;">
                                    <dl>
                                        <dt style="font-size: 1.3em; font-weight: initial; margin: 1.2em 0 0.3em;">
                                            거절사유
                                        </dt>
                                    </dl>
                                </div>
                            </div>

                            <div
                                class="member-mail-guide"
                            >
                                <span>{{ producer.prdc_reject }}</span>
                            </div>

                            <button
                                class="btn btn-complete"
                                @click="profileDeleteAndRewrite"
                            >
                                거절된 프로필 삭제 후 다시 제출하기
                            </button>
                        </div>
                        <div v-else>
                            <button
                                class="btn btn-complete"
                                @click="$router.push('/main')"
                            >
                                홈으로
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--TODO:END-->

            <footer-component />
        </section>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import moment from "moment";
moment.locale("ko");

export default {
    components: {
        HeaderComponent,
        FooterComponent
    },
    data() {
        return {
            isLoaded: false
        };
    },
    computed: {
        ...mapGetters([
            "isWaitingProducer",
            "isRejectedProducer",
            "isSuspendedProducer"
        ]),
        ...mapState({
            producer: "producer",
            moods: "moods",
            genres: "genres"
        }),
        producerMoods() {
            return this.moods.filter(mood =>
                this.producer.mood_json.includes(mood.mood_id)
            );
        },
        producerGenres() {
            return this.genres.filter(genre =>
                this.producer.cate_json.includes(genre.cate_id)
            );
        }
    },
    async created() {
        if (this.moods.length === 0) {
            await this.$http.get(`/api/moods`).then(response => {
                if (this.moods.length === 0 && response.data.length !== 0) {
                    this.$store.commit("updateMoods", response.data);
                }
            });
        }

        if (this.genres.length === 0) {
            await this.$http.get(`/api/categorys`).then(response => {
                if (this.genres.length === 0 && response.data.length !== 0) {
                    this.$store.commit("updateGenres", response.data);
                }
            });
        }

        setTimeout(() => {
            this.isLoaded = true;
        });
    },
    methods: {
        fromNow(date) {
            return moment(date).fromNow();
        },
        async profileDeleteAndRewrite() {
            if (this.isTryProfileDeleteAndRewrite) {
                return;
            }

            try {
                this.isTryProfileDeleteAndRewrite = true;
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.post("/api/leave_producer");

                this.$router.push("/main");
            } catch (e) {
                console.log(e);
            } finally {
                this.isTryProfileDeleteAndRewrite = false;
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
</style>
