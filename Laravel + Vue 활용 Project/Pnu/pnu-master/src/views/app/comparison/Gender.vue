<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.gender')" />
        <div class="separator mb-5"></div>
      </b-colxx>
    </b-row>
    <b-row>
      <b-colxx xxs="12">
        <b-card class="mb-4" :title="$t('menu.gender')">
          <b-row style="height: 450px;">
            <b-colxx xxs="12" lg="12" class="mb-4">
              <div class="chart-container">
                <bar-chart :data="barChartData" :height="400" ref="barchart" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>
      <b-colxx xxs="6" class="mb-4">
        <b-card>
          <b-row>
            <h3 class="mb-4" style="color:#0078FF; font-size: 1.5em;font-weight:bold;width:100%">Step 1 : 비교 카테고리를 선택하세요</h3>
              <b-colxx sm="4">
                <div class="form-group">
                    <b-button variant="outline-success" @click="step1Value(0)">학과</b-button>
                    <b-button variant="outline-success" @click="step1Value(1)">단대</b-button>
                    <b-button variant="outline-success" @click="step1Value(2)">학생</b-button>
                </div>
              </b-colxx>
          </b-row>
          <b-row class="mt-4" v-if="step1_on">
            <h3 class="mb-4" style="color:#0078FF; font-size: 1.5em;font-weight:bold;width:100%">Step 2 : 비교 대상을 선택하세요</h3>
            <vuetable
            ref="vuetable"
            :load-on-start="false"
            :api-url="MainTable.apiUrl"
            :fields="MainTable.fields"
            :http-fetch="httpFetch"
            :append-params="appendParams"
            pagination-path
            @vuetable:pagination-data="onPaginationData"
            @vuetable:row-clicked="rowClicked"
          ></vuetable>
          <vuetable-pagination-bootstrap
            ref="pagination"
            @vuetable-pagination:change-page="onChangePage"
          />
          </b-row>
        </b-card>
      </b-colxx>
    </b-row>
  </div>
