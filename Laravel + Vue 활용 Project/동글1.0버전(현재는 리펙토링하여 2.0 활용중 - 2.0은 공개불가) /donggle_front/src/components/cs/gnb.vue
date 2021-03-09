<template>
  <div class="l-side-navi">
    <div class="l-con-title-group type01 mb-10">
      <h2 class="in-subject">
        고객센터
      </h2>
    </div>
    <article class="in-nav-section">
      <ul>
        <li class="_list">
          <router-link to="/notices">
            공지사항
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/faqs">
            자주묻는 질문
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/qnas">
            1:1 문의
          </router-link>
        </li>
        <li class="_list">
          <router-link to="/events">
            이벤트
          </router-link>
        </li>
      </ul>
    </article>
  </div>
</template>

<script>
  export default {

  }
</script>

<style lang="scss" scoped>
</style>
