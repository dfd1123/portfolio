<template>
  <div
    class="plan_list"
    :class="{ checked_active : checked, point_color : planToDo.type == 1, custom_active : planToDo.kind === null }"
  >
    <!-- 왼쪽 상태아이콘 -->
    <figure class="in_icon">
      <img v-if="planToDo.kind" :src="kinds[Number(planToDo.kind) - 1]" alt="아이콘 이미지" />
    </figure>
    <!-- end -->
    <!-- 텍스트 -->
    <p class="in_text" @click="$emit('planListClick')">
      {{ planToDo.title }}
      <img
        :src="iconMemoColor"
        alt="메모 아이콘"
        class="memo"
        v-if="planToDo.memo && !checked"
      />
      <img :src="iconMemoDark" alt="메모 아이콘" class="memo" v-if="planToDo.memo && checked" />
    </p>
    <!-- end -->
    <!-- 시간 -->
    <span class="in_time">{{ planToDo.time }}</span>
    <!-- end -->
    <!-- checkbox -->
    <label class="in_checkbox checkbox_01">
      <input type="checkbox" class="none" @change="$emit('planCheckClick')" :checked="checked" />
      <checkbox-svg-type01></checkbox-svg-type01>
    </label>
    <!-- end -->
  </div>
</template>

<script>
export default {
  name: 'health-plan-list',
  props: ['planToDo'],

  data () {
    return {
      kinds: [
        '/assets/images/icons/icon_sikgi.svg',
        '/assets/images/icons/icon_drug.svg'
      ],
      iconMemoColor: '/assets/images/icons/icon_memo.svg',
      iconMemoDark: '/assets/images/icons/icon_memo_dark.svg'
    }
  },
  computed: {
    checked () { return this.planToDo.result === 1 }
  }
}
</script>

<style lang="scss" scoped>
.plan_list {
  width: 100%;
  min-height: 3.3rem;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.07);
  border-radius: 2rem;
  position: relative;
  background-color: white;
  margin: 0.95rem 0;
  padding: 0 6rem 0 3.6rem;
  display: flex;
  align-items: center;
  transition: all 0.8s;
  text-align: left;
  .in_icon {
    @include position($t: 50%, $l: 8px);
    @include translate($x: 0, $y: -50%);
    $circle-area: 2.5rem;
    width: $circle-area;
    height: $circle-area;
    background-color: #fff7e8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    .sikgi {
      position: relative;
      top: 14px;
    }
  }
  .in_text {
    width: 100%;
    font-weight: 500;
    .memo {
      position: relative;
      margin: 0 5px;
      top: 2px;
    }
  }
  .in_time {
    @include position($t: 50%, $r: 55px);
    @include translate($x: 0, $y: -50%);
    @include setFont(12px, $color: $gray-03);
  }
  .in_checkbox {
    @include position($r: 10px, $t: 50%);
    @include translate($x: 0, $y: -50%);
  }
  &:active {
    background-color: #fff7e8;
  }
  &:before {
    @include position($t: 50%, $l: -5px);
    @include zindex("default");
    content: "";
    width: 0;
    height: 1px;
    transition: all 0.3s;
    transition-timing-function: ease-in-out;
    display: inline-block;
    background-color: #909090;
  }
}
.plan_list.custom_active {
  padding-left: 1.4rem;
  .in_icon {
    display: none;
  }
}
.plan_list.point_color {
  color: $theme-02;
}
.plan_list.checked_active {
  background-color: #d6d6d6;
  color: white;
  transition: all 0.5s;
  // &:before {
  //   width: calc(100% + 10px);
  //   transition: all 0.5s;
  //   transition-timing-function: ease-in-out;
  // }
  .in_time {
    color: inherit;
  }
}
</style>
