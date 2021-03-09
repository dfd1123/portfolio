@extends(session('theme').'.mobile.layouts.app') 
@section('content')

<div class="mobile_comunity_wrap">
    <input type="hidden" name="board_name" value="{{$board_name}}" />
    <div class="m_hd_title">
        <div class="inner">
            {{__('head.community')}}
            <span class="write_btn_st write_btn" onClick="location.href='{{ route('comunity.create') }}?board_name={{$board_name}}'">
                <img src="{{ asset('/storage/image/homepage/mobile_icon/m_btn_write_wh.svg')}}" alt="tab">
            </span>
        </div>
    </div>

    <div class="m_tab_list bt_line">
        <ul>
            <li class="{{$board_name == 'free'? 'active' : '' }}">
                <a href="{{ route('comunity.index') }}?board_name=free"> 
                    {{ __('head.board')}}
                </a>
            </li>
            <li class="{{$board_name != 'free'? 'active' : '' }}">
            <!-- <li class="{{request()->is('comunity*')? 'active' : '' }}"> -->
                <!-- FIXME: 추후 코인게시판으로 라우트 바꿔야함 -->
                <a href="{{ route('comunity.index') }}?board_name=eth"> 
                    {{ __('head.coin_board')}}
                </a>
            </li>
            <li class="">
                <!-- FIXME: 추후 코인게시판으로 라우트 바꿔야함 -->
                <a href="{{ route('notice') }}"> 
                    {{ __('support.notice') }}
                </a>
            </li>
        </ul>
    </div>

    @if($board_name != 'free')
    <div class="coin_select_bar" id="coin_slt_bar">
        <label for="list_check">
            <p class="select_tit">
                <img src="{{ asset('/images/coin_img/'.$board_name.'.png') }}" alt="coin_img" class="coin_symbol"/>
                {!! __('coin_name.'.$board_name) !!}
            </p>
        </label>
        <input id="list_check" type="checkbox" class="hide"/>
        <div class="market_list_n_coin_list">
            <ul class="market_list_tab">
                <li {{($comunity->coin_type == 'sports')?'class=active':''}}>
                    <label for="usdc_market_list">SPORTS COIN</label>
                </li>
                <li {{($comunity->coin_type == 'public')?'class=active':''}}>
                    <label for="krw_market_list">PUBLIC COIN</label>
                </li> 
            </ul> 
            
            <input id="usdc_market_list" class="hide" type="radio" name="coin_list" {{($comunity->coin_type == 'sports')?'checked=checked':''}} />
            <input id="krw_market_list" class="hide" type="radio" name="coin_list" {{($comunity->coin_type == 'public')?'checked=checked':''}} />

            <ul class="select_list coin_list-1">
                @foreach($comunity_lists as $comunity_list)
                    @if($comunity_list->coin_type == 'sports')
                    <li>
                        <a href="/comunity?board_name={{$comunity_list->bo_table}}">
                            <img src="{{ asset('/images/coin_img/'.strtolower($comunity_list->bo_table).'.png') }}" alt="coin_img" class="coin_symbol"/>
                            {!! __('coin_name.'.$comunity_list->bo_table) !!}
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul>
            <ul class="select_list coin_list-4">
                @foreach($comunity_lists as $comunity_list)
                    @if($comunity_list->coin_type == 'public')
                    <li>
                        <a href="/comunity?board_name={{$comunity_list->bo_table}}">
                            <img src="{{ asset('/images/coin_img/'.strtolower($comunity_list->bo_table).'.png') }}" alt="coin_img" class="coin_symbol"/>
                            {!! __('coin_name.'.$comunity_list->bo_table) !!}
                        </a>
                    </li>
                    @endif
                @endforeach
            </ul> 
            
        </div>
    </div>
    @endif

    <!-- scrl_wrap -->
    {{-- FIXME:코인게시판엔 클래스 m_cs_wrap02를 추가해주세요. --}}
    <div class="scrl_wrap m_cs_wrap comunity_table_wrap comunity_board_list_wrap">
        <table class="cs_table">
            <tbody id="comunity_list_wrap" data-count="{{$board_cnt}}" data-offset="{{count($boards)}}">
                @foreach($notice_lists as $notice)
                    <tr class="notice_tr">
                        <td class="title_td">
                            <a href="/notice/{{$notice->id}}">
                                <div class="mobile_title_hd_wrap">
                                    <div class="mobile_title_hd">
                                        <em class="comunity_board_title">{{$notice->title}}</em>  
                                    </div>

                                    <div class="mobile_comunity_dates">
                                        <span class="nick_name">관리자</span>
                                        <span class="pl-2">{{date("Y-m-d H:i", $notice->created)}}</span>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                @endforeach
                @forelse($board_lists as $board)
                    @if($board->notice == 1)
                        <!-- 공지사항은 배경색상이 다름 -->
                        <tr class="notice_tr">
                            <td class="title_td">
                                <a href="{{ route('comunity.show', $board->id) }}?board_name={{$board_name}}">
                                    <div class="mobile_title_hd_wrap">
                                        <div class="mobile_title_hd">
                                            <em class="comunity_board_title">{{$board->title}}</em>
                                            <!-- ③ 댓글 수 보이기 -->
                                            <b class="reply_amt">{{number_format($board->comment_cnt)}}</b>    
                                        </div>

                                        <div class="mobile_comunity_dates">
                                            <span class="nick_name">{{$board->writer_nickname}}</span>
                                            <span class="pl-2">{{date("Y-m-d H:i", strtotime($board->created_at))}}</span>
                                        </div>
                                    </div>

                                </a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="title_td">
                                @if($board->secret_key == NULL)
                                <a href="{{ route('comunity.show', $board->id) }}?board_name={{$board_name}}">
                                    @if($board->images != NULL || $board->images != '')
                                    <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                                    <span class="comunity_board_thumb"></span>
                                    @endif
        
                                    <div class="mobile_title_hd_wrap">
                                        <div class="mobile_title_hd">
                                            @if(date_diff(new DateTime($board->created_at), $today)->days < 2)
                                            <!-- ② 새로 올린 게시글 뱃지 -->
                                            <img src="/images/icon/new_icon.svg" alt="new board">
                                            @endif
                                            <em class="comunity_board_title">{{$board->title}}</em>
                                            <!-- ③ 댓글 수 보이기 -->
                                            <b class="reply_amt">{{number_format($board->comment_cnt)}}</b>    
                                        </div>
        
                                        <div class="mobile_comunity_dates">
                                            <span class="nick_name">{{$board->writer_nickname}}</span>
                                            <span class="pl-2">{{date('Y-m-d H:i', strtotime($board->created_at))}}</span>
                                            <span class="pr-2 pl-2">조회 <em>{{$board->hit}}</em></span>
                                            <span>추천 <em>{{$board->recomend}}</em></span>
                                        </div>
                                    </div>
                                </a>
                                @else
                                <a  href="#" onclick="custom_alert_popup_open('#modal_popup_change_pw',{{$board->id}})">
                                    @if($board->images != NULL || $board->images != '')
                                    <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                                    <span class="comunity_board_thumb"></span>
                                    @endif
        
                                    <div class="mobile_title_hd_wrap">
                                        <div class="mobile_title_hd">
                                            @if(date_diff(new DateTime($board->created_at), $today)->days < 2)
                                            <!-- ② 새로 올린 게시글 뱃지 -->
                                            <img src="/images/icon/new_icon.svg" alt="new board">
                                            @endif
                                            <em class="comunity_board_title">{{$board->title}}</em>
                                            <!-- ③ 댓글 수 보이기 -->
                                            <b class="reply_amt">{{number_format($board->comment_cnt)}}</b>
                                            <span class="secret_comunity"><i class="fal fa-lock"></i>비밀글</span>    
                                        </div>
        
                                        <div class="mobile_comunity_dates">
                                            <span class="nick_name">{{$board->writer_nickname}}</span>
                                            <span class="pl-2">{{date('Y-m-d H:i', strtotime($board->created_at))}}</span>
                                            <span class="pr-2 pl-2">조회 <em>{{$board->hit}}</em></span>
                                            <span>추천 <em>{{$board->recomend}}</em></span>
                                        </div>
                                    </div>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @foreach($re_board_lists as $re_board)
                            @if($re_board->re_id == $board->id)
                            <tr>
                                <td class="title_td">
                                    @if($re_board->secret_key == NULL)
                                    <a href="{{ route('comunity.show', $re_board->id) }}?board_name={{$board_name}}">
                                        @if($re_board->images != NULL || $re_board->images != '')
                                        <i class="fal fa-reply" style="transform: rotate( 180deg );font-size: 16px;margin-right: 10px;"></i> 
                                        <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                                        <span class="comunity_board_thumb"></span>
                                        @endif

                                        <div class="mobile_title_hd_wrap">
                                            <div class="mobile_title_hd">
                                                @if(date_diff(new DateTime($re_board->created_at), $today)->days < 2)
                                                <!-- ② 새로 올린 게시글 뱃지 -->
                                                <img src="/images/icon/new_icon.svg" alt="new board">
                                                @endif
                                                <em class="comunity_board_title">{{$re_board->title}}</em>
                                                <!-- ③ 댓글 수 보이기 -->
                                                <b class="reply_amt">{{number_format($re_board->comment_cnt)}}</b>    
                                            </div>

                                            <div class="mobile_comunity_dates">
                                                <span class="nick_name">{{$re_board->writer_nickname}}</span>
                                                <span class="pl-2">{{date('Y-m-d H:i', strtotime($re_board->created_at))}}</span>
                                                <span class="pr-2 pl-2">조회 <em>{{$re_board->hit}}</em></span>
                                                <span>추천 <em>{{$re_board->recomend}}</em></span>
                                            </div>
                                        </div>
                                    </a>
                                    @else
                                    <a  href="#" onclick="custom_alert_popup_open('#modal_popup_change_pw',{{$board->id}})">
                                        @if($re_board->images != NULL || $re_board->images != '')
                                        <i class="fal fa-reply" style="transform: rotate( 180deg );font-size: 16px;margin-right: 10px;"></i> 
                                        <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                                        <span class="comunity_board_thumb"></span>
                                        @endif

                                        <div class="mobile_title_hd_wrap">
                                            <div class="mobile_title_hd">
                                                @if(date_diff(new DateTime($re_board->created_at), $today)->days < 2)
                                                <!-- ② 새로 올린 게시글 뱃지 -->
                                                <img src="/images/icon/new_icon.svg" alt="new board">
                                                @endif
                                                <em class="comunity_board_title">{{$re_board->title}}</em>
                                                <!-- ③ 댓글 수 보이기 -->
                                                <b class="reply_amt">{{number_format($re_board->comment_cnt)}}</b>
                                                <span class="secret_comunity"><i class="fal fa-lock"></i>비밀글</span>    
                                            </div>

                                            <div class="mobile_comunity_dates">
                                                <span class="nick_name">{{$re_board->writer_nickname}}</span>
                                                <span class="pl-2">{{date('Y-m-d H:i', strtotime($re_board->created_at))}}</span>
                                                <span class="pr-2 pl-2">조회 <em>{{$re_board->hit}}</em></span>
                                                <span>추천 <em>{{$re_board->recomend}}</em></span>
                                            </div>
                                        </div>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                @empty
                <tr>
                    <td class="non_data">등록된 글이 없습니다.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div id="comunity_scrl_top">
            <img src="/images/icon/btn_scrl_top.svg" alt="btn_scrl_top">
        </div>
    </div>
    <!-- //scrl_wrap -->

