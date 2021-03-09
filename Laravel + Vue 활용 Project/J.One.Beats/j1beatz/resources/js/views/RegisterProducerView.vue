<template>
    <!-- 메인 컨텐츠 -->
    <div id="wrap">
        <section class="section sub-section">
            <header-component />

            <!--TODO:-->
            <div class="page-contents-wrapper">
                <!-- FIXME: 프로듀서 프로필 등록 첫번째 -->
                <div
                    v-show="registerStep === 1"
                    class="sub-page-auth sub-page-auth-pd-profile __enroll"
                >
                    <div class="sub-page-auth-title">
                        <h2>프로듀서 프로필 등록</h2>
                        <span>제이원비츠 프로듀서 회원가입</span>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div class="certify-good">
                            <p>
                                <b>프로듀서 등록</b>을 시작합니다.
                            </p>
                            <span>
                                프로듀서 프로필 등록을 완료하세요.
                                <br>운영자의 프로필 검수 후, 프로듀서 회원 활동이 가능합니다.
                                <br>
                                <br>
                                <a
                                    href="#"
                                    role="button"
                                />
                            </span>
                        </div>

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
                                    class="thum_profil_pic"
                                >
                                <img
                                    src="img/icon/icon-edit-white.svg"
                                    alt="사진등록 아이콘"
                                    class="enroll_icon"
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
                                프로듀서 활동명
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
                        </div>

                        <div class="main-mood-genre-choice">
                            <input
                                v-for="mood in moodItems"
                                :key="mood.mood_id"
                                :checked="mood.checked"
                                type="checkbox"
                                class="none-input"
                            >

                            <ul>
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
                                    <label @click="genre.checked = !genre.checked">
                                        <span>{{ genre.cate_title }}</span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="in-title in-title-mini_ver">
                            <h3 class="in-title-name">
                                본인 제작 비트 첨부
                            </h3>
                            <span class="in-title-ment">MP3 파일 첨부</span>
                        </div>

                        <div class="auth-input_form _input-beat">
                            <input
                                v-model="audioFilename"
                                type="text"
                                disabled
                                readonly
                            >
                            <input
                                ref="file2"
                                type="file"
                                class="none-input"
                                accept=".mp3"
                                @change="audioFileChange('file2')"
                            >
                            <button
                                type="button"
                                class="btn btn-certify-mini active"
                                @click="$refs.file2.click()"
                            >
                                파일첨부
                            </button>
                        </div>

                        <span
                            class="pd-profile-caution"
                        >* 첨부하신 파일은 프로듀서 검수용 확인 후 삭제되며, 운영자 측에서 보관하지 않습니다.</span>

                        <div class="in-title in-title-mini_ver">
                            <h3 class="in-title-name">
                                계좌정보 입력
                            </h3>
                            <span class="in-title-ment">본인의 이름, 은행명, 계좌번호</span>
                        </div>

                        <div class="auth-input_form _double-chck">
                            <input
                                v-model="account"
                                type="text"
                                placeholder="김OO 하나 000-000000-00000"
                            >
                        </div>

                        <div class="in-title in-title-mini_ver">
                            <h3 class="in-title-name">
                                이용 약관
                            </h3>
                            <span class="in-title-ment">체크 시 동의함</span>
                            <div class>
                                <label class="label">
                                    <input
                                        id="check-this-agree1"
                                        v-model="agree1"
                                        type="checkbox"
                                        class="input-style-01"
                                    >
                                    <label for="check-this-agree1" />
                                    <b>(필수)</b>
                                    <span>음원 등록 및 판매대행 약관</span>
                                </label>
                                <span
                                    class="more"
                                    @click="showTerm('sale_terms_service')"
                                >전문보기</span>
                            </div>
                        </div>

                        <button
                            class="btn btn-complete"
                            @click="register"
                        >
                            프로필 등록 완료
                        </button>
                    </div>
                </div>
                <!-- FIXME: 프로듀서 프로필 등록 첫번째 END -->

                <div
                    v-show="registerStep === 2"
                    class="sub-page-auth sub-page-auth-pd-profile __complete"
                >
                    <div class="sub-page-auth-title">
                        <h2>프로듀서 프로필 등록</h2>
                        <span>제이원비츠 프로듀서 회원가입</span>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div class="certify-good">
                            <img
                                src="/img/icon/certify-good.svg"
                                alt="certify complete"
                            >
                            <p>
                                <b>프로듀서 프로필 등록</b>이 완료되었습니다.
                            </p>

                            <span>운영자의 프로필 검수 후, 프로듀서 회원 활동이 가능합니다.</span>
                        </div>

                        <div class="pd-profile-complt">
                            <figure :style="{'background-image': `url(${imgDatas['img_file1']})`}">
                                <figcaption class="none">
                                    프로듀서 썸네일
                                </figcaption>
                            </figure>

                            <dl>
                                <dt>{{ nickname }}</dt>
                                <dd v-if="selectedMoods.length > 0">
                                    <b>분위기</b>
                                    <em
                                        v-for="mood in selectedMoods"
                                        :key="mood.mood_id"
                                    >{{ mood.mood_title }}</em>
                                </dd>
                                <dd v-if="selectedGenres.length > 0">
                                    <b>장르</b>
                                    <em
                                        v-for="genre in selectedGenres"
                                        :key="genre.cate_id"
                                    >{{ genre.cate_title }}</em>
                                </dd>
                            </dl>
                        </div>

                        <button
                            class="btn btn-complete"
                            @click="$router.push('/main')"
                        >
                            홈으로
                        </button>
                    </div>
                </div>
            </div>
            <!--TODO:END-->

            <footer-component />
        </section>

        <notice-popup-component
            title-text="알림"
            :visible.sync="isPopupVisible"
        >
            <div class="popup-layer-txt">
                {{ popupMessage }}
            </div>
        </notice-popup-component>
        <terms-and-conditions-info-popup :visible.sync="isTacPopupVisible">
            <!-- eslint-disable-next-line vue/no-v-html -->
            <div v-html="termText" />
        </terms-and-conditions-info-popup>
    </div>
    <!-- //메인 컨텐츠 -->
