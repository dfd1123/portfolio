<template>
  <div class="_popup_wrapper _color_choice_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          기타 색상 선택<button
            type="button"
            class="_close_btn"
            @click="PopupClose"
          >
          </button>
        </h4>
      </div>
      <div class="_popup_content">
        <div class="dg-filter-search-list">
          <h3 class="#">
            기타 색상 목록<small> 스토어에서 직접 추가한 색상 목록입니다.</small>
          </h3>
          <div class="_color_palette_wrap over-y_scroll">
            <!-- 등록된 모든 색상이 가나다순으로 정렬 희망 -->
            <div class="_color_palette">
              <div
                v-for="(subColor, index) in subColors"
                :key="'subColor'+index"
                style="display:inline-block;"
              >
                <input
                  type="checkbox"
                  :id="'subColor'+index"
                  class="filter-checkbox display_none"
                  v-model="dataColor"
                  :value="subColor.color_name"
                >
                <label
                  :for="'subColor'+index"
                  class="rounded-btn-dark"
                >
                  {{ subColor.color_name }}
                </label>
              </div>
            </div>
          </div>
        </div>
        <div class="dg-dubble_btn_wrap clear_both">
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn"
            @click="PopupClose"
          >
            닫기
          </button>
          <button
            type="button"
            class="dg-btn_gra dg-dubble_btn"
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
</style>