</div>

<!-- 모달팝업 (비밀번호확인) -->
<div class="custom_alert_popup" id="modal_popup_change_pw">
    <h5 class="custom_alert_popup_tit">비밀번호 확인</h5>
    <span class="custom_alert_popup_input">
        <input type="password" id="secret_key" placeholder="비밀번호를 입력하세요.">
    </span>
    <div class="double_btn_02">
        <button type="button" class="custom_alert_popup_btn outline_gray" onClick="custom_alert_popup_close(this)">취소</button>
        <button type="button" id="secret_key_btn" class="custom_alert_popup_btn outline_blue" data-id="">확인</button>
    </div>
</div>
<!-- // 모달팝업 (비밀번호확인) -->

<template id="tr_comunity_list">
    <tr>
        <td class="title_td">
            <a href="#" class="list_link">
                <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                <span class="comunity_board_thumb"></span>

                <div class="mobile_title_hd_wrap">
                    <div class="mobile_title_hd">
                        <!-- ② 새로 올린 게시글 뱃지 -->
                        <img src="/images/icon/new_icon.svg" class="new_board_img" alt="new board">
                        <em class="comunity_board_title"></em>
                        <!-- ③ 댓글 수 보이기 -->
                        <b class="reply_amt"></b>
                        <span class="secret_comunity"><i class="fal fa-lock"></i>비밀글</span>    
                    </div>

                    <div class="mobile_comunity_dates">
                        <span class="nick_name"></span>
                        <span class="pl-2 created_date"></span>
                        <span class="pr-2 pl-2 hit">조회 <em>0</em></span>
                        <span>추천 <em class="recomend">0</em></span>
                    </div>
                </div>
            </a>
        </td>
    </tr>
