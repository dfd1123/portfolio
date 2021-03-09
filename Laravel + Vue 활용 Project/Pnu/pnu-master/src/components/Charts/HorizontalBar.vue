<script>
import { HorizontalBar } from 'vue-chartjs'
import { horizontalChartOptions } from './config'

export default {
  extends: HorizontalBar,
  props: ['data'],
  data () {
    return {
      options: horizontalChartOptions
    }
  },
  mounted () {
    this.renderChart(this.data, this.options)
  }
}
</script>
