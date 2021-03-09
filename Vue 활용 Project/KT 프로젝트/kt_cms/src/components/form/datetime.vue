<template>
  <div class="row-mulity-input">
    <button
      type="button"
      class="btn-datepicker"
      :style="{display: hideButton ? 'none' : ''}"
    ></button>
    <input
      type="text"
      name=""
      :id="`date_${inputData.name}_${status}`"
      value=""
      class="al-c date_field"
      style="width:149px;"
    >
    <input
      type="text"
      name=""
      :id="`time_${inputData.name}_${status}`"
      v-model.trim="time"
      class="al-c date_field"
      style="width:100px;"
      placeholder="HH:mm"
      @change="emitDatetime"
    >
  </div>
</template>

<script>
  export default {
    props: {
      inputData: {
        type: Object,
        default () {
          return {}
        },
        required: true
      },
      status: {
        type: String,
        required: true
      },
      readonly: {
        type: Boolean,
        default: false
      },
      minDate: {
        type: String,
        default: null
      },
      maxDate: {
        type: String,
        default: null
      },
      hideButton: {
        type: Boolean,
        default: false
      }
    },
    data () {
      return {
        date: '',
        time: ''
      }
    },
    mounted () {
      window.$(this.id).datepicker({
        showOn: 'both',
        buttonImage: '/images/datepicker/btn_datepicker.png',
        buttonImageOnly: false,
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년',
        maxDate: '0',
        onSelect: (dateText, inst) => {
          if (this.$moment(dateText, 'YYYY-MM-DD', true).isValid()) {
            this.date = dateText
            if (!this.time) {
              this.time = '00:00'
            }
            if (!this.$moment(this.time, 'HH:mm', true).isValid()) {
              this.time = '00:00'
            }

            this.emitDatetime()
          }
        },
        onClose: () => {
          if (!this.$moment(window.$(this.id).val(), 'YYYY-MM-DD', true).isValid()) {
            window.$(this.id).val('')
          }
        }
      })
    },
    beforeDestroy () {
      window.$(this.id).datepicker('destroy')
    },
    watch: {
      'inputData.value': {
        handler (newValue, oldValue) {
          if (this.inputData.value) {
            if (this.$moment(this.inputData.value).isValid()) {
              const [date, time] = this.$moment(this.inputData.value).format('YYYY-MM-DD HH:mm').split(' ')
              this.date = date
              this.time = time
              this.emitDatetime()
            }
          } else {
            this.date = ''
            this.time = ''
            window.$(this.id).datepicker('option', 'minDate', null)
            window.$(this.id).datepicker('option', 'maxDate', null)
            this.emitDatetime()
          }
        }
      },
      date () {
        this.$nextTick(() => {
          window.$(this.id).val(this.date).trigger('change')
        })
      },
      time (newValue, oldValue) {
        const value = !newValue.endsWith(':') ? newValue : newValue.replace(/:/g, '') + ':'

        if (value.length > 5) {
          this.time = oldValue
          return
        }

        if (this.$moment(value, 'HH:mm'.substring(0, value.length), true).isValid()) {
          this.time = (value.length === 2 && !oldValue.endsWith(':')) ? `${value}:` : value
        } else {
          this.time = ''
        }
      },
      minDate () {
        window.$(this.id).datepicker('option', 'minDate', this.minDate)
      },
      maxDate () {
        window.$(this.id).datepicker('option', 'maxDate', this.maxDate)
      }
    },
    computed: {
      id () {
        return `#date_${this.inputData.name}_${this.status}`
      },
      datetime () {
        if (!this.date) {
          return null
        }
        return this.date + ' ' + (this.time || '00:00')
      }
    },
    methods: {
      emitDatetime () {
        this.$emit('input', this.datetime)
        if (this.inputData.onSelect) {
          this.inputData.onSelect(this.datetime)
        }
      }
    }
  }
</script>

<style scoped>
  .input_wrap {
    display: inline-block;
  }

  .row-mulity-input {
    white-space: nowrap;
  }

  ::v-deep button[type="button"].ui-datepicker-trigger {
    display: none;
  }

  .date_field {
    position: relative;
    z-index: 99;
  }
</style>
