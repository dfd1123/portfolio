<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:메인일때는 main-section / 서브일때는 sub-section-->
        <section
            role="main"
            class="section main-section"
        >
            <header-component />

            <div class="page-contents-wrapper">
                <article class="main-panel-article">
                    <h3 class="none">
                        광고 배너 리스트
                    </h3>
                    <ul
                        id="advertise-slick"
                        class="main-panel-advertise"
                    >
                        <li
                            v-for="advertise in advertises"
                            :key="advertise.banner_id"
                            class="_list bugfix"
                        >
                            <div
                                class="_banner"
                                :title="advertise.banner_title"
                                :style="{
                                    'background-image': `url(/fdata/banner/${advertise.banner_img})`
                                }"
                            />
                        </li>
                    </ul>
                </article>

                <article
                    class="main-panel-article"
                    @mouseover="moodSwiper ? moodSwiper.autoplay.stop() : ''"
                    @mouseleave="moodSwiper ? moodSwiper.autoplay.start() : ''"
                >
                    <div
                        class="in-title"
                        :class="{'in-title-edit_mode': isMoodEditMode}"
                    >
                        <h3 class="in-title-name">
                            분위기
                        </h3>
                        <div
                            class="in-title-ment"
                        >
                            총 3개까지 선택가능합니다.
                        </div>
                        <span
                            v-for="mood in selectedMoods"
                            :key="mood.mood_id"
                            class="in_choice_mood"
                        >
                            {{ mood.mood_title }}
                            <b
                                v-show="isMoodEditMode"
                                class="in_choice_mood__del-btn"
                                @click="deselectSelectedMood(mood)"
                            />
                        </span>
                        <span
                            v-if="!isMoodEditMode"
                            class="in-title-right"
                        >
                            <a
                                v-if="!isMoodEditMode"
                                href="#"
                                role="button"
                                @click.prevent="startMoodEdit"
                            >편집</a>
                        </span>
                        <span
                            v-else
                            class="in-title-right"
                        >
                            <a
                                href="#"
                                role="button"
                                class="_del_btn"
                                @click.prevent="cancelMoodEdit"
                            >취소</a>
                            <a
                                href="#"
                                role="button"
                                @click.prevent="saveMoodEdit"
                            >저장</a>
                        </span>
                    </div>

                    <div
                        class="main-panel-choice_mood__wrapper"
                    >
                        <swiper
                            v-if="isMoodSwiperUpdated"
                            id="moodSwiper"
                            ref="moodSwiper"
                            :options="moodSwiperOption"
                            class="main-panel-choice_mood swiper-wrapper"
                        >
                            <swiper-slide
                                v-for="mood in moods"
                                :key="mood.mood_id"
                                class="swiper-slide main-panel-choice_mood__list"
                            >
                                <div
                                    :style="{
                                        'background-image': `url(/fdata/mood/${mood.mood_thumb})`
                                    }"
                                    class="main-panel-choice_mood__div clickable"
                                >
                                    <span class="clickable">{{ mood.mood_title }}</span>
                                </div>
                            </swiper-slide>
                            <div
                                slot="pagination"
                                class="swiper-pagination"
                            />
                            <div
                                slot="button-prev"
                                class="swiper-button swiper-button-prev"
                            />
                            <div
                                slot="button-next"
                                class="swiper-button swiper-button-next"
                            />
                        </swiper>
                    </div>
                </article>

                <article class="main-panel-article">
                    <beat-list-component
                        :title="beatChartTitle"
                        height="500"
                        :filters="beatChartFilters"
                        :beats="beatChartList"
                        :rank="true"
                        @beatListUpdate="loadBeatChartList"
                    />
                </article>

                <article
                    class="main-panel-article"
                    @mouseover="producerSwiper ? producerSwiper.autoplay.stop() : ''"
                    @mouseleave="producerSwiper ? producerSwiper.autoplay.start() : ''"
                >
                    <div class="in-title">
                        <h3 class="in-title-name">
                            프로듀서 best 10
                        </h3>
                        <span class="in-title-right in-title-right-more-dark">
                            <a
                                href="#"
                                role="button"
                                @click.prevent="$router.push('/producer-rank', () => {})"
                            >전체보기 ></a>
                        </span>
                    </div>

                    <div class="producer-container swiper-container">
                        <swiper
                            v-if="isProducerSwiperUpdated"
                            id="producerSwiper"
                            ref="producerSwiper"
                            :options="producerSwiperOption"
                            class="main-panel-producer_list _slick_panel swiper-wrapper"
                        >
                            <swiper-slide
                                v-for="producer in bestTenProducers"
                                :key="producer.prdc_id"
                                class="producer-card swiper-slide"
                            >
                                <a
                                    href="javascript:void(0)"
                                >
                                    <div
                                        class="producer-card-thumbnail clickable"
                                        :style="{
                                            'background-image': `url(/fdata/mkrthumb/${producer.prdc_img})`
                                        }"
                                    >
                                        <span class="none">프로듀서 썸네일</span>
                                        <span class="producer-card-ranking clickable" />
                                    </div>
                                    <p class="producer-card-name clickable">{{ producer.prdc_nick }}</p>
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
                            </swiper-slide>
                            <div
                                slot="pagination"
                                class="swiper-pagination"
                            />
                            <div
                                slot="button-prev"
                                class="swiper-button swiper-button-prev"
                            />
                            <div
                                slot="button-next"
                                class="swiper-button swiper-button-next"
                            />
                        </swiper>
                    </div>
                </article>
            </div>

            <footer-component />
        </section>
        <!--TODO:END 메인일때는 main-section / 서브일때는 sub-section-->

        <confirm-popup-component
            title-text="알림"
            :visible.sync="isPopupVisible"
            right-button-text="로그인 하기"
            @rightButtonClick="$router.push('/login')"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="popupMessage"
            />
        </confirm-popup-component>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { padPrefix, prefixNum } from "../lib/common";
