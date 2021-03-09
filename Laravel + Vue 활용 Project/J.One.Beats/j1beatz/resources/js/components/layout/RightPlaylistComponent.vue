<template>
    <!-- 우측고정 재생목록 -->
    <aside
        v-show="isPlaylistVisible"
        id="playlist"
        class="playlist"
        :class="{active: isPlaylistEditing}"
        :style="{right: isPlaylistOpened ? '0px' : '-530px'}"
    >
        <button
            class="ctrl-btn ctrl-btn-close"
            type="button"
            @click="closePlaylist"
        >
            <span class="none">닫기버튼</span>
        </button>

        <h2>
            재생목록
            <a
                href="#"
                role="button"
                @click.prevent="cancelPlaylistEdit"
            >편집취소</a>
        </h2>

        <div class="playlist-sch-bar">
            <fieldset>
                <input
                    v-model="searchModel"
                    type="text"
                    placeholder="재생목록 검색"
                    input="searchIME = $event.target.value"
                    @keyup.enter="updatePlaylistRows"
                >
                <button
                    type="button"
                    class="btn-sch-icon"
                    @click="updatePlaylistRows"
                >
                    <span class="none">검색버튼</span>
                </button>
            </fieldset>
            <a
                v-if="isSearching"
                href="#"
                role="button"
                class="cancle_btn"
                @click.prevent="cancelSearchClick"
            >취소</a>

            <a
                href="#"
                role="button"
                class="right_edit"
                @click.prevent="startPlaylistEdit"
            >편집</a>
        </div>

        <div class="playlist-del-bar">
            <fieldset>
                <input
                    id="check-all-list"
                    v-model="isCheckAllChecked"
                    type="checkbox"
                    class="input-style-01"
                    @change="toggleCheckAllBeats()"
                >
                <label for="check-all-list">
                    <span class="none">곡 전체선택</span>
                </label>
                <label for="check-all-list">전체선택</label>

                <a
                    href="#"
                    role="button"
                    @click.prevent="savePlaylistEdit"
                >완료</a>
            </fieldset>
        </div>

        <div class="playlist-inner">
            <section class="playlist-inner-wrapper">
                <h3 class="none">
                    플레이리스트
                </h3>
                <div
                    id="playlist-container"
                    data-mcs-theme="minimal"
                    class="playlist-inner-container beat-ul"
                    :class="{selecting: isCheckAnyChecked}"
                >
                    <ul>
                        <li
                            v-for="(beat, index) in playlistRows"
                            :key="beat.beat_id"
                            class="beat-ul-list"
                        >
                            <input
                                :id="`check-this-list-${index}`"
                                v-model="beat.checked"
                                type="checkbox"
                                class="input-style-01"
                                :data-index="`${index}`"
                                @change="beatCheckChanged"
                            >
                            <label :for="`check-this-list-${index}`" />
                            <!-- 썸네일, 제목, 가수 -->
                            <div
                                class="thumb-mini-outer"
                                style="cursor: pointer;"
                                :style="{'background-image': `url(/fdata/beathumb/${beat.beat_thumb})`}"
                                @click="play(beat)"
                            />
                            <span class="song_title">{{ beat.beat_title }}</span>
                            <small>{{ beat.prdc_nick }}</small>
                            <!-- 썸네일, 제목, 가수 end -->

                            <!-- 앨범추가버튼, 구매버튼 -->
                            <div class="mini-btns">
                                <span
                                    class="mini-btn mini-album-btn"
                                    @click="addBeatToAlbum(beat)"
                                >
                                    <em class="none">앨범담기</em>
                                </span>
                                <span
                                    class="mini-btn mini-buy-btn"
                                    @click="buyBeat(beat)"
                                >
                                    <em class="none">구매하기</em>
                                </span>
                            </div>
                            <!-- 앨범추가버튼, 구매버튼 end -->

                            <!-- 더보기버튼 -->
                            <div
                                class="mini-btn mini-dot3-btn"
                                @click="showMoreClick(beat)"
                            >
                                <em class="none">더보기</em>
                                <span
                                    v-if="beat.more"
                                    v-on-clickaway="away"
                                    class="beat-pd-info-view"
                                >
                                    <a
                                        href="#"
                                        @click.prevent="beatInfoClick(beat)"
                                    >비트정보</a>
                                    <a
                                        href="#"
                                        @click.prevent="producerInfoClick(beat)"
                                    >프로듀서 정보</a>
                                </span>
                            </div>
                            <!-- 더보기버튼 end -->

                            <div
                                class="del-btn"
                                @click="removeBeat(beat)"
                            >
                                <em class="none">삭제하기</em>
                            </div>
                        </li>
                        <li
                            v-if="isSearching && playlistRows.length === 0"
                            class="nothing_result"
                        >
                            <img
                                class="nothing_result__img"
                                src="/img/icon/icon_notsearch.svg"
                                alt="nothing result"
                            >
                            <span class="nothing_result__ment">검색결과를 찾을 수 없습니다.</span>
                        </li>
                    </ul>
                </div>
            </section>
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
    </aside>
    <!-- //우측고정 재생목록 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { mixin as clickaway } from "vue-clickaway";
