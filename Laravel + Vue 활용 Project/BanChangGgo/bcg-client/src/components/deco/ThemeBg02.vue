<template>
  <div class="theme_bg_box_02">
    <img
      src="/assets/images/bgs/theme_back_bg_pattern_top.svg"
      alt="배경 패턴"
      class="in_pattern in_pattern--top"
    />
    <div class="bt_patterns" :class="{ active : homeVue, active02 : ptMoreBt }">
      <img
        src="/assets/images/bgs/theme_back_bg_pattern_left.svg"
        alt="배경 패턴"
        class="in_pattern in_pattern--left"
      />
      <img
        src="/assets/images/bgs/theme_back_bg_pattern_center.svg"
        alt="배경 패턴"
        class="in_pattern in_pattern--center"
      />
      <img
        src="/assets/images/bgs/theme_back_bg_pattern_right.svg"
        alt="배경 패턴"
        class="in_pattern in_pattern--right"
      />
    </div>
  </div>
</template>

<script>
export default {
  name: 'theme-bg-02',
  props: {
    homeVue: {
      type: Boolean,
      default: false
    },
    ptMoreBt: {
      type: Boolean,
      default: false
    }
  }
}
</script>

<style lang="scss" scoped>
.theme_bg_box_02 {
  @include bgStyle(50%, 100%, 100%);
  background-color: $theme-03;
  width: 100%;
  height: 35vh;
  position: relative;
  overflow: hidden;
  .bt_patterns {
    position: relative;
    width: 100%;
    height: 200px;
    top: 35vh;
    @include responsive("mobile") {
      top: 15vh;
    }
  }
  .bt_patterns.active02 {
    @include responsive("mobile") {
      top: 23vh;
    }
  }
  .active {
    @include responsive("mobile") {
      top: 25vh;
    }
  }
  .in_pattern {
    position: absolute;
    animation-fill-mode: forwards;
    animation-direction: alternate;
    animation-iteration-count: infinite;
    animation-timing-function: ease-in-out;
  }
  .in_pattern--top {
    @include position($t: -20%, $l: -15%);
    width: 120%;
    transform: translateY(-20px);
    animation-delay: 0.3s;
    animation-name: patternDownUp;
    animation-duration: 4s;
  }
  .in_pattern--left {
    @include position($b: 0, $l: -4%);
    @include translate($x: 0, $y: 20px);
    animation-delay: 0.2s;
    animation-name: patternUpDown01;
    animation-duration: 6s;
  }
  .in_pattern--right {
    @include position($b: 0, $r: -10%);
    @include translate($x: 0, $y: 20px);
    width: 110%;
    animation-delay: 0.2s;
    animation-name: patternUpDown01;
    animation-duration: 4s;
  }
  .in_pattern--center {
    @include position($b: -50px, $r: -4.5%);
    @include translate($x: 0, $y: 40px);
    width: 125%;
    animation-delay: 0.3s;
    animation-name: patternUpDown02;
    animation-duration: 4.5s;
  }
}
@keyframes patternDownUp {
  0% {
    transform: translatey(-20px);
  }
  50% {
    transform: translatey(0);
  }
  100% {
    transform: translatey(-20px);
  }
}
@keyframes patternUpDown01 {
  0% {
    transform: translatey(20px);
  }
  50% {
    transform: translatey(0);
  }
  100% {
    transform: translatey(20px);
  }
}
@keyframes patternUpDown02 {
  0% {
    transform: translatey(40px);
  }
  50% {
    transform: translatey(0);
  }
  100% {
    transform: translatey(40px);
  }
}
</style>
