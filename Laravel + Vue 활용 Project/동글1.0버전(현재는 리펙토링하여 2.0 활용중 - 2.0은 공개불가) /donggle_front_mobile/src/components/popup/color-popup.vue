<template>
  <div class="_popup_wrapper _color_choice_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          기타 색상 목록
          <small>스토어에서 직접 추가한 색상 목록입니다.</small>
          <button
            type="button"
            class="_close_btn"
            @click="PopupClose"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <div class="dg-filter-search-list">
          <div class="_color_palette_wrap">
            <!-- 등록된 모든 색상이 가나다순으로 정렬 희망 -->
            <div class="_color_palette">
              <div
                v-for="(subColor, index) in subColors"
                :key="'subColor'+index"
                class="filiter-choice-item-wrap"
              >
                <input
                  type="checkbox"
                  :id="'subColor'+index"
                  class="_input_choice display_none"
                  v-model="dataColor"
                  :checked="dataColor"
                  :value="subColor.color_name"
                >
                <label
                  :for="'subColor'+index"
                  class="rounded-btn-dark _input_chck"
                >{{ subColor.color_name }}</label>
              </div>
            </div>
          </div>
          <button
            type="button"
            class="dg-btn_gra"
            @click="Submit"
          >
            선택완료
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        dataColor: []
      }
    },
    props: {
      subColors: {
        type: Array,
        default: () => {
          return []
        }
      },
      colors: {
        type: Array,
        default: () => {
          return []
        }
      }
    },
    watch: {
      colors () {
        this.dataColor = this.colors
      }
    },
    methods: {
      PopupClose () {
        this.dataColor = []
        this.$emit('popup-close')
      },
      Submit () {
        this.$emit('select-colors', this.dataColor)
        this.$emit('popup-close')
      }
    }
  }
</script>

<style lang="scss" scoped>
  .l-con-title-group {
    padding: 0 0;
  }
  .dg-filter-search-list .dg-btn_gra {
    position: fixed;
    bottom: 0px;
    left: 0px;
    width: 100%;
    height: 50px;
    // z-index: 100;
  }
</style>
