$(document).ready(function(){

    var market_sm_option = {
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
            lineWidth:0,
            gridLineWidth: 0,
            gridLineColor: '#fff',
            lineColor: '#fff', // x좌표 맨밑에 가로선 하얀색으로 변경
        },
        xAxis: {
            minRange: 8400000,//1day
            range: 24 * 3600 * 1000,
            minTickInterval : 3600000, 
            tickInterval : 3600000,
            enabled: false,
            labels: {
                enabled: false,
            },
            minorTickLength: 0,
            tickLength: 0,
            gridLineWidth: 0, // 그리드 세로선 굵기
            gridLineColor: '#fff', // 그리드 세로선 색상
            lineWidth:0, // x좌표 굵기
            lineColor: 'transparent', // x좌표 맨밑에 가로선 하얀색으로 변경
        },
        plotOptions: { //hover시 데이터를 표시해주는 박스 안 속성
            area: {
                lineWidth: 0,
                fillColor: 'rgba(124,181,236,0.2)',
                lineColor: '#5795f1',
                enableMouseTracking: false
            },
        series: {
            color: 'transparent',
            lineColor: '#00b9ff',
            lineWidth: 1,
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

    $.getJSON('/chartdata', function (data) {
        
            var coin_symbol = $('#mini_chart').data("coin");
            
            if(typeof data[coin_symbol] !== 'undefined'){
                market_sm_option.series[0].data = data[coin_symbol];
                var market_sm_chart = Highcharts.stockChart('mini_chart', market_sm_option);
            }else{
                market_sm_option.series[0].data = [[1555057344000,0],[1555017344000,0]];
                var market_sm_chart = Highcharts.stockChart('mini_chart', market_sm_option);
            }
    });

    /*$.getJSON('/test/chart', function (data) {
        // Create the chart
        market_sm_option.series[0].data = data;
        var market_sm_chart = Highcharts.stockChart('mini_chart', market_sm_option);
    });*/
});