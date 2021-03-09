<template>
  <div>
    <div class="filter_search_input_wrap">
      <div class="dg_write write_birth write_date _first">
        <Datepicker
          v-model="start_date"
          :format="CustomFormatter"
          :disabled-dates="startDateDisableDates.disabledDates"
          :language="lang"
          @closed="SelectedStartDate"
        />
      </div>
      <span class="_date_from-to">~</span>
      <div class="dg_write write_birth write_date _second">
        <Datepicker
          v-model="end_date"
          :format="CustomFormatter"
          :disabled-dates="EndDateDisableDates"
          :language="lang"
          @closed="SelectedEndDate"
        />
      </div>
    </div>
    <button
      class="filter-search-end-btn"
      @click="Submit"
    >
      조회
    </button>
  </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker'
  import { ko } from 'vuejs-datepicker/dist/locale'

  export default {
    components: {
      Datepicker
    },
    data: function () {
      return {
        start_date: this.startDate,
        end_date: this.endDate,
        date_range: this.dateRange,
        /* datepicker setting */
        startDateDisableDates: {
          disabledDates: {

          }
        },
        endDateDisableDates: {
          disabledDates: {
            to: (this.$route.query.startDate) ? new Date(this.$route.query.startDate) : this.$moment().subtract(1, 'w')._d
          }
        },
        lang: ko
      }
    },
    props: {
      startDate: {
        type: String,
        required: true
      },
      endDate: {
        type: String,
        required: true
      },
      dateRange: {
        type: String,
        required: true
      }
    },
    mounted () {
      this.start_date = this.startDate
      this.end_date = this.endDate
    },
    computed: {
      EndDateDisableDates () {
        return this.endDateDisableDates.disabledDates
      }
    },
    watch: {
      startDate () {
        this.start_date = this.startDate
      },
      endDate () {
        this.end_date = this.endDate
      },
      dateRange () {
        this.date_range = this.dateRange
      }
    },
    methods: {
      CustomFormatter (date) {
        return this.$moment(date).format('YYYY-MM-DD')
      },
      SelectedStartDate () {
        this.date_range = ''
        this.endDateDisableDates.disabledDates.to = new Date(this.CustomFormatter(this.start_date))
        this.$emit('relay-start-date', this.CustomFormatter(this.start_date))
        this.$emit('relay-date-range', this.date_range)
      },
      SelectedEndDate () {
        this.date_range = ''
        this.$emit('relay-end-date', this.CustomFormatter(this.end_date))
        this.$emit('relay-date-range', this.date_range)
      },
      Submit () {
        this.$emit('submit')
      }
    }
  }
</script>

<style lang="scss" scoped>
.write_date._first .vdp-datepicker__calendar{
  bottom: 0px;
  left: 0px;
}
.write_date._second .vdp-datepicker__calendar{
  bottom: 0px;
  right: 0px;
}
</style>
