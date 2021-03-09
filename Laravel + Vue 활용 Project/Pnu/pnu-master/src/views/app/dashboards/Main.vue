<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <b-card class="mb-4">
          <h3
            class="mb-4"
            style="color:#0078FF; font-size: 1em; font-weight:bold; width:100%"
          >차수를 선택해 주세요</h3>
          <b-button
            v-for="batch in batchList"
            :key="batch.batch_id"
            :pressed="batch.batch_id === selectedBatch.batch_id"
            variant="outline-primary"
            class="mr-1"
            @click="selectBatch(batch)"
          >[{{batch.batch_id}}] {{batch.batch_name}}</b-button>
        </b-card>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx md="12" lg="6">
        <b-card class="mb-4">
          <b-row>
            <b-colxx xxs="12" lg="12">
              <h6 class="card-subtitle">기간별 진단 인원</h6>
              <div class="chart-container">
                <line-chart :data="chart1.chartData" :height="300" ref="chart1" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>

      <b-colxx md="12" lg="6">
        <b-card class="mb-4">
          <b-row>
            <b-colxx xxs="12" lg="12">
              <h6 class="card-subtitle">8개 역량별 총 진단 인원</h6>
              <div class="chart-container">
                <radar-chart :data="chart2.chartData" :height="300" ref="chart2" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>

      <b-colxx md="12" lg="6">
        <b-card class="mb-4">
          <b-row>
            <b-colxx xxs="12" lg="12">
              <h6 class="card-subtitle">8개 역량별 총점 평균</h6>
              <div class="chart-container">
                <radar-chart :data="chart3.chartData" :height="300" ref="chart3" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>

      <b-colxx md="12" lg="6">
        <b-row>
          <b-colxx xxs="12" lg="12" class="mb-4">
            <gradient-with-radial-progress-card
              style="height: 100%"
              icon="simple-icon-graph"
              title="총 진단 인원"
              detail
              :progressText="total"
              :percent="100"
            ></gradient-with-radial-progress-card>
          </b-colxx>
        </b-row>
        <b-row>
          <b-colxx xxs="12" lg="12" class="mb-4">
            <gradient-with-radial-progress-card
              style="height: 100%"
              icon="simple-icon-graph"
              title="전체 총점 평균"
              detail
              :progressText="average"
              :percent="100"
            ></gradient-with-radial-progress-card>
          </b-colxx>
        </b-row>
      </b-colxx>
    </b-row>
  </div>
