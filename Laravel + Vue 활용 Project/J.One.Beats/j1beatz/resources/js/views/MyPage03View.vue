<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <!--TODO:-->
        <section class="section sub-section">
            <header-component ver="bg_gray_ver" />

            <my-page-tab-component />

            <div class="page-contents-wrapper">
                <div class="sub-page-mypage">
                    <div class="sub-page-mypage-03">
                        <div
                            v-if="!isPlayfolderEdit"
                            class="in-title"
                        >
                            <span
                                class="in-title-right in-title-right-del"
                                @click="isPlayfolderEdit = true"
                            >
                                <a
                                    href="#"
                                    role="button"
                                >삭제</a>
                            </span>
                        </div>

                        <div
                            v-else
                            class="in-title in-title-edit_mode"
                        >
                            <span class="in-title-left in-title-left-all_chck">
                                <a
                                    href="#"
                                    role="button"
                                    class="_all_check"
                                >전체선택</a>
                            </span>
                            <span class="in-title-right">
                                <a
                                    href="#"
                                    role="button"
                                    class="_del_btn"
                                    @click="checkedDelete"
                                >삭제하기</a>
                                <a
                                    href="#"
                                    role="button"
                                    @click="isPlayfolderEdit=false"
                                >완료</a>
                            </span>
                        </div>

                        <div class="myalbum-list">
                            <ul>
                                <li
                                    class="myalbum-card myalbum-plus-folder"
                                    @click="addfolder"
                                >
                                    <figure class="myalbum-thumb">
                                        <figcaption class="none">
                                            마이앨범 폴더썸네일
                                        </figcaption>
                                    </figure>
                                    <span>폴더 추가</span>
                                </li>

                                <li
                                    v-for="(playfolder,index) in playfolderlist"
                                    :key="playfolder.pf_id"
                                    class="myalbum-card"
                                >
                                    <input
                                        v-if="isPlayfolderEdit"
                                        :id="`check-this-album${index}`"
                                        v-model="playfoldercheck"
                                        type="checkbox"
                                        class="input-style-03"
                                        :value="playfolder.pf_id"
                                    >
                                    <label
                                        :for="`check-this-album${index}`"
                                        :style="{cursor: isPlayfolderEdit ? 'pointer' : 'initial'}"
                                    />
                                    <figure
                                        class="myalbum-thumb myalbum-thumb-not_song"
                                        :style="{cursor: !isPlayfolderEdit ? 'pointer' : 'initial'}"
                                        @click.prevent="showPlayfoler(playfolder)"
                                    >
                                        <figcaption class="none">
                                            마이앨범 폴더썸네일
                                        </figcaption>
                                    </figure>
                                    <div class="myalbum-info">
                                        <dl>
                                            <dt>{{ playfolder.pf_title }}</dt>
                                            <dd class="_total_song">
                                                총
                                                <em>{{ playfolder.pf_qtt }}</em>곡
                                            </dd>
                                            <dd class="_data">
                                                {{ toMoment(playfolder.created_at) }}
                                            </dd>
                                        </dl>
                                        <button
                                            type="button"
                                            class="btn"
                                        >
                                            <span class="none">버튼</span>
                                        </button>
                                    </div>
                                </li>

                                <!-- <li class="myalbum-card">
                                    <input
                                        id="check-this-album2"
                                        type="checkbox"
                                        class="input-style-03"
                                    >
                                    <label for="check-this-album2" />
                                    <figure class="myalbum-thumb">
                                        <img
                                            src="img/example/myalbum-thumnail01.png"
                                            alt="myalbum-thumbnail"
                                        >
                                        <img
                                            src="img/example/myalbum-thumnail02.png"
                                            alt="myalbum-thumbnail"
                                        >
                                        <img
                                            src="img/example/myalbum-thumnail01.png"
                                            alt="myalbum-thumbnail"
                                        >
                                        <img
                                            src="img/example/myalbum-thumnail02.png"
                                            alt="myalbum-thumbnail"
                                        >
                                        <figcaption class="none">
                                            마이앨범 폴더썸네일
                                        </figcaption>
                                    </figure>
                                    <div class="myalbum-info">
                                        <dl>
                                            <dt>폴더명을 입력하세요</dt>
                                            <dd class="_total_song">
                                                총 <em>0</em>곡
                                            </dd>
                                            <dd class="_data">
                                                2000. 00. 00
                                            </dd>
                                        </dl>
                                        <button
                                            type="button"
                                            class="btn"
                                        >
                                            <span class="none">버튼</span>
                                        </button>
                                    </div>
                                </li>

                                <li class="myalbum-card">
                                    <input
                                        id="check-this-album3"
                                        type="checkbox"
                                        class="input-style-03"
                                    >
                                    <label for="check-this-album3" />
                                    <figure class="myalbum-thumb">
                                        <img
                                            src="img/example/myalbum-thumnail01.png"
                                            alt="myalbum-thumbnail"
                                        >
                                        <img
                                            src="img/example/myalbum-thumnail02.png"
                                            alt="myalbum-thumbnail"
                                        >
                                        <img
                                            src="img/example/myalbum-thumnail01.png"
                                            alt="myalbum-thumbnail"
                                        >
                                        <img
                                            src="img/example/myalbum-thumnail02.png"
                                            alt="myalbum-thumbnail"
                                        >
                                        <figcaption class="none">
                                            마이앨범 폴더썸네일
                                        </figcaption>
                                    </figure>
                                    <div class="myalbum-info">
                                        <dl>
                                            <dt>폴더명을 입력하세요</dt>
                                            <dd class="_total_song">
                                                총 <em>0</em>곡
                                            </dd>
                                            <dd class="_data">
                                                2000. 00. 00
                                            </dd>
                                        </dl>
                                        <button
                                            type="button"
                                            class="btn"
                                        >
                                            <span class="none">버튼</span>
                                        </button>
                                    </div>
                                </li>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <license-info-popup :visible.sync="isLicensePopupVisible" />
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import MyPageTabComponent from "../components/my-page/MyPageTabComponent";
import LicenseInfoPopup from "../components/common/LicenseInfoPopup";
import moment from "moment";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        MyPageTabComponent,
        LicenseInfoPopup
    },
    data() {
        return {
            isLicensePopupVisible: false,
            playfolderlist: [],
            isPlayfolderEdit: false,
            playfoldercheck: []
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user"
        })
    },
    async created() {
        await this.loadPlayfolderList();
    },
    methods: {
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
        toMoment(datetime) {
            return moment(datetime).format("YYYY. MM. DD HH:mm");
        },
        checkedDelete() {
            this.playfoldercheck.forEach(pf_id => {
                this.deletePlayfolder(pf_id);
            });
            this.isPlayfolderEdit = false;
        },
        async deletePlayfolder(pf_id) {
            try {
                await this.$http
                    .delete(`/api/playfolders/${pf_id}`)
                    .then(response => {
                        return response.data;
                    });
            } catch (e) {
                console.log(e);
            }
            this.playfolderlist = this.playfolderlist.filter(folder => {
                return folder.pf_id != pf_id;
            });
        },
        async addfolder() {
            try {
                await this.$http
                    .post(`/api/playfolders`, {
                        req: "addnewfolder"
                    })
                    .then(response => {
                        return response.data;
                    });
                await this.loadPlayfolderList();
            } catch (e) {
                console.log(e);
            }
        },
        showPlayfoler(playfolder) {
            if (!this.isPlayfolderEdit) {
                this.$router.push(`my-page-03-1/${playfolder.pf_id}`, () => {});
            }
        }
    }
};
</script>

<style lang="scss">
</style>
