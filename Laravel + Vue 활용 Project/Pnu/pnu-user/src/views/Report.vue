<template>
  <div>
    <section id="pusan--report" class="pusan_contents pusan_report_contents">
      <div class="bg-symbol"></div>
      <div class="report_content">
        <!-- active로 화살표 모양과 ul 디스플레이 제어 -->
        <div
          v-on-clickaway="away"
          class="_graph_choice"
          :class="{active: isSelectOpened}"
          @click="isSelectOpened = !isSelectOpened"
        >{{options[selectedIndex].name}}</div>
        <ul class="_graph_choice_box">
          <!-- active로 본인 상태 변경 -->
          <li
            v-for="(option, index) in options"
            :key="index"
            class="_graph_choice_btn"
            :class="{active: selectedIndex === index}"
          >
            <a href="#" @click.prevent="selectOption(index, option.value)">{{option.name}}</a>
          </li>
        </ul>
        <div class="report_graph">
          <div class="graph">
            <!--그래프 들어올 자리-->
            <radar-chart v-if="isLoaded" :chart-data="chartData" :options="chartOptions" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import { mixin as clickaway } from 'vue-clickaway'

export default {
  name: 'report',
  mixins: [clickaway],
  components: {
  },
  data () {
    return {
      isSelectOpened: false,
      isLoaded: false,
      selectedIndex: 0,
      selectedValue: null,
      options: [
        {
          name: `${this.$moment().format('YYYY')}년 그래프`,
          value: 1
        },
        {
          name: '연간 변화 비교',
          value: 2
        },
        {
          name: '전체 재학생 평균 비교',
          value: 3
        },
        {
          name: '단과대학 평균 비교',
          value: 4
        },
        {
          name: '학과 평균 비교',
          value: 5
        },
        {
          name: '학년 평균 비교',
          value: 6
        }
      ],
      chartData: {},
      chartOptions: {}
    }
  },
  async created () {
    this.selectedValue = this.options[this.selectedIndex].value
  },
  async mounted () {
    setTimeout(() => {
      this.isChecked = true
    }, 150)
    await this.test()
  },
  methods: {
    away () {
      if (this.isSelectOpened) {
        this.isSelectOpened = false
      }
    },
    async selectOption (index, value) {
      this.selectedIndex = index
      this.selectedValue = value
      await this.test()
    },
    async test () {
      try {
        this.isLoaded = false

        const res = await this.$axios
          .get(`/user_cp_test_results/${this.selectedValue}`, {})
          .then(res => res.data)

        switch (this.selectedValue) {
          case 1:
            this.drawResult1(res)
            break
          case 2:
            this.drawResult2(res)
            break
          case 3: {
            const res0 = await this.$axios
              .get('/user_cp_test_results/1')
              .then(res => res.data)
            this.drawResult3(res, res0)
            break
          }
          case 4: {
            const res0 = await this.$axios
              .get('/user_cp_test_results/1')
              .then(res => res.data)
            this.drawResult4(res, res0)
            break
          }
          case 5: {
            const res0 = await this.$axios
              .get('/user_cp_test_results/1')
              .then(res => res.data)
            this.drawResult5(res, res0)
            break
          }
          case 6: {
            const res0 = await this.$axios
              .get('/user_cp_test_results/1')
              .then(res => res.data)
            this.drawResult6(res, res0)
            break
          }
        }
      } catch (e) {
        console.log(e)
        alert('정보를 불러오는 중 오류가 발생했습니다')
      } finally {
        this.isLoaded = true
      }
    },
    drawResult1 (res) {
      const labels = []
      const datas = []
      res.forEach(element => {
        labels.push([element.cpt_title])
        datas.push(Number(element.VALS) / element.count)
      })

      this.chartOptions = {
        tooltips: {
          mode: 'point',
          yPadding: 10,
          xPadding: 12,
          titleMarginBottom: 15,
          displayColors: false,
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          callbacks: {
            title (tooltipItems, data) {
              return '점수'
            },
            label (tooltipItem, data) {
              return data.labels[tooltipItem.index].join(' ') + String(' ').repeat(20) + tooltipItem.value
            }
          }
        },
        scale: {
          pointLabels: {
            fontColor: 'white',
            fontSize: 12.5
          },
          gridLines: {
            circular: true,
            color: 'rgba(255, 255, 255, 0.3)'
          },
          angleLines: {
            display: false
          },
          ticks: {
            display: false,
            beginAtZero: true
          }
        },
        legend: {
          display: false
        }
      }

      this.chartData = {
        labels,
        datasets: [
          {
            label: '',
            fill: false,
            data: datas,
            borderWidth: 5,
            lineTension: 0,
            borderColor: 'white',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          }
        ]
      }
    },
    drawResult2 (res) {
      const group = Object.entries(res.reduce((acc, obj) => {
        acc[obj.batch_id] = acc[obj.batch_id] || []
        acc[obj.batch_id].push(obj)
        return acc
      }, {}))

      const results = group.map(([, data]) => {
        return data.map(step => {
          let sum = 0
          step.ucpt_answer.forEach(page => {
            page.forEach(answer => {
              sum += answer.value
            })
          })

          return {
            title: [...step.cpt_title.split(' ').filter(x => x)],
            batch: step.batch_name,
            value: sum
          }
        })
      })

      this.chartOptions = {
        tooltips: {
          mode: 'point',
          yPadding: 10,
          xPadding: 12,
          titleMarginBottom: 15,
          displayColors: false,
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          callbacks: {
            title (tooltipItems, data) {
              return '점수'
            },
            label (tooltipItem, data) {
              const label = data.labels[tooltipItem.index]
              const dataset = data.datasets[tooltipItem.datasetIndex]
              return `${label.join(' ')}(${dataset.label}) ${String(' ').repeat(20)}${tooltipItem.value}`
            }
          }
        },
        scale: {
          pointLabels: {
            fontColor: 'white',
            fontSize: 12.5
          },
          gridLines: {
            circular: true,
            color: 'rgba(255, 255, 255, 0.3)'
          },
          angleLines: {
            display: false
          },
          ticks: {
            display: false,
            beginAtZero: true
          }
        },
        legend: {
          display: false
        }
      }

      this.chartData = {
        labels: (results[0] || []).map(x => x.title),
        datasets: results.map((result, index) => {
          return {
            label: result[index].batch,
            fill: false,
            data: result.map(x => x.value),
            borderColor: ['#FF8A65', '#FFB74D', '#FFD54F', '#FFF176', '#DCE775', '#AED581 ', '#81C784', '#4DB6AC', '#4DD0E1'][index % results.length],
            borderWidth: 5,
            lineTension: 0,
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'white',
            pointHoverBorderWidth: 3
          }
        })
      }
    },
    drawResult3 (res, res0) {
      this.chartOptions = {
        tooltips: {
          mode: 'point',
          yPadding: 10,
          xPadding: 12,
          titleMarginBottom: 15,
          displayColors: false,
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          callbacks: {
            title (tooltipItems, data) {
              return '점수'
            },
            label (tooltipItem, data) {
              return String(data.labels[tooltipItem.index] + data.datasets[tooltipItem.datasetIndex].label).padEnd(20, ' ') + tooltipItem.value
            }
          }
        },
        scale: {
          pointLabels: {
            fontColor: 'white',
            fontSize: 12.5
          },
          gridLines: {
            circular: true,
            color: 'rgba(255, 255, 255, 0.3)'
          },
          angleLines: {
            display: false
          },
          ticks: {
            display: false,
            beginAtZero: true
          }
        },
        legend: {
          display: false
        }
      }
      const labels = []
      const datas = []
      res.forEach(element => {
        labels.push([element.cpt_title])
        datas.push(Number(Number(element.VALS) / element.count).toFixed(2))
      })

      const datas2 = []
      res0.forEach(element => {
        datas2.push(Number(element.VALS) / element.count)
      })

      this.chartData = {
        labels,
        datasets: [
          {
            label: '',
            fill: false,
            data: datas,
            borderWidth: 5,
            lineTension: 0,
            borderColor: 'white',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          },
          {
            label: '(본인)',
            fill: false,
            data: datas2,
            borderWidth: 5,
            lineTension: 0,
            borderColor: '#FF8A65',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          }
        ]
      }
      /* console.log(res) */
      /* this.chartData = {
        labels: result.map(x => x.title),
        datasets: [
          {
            label: '',
            fill: false,
            data: result.map(x => x.value),
            borderWidth: 5,
            lineTension: 0,
            borderColor: 'white',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          }
        ]
      } */
    },
    drawResult4 (res, res0) {
      this.chartOptions = {
        tooltips: {
          mode: 'point',
          yPadding: 10,
          xPadding: 12,
          titleMarginBottom: 15,
          displayColors: false,
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          callbacks: {
            title (tooltipItems, data) {
              return '점수'
            },
            label (tooltipItem, data) {
              return String(data.labels[tooltipItem.index] + data.datasets[tooltipItem.datasetIndex].label).padEnd(20, ' ') + tooltipItem.value
            }
          }
        },
        scale: {
          pointLabels: {
            fontColor: 'white',
            fontSize: 12.5
          },
          gridLines: {
            circular: true,
            color: 'rgba(255, 255, 255, 0.3)'
          },
          angleLines: {
            display: false
          },
          ticks: {
            display: false,
            beginAtZero: true
          }
        },
        legend: {
          display: false
        }
      }

      const labels = []
      const datas = []
      res.forEach(element => {
        labels.push([element.cpt_title])
        datas.push(Number(element.VALS) / element.count)
      })

      const datas2 = []
      res0.forEach(element => {
        datas2.push(Number(element.VALS) / element.count)
      })

      this.chartData = {
        labels: labels,
        datasets: [
          {
            label: '',
            fill: false,
            data: datas,
            borderWidth: 5,
            lineTension: 0,
            borderColor: 'white',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          },
          {
            label: '(본인)',
            fill: false,
            data: datas2,
            borderWidth: 5,
            lineTension: 0,
            borderColor: '#FF8A65',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          }
        ]
      }
    },
    drawResult5 (res, res0) {
      this.chartOptions = {
        tooltips: {
          mode: 'point',
          yPadding: 10,
          xPadding: 12,
          titleMarginBottom: 15,
          displayColors: false,
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          callbacks: {
            title (tooltipItems, data) {
              return '점수'
            },
            label (tooltipItem, data) {
              return String(data.labels[tooltipItem.index] + data.datasets[tooltipItem.datasetIndex].label).padEnd(20, ' ') + tooltipItem.value
            }
          }
        },
        scale: {
          pointLabels: {
            fontColor: 'white',
            fontSize: 12.5
          },
          gridLines: {
            circular: true,
            color: 'rgba(255, 255, 255, 0.3)'
          },
          angleLines: {
            display: false
          },
          ticks: {
            display: false,
            beginAtZero: true
          }
        },
        legend: {
          display: false
        }
      }

      const labels = []
      const datas = []
      res.forEach(element => {
        labels.push([element.cpt_title])
        datas.push(Number(element.VALS) / element.count)
      })

      const datas2 = []
      res0.forEach(element => {
        datas2.push(Number(element.VALS) / element.count)
      })

      this.chartData = {
        labels: labels,
        datasets: [
          {
            label: '',
            fill: false,
            data: datas,
            borderWidth: 5,
            lineTension: 0,
            borderColor: 'white',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          },
          {
            label: '(본인)',
            fill: false,
            data: datas2,
            borderWidth: 5,
            lineTension: 0,
            borderColor: '#FF8A65',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          }
        ]
      }
    },
    drawResult6 (res, res0) {
      this.chartOptions = {
        tooltips: {
          mode: 'point',
          yPadding: 10,
          xPadding: 12,
          titleMarginBottom: 15,
          displayColors: false,
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          callbacks: {
            title (tooltipItems, data) {
              return '점수'
            },
            label (tooltipItem, data) {
              return String(data.labels[tooltipItem.index] + data.datasets[tooltipItem.datasetIndex].label).padEnd(20, ' ') + tooltipItem.value
            }
          }
        },
        scale: {
          pointLabels: {
            fontColor: 'white',
            fontSize: 12.5
          },
          gridLines: {
            circular: true,
            color: 'rgba(255, 255, 255, 0.3)'
          },
          angleLines: {
            display: false
          },
          ticks: {
            display: false,
            beginAtZero: true
          }
        },
        legend: {
          display: false
        }
      }

      const labels = []
      const datas = []
      res.forEach(element => {
        labels.push([element.cpt_title])
        datas.push(Number(element.VALS) / element.count)
      })

      const datas2 = []
      res0.forEach(element => {
        datas2.push(Number(element.VALS) / element.count)
      })

      this.chartData = {
        labels: labels,
        datasets: [
          {
            label: '',
            fill: false,
            data: datas,
            borderWidth: 5,
            lineTension: 0,
            borderColor: 'white',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          },
          {
            label: '(본인)',
            fill: false,
            data: datas2,
            borderWidth: 5,
            lineTension: 0,
            borderColor: '#FF8A65',
            pointRadius: 0,
            borderJoinStyle: 'round',
            pointHitRadius: 20,
            pointHoverRadius: 6,
            pointHoverBackgroundColor: 'black',
            pointHoverBorderWidth: 3
          }
        ]
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../styles/scss/report.scss";
@import "../styles/scss/layout/responsive.scss";

#pusan--report {
  max-width: initial;
}

.graph {
  position: relative;
  z-index: 1;
}
</style>
