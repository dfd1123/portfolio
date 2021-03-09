<template>
  <div>
    <section id="pusan--start" class="pusan_contents">
      <div class="onlybg">
        <div class="start--center">
          <h2 class="_title">
            <p>8대 핵심역량평가</p>
            <small>메뉴를 선택해주세요.</small>
          </h2>
          <div class="startbox start--start" @click.prevent="start">
            <p>8대 핵심역량</p>
            <p class="text--title">평가시작</p>
            <a href="#" class="start__btn">Go</a>
          </div>
          <div class="startbox start--result" @click.prevent="report">
            <p class="text--title">분석결과</p>
            <p>확인하기</p>
            <a href="#" class="start__btn">Go</a>
          </div>

          <div class="notice-all-time">
            <div>진단 안내</div>
            <p>부산대학교는 4H 통섭형 인재를 양성하기 위해 8대 핵심역량을 설정하여 관리하고 있습니다.</p>
            <p>본 진단은 8개 역량별로 실시되며, 결과는 개인별 확인이 가능합니다.</p>
            <p>축적된 데이터는 개인정보를 배제하고 통계 처리되어 학생 지원을 위한 자료로 활용될 것입니다.</p>
            <p>각 문항에 성실히 응답해 주시기 바랍니다.</p>
          </div>

          <!--
          <template v-if="notice.isVisible">
            <div class="notice-bg" @click.prevent="noticeClose"></div>
            <div class="notice">
              <div class="close" @click.prevent="noticeClose">[닫기]</div>
              <h1>임시 안내</h1>
              <div class="text">
                본 진단은 학기당 1회만 응시 가능합니다.
                결과 점수는 전체 학생의 결과값과 비교하여 제시되므로, 응답 인원이 일정 수준을 넘어설 때까지는 확인이 불가능합니다.
                응답 인원이 일정 수준을 넘어서면 푸쉬 알람을 통해 알려드릴 것입니다.
              </div>
            </div>
          </template>
          -->
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: 'home',
  data () {
    return {
      /*
      notice: {
        isVisible: true
      }
      */
    }
  },
  async created () {
    /*
    if (sessionStorage.cesaNoticeFlag) {
      this.notice.isVisible = false
    }
    */

    await this.userDetail()
  },
  methods: {
    start () {
      this.$router.push('/competency-start')
    },
    report () {
      this.$router.push('/report')
    }
    /*
    noticeClose () {
      this.notice.isVisible = false
      sessionStorage.setItem('cesaNoticeFlag', 'value')
    }
    */
  }
}
</script>

<style lang="scss" scoped>
@import "../styles/scss/start.scss";
@import "../styles/scss/layout/responsive.scss";

.notice-all-time {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  color: white;
  background-color: rgba(0, 0, 0, 0.5);
  padding: 1em;
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
}

.notice-all-time > div {
  font-size: 1.3em;
  padding: 10px;
  text-align: center;
}

.notice-bg {
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  background: black;
  opacity: 0.5;
}

.notice {
  position: fixed;
  left: 1.3em;
  right: 1.3em;
  top: 13em;
  bottom: 18em;
  background: white;
  box-shadow: 5px 5px;
  border-radius: 5px;
}

h1 {
  font-size: 2em;
  padding: 0.67em 0;
  text-align: center;
  background-color: rgb(0, 63, 143);
  color: white;
}

.notice > .text {
  padding: 1.5em;
  font-size: 1em;
}

.close {
  position: absolute;
  color: white;
  top: 10px;
  right: 10px;
  padding: 0;
}

.onlybg {
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
}
</style>
