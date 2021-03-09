@extends('header')
@section('content')
<div class="rows">
    <div>
        @if($query[0]->state == 0)
        <button class="deactive_toggle active">비활성화</button>
        <button class="active_toggle">활성화</button>
        @else
        <button class="deactive_toggle">비활성화</button>
        <button class="active_toggle active">활성화</button>
        @endif
    </div>
    <div class="status-bar">
        <div class="beat_name">(#{{$query[0]->beat_id}}) {{$query[0]->beat_title}} - <span style="cursor:pointer;" onclick="location.href='/beat?prdc_nick={{$query[0]->prdc_nick}}';">{{$query[0]->prdc_nick}}</span></div> 
        <ul class="beat_info">
            <li class="progres">
                <div class="content">
                    <div class="text" style="font-family: 'Oswald';font-size: 22px;font-weight: 500;">
                        {{$query[0]->cate_title}}
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="text" style="font-family: 'Oswald';font-size: 22px;font-weight: 500;">
                        {{$query[0]->mood_title}}
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="text" style="font-family: 'Oswald';font-size: 22px;font-weight: 500;">
                        {{$query[0]->beat_time}}
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
        <ul>
            <li class="progres">
                <div class="content">
                    <div class="numb" data-from="0" data-to="59" data-speed="1000" data-waypoint-active="yes">
                        @if($query[0]->beat_wish == NULL)
                            0
                        @else
                            {{$query[0]->beat_wish}}
                        @endif
                    </div>
                    <div class="text">
                        찜 갯수
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="numb" data-from="0" data-to="114" data-speed="1000" data-waypoint-active="yes">
                        @if($query[0]->beat_like == NULL)
                            0
                        @else
                            {{$query[0]->beat_like}}
                        @endif
                    </div>
                    <div class="text">
                        좋아요
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="numb" data-from="0" data-to="59" data-speed="1000" data-waypoint-active="yes">
                        @if($query[0]->beat_hit == NULL)
                            0
                        @else
                            {{$query[0]->beat_hit}}
                        @endif
                    </div>
                    <div class="text">
                        재생
                    </div>
                </div>
            </li>
            <li class="progres">
                <div class="content">
                    <div class="numb" data-from="0" data-to="59" data-speed="1000" data-waypoint-active="yes">
                        {{$query[0]->beat_buy}}/{{$query[0]->beat_free}}
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

    @if(isset($query[0]->mp3))
    <div class="box right">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>비트 듣기</h3>
            </div>
            <ul class="chart-tab">
                <li class="active waves-effect" style="visibility: hidden">샘플</li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="box-content style1">
            <audio controls>
                <source src="/fdata/admin/mp3/55fc2e7c42194fdc8b4dd8193d0c8bb656d4e72f4c845f955a6337305075f257/{{$query[0]->mp3}}" type="audio/mp3">
                이 문장은 사용자의 웹 브라우저가 audio 요소를 지원하지 않을 때 나타납니다!
            </audio>
        </div>
    </div>
    @endif

    <div class="box box-inbox full-width">
        <div class="box-header with-border">
            <div class="box-title">
                <h3>댓글목록</h3>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="box-content list">
            <ul class="inbox-list">
                @forelse($comment_info as $item)
                <li class="waves-effect">
                    <div class="left">
                        <img src="{{ asset('vendor/images/avatar/inbox-01.png')}}" alt="">
                        <div class="info">
                            <p class="name">{{$item->user_name}}</p>
                            <p>{{$item->created_at}}</p>
                            <p class="content">{{$item->cmt_content}}</p>
                        </div>
                    </div>
                    <div class="right">
                        <button class="delete_btn" onclick="javascript:delete_reply({{$item->cmt_id}});">삭제</button>
                    </div>
                </li>
                @empty
                <li class="waves-effect" style="cursor:inherit;">
                    <p>댓글이 없습니다</p>
                </li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="clearfix"></div>
</div>

<script src="{{ asset('vendor/js/canvasjs.min.js')}}"></script>
<script>
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

    var beat_id = findGetParameter('beat_id');
    //비트 상태 변경
    function activebtn(state){
        var param = {
            'beat_id' : beat_id,
            'state' : state
        }
        $.ajax({
            type : "PUT",
            data : param,
            dataType : 'json',
            url : '/api/beat/state',
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
                activebtn(0);
                $(this).addClass('active');
                $('.active_toggle').removeClass('active');
            }
        });
    });

    //답글 삭제
    function delete_reply(idx){
        event.stopPropagation();
        
        $.ajax({
            type : "DELETE",
            data: {req: 'comment'},
            dataType : 'json',
            url : '/api/beat/' + idx,
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
        menuactive('dashboard');
        
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

        $(function() {
            SplineArea();

            setTimeout(function() {
                $('.loader').hide();
            }, 100);
        });
    });
</script>
<style>
    @media only screen and (max-width: 1700px) and (min-width: 1367px){
        .icon {
            float: none;
            margin-right: 0;
            text-align: center;
        }
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
</style>
@endsection
