<template>
  <div id="loading_wrap">
    <transition appear name="slide-fadedown">
      <div class="loading_wrap__inner">
        <span class="ball-loader"></span>
        <p class="loader-text">
          Loading
          <span>.</span>
          <span>.</span>
          <span>.</span>
        </p>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'loading-component'
}
</script>

<style lang="scss" scoped>
#loading_wrap {
  @include position(fixed, $t: 0);
  @include zindex("popup");
  width: 100%;
  max-width: 1024px;
  height: 100%;
  background-color: darken(#3a291673, 80%);
  .loader-text {
    @include position($t: 60%);
    @include translate($x: 0, $y: -50%);
    @include setFont(13px, $color: white, $weight: 300);
    text-align: center;
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.9;
  }
  .loader-text span {
    font-weight: bold;
    animation-name: blink;
    animation-duration: 1.4s;
    animation-iteration-count: infinite;
    animation-fill-mode: both;
  }
  .loader-text span:nth-child(2) {
    animation-delay: 0.2s;
  }

  .loader-text span:nth-child(3) {
    animation-delay: 0.4s;
  }
  .loading_wrap__inner {
    width: 100%;
    height: 100%;
  }
}
@-moz-keyframes ball-loader {
  0% {
    -moz-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
    transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
    -moz-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  45% {
    -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
    -moz-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  50% {
    -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
    -moz-animation-timing-function: linear;
    animation-timing-function: linear;
  }
  55% {
    -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
    -moz-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
  }
}
@-webkit-keyframes ball-loader {
  0% {
    -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
    transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  45% {
    -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  50% {
    -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
    -webkit-animation-timing-function: linear;
    animation-timing-function: linear;
  }
  55% {
    -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
  }
}
@keyframes ball-loader {
  0% {
    -moz-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
    -ms-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
    -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
    transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
    -moz-animation-timing-function: ease-in;
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  45% {
    -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
    -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
    -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
    -moz-animation-timing-function: ease-in;
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  50% {
    -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
    -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
    -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
    -moz-animation-timing-function: linear;
    -webkit-animation-timing-function: linear;
    animation-timing-function: linear;
  }
  55% {
    -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
    -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
    -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
    transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
    -moz-animation-timing-function: ease-out;
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
  }
}
@-moz-keyframes ball-loader-shadow {
  0% {
    -moz-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
    -moz-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  45% {
    -moz-transform: translate3d(12.5px, -15px, -1px);
    transform: translate3d(12.5px, -15px, -1px);
    -moz-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  50% {
    -moz-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
    transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
    -moz-animation-timing-function: linear;
    animation-timing-function: linear;
  }
  55% {
    -moz-transform: translate3d(12.5px, -15px, -1px);
    transform: translate3d(12.5px, -15px, -1px);
    -moz-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
  }
  100% {
    -moz-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
    -moz-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
}
@-webkit-keyframes ball-loader-shadow {
  0% {
    -webkit-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  45% {
    -webkit-transform: translate3d(12.5px, -15px, -1px);
    transform: translate3d(12.5px, -15px, -1px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  50% {
    -webkit-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
    transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
    -webkit-animation-timing-function: linear;
    animation-timing-function: linear;
  }
  55% {
    -webkit-transform: translate3d(12.5px, -15px, -1px);
    transform: translate3d(12.5px, -15px, -1px);
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
  }
  100% {
    -webkit-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
}
@keyframes ball-loader-shadow {
  0% {
    -moz-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    -ms-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    -webkit-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
    -moz-animation-timing-function: ease-in;
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  45% {
    -moz-transform: translate3d(12.5px, -15px, -1px);
    -ms-transform: translate3d(12.5px, -15px, -1px);
    -webkit-transform: translate3d(12.5px, -15px, -1px);
    transform: translate3d(12.5px, -15px, -1px);
    -moz-animation-timing-function: ease-in;
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
  50% {
    -moz-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
    -ms-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
    -webkit-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
    transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
    -moz-animation-timing-function: linear;
    -webkit-animation-timing-function: linear;
    animation-timing-function: linear;
  }
  55% {
    -moz-transform: translate3d(12.5px, -15px, -1px);
    -ms-transform: translate3d(12.5px, -15px, -1px);
    -webkit-transform: translate3d(12.5px, -15px, -1px);
    transform: translate3d(12.5px, -15px, -1px);
    -moz-animation-timing-function: ease-out;
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
  }
  100% {
    -moz-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    -ms-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    -webkit-transform: translate3d(66.66667px, 66.66667px, -1px)
      scale3d(1.25, 1.25, 1);
    transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
    -moz-animation-timing-function: ease-in;
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
  }
}
.ball-loader {
  @include position($t: 30%, $l: 50%);
  margin-left: -30px;
  margin-top: -30px;
  @include responsive("iphone5") {
    top: 12%;
  }
}
/* :not(:required) hides this rule from IE9 and below */
.ball-loader:not(:required) {
  position: relative;
  display: inline-block;
  font-size: 0;
  letter-spacing: -1px;
  border-radius: 100%;
  background: url(/assets/images/logos/logo_bcg_character_big.svg);
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
  width: 60px;
  height: 60px;
  -moz-transform-style: preserve-3d;
  -webkit-transform-style: preserve-3d;
  transform-style: preserve-3d;
  -moz-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
  -ms-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
  -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
  transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
  -moz-transform-origin: 0 100%;
  -ms-transform-origin: 0 100%;
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
  -moz-animation: ball-loader 1500ms infinite linear;
  -webkit-animation: ball-loader 1500ms infinite linear;
  animation: ball-loader 1500ms infinite linear;
}
.ball-loader:not(:required)::before {
  content: "";
  position: absolute;
  top: 50px;
  left: 5.5px;
  width: 50px;
  height: 15px;
  background: rgba(0, 0, 0, 0.2);
  border-radius: 100%;
  -moz-transform: translate3d(66.66667px, 66.66667px, -1px)
    scale3d(1.25, 1.25, 1);
  -ms-transform: translate3d(66.66667px, 66.66667px, -1px)
    scale3d(1.25, 1.25, 1);
  -webkit-transform: translate3d(66.66667px, 66.66667px, -1px)
    scale3d(1.25, 1.25, 1);
  transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
  -moz-animation: ball-loader-shadow 1500ms infinite linear;
  -webkit-animation: ball-loader-shadow 1500ms infinite linear;
  animation: ball-loader-shadow 1500ms infinite linear;
  -webkit-filter: blur(1px);
  filter: blur(1px);
}
@keyframes blink {
  0% {
    opacity: 0.2;
  }
  20% {
    opacity: 1;
  }
  100% {
    opacity: 0.2;
  }
}
</style>
