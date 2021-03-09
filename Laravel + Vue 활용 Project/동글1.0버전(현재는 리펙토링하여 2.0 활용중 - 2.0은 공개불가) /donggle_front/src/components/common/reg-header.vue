<template>
  <!-- header -->
  <header id="dg-reg-hd">
    <h1
      class="dg-reg-hd-logo"
      @click="$router.push('/')"
    >
      donggle
    </h1>
    <div class="dg-reg-hd-step_wrap">
      <h2 v-if="!onlyLogo">
        {{ pageName }}
      </h2>
      <a
        href="#"
        @click="$router.go(-1)"
        v-if="!onlyLogo"
      >뒤로가기</a>
      <!-- #step1, #step_end에서 display:none -->
      <div
        v-if="stepView"
        class="dg-reg-hd-step"
      >
        <!-- ☆ step 진행도 표시
                #step_end 에서는 안보임
                #step1,2,3에서 작성후 버튼을 누르면 다음번 dg-btn_line에 add class active -->
        <div :class="['dg-btn_line dg-reg-hd-step_box1', {'active': step === '1'}]">
          1
        </div>
        <div :class="['dg-btn_line dg-reg-hd-step_box2', {'active': step === '2'}]">
          2
        </div>
        <div :class="['dg-btn_line dg-reg-hd-step_box3', {'active': step === '3'}]">
          3
        </div>
      </div>
    </div>
  </header>
  <!-- header E -->
</template>

<script>
  export default {
    data: function () {
      return {}
    },
    props: {
      pageName: {
        type: String,
        default: ''
      },
      onlyLogo: {
        type: Boolean,
        default: false
      },
      stepView: {
        type: Boolean,
        default: false
      },
      step: {
        type: String,
        default: '1'
      }
    }
  }
</script>

<style lang="scss" scoped>
  .dg-reg-hd-logo {
    cursor: pointer;
  }
</style>
