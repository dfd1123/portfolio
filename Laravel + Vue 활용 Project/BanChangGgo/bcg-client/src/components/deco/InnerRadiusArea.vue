<template>
  <div>
    <span class="inner_radius_area"></span>
  </div>
</template>

<script>
export default {
  name: 'inner-radius-area'
}
</script>

<style lang="scss" scoped>
.inner_radius_area {
  @include position($t: -55px, $l: -7%);
  @include zindex("zero");
  width: 114%;
  height: 210px;
  background-color: #f7f7f7;
  border-radius: 50%;
}
</style>
