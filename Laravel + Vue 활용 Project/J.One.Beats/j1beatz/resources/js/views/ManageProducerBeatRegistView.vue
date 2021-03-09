<template>
    <!-- 메인 컨텐츠 -->
    <div
        id="wrap"
        class="manage-producer-beat"
    >
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <div class="in-title in-title-mini_ver">
                    <h3 class="in-title-name">
                        <b>유료용</b> 음원 파일 업로드(보이스태그 제거)
                    </h3>
                    <span class="in-title-ment">아래 버튼을 눌러 선택해주세요(wav 파일)</span>
                </div>
                <div class="filebox">
                    <input
                        class="upload-name file2"
                        value="파일선택"
                        disabled="disabled"
                    >
                    <label for="ex_filename">업로드</label>
                    <input
                        id="ex_filename"
                        ref="file2"
                        class="upload-hidden"
                        type="file"
                        accept=".wav"
                        @change="audioFileChange('file2')"
                    >
                </div>
                <audio
                    id="upload_wav"
                    type="hidden"
                />
                <div class="in-title in-title-mini_ver">
                    <h3 class="in-title-name">
                        <b>무료용/스트리밍용</b> 음원 파일 업로드(보이스태그 삽입 권장)
                    </h3>
                    <span class="in-title-ment">아래 버튼을 눌러 선택해주세요(MP3 파일)</span>
                </div>
                <div class="filebox">
                    <input
                        class="upload-name file3"
                        value="파일선택"
                        disabled="disabled"
                    >
                    <label for="ex_filename2">업로드</label>
                    <input
                        id="ex_filename2"
                        ref="file3"
                        class="upload-hidden"
                        type="file"
                        accept=".mp3"
                        @change="audioFileChange('file3')"
                    >
                </div>
                <div class="in-title in-title-mini_ver">
                    <h3 class="in-title-name">
                        제목
                    </h3>
                    <span class="in-title-ment">제목은 2~20글자 사이로 입력하세요</span>
                </div>
                <div>
                    <input
                        v-model="beat_title"
                        type="text"
                        class="input-form"
                    >
                </div>

                <div class="in-title in-title-mini_ver">
                    <h3 class="in-title-name">
                        가격
                    </h3>
                    <span class="in-title-ment">최소 금액 20,000원</span>
                </div>
                <div>
                    <input
                        v-model="beat_price"
                        type="number"
                        min="20000"
                        class="input-form"
                    >
                </div>

                <div class="in-title in-title-mini_ver">
                    <h3 class="in-title-name">
                        태그
                    </h3>
                    <span class="in-title-ment">ex) "#낭만 #감성 #밤"</span>
                </div>
                <div>
                    <input
                        v-model="beat_tag"
                        type="text"
                        class="input-form"
                    >
                </div>

                <div class="in-title in-title-mini_ver">
                    <h3 class="in-title-name">
                        썸네일
                    </h3>
                    <span class="in-title-ment" />
                </div>
                <div class="pd-profile-enroll-thumbnail">
                    <button
                        type="button"
                        :style="[files.file1 ? {opacity: 1} : []]"
                        @click="$refs.file1.click()"
                    >
                        <img
                            ref="img_file1"
                            src="img/icon/icon-edit-white.svg"
                            alt="사진등록 아이콘"
                        >
                        <input
                            ref="file1"
                            type="file"
                            class="none-input"
                            accept=".jpg, .jpeg, .gif, .svg, .png, .webp"
                            @change="imageFileChange('file1')"
                        >
                        <span>사진등록</span>
                    </button>
                </div>

                <div class="in-title in-title-mini_ver">
                    <h3 class="in-title-name">
                        주요 분위기 선택
                    </h3>
                    <span class="in-title-ment">(한 가지만 선택할 수 있습니다. 신중하게 선택해 주세요)</span>
                </div>
                <div class="main-mood-genre-choice">
                    <input
                        v-for="mood in moodItems"
                        :key="mood.mood_id"
                        :checked="mood.checked"
                        type="checkbox"
                        class="none-input"
                    >

                    <ul id="mood-ul">
                        <li
                            v-for="mood in moodItems"
                            :key="mood.mood_id"
                            class="mood-genre-choice-btn"
                            :class="{active: mood.checked}"
                        >
                            <label @click="toggleSelectedMood(mood)">
                                <span>{{ mood.mood_title }}</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="in-title in-title-mini_ver">
                    <h3 class="in-title-name">
                        주요 장르 선택
                    </h3>
                    <span class="in-title-ment">(한 가지만 선택할 수 있습니다. 신중하게 선택해 주세요)</span>
                </div>

                <div class="main-mood-genre-choice">
                    <input
                        v-for="genre in genreItems"
                        :key="genre.cate_id"
                        v-model="genre.checked"
                        type="checkbox"
                        class="none-input"
                    >

                    <ul>
                        <li
                            v-for="genre in genreItems"
                            :key="genre.cate_id"
                            class="mood-genre-choice-btn main-genre"
                            :class="{active: genre.checked}"
                        >
                            <label @click="toggleSelectedgenre(genre)">
                                <span>{{ genre.cate_title }}</span>
                            </label>
                        </li>
                    </ul>
                </div>
                <div class="in-title in-title-mini_ver">
                    <button
                        class="registbtn"
                        @click="beatRegist"
                    >
                        등록
                    </button>
                </div>
            </div>

            <footer-component />
        </section>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isNoticePopupVisible"
        >
            <div class="popup-layer-txt" v-html="noticePopupText">
            </div>
        </notice-popup-component>

        <confirm-popup-component
            title-text="확인"
            :visible.sync="isConfirmPopupVisible"
            right-button-text="알림 텍스트"
            @rightButtonClick="confirmClick"
        >
            <div class="popup-layer-txt">
                {{ confirmPopupText }}
            </div>
        </confirm-popup-component>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";
