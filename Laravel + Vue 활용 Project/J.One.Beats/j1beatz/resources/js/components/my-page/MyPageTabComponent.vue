<template>
    <div>
        <!--TODO:이용권정보, 장바구니, 고객센터, 마이페이지일 때 아래 제목스타일 추가-->
        <div class="sub-page-large-title">
            <h2 class="sub-page-large-title-name">
                마이 페이지
            </h2>
        </div>
        <div class="sub-page-large-tab">
            <div class="tab-menu tab-menu-alink">
                <ul>
                    <li
                        class="tab-menu-list"
                        :class="{active: name === 'my-page-01'}"
                    >
                        <a
                            href="#"
                            @click.prevent="$router.push('/my-page-01',()=> {})"
                        >이용권 관리</a>
                    </li>
                    <li
                        class="tab-menu-list"
                        :class="{active: name === 'my-page-02'}"
                    >
                        <a
                            href="#"
                            @click.prevent="$router.push('/my-page-02',()=> {})"
                        >주문내역</a>
                    </li>
                    <li
                        class="tab-menu-list"
                        :class="{active: name === 'my-page-03'||name === 'my-page-03-1'}"
                    >
                        <a
                            href="#"
                            @click.prevent="$router.push('/my-page-03',()=> {})"
                        >마이앨범</a>
                    </li>
                    <li
                        class="tab-menu-list"
                        :class="{active: name === 'my-page-04'}"
                    >
                        <a
                            href="#"
                            @click.prevent="$router.push('/my-page-04',()=> {})"
                        >알림 메세지</a>
                    </li>
                    <li
                        class="tab-menu-list"
                        :class="{active: name === 'my-page-05'}"
                    >
                        <a
                            href="#"
                            @click.prevent="$router.push('/my-page-05',()=> {})"
                        >나의 정보관리</a>
                    </li>
                    <!--
                    <li class="tab-menu-list">
                        <a href="#">이벤트/쿠폰</a>
                    </li>
                    -->
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
