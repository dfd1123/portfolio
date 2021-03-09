<template>
  <div v-if="dataList !== null" id="hlthreport_page" class="page">
    <header-component
      :hd-title="`${healthReportInfo[0].bt_order}기 건강리포트`"
      :hd-theme-none-bg="true"
      @privButtonClick="privButtonClick"
    />
    <div class="page_container">
      <div class="contents">
        <!-- 건강리포트 상단 -->
        <div class="hlthreport_hd">
          <div class="in_bg_color" :class="[['active1', 'active2', 'active3' ][menu]]"></div>
          <!-- 메인 카테고리 -->
          <ul class="hlth_main_category">
            <li
              v-for="(item, index) in menus"
              :key="index"
              :class="{ active : index === menu }"
              @click="menuSelected(index)"
            >
              <span :ref="'menu'+index">{{ item.menu }}</span>
            </li>
            <span class="indicator" ref="indicator-menu" style="transition: none"></span>
          </ul>
          <!-- end -->
          <div class="in_symbol">
            <div class="waves"></div>
            <img src="/assets/images/logos/logo_bcg_character_big.svg" alt="반창꼬 캐릭터" class="orange" />
          </div>
          <article class="in_wordcard">
            <div>
              <h2>
                반창꼬쌤의
                <b>한마디</b>
              </h2>
              <p class="in_word">{{healthReportInfo[menu].hrp_comment}}</p>
              <input type="button" value="+자세히보기" @click="showDoctorsWord" />
            </div>
          </article>
          <router-link to="/health/report-more" class="in_more_btn">+더보기</router-link>
        </div>
        <!-- 건강리포트 상단 end -->
        <div class="hlthreport_contents">
          <!-- 서브카테고리 -->
          <div class="hlth_sub_category">
            <ul>
              <li
                v-for="(item, index) in dataList[menu]"
                :key="index"
                :class="{ active : index === submenu }"
                @click="submenuSelected(index)"
              >
                <span :ref="'submenu'+index">{{ ['영양개선', '생활개선', '보조약품'][index] }}</span>
              </li>
              <span class="indicator" ref="indicator-submenu" style="transition: none"></span>
            </ul>
          </div>
          <!-- 서브카테고리 end -->
          <!-- 내용영역 -->
          <!-- 1. 영양개선 -->
          <div class="in_contents_area" v-show="submenu === 0">
            <article class="panel--improve">
              <h3 class="visually-hidden">영양개선 패널</h3>
              <ul>
                <li
                  v-for="(item, index) in dataList[menu][submenu]"
                  :key="index"
                  class="_list clearfix"
                  @click="open(item.ntrcn_link)"
                >
                  <figure class="_review_thumb">
                    <img
                      :src="item.ntrcn_thumb ? `/fdata/nutrition_thumb/${item.ntrcn_thumb}` : ''"
                      alt="썸네일 이미지"
                    />
                  </figure>
                  <h4 class="_review_con">{{item.ntrcn_title}}</h4>
                </li>
              </ul>
            </article>
            <article v-show="healthReportInfo[menu].hrp_comment_med" class="panel--docters_word">
              <div class="_card">
                <figure class="docter_profile">
                  <img
                    :src="healthReportInfo[menu].adm_thumb ? `/fdata/admin_thumb/${healthReportInfo[menu].adm_thumb}` : '/assets/images/logos/logo_bcg_character_dark.svg'"
                    alt="의사선생님사진"
                  />
                </figure>
                <h3>반창꼬 쌤 의학 한마디</h3>
                <p>{{healthReportInfo[menu].hrp_comment_med}}</p>
              </div>
            </article>
          </div>
          <!-- end -->
          <!-- 2. 생활개선 -->
          <div class="in_contents_area" v-show="submenu === 1">
            <article class="panel--improve panel--improve_video">
              <h3 class="visually-hidden">생활개선 패널</h3>
              <ul>
                <li
                  v-for="(item, index) in dataList[menu][submenu]"
                  :key="index"
                  class="_list clearfix"
                  @click="open(item.hlth_link)"
                >
                  <figure class="_review_thumb">
                    <img
                      :src="item.hlth_thumb ? `/fdata/health_thumb/${item.hlth_thumb}` : ''"
                      alt="썸네일 이미지"
                    />
                  </figure>
                  <div class="_review_con">
                    <h4>{{item.hlth_title}}</h4>
                    <p>{{item.hlth_desc}}</p>
                  </div>
                </li>
              </ul>
            </article>
            <article v-show="healthReportInfo[menu].hrp_comment_med" class="panel--docters_word">
              <div class="_card">
                <figure class="docter_profile">
                  <img
                    :src="healthReportInfo[menu].adm_thumb ? `/fdata/admin_thumb/${healthReportInfo[menu].adm_thumb}` : '/assets/images/logos/logo_bcg_character_dark.svg'"
                    alt="의사선생님사진"
                  />
                </figure>
                <h3>반창꼬 쌤 의학 한마디</h3>
                <p>{{healthReportInfo[menu].hrp_comment_med}}</p>
              </div>
            </article>
          </div>
          <!-- end -->
          <!-- 3. 보조약품 -->
          <div class="in_contents_area" v-show="submenu === 2">
            <article class="panel--recommend_product">
              <h3 class="_title">{{get(healthReportInfo[menu], 'hrp_extra.mdcn_sub_title', '')}}</h3>
              <div class="_list">
                <div
                  class="product_card"
                  v-for="(item, index) in dataList[menu][submenu]"
                  :key="index"
                >
                  <router-link :to="{ name: 'HealthReportProduct', params: { item: item }}">
                    <figure class="_card">
                      <img
                        src="/assets/images/icons/icon_product_list.svg"
                        alt="플러스아이콘"
                        class="plus_icon"
                        v-if="index === 0"
                      />
                      <img
                        class="thumb"
                        :src="item.mdcn_thumb ? `/fdata/medicine_thumb/${item.mdcn_thumb}` : ''"
                        alt="상품 썸네일"
                      />
                      <h4 v-html="item.mdcn_title"></h4>
                      <figcaption v-html="get(item, 'mdcn_extra.sub_title', '')"></figcaption>
                    </figure>
                  </router-link>
                </div>
              </div>
            </article>
            <article v-show="healthReportInfo[menu].hrp_comment_med" class="panel--docters_word">
              <div class="_card">
                <figure class="docter_profile">
                  <img
                    :src="healthReportInfo[menu].adm_thumb ? `/fdata/admin_thumb/${healthReportInfo[menu].adm_thumb}` : '/assets/images/logos/logo_bcg_character_dark.svg'"
                    alt="의사선생님사진"
                  />
                </figure>
                <h3>반창꼬 쌤 의학 한마디</h3>
                <p>{{healthReportInfo[menu].hrp_comment_med}}</p>
              </div>
            </article>
          </div>
          <!-- end -->
          <!-- 내용영역 end -->
        </div>
      </div>
    </div>
    <footer-navigation :report-on="true"></footer-navigation>
  </div>
