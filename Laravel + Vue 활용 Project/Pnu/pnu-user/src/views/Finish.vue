<template>
  <section id="pusan--finish">
    <div class="bg-color"></div>
    <div class="_finish_bg_wrap">
      <div class="_finish_bg-img _finish_bg-left"></div>
      <div class="_finish_bg-img _finish_bg-right"></div>
    </div>
    <div class="_finish_wrap">
      <div class="_check_box" :class="{on: isChecked}"></div>
      <p class="competency_fibish">평가 완료</p>
      <div class="_desc">
        수고하셨습니다.
        <br />
        <b>핵심역량평가를 완료</b>하셨습니다.
        <br />아래 버튼을 통해 분석결과를
        <br />확인하실 수 있습니다.
      </div>
    </div>
    <div class="finish_btn_wrap">
      <div class="_all_result">
        <a href="#" class="_open_result" :class="{active: isChecked}" @click.prevent="showReport1">
          8대 핵심역량 결과확인
          <span class="_result_open">확인</span>
        </a>
      </div>
      <div class="_piece_result">
        <a href="#" @click.prevent="showReport2">
          통합핵심역량 결과확인
          <span class="_ready">(준비중)</span>
        </a>
        <a href="#" @click.prevent="showReport3">
          전체 핵심역량 결과확인
          <span class="_ready">(준비중)</span>
        </a>
        <a href="#" @click.prevent="showReport4">
          우수핵심역량 결과확인
          <span class="_ready">(준비중)</span>
        </a>
        <a href="#" @click.prevent="showReport5">
          확충해야할 핵심역량 결과확인
          <span class="_ready">(준비중)</span>
        </a>
      </div>
    </div>

    <template v-if="notice.isVisible">
      <div class="notice-bg" @click.prevent="noticeClose"></div>
      <div class="notice">
        <div class="close" @click.prevent="noticeClose">[닫기]</div>
        <h1>안내</h1>
        <div class="text">
          본 진단은 학기당 1회만 응시 가능합니다.
          결과 점수는 전체 학생의 결과값과 비교하여 제시되므로, 응답 인원이 일정 수준을 넘어설 때까지는 확인이 불가능합니다.
          응답 인원이 일정 수준을 넘어서면 푸쉬 알람을 통해 알려드릴 것입니다.
        </div>
      </div>
    </template>
  </section>
</template>

<script>
export default {
  name: 'finish',
  data () {
    return {
      isChecked: false,
      notice: {
        isVisible: true
      }
    }
  },
  async mounted () {
    await this.$nextTick()
    setTimeout(() => {
      this.isChecked = true
    }, 100)

    if (this.$route.params.notice) {

    }
  },
  methods: {
    showReport1 () {
      this.$router.push('/report')
    },
    showReport2 () {
      alert('준비중입니다')
    },
    showReport3 () {
      alert('준비중입니다')
    },
    showReport4 () {
      alert('준비중입니다')
    },
    showReport5 () {
      alert('준비중입니다')
    },
    noticeClose () {
      this.notice.isVisible = false
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../styles/scss/finish.scss";
@import "../styles/scss/layout/responsive.scss";

#pusan--finish {
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
}

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
  z-index: 1;
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
  z-index: 2;
  max-width: 480px;
  margin-left: auto;
  margin-right: auto;
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
</style>
