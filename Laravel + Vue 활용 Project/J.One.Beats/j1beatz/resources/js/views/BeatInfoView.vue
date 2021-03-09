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
                        class="beat-profile"
                    >
                        <figure
                            class="beat-profile-thumb"
                            :style="{'background-image': `url(/fdata/beathumb/${beatInfo.beat_thumb})`}"
                        >
                            <figcaption class="none">
                                프로듀서 프로필사진
                            </figcaption>
                            <button
                                type="button"
                                class="btn_beat_info_play"
                                @click="play"
                            >
                                <b class="none">재생</b>
                            </button>
                        </figure>
                        <div
                            class="beat-profile-detail"
                        >
                            <dl>
                                <dt>
                                    {{ beatInfo.beat_title }}
                                    <button
                                        type="button"
                                        class="btn_info_share"
                                        @click="share"
                                    />
                                </dt>
                                <dd
                                    class="_pd_name btn_beat_info_prdcer"
                                    @click="$router.push(`/producer-info/${producerInfo.prdc_id}`)"
                                >
                                    {{ producerInfo.prdc_nick }}
                                </dd>
                            </dl>
                            <ul class="_pd_summing_up">
                                <li class="_play-li">
                                    {{ prefixNum(beatInfo.beat_hit) }}
                                </li>
                                <li class="_like-li">
                                    {{ prefixNum(beatInfo.beat_like) }}
                                </li>
                            </ul>
                            <button
                                v-if="beatInfo.beat_free === 1"
                                type="button"
                                class="btn_beat_info_down"
                                @click="isDownPopupVisible = true"
                            >
                                FREE 다운로드
                            </button>
                        </div>
                    </div>

                    <div class="beat-info-wrapper">
                        <div class="beat-info-container">
                            <div class="inner-group">
                                <h3>구매하기</h3>
                                <div class="ticket-con ticket-con-delux">
                                    <span
                                        class="ticket-con-label"
                                    >deluxe</span>
                                    <ul>
                                        <li>
                                            <b v-if="isLoaded">{{ Number(beatInfo.beat_price).toLocaleString() }}원</b>
                                            <br>
                                            <a
                                                href="#"
                                                role="button"
                                                @click.prevent="isLicensePopupVisible = true"
                                            >음원이용범위 보기</a>
                                        </li>
                                        <li>
                                            <span>파일형식</span><br><b>mp3, </b><b>wav</b>
                                        </li>
                                        <li class="_button_list">
                                            <button
                                                type="button"
                                                class="btn"
                                                @click="cart"
                                            >
                                                구매하기
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div
                                class="inner-group"
                            >
                                <h3>사용중인 이용권</h3>
                                <div
                                    v-if="user.license.lcens_type === 1"
                                    class="ticket-con"
                                >
                                    <span class="ticket-con-label">무제한 스트리밍</span>
                                    <ul>
                                        <li>
                                            <b>무제한 스트리밍 이용중</b><br>
                                        </li>
                                        <li>
                                            <span>파일형식</span><br><b>제공되지 않음 (듣기만 가능)</b>
                                        </li>
                                    </ul>
                                </div>
                                <div
                                    v-else
                                    class="ticket-con ticket-con-nothing"
                                >
                                    <p><b>사용중인 이용권이 없습니다.</b><br><span>이용권을 구매하여 전곡을 감상해보세요.</span></p>
                                    <button
                                        class="btn"
                                        @click="$router.push('/license-info')"
                                    >
                                        이용권 구매하기
                                    </button>
                                </div>
                            </div>
                            <div
                                class="inner-group"
                            >
                                <h3>(추천) 이 프로듀서의 TOP 10</h3>
                                <div
                                    class="this-pd-recommend-list _slick_panel "
                                >
                                    <ul
                                        v-if="bestTenProducerBeatsSlickUpdated"
                                        id="producer-info-slick"
                                    >
                                        <li
                                            v-for="beat in bestTenProducerBeats"
                                            :key="beat.beat_id"
                                            class="result-card _banner"
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
                            <div
                                class="inner-group"
                            >
                                <h3>(추천) 이 곡의 분위기 TOP 10</h3>
                                <div
                                    class="this-pd-recommend-list _slick_panel"
                                >
                                    <ul
                                        v-if="bestTenMoodBeatsSlickUpdated"
                                        id="mood-info-slick"
                                    >
                                        <li
                                            v-for="beat in bestTenMoodBeats"
                                            :key="beat.beat_id"
                                            class="result-card _banner"
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
                        </div>

                        <div class="beat-info-container">
                            <div class="inner-group">
                                <h3>비트정보</h3>
                                <div class="beat-info-detail-panel">
                                    <ul>
                                        <li class="_subject">
                                            분위기
                                        </li>
                                        <li>
                                            <span
                                                v-for="mood in beatMoods"
                                                :key="mood.mood_id"
                                                class="mood-genre-choice-btn"
                                            ><label><span>{{ mood.mood_title }}</span></label></span>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li class="_subject">
                                            장르
                                        </li>
                                        <li>
                                            <span
                                                v-for="genre in beatGenres"
                                                :key="genre.cate_id"
                                                class="mood-genre-choice-btn"
                                            ><label><span>{{ genre.cate_title }}</span></label></span>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li class="_subject">
                                            시간
                                        </li>
                                        <li><span>{{ beatInfo.beat_time }}</span></li>
                                    </ul>
                                    <ul>
                                        <li class="_subject">
                                            태그
                                        </li>
                                        <li>
                                            <span
                                                v-for="(tag, index) in beatTags"
                                                :key="index"
                                                class="_tag"
                                            >{{ '#' + tag }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="inner-group">
                                <h3>코멘트</h3>
                                <div class="beat-info-comment-panel">
                                    <div
                                        v-if="beatCommentUpdated"
                                        id="comment-panel"
                                        class="_scroll-area"
                                        data-mcs-theme="minimal-dark"
                                    >
                                        <ul
                                            v-for="comment in beatComments"
                                            :key="comment.cmt_id"
                                        >
                                            <li class="_name">
                                                {{ comment.user_nick }}
                                            </li>
                                            <li class="_reple_txt">
                                                {{ comment.cmt_content }}
                                                <em class="_data">{{ toMoment(comment.created) }}</em>
                                                <em
                                                    v-if="isUser && comment.user_id === user.user_id"
                                                    class="_option_btns"
                                                >
                                                    <a
                                                        v-if="!isEditingComment"
                                                        href="#"
                                                        role="button"
                                                        class="_edit_btn"
                                                        @click.prevent="editComment(comment)"
                                                    >수정</a>
                                                    <a
                                                        v-if="!isEditingComment"
                                                        href="#"
                                                        role="button"
                                                        class="_del_btn"
                                                        @click.prevent="removeComment(comment)"
                                                    >삭제</a>
                                                    <a
                                                        v-if="isEditingComment && editingComment.cmt_id === comment.cmt_id"
                                                        href="#"
                                                        role="button"
                                                        class="_edit_btn"
                                                        @click.prevent="cancelEditComment()"
                                                    >취소</a>
                                                    <a
                                                        v-if="isEditingComment && editingComment.cmt_id === comment.cmt_id"
                                                        href="#"
                                                        role="button"
                                                        class="_del_btn"
                                                        @click.prevent="saveComment(comment)"
                                                    >저장</a>
                                                </em>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="beat-info-comment-write">
                                        <input
                                            v-model="editingCommentText"
                                            type="text"
                                            placeholder="내용을 입력하세요."
                                        >
                                        <button
                                            v-if="!isEditingComment"
                                            type="button"
                                            class="btn"
                                            @click="registerComment"
                                        >
                                            등록
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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

        <confirm-popup-component
            title-text="확인"
            :visible.sync="isEditConfirmPopupVisible"
            :right-button-text="editConfirmPopupRightText"
            @rightButtonClick="confirmAction"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="editConfirmPopupText"
            />
        </confirm-popup-component>

        <license-info-popup
            :visible.sync="isLicensePopupVisible"
        />

        <down-info-popup
            :visible.sync="isDownPopupVisible"
            @agreeclick="free"
        />
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { prefixNum } from "../lib/common";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";
import LicenseInfoPopup from "../components/common/LicenseInfoPopup";
import DownInfoPopup from "../components/common/DownInfoPopup";
import moment from "moment";
import { saveAs } from "file-saver";

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
        NoticePopupComponent,
        ConfirmPopupComponent,
        LicenseInfoPopup,
        DownInfoPopup
    },
    data() {
        return {
            isLoaded: false,
            isConfirmPopupVisible: false,
            isNoticePopupVisible: false,
            isLicensePopupVisible: false,
            isDownPopupVisible: false,
            isEditConfirmPopupVisible: false,
            isEditingComment: false,
            noticePopupMessage: "",
            id: this.$route.params.id,
            beatInfo: {},
            producerInfo: {},
            bestTenProducerBeats: [],
            bestTenProducerBeatsSlickUpdated: false,
            bestTenMoodBeats: [],
            bestTenMoodBeatsSlickUpdated: false,
            beatComments: [],
            beatCommentUpdated: false,
            editingComment: {},
            editingCommentText: "",
            editConfirmPopupText: "",
            editConfirmPopupRightText: "",
            confirmAction: () => {}
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
        beatMoods() {
            if (!this.beatInfo.beat_id) {
                return [];
            }

            return this.moods.filter(
                mood => this.beatInfo.mood_id === mood.mood_id
            );
        },
        beatGenres() {
            if (!this.beatInfo.beat_id) {
                return [];
            }

            return this.genres.filter(
                genre => this.beatInfo.cate_id === genre.cate_id
            );
        },
        beatTags() {
            if (!this.beatInfo.beat_id) {
                return [];
            }

            return this.parseBeatTags(this.beatInfo.beat_tag);
        }
    },
    watch: {
        async $route() {
            this.id = this.$route.params.id;
            await this.fetchData();
        },
        async currentPlayingBeat() {
            if (this.beatInfo.beat_id === this.currentPlayingBeat.beat_id) {
                try {
                    this.beatInfo = await this.$http
                        .get(`/api/beats/${this.id}`)
                        .then(response => {
                            if (!response.data.prdc_id) {
                                throw new Error("beatInfo load fail");
                            }
                            return response.data;
                        });
                } catch (e) {
                    console.log(e);
                }
            }
        },
        bestTenProducerBeats() {
            this.forceUpdateBestTenProducerBeatsSlick();
        },
        bestTenMoodBeats() {
            this.forceUpdateBestTenMoodBeatsSlick();
        },
        beatComments() {
            this.forceUpdateBeatComments();
        }
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        prefixNum,
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                this.isLoaded = false;

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

                try {
                    this.beatInfo = await this.$http
                        .get(`/api/beats/${this.id}`)
                        .then(response => {
                            if (!response.data.prdc_id) {
                                throw new Error("beatInfo load fail");
                            }
                            return response.data;
                        });
                } catch (e) {
                    this.$router.push("/404");
                }

                try {
                    this.producerInfo = await this.$http
                        .get(`/api/producers/${this.beatInfo.prdc_id}`)
                        .then(response => {
                            if (!response.data.prdc_id) {
                                throw new Error("producerInfo load fail");
                            }
                            return response.data;
                        });
                } catch (e) {
                    this.$router.push("/404");
                }

                this.$http
                    .get(`/api/beats`, {
                        params: {
                            req: "by_prdc_id_top_10",
                            prdc_id: this.producerInfo.prdc_id
                        }
                    })
                    .then(response => {
                        this.bestTenProducerBeats = response.data;
                    });

                this.$http
                    .get(`/api/beats`, {
                        params: {
                            req: "by_mood_id_top_10",
                            mood_id: this.beatInfo.mood_id
                        }
                    })
                    .then(response => {
                        this.bestTenMoodBeats = response.data;
                    });

                this.loadComments();

                this.isLoaded = true;
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async loadComments() {
            await this.$http
                .get(`/api/comments`, {
                    params: {
                        req: "by_beat_id",
                        beat_id: this.beatInfo.beat_id
                    }
                })
                .then(response => {
                    this.beatComments = response.data.map(comment => {
                        comment.isEditing = false;
                        return comment;
                    });
                });
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
        forceUpdateBestTenProducerBeatsSlick() {
            this.bestTenProducerBeatsSlickUpdated = false;
            this.$nextTick(() => {
                this.bestTenProducerBeatsSlickUpdated = true;
                this.$nextTick(() => {
                    const vm = this;
                    const slickId = "#producer-info-slick";

                    this.$RENDER_JQUERY_slick(slickId, {
                        variableWidth: true,
                        slidesToShow: 5,
                        slidesToSlide: 1,
                        swipeToSlide: true,
                        autoplay: true,
                        pauseOnFocus: true,
                        pauseOnHover: true
                    });

                    $(slickId)
                        .off("click")
                        .on("click", function(e) {
                            const clicked = $(e.target).closest("li");
                            if (clicked.is("._banner")) {
                                const slideClicked = clicked.attr(
                                    "data-slick-index"
                                );
                                vm.bestTenProducerBeatsSlickClick({
                                    ...vm.bestTenProducerBeats[
                                        Number(
                                            slideClicked %
                                                vm.bestTenProducerBeats.length
                                        )
                                    ]
                                });
                            }
                        });
                });
            });
        },
        bestTenProducerBeatsSlickClick(beat) {
            if (this.beatInfo.beat_id === beat.beat_id) {
                this.noticePopupMessage = "현재 보고계신 페이지입니다";
                this.isNoticePopupVisible = true;
            }

            this.$router.push(`/beat-info/${beat.beat_id}`, () => {});
        },
        forceUpdateBestTenMoodBeatsSlick() {
            this.bestTenMoodBeatsSlickUpdated = false;
            this.$nextTick(() => {
                this.bestTenMoodBeatsSlickUpdated = true;
                this.$nextTick(() => {
                    const vm = this;
                    const slickId = "#mood-info-slick";

                    this.$RENDER_JQUERY_slick(slickId, {
                        variableWidth: true,
                        slidesToShow: 5,
                        slidesToSlide: 1,
                        swipeToSlide: true,
                        autoplay: true,
                        pauseOnFocus: true,
                        pauseOnHover: true
                    });

                    $(slickId)
                        .off("click")
                        .on("click", function(e) {
                            const clicked = $(e.target).closest("li");
                            if (clicked.is("._banner")) {
                                const slideClicked = clicked.attr(
                                    "data-slick-index"
                                );
                                vm.bestTenMoodBeatsSlickClick({
                                    ...vm.bestTenMoodBeats[
                                        Number(
                                            slideClicked %
                                                vm.bestTenMoodBeats.length
                                        )
                                    ]
                                });
                            }
                        });
                });
            });
        },
        bestTenMoodBeatsSlickClick(beat) {
            if (this.beatInfo.beat_id === beat.beat_id) {
                this.noticePopupMessage = "현재 보고계신 페이지입니다";
                this.isNoticePopupVisible = true;
            }

            this.$router.push(`/beat-info/${beat.beat_id}`, () => {});
        },
        forceUpdateBeatComments() {
            this.beatCommentUpdated = false;
            this.$nextTick(() => {
                this.beatCommentUpdated = true;
                this.$nextTick(() => {
                    this.$RENDER_JQUERY_mCustomScrollbar(
                        "#comment-panel",
                        "minimal-dark"
                    );
                });
            });
        },
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD HH:mm");
        },
        async registerComment() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            if (this.editingCommentText === "") {
                this.noticePopupMessage = "댓글 내용을 입력해주시기 바랍니다";
                this.isNoticePopupVisible = true;
                return;
            }

            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.post(`/api/comments`, {
                    beat_id: this.beatInfo.beat_id,
                    cmt_content: this.editingCommentText
                });
                await this.loadComments();
                this.editingCommentText = "";
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        editComment(comment) {
            this.editingComment = comment;
            this.editingCommentText = comment.cmt_content;
            this.isEditingComment = true;
        },
        async removeComment(comment) {
            this.editConfirmPopupText = "해당 댓글을 삭제하시겠습니까?";
            this.editConfirmPopupRightText = "삭제하기";
            this.confirmAction = async () => {
                try {
                    this.$store.commit("updateIsGlobalLoading", true);

                    await this.$http.delete(`/api/comments/${comment.cmt_id}`);
                    await this.loadComments();
                } catch (e) {
                    console.log(e);
                } finally {
                    this.confirmAction = () => {};
                    this.$store.commit("updateIsGlobalLoading", false);
                }
            };
            this.isEditConfirmPopupVisible = true;
        },
        cancelEditComment() {
            this.confirmAction = () => {};
            this.editingComment = {};
            this.editingCommentText = "";
            this.isEditingComment = false;
        },
        async saveComment(comment) {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.put(`/api/comments/${comment.cmt_id}`, {
                    cmt_content: this.editingCommentText
                });
                await this.loadComments();
            } catch (e) {
                console.log(e);
            } finally {
                this.cancelEditComment();
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async cart() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.post("/api/carts", {
                    beat_id: this.beatInfo.beat_id
                });
                this.$store.commit("updateCartlist", []);

                window.fbq("track", "AddToCart", {
                    value: this.beatInfo.beat_price,
                    currency: "KRW"
                });

                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "해당 비트를 장바구니에 추가했습니다";
            } catch (e) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "이미 장바구니에 들어있거나 최근 구매한 비트입니다";
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        play() {
            this.$store.commit("updateIsPlaylistPlaying", false);
            this.$store.commit("updateCurrentPlayingBeat", {
                ...this.beatInfo
            });
        },
        async share() {
            const { protocol, hostname } = window.location;
            this.$clipboard(
                `${protocol}//${hostname}/beat-info/${this.beatInfo.beat_id}`
            );

            this.noticePopupMessage =
                "해당 비트 링크가 <br> 클립보드에 복사되었습니다. <br> 원하는 곳에 붙여넣으세요";
            this.isNoticePopupVisible = true;
        },
        async free() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http
                    .get(`/file/free/${this.beatInfo.beat_id}`, {
                        header: {
                            Accept: "audio/mpeg"
                        },
                        responseType: "arraybuffer"
                    })
                    .then(response => {
                        // response.data is an empty object
                        saveAs(
                            new Blob([response.data]),
                            `${this.beatInfo.beat_title}.mp3`
                        );
                    });
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
</style>