</template>

<script>
import get from 'lodash/get'

export default {
  name: 'HealthReport',
  data () {
    return {
      id: this.$route.params.id,
      healthReportInfo: null,
      menu: null,
      submenu: null,
      dataList: null,
      menus: [],
      submenus: []
    }
  },
  async mounted () {
    this.healthReportInfo = await this.$axios.get('/health_report_pages', {
      params: {
        hr_no: this.id
      }
    }).then(response => response.data)

    this.menu = Number(this.$route.query.menu) || 0
    this.submenu = Number(this.$route.query.submenu) || 0

    this.menus = this.healthReportInfo.map(page => ({ ...{ menu: page.dc_cat_name } }))
    this.dataList = this.healthReportInfo.map(menu => [menu.ntrcn_info, menu.health_info, menu.mdcn_info])

    await this.$nextTick()

    this.menuSelected(this.menu)
    this.submenuSelected(this.submenu)

    this.saveSelectionToQuery()
  },
  methods: {
    ...{ get },
    privButtonClick () {
      this.$router.push('/health/status')
    },
    menuSelected (index) {
      this.menu = index
      this.moveIndicatorTo('menu', index)
      this.saveSelectionToQuery()
    },
    submenuSelected (index) {
      this.submenu = index
      this.moveIndicatorTo('submenu', index)
      this.saveSelectionToQuery()
    },
    moveIndicatorTo (type, index) {
      const eachItem = this.$refs[type + index][0]
      const indicatorBar = this.$refs['indicator-' + type]
      const eachWidth = eachItem.clientWidth
      const eachLeft = eachItem.offsetLeft
      indicatorBar.style.width = eachWidth + 'px'
      indicatorBar.style.left = eachLeft + 'px'

      // 최초 로딩 시에만 트랜지션 비활성화
      setTimeout(() => { indicatorBar.style.transition = null })
    },
    saveSelectionToQuery () {
      this.$router.replace({
        name: 'HealthReport',
        query: {
          menu: this.menu,
          submenu: this.submenu
        }
      }, () => { })
    },
    showDoctorsWord () {
      this.$swal({
        showCancelButton: false,
        confirmButtonText: '확인했어요!',
        html: this.healthReportInfo[this.menu].hrp_comment_detail,
        customClass: {
          container: 'bcg-swal__container',
          popup: 'bcg-swal__popup bcg-swal__popup-bdtop_ylw',
          content: 'bcg-swal__content--theme01 bcg-swal__content--ta_left bcg-swal__content--pd_05em',
          confirmButton: 'bcg-swal__bdradius-button',
          cancelButton: 'bcg-swal__cancel-button',
          image: 'bcg-swal__orange_doctors'
        },
        imageUrl: '/assets/images/logos/logo_doctors_word.svg',
        imageWidth: 287,
        imageHeight: 83,
        imageAlt: 'Custom image'
      })
    },
    open (url) {
      window.open(url, '_blank', '')
    }
  }
}
</script>