</template>
{{--
<!-- TODO:커뮤니티 자유게시판 view화면 열리는 버튼 -->
<input type="button" value="테스트 버튼입니다" class="test_button" onClick="community_view_show('#view_modal')">
<!-- END -->
--}}
<!-- TODO:커뮤니티 자유게시판 view화면 모달 -->
<div id="view_modal" class="board_view_modal">

    <div class="mobile_view_wrapper mobile_comunity_wrap m_cs_wrap-view">

        <div class="cs_table_view ios-scroll comunity_table_view scrl_wrap">

            <div class="panel_subject">
                <span class="subjt">게시판{{-- $board->title --}}</span>
                <div class="comunity_board_dates">
                    <span class="reporting_date rpt_author"><span>게시판{{-- $board->writer_nickname --}}</span></span>
                    <span class="reporting_date"><span>2011-11-11{{-- date("Y-m-d H:i", strtotime($board->created_at)) --}}</span></span>
                    <span class="reporting_date">{{ __('support.hits') }} <span>55{{-- sprintf('%02d', $board->hit) --}}</span></span>
                    <span class="reporting_date">{{ __('support.recom') }} <span id="recom_cnt">55{{-- $board->recomend --}}</span></span>
                </div>
                @auth
                <div class="option_btns">
                    <label class="option_btns_img" for="option_btn_view"></label>
                    <input type="checkbox" id="option_btn_view" style="display: none;">
                    <div class="option_btn_list">
                        {{--@if($board->writer_id == Auth::id() || Auth::id() == 202798 || Auth::id() == 5269)--}}
                        <!-- 글쓴이일 경우 -->
                        {{--@if($board->writer_id == Auth::id())--}}
                        <button type="button" class="option_btn" onclick="location.href='{{--route('comunity.edit', $board->id).'?board_name='.$board_name--}}'">{{ __('support.modi') }}</button>
                        {{--@endif--}}
                        <button type="button" class="option_btn" onClick="custom_alert_popup_open('#modal_popup_del_posting')">{{ __('support.delete') }}</button>
                        <form action="{{--route('comunity.destroy', $board->id).'?board_name='.$board_name.'&kind=board'--}}" method="post" id="delete_board_form" class="hide">
                            {{--@csrf--}}
                            <input type="hidden" name="_method" value="DELETE" />
                        </form>
                        {{--@endif--}}
                        <!-- 모든 유저일 경우 -->
                        <button type="button" class="option_btn" onclick="location.href='{{--route('comunity.create').'?board_name='.$board_name.'&re_id='.$board->id--}}'">{{ __('support.reple') }}</button>
                        
                    </div>
                </div>
                @endauth
            </div>

            <div class="panel_content board_content">
                {{-- {!! $board->content !!} --}}
            </div>

            <div class="comunity_board_view_bt_container">
                {{--@if($board->recomend_uid == NULL)--}}
                <div class="this_posting_recommned_btn" data-id="{{--$board_id--}}">
                    <i class="_icon"></i>
                    <span class="_text">{{ __('support.recom') }}</span>
                    <em class="_num">0</em>
                </div>
                {{--@else--}}
                <div class="this_posting_recommned_btn {{--(in_array($uid, json_decode($board->recomend_uid)))?'active':''--}}"  data-id="{{--$board_id--}}">
                    <i class="_icon"></i>
                    <span class="_text">{{-- __('support.recom') --}}</span>
                    <em class="_num">{{-- $board->recomend --}}</em>
                </div>
                {{--endif--}}

                <div class="reply_container">
                    
                    <div class="reply_con_hd">
                        <span class="_total_amt">{{ __('support.all') }}<em>{{--$board->comment_cnt--}}</em></span>
                        <select id="comment_orderby" class="reply_sorting_slt">
                            <option value="id" selected="selected">{{ __('support.order_write') }}</option>
                            <option value="recomend">{{ __('support.order_recom') }}</option>
                        </select>
                    </div>
                    <div id="reply_con_wrap" data-count="0" data-offset="0">
                    </div>
                    <div class="more_comment_btn" style="display:none;">더보기</div>
                    @auth
                        <div id="comment_inp_wrap" class="reply_con_self_mode">
                            <div class="self_contents">
                                <input type="hidden" name="comment_id">
                                <textarea placeholder="{{ __('support.please_input_reple') }}" name="comment"></textarea>
                                <button type="button" id="comment_submit">{{ __('support.enroll') }}</button>
                            </div>
                            <span class="self_input_group hide">
                                <label class="self_input_tit">{{ __('support.pic') }}</label>
                                <span class="self_input_con">
                                    <input type="file" id="reply_picture" style="display: none;">
                                    <label for="reply_picture" class="self_input_file_btn">{{ __('support.select_input_file') }}</label>
                                    <input type="text" placeholder="{{ __('support.nothing_input_file') }}" class="self_input_file_text" readonly>
                                </span>
                            </span>
                            <span class="self_input_group hide">
                                <label class="self_input_tit">{{ __('support.input_file') }}</label>
                                <span class="self_input_con">
                                    <input type="file" id="reply_picture" style="display: none;">
                                    <label for="reply_picture" class="self_input_file_btn">{{ __('support.select_input_file') }}</label>
                                    <input type="text" placeholder="{{ __('support.nothing_input_file') }}" class="self_input_file_text" readonly>
                                </span>
                            </span>
                        </div>
                    @endauth

                </div>

            </div>

            <button class="btn_style_next btn_fix" onclick="community_view_close('#view_modal');">{{ __('support.gotolist') }}</button>

        </div>

    </div>
