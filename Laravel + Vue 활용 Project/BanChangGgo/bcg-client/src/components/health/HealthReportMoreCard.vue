<template>
  <div
    class="hlthreport_more_card"
    :class="{ active : panelInfo.bt_state === 1 && panelInfo.ubt_latest }"
  >
    <div class="in_hd">
      <h3>
        <b>{{ panelInfo.bt_order }}기 리포트</b>
        <template
          v-if="[1,2].includes(panelInfo.bt_state)"
        >{{ ` : ${panelInfo.bt_start} ~ ${panelInfo.bt_end}` }}</template>
        <template v-else-if="panelInfo.bt_state === 0">: 예정</template>
      </h3>
    </div>
    <div class="in_contents">
      <p v-if="panelInfo.bt_state === 0" class="text">
        {{panelInfo.bt_order}}기 리포트는
        <br />예정중입니다.
      </p>
      <template v-else-if="panelInfo.bt_state === 1">
        <input
          v-if="panelInfo.ubt_no === null"
          type="button"
          :value="`${panelInfo.bt_order}기 리포트 신청하기`"
          class="in_applybtn"
          :class="{active: !isAnyApplied}"
          @click="applyButtonClick(panelInfo)"
        />
        <template v-else>
          <p
            v-if="panelInfo.hr_no !== null && ((panelInfo.pt_day || 0) > panelInfo.pt_term)"
            class="text"
            @click="showPreviousReport(panelInfo)"
          >
            건강 리포트
            <br />
            <b class="color_theme-03">{{panelInfo.bt_order}}기</b>를 완료하셨습니다.
          </p>
          <p v-else class="text" @click="showPreviousReport(panelInfo)">
            회원님은 현재 건강 리포트
            <br />
            <b class="color_theme-03">{{panelInfo.bt_order}}기 이용중</b>입니다.
          </p>
        </template>
      </template>
      <p v-else-if="panelInfo.bt_state === 2" class="text">
        {{panelInfo.bt_order}}기 리포트는
        <br />종료되었습니다.
      </p>
    </div>
    <ul class="in_category">
      <li
        v-for="(item, index) in panelInfo.disease_list.map(x => x.dc_cat_name)"
        :key="index"
      >{{ item }}</li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'health-report-more-card',
  props: ['panelInfo', 'isAnyApplied'],
  methods: {
    applyButtonClick (panelInfo) {
      if (!this.isAnyApplied) {
        this.$emit('ApplyButtonClick', panelInfo)
      }
    },
    showPreviousReport (panelInfo) {
      if (panelInfo.hr_no) {
        this.$router.push(`/health/report/${panelInfo.hr_no}`)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.hlthreport_more_card {
  background-color: white;
  border-radius: 11px;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.07);
  text-align: center;
  position: relative;
  overflow: hidden;
  min-height: 11.2rem;
  max-width: 430px;
  margin: 0 auto 15px;
  .in_hd {
    background-color: #d1d1d1;
    color: white;
    padding: 0.5rem 10px;
    b {
      font-weight: bold;
    }
  }
  &.active .in_hd {
    background-color: $theme-03;
  }
  &.active .in_contents .text {
    @include setFont(13px, $weight: 400, $color: #272720);
    padding: 1.8rem 0 1.1rem;
  }
  .in_contents {
    .text {
      padding: 20px 0;
      color: rgba(#272720, 0.3);
    }
    .text b {
      font-weight: bold;
    }
  }
  .in_category {
    @include remFont("18px", $weight: 500, $color: #c9c9c9);
    & > li {
      display: inline-block;
      padding: 0 10px;
      border: {
        right: 1px solid;
      }
      color: inherit;
      line-height: 12px;
    }
    & > li:last-child {
      border-right: 0;
    }
  }
  .in_applybtn {
    @include initButton(4px);
    @include setFont(13px, $weight: 400);
    color: #a3a3a3;
    border: 1px solid #bcbcbc;
    padding: 5px 15px;
    margin: 25px 0 20px;

    &.active {
      @include setFont(13px, $weight: 500, $color: #272720);
      border: 1px solid $theme-03;
    }
  }
}
</style>
