<template>
  <div id="plninfo_page" class="page page--hd_default">
    <header-component
      hd-title="플랜정보"
      hd-modify="삭제"
      :modify-button="true"
      @modifyButtonClick="modifyButtonClick"
      @privButtonClick="privButtonClick"
    />
    <div class="page_container" v-if="planInfo">
      <transition appear name="slide-fade">
        <div class="contents">
          <fieldset class="page_field">
            <legend class="visually-hidden">플랜정보</legend>
            <!-- 플랜 타이틀 -->
            <div class="form_item type_02">
              <label for="#" class="form_label">플랜 타이틀</label>
              <div class="form_input_group">
                <input type="text" class="form_input" v-if="isEditMode" v-model="planInfo.title" />
                <p class="form_input" v-else>{{ planInfo.title }}</p>
              </div>
            </div>
            <!-- end -->
            <!-- 플랜 시간 설정 -->
            <div class="form_item type_02">
              <label for="#" class="form_label">시간 설정</label>
              <div class="form_set_time">
                <el-time-picker
                  v-model="planInfo.time"
                  popper-class="form_section__timepicker"
                  :editable="false"
                  :disabled="!isEditMode"
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
              <div
                class="form_set_alarm"
                :class="{ activeon : planInfo.push === 1 }"
                v-if="isEditMode"
              >
                <button type="button" class="in_option in_option--on" @click="planInfo.push = 1">
                  <b>on</b>
                </button>
                <button type="button" class="in_option in_option--off" @click="planInfo.push = 0">
                  <b>off</b>
                </button>
              </div>
              <div class="form_set_alarm" :class="{ activeon : planInfo.push === 1 }" v-else>
                <button type="button" class="in_option in_option--on">
                  <b>on</b>
                </button>
                <button type="button" class="in_option in_option--off">
                  <b>off</b>
                </button>
              </div>
            </div>
            <!-- end -->
            <!-- 플랜 메모 -->
            <div class="form_item type_02">
              <label for="#" class="form_label">메모</label>
              <div class="form_input_group">
                <textarea name id class="form_textarea" v-if="isEditMode" v-model="planInfo.memo"></textarea>
                <p class="form_textarea" v-else>{{ planInfo.memo }}</p>
              </div>
            </div>
            <!-- end -->
            <input
              v-if="isEditMode"
              type="button"
              value="수정하기"
              class="wide_btn btn_clear_to_theme step_btn active"
              @click="editButtonClick"
            />
          </fieldset>
        </div>
      </transition>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
import range from 'lodash/range'

export default {
  name: 'PlanInfo',
  data () {
    return {
      id: this.$route.params.id,
      planInfo: {},
      planTImeOptions: range(0, 25, 1),
      isEditMode: false
    }
  },
  async created () {
    await this.fetchData()
  },
  computed: {
    ...mapGetters([
      'plan',
      'planList'
    ])
  },
  methods: {
    ...mapActions(['getPlan', 'setPlanItem', 'removePlanItem']),
    async fetchData () {
      try {
        if (this.planList.length === 0) {
          await this.getPlan()
        }

        this.planInfo = this.planList.find((x) => x.id === this.id)
        if (!this.planInfo) {
          return this.$router.go(-1)
        }

        if (!Number(this.id)) {
          this.isEditMode = true
        }
      } catch (e) {
        console.log(e)
      }
    },
    privButtonClick () {
      this.$router.push('/')
    },
    async modifyButtonClick () {
      const value = await this.$swal({
        text: '해당 플랜을 삭제하시겠습니까? (모든 플랜 삭제시 쌤이 등록한 기본 스케줄이 자동으로 등록됩니다)',
        showCancelButton: true,
        cancelButtonText: '아니요',
        confirmButtonText: '네, 삭제합니다.'
      }).then(result => result.value)

      if (!value) {
        return
      }

      try {
        await this.removePlanItem(this.planInfo)

        this.$swal({
          text: '완료되었습니다',
          type: 'success',
          showCancelButton: false,
          confirmButtonText: '확인'
        }).then(result => this.$router.push('/home'))
      } catch (e) {
        console.log(e)
      }
    },
    async editButtonClick () {
      const value = await this.$swal({
        text: '해당 플랜을 수정하시겠습니까?',
        showCancelButton: true,
        cancelButtonText: '아니요',
        confirmButtonText: '네, 수정합니다.'
      }).then(result => result.value)

      if (!value) {
        return
      }

      try {
        await this.setPlanItem(this.planInfo)

        await this.$swal({
          text: '플랜을 수정했습니다',
          type: 'success',
          showCancelButton: false,
          confirmButtonText: '확인'
        })
      } catch (e) {
        console.log(e)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
#plninfo_page {
  .step_btn {
    @include btButton;
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
