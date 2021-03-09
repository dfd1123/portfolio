<template>
  <div v-show="isLoaded">
    <div id="cp--hd">
      <a href="#" class="off"></a>
      <h1 class="intro__title">역량평가</h1>
    </div>
    <section id="grobalcompetency" class="_competency">
      <div class="onlybgcolorwh"></div>
      <h2 class="competency--title">
        <p class="title__number">{{String(no).padStart(2, '0')}}</p>
        <p class="title__en" v-html="titleTop"></p>
        <p class="title__ko" v-html="title"></p>
      </h2>
      <p class="competency--desc" v-html="desc"></p>
    </section>
    <div id="start__btnwrap">
      <a href="#" @click.prevent="buttonClick">{{buttonText}}</a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'competency-intro',
  data () {
    return {
      isLoaded: false,
      no: '',
      titleTop: '',
      title: '',
      desc: '',
      buttonText: '역량평가 시작하기',
      qnas: [],
      countQuestions: 0,
      countAnswered: 0
    }
  },
  async created () {
    await this.fetchData()
    this.isLoaded = true

    if (this.id && this.user.cpt_order > 0 && !this.$route.params.continue) {
      alert('이전에 중단하신 부분부터 이어서 진행됩니다')
    }
  },
  methods: {
    async fetchData () {
      await this.userDetail()

      const res = await this.$axios
        .get('/cp_test_templates', {
          params: {
            cpt_order: this.user.cpt_order + 1
          }
        })
        .then(res => res.data)
        .then(this.first)

      if (!res) {
        return this.$router.replace('/')
      }

      this.id = res.cpt_id
      this.no = res.cpt_order
      this.title = res.cpt_title
      this.titleTop = res.cpt_title_en
      this.desc = res.cpt_desc
      this.qnas = res.cpt_question
      this.countQuestions = res.count_questions
      this.countAnswered = res.count_answered
    },
    buttonClick () {
      this.$router.replace({
        name: 'competency-qna',
        params: {
          id: this.id,
          no: this.no,
          title: this.title.replace(/<br>/g, ' '),
          titleTop: this.titleTop.replace(/<br>/g, ' '),
          qnas: this.qnas,
          countQuestions: this.countQuestions,
          countAnswered: this.countAnswered
        }
      })
    }
  }
}
</script>s

<style lang="scss" scoped>
@import "../styles/scss/competency_intro.scss";
@import "../styles/scss/layout/responsive.scss";
</style>
