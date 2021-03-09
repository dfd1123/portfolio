<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.student')" />
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
        <b-card class="mb-4" :title="$t('menu.student') + `역량 별 특정분기 총합`">
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
        <b-card class="mb-4" :title="$t('menu.student') + `백분위`">
          <b-row>
            <b-colxx xxs="12" lg="12" class="mb-4">
              <div class="chart-container">
                <bar-chart :data="barChartData2" :height="300" ref="barchart2" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>
      <b-colxx xxs="4">
        <b-card class="mb-4" :title="$t('menu.student') + `총합`">
          <b-row>
            <b-colxx xxs="12" lg="12" class="mb-4">
              <div class="chart-container">
                <bar-chart :data="barChartData" :height="300" ref="barchart" />
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>
      <b-colxx xxs="6" class="mb-4">
        <b-card>
          <b-row>
            <h3
              class="mb-4"
              style="color:#0078FF; font-size: 1.5em;font-weight:bold;width:100%"
            >Step 1 : 특정 학생을 선택하세요(1명만 가능)</h3>
            <b-colxx sm="4">
              <div class="form-group">
                <b-input v-model="keyword" placeholder="학번(아이디)" />
              </div>
            </b-colxx>
            <b-colxx sm="4">
              <b-button
                variant="success"
                class="mb-2"
                @click="searchUser"
                style="margin-left:1em;"
              >검색</b-button>
              <b-button
                v-if="isUserSearchOn"
                variant="primary"
                class="mb-2"
                @click="viewUserAll"
                style="margin-left:1em;"
              >전체보기</b-button>
            </b-colxx>
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
      <b-colxx xxs="6" class="mb-4" v-if="step1_on">
        <b-card>
          <b-row>
            <h3
              class="mb-4"
              style="color:#0078FF; font-size: 1.5em;font-weight:bold;width:100%"
            >Step 2 : 비교 카테고리를 선택하세요</h3>
            <b-colxx sm="12">
              <div class="form-group">
                <b-button variant="outline-success" @click="step2Value('학과')">학과</b-button>
                <b-button variant="outline-success" @click="step2Value('전공')">전공</b-button>
                <b-button variant="outline-success" @click="step2Value('학생')">학생</b-button>
              </div>
            </b-colxx>
          </b-row>
          <b-row class="mt-4" v-if="step2_on">
            <h3
              class="mb-4"
              style="color:#0078FF; font-size: 1.5em;font-weight:bold;width:100%"
            >Step 3 : 비교 대상을 선택하세요(다수 가능)</h3>
            <vuetable
              ref="vuetable2"
              :load-on-start="false"
              :api-url="SubTable.apiUrl"
              :fields="SubTable.fields"
              :http-fetch="httpFetch"
              :append-params="appendParams2"
              :row-class="onRowClass"
              pagination-path
              @vuetable:pagination-data="onPaginationData2"
              @vuetable:row-clicked="rowClicked2"
            ></vuetable>
            <vuetable-pagination-bootstrap
              ref="pagination2"
              @vuetable-pagination:change-page="onChangePage2"
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
  barChartData,
  barChartData2,
  radarChartData
} from '../../../data/charts'
import { barChartOptions, radarChartOptions } from '../../../components/Charts/config'
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'
import BarChart from '../../../components/Charts/Bar'
import RadarChart from '../../../components/Charts/Radar'

