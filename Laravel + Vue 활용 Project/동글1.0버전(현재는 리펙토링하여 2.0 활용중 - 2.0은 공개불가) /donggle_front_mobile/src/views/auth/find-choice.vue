<template>
  <div id="find_choice_contents">
    <header id="dg-find-hd">
      <h1 class="dg-find-hd-logo dg_blind">
        gonggle
      </h1>
      <div class="dg-find-hd-step_wrap">
        <h2>
          아이디/비밀번호 찾기
          <router-link
            to="/login"
            class="_back_btn"
          >
            뒤로가기
          </router-link>
        </h2>
      </div>
    </header>

    <!-- content -->
    <section
      id="find_choice"
      class="step_common"
    >
      <h2 class="dg_blind">
        아이디 비밀번호 찾기
      </h2>
      <div class="dg-choice_box-btn dg-find_id-btn">
        <h3>아이디 찾기</h3>
        <p>휴대폰 인증을 통하여 아이디를 찾습니다.</p>
        <router-link
          to="/find/id"
          class="_next_round_btn"
        >
          바로가기
        </router-link>
      </div>
      <div class="dg-choice_box-btn dg-find_pw-btn">
        <h3>비밀번호 찾기</h3>
        <p>휴대폰 인증을 통하여 비밀번호를 찾습니다.</p>
        <router-link
          to="/find/pw"
          class="_next_round_btn"
        >
          바로가기
        </router-link>
      </div>
    </section>
    <!-- content E -->
  </div>
</template>

<script>
  export default {

  }
</script>

<style lang="scss" scoped>
</style>