<style lang="scss" scoped>
#hlthreport_page {
  background-color: #f7f7f7;
  position: relative;
  overflow-y: scroll;
  padding-bottom: $g-page_footer_nav_height;
  .page_title {
    @include position($t: 0, $l: 0);
  }
  .orange {
    position: relative;
    animation: orangeAni 3.5s infinite;
    animation-timing-function: ease-in-out;
  }
  .contents {
    padding: 0 0 3rem;
  }
}
.hlthreport_hd {
  padding-top: $g-page_title_height;
  height: 30vh;
  min-height: 320px;
  background-color: white;
  text-align: center;
  position: relative;
  padding-bottom: 4.5rem;
  .in_bg_color {
    @include position($t: 0, $l: 0);
    @include zindex("zero");
    width: 100%;
    height: 100%;
    display: inline-block;
    background-color: #ff6926;
    transition: background-color 0.8s;
  }
  .in_bg_color.active1 {
    background-color: #ff6926;
    transition: background-color 0.8s;
  }
  .in_bg_color.active2 {
    background-color: #00bcc2;
    transition: background-color 0.8s;
  }
  .in_bg_color.active3 {
    background-color: #4f8af1;
    transition: background-color 0.8s;
  }
  .hlth_main_category {
    @include position(relative);
    @include zindex("zero");
    @include remFont("34px", $weight: 400, $color: rgba(255, 255, 255, 0.6));
    display: inline-block;
  }
  .hlth_main_category > li {
    display: inline-block;
    margin: 0 10px;
    cursor: pointer;
  }
  .hlth_main_category > li span {
    display: inline-block;
    width: 100%;
    letter-spacing: -2px;
  }
  .hlth_main_category > li.active span {
    @include setFont(inherit, $weight: bold, $color: white);
  }
  .indicator {
    @include position($b: 0, $l: 0);
    display: inline-block;
    height: 3px;
    background-color: white;
    transition: all 0.3s;
  }
  .in_symbol {
    @include zindex("default");
    position: relative;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .in_wordcard {
    @include position($b: -3rem, $l: 0);
    @include zindex("default");
    width: 100%;
    padding: 0 1.45rem;
    & > div {
      background-color: white;
      text-align: left;
      padding: 1.1rem 1rem 1.15rem;
      color: $gray-01;
      border-radius: 15px;
      position: relative;
      box-shadow: 0 8px 10px rgba(0, 0, 0, 0.08);
      max-width: 570px;
      margin: 0 auto;
    }
    h2 {
      @include remFont("19px");
      margin-bottom: 11px;
    }
    h2 b {
      font-weight: bold;
    }
    p {
      @include remFont("15px", $weight: 300);
      line-height: 1.45;
    }
    input {
      @include initInput;
      @include position($r: 0.7rem, $t: 1.5rem);
      @include setFont(12px, $color: $theme-03);
    }
    input:active {
      color: #ffad09;
    }
  }
  .in_more_btn {
    @include position($t: 135px, $r: 0);
    @include zindex("default");
    @include setFont(12px, $color: $theme-03);
    background-color: white;
    padding: 4px 8px 4px 13px;
    border-top-left-radius: 20px;
    border-bottom-left-radius: 20px;
    &:active {
      background-color: #fffaef;
    }
  }
}
.hlthreport_contents {
  .hlth_sub_category {
    padding-top: 4.5rem;
    background-color: white;
    text-align: center;
    border-bottom: 1px solid #e6e6e6;
    & > ul {
      display: inline-block;
      text-align: center;
      position: relative;
    }
    & > ul > li {
      @include remFont("18px", $weight: 400, $color: rgba($gray-01, 0.5));
      display: inline-block;
      margin: 0 18px;
      cursor: pointer;
    }
    & > ul > li > span {
      display: inline-block;
      padding-bottom: 13px;
    }
    & > ul > li.active span {
      @include remFont("18px", $weight: bold, $color: $gray-01);
    }
    .indicator {
      @include position($b: 0, $l: 0);
      height: 4px;
      background-color: $theme-03;
      display: inline-block;
      transition: all 0.3s;
    }
  }
  .in_contents_area {
    text-align: center;
    padding: 1.5rem 0.85rem;
  }
}

.panel--recommend_product {
  ._title {
    font-weight: bold;
    margin-bottom: 10px;
  }
  ._list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    max-width: 570px;
    margin: 10px auto;
  }
  .product_card {
    width: 50%;
    padding: 6px;
    & ._card {
      background-color: white;
      height: 100%;
      border-radius: 6px;
      padding: 1rem 0.9rem;
      position: relative;
    }
    & ._card .thumb {
      max-height: 76px;
      margin-bottom: 10px;
    }
    & ._card .plus_icon {
      @include position($b: -37px, $r: -37px);
      @include zindex("default");
      width: 64px;
      height: 64px;
      @include responsive("iphone5") {
        @include position($b: -32px, $r: -32px);
        width: 55px;
        height: 55px;
      }
      pointer-events: none;
    }
    & ._card h4 {
      @include setFont(14px, $color: $gray-01, $weight: bold);
      line-height: 1.4;
    }
    & ._card figcaption {
      @include setFont(12px, $weight: 300, $color: #acacac);
    }
  }
}
.panel--docters_word {
  padding: 0 6px;
  max-width: 570px;
  margin: 0 auto;
  ._card {
    @include remFont("15px");
    background-color: white;
    border-radius: 12px;
    padding: 0.9rem 0.9rem 0.9rem 6rem;
    position: relative;
    text-align: left;
    & > h3 {
      @include setFont(0.85em, $color: white);
      background-color: $theme-03;
      display: inline-block;
      border-radius: 30px;
      padding: 3px 15px;
      margin-bottom: 10px;
    }
  }
  .docter_profile {
    @include position($t: 0.9rem, $l: 0.9rem);
    width: 4.15rem;
    height: 4.15rem;
    background-color: gray;
    border-radius: 50%;
    overflow: hidden;
    & > img {
      width: 100%;
    }
  }
}
.panel--improve {
  padding: 0 6px;
  max-width: 570px;
  margin: 0 auto;
  ._list {
    background-color: white;
    border-radius: 6px;
    box-shadow: 4px 13px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 1rem;
    position: relative;
    overflow: hidden;
    & > ._review_thumb {
      float: left;
      width: 49%;
    }
    & > ._review_thumb img {
      width: 100%;
      float: left;
    }
    & > ._review_con {
      @include remFont("15px", $weight: 300);
      @include position($t: 0, $l: 0);
      display: flex;
      width: 100%;
      height: 100%;
      text-align: left;
      padding: 13px 13px 13px calc(49% + 15px);
      align-items: center;
      line-height: 1.45;
      flex-wrap: wrap;
    }
    & > ._review_con h4 {
      @include ellipsis(2, 22px);
    }
    & > ._review_con p {
      @include setFont(0.85em, $weight: 400, $color: $gray-03);
    }
  }
}
.panel--improve_video {
  ._list {
    padding: 1.25rem;
  }
}

.waves {
  position: absolute;
  left: 50%;
  top: 50%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  background: rgba(255, 255, 255, 0.25);
  width: 140px;
  height: 140px;
  border-radius: 50%;
}
.waves:before,
.waves:after {
  content: "";
  position: absolute;
  background: rgba(255, 255, 255, 0.5);
  margin-left: -35px;
  margin-top: 35px;
  width: 70px;
  height: 70px;
  border-radius: 50%;
  animation: wave 4.5s infinite linear;
}
.waves:after {
  opacity: 0;
  animation: wave 4s 2s infinite linear;
}
@keyframes wave {
  0% {
    -webkit-transform: scale(0);
    transform: scale(0);
    opacity: 1;
  }
  100% {
    -webkit-transform: scale(3.5);
    transform: scale(3.5);
    opacity: 0;
  }
}
</style>
