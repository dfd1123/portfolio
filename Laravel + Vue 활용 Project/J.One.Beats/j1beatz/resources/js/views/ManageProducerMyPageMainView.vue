<template>
    <div id="wrap">
        <section class="section sub-section">
            <div class="page-contents-wrapper">
                <div class="sub-page-auth sub-page-auth-pd-profile __complete">
                    <div class="sub-page-auth-title">
                        <h2>프로듀서 프로필</h2>
                    </div>

                    <div class="sub-page-auth-panel">
                        <div class="pd-profile-complt">
                            <figure
                                :style="{
                                    'background-image': `url(/fdata/mkrthumb/${producer.prdc_img})`
                                }"
                            >
                                <figcaption class="none">
                                    프로듀서 썸네일
                                </figcaption>
                            </figure>

                            <dl>
                                <dt>{{ producer.prdc_nick }}</dt>
                                <dd>
                                    <b>분위기</b>
                                    <em />
                                    <em
                                        v-for="(mood, index) in moodList"
                                        :key="index"
                                    >{{ mood }}</em>
                                </dd>
                                <dd>
                                    <b>장르</b>
                                    <em />
                                    <em
                                        v-for="(genre, index) in genreList"
                                        :key="index"
                                    >{{ genre }}</em>
                                </dd>
                            </dl>
                        </div>

                        <button
                            class="btn btn-complete"
                            @click.prevent="$router.push(`manage-producer-my-page-update`, () => {});"
                        >
                            수정
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import { mapState, mapGetters } from "vuex";
import HeaderComponent from "../components/layout/HeaderComponent";
import NoticePopupComponent from "../components/common/NoticePopupComponent";
import ConfirmPopupComponent from "../components/common/ConfirmPopupComponent";

export default {
    components: {
        HeaderComponent,
        NoticePopupComponent,
        ConfirmPopupComponent
    },
    data() {
        return {
            isNoticePopupVisible: false,
            isConfirmPopupVisible: false,
            noticePopupText: "",
            confirmPopupText: "",
            moodList: [],
            genreList: []
        };
    },
    computed: {
        ...mapGetters(["isUser", "isProducer"]),
        ...mapState({
            user: "user",
            producer: "producer",
            moods: "moods",
            genres: "genres"
        })
    },
    async created() {
        this.mood();
        this.genre();
    },
    methods: {
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
        }
    }
};
</script>