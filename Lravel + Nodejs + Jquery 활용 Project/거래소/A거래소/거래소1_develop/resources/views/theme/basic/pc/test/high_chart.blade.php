

@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div id="containers" style="height: 400px; min-width: 310px"></div>

<script>
$.getJSON('/test/chart', function (data) {

// Create the chart
Highcharts.stockChart('containers', {
    title: {
        enabled: false,
        text: ''
    },
    legend: {
        enabled: false
    },
    tooltip: {
        enabled: false,
        crosshairs: false
    },
    exporting: { enabled: false },
    yAxis: {
        //min: 1,
        //max: 9,
        labels: {
            enabled: false,
        },
        lineWidth:0,
        gridLineWidth: 0,
        gridLineColor: '#ddd',
        lineColor: '#ddd', // x좌표 맨밑에 가로선 하얀색으로 변경
    },
    xAxis: {
        minRange: 8400000,//1day
        range: 24 * 3600 * 1000,
        minTickInterval : 3600000, 
        tickInterval : 3600000,
        enabled: true,
        labels: {
            enabled: true,
        },
        gridLineWidth: 0, // 그리드 세로선 굵기
        gridLineColor: '#ddd', // 그리드 세로선 색상
        lineWidth:0, // x좌표 굵기
        lineColor: '#ffffff', // x좌표 맨밑에 가로선 하얀색으로 변경
    },
    plotOptions: { //hover시 데이터를 표시해주는 박스 안 속성
        area: {
            lineWidth: 1,
            fillColor: 'rgba(124,181,236,0.2)',
            lineColor: '#5795f1',
            enableMouseTracking: false
        },
    series: {
        color: '#ff9900',
        lineColor: '#ffffff',
        states: {
            hover: {
                enabled: false
            }
        }
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
        name: 'AAPL Stock Price',
        data: data,
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
});
});
</script>

@endsection