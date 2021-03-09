<template>
    <div id="wrap">
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <my-page-tab-component />
            <div class="page-contents-wrapper">
                <div class="sub-page-mypage">
                    <div class="sub-page-mypage-03-detail">
                        <div
                            class="myalbum-setting"
                            :class="{active:titleEdit}"
                        >
                            <input
                                id="playfolder_title"
                                type="text"
                                :value="beatFolderTitle"
                                :placeholder="isLoaded ? '마이앨범 폴더명' : ''"
                                :disabled="!titleEdit"
                            >
                            <img
                                src="/img/icon/icon-edit-purple.svg"
                                class="edit-icon"
                                @click="titleEdit = true"
                            >
                            <span
                                v-if="titleEdit"
                                class="_after_btn"
                            >
                                <a
                                    href="#"
                                    role="button"
                                    @click="titleEditcancel"
                                >취소</a>
                                <a
                                    href="#"
                                    role="button"
                                    class="_complt"
                                    @click="titleEditcomplete"
                                >완료</a>
                            </span>
                        </div>

                        <article class="main-panel-article">
                            <beat-list-component
                                height="500"
                                :beats="beatChartList"
                                :rank="true"
                                @beatListUpdate="loadBeatChartList"
                            />
                        </article>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>
    </div>
</template>
<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import MyPageTabComponent from "../components/my-page/MyPageTabComponent";
import BeatListComponent from "../components/common/BeatListComponent";

import moment from "moment";

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
        MyPageTabComponent,
        BeatListComponent
    },
    data() {
        return {
            isLoaded: false,
            isLicensePopupVisible: false,
            isBeatChartLoading: false,
            beatChartList: [],
            beatFolderTitle: "",
            titleEdit: false,
            id: Number(this.$route.params.id)
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            deletedBeats: "deletedBeats"
        })
    },
    watch: {
        $route() {
            this.id = Number(this.$route.params.id);
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
        },
        deletedBeats() {
            this.loadBeatChartList();
        }
    },
    async created() {
        try {
            this.$store.commit("updateIsGlobalLoading", true);
            await this.folderTitle();
            await this.loadBeatChartList();
            this.isLoaded = true;
        } finally {
            this.$store.commit("updateIsGlobalLoading", false);
        }
    },
    async destroyed() {
        this.$store.commit("updatePlayfolder", {});
    },
    methods: {
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD HH:mm");
        },
        async folderTitle() {
            const currentfolder = await this.$http
                .get(`/api/playfolders/${this.id}`, {
                    params: {
                        req: "detail"
                    }
                })
                .then(response => {
                    return response.data;
                });
            this.$store.commit("updatePlayfolder", currentfolder[0]);
            this.beatFolderTitle = currentfolder[0].pf_title;
        },
        async loadBeatChartList() {
            if (this.isBeatChartLoading) {
                return;
            }

            this.isBeatChartLoading = true;

            try {
                this.beatChartList = await this.$http
                    .get(`/api/beats`, {
                        params: {
                            req: "by_user_folder",
                            pf_id: this.id
                        }
                    })
                    .then(response => {
                        return response.data;
                    });
            } catch (e) {
                console.log(e);
            } finally {
                this.isBeatChartLoading = false;
            }
        },
        titleEditcancel() {
            $("#playfolder_title").val(this.beatFolderTitle);
            this.titleEdit = false;
        },
        async titleEditcomplete() {
            try {
                await this.$http
                    .put(`/api/playfolders/${this.id}`, {
                        req: "titleupdate",
                        pf_title: $("#playfolder_title").val()
                    })
                    .then(response => {
                        return response.data;
                    });
                this.beatFolderTitle = $("#playfolder_title").val();
                this.titleEdit = false;
            } catch (e) {
                console.log(e);
            } finally {
                this.isBeatChartLoading = false;
            }
        }
    }
};
</script>

<style lang="scss">
#playfolder_title {
    background: transparent;
}
.myalbum-setting .edit-icon {
    position: absolute;
    top: 50%;
    right: 0;
    transform: translate(-50%, -50%);
    cursor: pointer;
}
.myalbum-setting.active .edit-icon {
    display: none;
}
</style>
