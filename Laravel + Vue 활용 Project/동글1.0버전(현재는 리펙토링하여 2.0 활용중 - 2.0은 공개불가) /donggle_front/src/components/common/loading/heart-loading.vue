<template>
  <div class="loading_wrap">
    <div class="heart-loader"></div>
  </div>
</template>

<script>
  export default {

  }
</script>

<style lang="scss" scoped>
  .loading_wrap {
    width: 40px;
    margin: 0 auto;
    opacity: 0.95;
  }
  .heart-loader {
    background: #a535e8;
    background: -webkit-gradient(
      linear,
      left top,
      right top,
      from(#a535e8),
      to(#ff4fac)
    );
    background: -o-linear-gradient(left, #a535e8 0%, #ff4fac 100%);
    background: linear-gradient(90deg, #a535e8 0%, #ff4baa 100%);
    width: 40px;
    height: 40px;
    position: relative;
    -webkite-transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
    -webkit-animation: pulsate 1s infinite;
    animation: pulsate 1s infinite;
    border-top-right-radius: 24px;
    border-top-left-radius: 24px;
    border-bottom-left-radius: 24px;
    transform: scale(0.5);
  }

  .heart-loader::before,
  .heart-loader::after {
    display: inline-block;
    content: "";
    width: 40px;
    height: 40px;
    position: absolute;
    border-radius: 50%;
  }

  .heart-loader::before {
    background: -webkit-gradient(
      linear,
      left top,
      right top,
      from(#a535e8),
      to(#ff4fac)
    );
    background: -o-linear-gradient(left, #a535e8 0%, #ff4fac 100%);
    background: linear-gradient(90deg, #a535e8 72%, transparent 100%);
    border-bottom-right-radius: 0;
  }

  .heart-loader::after {
    background: -webkit-gradient(
      linear,
      left top,
      right top,
      from(#a535e8),
      to(#ff4fac)
    );
    background: -o-linear-gradient(left, #a535e8 0%, #ff4fac 100%);
    background: linear-gradient(90deg, #a535e8 16%, #ff4fac 100%);
    border-bottom-right-radius: 0;
  }

  .heart-loader::before {
    top: 0px;
    left: -19px;
  }
  .heart-loader::after {
    top: -19px;
    left: -0px;
  }

  @-webkit-keyframes pulsate {
    0%,
    10%,
    50%,
    100% {
      -webkit-transform: rotate(45deg) scale(0.8);
      transform: rotate(45deg) scale(0.8);
      transform: rotate(45deg) scale(0.8);
    }
    5% {
      -webkit-transform: rotate(45deg) scale(0.9);
      transform: rotate(45deg) scale(0.9);
    }
    15% {
      -webkit-transform: rotate(45deg) scale(1);
      transform: rotate(45deg) scale(1);
    }
  }

  @keyframes pulsate {
    0%,
    10%,
    50%,
    100% {
      -webkit-transform: rotate(45deg) scale(0.8);
      transform: rotate(45deg) scale(0.8);
      transform: rotate(45deg) scale(0.8);
    }
    5% {
      -webkit-transform: rotate(45deg) scale(0.9);
      transform: rotate(45deg) scale(0.9);
    }
    15% {
      -webkit-transform: rotate(45deg) scale(1);
      transform: rotate(45deg) scale(1);
    }
  }
</style>
