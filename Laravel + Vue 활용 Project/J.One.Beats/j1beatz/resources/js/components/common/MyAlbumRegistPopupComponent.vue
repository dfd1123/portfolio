<template>
    <div v-show="isMyAlbumPopupVisible">
        <div class="black-overlay" style="display: initial" />
        <div class="popup-layer">
            <div class="popup-myalbumchoice popup-01">
                <h4 class="popup-layer-title">마이앨범</h4>
                <div class="popup-layer-txt">
                    <div class="label-select-group">
                        <span class="select-folder-label">폴더선택</span>
                        <div class="select-folder">
                            <label for="check-show-folder-list" class="_label">
                                <em :class="{_create_btn:addfolder}">{{ selectFolderTitle }}</em>
                            </label>

                            <input
                                id="check-show-folder-list"
                                v-model="checked"
                                type="checkbox"
                                class="none-input"
                            />
                            <ul class="select-folder-group">
                                <li
                                    v-for="folder in playfolderlist"
                                    :key="folder.pf_id"
                                    class="select-folder-list"
                                    @click="selectedFolder(folder)"
                                >{{ folder.pf_title }}</li>
                                <li
                                    class="select-folder-list _create_btn"
                                    @click="selectAddFolder()"
                                >+ 새 폴더 만들기</li>
                            </ul>
                        </div>
                    </div>

                    <div v-if="addfolder" class="label-select-group">
                        <span class="select-folder-label">새 폴더</span>
                        <div class="select-folder">
                            <input
                                v-model="newFolderTitle"
                                type="text"
                                placeholder="폴더명을 입력하세요."
                                class="_input"
                            />
                        </div>
                    </div>
                </div>
                <div class="btn-group _double">
                    <button
                        ref="button"
                        type="button"
                        class="btn btn-02"
                        @click="leftButtonClick"
                    >취소</button>
                    <button type="button" class="btn btn-01" @click="playfolderAdd">추가하기</button>
                </div>
                <button type="button" class="popup-layer-close-btn" @click="closeButtonClick">
                    <span class="none">팝업 닫기버튼</span>
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState, mapGetters } from "vuex";
import NoticePopupComponent from "../common/NoticePopupComponent";

export default {
    props: {
        closeText: {
            type: String,
            default: "확인"
        }
    },
    data() {
        return {
            playfolderlist: [],
            addfolder: false,
            selectFolderTitle: "폴더명",
            selectFolderId: "",
            checked: false,
            newFolderTitle: "",
            isNoticePopupVisible: false,
            noticePopupMessage: ""
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            isMyAlbumPopupVisible: "isMyAlbumPopupVisible",
            selectedBeats: "selectedBeats",
            beatAddToAlbum: "beatAddToAlbum"
        })
    },
    watch: {
        isUser() {
            this.loadPlayfolderList();
        },
        async isMyAlbumPopupVisible() {
            if (this.isMyAlbumPopupVisible) {
                await this.loadPlayfolderList();

                if (!this.isUser) {
                    this.$store.commit("updateIsMyAlbumPopupVisible", false);
                }
                this.$nextTick(() => {
                    this.$refs.button.focus();
                    this.$refs.button.blur();
                });
            }
        }
    },
    async created() {
        if (!this.isUser) {
            return;
        }
    },
    methods: {
        leftButtonClick() {
            this.$store.commit("updateIsMyAlbumPopupVisible", false);
            this.$emit("leftButtonClick");
            this.selectFolderTitle = "폴더명";
            this.selectFolderId = "";
            this.addfolder = false;
            this.checked = false;
            this.newFolderTitle = "";
            this.$store.commit("updateBeatAddToAlbum", []);
        },
        rightButtonClick() {
            this.$store.commit("updateIsMyAlbumPopupVisible", false);
            this.$emit("rightButtonClick");
            this.selectFolderTitle = "폴더명";
            this.selectFolderId = "";
            this.addfolder = false;
            this.checked = false;
            this.newFolderTitle = "";
        },
        closeButtonClick() {
            this.$store.commit("updateIsMyAlbumPopupVisible", false);
            this.$emit("closeButtonClick");
            this.selectFolderTitle = "폴더명";
            this.selectFolderId = "";
            this.addfolder = false;
            this.checked = false;
            this.newFolderTitle = "";
            this.$store.commit("updateBeatAddToAlbum", []);
        },
        async loadPlayfolderList() {
            try {
                this.playfolderlist = await this.$http
                    .get(`/api/playfolders`)
                    .then(response => {
                        return response.data;
                    });
            } catch (e) {
                console.log(e);
            }
        },
        selectedFolder(folder) {
            this.selectFolderTitle = folder.pf_title;
            this.selectFolderId = folder.pf_id;
            this.checked = false;
            this.addfolder = false;
            this.newFolderTitle = "";
        },
        selectAddFolder() {
            this.selectFolderTitle = "+ 새 폴더 만들기";
            this.selectFolderId = "";
            this.addfolder = true;
            this.checked = false;
            this.newFolderTitle = "";
        },
        async playfolderAdd() {
            if (this.selectFolderTitle == "폴더명" && !this.addfolder) {
                return false;
            } else {
                if (!this.addfolder) {
                    var beat = "";
                    if (this.beatAddToAlbum) {
                        beat = this.beatAddToAlbum.map(beat => {
                            return beat.beat_id;
                        });
                    } else {
                        beat = this.selectedBeats.map(beat => {
                            return beat.beat_id;
                        });
                    }
                    try {
                        this.$store.commit("updateIsGlobalLoading", true);

                        await this.$http
                            .put(`/api/playfolders/${this.selectFolderId}`, {
                                req: "addbeat_at_playfolder",
                                beats: beat
                            })
                            .then(response => {
                                return response.data;
                            });
                        this.isNoticePopupVisible = true;
                        this.noticePopupMessage = "마이앨범에 추가되었습니다.";
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.$store.commit("updateIsGlobalLoading", false);
                    }
                } else {
                    try {
                        this.$store.commit("updateIsGlobalLoading", true);

                        await this.$http
                            .post(`/api/playfolders`, {
                                req: "addbeat_at_newplayfolder",
                                pf_title: this.newFolderTitle,
                                beats: this.selectedBeats.map(beat => {
                                    return beat.beat_id;
                                })
                            })
                            .then(response => {
                                return response.data;
                            });
                        this.isNoticePopupVisible = true;
                        this.noticePopupMessage = "마이앨범에 추가되었습니다.";
                    } catch (e) {
                        console.log(e);
                    } finally {
                        this.$store.commit("updateIsGlobalLoading", false);
                    }
                }
            }
            this.$store.commit("updateIsMyAlbumPopupVisible", false);
            this.selectFolderTitle = "폴더명";
            this.selectFolderId = "";
            this.addfolder = false;
            this.checked = false;
            this.newFolderTitle = "";
        }
    }
};
</script>

<style lang="scss">
.popup-layer .select-folder ._label em._create_btn {
    color: #ff6767;
}
</style>