import Vue from "vue";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
        ConfirmPopupComponent
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            noticePopupText: "",
            confirmPopupText: "",
            files: {},
            moodItems: [],
            genreItems: [],
            beat_title: "",
            beat_price: "",
            beat_tag: ""
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            moods: "moods",
            genres: "genres"
        }),
        selectedMoods() {
            return this.moodItems.filter(mood => {
                return mood.checked;
            });
        },
        selectedGenres() {
            return this.genreItems.filter(genre => {
                return genre.checked;
            });
        }
    },
    watch: {
        moods() {
            this.updateMoodItems();
        },
        genres() {
            this.updateGenreItems();
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
        this.updateMoodItems();

        if (this.genres.length === 0) {
            await this.$http.get(`/api/categorys`).then(response => {
                if (this.genres.length === 0 && response.data.length !== 0) {
                    this.$store.commit("updateGenres", response.data);
                }
            });
        }
        this.updateGenreItems();
    },
    methods: {
        confirmClick() {
            this.noticePopupText = "wow";
            alert();
        },
        async beatRegist() {
            if (!this.isProducer) {
                return;
            }

            try {
                const formData = new FormData();
                //file1 : 썸네일, file2 : 음원, file3 : 보이스태그 음원
                if (this.files.file1) {
                    formData.append("file1", this.files.file1.files[0]);
                }

                if (this.files.file2) {
                    formData.append("file2", this.files.file2.files[0]);
                } else {
                    this.noticePopupText = "음원 파일을 업로드해 주세요";
                    this.isNoticePopupVisible = true;
                    return;
                }

                if (this.files.file3) {
                    formData.append("file3", this.files.file3.files[0]);
                } else {
                    this.noticePopupText =
                        "보이스 태그 포함된 음원 파일을 <br> 업로드해 주세요";
                    this.isNoticePopupVisible = true;
                    return;
                }

                if (this.beat_price < 20000) {
                    this.noticePopupText = "최소 금액은 20000원 입니다";
                    this.isNoticePopupVisible = true;
                    return;
                }

                this.$store.commit("updateIsGlobalLoading", true);

                formData.append("mood_id", this.selectedMoods[0].mood_id);
                formData.append("cate_id", this.selectedGenres[0].cate_id);
                formData.append("beat_title", this.beat_title);
                formData.append("beat_price", this.beat_price);
                formData.append("beat_tag", this.beat_tag);
                await this.$http.post("/api/beats", formData, {
                    headers: { "Content-Type": "multipart/form-data" }
                });

                this.noticePopupText = "등록되었습니다";
                this.isNoticePopupVisible = true;
                $(".btn.btn-01").click(function() {
                    location.reload();
                });
            } catch (e) {
                this.$store.commit("updateIsGlobalLoading", false);
                this.noticePopupText = "등록 중 오류가 발생했습니다";
                this.isNoticePopupVisible = true;
                $(".btn.btn-01").click(function() {
                    return;
                });
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        updateMoodItems() {
            this.moodItems = this.moods.map(mood => {
                return { ...mood, ...{ checked: false } };
            });
        },
        updateGenreItems() {
            this.genreItems = this.genres.map(genre => {
                return { ...genre, ...{ checked: false } };
            });
        },
        toggleSelectedMood(selectedMood) {
            if (!selectedMood.checked) {
                if (this.selectedMoods.length < 1) {
                    selectedMood.checked = true;
                } else {
                    this.selectedMoods[0].checked = false;
                    selectedMood.checked = true;
                }
            } else {
                selectedMood.checked = false;
            }
        },
        toggleSelectedgenre(selectedgenre) {
            if (!selectedgenre.checked) {
                if (this.selectedGenres.length < 1) {
                    selectedgenre.checked = true;
                } else {
                    this.selectedGenres[0].checked = false;
                    selectedgenre.checked = true;
                }
            } else {
                selectedgenre.checked = false;
            }
        },
        audioFileChange(name) {
            console.log(name);
            const fileInput = this.$refs[name];
            Vue.set(this.files, name, fileInput);
            $(`.upload-name.${name}`).val(
                $(fileInput)[0]
                    .value.split("/")
                    .pop()
                    .split("\\")
                    .pop()
            );
        },
        imageFileChange(name) {
            const fileInput = this.$refs[name];
            Vue.set(this.files, name, fileInput);

            const { files } = fileInput;
            if (FileReader && files && files.length) {
                const fr = new FileReader();
                fr.onload = () => {
                    this.$refs[`img_${name}`].src = fr.result;
                    this.imgDatas[`img_${name}`] = fr.result;
                };
                fr.readAsDataURL(files[0]);
            }
            $(".manage-producer-beat .pd-profile-enroll-thumbnail img").attr(
                "style",
                "width:180px;height:180px"
            );
        }
    }
};
</script>

<style lang="scss">
.manage-producer-beat {
    .filebox input[type="file"] {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }
    .filebox label {
        display: inline-block;
        padding: 0.5em 0.75em;
        color: #999;
        font-size: inherit;
        line-height: normal;
        vertical-align: middle;
        background-color: #fdfdfd;
        cursor: pointer;
        border: 1px solid #ebebeb;
        border-bottom-color: #e2e2e2;
        border-radius: 0.25em;
    }

    .filebox .upload-name {
        width: 20em;
        display: inline-block;
        padding: 0.5em 0.75em; /* label의 패딩값과 일치 */
        font-size: inherit;
        font-family: inherit;
        line-height: normal;
        vertical-align: middle;
        background-color: #f5f5f5;
        border: 1px solid #ebebeb;
        border-bottom-color: #e2e2e2;
        border-radius: 0.25em;
        -webkit-appearance: none; /* 네이티브 외형 감추기 */
        -moz-appearance: none;
        appearance: none;
    }

    .input-form {
        width: 20em;
        display: inline-block;
        padding: 0.5em 0.75em; /* label의 패딩값과 일치 */
        font-size: inherit;
        font-family: inherit;
        line-height: normal;
        vertical-align: middle;
        background-color: #fffdfdd8;
        border: 1px solid #ebebeb;
        border-bottom-color: #e2e2e2;
        border-radius: 0.25em;
        -webkit-appearance: none; /* 네이티브 외형 감추기 */
        -moz-appearance: none;
        appearance: none;
    }

    .main-mood-genre-choice .mood-genre-choice-btn.active {
        margin-bottom: 10px;
        background-color: #7e2aff;
        border-color: #7e2aff;
    }
    .pd-profile-enroll-thumbnail {
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        width: 180px;
        height: 180px;
        border-radius: 64.28571429px;
        position: absolute;
        position: relative;
        border-radius: 30px;
        background-color: #707070;
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        cursor: pointer;
    }
    .pd-profile-enroll-thumbnail button {
        width: 100%;
        height: 100%;
        border: 0;
        background-color: rgba(0, 0, 0, 0.2);
        font-size: 14px;
        font-weight: 400;
        opacity: 0;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }
    .pd-profile-enroll-thumbnail:hover button {
        opacity: 1;
        transition: all 0.2s;
    }
    .pd-profile-enroll-thumbnail img {
        margin: 0 auto;
        display: block;
    }
    .none-input {
        display: none;
    }
    .pd-profile-enroll-thumbnail span {
        font-size: 14px;
        font-weight: 400;
        color: #fff;
        display: inline-block;
        width: 100%;
        margin-top: 10px;
    }
    .registbtn {
        display: inline-block;
        padding: 0.5em 0.75em;
        color: #999;
        font-size: inherit;
        line-height: normal;
        vertical-align: middle;
        background-color: #fdfdfd;
        cursor: pointer;
        border: 1px solid #ebebeb;
        border-bottom-color: #e2e2e2;
        border-radius: 0.25em;
    }
}
</style>