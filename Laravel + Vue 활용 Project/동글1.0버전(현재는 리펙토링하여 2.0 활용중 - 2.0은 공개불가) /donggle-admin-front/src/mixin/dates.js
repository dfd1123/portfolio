import moment from 'moment'
import { extendMoment } from 'moment-range'

const dates = {
  $momentRange (start, end) {
    const momentRange = extendMoment(moment)
    return momentRange.range(start, end)
  },
  dateNameToDateFormat (dateName, format = 'YYYY-MM-DD') {
    const date = moment()

    if (dateName === 'today') {
      return {
        start: date.clone().format(format),
        end: date.clone().format(format)
      }
    }

    if (dateName === 'yesterday') {
      return {
        start: moment().clone().add(-1, 'days').format(format),
        end: moment().clone().add(-1, 'days').format(format)
      }
    }

    if (dateName === 'this_week') {
      return {
        start: moment().clone().startOf('week').format(format),
        end: moment().clone().format(format)
      }
    }

    if (dateName === 'last_week') {
      return {
        start: moment().clone().subtract(1, 'weeks').startOf('week').format(format),
        end: moment().clone().subtract(1, 'weeks').endOf('week').format(format)
      }
    }

    if (dateName === 'last_month') {
      return {
        start: moment().clone().subtract(1, 'months').startOf('month').format(format),
        end: moment().clone().subtract(1, 'months').endOf('month').format(format)
      }
    }

    if (dateName === 'this_month') {
      return {
        start: moment().clone().startOf('month').format(format),
        end: moment().clone().format(format)
      }
    }

    return {
      start: null,
      end: null
    }
  }
}

export default dates
