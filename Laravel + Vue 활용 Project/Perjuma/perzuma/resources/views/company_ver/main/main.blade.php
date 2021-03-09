@extends('company_ver.layouts.app') 
@section('content')
<script>

var start=10;
var state = 0; //0일때는 전체 리스트, 1일때는 검색 리스트
    function tradelist(){
        if(state == 0){
            var param = {
                'search_keyword' : 1534,
                'search_type' : 2,
                'offset' : start
            };
        }
        else{
            var param = {
                'search_keyword' : $('#main_search').val(),
                'search_type' : 6,
                'offset' : start
            };
        }
        $.ajax({
            type : "GET",
            dataType: "json",
            data : param,
            url : "/api/trade/search",
            success : function(data) {
                var tradelist = $('#tradelist');
                var template;
                var yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                if(data.state==1 && data.query.length!=0){
                    var items = data.query;
                    for(var i = 0; i< items.length;i++){
                        if(items[i].state==3){
                            var template = $($('#before_template').html());
                            template.find('#before_template_date').text(items[i].created_at);
                            template.find('#before_template_name').text(items[i].trd_name);
                            template.attr('onclick','location.href=\'/company_ver/detail/'+items[i].trd_no);
                            if(items[i].updated_at >= yesterday){
                                template.find('#mark').addClass('blue_mark');
                            }
                            tradelist.append(template);
                        }
                        else if(items[i].state==4){
                            var template = $($('#proceed_template').html());
                            template.find('#proceed_template_date').text(items[i].created_at);
                            template.find('#proceed_template_name').text(items[i].trd_name);
                            template.attr('onclick','location.href=\'/company_ver/detail/'+items[i].trd_no);
                            if(items[i].updated_at >= yesterday){
                                template.find('#mark').addClass('blue_mark');
                            }
                            tradelist.append(template);
                        }
                        else if(items[i].state==5){
                            var template = $($('#complete_template').html());
                            template.find('#complete_template_date').text(items[i].created_at);
                            template.find('#complete_template_name').text(items[i].trd_name);
                            template.attr('onclick','location.href=\'/company_ver/detail/'+items[i].trd_no);
                            if(items[i].updated_at >= yesterday){
                                template.find('#mark').addClass('blue_mark');
                            }
                            tradelist.append(template);
                        }
                        start++;
                    }
                }
                else if(data.state==1 && data.query.length==0){
                    template = '<li data-sqm=\"1down\">';
                    template += '\n<div>';
                    template += '\n<p id=\"before_template_name\">시공내역 없음</p>';
                    template += '\n</div>';
                    template += '\n</li>';
                    tradelist.append(template);
                }
                else{
                    alert(data.msg);
                }
            },
            error : function(data){
            }
        });
    }
    $(document).on('keypress', '#main_search', function(e) {
        if (e.which == 13) {
            state = 1;
            start = 0;
            $('#tradelist').html('');
            tradelist();
            $('.viewall').addClass('active');
        }
    });
    $(window).scroll(function() {
        var scrollBottom = $(window).scrollTop() + $(window).height();
        if (scrollBottom == $(document).height()) {
            tradelist();
        }
    });
    $(function(){
        $('.viewall').click(function(){
            if($('.viewall').hasClass('active')){
                $('.viewall').removeClass('active');
                state = 0;
                start = 0;
                $('#tradelist').html('');
                tradelist();
            }
        });
        $('#content').attr('style','min-height : '+$(window).height()+'px;');
    });
</script>
<div>
    <div class="main_wrap">
        <input type="hidden" name="estimate_request_id" />
        <p class="title">시공 내역</p>
        <p class="viewall">전체보기 ></p>
        <ul id="tradelist">
            @forelse($agent as $item)
            <li data-sqm="1down" onclick="location.href='/company_ver/detail/{{$item->trd_no}}'">
                <div>
                    <p id="before_template_date">{{$item->created_at}}</p>
                    <p id="before_template_name">{{$item->trd_name}}</p>
                </div>
                <div>
                    @if($item->state==3)
                    <p class="before">진행 예정</p>
                    @elseif($item->state==4)
                    <p class="proceed">진행 중</p>
                    @elseif($item->state==5)
                    <p class="after">진행 완료</p>
                    @endif
                </div>
                @if($item->updated_at > date("Y-m-d", strtotime(now()." -1 day")))
                <span class="blue_mark"></span>
                @endif
            </li>
            @empty
            <li data-sqm="1down">
                <div>
                    <p id="before_template_name">시공내역 없음</p>
                </div>
            </li>
            @endforelse
        </ul>
    </div>
</div>
<template id="before_template">
    <li data-sqm="1down">
        <div>
            <p id="before_template_date"></p>
            <p id="before_template_name"></p>
        </div>
        <div>
            <p class="before">진행 예정</p>
        </div>
        <span id="mark"></span>
    </li>
</template>
<template id="proceed_template">
    <li data-sqm="1down">
        <div>
            <p id="proceed_template_date"></p>
            <p id="proceed_template_name"></p>
        </div>
        <div>
            <p class="proceed">진행 중</p>
        </div>
        <span id="mark"></span>
    </li>
</template>
<template id="complete_template">
    <li data-sqm="1down">
        <div>
            <p class="complete_template_date"></p>
            <p class="complete_template_name"></p>
        </div>
        <div>
            <p class="after">진행 완료</p>
        </div>
        <span id="mark"></span>
    </li>
</template>
<div class="footer">
    @include('company_ver.layouts.footer')
</div>
<style>
    .main_wrap{
        padding:1em 0 2em;
    }
    .main_wrap ul li:hover{
        border:1px solid #007bd2;
    }
</style>
@endsection