import isVisible from "is-element-visible";
import NoticePopupComponent from "../common/NoticePopupComponent";

export default {
    components: {
        NoticePopupComponent
    },
    mixins: [clickaway],
    data() {
        return {
            isSearching: false,
            isNoticePopupVisible: false,
            noticePopupMessage: "",
            //searchIME: "",
            searchModel: "",
            playlistRows: []
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            isPlaylistVisible: "isPlaylistVisible",
            isPlaylistOpened: "isPlaylistOpened",
            isPlaylistEditing: "isPlaylistEditing",
            currentPlayingBeat: "currentPlayingBeat",
            playlist: "playlist",
            editingPlaylist: "editingPlaylist",
            deletedBeats: "deletedBeats",
            beatAddToAlbum: ""
        }),
        isCheckAllChecked: {
            get() {
                if (this.playlistRows.length === 0) {
                    return false;
                }

                return this.playlistRows.every(beat => {
                    return beat.checked;
                });
            },
            set(newValue) {
                this.playlistRows = this.playlistRows.map(beat => {
                    beat.checked = newValue;
                    return beat;
                });
            }
        },
        isCheckAnyChecked() {
            return this.playlistRows.some(beat => {
                return beat.checked;
            });
        }
    },
    watch: {
        editingPlaylist() {
            if (this.isPlaylistEditing) {
                this.playlistRows = this.editingPlaylist.slice(0); // 복사
            }
        },
        playlist() {
            this.updatePlaylistRows();
        }
    },
    async mounted() {
        this.$nextTick(() => {
            this.$RENDER_JQUERY_mCustomScrollbar(
                "#playlist-container",
                "minimal"
            );
        });
    },
    async created() {
        await this.fetchData();
    },
    methods: {
        async fetchData() {
            if (!this.isUser) return;

            try {
                if (this.playlist.length === 0) {
                    const playlist = await this.$http
                        .get(`/api/playlists`)
                        .then(response => {
                            return response.data;
                        });

                    this.$store.commit("updatePlaylist", playlist);
                }
            } catch (e) {
                console.log(e);
            }
        },
        away() {
            this.playlistRows = this.playlist.map(beat => {
                return { ...beat, ...{ more: false } };
            });
        },
        updatePlaylistRows() {
            this.isSearching = this.searchModel !== "";

            this.playlistRows = this.playlist
                .filter(beat => {
                    return (
                        beat.beat_title
                            .toLowerCase()
                            .includes(this.searchModel.toLowerCase()) ||
                        beat.prdc_nick
                            .toLowerCase()
                            .includes(this.searchModel.toLowerCase())
                    );
                })
                .map(beat => {
                    return { ...beat, ...{ checked: false, more: false } };
                });
            this.$store.commit("updateEditingPlaylist", []);
        },
        closePlaylist() {
            if (this.isPlaylistEditing) {
                this.cancelPlaylistEdit();
            }
            this.$store.commit("updateIsPlaylistOpened", false);
        },
        addBeatToAlbum(beat) {
            this.$store.commit("updateIsMyAlbumPopupVisible", true);
            this.$store.commit("updateBeatAddToAlbum", [beat]);
        },
        async buyBeat(beat) {
            // 일단 장바구니에 담음
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

                window.fbq("track", "AddToCart", {
                    value: beat.beat_price,
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
        removeBeat(beat) {
            this.$store.commit(
                "updateEditingPlaylist",
                this.editingPlaylist.filter(
                    editingBeat => editingBeat.beat_id != beat.beat_id
                )
            );
            this.$store.commit(
                "updateDeletedBeats",
                this.deletedBeats.concat([beat])
            );
        },
        startPlaylistEdit() {
            this.$store.commit("updateIsPlaylistEditing", true);
            this.$store.commit("updateEditingPlaylist", this.playlistRows);
        },
        async savePlaylistEdit() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                const deletedBeats = this.deletedBeats.map(
                    beat => beat.beat_id
                );

                const updatedPlaylist = this.playlist.filter(beat => {
                    return !deletedBeats.includes(beat.beat_id);
                });

                await this.$http.post("/api/playlists", {
                    playlist: updatedPlaylist.map(beat => beat.beat_id)
                });

                this.$store.commit("updateIsPlaylistEditing", false);
                this.$store.commit("updatePlaylist", updatedPlaylist);
                this.$store.commit("updateDeletedBeats", []);
                this.$store.commit("updateEditingPlaylist", []);
            } catch (e) {
                console.log(e);
                this.cancelPlaylistEdit();
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        cancelPlaylistEdit() {
            this.$store.commit("updateIsPlaylistEditing", false);
            this.$store.commit("updatePlaylist", this.playlist.slice(0));
            this.$store.commit("updateDeletedBeats", []);
            this.$store.commit("updateEditingPlaylist", []);
        },
        beatCheckChanged(e) {
            this.$nextTick(() => {
                const index = Number(e.target.dataset.index) + 1;
                const clickedEls = document
                    .getElementById("playlist-container")
                    .getElementsByTagName("ul")[0]
                    .querySelectorAll(
                        `li.beat-ul-list:nth-child(${index}),
                         li.beat-ul-list:nth-child(${index + 1})`
                    );

                if (
                    (clickedEls.length == 1 && !isVisible(clickedEls[0])) ||
                    (clickedEls.length == 2 && !isVisible(clickedEls[1]))
                ) {
                    this.$RENDER_JQUERY_mCustomScrollbar_scrollTo(
                        "#playlist-container",
                        `-=150`
                    );
                }
            });
            this.$store.commit("updateEditingPlaylist", this.playlistRows);
        },
        toggleCheckAllBeats() {
            this.playlistRows = this.editingPlaylist.map(beat => {
                return { ...beat, ...{ checked: this.isCheckAllChecked } };
            });
            this.$store.commit("updateEditingPlaylist", this.playlistRows);
        },
        cancelSearchClick() {
            //this.searchIME = "";
            this.searchModel = "";
            this.updatePlaylistRows();
        },
        play(beat) {
            if (this.isPlaylistEditing) {
                return;
            }

            this.$store.commit("updateIsPlaylistPlaying", true);
            this.$store.commit("updateCurrentPlayingBeat", { ...beat });
        },
        showMoreClick(beat) {
            this.playlistRows.forEach(beat => {
                if (beat.more === true) {
                    beat.more = false;
                }
            });
            beat.more = true;
        },
        beatInfoClick(beat) {
            setTimeout(() => {
                this.away();
            });
            this.$router.push(`/beat-info/${beat.beat_id}`, () => {});
        },
        producerInfoClick(beat) {
            setTimeout(() => {
                this.away();
            });
            this.$router.push(`/producer-info/${beat.prdc_id}`, () => {});
        }
    }
};
</script>

<style lang="scss">
@import "../../../styles/scss/layouts/right-playlist";
</style>
