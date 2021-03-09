<template>
  <div class="theme_bg_box_01">
    <svg
      class="top_waves"
      version="1.1"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      x="0px"
      y="0px"
      width="574.558px"
      height="230px"
      viewBox="0 0 574.558 120"
      enable-background="new 0 0 574.558 120"
      xml:space="preserve"
    >
      <defs>
        <pattern id="top_water" width=".25" height="1.1" patternContentUnits="objectBoundingBox">
          <path
            fill="rgba(255,255,255,0.2)"
            d="M0.25,1H0c0,0,0-0.659,0-0.916c0.083-0.303,0.158,0.334,0.25,0C0.25,0.327,0.25,1,0.25,1z"
          />
        </pattern>
      </defs>
      <rect class="water-fill" fill="url(#top_water)" x="-400" y="0" width="1600" height="140" />
    </svg>
    <svg
      class="bt_waves"
      version="1.1"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      x="0px"
      y="0px"
      width="574.558px"
      height="230px"
      viewBox="0 0 574.558 120"
      enable-background="new 0 0 574.558 120"
      xml:space="preserve"
    >
      <defs>
        <pattern id="water" width=".25" height="1.1" patternContentUnits="objectBoundingBox">
          <path
            fill="rgba(255,255,255,0.7)"
            d="M0.25,1H0c0,0,0-0.659,0-0.916c0.083-0.303,0.158,0.334,0.25,0C0.25,0.327,0.25,1,0.25,1z"
          />
        </pattern>
        <pattern id="water_02" width=".25" height="1.1" patternContentUnits="objectBoundingBox">
          <path
            fill="rgba(255,255,255,0.4)"
            d="M0.25,1H0c0,0,0-0.659,0-0.916c0.083-0.303,0.158,0.334,0.25,0C0.25,0.327,0.25,1,0.25,1z"
          />
        </pattern>
      </defs>
      <rect class="water-fill" fill="url(#water)" x="-400" y="0" width="1600" height="140" />
      <rect
        class="water-fill-reverse"
        fill="url(#water_02)"
        x="-400"
        y="0"
        width="1600"
        height="140"
      />
    </svg>
  </div>
</template>

<script>
export default {
  name: 'theme-bg-01'
}
</script>

<style lang="scss" scoped>
.theme_bg_box_01 {
  width: 100%;
  position: relative;
  background-color: $theme-03;
}

.top_waves {
  @include position($t: -30px, $l: 0);
  width: 100%;
  transform: rotate(180deg);
  height: 110px;

  .water-fill {
    animation: wave 10s infinite linear,
      fill-up-03 10s infinite ease-out alternate;
  }
}

.bt_waves {
  @include position($b: -35px, $l: 0);
  opacity: 0.3;
  width: 100%;
}

.water-fill {
  animation: wave 10s infinite linear, fill-up 10s infinite ease-out alternate;
}

.water-fill-reverse {
  animation: wave 10s infinite reverse,
    fill-up-02 10s infinite ease-out alternate;
}

@keyframes wave {
  0% {
    x: -1000px;
  }
  60% {
    x: -300px;
  }
  100% {
    x: -800px;
  }
}

@keyframes fill-up {
  0% {
    height: 120px;
    y: 130px;
  }
  100% {
    height: 160px;
    y: 0;
  }
}

@keyframes fill-up-02 {
  0% {
    height: 150px;
    y: 90px;
  }
  100% {
    height: 200px;
    y: 0;
  }
}

@keyframes fill-up-03 {
  0% {
    height: 200px;
    y: 90px;
  }
  100% {
    height: 140px;
    y: 40px;
  }
}
</style>
