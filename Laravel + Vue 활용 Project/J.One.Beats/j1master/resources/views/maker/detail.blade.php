@extends('header')
@section('content')
<div class="rows">
    <div>
        @if($query[0]->state == 0)
        <button class="active_btn">승인</button>
        @elseif($query[0]->state == 1)
        <button class="deactive_toggle">비활성화</button>
        <button class="active_toggle active">활성화</button>
        @elseif($query[0]->state == 2)
        <button class="deactive_toggle active">비활성화</button>
        <button class="active_toggle">활성화</button>
        @endif
    </div>
    <div class="status-bar">
        <div class="producer_img_div">
            @if(isset($query[0]->prdc_img))
            <img src="/fdata/mkrthumb/{{$query[0]->prdc_img}}" alt="이미지 없음"/>
            @else
            썸네일 없음
            @endif
        </div>
        <div class="producer_name_div"><p>(#{{$query[0]->prdc_id}}) {{$query[0]->prdc_nick}}</p></div>
        <div class="producer_name_div">
            <p>
                @if($query[0]->cate_info=='[]')
                선택한 카테고리 없음
                @else
                    @forelse(json_decode($query[0]->cate_info) as $cate)
                    {{$cate->cate_title}},
                    @empty
                    선택한 카테고리 없음
                    @endforelse
                @endif
            </p>
        </div>
        <div class="producer_name_div">
            <p>
                @if($query[0]->atmo_info=='[]')
                선택한 분위기 없음
                @else
                    @forelse(json_decode($query[0]->atmo_info) as $atmo)
                    {{$atmo->mood_title}},
                    @empty
                    선택한 분위기 없음
                    @endforelse
                @endif
            </p>
        </div>
    </div>
</div>

<div class="rows">
    <div class="status-bar">
        <ul style="width: 100%;">
            <li class="progres">
                <div class="content">
                    <div class="numb" data-from="0" data-to="59" data-speed="1000" data-waypoint-active="yes">
                        {{$query[0]->prdc_like}}
                    </div>
                    <div class="text">
                        좋아요
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="numb" data-from="0" data-to="59" data-speed="1000" data-waypoint-active="yes">
                        {{$query[0]->prdc_follow}}
                    </div>
                    <div class="text">
                        팔로우
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="numb" data-from="0" data-to="59" data-speed="1000" data-waypoint-active="yes">
                        {{$query[0]->prdc_buy}}/{{$query[0]->prdc_free}}
                    </div>
                    <div class="text">
                        구매/무료
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>

<div class="rows">
    <div class="box left">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>판매동향</h3>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content style1">
            <ul class="chart-tab">
                <li class="active waves-effect waves-teal">월간</li>
            </ul>
            <div id="chartStatistics">
            </div>
        </div>
    </div>
    
    @if($query[0]->state == 0)
    <div class="box right">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>비트 샘플</h3>
            </div>
            <div class="box-tools pull-right">
            </div>
            <ul class="chart-tab">
                <li class="active waves-effect" style="visibility: hidden">샘플</li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="box-content style1">
            <audio controls>
                <source src="/fdata/mkrsmpl/{{$query[0]->prdc_sample}}" type="audio/mp3">
                이 문장은 사용자의 웹 브라우저가 audio 요소를 지원하지 않을 때 나타납니다!
            </audio>
        </div>
    </div>
    @endif
    
    <div class="clearfix"></div>
</div>

<script src="{{ asset('vendor/js/canvasjs.min.js')}}"></script>
<script>
    menuactive('message');
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

    var prdc_id = findGetParameter('prdc_id');
    //비트 상태 변경
    function activebtn(state){
        var param = {
            'prdc_id' : prdc_id,
            'state' : state
        }
        $.ajax({
            type : "PUT",
            data : param,
            dataType : 'json',
            url : '/api/producers/state',
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
    $(function() {
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
                activebtn(2);
                $(this).addClass('active');
                $('.active_toggle').removeClass('active');
            }
        });

        $('.active_btn').click(function(){
            activebtn(1);
        });

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
                axisX: {
                    gridColor: "#24262a",
                    gridThickness: 1,
                    tickColor: "transparent",
                    LabelFontColor: "#898989",
                    labelFontSize: "12",
                    labelFontFamily: "Montserrat",
                    lineThickness: 1,
                    lineColor: "#24262a",
                    tickLength: 15,
                    tickColor: "transparent",
                    minimum: 1,
                    maximum: 12
                },
                axisY: {
                    gridColor: "#24262a",
                    gridThickness: 1,
                    tickColor: "transparent",
                    labelFontFamily: "Montserrat",
                    LabelFontColor: "#898989",
                    labelFontSize: "12",
                    valueFormatString: "####",
                    lineThickness: 1,
                    lineColor: "#24262a",
                    tickLength: 15,
                    tickColor: "transparent",
                },
                data: [
                    {
                        type: "splineArea",
                        markerColor: "transparent",
                        fillOpacity: 0.3,
                        bevelEnabled: true,
                        lineColor: "#f72771",
                        dataPoints: [
                            @foreach($buys_for_month as $buy)
                            {
                                label: '{{$buy->beat_month}}월',
                                x: {{$loop->iteration}},
                                y: {{$buy->beat_count}}
                            }
                                @if($loop->iteration < count($buys_for_month))
                                ,
                                @endif
                            @endforeach
                        ]
                    }
                ]
            });

            chart.render();
        };

        $(window).load(function() {
            SplineArea();

            setTimeout(function() {
                $('.loader').hide();
            }, 100);
        });
    });
</script>

<style>
    .active_btn{
        background-color: #04aec6;
        text-align: center;
        line-height: 42px;
        border-radius: 20px;
        color: #fff;
        font-size: 1.3em;
        margin: 0.5em 0;
        padding: 0 4em;
        width:12em;
        margin-bottom:1em;
    }
    .active_btn:hover{
        background-color:#3DB7CC;
    }
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
    .status-bar{
        display:flex;
    }
    .producer_img_div{
        margin-right: 1em;
        border-radius: 8px;
        background-color: #242629;
        padding: 1em;
        margin-bottom: 0.5em;
        text-align:center;
        display:inline-block;
    }
    .producer_img_div img{
        width: 12em;
        height: 12em;
        padding: 2em;
        border-radius: 50%;
    }
    .producer_name_div{
        margin-right: 4px;
        border-radius: 8px;
        background-color: #242629;
        padding: 1em;
        margin-bottom: 0.5em;
        flex:auto;
        position:relative;
    }
    .producer_name_div p{
        color:#fff;
        font-family: 'Oswald';
        position:absolute;
        font-size:1.3em;
        top:50%;
        left:50%;
        transform:translate(-50%,-50%);
    }
    .playtime{
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 0);
    }
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
