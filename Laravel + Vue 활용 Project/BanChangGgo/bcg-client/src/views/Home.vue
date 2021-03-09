<template>
  <div id="home_page" class="page page--default">
    <div class="page_container page_scrl_pd_bt">
      <!-- 상단 정보영역 -->
      <div class="home_hd">
        <theme-bg-02 :home-vue="true"></theme-bg-02>
        <div class="hlth_main_infos">
          <!-- 날씨정보 -->
          <transition appear name="slide-fadeup">
            <div class="info_today">
              <span class="in_date">{{ this.$moment().format('YY.MM.DD(ddd)') }}</span>
              <!--
              <div class="in_weather">
                <span class="icon_weather cloudy"></span>
                <b>22</b>
                <i>˚C</i>
              </div>
              -->
            </div>
          </transition>
          <!-- end -->
          <!-- 반창꼬 캐릭터정보 -->
          <transition appear name="slide-fadeup">
            <figure class="info_character">
              <DoughnutChart
                v-bind:percent="planProgressPercent"
                :width="145"
                :height="145"
                :strokeWidth="7.5"
                foregroundColor="#ffffff"
                backgroundColor="#ffffff4d"
              />
              <div class="in_circle">
                <img src="/assets/images/logos/logo_bcg_character.svg" alt="반창꼬 캐릭터" class="orange" />
              </div>
              <figcaption class="percent">
                <i class="in_gage" v-bind:style="{ width: planProgressPercent + '%'}"></i>
                <b>{{planProgressPercent}}%</b>
              </figcaption>
            </figure>
          </transition>
          <!-- end -->
          <transition appear name="slide-fade">
            <input type="button" class="info_qmark" @click="showRestrictionNotice" />
          </transition>
          <!-- end -->
        </div>
      </div>
      <!-- end 상단 정보영역 -->
      <!-- 하단 회색박스 컨텐츠 -->
      <div class="contents">
        <inner-radius-area></inner-radius-area>
        <!-- 건강습관 추가버튼 있는 영역 -->
        <div class="hlth_habit_add_area">
          <router-link to="/plan/add" class="in_btn" />
          <p class="in_word">365일, 나만의 건강 습관</p>
          <span
            v-if="user.usr_batch.hr_no === null"
            class="in_report_status"
          >{{userBatchOrder}}기 리포트 이용중</span>
        </div>
        <!-- end -->
        <!-- 건강습관 리스트영역 -->
        <div class="hlth_habit_view_area">
          <div class="hlth_habit_group">
            <transition-group v-if="planList.length !== 0">
              <health-plan-list
                v-for="item in planList"
                :key="item.id"
                :plan-to-do="item"
                @planListClick="$router.push(`/plan/info/${item.id}`)"
                @planCheckClick="setPlanItem({id: item.id, result: item.result ^= 1})"
              ></health-plan-list>
            </transition-group>
            <div v-else>컨텐츠를 준비중입니다...</div>
          </div>
        </div>
        <!-- end -->
      </div>
      <!-- end 하단 회색박스 컨텐츠 -->
      <!-- 어디가 아프신가요 라벨지 -->
      <!--
      <div class="home_tag" :class="{ active : isTagVisible }">
        <div class="in_white_tag" @click="isTagVisible = !isTagVisible">
          <h3 class="in_red_tag" @click="$router.push('/health/content')">
            <b>{{user.usr_name}}</b>님 어디가 아프신가요?
            <img src="/assets/images/btn/btn_bcg_red.svg" alt="반창꼬 버튼" />
          </h3>
          <img src="/assets/images/btn/btn_spread.svg" alt="라벨지 펼치기 버튼" />
        </div>
      </div>
      -->
      <!-- end -->
    </div>
    <footer-navigation :home-on="true"></footer-navigation>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import DoughnutChart from 'vue-doughnut-chart'