</template>
<script>
import {
  radarChartData,
  lineChartData
} from '../../../data/charts'
import LineChart from '../../../components/Charts/Line'
import RadarChart from '../../../components/Charts/Radar'
import { lineChartOptions, radarChartOptions } from '../../../components/Charts/config'
import GradientWithRadialProgressCard from '../../../components/Cards/GradientWithRadialProgressCard'
export default {
  name: 'Main',
  components: {
    'line-chart': LineChart,
    'radar-chart': RadarChart,
    GradientWithRadialProgressCard
  },
  data () {
    return {
      batchList: [],
      selectedBatch: {},
      chart1: {
        chartData: { ...lineChartData, ...{ datasets: [] } },
        options: { ...lineChartOptions, ...{ tooltips: { ...lineChartOptions.tooltips } } }
      },
      chart2: {
        chartData: { ...radarChartData, ...{ datasets: [] } },
        options: { ...radarChartOptions, ...{ tooltips: { ...radarChartOptions.tooltips } } }
      },
      chart3: {
        chartData: { ...radarChartData, ...{ datasets: [] } },
        options: { ...radarChartOptions, ...{ tooltips: { ...radarChartOptions.tooltips } } }
      },
      /*
      chart4: {
        chartData: { ...radarChartData, ...{ datasets: [] } },
        options: { ...radarChartOptions, ...{ tooltips: { ...radarChartOptions.tooltips } } }
      }
      */
      total: null,
      average: null
    }
  },
  async mounted () {
    await this.load()
  },
  methods: {
    async load () {
      this.batchList = await this.$axios
        .get('/batches', {
          params: {
            limit: 10
          }
        })
        .then(r => r.data)

      await this.$nextTick()

      if (this.batchList.length > 0) {
        setTimeout(() => {
          this.selectBatch(this.batchList[0])
        }, 150)
      }
    },
    async selectBatch (batch) {
      try {
        this.selectedBatch = batch
        await Promise.all([
          this.loadChart1(),
          this.loadChart2(),
          this.loadChart3(),
          this.loadAverage()
        ])
      } catch (e) {
        console.log(e)
      }
    },
    async loadChart1 () {
      const { chartData, options } = this.chart1

      const data = await this.$axios
        .get(`/analysises`, {
          params: {
            req: 'passed',
            batch: this.selectedBatch.batch_id
          }
        })
        .then(r => r.data.query)

      const max = Math.max(...data.map(x => x.ppl))
      const color = this.getRandomColor()

      chartData.labels = data.map(x => x.created_at)
      chartData.datasets = [
        {
          label: this.selectedBatch.batch_name,
          data: data.map(x => x.ppl),
          borderColor: color,
          pointBackgroundColor: color,
          pointBorderColor: color,
          pointHoverBackgroundColor: color,
          pointHoverBorderColor: color,
          pointRadius: 6,
          pointBorderWidth: 2,
          pointHoverRadius: 8,
          fill: false
        }
      ]

      options.scales.yAxes[0].ticks.max = max + (9 - (max & 10))
      options.tooltips.callbacks = {
        label (tooltipItem, data) {
          return '당일: ' + tooltipItem.value + ` 누적: ${data.datasets[tooltipItem.datasetIndex].data.slice(0, tooltipItem.index + 1).reduce((a, b) => a + b, 0)}`
        }
      }

      await this.$nextTick()

      this.$refs.chart1.renderChart(chartData, options)
    },
    async loadChart2 () {
      const { chartData, options } = this.chart2

      const data = await this.$axios
        .get(`/analysises`, {
          params: {
            req: 'type_total_per_batch',
            batch_id: this.selectedBatch.batch_id
          }
        })
        .then(r => r.data.query)

      const color = this.getRandomColor()

      chartData.labels = data.map(x => x.cpt_title)
      chartData.datasets = [
        {
          label: this.selectedBatch.batch_name,
          borderWidth: 2,
          pointBackgroundColor: color,
          borderColor: color,
          backgroundColor: color,
          data: data.map(x => x.cpt_total),
          fill: false
        }
      ]

      options.legend.display = false
      options.scale.ticks.beginAtZero = true

      await this.$nextTick()

      this.total = Math.max(...data.map(x => x.cpt_total), 0)
      this.$refs.chart2.renderChart(chartData, options)
    },
    async loadChart3 () {
      const { chartData, options } = this.chart3

      const data = await this.$axios
        .get(`/analysises`, {
          params: {
            req: 'type_avg_per_batch',
            batch_id: this.selectedBatch.batch_id
          }
        })
        .then(r => r.data.query)

      const color = this.getRandomColor()

      chartData.labels = data.map(x => x.cpt_title)
      chartData.datasets = [
        {
          label: this.selectedBatch.batch_name,
          borderWidth: 2,
          pointBackgroundColor: color,
          borderColor: color,
          backgroundColor: color,
          data: data.map(x => x.ucpt_avg),
          fill: false
        }
      ]

      options.legend.display = false

      await this.$nextTick()

      this.$refs.chart3.renderChart(chartData, options)
    },
    async loadAverage () {
      const data = await this.$axios
        .get(`/analysises`, {
          params: {
            req: 'avg_per_batch',
            batch_id: this.selectedBatch.batch_id
          }
        })
        .then(r => r.data.query)

      this.average = Number(data[0].ucpt_avg).toFixed(2)
    },
    getRandomColor () {
      return '#' + (Math.random() * 0xFFFFFF << 0).toString(16)
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../../styles/scss/layout/responsive.scss";
</style>
