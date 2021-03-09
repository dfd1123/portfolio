<template>
  <div class="main-category-group">
    <i class="in-line-element"></i>
    <div class="in-category-group">
      <!-- 카테고리 불러오기 -->
      <span
        class="in-category"
        :class="{ active : caId === category.ca_id }"
        :ref="'menu'+category.ca_id"
        v-for="category in this.$store.state.categorys"
        :key="category.id"
        @click="MenuSelected(category.ca_id)"
      >
        <router-link :to="'home?ca_id='+category.ca_id" class="in-link">{{category.ca_name}}</router-link>
      </span>
      <!-- 카테고리 불러오기 -->
    </div>
    <div class="indicator" ref="indicator"></div>
  </div>
</template>

<script>
export default {
  name: "main-category-group",
  props: {
    caId: {
      type: Number,
      required: true
    }
  },
  mounted() {
    // console.log(this.$refs["menu" + this.caId][0]);
    this.MenuSelected(this.caId);
  },
  methods: {
    MenuSelected(index) {
      this.MoveIndicatorTo(index);
    },
    MoveIndicatorTo(index) {
      const eachItem = this.$refs["menu" + index][0];
      const indicatorBar = this.$refs.indicator;
      const eachWidth = eachItem.clientWidth;
      const eachLeft = eachItem.offsetLeft;
      indicatorBar.style.width = eachWidth + "px";
      indicatorBar.style.left = eachLeft + "px";

      // 최초 로딩 시에만 트랜지션 비활성화
      setTimeout(() => {
        indicatorBar.style.transition = null;
      });
    }
  }
};
</script>

<style scoped>
.indicator {
  position: absolute;
  display: inline-block;
  height: 11px;
  background-color: rgba(255, 255, 190, 0.23);
  transition: all 0.4s;
  bottom: 23px;
  z-index: -1;
  transform: scaleX(1.1);
}

@media all and (max-width: 991px) {
  .indicator {
    display: none;
  }
}
</style>