export default {
  name: 'home',
  components: {
    DoughnutChart
  },
  data () {
    return {
      isTagVisible: false
    }
  },
  async created () {
    await this.fetchData()
  },
  async mounted () {
    await this.$nextTick()

    if (!localStorage.bcg_confirm_restriction) {
      localStorage.bcg_confirm_restriction = true
      this.showRestrictionNotice()
    }
  },
  computed: {
    ...mapGetters([
      'user',
      'userBatchOrder',
      'plan',
      'planList',
      'planProgressPercent'
    ])
  },
  methods: {
    ...mapActions(['getPlan', 'setPlanItem']),
    async fetchData () {
      try {
        await this.getPlan()
      } catch (e) {
        console.log(e)
      }
    },
    async showRestrictionNotice () {
      const value = await this.$swal({
        confirmButtonText: '네, 확인했습니다.',
        html: '메디플래너에서<br> 매일마다 일정을 90% 이상 확인하신 분들만<br> 건강보고서를 받으실 수 있습니다.',
        customClass: {
          container: 'bcg-swal__container',
          popup: 'bcg-swal__popup bcg-swal__popup--doublebtn',
          content: 'bcg-swal__content bcg-swal__content--theme01',
          confirmButton: 'bcg-swal__confirm-button',
          cancelButton: 'bcg-swal__cancel-button',
          image: 'bcg-swal__orange_single'
        },
        imageUrl: '/assets/images/logos/logo_bcg_swal_single.svg',
        imageWidth: 103,
        imageHeight: 103,
        imageAlt: 'Custom image'
      }).then(result => result.value)

      if (!value) {
        return
      }

      if (this.user.usr_batch.pt_day !== null) {
        return
      }

      this.$swal({
        confirmButtonText: '네, 확인했습니다.',
        html: '오후 12시 이후에 리포트를 신청하신 분들은 다음날부터<br> 건강리포트 기록이 시작됩니다.',
        customClass: {
          container: 'bcg-swal__container',
          popup: 'bcg-swal__popup bcg-swal__popup--doublebtn',
          content: 'bcg-swal__content bcg-swal__content--theme01',
          confirmButton: 'bcg-swal__confirm-button',
          cancelButton: 'bcg-swal__cancel-button',
          image: 'bcg-swal__orange_single'
        },
        imageUrl: '/assets/images/logos/logo_bcg_swal_single.svg',
        imageWidth: 103,
        imageHeight: 103,
        imageAlt: 'Custom image'
      })
    }
  }
}
</script>

<style lang="scss" scoped>
#home_page {
  overflow: hidden;
  .theme_bg_box_02 {
    @include position($t: 0, $l: 0);
    height: 100%;
  }
  .page_container {
    overflow-x: hidden;
    overflow-y: scroll;
    background-color: #f7f7f7;
    -webkit-overflow-scrolling: touch;
  }
  .contents {
    padding: 0 1.2rem 0;
    padding-bottom: 1.2rem;
    height: auto;
    text-align: center;
    position: relative;
    overflow: visible;
  }
  .home_hd {
    width: 100%;
    height: 45vh;
    min-height: 360px;
    position: relative;
    .theme_bg_box {
      @include position($t: 0, $l: 0);
      @include zindex("zero");
      width: 100%;
      height: 100%;
    }
  }
}

