<template>
    <!-- 하단고정 재생바 -->
    <div
        v-show="isPlaybarVisible"
        id="playbar"
        class="playbar"
    >
        <section
            class="playbar-inner"
            style="position: relative;"
        >
            <loading-indicator-component :is-loading="isLoading" />
            <h3 class="none">
                재생하기
            </h3>

            <div
                v-show="currentPlayingBeat.beat_id"
                class="playbar-info01"
            >
                <div
                    class="thumb"
                    :style="{'background-image': `url(/fdata/beathumb/${currentPlayingBeat.beat_thumb})`}"
                >
                    <span class="none">앨범제목</span>
                </div>
                <div class="title_singer_info">
                    <p class="title">
                        {{ currentPlayingBeat.beat_title }}
                    </p>
                    <p class="artist">
                        {{ currentPlayingBeat.prdc_nick }}
                    </p>
                </div>
            </div>

            <div class="playbar-control-area">
                <div class="playbar-btns">
                    <button
                        type="button"
                        class="ctrl-btn-repeat ctrl-btn"
                        :class="{active: isRepeat}"
                        @click="repeat"
                    >
                        <span class="none">반복 재생</span>
                    </button>
                    <button
                        type="button"
                        class="ctrl-btn-prv-play ctrl-btn"
                        @click="prevPlay"
                    >
                        <span class="none">이전곡 재생</span>
                    </button>

                    <button
                        v-if="isPlaying"
                        type="button"
                        class="ctrl-btn-list-palse ctrl-btn"
                        @click="palse"
                    >
                        <span class="none">현재곡 정지</span>
                    </button>
                    <button
                        v-else
                        type="button"
                        class="ctrl-btn-list-play ctrl-btn"
                        @click="play"
                    >
                        <span class="none">현재곡 재생</span>
                    </button>

                    <button
                        type="button"
                        class="ctrl-btn-next-play ctrl-btn"
                        @click="nextPlay"
                    >
                        <span class="none">다음곡 재생</span>
                    </button>
                    <button
                        type="button"
                        class="ctrl-btn-shuffle ctrl-btn"
                        :class="{active: isShuffle}"
                        @click="shuffle"
                    >
                        <span class="none">셔틀 설정</span>
                    </button>
                </div>
                <div class="playbar-time">
                    <span class="current_time time">{{ currentTime }}</span>
                    <div class="playbar-time-progress-range">
                        <input
                            v-model.number="currentProgress"
                            type="range"
                            min="0"
                            max="100"
                            step="any"
                            :disabled="!player"
                            @mousedown="seekStart"
                            @mouseup="seekEnd"
                            @mouseleave="seekEnd"
                        >
                        <span
                            class="playbar-time-progress-bar"
                            :style="{width: `${currentProgress}%`}"
                        />
                    </div>
                    <span class="all_time time">{{ totalTime }}</span>
                </div>
            </div>

            <div class="playbar-info02">
                <button
                    type="button"
                    class="ctrl-btn-like-song ctrl-btn"
                    :class="{active: currentPlayingBeat.bl_state === 1}"
                    @click="like"
                />
                <button
                    type="button"
                    class="ctrl-btn-volume-ctrl ctrl-btn"
                    :class="{active: isVolumeControlVisible}"
                    @click="isVolumeControlVisible = true"
                />
                <button
                    type="button"
                    class="ctrl-btn-show-list ctrl-btn"
                    :class="{active: isPlaylistOpened}"
                    @click="togglePlayList"
                />
                <button
                    type="button"
                    class="btn-buy btn btn-01"
                    @click="cart"
                >
                    구매하기
                </button>
                <span
                    v-if="isVolumeControlVisible"
                    v-on-clickaway="away"
                    class="volume-ctrl-panel"
                >
                    <span class="volume-ctrl-panel__inner">
                        <input
                            v-model="currentVolume"
                            type="range"
                            min="0"
                            max="100"
                            value="0"
                        >
                        <span
                            class="volume-ctrl-progress-bar"
                            :style="{width: `${currentVolume}%`}"
                        />
                    </span>
                </span>
            </div>
        </section>

        <div
            v-if="currentOptionArea == 'purchase' || currentOptionArea == 'playbar'"
            class="playbar-option-bar option-bar"
        >
            <div
                :style="{display: currentOptionArea == 'purchase' ? 'block' : 'none'}"
                class="option-disable"
            >
                <p>
                    <b>사용중인 이용권이 없습니다.</b>
                    <br>
                    <span>이용권을 구매하여 전곡을 감상해보세요.</span>
                </p>
                <button
                    type="button"
                    class="btn _buy_btn"
                    @click.prevent="$router.push('/license-info', () => {})"
                >
                    이용권 구매하기
                </button>
                <button
                    type="button"
                    class="_x_btn"
                    @click="closeNoticeToPurchase"
                />
            </div>

            <div
                :style="{display: currentOptionArea == 'playbar' ? 'block' : 'none'}"
                class="option-able"
            >
                <span class="option-total-song-amount">
                    <b>{{ selectedBeats.length }}</b>곡
                </span>
                <button
                    type="button"
                    class="option-btn option-btn-all_no"
                    @click="unselectAllBeats"
                >
                    전체해제
                </button>
                <button
                    v-if="selectedBeats.length === 1"
                    type="button"
                    class="option-btn option-btn-play"
                    @click="playFromBeatList(selectedBeats[0])"
                >
                    듣기
                </button>
                <button
                    type="button"
                    class="option-btn option-btn-list"
                    @click="appendAllSelectedBeatsToPlaylist"
                >
                    재생목록
                </button>
                <button
                    type="button"
                    class="option-btn option-btn-cart"
                    @click="cartSelectedBeats"
                >
                    장바구니
                </button>
                <button
                    v-if="selectedBeats.length === 1"
                    type="button"
                    class="option-btn option-btn-share"
                    @click="share"
                >
                    공유하기
                </button>
                <button
                    v-if="$route.name !== 'my-page-03-1'"
                    type="button"
                    class="option-btn option-btn-album"
                    @click="myalbumadd"
                >
                    마이앨범
                </button>
                <button
                    v-if="$route.name === 'my-page-03-1'"
                    type="button"
                    class="option-btn option-btn-del"
                    @click="myalbumdelete"
                >
                    선택삭제
                </button>
            </div>
        </div>

        <div
            v-show="currentOptionArea == 'playlist'"
            class="playlist-option-bar option-bar"
        >
            <div class="option-able">
                <span class="option-total-song-amount">
                    <b>{{ selectedBeatsInPlaylist.length }}</b>곡
                </span>
                <button
                    type="button"
                    class="option-btn option-btn-all_no"
                    @click="unselectAllPlaylist"
                >
                    전체해제
                </button>
                <button
                    type="button"
                    class="option-btn option-btn-cart"
                    @click="cartSelectedBeatsInPlaylist"
                >
                    장바구니
                </button>
                <button
                    type="button"
                    class="option-btn option-btn-del"
                    @click="deleteAllSelectedBeatsFromPlaylist"
                >
                    선택삭제
                </button>
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
                v-html="`<b>로그인</b>이 필요한 서비스입니다.<br>로그인 하시겠습니까?`"
            />
        </confirm-popup-component>
    </div>
    <!-- //하단고정 재생바 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { mixin as clickaway } from "vue-clickaway";
