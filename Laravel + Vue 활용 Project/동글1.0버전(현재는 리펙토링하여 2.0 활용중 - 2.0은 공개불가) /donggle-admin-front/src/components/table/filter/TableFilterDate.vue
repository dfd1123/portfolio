<template>
  <div class="search-option-period">
    <span class="in-tit">{{title}}</span>
    <div class="in-period">
      <template v-if="type === 'date'">
        <date-pick
          v-model="startDate"
          :class="[startDate ? 'hide-cal' : 'hide-btn']"
          :inputAttributes="{class: 'form-input-date'}"
          nextMonthCaption="다음 달"
          prevMonthCaption="이전 달"
          setTimeCaption="시간:"
          :weekdays="weekdayLabels"
          :months="monthLabels"
        ></date-pick>
        <span v-if="isRange">~</span>
        <date-pick
          v-if="isRange"
          v-model="endDate"
          :class="[endDate ? 'hide-cal' : 'hide-btn']"
          :inputAttributes="{class: 'form-input-date'}"
          nextMonthCaption="다음 달"
          prevMonthCaption="이전 달"
          setTimeCaption="시간:"
          :weekdays="weekdayLabels"
          :months="monthLabels"
        ></date-pick>
      </template>

      <template v-else-if="type === 'month'">
        <v-month-picker-custom v-model="startMonthDate" :months="monthLabels">
          <input type="text" class="form-input-date" readonly="readonly" :value="start" />
        </v-month-picker-custom>
        <span v-if="isRange">~</span>
        <v-month-picker-custom v-if="isRange" v-model="endMonthDate" :months="monthLabels">
          <input type="text" class="form-input-date" readonly="readonly" :value="end" />
        </v-month-picker-custom>
      </template>

      <template v-else-if="type === 'year'">
        <select class="form-input-date" @change="startYearChange" :value="''">
          <option v-for="year in yearRanges" :key="year" :value="year">{{year}}</option>
        </select>
        <span v-if="isRange">~</span>
        <select class="form-input-date" @change="endYearChange" :value="''">
          <option v-for="year in yearRanges" :key="year" :value="year">{{year}}</option>
        </select>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TableFilterDate',
  props: {
    isRange: {
      type: Boolean,
      default: true
    },
    title: {
      type: String,
      default: ''
    },
    start: {
      type: String,
      default: ''
    },
    end: {
      type: String,
      default: ''
    },
    type: {
      type: String,
      default: 'date'
    }
  },
  created () {
    this.weekdayLabels = ['월', '화', '수', '목', '금', '토', '일']
    this.monthLabels = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월']

    const currentYear = Number(this.$moment().format('YYYY'))
    this.yearRanges = this._range(currentYear + 60, currentYear).concat(this._range(currentYear, currentYear - 60, -1))
  },
  data () {
    return {
      format: 'YYYY-MM-DD',
      monthFormat: 'YYYY-MM',
      startDate: this.start || '',
      endDate: this.end || '',
      startMonthDate: {
        month: null,
        year: null
      },
      endMonthDate: {
        month: null,
        year: null
      }
    }
  },
  watch: {
    start () {
      this.startDate = this.start
    },
    end () {
      if (!this.isRange) {
        this.endDate = ''
      }

      this.endDate = this.end
    },
    startDate (newVal, oldVal) {
      if (newVal !== this.start) {
        this.$emit('update:start', newVal)
        this.$emit('change', newVal)
      }
    },
    endDate (newVal, oldVal) {
      if (newVal !== this.end) {
        this.$emit('update:end', newVal)
        this.$emit('change', newVal)
      }
    },
    isRange () {
      if (!this.isRange) {
        this.$emit('update:end', '')
        this.$emit('change', '')
      }
    },
    type () {
      this.startDate = ''
      this.endDate = ''
      this.startMonthDate.month = ''
      this.endMonthDate.month = ''
      this.startMonthDate.year = Number(this.$moment().format('YYYY'))
      this.endMonthDate.year = Number(this.$moment().format('YYYY'))
      this.$emit('update:start', '')
      this.$emit('update:end', '')
    },
    startMonthDate: {
      deep: true,

      handler () {
        if (this.startMonthDate.year === '' || this.startMonthDate.month === '') {
          return
        }

        const selected = this.startMonthDate.year + '-' + String(this.startMonthDate.month + 1).padStart(2, '0')
        this.$emit('update:start', selected)
        this.$emit('change', selected)
      }
    },
    endMonthDate: {
      deep: true,

      handler () {
        if (this.endMonthDate.year === '' || this.endMonthDate.month === '') {
          return
        }

        const selected = this.endMonthDate.year + '-' + String(this.endMonthDate.month + 1).padStart(2, '0')
        this.$emit('update:end', selected)
        this.$emit('change', selected)
      }
    }
  },
  methods: {
    startYearChange (e) {
      this.$emit('update:start', e.target.value)
      this.$emit('change', e.target.value)
    },
    endYearChange (e) {
      this.$emit('update:end', e.target.value)
      this.$emit('change', e.target.value)
    }
  }
}
</script>

<style lang="scss" scoped>
::v-deep {
  .hide-btn .vdpClearInput {
    display: none;
  }

  .hide-cal .form-input-date {
    background: white;
  }

  .vue-month-selector-input-container {
    display: inline-block;
  }
}
</style>