.hlth_main_infos {
  @include zindex("default");
  width: 100%;
  height: 100%;
  position: relative;
  color: white;

  .info_today {
    @include position($t: 50px, $l: 20px);
    line-height: normal;
    .in_date {
      @include setFont(13px);
      letter-spacing: 0;
    }
    .in_weather {
      @include emFont("26px", $weight: 500);
    }
    .in_weather > b {
      @include setFont(inherit, $weight: bold);
      margin-right: 3px;
    }
  }
  .info_character {
    @include position($b: 75px, $l: 50%);
    @include translate($x: -50%, $y: 0);
    text-align: center;
    .in_circle {
      @include position($t: 22px, $l: 50%);
      @include translate($x: -50%, $y: 0);
      $circle-area: 100px;
      width: $circle-area;
      height: $circle-area;
      border-radius: 50%;
      background-color: white;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .in_circle:after {
      @include position($b: 10px);
      content: "";
      width: 45px;
      height: 11px;
      border-radius: 50%;
      background-color: #e6e6e6;
    }
    .in_circle .orange {
      @include zindex("default");
      width: 60%;
      position: relative;
      animation: orangeAni 3.5s 10;
      animation-timing-function: ease-in-out;
    }
    .percent {
      @include setFont(13px, $color: $theme-02, $weight: bold);
      @include pushAuto;
      background-color: white;
      border: 3px solid white;
      border-radius: 30px;
      width: 60px;
      letter-spacing: 0;
    }
    .percent b {
      @include setFont(inherit, $color: inherit, $weight: inherit);
      display: inline-block;
      width: 100%;
      border-radius: 30px;
    }
    .percent .in_gage {
      @include position($t: 0px, $l: 0);
      background-color: rgba(252, 200, 88, 0.3);
      width: 50%;
      height: 100%;
      border-radius: 30px;
      display: inline-block;
    }
  }
  .info_qmark {
    @include position($t: 53px, $r: 20px);
    @include initInput;
    @include bgStyle(50%, 50%, contain);
    width: 25px;
    height: 25px;
    background-image: url("/assets/images/icons/icon_circle_qmark.svg");
  }
}

.hlth_habit_add_area {
  @include zindex("default");
  position: relative;
  top: -20px;
  .in_btn {
    @include bgStyle(50%, 50%, 50%);
    display: inline-block;
    width: 43px;
    height: 43px;
    background-color: $theme-02;
    box-shadow: 0 5px 13px rgba(250, 187, 60, 0.4);
    border-radius: 50%;
    background-image: url("/assets/images/icons/icon_plus_white.svg");
    transition: all 0.3s;
  }
  .in_btn:active {
    background-color: darken($theme-02, 3%);
    transform: scale(1.1);
    transition: all 0.3s;
  }
  .in_word {
    @include remFont("16px", $weight: 400);
    padding: 0.5rem 0 0.4rem;
  }
  .in_report_status {
    @include remFont("13px", $color: $theme-02);
    padding: 5px 10px;
    background-color: white;
    display: inline-block;
    border-radius: 30px;
  }
}

.hlth_habit_view_area {
  @include zindex("default");
  position: relative;
  top: -10px;
}

.home_tag {
  @include position(fixed, $b: 7rem);
  @include zindex("nav");
  width: 100%;
  max-width: 400px;
  height: 5rem;
  padding-right: 2rem;
  transition: all 0.4s;
  &.active {
    @include position(fixed, $b: 7rem, $l: 0);
    transition: all 0.3s;
  }
  @include responsive("tablet_pad") {
    left: -32.5%;
  }
  @include responsive("tablet_tab") {
    left: -43.5%;
  }
  @include responsive("mobile") {
    left: -83%;
  }
  @include responsive("iphone5") {
    left: -81%;
  }
  .in_white_tag {
    position: relative;
    width: 100%;
    height: 100%;
    background-color: white;
    box-shadow: 5px 10px 22px rgba(0, 0, 0, 0.1);
    padding: 1.2rem 60px 1.2rem 0.75rem;
    border: {
      top-right-radius: 50px;
      bottom-right-radius: 50px;
    }
    & > img {
      @include position($t: 50%, $r: 13px);
      @include translate($x: 0, $y: -50%);
      width: 15px;
    }
  }
  .in_red_tag {
    background: linear-gradient(-45deg, #fa753d, #fabb3c);
    border-radius: 50px;
    color: white;
    padding: 0.5rem 1rem;
    position: relative;
    & > img {
      @include position($t: -20px, $r: -25px);
      max-width: 85px;
      @include responsive("iphone5") {
        top: -15px;
        max-width: 68px;
      }
    }
    & > b {
      font-weight: bold;
    }
  }
}
</style>