</div>
<!-- END -->

<script>
var board_name = $('input[name="board_name"]').val();

$(function(){

    $('#comunity_scrl_top').click(function(){

        $('.comunity_board_list_wrap').animate({
            scrollTop: '0'
        }, 300);

    })

    $('.comunity_board_list_wrap').scroll(function(){
        var scrlTop = $(this).scrollTop();

        if( scrlTop > 100 ){
            $('#comunity_scrl_top').fadeIn(200);
        }else{
            $('#comunity_scrl_top').fadeOut(200);
        }
        if (scrlTop >= $('.cs_table').height() - $(this).height() - 10) {
            var offset = $('#comunity_list_wrap').data('offset');
            var count = $('#comunity_list_wrap').data('count');
            
            if(offset != count){
                var today = moment();
                $.ajax({
                    type : "GET",
                    dataType: "json",
                    data: { _token : $('meta[name="csrf-token"]').attr('content'), offset : offset, board_name : board_name, kind : 'board_more'},
                    url : "/api/comunity",
                    async : false,
                    success : function(data) {
                        var comunity_list_wrap = $('#comunity_list_wrap');
                        comunity_list_wrap.data('offset', data.offset);
                        data.boards.forEach(function(board) {
                            var dateTime = new Date(board.created_at);
                            var created_date = moment(dateTime, 'YYYY-MM-DD HH:mm');
                            var templete = $($('#tr_comunity_list').html());
                            
                            if(moment.duration(today.diff(created_date)).asDays() >= 2){
                                templete.find('.new_board_img').remove();
                            }

                            if(board.secret_key == null){
                                templete.find('.list_link').attr('href',"/comunity/"+board.id+"?board_name="+board_name+"");
                                templete.find('.secret_comunity').remove();
                            }else{
                                templete.find('.list_link').attr("onclick","custom_alert_popup_open('#modal_popup_change_pw'," + board.id + ")");
                            }
                            if(board.images == null){
                                templete.find('.comunity_board_thumb').remove();
                            }
                            templete.find('.comunity_board_title').text(board.title);
                            templete.find('.reply_amt').text(board.comment_cnt);
                            templete.find('.nick_name').text(board.writer_nickname);
                            templete.find('.created_date').text(moment(dateTime).format("YYYY-MM-DD"));
                            templete.find('.hit em').text(board.hit);
                            templete.find('.recomend').text(board.recomend);
                            
                            comunity_list_wrap.append(templete);
                        });

                    },
                    error : function(data){
                        swal({
                            title: "네트워크 오류",
                            text: "잠시 후 다시 시도해주세요.",
                            button: "확인",
                        });
                    }
                });
            }
        }

    })

});

