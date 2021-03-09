<template>
  <div>
    <b-row>
      <b-colxx xxs="12">
        <piaf-breadcrumb :heading="$t('menu.descdeptcd')" />
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
      <b-colxx xxs="6">
        <b-card class="mb-4" :title="$t('menu.descdeptcd')">
          <b-row style="height: 500px;">
            <b-colxx xxs="12" lg="12" class="mb-4" style="overflow-y: auto;">
              <div class="chart-container">
                  <horizontal-bar :data="horizontalChartData" :height="400" ref="horizontalchart" />
                  <!-- <div v-for="(rank, index) in rankResult" :key="index">
                    <div >{{(index+1)}}순위 :
                      <p style="font-size:1.5em;font-weight:600;display:inline-block;">{{rank.name}}</p>
                      <p style="font-size:1.3em;display:inline-block;">/ 점수 : {{rank.VALS}} 점</p>
                    </div>
                  </div> -->
              </div>
            </b-colxx>
          </b-row>
        </b-card>
      </b-colxx>
      <b-colxx xxs="6" class="mb-4">
        <b-card>
          <b-row>
            <h3 class="mb-4" style="color:#0078FF; font-size: 1.5em;font-weight:bold;width:100%">Step 1 : 학과를 클릭하세요</h3>
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
import Vuetable from 'vuetable-2/src/components/Vuetable'
import VuetablePaginationBootstrap from '../../../components/Common/VuetablePaginationBootstrap'
import {
  horizontalChartData
} from '../../../data/charts'
import { horizontalChartOptions } from '../../../components/Charts/config'
import HorizontalBar from '../../../components/Charts/HorizontalBar'

export default {
  name: 'Descdeptcd',
  components: {
    Vuetable,
    VuetablePaginationBootstrap,
    'horizontal-bar': HorizontalBar
  },
  data () {
    return {
      batchList: [],
      selectedBatch: {},
      MainTable: {
        apiUrl: '/groups/paginate',
        fields: [
          {
            name: 'dept',
            title: '학과 이름'
          }
        ]
      },
      appendParams: {
        req: 'dept'
      },
      rankResult: [],
      keyword: '',
      graphColor: '#' + (Math.random() * 0xFFFFFF << 0).toString(16),
      isDeptSearchOn: false,
      horizontalChartData,
      hChartOption: horizontalChartOptions
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
    // await this.loadBatch()
  },
  methods: {
    async loadBatch () {
      this.horizontalChartData.labels = []
      this.horizontalChartData.datasets = []
      /* await this.$axios.get(`/batches`, {}).then(res => {
        for (var key in res.data) {
          this.barChartData.labels.push(res.data[key].batch_name)
          this.barChartData2.labels.push(res.data[key].batch_name)
        }
      }) */
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
    openEdit (row) {
      this.horizontalChartData.datasets = []
      this.horizontalChartData.labels = [this.selectedBatch.batch_id + '차수']
      this.$axios.get(`/analysises`, {
        params: {
          req: 'descdeptcd',
          batch: this.selectedBatch.batch_id,
          deptcd: row.deptcd
        }
      }).then(res => {
        res.data.query.forEach(element => {
          this.generator()
          this.horizontalChartData.datasets.push({
            label: element.name,
            borderColor: this.graphColor,
            backgroundColor: this.graphColor,
            data: [Number(element.VALS)],
            borderWidth: 2
          })
        })
        this.$refs.horizontalchart.renderChart(this.horizontalChartData, this.hChartOption)
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
