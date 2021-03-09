<template>
  <div id="nav_wrapper">
    <div class="nav_slider">
      <div class="nav_slider__con">
        <div class="nav_wrapper__menu">
          <div class="nav_wrapper__menu_group" id="bsns_to_inquiry">
            <span class="back_btn" :class="{ active : hide }" @click="test">
              <span class="in_arrow"></span>
            </span>
            <h2 class="title first_title" :class="{ active : hide }">business</h2>
            <ul class="nav">
              <li class="nav_list" :class="{ active : hide }">
                <a :href="pocketUrl+'/Webr/index_default'" class="menu" target="_blank">서비스 소개</a>
              </li>
              <li class="nav_list" :class="{ active : hide }">
                <a href="#" id="qna_btn" class="menu" ref="testEle" @click="test">문의하기</a>
              </li>
            </ul>
          </div>
          <div
            class="nav_wrapper__menu_group nav_wrapper__menu_group--contact"
            :class="hide ? 'active' : 'show'"
            v-show="contactGroup"
          >
            <h2 class="title">contact</h2>
            <ul class="nav">
              <li class="nav_list">
                <a :href="pocketUrl+'/Webr/adrs'" target="blank" class="menu">오시는길</a>
              </li>
            </ul>
          </div>
          <div class="nav_wrapper__menu_group nav_wrapper__inquiry_group" v-show="hide">
            <ul class="nav">
              <li class="inquiry_list" :class="{ active : inquiry }">
                <a href="tel:18002550" class="menu tel">
                  전화문의
                  <em class="small_text">1800-2550</em>
                </a>
              </li>
              <li class="inquiry_list" :class="{ active : inquiry }">
                <a
                  href="https://open.kakao.com/o/s3Il3lIb"
                  target="_blank"
                  class="menu chat"
                  id="openchat"
                >
                  채팅문의
                  <em class="small_text">카카오톡 플러스친구</em>
                </a>
              </li>
              <li class="inquiry_list" :class="{ active : inquiry }">
                <a :href="pocketUrl+'/Webr/support'" target="_blank" class="menu mail">
                  메일문의
                  <em class="small_text">문의사항 작성하기</em>
                </a>
              </li>
            </ul>
          </div>
          <div class="nav_wrapper__sns_group">
            <a
              href="https://www.youtube.com/channel/UCyfPPaalcgzug1C7Gon9efg"
              class="btn"
              target="_blank"
            >
              <img src="assets/images/icon/btn_01-youtube.svg" alt="포켓컴퍼니 유튜브" />
            </a>
            <a
              href="https://stalkture.com/a/ceo.pocketcompany/2252432570"
              class="btn"
              target="_blank"
            >
              <img src="assets/images/icon/btn_02-insta.svg" alt="포켓컴퍼니 인스타그램" />
            </a>
            <a
              href="https://www.facebook.com/pages/category/Business-Service/%ED%8F%AC%EC%BC%93%EC%BB%B4%ED%8D%BC%EB%8B%88%E3%85%A1%EC%82%AC%EC%97%85%EA%B3%84%ED%9A%8D%EC%84%9C%EC%8A%A4%ED%83%80%ED%8A%B8%EC%97%85%EC%A0%95%EB%B6%80%EC%A7%80%EC%9B%90%ED%88%AC%EC%9E%90%EC%A0%9C%EC%95%88%EC%84%9C-377103632904873/"
              class="btn"
              target="_blank"
            >
              <img src="assets/images/icon/btn_03-facebook.svg" alt="포켓컴퍼니 페이스북" />
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "nav-component",
  data() {
    return {
      hide: false,
      contactShow: true,
      contactGroup: true,
      inquiry: false,
      pocketUrl: "https://www.xn--9i1b674cwc38r6pa.com"
    };
  },
  methods: {
    test() {
      this.hide = !this.hide;

      if (this.hide === true) {
        setTimeout(() => {
          this.contactGroup = false;
          this.inquiry = true;
        }, 200);
      } else {
        setTimeout(() => {
          this.contactGroup = true;
          this.inquiry = false;
        }, 200);
      }
    }
  }
};
</script>

<style scoped>
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
</style>