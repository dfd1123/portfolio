<template>
    <div
        id="wrap"
        class="manage-producer-update"
    >
        <section class="section sub-section">
            <div class="page-contents-wrapper">
                <div class="sub-page-auth sub-page-auth-pd-profile __enroll">
                    <div class="sub-page-auth-title">
                        <h2>프로듀서 프로필 수정</h2>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div class="member-mail-guide">
                            <span>제이원비츠 회원아이디</span>
                            <p>{{ user.user_email }}</p>
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
                                프로듀서 활동명 :
                                <em class="emphasis">{{ producer.prdc_nick }}</em>
                            </h3>
                        </div>

                        <div class="auth-input_form _double-chck">
                            <input
                                v-model="nickname"
                                type="text"
                                :disabled="isProducerNicknameDuplicateChecked"
                            >
                            <button
                                type="button"
                                class="btn btn-certify-mini"
                                :class="{active: isNicknameInputed}"
                                :disabled="!isNicknameInputed"
                                @click="producerNicknameDuplicateCheck"
                            >
                                {{ !isProducerNicknameDuplicateChecked ? '중복검사' : '수정하기' }}
                            </button>
                        </div>

                        <div class="in-title in-title-mini_ver">
                            <h3 class="in-title-name">
                                주요 분위기 선택
                            </h3>
                            <span class="in-title-ment">최대 3개까지 가능합니다.</span>
                            <p class="current">
                                현재 장르 :
                                <em
                                    v-for="(mood, index) in moodList"
                                    :key="index"
                                    class="emphasis"
                                >{{ mood }} /</em>
                            </p>
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
                            <span class="in-title-ment">선택 개수 제한이 없습니다.</span>
                            <p class="current">
                                현재 장르 :
                                <em
                                    v-for="(genre, index) in genreList"
                                    :key="index"
                                    class="emphasis"
                                >{{ genre }} /</em>
                            </p>
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
                        <button
                            class="btn btn-complete"
                            @click="updateProducerinfo"
                        >
                            프로필 수정
                        </button>
                    </div>
                </div>
            </div>

            <footer-component />
        </section>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isNoticePopupVisible"
        >
            <div class="popup-layer-txt">
                {{ noticePopupText }}
            </div>
        </notice-popup-component>
    </div>
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
            moodItems: [],
            genreItems: [],
            moodList: [],
            genreList: [],
            files: {},
            nickname: "",
            isProducerNicknameDuplicateChecked: false,
            isTryProducerNicknameDuplicateCheck: false
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            producer: "producer",
            moods: "moods",
            genres: "genres"
        }),
        isNicknameInputed() {
            return this.nickname ? true : false;
        },
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
        this.mood();
        this.genre();
    },
    methods: {
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
                if (this.selectedMoods.length < 3) {
                    selectedMood.checked = true;
                }
            } else {
                selectedMood.checked = false;
            }
        },
        toggleSelectedgenre(selectedgenre) {
            if (!selectedgenre.checked) {
                selectedgenre.checked = true;
            } else {
                selectedgenre.checked = false;
            }
        },
        mood() {
            this.producer.mood_json.forEach(element => {
                this.moods.forEach(mood => {
                    if (element == mood.mood_id) {
                        this.moodList.push(mood.mood_title);
                    }
                });
            });
        },
        genre() {
            this.producer.cate_json.forEach(element => {
                this.genres.forEach(genre => {
                    if (element == genre.cate_id) {
                        this.genreList.push(genre.cate_title);
                    }
                });
            });
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
            $(".manage-producer-update .pd-profile-enroll-thumbnail img").attr(
                "style",
                "width:190px;height:190px"
            );
        },
        async producerNicknameDuplicateCheck() {
            if (this.isProducerNicknameDuplicateChecked) {
                this.isProducerNicknameDuplicateChecked = false;
            } else {
                if (this.isTryProducerNicknameDuplicateCheck) {
                    return;
                }

                try {
                    this.isTryProducerNicknameDuplicateCheck = true;
                    this.$store.commit("updateIsGlobalLoading", true);

                    await this.$http.get(`/api/producers`, {
                        params: {
                            req: "check_prdc_nick_duplicate",
                            prdc_nick: this.nickname
                        }
                    });

                    this.isProducerNicknameDuplicateChecked = true;
                    this.noticePopupText = "사용가능한 프로듀서 활동명입니다";
                    this.isNoticePopupVisible = true;
                } catch (e) {
                    this.isProducerNicknameDuplicateChecked = false;
                    this.noticePopupText = "중복된 프로듀서 활동명입니다";
                    this.isNoticePopupVisible = true;
                    console.log(e);
                } finally {
                    this.isTryProducerNicknameDuplicateCheck = false;
                    this.$store.commit("updateIsGlobalLoading", false);
                }
            }
        },
        async updateProducerinfo() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);
                const formData = new FormData();
                if (this.files.file1) {
                    formData.append("prdc_img", this.files.file1.files[0]);
                }
                if (this.selectedMoods.length > 0) {
                    var mood_json = new Array();
                    this.selectedMoods.forEach(function(mood) {
                        mood_json.push(mood.mood_id);
                    });
                    formData.append("mood_json", JSON.stringify(mood_json));
                }
                if (this.selectedGenres.length > 0) {
                    var cate_json = new Array();
                    this.selectedGenres.forEach(function(genre) {
                        cate_json.push(genre.cate_id);
                    });
                    formData.append("cate_json", JSON.stringify(cate_json));
                }
                if (this.nickname != "") {
                    if (!this.isProducerNicknameDuplicateChecked) {
                        this.noticePopupText = "중복 검사를 확인해 주세요";
                        this.isNoticePopupVisible = true;
                        return false;
                    } else {
                        formData.append("prdc_nick", this.nickname);
                    }
                }

                await this.$http.post(`/api/producers`, formData, {
                    headers: { "Content-Type": "multipart/form-data" }
                });
                this.$store.commit("updateIsGlobalLoading", false);
                this.noticePopupText = "수정되었습니다";
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
        }
    }
};
</script>
<style lang="scss">
.main-mood-genre-choice .mood-genre-choice-btn.active {
    margin-bottom: 10px;
    background-color: #7e2aff;
    border-color: #7e2aff;
}
.emphasis {
    color: #7e2aff;
}
.current {
    margin: 1em 0;
    font-size: 0.8em;
}
.manage-producer-update {
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
}
</style>