$(document).ready(function() {
    sports_coin();
    public_coin();

    main_md_option = {
        title: {
            enabled: false,
            text: ""
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
                enabled: false
            },
            lineWidth: 1,
            gridLineWidth: 0,
            gridLineColor: "#ddd",
            lineColor: "#ddd" // x좌표 맨밑에 가로선 하얀색으로 변경
        },
        xAxis: {
            minRange: 8400000, //1day
            range: 24 * 3600 * 1000,
            minTickInterval: 3600000,
            tickInterval: 3600000 * 6,
            enabled: false,
            labels: {
                enabled: true
            },
            gridLineWidth: 1, // 그리드 세로선 굵기
            gridLineColor: "#ddd", // 그리드 세로선 색상
            lineWidth: 1, // x좌표 굵기
            lineColor: "transparent" // x좌표 맨밑에 가로선 하얀색으로 변경
        },
        plotOptions: {
            //hover시 데이터를 표시해주는 박스 안 속성
            area: {
                lineWidth: 1,
                fillColor: "rgba(124,181,236,0.2)",
                lineColor: "#5795f1",
                enableMouseTracking: true
            },
            series: {
                color: "#ff9900",
                lineColor: "#00b9ff",
                lineWidth: 2
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

        series: [
            {
                name: "마지막 체결가",
                //data: data,
                type: "areaspline",
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
                        [
                            1,
                            Highcharts.Color(Highcharts.getOptions().colors[0])
                                .setOpacity(0)
                                .get("rgba")
                        ]
                    ]
                }
            }
        ]
    };
});
var main_md_option;
function sports_coin(
    coin = '{"api":"mnu","symbol":"MNU","last_trade_price_krw":"","price_change_24h_krw":"","percent_change_24h_krw":"","image":"MNU.png"}'
) {
    var coin_object = JSON.parse(coin);
    $("#check01").prop("checked", false);
    $.getJSON("/chartdata", function(data) {
        // Create the chart

        if (coin_object.last_trade_price_krw != "") {
            //console.log(__.coin_name[coin_object.api]);
            $("#chart_sports_img").attr(
                "src",
                "/images/coin_img/" + coin_object.image.toLowerCase()
            );
            var coinname = ''+__.coin_name[coin_object.api]+'';
            coinname = coinname.replace('&lt;small&gt;Fanclub Coin&lt;/small&gt;', '');
            $("#chart_sports_coinname").text(coinname);
            $("#chart_sports_symbol").text(coin_object.symbol + "/KRW");
            $("#chart_sports_price")
                .text(
                    numberWithCommas(
                        parseInt(coin_object.last_trade_price_krw).toFixed(0)
                    )
                )
                .attr("data-coin", coin_object.api);

            var numPriceChange = Number(
                coin_object.price_change_24h_krw.replace(/\,/g)
            );
            var numPercentChange = Number(
                coin_object.percent_change_24h_krw.replace(/\,/g)
            );
            var str = "";
            if (numPriceChange < 0) {
                str =
                    '<b class="percent_down_color">' +
                    numberWithCommas(numPriceChange.toFixed(0)) +
                    " (" +
                    numPercentChange.toFixed(2) +
                    "%) ▼</b>";
            } else if (numPriceChange > 0) {
                str =
                    '<b class="percent_up_color">+' +
                    numberWithCommas(numPriceChange.toFixed(0)) +
                    " (+" +
                    numPercentChange.toFixed(2) +
                    "%) ▲</b>";
            } else {
                str =
                    "<b>" +
                    numPriceChange.toFixed(0) +
                    " (" +
                    numPercentChange.toFixed(2) +
                    "%)</b>";
            }
            $("#chart_sports_change")
                .attr("data-coin", coin_object.api)
                .html(str);
        }

        main_md_option.series[0].data = data[coin_object.api];
        var main_md_chart = Highcharts.stockChart(
            "sports_coin_chart",
            main_md_option
        );
    });
}

function public_coin(
    coin = '{"api":"eth","symbol":"ETH","last_trade_price_krw":"","price_change_24h_krw":"","percent_change_24h_krw":"","image":"ETH.png"}'
) {
    var coin_object = JSON.parse(coin);
    $("#check02").prop("checked", false);
    $.getJSON("/chartdata", function(data) {
        // Create the chart

        if (coin_object.last_trade_price_krw != "") {
            console.log(__.coin_name[coin_object.api]);
            $("#chart_public_img").attr(
                "src",
                "/images/coin_img/" + coin_object.image.toLowerCase()
            );
            $("#chart_public_coinname").html(__.coin_name[coin_object.api]);
            $("#chart_public_symbol").text(coin_object.symbol + "/KRW");
            $("#chart_public_price")
                .text(
                    numberWithCommas(
                        parseInt(coin_object.last_trade_price_krw).toFixed(0)
                    )
                )
                .attr("data-coin", coin_object.api);

            var numPriceChange = Number(
                coin_object.price_change_24h_krw.replace(/\,/g)
            );
            var numPercentChange = Number(
                coin_object.percent_change_24h_krw.replace(/\,/g)
            );
            var str = "";
            if (numPriceChange < 0) {
                str =
                    '<b class="percent_down_color">' +
                    numberWithCommas(numPriceChange.toFixed(0)) +
                    " (" +
                    numPercentChange.toFixed(2) +
                    "%) ▼</b>";
            } else if (numPriceChange > 0) {
                str =
                    '<b class="percent_up_color">+' +
                    numberWithCommas(numPriceChange.toFixed(0)) +
                    " (+" +
                    numPercentChange.toFixed(2) +
                    "%) ▲</b>";
            } else {
                str =
                    "<b>" +
                    numPriceChange.toFixed(0) +
                    " (" +
                    numPercentChange.toFixed(2) +
                    "%)</b>";
            }
            $("#chart_public_change")
                .attr("data-coin", coin_object.api)
                .html(str);
        }

        main_md_option.series[0].data = data[coin_object.api];
        var main_md_chart = Highcharts.stockChart(
            "public_coin_chart",
            main_md_option
        );
    });
}
