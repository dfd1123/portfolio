<template>
  <div class="dg-filter-search-list">
    <h3 class="in-label">
      색상
    </h3>
    <div class="in-section">
      <input
        type="checkbox"
        id="colorAll"
        v-model="dataColor"
        @change="ColorAll()"
        class="filter-checkbox display_none"
        value=""
      >
      <label
        for="colorAll"
        class="rounded-btn-dark"
      >
        전체
      </label>
      <div
        v-for="(mainColor, index) in MainColors"
        v-show="mainColor !== '기타색상'"
        :key="'color'+index"
        style="display:inline-block;"
      >
        <input
          type="checkbox"
          :id="'color'+index"
          class="filter-checkbox display_none"
          v-model="dataColor"
          @change="ChangeEvent()"
          :value="mainColor"
        >
        <label
          :for="'color'+index"
          class="rounded-btn-dark"
        >
          {{ mainColor }}
        </label>
      </div>
      <div style="display:inline-block;">
        <input
          type="checkbox"
          id="sub_color"
          class="filter-checkbox display_none"
          v-model="dataColor"
          @change="ChangeEvent()"
          value="기타색상"
        >
        <label
          for="sub_color"
          class="rounded-btn-dark"
        >
          기타색상
        </label>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        dataColor: this.color,
        popupOpen: false
      }
    },
    props: {
      color: {
        type: Array,
        default: () => {
          return []
        },
        required: true
      },
      mainColors: {
        type: Array,
        default: () => {
          return []
        }
      },
      subColors: {
        type: Array,
        default: () => {
          return []
        }
      }
    },
    mounted () {
      if (this.color.length === 0) {
        document.getElementById('colorAll').checked = true
      }
    },
    computed: {
      MainColors () {
        let colors = []
        this.mainColors.forEach(mainColor => {
          colors.push(mainColor.color_name)
        })
        colors = colors.concat(this.dataColor)
        return [...new Set(colors)]
      }
    },
    methods: {
      ColorAll () {
        this.dataColor = []
        this.$emit('input', this.dataColor)
        this.$emit('search-submit')
      },
      ChangeEvent () {
        document.getElementById('colorAll').checked = false
        this.dataColor = [...new Set(this.dataColor)]
        this.$emit('input', this.dataColor)
        this.$emit('search-submit')
      },
      PopupOpen () {
        const dataColor = this.dataColor
        this.dataColor = []
        this.dataColor = this.dataColor = [...new Set(dataColor)]
        this.popupOpen = true
      },
      PopupClose () {
        this.popupOpen = false
      },
      PopupSelectColor (dataColor) {
        if (dataColor.length > 0) {
          document.getElementById('colorAll').checked = false
        } else {
          document.getElementById('colorAll').checked = true
        }
        this.dataColor = [...new Set(dataColor)]
        this.$emit('input', this.dataColor)
        this.$emit('search-submit')
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
