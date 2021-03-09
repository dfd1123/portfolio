<template>
  <div v-if="no">
    <div id="cp--hd">
      <a href="#" @click.prevent="prevButton" class="page"></a>
      <a href="#" class="form off" @click.prevent></a>
      <h1>역량평가</h1>
    </div>
    <section id="grobalcompetency--test">
      <div class="bggray"></div>
      <h2 class="test--title">
        <div class="test--title__fix">
          <p class="title__step">

            <b>{{answered}}</b> /{{questions}}
          </p>
          <div class="test--progress">
            <div class="test--progress__direction" :style="{width: `${progress * 100}%`}"></div>
          </div>
        </div>
        <div class="test--title__mar">
          <p class="title__number">{{String(no).padStart(2, '0')}}</p>
          <p class="title__big">8대 핵심역량</p>
          <div class="title__desc">
            <p class="title__ko">{{title}}</p>
            <p class="title__en">{{titleTop}}</p>
          </div>
        </div>
      </h2>
      <div class="test--form test--form01 active">
        <div v-for="(qna, index) in qnas[curStep - 1]" :key="index" class="test--qnawrap active">
          <h3>
            <span>
              <b>Q.</b>
              {{String([0, ...Array.from({length: curStep - 1}, (x, i) => qnas[i].length)].reduce((a, x) => a + x) + index + 1).padStart(Math.max(String(qnaSize).length, 2), '0')}}
            </span>
            {{qna.category}}
          </h3>
          <p class="test--qna__q">{{qna.question}}</p>
          <form class="test--qna__a clear">
            <label
              :for="`q1_answer__1_${index}`"
              class="test--qna__btn"
              :class="{active: qna.value === (qna.type === 'a' ? 1 : 5)}"
            >
              <p class="texfill">{{_get(qna, 'choice[0]') || '매우 그렇다'}}</p>
              <input
                v-model.number="qna.value"
                :id="`q1_answer__1_${index}`"
                type="radio"
                :value="(qna.type === 'a' ? 1 : 5)"
              />
            </label>
            <label
              :for="`q1_answer__2_${index}`"
              class="test--qna__btn"
              :class="{active: qna.value === (qna.type === 'a' ? 2 : 4)}"
            >
              <p class="texfill">{{_get(qna, 'choice[1]') || '그렇다'}}</p>
              <input
                v-model.number="qna.value"
                :id="`q1_answer__2_${index}`"
                type="radio"
                :value="(qna.type === 'a' ? 2 : 4)"
              />
            </label>
            <label
              :for="`q1_answer__3_${index}`"
              class="test--qna__btn"
              :class="{active: qna.value === (qna.type === 'a' ? 3 : 3)}"
            >
              <p class="texfill">{{_get(qna, 'choice[2]') || '보통'}}</p>
              <input
                v-model.number="qna.value"
                :id="`q1_answer__3_${index}`"
                type="radio"
                :value="(qna.type === 'a' ? 3 : 3)"
              />
            </label>
            <label
              :for="`q1_answer__4_${index}`"
              class="test--qna__btn"
              :class="{active: qna.value === (qna.type === 'a' ? 4 : 2)}"
            >
              <p class="texfill">{{_get(qna, 'choice[3]') || '그저 그렇다'}}</p>
              <input
                v-model.number="qna.value"
                :id="`q1_answer__4_${index}`"
                type="radio"
                :value="(qna.type === 'a' ? 4 : 2)"
              />
            </label>
            <label
              :for="`q1_answer__5_${index}`"
              class="test--qna__btn"
              :class="{active: qna.value === (qna.type === 'a' ? 5 : 1)}"
            >
              <p class="texfill">{{_get(qna, 'choice[4]') || '그렇지 않다'}}</p>
              <input
                v-model.number="qna.value"
                :id="`q1_answer__5_${index}`"
                type="radio"
                :value="(qna.type === 'a' ? 5 : 1)"
              />
            </label>
          </form>
        </div>
      </div>
    </section>
    <div id="next__btnwrap" :class="{active: isStepCompleted}">
      <a
        v-if="curStep < maxStep"
        href="#"
        data-num="01"
        class="next__btn next__btn01 active"
        @click.prevent="nextButton"
      >다음</a>
      <a
        v-else
        href="#"
        data-num="10"
        class="next__btn next__btn10 active last"
        @click.prevent="completeButton"
        :style="{'pointer-events': isRunning ? 'none' : 'auto'}"
      >완료</a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'competency-qna',
  data () {
    return {
      isRunning: false,
      curStep: Number(this.$route.query.step) || 1,
      id: '',
      no: '',
      title: '',
      titleTop: '',
      countQuestions: 0,
      countAnswered: 0,
      qnas: []
    }
  },
  created () {
    const { params } = this.$route

    this.$router.push({ query: { step: 1 }, params })
    if (!params.no) {
      return this.$router.push('/competency-intro')
    }

    this.id = params.id
    this.no = params.no
    this.title = params.title
    this.titleTop = params.titleTop
    this.qnas = params.qnas

    this.countQuestions = Number(params.countQuestions)
    this.countAnswered = Number(params.countAnswered)

    if (!Array.isArray(this.qnas)) {
      return this.$router.push('/competency-intro')
    }
  },
  beforeDestroy () {
    this.isRunning = true
  },
  computed: {
    isStepCompleted () {
      return this.qnas[this.curStep - 1] && this.qnas[this.curStep - 1].every(x => x.value)
    },
    answered () {
      return this.countAnswered + this.qnas.flat(2).filter(x => x.value).length
    },
    questions () {
      return this.countQuestions
    },
    maxStep () {
      return this.qnas.length
    },
    progress () {
      return (this.answered / this.questions)
    },
    qnaSize () {
      return this.qnas.flat(2).length
    }
  },
  watch: {
    $route () {
      const step = Number(this.$route.query.step)
      if (step && step > 0 && step <= this.maxStep) {
        this.curStep = step
      }
    },
    curStep () {
      this.$router.push({ query: { step: Number(this.curStep) } })
    }
  },
  methods: {
    prevButton () {
      if (this.curStep > 1) {
        this.curStep -= 1
      } else {
        if (confirm('홈화면으로 돌아가시겠습니까?')) {
          return this.$router.push('/home')
        }
      }
    },
    nextButton () {
      if (!this.isStepCompleted) {
        alert('답변을 선택하지 않은 질문이 있습니다')
        return
      }

      if (this.curStep < this.maxStep) {
        this.curStep += 1
      }
    },
    async completeButton () {
      if (this.isRunning) {
        return
      }
      this.isRunning = true

      try {
        if (!this.isStepCompleted) {
          alert('답변을 선택하지 않은 질문이 있습니다')
          return
        }

        if (!this.qnas.flat().every(x => x.value)) {
          alert('오류가 발생했습니다. 다시 진행해주시기 바랍니다')
          window.location.reload()
          return
        }

        await this.$axios
          .post('/user_cp_tests', {
            cpt_id: this.id,
            ucpt_answer: this.qnas
          })

        await this.userDetail()

        if (this.user.cpt_order < this.user.max_cpt_order) {
          return this.$router.push({ name: 'competency-intro', params: { continue: true } })
        }

        return this.$router.push({ name: 'finish', params: { notice: true } })
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.message : e)
      } finally {
        this.isRunning = false
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../styles/scss/competency_qna.scss";
@import "../styles/scss/layout/responsive.scss";

.test--qna__btn {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;

  .tettscore {
    margin-top: 0;
    width: 100%;
  }
}
</style>
