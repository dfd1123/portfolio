<template>
  <div class="card-loading-wrapper">
    <div class="ball-scale-multiple">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</template>

<script>
export default {
  name: "card-loading-component"
};
</script>

<style scoped>
.card-loading-wrapper {
  padding-top: 170px;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 100;
}

@-webkit-keyframes ball-grid-beat {
  50% {
    opacity: 0.7;
  }
  100% {
    opacity: 1;
  }
}

@keyframes ball-grid-beat {
  50% {
    opacity: 0.7;
  }
  100% {
    opacity: 1;
  }
}

.ball-grid-beat {
  width: 57px;
  display: inline-block;
}
.ball-grid-beat > div:nth-child(1) {
  -webkit-animation-delay: 0.15s;
  animation-delay: 0.15s;
  -webkit-animation-duration: 1.45s;
  animation-duration: 1.45s;
}
.ball-grid-beat > div:nth-child(2) {
  -webkit-animation-delay: -0.02s;
  animation-delay: -0.02s;
  -webkit-animation-duration: 0.97s;
  animation-duration: 0.97s;
}
.ball-grid-beat > div:nth-child(3) {
  -webkit-animation-delay: 0.66s;
  animation-delay: 0.66s;
  -webkit-animation-duration: 1.23s;
  animation-duration: 1.23s;
}
.ball-grid-beat > div:nth-child(4) {
  -webkit-animation-delay: 0.64s;
  animation-delay: 0.64s;
  -webkit-animation-duration: 1.24s;
  animation-duration: 1.24s;
}
.ball-grid-beat > div:nth-child(5) {
  -webkit-animation-delay: -0.19s;
  animation-delay: -0.19s;
  -webkit-animation-duration: 1.13s;
  animation-duration: 1.13s;
}
.ball-grid-beat > div:nth-child(6) {
  -webkit-animation-delay: 0.69s;
  animation-delay: 0.69s;
  -webkit-animation-duration: 1.42s;
  animation-duration: 1.42s;
}
.ball-grid-beat > div:nth-child(7) {
  -webkit-animation-delay: 0.58s;
  animation-delay: 0.58s;
  -webkit-animation-duration: 1.14s;
  animation-duration: 1.14s;
}
.ball-grid-beat > div:nth-child(8) {
  -webkit-animation-delay: 0.21s;
  animation-delay: 0.21s;
  -webkit-animation-duration: 1.17s;
  animation-duration: 1.17s;
}
.ball-grid-beat > div:nth-child(9) {
  -webkit-animation-delay: -0.18s;
  animation-delay: -0.18s;
  -webkit-animation-duration: 0.65s;
  animation-duration: 0.65s;
}
.ball-grid-beat > div {
  background-color: rgb(30, 174, 255);
  width: 15px;
  height: 15px;
  border-radius: 100%;
  margin: 2px;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  display: inline-block;
  float: left;
  -webkit-animation-name: ball-grid-beat;
  animation-name: ball-grid-beat;
  -webkit-animation-iteration-count: infinite;
  animation-iteration-count: infinite;
  -webkit-animation-delay: 0;
  animation-delay: 0;
}

@-webkit-keyframes ball-scale-multiple {
  0% {
    -webkit-transform: scale(0);
    transform: scale(0);
    opacity: 0;
  }
  5% {
    opacity: 1;
  }
  100% {
    -webkit-transform: scale(1);
    transform: scale(1);
    opacity: 0;
  }
}

@keyframes ball-scale-multiple {
  0% {
    -webkit-transform: scale(0);
    transform: scale(0);
    opacity: 0;
  }
  5% {
    opacity: 1;
  }
  100% {
    -webkit-transform: scale(1);
    transform: scale(1);
    opacity: 0;
  }
}

.ball-scale-multiple {
  position: relative;
  -webkit-transform: translateY(-30px);
  transform: translateY(-30px);
  display: inline-block;
}
.ball-scale-multiple > div:nth-child(2) {
  -webkit-animation-delay: -0.4s;
  animation-delay: -0.4s;
}
.ball-scale-multiple > div:nth-child(3) {
  -webkit-animation-delay: -0.2s;
  animation-delay: -0.2s;
}
.ball-scale-multiple > div {
  background-color: rgb(30, 174, 255);
  width: 15px;
  height: 15px;
  border-radius: 100%;
  margin: 2px;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  position: absolute;
  left: -30px;
  top: 0px;
  opacity: 0;
  margin: 0;
  width: 60px;
  height: 60px;
  -webkit-animation: ball-scale-multiple 1s 0s linear infinite;
  animation: ball-scale-multiple 1s 0s linear infinite;
}
</style>