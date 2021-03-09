<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.major')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
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
      <b-colxx xxs="4">
        <b-card class="mb-4" :title="$t('menu.major') + `(역량 별 특정분기 총합)`">
          <b-row>
            <b-colxx xxs="12" lg="12" class="mb-4">
              <div class="chart-container">
                <radar-chart :data="radarChartData" :height="300" ref="radarchart" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>
      <b-colxx xxs="4">
        <b-card class="mb-4" :title="$t('menu.major') + `(총점)`">
          <b-row>
            <b-colxx xxs="12" lg="12" class="mb-4">
              <div class="chart-container">
                <bar-chart :data="barChartData" :height="300" ref="barchart" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>
      <b-colxx xxs="4">
        <b-card class="mb-4" :title="$t('menu.major') + `(백분위)`">
          <b-row>
            <b-colxx xxs="12" lg="12" class="mb-4">
              <div class="chart-container">
                <bar-chart :data="barChartData2" :height="300" ref="barchart2" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>
      <b-colxx xxs="12" class="mb-4">
        <b-card>
          <b-row>
            <h3
              class="mb-4"
              style="color:#0078FF; font-size: 1.5em;font-weight:bold;width:100%"
            >Step 1 : 비교하려는 전공을 클릭하세요</h3>
          </b-row>
          <vuetable
            ref="vuetable"
            :load-on-start="false"
            :api-url="MainTable.apiUrl"
            :fields="MainTable.fields"
            :http-fetch="httpFetch"
            :append-params="appendParams"
            :row-class="onRowClass"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="rowClicked"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
        </b-card>
      </b-colxx>
    </b-row>
  </div>
</template>
<script>
import {
  radarChartData,
  barChartData,
  barChartData2
} from '../../../data/charts'
import { barChartOptions, radarChartOptions } from '../../../components/Charts/config'
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'
import BarChart from '../../../components/Charts/Bar'
import RadarChart from '../../../components/Charts/Radar'

export default {
  name: 'Major',
  components: {
    Vuetable,
    VuetablePaginationBootstrap,
    'bar-chart': BarChart,
    'radar-chart': RadarChart
  },
  data () {
    return {
      batchList: [],
      selectedBatch: {},
      MainTable: {
        apiUrl: '/groups/paginate',
        fields: [
          {
            name: 'major',
            title: '전공 이름'
          }
        ]
      },
      radarChartData,
      barChartData,
      barChartData2,
      radarOptions: radarChartOptions,
      barOptions: barChartOptions,
      barOptions2: barChartOptions,
      appendParams: {
        req: 'major'
      },
      keyword: '',
      graphColor: '#' + (Math.random() * 0xFFFFFF << 0).toString(16),
      isCollegeSearchOn: false
    }
  },
  async mounted () {
    setTimeout(() => {
      this.isChecked = true
    }, 150)
    try {
      this.$store.commit('setProcessing', true)
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

      await this.$refs.vuetable.reload()
    } finally {
      this.$store.commit('setProcessing', false)
    }
  },
  async created () {
    await this.loadBatch()
  },
  methods: {
    async loadBatch () {
      this.radarChartData.datasets = []
      this.barChartData.labels = []
      this.barChartData2.labels = []
      this.barChartData.datasets = []
      this.barChartData2.datasets = []
      await this.$axios.get(`/batches`, {}).then(res => {
        for (var key in res.data) {
          this.barChartData.labels.push(res.data[key].batch_name)
          this.barChartData2.labels.push(res.data[key].batch_name)
        }
        this.barOptions.scales.yAxes[0].ticks.max = 1000
        this.barOptions2.scales.yAxes[0].ticks.max = 1000
        this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
        this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
        this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions2)
      })
    },
    async selectBatch (batch) {
      this.selectedBatch = batch
    },
    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
    },
    rowClicked (row) {
      this.openEdit(row)
    },
    addNotification () {
      var compare = ''
      for (var key in this.barChartData.datasets) {
        compare += this.barChartData.datasets[key].label + '<br>'
      }
      this.$notify('success filled', '현재 비교자(클릭시 사라져요)', compare, { duration: 5000, permanent: false })
    },
    openEdit (row) {
      this.generator()
      for (var key2 in this.barChartData.datasets) {
        if (this.barChartData.datasets[key2].label === row.major || this.barChartData2.datasets[key2].label === row.major || this.radarChartData.datasets[key2].label === row.major) {
          this.barChartData.datasets.splice(key2, 1)
          this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
          this.barChartData2.datasets.splice(key2, 1)
          this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions2)
          this.radarChartData.datasets.splice(key2, 1)
          this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
          this.addNotification()
          return false
        }
      }
      this.$axios.get(`/analysises`, {
        params: {
          req: 'major_perc_rank',
          majorcd: row.majorcd
        }
      }).then(res => {
        let ppl = Number(res.data.query[0].VALS)
        this.barChartData.datasets.push({
          label: row.major,
          borderColor: this.graphColor,
          backgroundColor: this.graphColor,
          data: [ppl],
          borderWidth: 2
        })
        let count = res.data.query[0].COUNT
        let perc = 100 - ((Number(res.data.query[0].RANK) * 100) / count)
        this.barChartData2.datasets.push({
          label: row.major,
          borderColor: this.graphColor,
          backgroundColor: this.graphColor,
          data: [perc],
          borderWidth: 2
        })

        // 특정 전공 분기별 총합
        this.$axios.get(`/analysises`, {
          params: {
            req: 'major_total_per_batch',
            batch_id: this.selectedBatch.batch_id,
            majorcd: row.majorcd
          }
        }).then(res => {
          const data = res.data.query

          this.radarChartData.labels = data.map(x => x.cpt_title)
          this.radarChartData.datasets.push(
            {
              label: row.major,
              borderWidth: 2,
              pointBackgroundColor: this.graphColor,
              borderColor: this.graphColor,
              backgroundColor: this.graphColor,
              data: data.map(x => x.ucpt_sum),
              fill: false
            }
          )

          this.$nextTick(() => {
            this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
          })
        })

        this.$nextTick(() => {
          if (this.barOptions.scales.yAxes[0].ticks.max < ppl) {
            this.barOptions.scales.yAxes[0].ticks.max = ppl
          }
          this.$refs.barchart.renderChart(this.barChartData, this.barOptions)

          if (this.barOptions.scales.yAxes[0].ticks.max < Number(perc)) {
            this.barOptions2.scales.yAxes[0].ticks.max = Number(perc)
          }
          this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions2)
          this.addNotification()
        })
      })
    },
    generator () {
      this.graphColor = '#' + (Math.random() * 0xFFFFFF << 0).toString(16)
    },
    httpFetch (apiUrl, httpOptions) {
      return this.$axios.get(apiUrl, httpOptions)
    },
    onChangePage (page) {
      this.$refs.vuetable.changePage(page)
    },
    searchCollege () {
      this.appendParams = {
        id: this.keyword
      }
      this.isCollegeSearchOn = true
      this.$nextTick(() => {
        this.$refs.vuetable.reload()
      })
    },
    viewCollegeAll () {
      this.appendParams = {}
      this.isCollegeSearchOn = false
      this.$nextTick(() => {
        this.$refs.vuetable.reload()
      })
    },
    onRowClass (dataItem, index) {
      return Number(index) % 2 === 0 ? 'bg-semi-muted' : ''
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../../styles/scss/layout/responsive.scss";

.graph {
  position: relative;
  z-index: 1;
}
</style>
