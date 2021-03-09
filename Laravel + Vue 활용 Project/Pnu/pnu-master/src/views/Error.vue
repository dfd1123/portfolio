<template>
    <div id="root">
        <div class="fixed-background">
        <main>
            <div class="container">
              <b-row class="h-100">
                <b-colxx xxs="12" md=10  class="mx-auto my-auto">
                  <b-card class="auth-card" no-body>
                      <div class="position-relative image-side ">
                        <p class=" text-white h2">역량평가 관리시스템</p>
                          <p class="white mb-0">오류 페이지</p>
                      </div>
                      <div class="form-side">
                        <router-link tag="a" to="/"><img src="/master/assets/img/header_logo.svg" /></router-link>
                        <h6 class="mb-4 mt-4">현재 찾을 수 없는 페이지를 요청하셨습니다. 삭제된 주소거나, 일시적으로 사용할 수 없습니다</h6>
                        <p class="mb-0 text-muted text-small mb-0">잠시 후 다시 시도해주시기 바랍니다</p>
                        <p class="display-1 font-weight-bold mb-5">404</p>
                        <b-button type="submit" variant="primary" size="lg" class="btn-shadow" @click="goBack">이전 페이지로</b-button>
                    </div>
                  </b-card>
                </b-colxx>
              </b-row>
            </div>
        </main>
        </div>
    </div>
</template>
<script>
export default {
  methods: {
    goBack () {
      this.$router.push('/')
    }
  },
  mounted () {
    document.body.classList.add('background')
  },
  beforeDestroy () {
    document.body.classList.remove('background')
  }
}
</script>
