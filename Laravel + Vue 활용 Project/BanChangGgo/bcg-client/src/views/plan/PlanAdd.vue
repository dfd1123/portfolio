<template>
  <div id="plnadd_page" class="page page--hd_default">
    <header-component
      hd-title="플랜추가"
      :priv-button="false"
      :close-button="true"
      @closeButtonClick="closeButtonClick"
    />
    <div class="page_container">
      <transition appear name="slide-fade">
        <div class="contents">
          <fieldset class="page_field">
            <legend class="visually-hidden">플랜추가</legend>
            <div class="form-stretch">
              <!-- 플랜 타이틀 -->
              <div class="form_item type_02">
                <label for="#" class="form_label">플랜 타이틀</label>
                <div class="form_input_group">
                  <input
                    type="text"
                    class="form_input"
                    placeholder="플랜명을 입력하세요."
                    v-model="planTitle"
                  />
                </div>
              </div>
              <!-- end -->
              <!-- 시간 설정 -->
              <div class="form_item type_02">
                <label for="#" class="form_label">시간 설정</label>
                <div class="form_set_time">
                  <el-time-picker
                    v-model="planTime"
                    popper-class="form_section__timepicker"
                    :editable="false"
                    :format="'HH:mm'"
                    value-format="HH:mm"
                    placeholder="시간을 선택해주세요"
                  ></el-time-picker>
                </div>
              </div>
              <!-- end -->
              <!-- 알람 설정 -->
              <div class="form_item type_02 type_alarm">
                <label for="#" class="form_label">알람 설정</label>
                <div class="form_set_alarm" :class="{ activeon : setAlarm }">
                  <button type="button" class="in_option in_option--on" @click="setAlarm = true">
                    <b>on</b>
                  </button>
                  <button type="button" class="in_option in_option--off" @click="setAlarm = false">
                    <b>off</b>
                  </button>
                </div>
              </div>
              <!-- end -->
              <!-- 플랜 메모 -->
              <div class="form_item type_02">
                <label for="#" class="form_label">메모</label>
                <div class="form_input_group">
                  <textarea name id class="form_textarea" v-model="planMemo"></textarea>
                </div>
              </div>
              <!-- end -->
              <input
                :class="{ active: planTitle && planTime }"
                type="button"
                value="등록하기"
                class="wide_btn btn_clear_to_theme step_btn"
                @click="planAddButtonClick"
              />
            </div>
          </fieldset>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'PlanAdd',
  data () {
    return {
      planTitle: '',
      planTime: null,
      planMemo: '',
      setAlarm: false
    }
  },
  methods: {
    ...mapActions(['addPlanItem']),
    closeButtonClick () {
      this.$router.push('/home')
    },
    async planAddButtonClick () {
      if (!this.planTitle) {
        this.$swal({
          text: '플랜 타이틀을 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1500
        })

        return
      }

      if (!this.planTime) {
        this.$swal({
          text: '시간을 입력하세요.',
          type: 'warning',
          showConfirmButton: false,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup--btnfalse',
            content: 'bcg-swal__content',
            icon: 'bcg-swal__icon--btnfalse'
          },
          timer: 1500
        })

        return
      }

      try {
        await this.addPlanItem({
          title: this.planTitle,
          time: this.planTime,
          memo: this.planMemo,
          push: this.setAlarm
        })

        await this.$swal({
          text: '플랜을 추가했습니다',
          type: 'success',
          showCancelButton: false,
          confirmButtonText: '확인'
        }).then((result) => {
          this.$router.push('/home')
        })
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#plnadd_page {
  .step_btn {
    margin-top: auto;
    min-height: 53px;
  }

  .form-stretch {
    display: flex;
    flex-direction: column;
    min-height: 100%;
    padding-bottom: 2rem;
  }

  .page_container {
    overflow: hidden;
  }

  .type_alarm {
    position: relative;
    border-bottom: 1px solid #272720;
    margin-bottom: 10px;
    padding: 5px 0 15px;
  }

  .form_set_alarm {
    @include position($t: 0, $r: 0);
  }

  .form_set_time {
    position: relative;
    border-bottom: 1px solid #272720;
    margin-bottom: 10px;
    padding: 5px 0 15px;

    .el-date-editor--time {
      width: 100%;
    }
  }
}
</style>