</template>
<script>
import {
  areaChartData,
  barChartData
} from '../../../data/charts'
import { barChartOptions } from '../../../components/Charts/config'
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'
import BarChart from '../../../components/Charts/Bar'
export default {
  name: 'Gender',
  components: {
    Vuetable,
    VuetablePaginationBootstrap,
    'bar-chart': BarChart
  },
  data () {
    return {
      MainTable: {
        apiUrl: '/users/paginate',
        fields: [
          {
            name: 'user_id',
            title: '유저 내부식별ID'
          },
          {
            name: 'user_no',
            title: '유저ID'
          },
          {
            name: 'sta',
            title: '학적상태명'
          },
          {
            name: 'deptcd',
            title: '소속(학과)코드'
          },
          {
            name: 'dept',
            title: '소속(학과)명'
          },
          {
            name: 'majorcd',
            title: '전공코드'
          },
          {
            name: 'stdyear',
            title: '학년'
          },
          {
            name: 'created_at',
            title: '생성일'
          },
          {
            name: 'updated_at',
            title: '변경일'
          }
        ]
      },
      testGraph: {
        series: [
          {
            name: 'Males',
            data: [0.4, 0.65, 0.76, 0.88, 1.5, 2.1, 2.9, 3.8, 3.9, 4.2, 4, 4.3, 4.1, 4.2, 4.5,
              3.9, 3.5, 3
            ]
          },
          {
            name: 'Females',
            data: [-0.8, -1.05, -1.06, -1.18, -1.4, -2.2, -2.85, -3.7, -3.96, -4.22, -4.3, -4.4,
              -4.1, -4, -4.1, -3.4, -3.1, -2.8
            ]
          }
        ],
        chartOptions: {
          chart: {
            type: 'bar',
            height: 440,
            stacked: true
          },
          colors: ['#008FFB', '#FF4560'],
          plotOptions: {
            bar: {
              horizontal: true,
              barHeight: '80%'
            }
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            width: 1,
            colors: ['#fff']
          },
          grid: {
            xaxis: {
              lines: {
                show: false
              }
            }
          },
          yaxis: {
            min: -5,
            max: 5,
            title: {
              // text: 'Age',
            }
          },
          tooltip: {
            shared: false,
            x: {
              formatter: function (val) {
                return val
              }
            },
            y: {
              formatter: function (val) {
                return Math.abs(val) + '%'
              }
            }
          },
          title: {
            text: 'Mauritius population pyramid 2011'
          },
          xaxis: {
            categories: ['85+', '80-84', '75-79', '70-74', '65-69', '60-64', '55-59', '50-54',
              '45-49', '40-44', '35-39', '30-34', '25-29', '20-24', '15-19', '10-14', '5-9',
              '0-4'
            ],
            title: {
              text: 'Percent'
            },
            labels: {
              formatter: function (val) {
                return Math.abs(Math.round(val)) + '%'
              }
            }
          }
        }
      },
      barChartData,
      barOptions: barChartOptions,
      areaChartData,
      appendParams: {},
      keyword: '',
      graphColor: '#' + (Math.random() * 0xFFFFFF << 0).toString(16),
      isDeptSearchOn: false,
      step1_on: false
    }
  },
  async mounted () {
    setTimeout(() => {
      this.isChecked = true
    }, 150)
    try {
      this.$store.commit('setProcessing', true)
      // await this.$refs.vuetable.reload()
    } finally {
      this.$store.commit('setProcessing', false)
    }
  },
  async created () {
    await this.loadBatch()
  },
  methods: {
    async loadBatch () {
      this.barChartData.labels = []
      this.barChartData.datasets = []
      await this.$axios.get(`/batches`, {}).then(res => {
        for (var key in res.data) {
          this.barChartData.labels.push(res.data[key].batch_name)
        }
        this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
      })
    },
    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
    },
    rowClicked (row) {
      this.openEdit(row)
      this.addNotification()
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
      for (var key in this.barChartData.datasets) {
        if (this.barChartData.datasets[key].label === row.user_no) {
          this.barChartData.datasets.splice(key, 1)
          this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
          return false
        }
      }
      this.barChartData.datasets.push({
        label: row.user_no,
        borderColor: this.graphColor,
        backgroundColor: this.graphColor,
        data: [332, 780, 542],
        borderWidth: 2
      })
      this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
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
    searchDept () {
      this.appendParams = {
        user_no: this.keyword
      }
      this.isDeptSearchOn = true
      this.$nextTick(() => {
        this.$refs.vuetable.reload()
      })
    },
    viewDeptAll () {
      this.appendParams = {}
      this.isDeptSearchOn = false
      this.$nextTick(() => {
        this.$refs.vuetable.reload()
      })
    },
    step1Value (value) {
      this.step1_on = true
      // 현재 아래는 테스틑용(0: 학과, 1: 단대, 2: 학생)
      switch (value) {
        case 0:
          this.MainTable.apiUrl = '/users/paginate'
          this.MainTable.fields = [
            {
              name: 'user_id',
              title: '유저 내부식별ID'
            },
            {
              name: 'user_no',
              title: '유저ID'
            },
            {
              name: 'deptcd',
              title: '소속(학과)코드'
            },
            {
              name: 'dept',
              title: '소속(학과)명'
            },
            {
              name: 'majorcd',
              title: '전공코드'
            },
            {
              name: 'stdyear',
              title: '학년'
            }
          ]
          this.$nextTick(() => {
            this.$refs.vuetable.reload()
          })
          break
        case 1:
          this.SubTable.apiUrl = '/users/paginate'
          this.SubTable.fields = [
            {
              name: 'user_id',
              title: '유저 내부식별ID'
            },
            {
              name: 'user_no',
              title: '유저ID'
            },
            {
              name: 'deptcd',
              title: '소속(학과)코드'
            },
            {
              name: 'dept',
              title: '소속(학과)명'
            },
            {
              name: 'majorcd',
              title: '전공코드'
            },
            {
              name: 'stdyear',
              title: '학년'
            }
          ]
          this.$nextTick(() => {
            this.$refs.vuetable.reload()
          })
          break
        case 2:
          this.SubTable.apiUrl = '/users/paginate'
          this.SubTable.fields = [
            {
              name: 'user_id',
              title: '유저 내부식별ID'
            },
            {
              name: 'user_no',
              title: '유저ID'
            },
            {
              name: 'deptcd',
              title: '소속(학과)코드'
            },
            {
              name: 'dept',
              title: '소속(학과)명'
            },
            {
              name: 'majorcd',
              title: '전공코드'
            },
            {
              name: 'stdyear',
              title: '학년'
            }
          ]
          this.$nextTick(() => {
            this.$refs.vuetable.reload()
          })
          break
      }
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
