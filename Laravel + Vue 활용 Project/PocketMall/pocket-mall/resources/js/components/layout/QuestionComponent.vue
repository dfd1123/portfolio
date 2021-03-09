<template>
  <div id="nav_wrapper">
    <div class="nav_slider">
      <div class="nav_slider__con">
        <div class="nav_wrapper__menu">
          <ul>
            <li @click="GotoUrl('https://포켓컴퍼니.com')">
              작업과정이 궁금하다면?<br />
              <span>포켓 작업과정 보러가기</span>
            </li>
            <li @click="GotoUrl('http://platcommerce.com/')">
              파트너를 맺고 싶다면?<br />
              <span>(주)플랫커머스 그룹 홈페이지 바로가기</span>
            </li>
            <li @click="GotoUrl('https://members.durutalk.com/')">
              최적의 광고를 하고 싶다면?<br />
              <span>두루톡, 광고 파트너 사이트 바로가기</span>
            </li>
            <li @click="GotoUrl('https://open.kakao.com/o/s3Il3lIb')">
              실시간 상담을 원한다면?<br />
              <span>상담원 채팅하기</span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "question-component",
    data() {
      return {

      };
    },
    methods: {
      GotoUrl(url){
        window.open(url);
      }
    }
  }
</script>

<style lang="scss" scoped>
.nav_wrapper__menu_group--contact {
  position: relative;
  opacity: 0;
  transform: translateX(30px);
}

.nav_wrapper__menu_group--contact.show {
  animation: contactGroupShow 1s 1 both;
  animation-timing-function: cubic-bezier(0.15, 0.52, 0.15, 1.15);
}

@keyframes contactGroupShow {
  0% {
    opacity: 0;
    transform: translateX(30px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

.nav_wrapper__menu_group--contact.active {
  opacity: 0;
  transform: translateX(30px);
  transition: 0.3s;
  display: block;
  animation: unset;
}

.nav_wrapper__menu>ul{
  padding-top: 150px;
  opacity: 0;
  // fit-content 506px + hover padding 60px
  width: 566px;
  margin: 0px auto;
}

#nav_wrapper.opened_nav{
  .nav_wrapper__menu>ul{
    min-width: 300px;
    opacity: 1;
  }
}

.nav_wrapper__menu>ul>li{
  font-size: 40px;
  margin-bottom: 40px;
  cursor:pointer;
  >span{
    position:relative;
    font-size: 30px;
    color: #d4d4d4;
    padding-left:0px;
    transition:padding-left ease 0.2s, color ease 0.2s;
    &::before{
      position:absolute;
      top:24px;
      left:0;
      content:'';
      display: block;
      width:0px;
      height:2px;
      background: #000;
      transition:width ease 0.2s;
    }
  }

  &:hover{
    >span{
      padding-left:60px;
      color:#1C6AFF;
      &::before{
        width:50px;
      }
    }
  }
}

@media all and (max-width: 991px) {
  .nav_wrapper__menu>ul{
    padding-top: 100px;
  }

  .nav_wrapper__menu>ul>li{
    font-size: 25px;
    margin-bottom: 25px;
    >span{
      position:relative;
      font-size: 18px;
      color: #d4d4d4;
      padding-left:0px;
      transition:padding-left ease 0.2s, color ease 0.2s;
      &::before{
        top: 13px;
      }
    }

    &:hover{
      >span{
        padding-left:30px;
        color:#1C6AFF;
        &::before{
          width:20px;
        }
      }
    }
  }
}

@media all and (max-width: 630px) {
  .nav_wrapper__menu>ul{
    padding-top: 0;
  }
}

</style>