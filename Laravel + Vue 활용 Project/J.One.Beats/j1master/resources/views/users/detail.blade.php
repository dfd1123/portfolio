@extends('header')
@section('content')
<input type="hidden" id="mp3_down" value="{{$mp3_cnt}}"/>
<input type="hidden" id="wav_down" value="{{$wav_cnt}}"/>
<input type="hidden" id="license_down" value="{{$license_cnt}}"/>
<div class="rows">
    <div>
    	@if($query[0]->state == 0)
        <button class="deactive_toggle active">비활성화</button>
        <button class="active_toggle">활성화</button>
        @elseif($query[0]->state == 1)
        <button class="deactive_toggle">비활성화</button>
        <button class="active_toggle active">활성화</button>
        @else
        <p>탈퇴 대기중</p>
        @endif
    </div>
    <div class="status-bar">
        <div class="beat_name">{{$query[0]->user_nick}}</div> 
        <ul class="beat_info">
            <li class="progres">
                <div class="content">
                    <div class="text" style="font-family: 'Oswald';font-size: 22px;font-weight: 500;">
                        {{$query[0]->user_email}}
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content" style="background-image:url('img/imgs/mood07.jpg');">
                    <div class="text" style="font-family: 'Oswald';font-size: 22px;font-weight: 500;">
                        {{$query[0]->user_name}}
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="text" style="font-family: 'Oswald';font-size: 22px;font-weight: 500;">
                        {{$query[0]->user_mobile}}
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="text" style="font-family: 'Oswald';font-size: 22px;font-weight: 500;">
                        {{$query[0]->created_at}}
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>

<div class="box box-inbox full-width">
    <div class="box-header with-border">
        <div class="box-title">
            <h3>구매 목록</h3>
        </div>
        <div class="clearfix"></div>
    </div><!-- /.box-header -->
    <div class="box-content list">
        <ul class="inbox-list">
            @forelse($query2 as $item)
            <li class="waves-effect">
                <div class="left">
                    <img src="/fdata/beathumb/{{$item->beat_thumb}}" alt="분위기 배경사진"/>
                    <div class="info">
                        <p class="name">{{$item->beat_title}}</p>
                        <p>{{$item->prdc_nick}}</p>
                        <p class="content">{{$item->created_at}}</p>
                    </div>
                </div>
            </li>
            @empty
            <li class="waves-effect">
                <p>구매한 비트가 없습니다</p>
            </li>
            @endforelse
            
        </ul><!-- /.inbox-list -->
    </div><!-- /.box-content -->
</div><!-- /.box box-inbox -->

<style>
    .content{
        font-size:1.2em;
        position: relative;
    }
    .beat_info{
        margin-bottom:1em;
    }
    .delete_btn{
        background-color: #CC3D3D;
        text-align: center;
        line-height: 42px;
        border-radius: 20px;
        color: #fff;
        font-size: 1.3em;
        margin: 0.5em 0;
        padding: 0 4em;
        width:12em;
    }
    .delete_btn:hover{
        background-color:#F15F5F;
    }
    @media only screen and (max-width: 1700px) and (min-width: 1367px){
        .icon {
            float: none;
            margin-right: 0;
            text-align: center;
        }
    }
    .deactive_toggle{
        color: #fff;
        padding: 1em;
        background-color: #8c8c8c;
        margin-bottom: 1.5em;
        border-top-left-radius: 1em;
        border-bottom-left-radius: 1em;
    }
    .deactive_toggle:hover{
        background-color: #a22321;
    }
    .deactive_toggle.active{
        background-color: #a22321;
    }
    .active_toggle{
        color: #fff;
        padding: 1em;
        background-color: #8c8c8c;
        margin-bottom: 1.5em;
        border-top-right-radius: 1em;
        border-bottom-right-radius: 1em;
    }
    .active_toggle:hover{
        background-color: #04aec6;
    }
    .active_toggle.active{
        background-color: #04aec6;
    }
    .playtype{
        float: none;
        vertical-align: middle;
        display: inline-block;
        font-family: 'Oswald';
        font-size: 54px;
        font-weight: 500;
        line-height: 80px;
        color: #f9f9f9;
        margin-right: 18px;
    }
    .play_btn{
        color:#fff;
        vertical-align:middle;
        margin:0 0.5em;
        cursor:pointer;
    }
    .stop_btn{
        color:#fff;
        vertical-align:middle;
        margin:0 0.5em;
        cursor:pointer;
    }
    .beat_name{
        width:100%;
        margin-right: 4px;
        border-radius: 8px;
        background-color: #242629;
        padding: 1em;
        margin-bottom: 0.5em;
        text-align:center;
        font-size:1.3em;
        color:#fff;
        font-family: 'Oswald';
    }
    .playtime{
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 0);
    }
    .box-content.list{
	    max-height: 26em;
	    overflow-y: auto;
	    padding: 1em;
    }
</style>
<script src="{{ asset('vendor/js/raphael.min.js')}}"></script>
<script src="{{ asset('vendor/js/morris.min.js')}}"></script>
<script src="{{ asset('vendor/js/canvasjs.min.js')}}"></script>
<script>
menuactive('calendar');
//GET파라미터 찾기
function findGetParameter(parameterName) {
	var result = null,
		tmp = [];
	location.search
		.substr(1)
		.split("&")
		.forEach(function (item) {
			tmp = item.split("=");
			if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
		});
	return result;
}
var user_id = findGetParameter('user_id');
//유저 상태 변경
function activebtn(state){
    var param = {
    	'user_id' : user_id,
    	'state' : state
    }
    $.ajax({
    	type : "PUT",
    	data : param,
    	dataType : 'json',
    	url : '/api/user/state',
    	success : function(data){
    		console.log(data);
			if(data.state==1 && data.query !=null){
				alert('변경 되었습니다!');
				location.reload();
			}else{
				alert(data.msg);
			}
    	},
    	error : function(e){
    		alert(e);
    	}
    });
}
$('.active_toggle').click(function(){
    if($(this).hasClass('active')){
    }else{
    	activebtn(1);
        $(this).addClass('active');
        $('.deactive_toggle').removeClass('active');
    }
});
$('.deactive_toggle').click(function(){
    if($(this).hasClass('active')){
    }else{
    	activebtn(0);
        $(this).addClass('active');
        $('.active_toggle').removeClass('active');
    }
});
    
$(function() {
    $('#mp3-play').click(function(){
        if($(this).hasClass('fa-play')){
            $(this).removeClass('fa-play');
            $(this).addClass('fa-pause');
        }else{
            $(this).addClass('fa-play');
            $(this).removeClass('fa-pause');
        }
    });
    $('#wav-play').click(function(){
        if($(this).hasClass('fa-play')){
            $(this).removeClass('fa-play');
            $(this).addClass('fa-pause');
        }else{
            $(this).addClass('fa-play');
            $(this).removeClass('fa-pause');
        }
    });
    
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

	function donutChart(mp3, wav, license) {
        //DONUT CHART
        var donut = new Morris.Donut({
            element: 'sales-chart',
            resize: true,
            colors: ["#ff2d78", "#04aec6", "#4c418b"],
            data: [{
                    label: "이용권사용",
                    value: mp3
                },
                {
                    label: "MP3",
                    value: wav
                },
                {
                    label: "WAV",
                    value: license
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
            donutChart($('#mp3_down').val(),$('#wav_down').val(),$('#license_down').val());
			$('#mp3_percent').html($('#mp3_down').val()+'%');
			$('#wav_percent').html($('#wav_down').val()+'%');
			$('#license_percent').html($('#license_down').val()+'%');

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