import { swiper, swiperSlide } from "vue-awesome-swiper";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import BeatListComponent from "../components/common/BeatListComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";

export default {
    name: "MainView",
    components: {
        swiper,
        swiperSlide,
        FooterComponent,
        HeaderComponent,
        BeatListComponent,
        ConfirmPopupComponent
    },
    data() {
        return {
            isPopupVisible: false,
            isMoodEditMode: false,
            isBeatChartLoading: false,
            advertiseSlickUpdated: true,
            isMoodSwiperUpdated: true,
            isProducerSwiperUpdated: true,
            popupMessage: "",
            advertises: [],
            bestTenProducers: [],
            beatChartTitle: "",
            beatChartList: [],
            beatChartFilters: [],
            savedSelectedMoods: [],
            moodSwiperOption: {
                grabCursor: true,
                threshold: 15,
                autoplay: {
                    delay: 1000,
                    preventInteractionOnTransition: true,
                    disableOnInteraction: false
                },
                loop: true,
                slidesPerView: "auto",
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                on: {
                    click: e => {
                        // 화살표 함수 사용으로 this가 vue로 바인딩됨
                        if (this.isMoodEditMode) {
                            if (e.target.classList.contains("clickable")) {
                                const clickedIndex =
                                    this.moodSwiper.clickedIndex %
                                    this.moodSwiper.loopedSlides;

                                this.toggleSelectedMood(
                                    this.moods[clickedIndex]
                                );
                            }
                        }
                    }
                }
            },
            producerSwiperOption: {
                grabCursor: true,
                threshold: 15,
                autoplay: {
                    delay: 1000,
                    preventInteractionOnTransition: true,
                    disableOnInteraction: false
                },
                loop: true,
                slidesPerView: "auto",
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev"
                },
                on: {
                    click: e => {
                        // 화살표 함수 사용으로 this가 vue로 바인딩됨
                        if (e.target.classList.contains("clickable")) {
                            const clickedIndex =
                                this.producerSwiper.clickedIndex %
                                this.producerSwiper.loopedSlides;
                            const producer = {
                                ...this.bestTenProducers[clickedIndex]
                            };

                            this.$router.push(
                                `/producer-info/${producer.prdc_id}`
                            );
                        }
                    }
                }
            },
            source: null
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            moods: "moods",
            selectedMoods: "selectedMoods",
            currentPlayingBeat: "currentPlayingBeat"
        }),
        moodSwiper: {
            cache: false,
            get() {
                return this.$refs.moodSwiper
                    ? this.$refs.moodSwiper.swiper
                    : null;
            }
        },
        producerSwiper: {
            cache: false,
            get() {
                return this.$refs.producerSwiper
                    ? this.$refs.producerSwiper.swiper
                    : null;
            }
        }
    },
    watch: {
        advertises() {
            this.forceUpdateAdvertiseSlick();
        },
        moods() {
            this.forceUpdateMoodSwiper();
        },
        bestTenProducers() {
            this.forceUpdateProducerSwiper();
        },
        selectedMoods() {
            this.forceUpdateSelectedMoods();
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
        this.fetchData();
    },
    methods: {
        padPrefix,
        prefixNum,
        async fetchData() {
            this.$http.get(`/api/banners`).then(response => {
                this.advertises = response.data;
            });

            this.$http
                .get(`/api/producers`, {
                    params: {
                        req: "best_ten"
                    }
                })
                .then(response => {
                    this.bestTenProducers = response.data;
                });

            if (this.moods.length === 0) {
                const response = await this.$http.get(`/api/moods`);

                this.$store.commit("updateMoods", response.data);
            }

            if (this.isUser) {
                if (this.selectedMoods.length === 0) {
                    const response = await this.$http.get(`/api/mood_selects`);

                    const moodSelects = response.data.map(
                        select => select.mood_s_select
                    );

                    const selectedMoods = this.moods.filter(mood => {
                        return moodSelects.includes(mood.mood_id);
                    });

                    this.$store.commit("updateSelectedMoods", selectedMoods);
                } else {
                    this.$nextTick(() => {
                        this.forceUpdateSelectedMoods();
                    });
                }
            }

            await this.loadBeatChartList();
        },
        forceUpdates() {
            this.forceUpdateAdvertiseSlick();
            this.forceUpdateMoodSwiper();
            this.forceUpdateProducerSwiper();
        },
        forceUpdateAdvertiseSlick() {
            this.advertiseSlickUpdated = false;
            this.$nextTick(() => {
                this.advertiseSlickUpdated = true;
                this.$nextTick(() => {
                    const vm = this;
                    const slickId = "#advertise-slick";

                    this.$RENDER_JQUERY_slick(slickId, {
                        variableWidth: true,
                        centerMode: true,
                        slidesToShow: 1,
                        slidesToSlide: 1,
                        swipe: false,
                        swipeToSlide: false,
                        touchMove: false,
                        draggable: false,
                        dots: true,
                        autoplay: true,
                        pauseOnFocus: true,
                        pauseOnHover: true,
                        pauseOnDotsHover: true
                    });

                    $(slickId)
                        .off("click")
                        .on("click", function(e) {
                            if ($(e.target).is("._banner")) {
                                const slideClicked = $(e.currentTarget)
                                    .find(".slick-active")
                                    .attr("data-slick-index");
                                vm.advertiseClick({
                                    ...vm.advertises[Number(slideClicked)]
                                });
                            }
                        });
                });
            });
        },
        forceUpdateMoodSwiper() {
            this.isMoodSwiperUpdated = false;
            this.$nextTick(() => {
                this.isMoodSwiperUpdated = true;
                this.forceUpdateSelectedMoods();
            });
        },
        forceUpdateSelectedMoods() {
            const swiper = document.querySelector("#moodSwiper");
            if (swiper === null) {
                return;
            }

            swiper.querySelectorAll(`div > div > div.active`).forEach(el => {
                el.classList.remove("active");
            });

            this.selectedMoods.forEach(selectedMood => {
                for (const [index, mood] of this.moods.entries()) {
                    if (mood.mood_id === selectedMood.mood_id) {
                        swiper
                            .querySelectorAll(
                                `div > [data-swiper-slide-index="${index}"] > div`
                            )
                            .forEach(el => {
                                el.classList.add("active");
                            });
                    }
                }
            });
        },
        forceUpdateProducerSwiper() {
            this.isProducerSwiperUpdated = false;
            this.$nextTick(() => {
                this.isProducerSwiperUpdated = true;
            });
        },
        toggleSelectedMood(selectedMood) {
            if (!this.isMoodEditMode) {
                return;
            }

            if (!this.isMoodSelected(selectedMood)) {
                if (this.selectedMoods.length < 3) {
                    this.$store.commit(
                        "updateSelectedMoods",
                        this.selectedMoods.concat([selectedMood])
                    );
                }
            } else {
                const editedMoods = this.selectedMoods.filter(
                    mood => mood.mood_id != selectedMood.mood_id
                );
                this.$store.commit("updateSelectedMoods", editedMoods);
            }
        },
        isMoodSelected(mood) {
            return this.selectedMoods
                .map(mood => mood.mood_id)
                .includes(mood.mood_id);
        },
        deselectSelectedMood(selectedMood) {
            if (!this.isMoodEditMode) {
                return;
            }

            if (this.isMoodSelected(selectedMood)) {
                this.toggleSelectedMood(selectedMood);
            }
        },
        startMoodEdit() {
            if (!this.isUser) {
                this.popupMessage = `<b>로그인</b>이 필요한 서비스입니다.<br>로그인 하시겠습니까?`;
                this.isPopupVisible = true;
                return;
            }

            if (this.isMoodEditMode) {
                return;
            }

            this.savedSelectedMoods = this.selectedMoods.slice(0); //복사
            this.isMoodEditMode = true;
        },
        async saveMoodEdit() {
            this.$store.commit("updateIsGlobalLoading", true);
            if (!this.isMoodEditMode) {
                return;
            }

            try {
                await this.$http.post("/api/mood_selects", {
                    mood_s_selects: this.selectedMoods.map(mood => mood.mood_id)
                });

                this.isBeatChartLoading = false;
                this.savedSelectedMoods = [];

                this.isMoodEditMode = false;
                await this.loadBeatChartList();
            } catch (e) {
                console.log(e);
                this.cancelMoodEdit();
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        cancelMoodEdit() {
            if (!this.isMoodEditMode) {
                return;
            }

            this.$store.commit(
                "updateSelectedMoods",
                this.savedSelectedMoods.slice(0)
            );
            this.isMoodEditMode = false;
        },
        async loadBeatChartList() {
            if (this.isBeatChartLoading) {
                return;
            }
            this.isBeatChartLoading = true;

            try {
                if (this.selectedMoods.length === 0) {
                    this.beatChartList = await this.$http
                        .get(`/api/beats`, {
                            params: {
                                req: "realtime_top"
                            }
                        })
                        .then(response => {
                            return response.data;
                        });
                    this.beatChartTitle = "실시간 TOP 100";
                    this.beatChartFilters = [];
                } else {
                    this.beatChartList = await this.$http
                        .get(`/api/beats`, {
                            params: {
                                req: "by_mood_top_50",
                                mood1: this.selectedMoods[0]
                                    ? this.selectedMoods[0].mood_id
                                    : null,
                                mood2: this.selectedMoods[1]
                                    ? this.selectedMoods[1].mood_id
                                    : null,
                                mood3: this.selectedMoods[2]
                                    ? this.selectedMoods[2].mood_id
                                    : null
                            }
                        })
                        .then(response => {
                            return response.data;
                        });
                    this.beatChartTitle = "분위기별 TOP 50";
                    this.beatChartFilters = this.selectedMoods.map(mood => {
                        return { id: mood.mood_id, title: mood.mood_title };
                    });
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.isBeatChartLoading = false;
            }
        },
        advertiseClick(/* advertise */) {
            // console.log(advertise);
        }
    }
};
</script>

<style lang="scss">
</style>
