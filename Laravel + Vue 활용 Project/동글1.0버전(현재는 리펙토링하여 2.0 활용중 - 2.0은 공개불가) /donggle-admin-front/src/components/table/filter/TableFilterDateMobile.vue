<template>
  <section class="filter-input_date">
    <h3 class="filter_section_title small_title">{{title}}</h3>
    <div class="filter_input_wrap clearfix">
      <template v-if="type === 'date'">
        <div class="date_start">
          <date-pick
            v-model="startDate"
            :class="[startDate ? 'hide-cal' : 'hide-btn']"
            :inputAttributes="{class: 'form-input-date'}"
            nextMonthCaption="다음 달"
            prevMonthCaption="이전 달"
            setTimeCaption="시간:"
            :weekdays="['월', '화', '수', '목', '금', '토', '일']"
            :months="['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월']"
            style="min-width: 100%;"
          ></date-pick>
        </div>
        <span v-if="isRange" class="swung-dash">~</span>
        <div v-if="isRange" class="date_end">
          <date-pick
            v-model="endDate"
            :class="[endDate ? 'hide-cal' : 'hide-btn']"
            :inputAttributes="{class: 'form-input-date'}"
            nextMonthCaption="다음 달"
            prevMonthCaption="이전 달"
            setTimeCaption="시간:"
            :weekdays="weekdayLabels"
            :months="monthLabels"
            style="min-width: 100%;"
          ></date-pick>
        </div>
      </template>

      <template v-else-if="type === 'month'">
        <div class="date_start">
          <v-month-picker-custom v-model="startMonthDate" :months="monthLabels">
            <input type="text" class="form-input-date" readonly="readonly" :value="start" />
          </v-month-picker-custom>
        </div>
        <span v-if="isRange" class="swung-dash">~</span>
        <div class="date_end from-right">
          <v-month-picker-custom v-if="isRange" v-model="endMonthDate" :months="monthLabels">
            <input type="text" class="form-input-date" readonly="readonly" :value="end" />
          </v-month-picker-custom>
        </div>
      </template>

      <template v-else-if="type === 'year'">
        <div class="date_start">
          <select class="form-input-date" @change="startYearChange" :value="null">
            <option v-for="year in yearRanges" :key="year" :value="year">{{year}}</option>
          </select>
        </div>
        <span v-if="isRange" class="swung-dash">~</span>
        <div class="date_end">
          <select class="form-input-date" @change="endYearChange" :value="null">
            <option v-for="year in yearRanges" :key="year" :value="year">{{year}}</option>
          </select>
        </div>
      </template>
    </div>
  </section>
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
  mounted () {

  },
  created () {
    this.weekdayLabels = ['월', '화', '수', '목', '금', '토', '일']
    this.monthLabels = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월']

    const currentYear = Number(this.$moment().format('YYYY'))
    this.yearRanges = this._range(currentYear + 60, currentYear).concat(this._range(currentYear, currentYear - 60, -1))
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
    width: 100%;
  }

  .vue-month-selector-slot-container {
    width: 100%;
  }

  .from-right .vue-month-selector-input-component-container {
    right: 0
  }
}
</style>
