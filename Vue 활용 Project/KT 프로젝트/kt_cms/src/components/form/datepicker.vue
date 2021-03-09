<template>
  <div class="row-mulity-input">
    <button
      type="button"
      class="btn-datepicker"
    ></button>
    <div class="date-input-wrap">
      <div class="cell">
        <p class="date-input">
          <Input
            type="text"
            :name="'from_' + id"
            :id="'from_' + id"
            class="datepicker hasDatepicker"
            :style="'width:' + width"
            :readonly="readonly"
            :dis-abled="disAbled"
            v-model="from_value"
          />
        </p>
      </div>
      <div
        v-if="range"
        class="cell"
      >
        <p class="date-input">
          <Input
            type="text"
            :name="'to_' + id"
            :id="'to_' + id"
            class="datepicker hasDatepicker"
            :style="'width:' + width"
            :readonly="readonly"
            :dis-abled="disAbled"
            v-model="to_value"
          />
        </p>
      </div>
    </div>
    <RadioBox
      :id="'dateRadio'+id"
      radiobox-label="3개월"
      radio-value="3"
      v-model="dateRadio"
    />
    <RadioBox
      :id="'dateRadio'+id"
      radiobox-label="6개월"
      radio-value="6"
      v-model="dateRadio"
    />
    <RadioBox
      :id="'dateRadio'+id"
      radiobox-label="1년"
      radio-value="12"
      v-model="dateRadio"
    />
  </div>
</template>

<script>
  import Input from '@/components/form/input.vue'
  import RadioBox from '@/components/form/radiobox.vue'

  let from
  let to

  export default {
    components: {
      Input,
      RadioBox
    },
    props: {
      width: {
        type: String,
        default: 'auto'
      },
      id: {
        type: String,
        required: true
      },
      readonly: {
        type: Boolean,
        default: false
      },
      disAbled: {
        type: Boolean,
        default: false
      },
      range: {
        type: Boolean,
        default: true
      },
      searchli: {
        type: Object,
        default: function () {
          return {}
        }
      }
    },
    data: function () {
      return {
        from_value: this.searchli.from_value, // this.$moment().subtract(3, 'months').format('YYYY-MM-DD'),
        to_value: this.searchli.to_value, // this.$moment().format('YYYY-MM-DD'),
        dateRadio: this.$moment(this.searchli.to_value, 'YYYY-MM').diff(this.searchli.from_value, 'month')
      }
    },
    mounted () {
      const vm = this
      from = window.$('#from_' + vm.id)

      from.datepicker({
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
        onSelect: function (dateText, inst) {
          vm.from_value = dateText
        }
      })

      to = window.$('#to_' + vm.id)
      to.datepicker({
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
        onSelect: function (dateText, inst) {
          vm.to_value = dateText
        }
      })
    },
    watch: {
      dateRadio: function (newDateRadio) {
        this.setDate()
      },
      from_value () {
        this.$emit('from-value', this.from_value)
      },
      to_value () {
        this.$emit('to-value', this.to_value)
      }
    },
    methods: {
      update: function () {
        const fromDate = window.$('#from_' + this.searchli.name).val()
        const toDate = window.$('#to_' + this.searchli.name).val()

        this.from_value = fromDate
        this.to_value = toDate
      },
      setDate: function () {
        this.from_value = this.$moment().subtract(this.dateRadio, 'months').format('YYYY-MM-DD')
        this.to_value = this.$moment().format('YYYY-MM-DD')
      }
    }
  }
</script>

<style scoped>
  .input_wrap {
    display: inline-block;
  }

  ::v-deep button[type="button"].ui-datepicker-trigger {
    display: none;
  }
</style>
