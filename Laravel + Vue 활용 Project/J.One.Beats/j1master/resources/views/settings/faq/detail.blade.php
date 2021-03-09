@extends('header')
@section('content')

<div class="rows">
    <div class="status-bar">
        <ul>
            <li class="progres">
                <div class="content">
                    <div class="text">
                        노래 활성화
                    </div>
                </div>
            </li><!-- /.progres -->
            <li class="progres">
                <div class="content">
                    <div class="text">
                        노래 비활성화
                    </div>
                </div>
            </li><!-- /.progres -->
            <li class="progres">
                <div class="content">
                    <div class="text">
                        WAV
                    </div>
                    <!-- Audio track용 하단에 JS랑 CSS 있으니 참고, 재생 정지 아이콘은 적용해야함-->
                    MP3 (재생)
                    (정지)
                    <div id="mp3-range" class="range" style="width: 30%;"></div>
                </div>

                <audio id="player" ontimeupdate="initProgressBar()">
                    <source
                        src="https://dl-web.dropbox.com/get/Oslo.mp3?_subject_uid=199049471&amp;w=AABuDNt9BDJnaZOelVFws9FXTufkXCvAPS5SYpy_gRZ2GQ&amp;duc_id=dropbox_duc_id"
                        type="audio/mp3">
                </audio>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="text">
                        WAV
                    </div>
                    <!-- Audio track용 하단에 JS랑 CSS 있으니 참고, 재생 정지 아이콘은 적용해야함-->
                    MP3 (재생)
                    (정지)
                    <div id="wav-range" class="range"></div>
                </div>

                    <audio id="player" ontimeupdate="initProgressBar()">
                        <source
                            src="https://dl-web.dropbox.com/get/Oslo.mp3?_subject_uid=199049471&w=AABuDNt9BDJnaZOelVFws9FXTufkXCvAPS5SYpy_gRZ2GQ&duc_id=dropbox_duc_id"
                            type="audio/mp3">
                    </audio>
                </div>
        </li><!-- /.progres -->

    </ul>
</div>
</div>

<div class="rows">
    <div class="box box-danger left">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>판매량</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>
            </div><!-- /.box-tools pull-right -->
            <div class="clearfix"></div>
        </div><!-- /.box-header -->
        <div class="box-content chart-doughnut">
            <div class="divider50"></div>
            <div class="chart" id="sales-chart" style="height: 250px; width: 280px; position: relative;"></div>
            <div class="legend style1">
                <ul class="legend-list">
                    <li class="ux">
                        <span class="text">MP3다운</span>
                        <p>45 %</p>
                    </li>
                    <li class="ui">
                        <span class="text">WAV다운</span>
                        <p>35 %</p>
                    </li>
                    <li class="code">
                        <span class="text">이용권사용</span>
                        <p>20 %</p>
                    </li>
                </ul><!-- /.legend-list -->
            </div><!-- /.legend style1 -->
        </div><!-- /.box-body -->
    </div><!-- /.box box-danger -->
    <div class="box box-statistics right">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>판매동향</h3>
            </div>
            <div class="box-tools pull-right">
                <i class="zmdi zmdi-more-vert waves-effect waves-teal"></i>

            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-header -->
        <div class="box-content style1">
            <ul class="chart-tab">
                <li class="active waves-effect waves-teal">월간</li>
            </ul>
            <div id="chartStatistics">
            </div><!-- /#chartStatistics -->
        </div><!-- /.box-content -->
    </div><!-- /.box box-statistics -->
    <div class="clearfix"></div>
</div><!-- /.rows -->


<div class="box box-inbox full-width">
    <div class="box-header with-border">
        <div class="box-title">
            <h3>댓글목록 (좋아요갯수) (찜갯수) </h3>
        </div>
        <div class="clearfix"></div>
    </div><!-- /.box-header -->
    <div class="box-content">
        <ul class="inbox-list">
            <li class="waves-effect">
                <a href="#" title="">
                    <div class="left">
                        <img src="{{ asset('vendor/images/avatar/inbox-01.png')}}" alt="">
                        <div class="info">
                            <p class="name">John Alex</p>
                            <p>Hey! How are you?</p>
                        </div>

                        (삭제버튼)
                    </div>
                    <div class="right">
                        12:20 PM
                    </div>
                    <div class="clearfix"></div>
                </a>
            </li>
        </ul><!-- /.inbox-list -->
    </div><!-- /.box-content -->
</div><!-- /.box box-inbox -->


