<template>
  <div>
    <RegHeader
      page-name="아이디/비밀번호 찾기"
      :only-logo="false"
      :step-view="false"
    />
    <section
      id="find_choice"
      class="step_common"
    >
      <h2 class="dg_blind">
        아이디 비밀번호 찾기
      </h2>
      <div
        class="dg-choice_box-btn dg-find_id-btn"
        @click="$router.push('/find/id')"
      >
        <router-link to="/find/id">
          <h3>아이디 찾기</h3>
          <p>휴대폰 인증을 통하여 아이디를 찾습니다.</p>
        </router-link>
      </div>
      <div
        class="dg-choice_box-btn dg-find_pw-btn"
        @click="$router.push('/find/pw')"
      >
        <router-link to="/find/pw">
          <h3>비밀번호 찾기</h3>
          <p>휴대폰 인증을 통하여 비밀번호를 찾습니다.</p>
        </router-link>
      </div>
    </section>
  </div>
</template>

<script>
  import RegHeader from '@/components/common/reg-header.vue'

  export default {
    components: {
      RegHeader
    }
  }
</script>

<style lang="scss" scoped>
  .dg-choice_box-btn > a > p {
    line-height: 1.5;
    font-size: 0.75em;
    font-weight: 200;
    color: #707070;
  }
</style>
