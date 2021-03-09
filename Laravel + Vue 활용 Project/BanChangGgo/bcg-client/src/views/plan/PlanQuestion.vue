<template>
  <div id="plnquestion_page" class="page page--default">
    <div class="page_container">
      <theme-bg-02></theme-bg-02>
      <!-- 반창꼬에게 알려주세요 -->
      <section class="planquestion_hd">
        <button type="button" class="page_title_btn btn-left" @click="prevBtnClick">
          <i class="icon_prev"></i>
        </button>
        <span class="sub_tit">반창꼬에게 알려주세요</span>
        <h2 class="tit">Question</h2>
        <span class="step_guide">
          <b>{{ percentStep }}</b>
          /{{this.pages.length}}
        </span>
        <div class="progress_bar">
          <span v-bind:style="{ width : (percentStep / (this.pages.length) * 100) + '%'}"></span>
        </div>
        <button type="button" class="page_title_btn btn-right" @click="save">
          <i class="icon_del_word">저장</i>
        </button>
      </section>
      <!-- end -->
      <!-- 템플릿 영역 -->
      <div class="planquestion_contents">
        <qna-container
          v-for="(item, index) in this.pages[step]"
          :key="`${step}-${index}`"
          :symbol="index === 0"
          :question-number="String(percentStep).padStart(2, '0') + (pages[step].length > 1 ? `-${index + 1}` : '')"
          :card-info="item"
        ></qna-container>
      </div>
      <!-- end -->
    </div>
    <input type="button" value="다음" class="wide_btn btn_only_theme step_btn" @click="nextBtnClick" />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'PlanQuestion',
  data () {
    return {
      step: 0,
      pages: JSON.parse(localStorage.planQuestionData || '[]')
    }
  },
  computed: {
    ...mapGetters(['user', 'plan', 'userBatchOrder']),
    percentStep () {
      return this.step + 1
    }
  },
  watch: {
    step () {
      this.$nextTick(() => {
        const el = document.getElementsByClassName('page_container')[0]
        const elDistanceToTop = window.pageYOffset + el.getBoundingClientRect().top
        el.scrollTop = elDistanceToTop
      })
    }
  },
  async created () {
    if (this.pages.length !== 0) {
      return
    }

    const batch = await this.fetchData()
    this.pages = batch.bt_qna_list
  },
  methods: {
    ...mapActions(['getUser']),
    async fetchData () {
      try {
        const batch = await this.$axios
          .get(`/batches/${this.user.usr_batch.bt_no}`)
          .then(response => response.data)

        return batch
      } catch { }
    },
    prevBtnClick () {
      if (this.percentStep > 1) {
        this.step = this.step - 1
        return
      }

      this.$swal({
        html: '건강사항을 먼저 작성해주시기 바랍니다',
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
    },
    async nextBtnClick () {
      if (this.percentStep < this.pages.length) {
        this.step = this.step + 1
        return
      }

      if (this.percentStep === this.pages.length) {
        try {
          await this.$axios
            .put(`/user_batches/${this.user.usr_batch.ubt_no}`, {
              ubt_qna_list: JSON.stringify(this.pages)
            })
            .then(response => response.data)

          await this.getUser()

          localStorage.removeItem('planQuestionData')

          this.$swal({
            allowOutsideClick: false,
            confirmButtonText: '네, 확인했습니다.',
            html: `${this.userBatchOrder}기 결과 리포트 신청이 완료되었습니다.<br>${this.user.usr_batch.pt_term}일 간 스케줄을 꼼꼼히 기록하시고,<br>${this.userBatchOrder}기 건강리포트를 확인해보세요.`,
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
          }).then(result => {
            if (result.value) {
              this.$router.push('/home')
            }
          })
        } catch (e) {
          console.log(e)
        }
      }
    },
    save () {
      localStorage.planQuestionData = JSON.stringify([...this.pages])

      this.$swal({
        html: '임시저장 되었습니다',
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
#plnquestion_page {
  background-color: #f7f7f7;
  .page_container {
    height: calc(100% - #{$g-wide_btn});
    overflow-y: scroll;
  }
  .step_btn {
    position: fixed;
    bottom: 0;
  }
  .theme_bg_box_02 {
    @include position($t: 0, $l: 0);
    @include zindex("zero");
    height: 310px;
  }
  .planquestion_hd {
    @include zindex("default");
    position: relative;
    color: white;
    text-align: center;
    padding: 18px 2.5rem 0;
    margin-bottom: 58px;
    .sub_tit {
      @include remFont("14px", $weight: 300);
    }
    .tit {
      @include setFont(38px, $weight: bold);
      line-height: 1;
      margin-bottom: 10px;
    }
    .step_guide {
      @include remFont("14px", $weight: 300);
    }
    .step_guide b {
      @include remFont("18px", $weight: bold);
    }
    .progress_bar {
      width: 100%;
      height: 5px;
      border: 1px solid rgba(255, 255, 255, 0.5);
      border-radius: 30px;
      margin: 10px 0;
      position: relative;
    }
    .progress_bar span {
      @include position($t: 0, $l: 0);
      width: 20%;
      height: 100%;
      background-color: white;
      border-radius: 15px;
      transition: all 0.4s;
      transition-timing-function: ease-in-out;
    }
  }
  .planquestion_hd .page_title_btn {
    @include initButton();
    padding: 0;
    width: 60px;
    height: 60px;

    &.btn-left {
      @include position($t: 0, $l: 0);
    }
    &.btn-right {
      @include position($t: 0, $r: 0);
    }
    .icon_prev {
      background-image: iconPrev(ffffff);
    }
    .icon_del_word {
      color: white;
    }
    & > i {
      @include bgStyle(50%, 50%);
      display: inline-block;
      width: 100%;
      height: 100%;
    }
  }
  .planquestion_contents {
    padding: 0 1.5rem 3rem;
  }
}
</style>
