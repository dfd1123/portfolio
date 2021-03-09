<template>
    <article class="j1beatz-ft">
        <footer class="j1beatz-ft-inner">
            <h4>
                제이원비츠
                <br>j1beatz
            </h4>
            <address>
                <span>대표 김형민, 박지석</span>
                <span>사업자등록번호 803-02-01042</span>
                <span>주소 서울특별시  성동구 마장로35나길 39 신진빌딩 4층 ACROFFICE B02호</span>
                <span>Mail contact@j1beatz.com</span>
                <span>고객센터 02-6217-7182</span>
                <span>개인정보보호책임자 : 박지석</span>
                <span>통신판매업신고 : 2019-서울강북-0660</span>
                <span
                    class="p-click"
                    @click="showTerm('terms_service')"
                >이용약관</span>
                <span
                    class="p-click"
                    @click="showTerm('privacy_policy')"
                >개인정보처리방침</span>
            </address>
            <small>Copyrightⓒ J1BEATZ All right reserved</small>
        </footer>

        <terms-and-conditions-info-popup
            :visible.sync="isTacPopupVisible"
        >
            <!-- eslint-disable-next-line vue/no-v-html -->
            <div v-html="termText" />
        </terms-and-conditions-info-popup>
    </article>
</template>

<script>
import TermsAndConditionsInfoPopup from "../common/TermsAndConditionsInfoPopup";

export default {
    components: {
        TermsAndConditionsInfoPopup
    },
    data() {
        return {
            isLoaded: false,
            isTacPopupVisible: false,
            terms: {},
            termText: ""
        };
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("updateIsGlobalLoading", true);

                this.terms = await this.$http
                    .get(`/api/terms`)
                    .then(response => response.data[0]);

                this.isLoaded = true;
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("updateIsGlobalLoading", false);
            }
        },
        async showTerm(termName) {
            if (!this.isLoaded) {
                await this.fetchData();
            }

            this.termText = this.terms[termName];
            this.isTacPopupVisible = true;
        }
    }
};
</script>

<style lang="scss">
@import "../../../styles/scss/layouts/j1beats-ft";

.p-click {
    text-decoration: underline;
    cursor: pointer;
}

</style>