<script src="{{ asset('vendor/js/raphael.min.js')}}"></script>
<script src="{{ asset('vendor/js/morris.min.js')}}"></script>
<script src="{{ asset('vendor/js/canvasjs.min.js')}}"></script>
<script>
$(function() {
    function initProgressBar() {
        var player = document.getElementById('player');
        var length = player.duration
        var current_time = player.currentTime;

        // calculate total length of value
        var totalLength = calculateTotalValue(length)
        document.getElementById("end-time").innerHTML = totalLength;

        // calculate current value time
        var currentTime = calculateCurrentValue(current_time);
        document.getElementById("start-time").innerHTML = currentTime;

        var progressbar = document.getElementById('seek-obj');
        progressbar.value = (player.currentTime / player.duration);
        progressbar.addEventListener("click", seek);

        if (player.currentTime == player.duration) {
            document.getElementById('play-btn').className = "";
        }

        function seek(event) {
            var percent = event.offsetX / this.offsetWidth;
            player.currentTime = percent * player.duration;
            progressbar.value = percent / 100;
        }
    };

    var donutChart = function() {
        //DONUT CHART
        var donut = new Morris.Donut({
            element: 'sales-chart',
            resize: true,
            colors: ["#ff2d78", "#04aec6", "#4c418b"],
            data: [{
                    label: "이용권사용",
                    value: 20
                },
                {
                    label: "MP3",
                    value: 35
                },
                {
                    label: "WAV",
                    value: 45
                }
            ],
            options: {
                segmentShowStroke: true,
                StrokeColor: "#c5c5c5",
                fill: "#c5c5c5",
                percentageInnerCutout: 50,
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                responsive: true,
                maintainAspectRatio: true,
                showScale: true,
                animateScale: true,
                responsive: true,
                resize: true,
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 0,
                tooltipCornerRadius: 2,
            },
            hideHover: 'auto',
        });
    }; // Donut Chart

    var SplineArea = function() {
        CanvasJS.addColorSet("greenShades",
            [ //그래프별 색상
                "#6256a9",
                "#e02c73",
            ]);

        var chart = new CanvasJS.Chart("chartStatistics", {
            backgroundColor: "transparent",
            colorSet: "greenShades",
            responsive: false,
            animationEnabled: true,
            //손댈필요없음
            axisX: {
                gridColor: "#24262a",
                gridThickness: 1,
                tickColor: "transparent",
                LabelFontColor: "#898989",
                labelFontSize: "12",
                labelFontFamily: "Montserrat",
                maximum: 75,
                minimum: 5,
                lineThickness: 1,
                lineColor: "#24262a",
                tickLength: 15,
                tickColor: "transparent",
            },
            //손댈필요없음
            axisY: {
                gridColor: "#24262a",
                gridThickness: 1,
                tickColor: "transparent",
                labelFontFamily: "Montserrat",
                LabelFontColor: "#898989",
                labelFontSize: "12",
                maximum: 2000,
                minimum: 0,
                interval: 500,
                valueFormatString: "####",
                lineThickness: 1,
                lineColor: "#24262a",
                tickLength: 15,
                tickColor: "transparent",
            },
            //손댈필요없음
            data: [{
                    type: "column",
                    dataPoints: [{
                            label: "JAN",
                            x: 10,
                            y: 0
                        },
                        {
                            label: "FEB",
                            x: 20,
                            y: 0
                        },
                        {
                            label: "MAR",
                            x: 30,
                            y: 20
                        },
                        {
                            label: "APR",
                            x: 40,
                            y: 0
                        },
                        {
                            label: "MAY",
                            x: 50,
                            y: 0
                        },
                        {
                            label: "JUN",
                            x: 60,
                            y: 0
                        },
                        {
                            label: "JUL",
                            x: 70,
                            y: 0
                        },
                    ]
                },

                //월간 판매량표시
                //X축은 3일씩
                {
                    type: "splineArea",
                    markerColor: "transparent",
                    fillOpacity: 0.9,
                    bevelEnabled: true,
                    lineColor: "#04aec6",
                    dataPoints: [{
                            label: "",
                            x: 0,
                            y: 750
                        },
                        {
                            label: "오늘",
                            x: 10,
                            y: 700
                        },
                        {
                            label: "D-5",
                            x: 20,
                            y: 1400
                        },
                        {
                            label: "D-10",
                            x: 30,
                            y: 700
                        },
                        {
                            label: "D-15",
                            x: 40,
                            y: 850
                        },
                        {
                            label: "D-20",
                            x: 50,
                            y: 1350
                        },
                        {
                            label: "D-25",
                            x: 60,
                            y: 1000
                        },
                        {
                            label: "D-30",
                            x: 70,
                            y: 900
                        },
                        {
                            label: "D-35",
                            x: 80,
                            y: 1100
                        },
                    ]
                },
                {
                    type: "splineArea",
                    markerColor: "transparent",
                    fillOpacity: 0.9,
                    bevelEnabled: true,
                    lineColor: "#f72771",
                    dataPoints: [{
                            label: "",
                            x: 0,
                            y: 750
                        },
                        {
                            label: "오늘",
                            x: 10,
                            y: 70
                        },
                        {
                            label: "D-5",
                            x: 20,
                            y: 100
                        },
                        {
                            label: "D-10",
                            x: 30,
                            y: 500
                        },
                        {
                            label: "D-15",
                            x: 40,
                            y: 250
                        },
                        {
                            label: "D-20",
                            x: 50,
                            y: 950
                        },
                        {
                            label: "D-25",
                            x: 60,
                            y: 800
                        },
                        {
                            label: "D-30",
                            x: 70,
                            y: 1200
                        },
                        {
                            label: "D-35",
                            x: 80,
                            y: 500
                        },
                    ]
                }
            ]
        });
        chart.render();
    }; // Spline Area



    $(window).load(function() {
        setTimeout(function() {
            $('.loader').hide();
            SplineArea();
            donutChart();

            $('#mp3-range').stop(true, true).animate({
                'width': '30%'
            }, 250, 'linear');

            $('#wav-range').stop(true, true).animate({
                'width': '38%'
            }, 250, 'linear');

        }, 100);
    });

    var player = document.getElementById('player');
    player.addEventListener("timeupdate", function() {
        var currentTime = player.currentTime;
        var duration = player.duration;
        $('.range').stop(true, true).animate({
            'width': (currentTime + .25) / duration * 100 + '%'
        }, 250, 'linear');
    });

});
</script>
<style>
.slide {
    width: 100%;
    background: black;
    height: 25px;
}

.range {
    width: 0;
    background: #04aec6;
    height: 25px;
}
</style>
@endsection