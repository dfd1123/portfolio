<template>
    <div>
        <!--TODO:이용권정보, 장바구니, 고객센터, 마이페이지일 때 아래 제목스타일 추가-->
        <div class="sub-page-large-title">
            <h2 class="sub-page-large-title-name">고객센터</h2>
        </div>
        <div class="sub-page-large-tab">
            <div class="tab-menu tab-menu-alink">
                <ul>
                    <li class="tab-menu-list" :class="{active: name ==='notice'}">
                        <a href="#" role="button" @click.prevent="$router.push('/notice',()=> {})">공지사항</a>
                    </li>
                    <li class="tab-menu-list" :class="{active: name ==='faq'}">
                        <a href="#" role="button" @click.prevent="$router.push('/faq',()=> {})">FAQ</a>
                    </li>
                    <li class="tab-menu-list" :class="{active: name ==='qna'}">
                        <a href="#" role="button" @click.prevent="$router.push('/qna',()=> {})">1:1문의</a>
                    </li>
                </ul>
            </div>
        </div>
        <!--TODO:END-->
    </div>
</template>
<script>
export default {
    data() {
        return {
            name: this.$route.name
        };
    }
};
</script>

<style lang="scss">
</style>
