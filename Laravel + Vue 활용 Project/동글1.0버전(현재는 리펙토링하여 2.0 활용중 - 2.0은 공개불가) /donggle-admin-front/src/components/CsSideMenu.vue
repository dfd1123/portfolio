<template>
  <!-- 활성화된 메뉴 _list 에 active 추가 -->
  <div class="cs-side-navi">
    <div class="sidenav-title-group">
      <h2 class="in-title">고객센터</h2>
    </div>
    <article class="in-nav-section">
      <ul class="clearfix">
        <li class="_list" :class="{active: $route.path.startsWith('/cs-notice')}">
          <a href="#" @click.prevent="$router.push('/cs-notice-main')">공지사항</a>
        </li>
        <li class="_list" :class="{active: $route.path === '/cs-faq'}">
          <a href="#" @click.prevent="$router.push('/cs-faq')">자주묻는 질문</a>
        </li>
        <li class="_list" :class="{active: $route.path === '/cs-support'}">
          <a href="#" @click.prevent="$router.push('/cs-support')">관리자1:1문의</a>
        </li>
      </ul>
    </article>
  </div>
</template>

<script>
export default {
  name: 'CsSideMenu'
}
</script>

<style>
</style>
