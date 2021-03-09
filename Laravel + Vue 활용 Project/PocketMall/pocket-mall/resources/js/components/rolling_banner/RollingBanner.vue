<template>
<div class="banner_wrap">
  <div class="banner_label"><span>Pocket <br />Partner</span></div>
  <div class="banner_wraper">
      <img src="/assets/images/rolling_banner/logo_01.svg" alt="GS"/>
      <img src="/assets/images/rolling_banner/logo_02.svg" alt="KT"/>
      <img src="/assets/images/rolling_banner/logo_03.svg" alt="SK"/>
      <img src="/assets/images/rolling_banner/logo_04.svg" alt="SPOWIDE"/>
      <img src="/assets/images/rolling_banner/logo_05.svg" alt="부산대학교"/>
      <img src="/assets/images/rolling_banner/logo_06.svg" alt="Jeju"/>
      <img src="/assets/images/rolling_banner/logo_07.svg" alt="전자랜드"/>
      <img src="/assets/images/rolling_banner/logo_08.svg" alt="A TWOSOME PLACE"/>
      <img src="/assets/images/rolling_banner/logo_09.svg" alt="COFFEE SEEKOO"/>
      <img src="/assets/images/rolling_banner/logo_10.svg" alt="MG손해보험"/>
      <img src="/assets/images/rolling_banner/logo_11.svg" alt="꽉찬돼지"/>
      <img src="/assets/images/rolling_banner/logo_12.svg" alt="Creative Innovation"/>
      <img src="/assets/images/rolling_banner/logo_13.svg" alt="인천대학교"/>
    </div>
  </div>
</template>

<script>
  export default {
    name:'rolling-banner'
  }
</script>

<style lang="scss" scoped>
.banner_wrap{height: 143px; width: 100%; position: fixed; bottom:0;left:0;padding: 29px 0;background-color: #DFE8F8; z-index:5;}
.banner_label{
  background: #DFE8F8;
  padding: 47px 0;
  padding-left: 55px;
  padding-right: 100px;
  font-size: 14px;
  font-weight: 300;
  position: absolute;
  bottom: 0;
  left: 0;
  z-index: 3;
  &::after{
    content: "";
    display: block;
    width: 45px;
    height: 1px;
    background: #000;
    position: absolute;
    top: 70px;
    right: 20px;
  }
}
.banner_wraper {width: 100%;overflow: hidden; position: absolute; bottom:0;left:0;height: 143px;padding: 40px 0;}
.banner_wraper img { position: absolute; box-shadow: 0px 13px 16px rgba(0,0,0,0.05); }

@media all and (max-width: 991px) {
  .banner_wraper img {
    width: 107px;
    height: 52px;
  }
}
</style>