import { padPrefix } from "../../lib/common";
import { Howl, Howler } from "howler";
import NoticePopupComponent from "../common/NoticePopupComponent";
import ConfirmPopupComponent from "../common/ConfirmPopupComponent";
import LoadingIndicatorComponent from "../common/LoadingIndicatorComponent";

export default {
    components: {
        NoticePopupComponent,
        ConfirmPopupComponent,
        LoadingIndicatorComponent
    },
    mixins: [clickaway],
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            isVolumeControlVisible: false,
            isPurchaseOptionVisible: false,
            isNoticeToPurchaseVisible: false,
            isPlaying: false,
            isPaused: false,
            isRepeat: false,
            isShuffle: false,
            isLoading: false,
            isSeeking: false,
            confirmPopupMessage: "",
            noticePopupMessage: "",
            currentProgress: 0,
            currentVolume: 50,
            currentTime: "00:00",
            player: null,
            progressLoop: null
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user",
            isPlaybarVisible: "isPlaybarVisible",
            isPlaylistOpened: "isPlaylistOpened",
            isPlaylistEditing: "isPlaylistEditing",
            isPlaylistPlaying: "isPlaylistPlaying",
            selectedBeats: "selectedBeats",
            currentPlayingBeat: "currentPlayingBeat",
            playlist: "playlist",
            editingPlaylist: "editingPlaylist",
            playfolder: "playfolder",
            deletedBeats: "deletedBeats"
        }),
        isAnyCheckedBeatInPlaylist() {
            return this.editingPlaylist.some(beat => {
                return beat.checked;
            });
        },
        selectedBeatsInPlaylist() {
            return this.editingPlaylist.filter(
                editingBeat => editingBeat.checked
            );
        },
        currentOptionArea() {
            if (
                this.isUser &&
                this.isPlaylistOpened &&
                this.isPlaylistEditing &&
                this.isAnyCheckedBeatInPlaylist
            ) {
                return "playlist";
            } else if (this.isUser && this.selectedBeats.length > 0) {
                return "playbar";
            }

            if (this.isNoticeToPurchaseVisible) {
                return "purchase";
            }

            return "none";
        },
        currentBeatIndex() {
            if (!this.isPlaylistPlaying) {
                return -1;
            }

            return this.playlist.findIndex(playlistBeat => {
                return playlistBeat.beat_id === this.currentPlayingBeat.beat_id;
            });
        },
        firstBeat() {
            if (!this.isPlaylistPlaying) {
                return null;
            }

            const first = this.playlist[0];
            if (first === undefined) {
                return null;
            }

            return first;
        },
        lastBeat() {
            if (!this.isPlaylistPlaying) {
                return null;
            }

            const last = this.playlist[this.playlist.length - 1];
            if (last === undefined) {
                return null;
            }

            return last;
        },
        nextBeat() {
            if (!this.isPlaylistPlaying) {
                return null;
            }

            const next = this.playlist[this.currentBeatIndex + 1];
            if (next === undefined) {
                return null;
            }

            return next;
        },
        prevBeat() {
            if (!this.isPlaylistPlaying) {
                return null;
            }

            const prev = this.playlist[this.currentBeatIndex - 1];
            if (prev === undefined) {
                return null;
            }

            return prev;
        },
        totalTime() {
            if (this.player !== null) {
                return this.secondsToMinutes(this.player.duration());
            }

            return "00:00";
        }
    },
    watch: {
        isPlaybarVisible() {
            if (this.isPlaybarVisible === false) {
                this.palse();
            }
        },
        user() {
            if (this.user.license.lcens_type !== undefined) {
                this.isNoticeToPurchaseVisible = false;
            }
        },
        currentPlayingBeat(newVal, oldVal) {
            if (newVal.beat_id) {
                if (oldVal.beat_id) {
                    if (
                        newVal.beat_id === oldVal.beat_id &&
                        oldVal.bl_state !== newVal.bl_state
                    ) {
                        return;
                    }
                }

                if (newVal.beat_id === oldVal.beat_id) {
                    if (this.player !== null && this.player.playing()) {
                        return;
                    }
                }

                this.unloadPlayer();
                this.loadPlayer();
            } else {
                this.unloadPlayer();
            }
        },
        currentProgress() {
            if (this.isSeeking) {
                if (this.player !== null) {
                    const seekTime =
                        (this.currentProgress / 100) * this.player.duration() ||
                        0;
                    this.currentTime = this.secondsToMinutes(seekTime);
                }
            }
        },
        currentVolume() {
            Howler.volume(this.currentVolume / 100);
        }
    },
    mounted() {
        this.checkLicensePopup();
    },
    methods: {
        padPrefix,
        away() {
            this.isVolumeControlVisible = false;
        },
        checkLicensePopup() {
            if (
                !this.isUser ||
                (this.isUser && this.user.license.lcens_type === undefined)
            ) {
                this.isNoticeToPurchaseVisible = true;
            }
        },
        play() {
            if (this.player !== null) {
                this.player.play();
            }
        },
        palse() {
            // pause...
            if (this.player !== null) {
                this.player.pause();
            }
        },
        nextPlay() {
            if (!this.isUser) {
                return;
            }

            if (!this.isPlaylistPlaying) {
                return;
            }

            if (this.isShuffle) {
                const randomBeat = this.getRandomBeatFromPlaylist();
                if (randomBeat.beat_id !== this.currentPlayingBeat.beat_id) {
                    this.$store.commit("updateCurrentPlayingBeat", randomBeat);
                }
            }

            if (this.nextBeat === null && this.firstBeat !== null) {
                this.$store.commit("updateCurrentPlayingBeat", this.firstBeat);
            } else {
                this.$store.commit("updateCurrentPlayingBeat", this.nextBeat);
            }
        },
        prevPlay() {
            if (!this.isUser) {
                return;
            }

            if (!this.isPlaylistPlaying) {
                return;
            }

            if (this.prevBeat === null && this.lastBeat !== null) {
                this.$store.commit("updateCurrentPlayingBeat", this.lastBeat);
            } else {
                this.$store.commit("updateCurrentPlayingBeat", this.prevBeat);
            }
        },
        repeat() {
            if (!this.isUser) {
                return;
            }

            this.isRepeat = !this.isRepeat;
        },
        shuffle() {
            if (!this.isUser) {
                return;
            }

            this.isShuffle = !this.isShuffle;
        },
        async like() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            if (!this.currentPlayingBeat.beat_id) {
                return;
            }

            try {
                const blState = this.currentPlayingBeat.bl_state === 1 ? 0 : 1;

                await this.$http.post("/api/beat_likes", {
                    beat_id: this.currentPlayingBeat.beat_id,
                    state: blState
                });

                this.$store.commit("updateCurrentPlayingBeat", {
                    ...this.currentPlayingBeat,
                    ...{ bl_state: blState }
                });
            } catch (e) {
                console.log(e);
            }
        },
        async cart() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            if (!this.currentPlayingBeat.beat_id) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "현재 재생 중인 비트가 없습니다";
                return;
            }

            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.post("/api/carts", {
                    beat_id: this.currentPlayingBeat.beat_id
                });
                this.$store.commit("updateCartlist", []);

                window.fbq("track", "AddToCart", {
                    value: this.currentPlayingBeat.beat_price,
                    currency: "KRW"
                });

                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "현재 재생 중인 비트를 <br> 장바구니에 추가했습니다";
            } catch (e) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "이미 장바구니에 들어있거나 최근 구매한 비트입니다";
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        share() {
            if (this.selectedBeats.length !== 1) {
                return;
            }

            const { protocol, hostname } = window.location;
            this.$clipboard(
                `${protocol}//${hostname}/beat-info/${
                    this.selectedBeats[0].beat_id
                }`
            );

            this.isNoticePopupVisible = true;
            this.noticePopupMessage =
                "해당 곡 링크가 <br> 클립보드에 복사되었습니다. <br> 원하는 곳에 붙여넣으세요";
        },
        myalbumadd() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }
            this.$store.commit("updateIsMyAlbumPopupVisible", true);
        },
        async myalbumdelete() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http
                    .put(`/api/playfolders/${this.playfolder.pf_id}`, {
                        req: "deletebeat_at_playfolder",
                        beats: this.selectedBeats.map(beat => {
                            return beat.beat_id;
                        })
                    })
                    .then(response => {
                        return response.data;
                    });
                this.$store.commit("updateDeletedBeats", []);
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        togglePlayList() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            this.$store.commit(
                "updateIsPlaylistOpened",
                !this.isPlaylistOpened
            );
        },
        closeNoticeToPurchase() {
            this.isNoticeToPurchaseVisible = false;
        },
        unselectAllBeats() {
            this.$store.commit("updateSelectedBeats", []);
        },
        unselectAllPlaylist() {
            this.$store.commit(
                "updateEditingPlaylist",
                this.editingPlaylist.map(beat => {
                    beat.checked = false;
                    return beat;
                })
            );
        },
        async appendAllSelectedBeatsToPlaylist() {
            const beatsAppendToPlaylist = this.selectedBeats.slice(0);

            const updatedPlaylist = this.filterDuplicatedBeat(
                this.playlist.concat(beatsAppendToPlaylist)
            );

            try {
                const editedBeatIdList = updatedPlaylist.map(
                    beat => beat.beat_id
                );

                await this.$http.post("/api/playlists", {
                    playlist: editedBeatIdList
                });

                this.$store.commit("updatePlaylist", updatedPlaylist);
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "곡이 재생목록에 추가되었습니다. <br> (중복곡은 제외됩니다)";
            } catch (e) {
                console.log(e);
            }
        },
        deleteAllSelectedBeatsFromPlaylist() {
            const editedBeats = this.editingPlaylist.filter(
                editingBeat => !editingBeat.checked
            );
            const deletedBeats = this.editingPlaylist.filter(
                editingBeat => editingBeat.checked
            );

            this.$store.commit("updateEditingPlaylist", editedBeats);
            this.$store.commit(
                "updateDeletedBeats",
                this.deletedBeats.concat(deletedBeats)
            );
        },
        filterDuplicatedBeat(playlist) {
            const seen = new Set();
            return playlist.filter(beat => {
                const duplicate = seen.has(beat.beat_id);
                seen.add(beat.beat_id);
                return !duplicate;
            });
        },
        async loadPlayer() {
            try {
                this.isLoading = true;
                this.player = new Howl({
                    src: [await this.generateUrl()],
                    html5: true,
                    onplay: () => {
                        this.isPlaying = true;
                        this.isPaused = false;
                        if (this.progressLoop === null) {
                            this.step();
                        }
                    },
                    onpause: () => {
                        this.isPlaying = false;
                        this.isPaused = true;
                    },
                    onload: () => {
                        this.player.play();
                        this.isLoading = false;
                    },
                    onend: () => {
                        this.isLoading = false;
                        this.isPlaying = false;
                        this.isPaused = false;
                        this.player.stop();
                        this.updateProgress(0);
                        this.loadNextPlaylistBeat();
                    },
                    onstop: () => {
                        this.isLoading = false;
                    },
                    onplayerror: (errorId, messageAndCode) => {
                        console.log([errorId, messageAndCode]);
                        this.isNoticePopupVisible = true;
                        this.noticePopupMessage =
                            "곡 재생 중 에러가 발생했습니다.";
                        this.unloadPlayer();
                        Howler.unload();
                        this.$store.commit("updateCurrentPlayingBeat", {});
                        this.isLoading = false;
                        this.isPlaying = false;
                        this.isPaused = false;
                    },
                    onloaderror: () => {
                        this.isNoticePopupVisible = true;
                        this.noticePopupMessage =
                            "곡 로딩 중 에러가 발생했습니다.";
                        this.unloadPlayer();
                        Howler.unload();
                        this.$store.commit("updateCurrentPlayingBeat", {});
                        this.isLoading = false;
                        this.isPlaying = false;
                        this.isPaused = false;
                    }
                });
            } catch (e) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage = "곡 로딩 중 에러가 발생했습니다.";
                this.isLoading = false;
                this.unloadPlayer();
                Howler.unload();
                this.$store.commit("updateCurrentPlayingBeat", {});
                console.log(e);
            }
        },
        step() {
            try {
                if (!this.isSeeking) {
                    if (this.player !== null) {
                        const seek = this.player.seek();
                        if (this.isPlayerStreamLoading()) {
                            this.isLoading = true;
                            if (
                                this.player.playing() &&
                                this.player.state() === "loaded"
                            ) {
                                this.player.stop();
                            }
                        } else {
                            this.updateProgress(seek);
                            this.isLoading = false;
                        }
                    }
                }
                if (!this.isPlaying || this.isPaused) {
                    this.isLoading = false;
                }
            } catch (e) {
                console.error(e);
            } finally {
                if (this.isPlaying && !this.isPaused) {
                    this.progressLoop = setTimeout(() => {
                        this.step();
                    }, 200);
                } else {
                    this.progressLoop = null;
                }
            }
        },
        unloadPlayer() {
            if (this.player !== null) {
                this.isLoading = true;
                this.player.stop(); // this.isLoading = false;
                this.player.unload();
                this.player = null;
            }
            this.updateProgress(0);
        },
        isPlayerStreamLoading() {
            if (this.player === null) {
                return false;
            }

            return Number.isNaN(Number(this.player.seek()));
        },
        loadNextPlaylistBeat() {
            if (!this.isPlaylistPlaying) {
                if (this.isRepeat) {
                    this.player.play();
                }
            } else {
                if (this.playlist.length === 0) {
                    this.unloadPlayer();
                    Howler.unload();
                    this.$store.commit("updateCurrentPlayingBeat", {});
                }

                if (this.nextBeat === null) {
                    if (this.isRepeat || this.isShuffle) {
                        this.nextPlay();
                        return;
                    }

                    this.unloadPlayer();
                } else {
                    this.nextPlay();
                }
            }
        },
        seekStart() {
            if (this.isSeeking || this.isLoading) {
                return;
            }

            if (this.player !== null) {
                if (!this.isPlayerStreamLoading()) {
                    if (!this.loading) {
                        this.isSeeking = true;
                        Howler.mute(true);
                    }
                }
            }
        },
        seekEnd() {
            if (!this.isSeeking) {
                return;
            }

            if (this.player !== null) {
                if (!this.isPlayerStreamLoading()) {
                    if (!this.loading) {
                        const seekTime =
                            (this.currentProgress / 100) *
                                this.player.duration() || 0;
                        this.player.seek(seekTime);
                        Howler.mute(false);
                    }
                }
            }

            this.isSeeking = false;
        },
        updateProgress(seek) {
            let duration = 0;

            if (this.player !== null) {
                duration = this.player.duration();
            }

            let progress = (seek / duration) * 100 || 0;
            if (progress > 100) {
                progress = 100;
            }

            this.currentSeek = seek;
            this.currentTime = this.secondsToMinutes(seek);
            this.currentProgress = progress;
        },
        getRandomBeatFromPlaylist() {
            return this.playlist[
                Math.floor(Math.random() * this.playlist.length)
            ];
        },
        playFromBeatList(beat) {
            this.$store.commit("updateIsPlaylistPlaying", false);
            this.$store.commit("updateCurrentPlayingBeat", beat);
        },
        async generateUrl() {
            if (
                localStorage.laravel_token &&
                this.user.license.lcens_type === 1
            ) {
                const url = await this.$http
                    .post("/api/request_url", {
                        beat_id: this.currentPlayingBeat.beat_id
                    })
                    .then(response => response.data.url);

                return `/file/temp/${url}`;
            }

            return `/fdata/clip/${this.currentPlayingBeat.beat_url}`;
        },
        secondsToMinutes(time) {
            const min = Math.floor(time / 60) || 0;
            const sec = Math.floor(time % 60) || 0;
            return padPrefix(min, 2, "0") + ":" + padPrefix(sec, 2, "0");
        },
        async cartSelectedBeats() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            await this.cartBeatList(this.selectedBeats);
        },
        async cartSelectedBeatsInPlaylist() {
            if (!this.isUser) {
                this.isConfirmPopupVisible = true;
                return;
            }

            await this.cartBeatList(this.selectedBeatsInPlaylist);
        },
        async cartBeatList(beats) {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                await this.$http.patch("/api/carts/collection", {
                    action: "store",
                    beat_ids: beats.map(beat => beat.beat_id)
                });
                this.$store.commit("updateCartlist", []);

                beats.forEach(beat => {
                    window.fbq("track", "AddToCart", {
                        value: beat.beat_price,
                        currency: "KRW"
                    });
                });

                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "선택된 비트를 장바구니에 추가했습니다. <br> 이미 장바구니에 들어있거나 <br> 최근 구매한 비트는 제외됩니다";
            } catch (e) {
                this.isNoticePopupVisible = true;
                this.noticePopupMessage =
                    "선택된 비트를 장바구니에 담는 중 오류가 발생했습니다";
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        }
    }
};
</script>

<style lang="scss">
@import "../../../styles/scss/layouts/bottom-playbar";
</style>
