<template>
  <header id="dg-reg-hd">
    <h1>
      {{ pageName }} <a
        href="#"
        class="_back_btn"
        @click.prevent="$router.go(-1)"
      >뒤로가기</a>
      <button
        type="button"
        class="skip_btn"
        v-if="styleChoice"
        @click="SkipStyle"
      >
        SKIP
      </button>
    </h1>
    <div class="dg-reg-hd-step_wrap">
      <!-- #step3에서만 display:block -->
      <div
        v-if="styleChoice"
        class="dg-reg-style_choice_box"
      >
        <button
          type="button"
          class="clear_Btn"
          @click="$emit('all-cancel')"
        >
          해제
        </button>
        <div class="style_choice_scroll">
          <div
            v-for="(uniqHashTag, index) in uniqHashTags"
            :key="index"
            class="dg-reg-style_choice rounded-btn-outline"
          >
            #{{ uniqHashTag }}
            <button
              class="_close_btn"
              @click="$emit('one-cancel', uniqHashTag)"
            ></button>
          </div>
        </div>
      </div>
      <!-- #step3에서만 display:block E -->
      <div class="dg-reg-hd-step">
        <span class="dg-reg-hd-step_text">STEP</span>
        <!-- ☆ step 진행도 표시
                  #step_end 에서는 안보임
                  #step1,2,3에서 작성후 버튼을 누르면 다음번 dg-btn_line에 add class active -->
        <div class="dg-reg-hd-step_circle">
          <div :class="['dg-btn_line dg-reg-hd-step_box1', {'active':step === 1}]">
            1
          </div>
          <div :class="['dg-btn_line dg-reg-hd-step_box2', {'active':step === 2}]">
            2
          </div>
          <div :class="['dg-btn_line dg-reg-hd-step_box3', {'active':step === 3}]">
            3
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
  export default {
    props: {
      pageName: {
        type: String,
        required: true
      },
      styleChoice: {
        type: Boolean,
        default: false
      },
      step: {
        type: Number,
        default: 1
      },
      register: {
        type: Boolean,
        default: false
      },
      uniqHashTags: {
        type: Array,
        default: () => {
          return []
        }
      }
    },
    methods: {
      async SkipStyle () {
        await this.SuccessAlert('회원가입을 성공적으로 완료 하였습니다!\n로그인 후 이용해주세요.')
        this.$router.push('/')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
