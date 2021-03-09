<template>
  <div class="ai_wrapper">
    <header-component
      leftButton="back"
      leftButtonRoute="/home"
      center="text"
      :centerText="__('revenue.revenue_view')"
      rightButton="home"
    ></header-component>
    <div
      id="curve_chart"
      style="width:100%;"
    ></div>
    <div class="trst-container bgcolor scroll_area">
      <ul
        v-if="revenue_lists.length > 0"
        class="benefit-info-table"
      >
        <li
          v-for="revenue_list in revenue_lists"
          v-bind:key="revenue_list.id"
          class="benefit-list"
        >
          <h6 class="bnf-title">{{revenue_list.date}} {{__('revenue.history')}}</h6>
          <span class="bnf-date">{{revenue_list.date}}.01</span>
          <dl class="bnf-detail-list">
            <dt class="bnf-detail-list-tit">
              <span>{{revenue_list.date}} {{__('revenue.invest_revenue')}}</span>
            </dt>
            <dd class="bnf-detail-list-text">
              <b>{{numberWithCommas(revenue_list.revenue)}}</b><strong>$</strong>
            </dd>
          </dl>
          <dl class="bnf-detail-list">
            <dt class="bnf-detail-list-tit">
              <span>{{__('revenue.my_coin_retention')}}</span>
            </dt>
            <dd class="bnf-detail-list-text">
              <b>{{numberWithCommas(revenue_list.coin_retention)}}</b><strong>%</strong>
            </dd>
          </dl>
          <dl class="bnf-detail-list">
            <dt class="bnf-detail-list-tit">
              <span>{{__('revenue.fee')}}</span>
            </dt>
            <dd class="bnf-detail-list-text">
              <b>{{numberWithCommas(revenue_list.fee)}}</b><strong>$</strong>
            </dd>
          </dl>
          <dl class="bnf-detail-list">
            <dt class="bnf-detail-list-tit">
              <span>{{__('revenue.my_invest_revenue')}}</span>
            </dt>
            <dd class="bnf-detail-list-text">
              <b>{{numberWithCommas(revenue_list.return_invest)}}</b><strong>$</strong>
            </dd>
          </dl>
        </li>
      </ul>
      <ul
        v-else
        class="benefit-info-table"
      >
        <!-- 내역이 존재하지 않을 때 -->
        <li class="nothing-benefit-list">
          <img
            src="/images/trst-images/icon/icon_empty.svg"
            alt="empty icon"
          >
          <p>{{__('revenue.none_data')}}</p>
        </li>
        <!-- END 내역이 존재하지 않을 때 -->
      </ul>
    </div>
  </div>
</template>

<script>
  import HeaderComponent from "../components/common/HeaderComponent";

  export default {
    components: {
      "header-component": HeaderComponent
    },
    data() {
      return {
        revenue_lists: {},
        all_revenues: []
      };
    },
    created() {
      this.fetch_data()
    },
    async mounted() {
      await this.$nextTick()
      google.charts.setOnLoadCallback(this.drawMultSeries())
    },
    computed: {

    },
    methods: {
      async fetch_data() {
        try {
          this.$store.commit("progressComponentShow");
          this.revenue_lists = (await axios.get(`/api/revenue/user_revenue`)).data;
        } catch (e) {
          console.log(e);
        } finally {
          this.$store.commit("progressComponentHide");
        }
      },
      numberWithCommas(x) {
        const x_temp = x.split(".");
        const decimal = x_temp[1];
        let integer = x_temp[0];

        integer = integer.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        x_temp[0] = integer;

        return x_temp[0] + '.' + x_temp[1];
      },
      async drawMultSeries() {
        let arr = [['DATE', 'Price']]
        const all_revenues = (await axios.get(`/api/revenue/monthly_revenue`)).data;
        all_revenues.forEach(function (revenue) {
          arr.push([revenue.date, revenue.revenue])
        });

        const data = google.visualization.arrayToDataTable(arr)

        const options = {
          title: '',
          chartArea: { width: '50%' },
          hAxis: {
            title: ''
          },
          vAxis: {
            title: ''
          },
          legend: { position: "none" },
          hAxis: { textPosition: 'none' },
          width: $(window).width(),
          height: 135,
          chartArea: { width: $(window).width(), left: 0, top: 0, height: 135 }
        }

        const chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    }
  }
</script>

<style scoped>
  .ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
  }

  .trst-container {
    padding: 0;
  }

  .benefit-info-table {
    font-weight: 300;
    font-size: 13px;
  }

  .benefit-list {
    position: relative;
    border-bottom: 1px solid #ebf0f0;
    padding: 25px 20px;
  }

  .bnf-title {
    font-size: 18px;
    color: #1e1e1e;
    margin-bottom: 18px;
  }

  .bnf-title:before {
    content: url(/images/trst-images/icon/icon_dollar.svg);
    float: left;
    margin-right: 6px;
  }

  .bnf-date {
    position: absolute;
    top: 31px;
    right: 20px;
    color: #969696;
    font-size: 12px;
    letter-spacing: 0.2px;
    font-weight: 300;
  }

  .bnf-detail-list {
    color: #787878;
    font-weight: 300;
    margin: 0;
    line-height: 1.7;
    width: 100%;
  }

  .bnf-detail-list-tit {
    font-weight: 300;
    display: inline-block;
    line-height: 1.7;
  }

  .bnf-detail-list-text {
    color: #333333;
    letter-spacing: 0.2px;
    float: right;
    margin: 0;
    font-weight: 300;
  }

  .bnf-detail-list-text strong {
    color: #19b4aa;
    font-weight: bold;
    padding-left: 5px;
  }

  .nothing-benefit-list {
    width: 100%;
    height: 100%;
    padding: 60% 0;
    text-align: center;
    color: #505050;
  }

  .nothing-benefit-list > img {
    display: block;
    margin: 0 auto 10px;
  }
</style>