$('#secret_key_btn').on('click', function(){
    var id = $(this).data('id');
    var secret_key = $('#secret_key').val();

    $.ajax({
        type : "POST",
        dataType: "json",
        data: { _token : $('meta[name="csrf-token"]').attr('content'), id : id, secret_key : secret_key, board_name : board_name},
        url : "/api/comunity/secret_key",
        success : function(data) {
            if(data.status){
                location.href="/comunity/"+id+"?board_name="+board_name+"";
            }else{
                swal({
                    title: "비밀번호 불일치",
                    icon: 'warning',
                    text: "비밀번호가 맞지 않습니다.\n다시 입력해주세요.",
                    button: "확인",
                });
            }

        },
        error : function(data){
            swal({
                title: "네트워크 오류",
                text: "잠시 후 다시 시도해주세요.",
                button: "확인",
            });
        }
    });
});

function custom_alert_popup_open(name, id){
    $(name).show().addClass('active');
    $('#secret_key_btn').data('id',id);
}
function custom_alert_popup_close(name){
    $('#modal_popup_change_pw input').val('');
    $(name).parents('.custom_alert_popup').fadeOut(200).removeClass('active');
}

// TODO:커뮤니티 자유게시판 view화면 모달 열리기
function community_view_show(name){
    $(name).fadeIn();
    $(name).children('.mobile_view_wrapper').addClass('active');
}//end

// TODO:커뮤니티 자유게시판 view화면 모달 닫히기
function community_view_close(name){
    $(name).fadeOut();
    $(name).children('.mobile_view_wrapper').removeClass('active');
}//end

</script>

@endsection
