@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.comunity.include.sub_menu')

			<div class="right_con">

				<div class="cs_main_tit">
                    @if($board_name != 'free')
                    {{ __('head.coin_board') }}
                    <select class="coin_comunity_slct">
                        @foreach($comunity_lists as $comunity_list)
                            <option value="{{$comunity_list->bo_table}}" {{($comunity_list->bo_table == $board_name)?'selected=selected':''}}>{!! __('coin_name.'.$comunity_list->coin_symbol) !!}</option>
                        @endforeach
                    </select>
                    @else
                        {{ __('head.board') }}
                    @endif
                </div>

                <!-- 게시판 리스트 뷰 -->
				<div class="cs_table_wrap comunity_table_wrap">

                    <div class="comunity_table_hd">
                        <form method="GET" action="" id="comunity_srch">
                            <input type="hidden" name="board_name" value="{{$board_name}}" />
                            <ul class="comunity_board_sorting">
                                <li {{($orderBy == 'latest' || $orderBy == NULL)?'class=active':''}}><input type="radio" name="orderBy" value="latest" class="hide" {{($orderBy == 'latest' || $orderBy == NULL)?'checked=checked':''}} />{{ __('support.order_new') }}</li>
                                <li {{($orderBy == 'recomend')?'class=active':''}}><input type="radio" name="orderBy" value="recomend" class="hide" {{($orderBy == 'recomend')?'checked=checked':''}} />{{ __('support.order_recom') }}</li>
                                <li {{($orderBy == 'updated_at')?'class=active':''}}><input type="radio" name="orderBy" value="updated_at" class="hide" {{($orderBy == 'updated_at')?'checked=checked':''}} />{{ __('support.order_update') }}</li>
                            </ul>
                            <div class="comunity_board_sch_wrap">
                                <select class="comunity_board_select_bar" name="filter">
                                    <option value="all" {{($srch_filter == 'all')?'selected=selected':''}}>{{ __('support.all') }}</option>
                                    <option value="title" {{($srch_filter == 'title')?'selected=selected':''}}>{{ __('support.title') }}</option>
                                    <option value="content" {{($srch_filter == 'content')?'selected=selected':''}}>{{ __('support.contents') }}</option>
                                    <option value="writer_nickname" {{($srch_filter == 'writer_nickname')?'selected=selected':''}}>{{ __('support.writer') }}</option>
                                </select>
                                <span class="comunity_board_sch_bar">
                                    <input type="text" name="srch" placeholder="{{ __('support.word_search') }}" {{($srch != NULL)?'value='.$srch:''}}>
                                    <button type="submit"></button>
                                </span>
                            </div>
                            @auth
                            <button type="button" class="solid_btn"  onclick="location.href='{{route('comunity.create').'?board_name='.$board_name}}'">{{ __('support.write_contents') }}</button>
                            @endauth
                        </form>
                    </div>

					<table class="table_label">
						<thead>
							<tr>
								<th class="hits_th">{{ __('support.hits') }}</th>
								<th class="recommend_th">{{ __('support.recom') }}</th>
								<th class="title_th">{{ __('support.title') }}</th>
								<th class="writer_th">{{ __('support.writer') }}</th>
								<th class="date_th">{{ __('support.date') }}</th>
							</tr>
						</thead>
					</table>

					<table class="cs_table">
						<tbody>
                            @foreach($notice_lists as $notice)
                                <tr class="notice_tr">
                                    <td class="hits_td" colspan="2">
                                        <span class="notice_symbol">공지사항</span>
                                    </td>
                                    <td class="title_td">
                                        <a href="/notice/{{$notice->id}}">
                                            <em class="comunity_board_title">{{$notice->title}}</em>
                                        </a>
                                    </td>
                                    <td class="writer_td">관리자</td>
                                    <td class="date_td">{{date("Y-m-d H:i", $notice->created)}}</td>
                                </tr>
                            @endforeach
                            @forelse($board_lists as $board)
                                @if($board->notice == 1)
                                    <!-- 공지사항은 제목색상이 다름 -->
                                    <tr class="notice_tr">
                                        <td class="hits_td" colspan="2">
                                            <span class="notice_symbol">공지사항</span>
                                        </td>
                                        <td class="title_td">
                                            <a href="{{ route('comunity.show', $board->id) }}?board_name={{$board_name}}">
                                                <em class="comunity_board_title">{{$board->title}}</em>
                                                <!-- ③ 댓글 수 보이기 -->
                                                <b class="reply_amt">{{number_format($board->comment_cnt)}}</b>    
                                            </a>
                                        </td>
                                        <td class="writer_td">{{$board->writer_nickname}}</td>
                                        <td class="date_td">{{date("Y-m-d H:i", strtotime($board->created_at))}}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="hits_td">{{number_format($board->hit)}}</td>
                                        <td class="recommend_td">{{number_format($board->recomend)}}</td>
                                        <td class="title_td">
                                            @if($board->secret_key == NULL || Auth::id() == 202798 || Auth::id() == 5269)
                                            <a href="{{ route('comunity.show', $board->id) }}?board_name={{$board_name}}">
                                                @if($board->images != NULL || $board->images != '')
                                                <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                                                <span class="comunity_board_thumb"></span>
                                                <!-- ② 새로 올린 게시글 뱃지 -->
                                                @endif
        
                                                @if(date_diff(new DateTime($board->created_at), $today)->days < 2)
                                                <img src="/images/icon/new_icon.svg" alt="new board">
                                                @endif
                                                <em class="comunity_board_title">{{$board->title}}</em>
                                                
                                                <!-- ③ 댓글 수 보이기 -->
                                                <b class="reply_amt">{{number_format($board->comment_cnt)}}</b>
                                                @if($board->secret_key != NULL)
                                                <span class="secret_comunity"><i class="fal fa-lock" aria-hidden="true"></i>{{ __('support.secret_board') }}</span>
                                                @endif
                                            </a>
                                            @else
                                            <a href="#" onclick="custom_alert_popup_open('#modal_popup_change_pw',{{$board->id}})">
                                                @if($board->images != NULL || $board->images != '')
                                                <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                                                <span class="comunity_board_thumb"></span>
                                                <!-- ② 새로 올린 게시글 뱃지 -->
                                                @endif
                                                @if(date_diff(new DateTime($board->created_at), $today)->days < 2)
                                                <img src="/images/icon/new_icon.svg" alt="new board">
                                                @endif
                                                
                                                <em class="comunity_board_title">{{$board->title}}</em>
                                                
                                                <!-- ③ 댓글 수 보이기 -->
                                                <b class="reply_amt">{{number_format($board->comment_cnt)}}</b>    
                                                <span class="secret_comunity"><i class="fal fa-lock" aria-hidden="true"></i>{{ __('support.secret_board') }}</span>
                                            </a>
                                            @endif
                                        </td>
                                        <td class="writer_td">{{$board->writer_nickname}}</td>
                                        <td class="date_td">{{date("Y-m-d H:i", strtotime($board->created_at))}}</td>
                                    </tr>
                                    @foreach($re_board_lists as $re_board)
                                        @if($re_board->re_id == $board->id)
                                            <tr>
                                                <td class="hits_td">{{number_format($re_board->hit)}}</td>
                                                <td class="recommend_td">{{number_format($re_board->recomend)}}</td>
                                                <td class="title_td">
                                                    @if($board->secret_key == NULL)
                                                    <a href="{{ route('comunity.show', $re_board->id) }}?board_name={{$board_name}}">
                                                        <i class="fal fa-reply" style="transform: rotate( 180deg );font-size: 16px;margin-right: 10px;"></i> 
                                                        @if($re_board->images != NULL || $re_board->images != '')
                                                        <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                                                        <span class="comunity_board_thumb"></span>
                                                        <!-- ② 새로 올린 게시글 뱃지 -->
                                                        @endif
                                                        <img src="/images/icon/new_icon.svg" alt="new board">
                                                        
                                                        <em class="comunity_board_title">{{$re_board->title}}</em>
                                                        
                                                        <!-- ③ 댓글 수 보이기 -->
                                                        <b class="reply_amt">{{number_format($re_board->comment_cnt)}}</b>    
                                                    </a>
                                                    @else
                                                    <a href="#" onclick="custom_alert_popup_open('#modal_popup_change_pw',{{$board->id}})">
                                                        <i class="fal fa-reply" style="transform: rotate( 180deg );font-size: 16px;margin-right: 10px;"></i> 
                                                        @if($re_board->images != NULL || $re_board->images != '')
                                                        <!-- ① 게시물에 이미지 있을 경우, 썸네일 -->
                                                        <span class="comunity_board_thumb"></span>
                                                        <!-- ② 새로 올린 게시글 뱃지 -->
                                                        @endif
                                                        <img src="/images/icon/new_icon.svg" alt="new board">
                                                        
                                                        <em class="comunity_board_title">{{$re_board->title}}</em>
                                                        
                                                        <!-- ③ 댓글 수 보이기 -->
                                                        <b class="reply_amt">{{number_format($re_board->comment_cnt)}}</b>    
                                                    </a>
                                                    @endif
                                                </td>
                                                <td class="writer_td">{{$re_board->writer_nickname}}</td>
                                                <td class="date_td">{{date("Y-m-d H:i", strtotime($re_board->created_at))}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @empty
                            <tr>
                                <td colspan="4" class="non_data"><i class="fas fa-exclamation-circle none_fas mr-1"></i>{{ __('support.no_board') }}</td>
                            </tr>
                            @endforelse
						</tbody>
					</table>

					<!--게시물 10개 이상이면 페이지 넘어가면서 페이지네이션 생김-->
					<!--<div class="cs_pagination">
						<ul>
							<li class="paging_arrow"><a href="#"><i class="fal fa-angle-double-left"></i></a></li>
							<li class="paging_arrow mr-1"><a href="#"><i class="fal fa-angle-left"></i></a></li>
							<li class="active"><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>
							<li><a href="#">5</a></li>
							<li><a href="#">6</a></li>
							<li class="paging_arrow ml-1"><a href="#"><i class="fal fa-angle-right"></i></a></li>
							<li class="paging_arrow"><a href="#"><i class="fal fa-angle-double-right"></i></a></li>
						</ul>
					</div>-->
					<!--//게시물 10개 이상이면 페이지 넘어가면서 페이지네이션 생김-->
                    @if($board_cnt >= 15)
                        <div class="cs_pagination">
                            {!! $boards->render() !!}
                        </div>
                    @endif

				</div>
                <!-- // 게시판 리스트 뷰 -->

			</div>
            <!-- // right_con -->

		</div>
        <!-- // board_st_con -->

	</div>
    <!-- // board_st_inner -->

    <!-- 모달팝업 (비밀번호확인) -->
    <div class="custom_alert_popup" id="modal_popup_input_pw">
        <h5 class="custom_alert_popup_tit">비밀번호 확인</h5>
        <span class="custom_alert_popup_input">
            <input type="password" placeholder="비밀번호를 입력하세요.">
        </span>
        <div class="double_btn_02">
            <button type="button" class="custom_alert_popup_btn outline_gray" onClick="custom_alert_popup_close(this)">취소</button>
            <button type="button" class="custom_alert_popup_btn outline_blue">확인</button>
        </div>
    </div>
    <!-- // 모달팝업 (비밀번호확인) -->

    <!-- 비밀글 열기 -->
    <form id="form-secret-post" class="hide" method="post">
        @csrf
        <input type="hidden" name="secret_key" />
    </form>
    <!-- //비밀글 열기 -->
</div>

<!-- 모달팝업 (비밀번호확인) -->
<div class="custom_alert_popup" id="modal_popup_change_pw">
    <h5 class="custom_alert_popup_tit">비밀번호 확인</h5>
    <span class="custom_alert_popup_input">
        <input type="password" id="secret_key" placeholder="비밀번호를 입력하세요." onkeypress="if(event.key === 'Enter'){ $('#secret_key_btn').click(); }">
    </span>
    <div class="double_btn_02">
        <button type="button" class="custom_alert_popup_btn outline_gray" onClick="custom_alert_popup_close(this)">취소</button>
        <button type="button" id="secret_key_btn" class="custom_alert_popup_btn outline_blue" data-id="">확인</button>
    </div>
</div>
<!-- // 모달팝업 (비밀번호확인) -->

<script>
    var board_name = $('input[name="board_name"]').val();

    $('.comunity_board_sorting li').on('click', function(){
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        $(this).children('input[name="orderBy"]').attr("checked", true);
        $('#comunity_srch').submit();
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
                    var form = $("#form-secret-post");
                    form.attr("action", "/comunity/" + id + "?board_name=" + board_name);
                    form.find('[name=secret_key]').val(secret_key);
                    form.submit();
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

    $('.coin_comunity_slct').on('change', function(){
        location.href='/comunity?board_name='+$(this).val()+'';
    })
</script>
@endsection