export default {
  name: 'Student',
  components: {
    Vuetable,
    VuetablePaginationBootstrap,
    'radar-chart': RadarChart,
    'bar-chart': BarChart
  },
  data () {
    return {
      batchList: [],
      selectedBatch: {},
      MainTable: {
        apiUrl: '/users/paginate',
        fields: [
          /*
          {
            name: 'user_id',
            title: '유저 내부식별ID'
          },
          */
          {
            name: 'user_no',
            title: '유저ID'
          },
          {
            name: 'sta',
            title: '학적상태명'
          },
          {
            name: 'stdyear',
            title: '학년'
          },
          /*
          {
            name: 'deptcd',
            title: '소속(학과)코드'
          },
          */
          {
            name: 'dept',
            title: '소속(학과)명'
          }
          /*
          {
            name: 'majorcd',
            title: '전공코드'
          },
          {
            name: 'created_at',
            title: '생성일'
          },
          {
            name: 'updated_at',
            title: '변경일'
          }
          */
        ]
      },
      SubTable: {
        apiUrl: '',
        fields: [],
        type: '',
        Subkey: ''
      },
      barChartData,
      barChartData2,
      radarChartData,
      radarOptions: radarChartOptions,
      barOptions: barChartOptions,
      areaChartData,
      appendParams: {},
      appendParams2: {},
      keyword: '',
      graphColor: '#' + (Math.random() * 0xFFFFFF << 0).toString(16),
      isUserSearchOn: false,
      step1_on: false,
      step2_on: false
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
  created () {
    this.loadBatch()
  },
  methods: {
    loadBatch () {
      this.barChartData.labels = []
      this.barChartData2.labels = []
      this.barChartData.datasets = []
      this.barChartData2.datasets = []
      this.radarChartData.datasets = []
      this.$nextTick(() => {
        this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
        this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
        this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
      })
    },
    async selectBatch (batch) {
      this.selectedBatch = batch
    },
    onPaginationData (paginationData) {
      this.$refs.pagination.setPaginationData(paginationData)
    },
    onPaginationData2 (paginationData) {
      this.$refs.pagination2.setPaginationData(paginationData)
    },
    rowClicked (row) {
      this.openEdit(row)
    },
    rowClicked2 (row) {
      this.openEdit2(row)
    },
    addNotification () {
      var compare = ''
      for (var key in this.barChartData.datasets) {
        compare += this.barChartData.datasets[key].label + '<br>'
      }
      this.$notify('success filled', '현재 비교자(클릭시 사라져요)', compare, { duration: 5000, permanent: false })
    },
    async openEdit (row) {
      try {
        this.generator()
        this.barChartData.labels = []
        this.barChartData2.labels = []
        if (this.barChartData.datasets.length > 0) {
          for (var key in this.barChartData.datasets) {
            if (this.barChartData.datasets[key].label === row.user_no) {
              barChartData.datasets.splice(key, this.barChartData.datasets.length)
              barChartData2.datasets.splice(key, this.barChartData2.datasets.length)
              radarChartData.datasets.splice(key, this.radarChartData.datasets.length)
              this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
              this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
              this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
              this.step1_on = false
              this.step2_on = false
              return false
            }
          }
          this.step1_on = false
          this.step2_on = false
          this.barChartData.datasets.splice(key, this.barChartData.datasets.length)
          this.barChartData2.datasets.splice(key, this.barChartData2.datasets.length)
          this.radarChartData.datasets.splice(key, this.radarChartData.datasets.length)
          this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
          this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
          this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
        }

        const req1 = this.$axios.get(`/analysises`, {
          params: {
            req: 'stdt',
            user_id: row.user_id,
            batch_id: this.selectedBatch.batch_id
          }
        }).then(res => {
          let Alltotal = 0
          let Subtotal = 0
          this.barChartData.labels.push('원점수 합')
          this.barChartData2.labels.push('백분위')
          for (var key in res.data.query) {
            Subtotal = 0
            for (var idx in JSON.parse(res.data.query[key].VALS)) {
              Subtotal += Number(JSON.parse(res.data.query[key].VALS)[idx])
            }
            Alltotal += Subtotal
          }
          this.barChartData.datasets.push({
            label: `${row.name}(${row.user_no})`,
            borderColor: this.graphColor,
            backgroundColor: this.graphColor,
            data: [Alltotal],
            borderWidth: 2
          })
          this.barOptions.scales.yAxes[0].ticks.max = Alltotal + (100 - Alltotal)
          this.$nextTick(() => {
            this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
            this.addNotification()
          })
        })
        const req2 = this.$axios.get(`/analysises`, {
          params: {
            req: 'stdt_perc_rank',
            user_id: row.user_id
          }
        }).then(res => {
        // 테스트용
          let count = res.data.query[0].COUNT
          let perc = 100 - ((res.data.query[0].RANK * 100) / count)
          this.barChartData2.datasets.push({
            label: `${row.name}(${row.user_no})`,
            borderColor: this.graphColor,
            backgroundColor: this.graphColor,
            data: [Number(perc)],
            borderWidth: 2
          })
          this.barOptions.scales.yAxes[0].ticks.max = perc + (100 - perc)
          this.$nextTick(() => {
            this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
            this.addNotification()
          })
        })
        this.step1_on = true

        // 특정 학생 분기별 총합
        this.generator()
        this.radarChartData.datasets = []
        const req3 = this.$axios.get(`/analysises`, {
          params: {
            req: 'stdt',
            user_id: row.user_id,
            batch_id: this.selectedBatch.batch_id
          }
        }).then(res => {
          let cpt1 = 0
          let cpt2 = 0
          let cpt3 = 0
          let cpt4 = 0
          let cpt5 = 0
          let cpt6 = 0
          let cpt7 = 0
          let cpt8 = 0
          let preTotal = 0
          for (var key in res.data.query) {
            preTotal = 0
            for (var idx in JSON.parse(res.data.query[key].VALS)) {
              preTotal += JSON.parse(res.data.query[key].VALS)[idx]
            }
            switch (res.data.query[key].cpt_id) {
              case 1:
                cpt1 += preTotal
                break
              case 2:
                cpt2 += preTotal
                break
              case 3:
                cpt3 += preTotal
                break
              case 4:
                cpt4 += preTotal
                break
              case 5:
                cpt5 += preTotal
                break
              case 6:
                cpt6 += preTotal
                break
              case 7:
                cpt7 += preTotal
                break
              case 8:
                cpt8 += preTotal
                break
            }
          }
          this.radarChartData.datasets.push({
            label: `${row.name}(${row.user_no})`,
            borderWidth: 2,
            pointBackgroundColor: this.graphColor,
            borderColor: this.graphColor,
            data: [cpt1, cpt2, cpt3, cpt4, cpt5, cpt6, cpt7, cpt8]
          })
          this.$nextTick(() => {
            this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
          })
        })

        await Promise.all([req1, req2, req3])
      } catch (e) {
        console.log(e)
      } finally {

      }
    },
    openEdit2 (row) {
      this.generator()
      if (this.SubTable.Subkey === 'dept') {
        for (var key in this.barChartData.datasets) {
          if (this.barChartData.datasets[key].label === row[this.SubTable.Subkey]) {
            this.barChartData.datasets.splice(key, 1)
            this.barChartData2.datasets.splice(key, 1)
            this.radarChartData.datasets.splice(key, 1)
            this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
            this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
            this.$refs.radarchart.renderChart(this.radarChartData, this.radarChartOptions)
            this.addNotification()
            return false
          }
        }
        this.$axios.get(`/analysises`, {
          params: {
            req: 'detp_sum',
            deptcd: row.deptcd
          }
        }).then(res => {
          let ppl = Number(res.data.query[0].ppl)
          this.barChartData.datasets.push({
            label: row[this.SubTable.Subkey],
            borderColor: this.graphColor,
            backgroundColor: this.graphColor,
            data: [ppl],
            borderWidth: 2
          })
          this.$nextTick(() => {
            if (this.barOptions.scales.yAxes[0].ticks.max < ppl) {
              this.barOptions.scales.yAxes[0].ticks.max = ppl
            }
            this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
            this.addNotification()
          })
        })
      } else if (this.SubTable.Subkey === 'major') {
        for (var key2 in this.barChartData.datasets) {
          if (this.barChartData.datasets[key2].label === row[this.SubTable.Subkey]) {
            this.barChartData.datasets.splice(key2, 1)
            this.barChartData2.datasets.splice(key2, 1)
            this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
            this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
            this.addNotification()
            return false
          }
        }
        this.$axios.get(`/analysises`, {
          params: {
            req: 'major_sum',
            majorcd: row.majorcd
          }
        }).then(res => {
          let ppl = Number(res.data.query[0].ppl)
          this.barChartData.datasets.push({
            label: row[this.SubTable.Subkey],
            borderColor: this.graphColor,
            backgroundColor: this.graphColor,
            data: [ppl],
            borderWidth: 2
          })
          this.$nextTick(() => {
            if (this.barOptions.scales.yAxes[0].ticks.max < ppl) {
              this.barOptions.scales.yAxes[0].ticks.max = ppl
            }
            this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
            this.addNotification()
          })
        })
      } else {
        this.generator()
        if (this.barChartData.datasets.length > 0) {
          for (var key3 in this.barChartData.datasets) {
            if (this.barChartData.datasets[key3].label === row.user_no) {
              barChartData.datasets.splice(key3, 1)
              barChartData2.datasets.splice(key3, 1)
              radarChartData.datasets.splice(key3, 1)
              this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
              this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
              this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
              this.addNotification()
              return false
            }
          }
        }

        this.$axios.get(`/analysises`, {
          params: {
            req: 'stdt',
            user_id: row.user_id,
            batch_id: this.selectedBatch.batch_id
          }
        }).then(res => {
          let Alltotal = 0
          let Subtotal = 0
          let data = []
          for (var key in res.data.query) {
            Subtotal = 0
            // this.barChartData.labels.push(res.data.query[key].cpt_title)
            for (var idx in JSON.parse(res.data.query[key].VALS)) {
              Subtotal += Number(JSON.parse(res.data.query[key].VALS)[idx])
            }
            // data.push(Subtotal)
            Alltotal += Subtotal
          }
          data.unshift(Alltotal)
          this.barChartData.datasets.push({
            label: `${row.name}(${row.user_no})`,
            borderColor: this.graphColor,
            backgroundColor: this.graphColor,
            data: [Alltotal],
            borderWidth: 2
          })
          this.barOptions.scales.yAxes[0].ticks.max = Alltotal + (100 - Alltotal)
          this.$nextTick(() => {
            this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
            this.addNotification()
          })
          this.$axios.get(`/analysises`, {
            params: {
              req: 'stdt_perc_rank',
              user_id: row.user_id
            }
          }).then(res => {
            let count = res.data.query[0].COUNT
            let perc = 100 - ((res.data.query[0].RANK * 100) / count)
            this.barChartData2.datasets.push({
              label: `${row.name}(${row.user_no})`,
              borderColor: this.graphColor,
              backgroundColor: this.graphColor,
              data: [Number(perc)],
              borderWidth: 2
            })
            this.barOptions.scales.yAxes[0].ticks.max = perc + (100 - perc)
            this.$nextTick(() => {
              this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
              this.addNotification()
            })
          })
        })
        // 특정 학생 분기별 총합
        this.$axios.get(`/analysises`, {
          params: {
            req: 'stdt',
            user_id: row.user_id,
            batch_id: this.selectedBatch.batch_id
          }
        }).then(res => {
          let cpt1 = 0
          let cpt2 = 0
          let cpt3 = 0
          let cpt4 = 0
          let cpt5 = 0
          let cpt6 = 0
          let cpt7 = 0
          let cpt8 = 0
          let preTotal = 0
          for (var key in res.data.query) {
            preTotal = 0
            for (var idx in JSON.parse(res.data.query[key].VALS)) {
              preTotal += JSON.parse(res.data.query[key].VALS)[idx]
            }
            switch (res.data.query[key].cpt_id) {
              case 1:
                cpt1 += preTotal
                break
              case 2:
                cpt2 += preTotal
                break
              case 3:
                cpt3 += preTotal
                break
              case 4:
                cpt4 += preTotal
                break
              case 5:
                cpt5 += preTotal
                break
              case 6:
                cpt6 += preTotal
                break
              case 7:
                cpt7 += preTotal
                break
              case 8:
                cpt8 += preTotal
                break
            }
          }
          this.radarChartData.datasets.push({
            label: `${row.name}(${row.user_no})`,
            borderWidth: 2,
            pointBackgroundColor: this.graphColor,
            borderColor: this.graphColor,
            data: [cpt1, cpt2, cpt3, cpt4, cpt5, cpt6, cpt7, cpt8]
          })
          this.$nextTick(() => {
            this.$refs.radarchart.renderChart(this.radarChartData, this.radarOptions)
          })
        })
      }
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
    onChangePage2 (page) {
      this.$refs.vuetable2.changePage(page)
    },
    searchUser () {
      this.appendParams = {
        user_no: this.keyword
      }
      this.isUserSearchOn = true
      this.$nextTick(() => {
        this.$refs.vuetable.reload()
      })
    },
    viewUserAll () {
      this.appendParams = {}
      this.isUserSearchOn = false
      this.$nextTick(() => {
        this.$refs.vuetable.reload()
      })
    },
    step2Value (value) {
      this.step2_on = true
      // 현재 아래는 테스틑용(0: 학과, 1: 단대, 2: 학생)
      this.SubTable.type = value
      this.barChartData.datasets.splice(1, this.barChartData.datasets.length)
      this.barChartData2.datasets.splice(1, this.barChartData2.datasets.length)
      this.$refs.barchart.renderChart(this.barChartData, this.barOptions)
      this.$refs.barchart2.renderChart(this.barChartData2, this.barOptions)
      switch (value) {
        case '학과':
          this.SubTable.apiUrl = '/groups/paginate'
          this.SubTable.fields = [
            {
              name: 'dept',
              title: '학과 이름'
            }
          ]
          this.SubTable.Subkey = 'dept'
          this.appendParams2 = {
            req: 'dept'
          }
          this.$nextTick(() => {
            this.$refs.vuetable2.normalizeFields()
            this.$refs.vuetable2.refresh()
          })
          break
        case '전공':
          this.SubTable.apiUrl = '/groups/paginate'
          this.SubTable.fields = [
            {
              name: 'major',
              title: '전공 이름'
            }
          ]
          this.SubTable.Subkey = 'major'
          this.appendParams2 = {
            req: 'major'
          }
          this.$nextTick(() => {
            this.$refs.vuetable2.normalizeFields()
            this.$refs.vuetable2.refresh()
          })
          break
        case '학생':
          this.SubTable.apiUrl = '/users/paginate'
          this.SubTable.fields = [
            {
              name: 'user_no',
              title: '유저ID'
            },
            {
              name: 'sta',
              title: '학적상태명'
            },
            {
              name: 'stdyear',
              title: '학년'
            },
            {
              name: 'dept',
              title: '소속(학과)명'
            }
          ]
          this.SubTable.Subkey = 'user_no'
          this.appendParams2 = {
            without_no: this.barChartData.datasets[0].label
          }
          this.$nextTick(() => {
            this.$refs.vuetable2.normalizeFields()
            this.$refs.vuetable2.refresh()
          })
          break
      }
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