</template>

<script>
import Vue from "vue";
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import FooterComponent from "../components/layout/FooterComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import TermsAndConditionsInfoPopup from "../components/common/TermsAndConditionsInfoPopup";

export default {
    components: {
        HeaderComponent,
        FooterComponent,
        NoticePopupComponent,
        TermsAndConditionsInfoPopup
    },
    data() {
        return {
            isPopupVisible: false,
            isProducerNicknameDuplicateChecked: false,
            isTryProducerNicknameDuplicateCheck: false,
            isTacPopupVisible: false,
            isTryRegister: false,
            popupMessage: "",
            moodItems: [],
            genreItems: [],
            registerStep: 1,
            nickname: "",
            files: {},
            imgDatas: {},
            audioFilename: "",
            account: "",
            terms: {},
            termText: "",
            agree1: false
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
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

        this.terms = await this.$http
            .get(`/api/terms`)
            .then(response => response.data[0]);
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
                    this.popupMessage = "사용가능한 프로듀서 활동명입니다";
                    this.isPopupVisible = true;
                } catch (e) {
                    this.isProducerNicknameDuplicateChecked = false;
                    this.popupMessage = "중복된 프로듀서 활동명입니다";
                    this.isPopupVisible = true;
                    console.log(e);
                } finally {
                    this.isTryProducerNicknameDuplicateCheck = false;
                    this.$store.commit("updateIsGlobalLoading", false);
                }
            }
        },
        registerCheck() {
            if (!this.isNicknameInputed) {
                this.popupMessage = "프로듀서 활동명을 입력하세요";
                this.isPopupVisible = true;
                return false;
            }

            if (!this.isProducerNicknameDuplicateChecked) {
                this.popupMessage = "프로듀서 활동명 중복검사가 필요합니다";
                this.isPopupVisible = true;
                return false;
            }

            if (!this.files.file2) {
                this.popupMessage = "본인 제작 비트를 첨부해 주시기 바랍니다";
                this.isPopupVisible = true;
                return false;
            }

            if (!this.account) {
                this.popupMessage = "본인의 계좌정보를 입력해 주시기 바랍니다";
                this.isPopupVisible = true;
                return false;
            }

            if (!this.agree1) {
                this.popupMessage = "음원 등록 및 판매대행 약관을 체크해주세요";
                this.isPopupVisible = true;
                return;
            }
            return true;
        },
        async register() {
            if (!this.registerCheck()) {
                return;
            }

            if (this.isTryRegister) {
                return;
            }

            try {
                this.isTryRegister = true;
                this.$store.commit("updateIsGlobalLoading", true);

                const formData = new FormData();
                if (this.files.file1) {
                    formData.append("file1", this.files.file1.files[0]);
                }
                if (this.files.file2) {
                    formData.append("file2", this.files.file2.files[0]);
                }
                formData.append("prdc_nick", this.nickname);
                formData.append(
                    "mood_json",
                    JSON.stringify(
                        this.selectedMoods.map(mood => mood.mood_id).slice(0)
                    )
                );
                formData.append(
                    "cate_json",
                    JSON.stringify(
                        this.selectedGenres.map(genre => genre.cate_id).slice(0)
                    )
                );
                formData.append("prdc_bnk_accnt", this.account);

                await this.$http.post("/api/register_producer", formData, {
                    headers: { "Content-Type": "multipart/form-data" }
                });

                const { user, producer } = await this.$http
                    .get("/api/info")
                    .then(response => response.data);

                this.$store.commit("updateUser", user);
                this.$store.commit("updateProducer", producer);

                this.registerStep = 2;
            } catch (e) {
                this.popupMessage = "프로필 등록 중 오류가 발생했습니다";
                this.isPopupVisible = true;
                console.log(e);
            } finally {
                this.isTryRegister = false;
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        audioFileChange(name) {
            const fileInput = this.$refs[name];
            Vue.set(this.files, name, fileInput);
            this.audioFilename = fileInput.files.item(0).name;
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
        },
        showTerm(termName) {
            this.termText = this.terms[termName];
            this.isTacPopupVisible = true;
        }
    }
};
</script>

<style lang="scss">
.label {
    display: inline-block;
    height: 100%;
    padding: 15px 20px;
    position: relative;
}
.label input[type="checkbox"] + label {
    position: relative;
    top: 4px;
    margin-right: 7px;
}
.label b {
    color: #7e2aff;
    font-weight: 500;
}
.none {
    position: absolute;
    top: -5000em;
    left: 0;
    width: 0;
    height: 0;
    font-size: 0;
    line-height: 0;
    overflow: hidden;
}
.more {
    text-decoration: underline;
    cursor: pointer;
}
</style>
