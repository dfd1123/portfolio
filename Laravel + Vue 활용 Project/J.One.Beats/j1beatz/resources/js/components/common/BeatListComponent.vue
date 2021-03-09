<template>
    <div>
        <div
            v-if="title"
            class="in-title"
        >
            <h3 class="in-title-name">
                {{ title }}
            </h3>
            <span
                v-for="filter in filters"
                :key="filter.id"
                class="in_choice_mood"
            >{{ filter.title }}</span>
        </div>

        <div class="beat_chart_list">
            <table>
                <thead>
                    <tr>
                        <th
                            v-if="isUser"
                            class="th-check"
                        >
                            <div>
                                <input
                                    id="check-all-song"
                                    v-model="isCheckAllChecked"
                                    type="checkbox"
                                    title="곡 전체선택"
                                    class="input-style-01"
                                    @change="toggleCheckAllBeats()"
                                >
                                <label
                                    :style="{visibility: isPlaylistEditing ? 'hidden' : 'visible'}"
                                    for="check-all-song"
                                >
                                    <span class="none">곡 전체선택하기</span>
                                </label>
                            </div>
                        </th>
                        <th
                            v-if="rank"
                            class="th-rank"
                        >
                            <div>순위</div>
                        </th>
                        <th class="th-rank_info">
                            <div class="none">
                                상승/하락폭
                            </div>
                        </th>
                        <th class="th-song">
                            <div>곡</div>
                        </th>
                        <th class="th-pd">
                            <div>프로듀서</div>
                        </th>
                        <th class="th-mood">
                            <div>분위기</div>
                        </th>
                        <th class="th-genre">
                            <div>장르</div>
                        </th>
                        <th class="th-play">
                            <div>듣기</div>
                        </th>
                        <th class="th-list">
                            <div>재생목록</div>
                        </th>
                        <th class="th-share">
                            <div>공유</div>
                        </th>
                        <th class="th-like">
                            <div>좋아요</div>
                        </th>
                        <th class="th-more">
                            <div>더보기</div>
                        </th>
                        <th class="th-cart">
                            <div>장바구니</div>
                        </th>
                    </tr>
                </thead>
            </table>

            <div
                id="beat-list"
                class="chart_scroll_area"
                :data-mcs-theme="scrollTheme"
                :style="{height: `${propheight}`}"
            >
                <table>
                    <tbody>
                        <tr
                            v-for="(beat, index) in beatRows"
                            :key="beat.beat_id"
                        >
                            <td
                                v-if="isUser"
                                class="td-check"
                            >
                                <div>
                                    <input
                                        :id="`check-each-song-${index}`"
                                        v-model="beat.checked"
                                        type="checkbox"
                                        title="곡 개별선택"
                                        class="input-style-01"
                                        @change="beatCheckChanged()"
                                    >
                                    <label
                                        :for="`check-each-song-${index}`"
                                        :style="{visibility: isPlaylistEditing ? 'hidden' : 'visible'}"
                                    >
                                        <span class="none">곡 개별선택하기</span>
                                    </label>
                                </div>
                            </td>
                            <td
                                v-if="rank"
                                class="td-rank"
                            >
                                <div>{{ index + 1 }}</div>
                            </td>
                            <td class="td-rank_info">
                                <!--
                                <div>ㅡ</div>
                                <div class="rank_info rank_info--up"><b class="rank_info__triangle"></b><em>2</em></div>
                                <div class="rank_info rank_info--down"><b class="rank_info__triangle"></b><em>2</em></div>
                                -->
                            </td>
                            <td class="td-song">
                                <div class="c-song-title">
                                    <span
                                        class="thumb-mini"
                                        :style="{'background-image': `url(/fdata/beathumb/${beat.beat_thumb})`}"
                                    >
                                        <span class="none">썸네일</span>
                                    </span>
                                    <span
                                        :style="{'cursor': 'pointer'}"
                                        @click.prevent="$router.push(`/beat-info/${beat.beat_id}`, () => {})"
                                    >{{ beat.beat_title }}</span>
                                </div>
                            </td>
                            <td class="td-pd">
                                <div
                                    :style="{'cursor': 'pointer'}"
                                    @click.prevent="$router.push(`/producer-info/${beat.prdc_id}`, () => {})"
                                >
                                    {{ beat.prdc_nick }}
                                </div>
                            </td>
                            <td class="td-mood">
                                <div>{{ beat.mood_title }}</div>
                            </td>
                            <td class="td-genre">
                                <div>{{ beat.cate_title }}</div>
                            </td>
                            <td class="td-play">
                                <button
                                    type="button"
                                    class="chart-ctrl-btn chart-ctrl-btn-play"
                                    @click="play(beat)"
                                >
                                    <span class="none">듣기 재생</span>
                                </button>
                            </td>
                            <td class="td-list">
                                <button
                                    type="button"
                                    class="chart-ctrl-btn chart-ctrl-btn-list"
                                    @click="addBeatToPlaylist(beat)"
                                >
                                    <span class="none">재생목록 담기</span>
                                </button>
                            </td>
                            <td class="td-share">
                                <button
                                    type="button"
                                    class="chart-ctrl-btn chart-ctrl-btn-share"
                                    @click="share(beat)"
                                >
                                    <span class="none">공유</span>
                                </button>
                            </td>
                            <td class="td-like">
                                <div>
                                    <button
                                        type="button"
                                        class="chart-ctrl-btn chart-ctrl-btn-like"
                                        :class="{active: beat.bl_state === 1}"
                                        @click="like(beat)"
                                    >
                                        <span class="none">좋아요</span>
                                    </button>
                                    <em>{{ prefixNum(beat.beat_like) }}</em>
                                </div>
                            </td>
                            <td class="td-more">
                                <div>
                                    <!-- 더보기버튼 -->
                                    <button
                                        type="button"
                                        class="chart-ctrl-btn chart-ctrl-btn-more"
                                        @click="more(beat)"
                                    >
                                        <span class="none">더보기</span>
                                    </button>
                                    <span
                                        v-if="beat.more"
                                        v-on-clickaway="away"
                                        class="beat-pd-info-view"
                                    >
                                        <a
                                            v-if="showBeatLink"
                                            href="#"
                                            @click.prevent="$router.push(`/beat-info/${beat.beat_id}`, () => {})"
                                        >비트정보</a>
                                        <a
                                            v-if="showProducerLink"
                                            href="#"
                                            @click.prevent="$router.push(`/producer-info/${beat.prdc_id}`, () => {})"
                                        >프로듀서 정보</a>
                                    </span>
                                    <!-- 더보기버튼 end -->
                                </div>
                            </td>
                            <td class="td-cart">
                                <div>
                                    <button
                                        type="button"
                                        class="chart-ctrl-btn-cart"
                                        @click="cart(beat)"
                                    >
                                        <span class="none">가격</span>
                                        <span class="chart-ctrl-btn" />
                                        <b>{{ Number(beat.beat_price).toLocaleString() }}</b>원
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { mixin as clickaway } from "vue-clickaway";
import { prefixNum } from "../../lib/common";
import NoticePopupComponent from "../common/NoticePopupComponent";
import ConfirmPopupComponent from "../common/ConfirmPopupComponent";

