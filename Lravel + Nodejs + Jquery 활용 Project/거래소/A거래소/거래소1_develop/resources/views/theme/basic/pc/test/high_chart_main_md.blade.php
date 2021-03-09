@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div id="sports_coin_chart" style="height: 226px; width: 601px"></div>
<div id="public_coin_chart" style="height: 226px; width: 601px"></div>

<script>

$(document).ready(function(){

    sports_coin();
    public_coin();

    var main_md_option = {
        title: {
            enabled: false,
            text: ''
        },
        legend: {
            enabled: false
        },
        tooltip: {
            //enabled: false,
        },
        scrollbar: {
            enabled: false
        },
        exporting: { enabled: false },
        yAxis: {
            //min: 1,
            //max: 9,
            labels: {
                enabled: false,
            },
            lineWidth:1,
            gridLineWidth: 0,
            gridLineColor: '#ddd',
            lineColor: '#ddd', // x좌표 맨밑에 가로선 하얀색으로 변경
        },
        xAxis: {
            minRange: 8400000,//1day
            range: 24 * 3600 * 1000,
            minTickInterval : 3600000, 
            tickInterval : 3600000 * 6,
            enabled: false,
            labels: {
                enabled: true,
            },
            gridLineWidth: 1, // 그리드 세로선 굵기
            gridLineColor: '#ddd', // 그리드 세로선 색상
            lineWidth:1, // x좌표 굵기
            lineColor: 'transparent', // x좌표 맨밑에 가로선 하얀색으로 변경
        },
        plotOptions: { //hover시 데이터를 표시해주는 박스 안 속성
            area: {
                lineWidth: 1,
                fillColor: 'rgba(124,181,236,0.2)',
                lineColor: '#5795f1',
                enableMouseTracking: true
            },
        series: {
            color: '#ff9900',
            lineColor: '#00b9ff',
            lineWidth: 2,
        }
        },
        credits: {
            enabled: false
        },
        navigator: {
            enabled: false
        },
        rangeSelector: {
            enabled: false
        },

        series: [{
            name: '마지막 체결가',
            //data: data,
            type: 'areaspline',
            threshold: null,
            tooltip: {
                valueDecimals: 2
            },
            fillColor: {
                linearGradient: {
                    x1: 0,
                    y1: 0,
                    x2: 0,
                    y2: 1
                },
                stops: [
                    [0, Highcharts.getOptions().colors[0]],
                    [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                ]
            },
        }]
    };

    function sports_coin(coin = 'mu'){
        $.getJSON('/test/chart', function (data) {
            // Create the chart
            main_md_option.series[0].data = data;
            var main_md_chart = Highcharts.stockChart('sports_coin_chart', main_md_option);
        });
    }

    function public_coin(coin = 'btc'){
        $.getJSON('/test/chart', function (data) {
            // Create the chart
            main_md_option.series[0].data = data;
            var main_md_chart = Highcharts.stockChart('public_coin_chart', main_md_option);
        });
    }

});
</script>

@endsection