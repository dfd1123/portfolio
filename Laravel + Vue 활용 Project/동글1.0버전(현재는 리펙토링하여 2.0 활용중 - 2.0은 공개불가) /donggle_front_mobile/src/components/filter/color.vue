<template>
  <div class="in-section _color_wrap">
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="colorAll"
        class="_input_choice display_none"
        value=""
        v-model="dataColor"
        :checked="dataColor"
        @change="ColorAll()"
      >
      <label
        for="colorAll"
        class="rounded-btn-dark _input_chck"
      >전체</label>
    </div>
    <div
      v-for="(mainColor, index) in MainColors"
      v-show="mainColor !== '기타색상'"
      :key="'color' + index"
      class="filiter-choice-item-wrap"
    >
      <input
        type="checkbox"
        :id="'color' + index"
        class="_input_choice display_none"
        v-model="dataColor"
        @change="ChangeEvent()"
        :value="mainColor"
      >
      <label
        :for="'color' + index"
        class="rounded-btn-dark _input_chck"
      >{{
        mainColor
      }}</label>
    </div>
    <div class="filiter-choice-item-wrap">
      <input
        type="checkbox"
        id="sub_color"
        class="_input_choice display_none"
        value="기타색상"
        v-model="dataColor"
        @change="ChangeEvent()"
      >
      <label
        for="sub_color"
        class="rounded-btn-dark _input_chck"
      >기타색상</label>
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
    watch: {
      '$route.query.colorpop' () {
        if (this.$route.query.colorpop === 1) {
          this.popupOpen = true
        } else {
          this.popupOpen = false
        }
      },
      color () {
        this.dataColor = this.color
        if (this.dataColor.length === 0) {
          document.getElementById('colorAll').checked = true
        }
      }
    },
    methods: {
      ColorAll () {
        this.dataColor = []
        this.$emit('input', this.dataColor)
      },
      ChangeEvent () {
        document.getElementById('colorAll').checked = false
        this.dataColor = [...new Set(this.dataColor)]
        this.$emit('input', this.dataColor)
      },
      PopupOpen () {
        const dataColor = this.dataColor
        this.dataColor = []
        this.dataColor = this.dataColor = [...new Set(dataColor)]
        const query = {
          filterpop: 1,
          colorpop: 1
        }

        this.$router.push({ name: this.$router.name, query: query })
      },
      PopupClose () {
        const query = {
          filterpop: 1
        }

        this.$router.replace({ name: this.$router.name, query: query })
      },
      PopupSelectColor (dataColor) {
        if (dataColor.length > 0) {
          document.getElementById('colorAll').checked = false
        } else {
          document.getElementById('colorAll').checked = true
        }
        this.dataColor = [...new Set(dataColor)]
        this.$emit('input', this.dataColor)
      }
    }
  }
</script>

<style lang="scss" scoped></style>