export default {
    components: {
        NoticePopupComponent,
        ConfirmPopupComponent
    },
    mixins: [clickaway],
    props: {
        title: {
            type: String,
            default: ""
        },
        height: {
            type: String,
            default: "500"
        },
        filters: {
            type: Array,
            default: () => []
        },
        beats: {
            type: Array,
            default: () => []
        },
        rank: {
            type: Boolean,
            default: false
        },
        scrollTheme: {
            type: String,
            default: "minimal"
        },
        showBeatLink: {
            type: Boolean,
            default: true
        },
        showProducerLink: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            noticePopupMessage: "",
            confirmPopupMessage: "",
            isCheckAllChecked: false,
            beatRows: []
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user",
            selectedBeats: "selectedBeats",
            playlist: "playlist",
            editingPlaylist: "editingPlaylist",
            isPlaylistEditing: "isPlaylistEditing",
            currentPlayingBeat: "currentPlayingBeat"
        }),
        propheight() {
            if (Number.isNaN(Number(this.height))) {
                return this.height;
            }

            return `${this.height}px`;
        }
    },
    watch: {
        beats() {
            this.beatRows = this.beats.map(beat => {
                return { ...beat, ...{ checked: false, more: false } };
            });
            this.$store.commit("updateSelectedBeats", []);
        },
        selectedBeats() {
            this.beatRows.forEach(beat => {
                const found = this.selectedBeats.find(
                    selectedBeat => selectedBeat.beat_id === beat.beat_id
                );
                beat.checked = found ? true : false;
            });

            this.isCheckAllChecked =
                this.beatRows.length !== 0 &&
                this.beatRows.length === this.selectedBeats.length;
        },
        isPlaylistEditing() {
            this.$store.commit("updateSelectedBeats", []);
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.$RENDER_JQUERY_mCustomScrollbar(
                "#beat-list",
                this.scrollTheme,
                {
                    callbacks: {
                        onTotalScroll: () => {
                            this.$emit("scrollAtBottom");
                        },
                        onTotalScrollOffset: 250
                    }
                }
            );
        });
    },
    destroyed() {
        this.$store.commit("updateSelectedBeats", []);
    },
    methods: {
        prefixNum,
        away() {
            this.beatRows.forEach(beat => {
                beat.more = false;
            });
        },
        beatCheckChanged() {
            this.$store.commit(
                "updateSelectedBeats",
                this.beatRows.filter(beat => beat.checked)
            );
        },
        toggleCheckAllBeats() {
            this.beatRows.forEach(beat => {
                beat.checked = this.isCheckAllChecked;
            });

            this.$store.commit(
                "updateSelectedBeats",
                this.beatRows.filter(beat => beat.checked)
            );
        },
        async addBeatToPlaylist(beat) {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            if (this.editingPlaylist.length > 0) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "재생목록 편집 중에는 곡을 추가할 수 없습니다.";
                return;
            }

            try {
                const duplicate = this.playlist.filter(playlistBeat => {
                    return playlistBeat.beat_id === beat.beat_id;
                });

                if (duplicate.length === 0) {
                    const updatedPlaylist = this.playlist.concat([beat]);
                    const updatedBeatIdList = updatedPlaylist.map(
                        beat => beat.beat_id
                    );

                    await this.$http.post("/api/playlists", {
                        playlist: updatedBeatIdList
                    });

                    this.$store.commit("updatePlaylist", updatedPlaylist);
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "곡이 재생목록에 추가되었습니다. <br> (중복곡은 제외됩니다)";
            }
        },
        play(beat) {
            this.$store.commit("updateIsPlaylistPlaying", false);
            this.$store.commit("updateCurrentPlayingBeat", { ...beat });
        },
        async share(beat) {
            const { protocol, hostname } = window.location;
            this.$clipboard(
                `${protocol}//${hostname}/beat-info/${beat.beat_id}`
            );

            this.noticePopupMessage =
                "해당 비트 링크가 <br> 클립보드에 복사되었습니다. <br> 원하는 곳에 붙여넣으세요";
            this.isNoticePopupVisible = true;
        },
        async like(beat) {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            try {
                const blState = beat.bl_state === 1 ? 0 : 1;

                await this.$http.post("/api/beat_likes", {
                    beat_id: beat.beat_id,
                    state: blState
                });

                if (this.currentPlayingBeat.beat_id === beat.beat_id) {
                    this.$store.commit("updateCurrentPlayingBeat", {
                        ...this.currentPlayingBeat,
                        ...{ bl_state: blState }
                    });
                } else {
                    this.$emit("beatListUpdate");
                }
            } catch (e) {
                console.log(e);
            }
        },
        async cart(beat) {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.post("/api/carts", {
                    beat_id: beat.beat_id
                });
                this.$store.commit("updateCartlist", []);

                window.fbq('track', 'AddToCart', {
                    value: beat.beat_price,
                    currency: 'KRW',
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
        more(beat) {
            this.beatRows.forEach(beat => {
                beat.more = false;
            });
            beat.more = true;
        }
    }
};
</script>

<style lang="scss">
</style>
