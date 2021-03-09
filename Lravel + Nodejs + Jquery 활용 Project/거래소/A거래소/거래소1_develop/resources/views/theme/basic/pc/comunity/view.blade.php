@extends(session('theme').'.pc.layouts.app') 
@section('content')

<div class="board_st_wrap cs_wrap comunity_st_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.comunity.include.sub_menu')

			<div class="right_con">

				<div class="cs_main_tit">
                    {{ __('head.board') }}
                    <div class="comunity_board_buttons">
                        @auth
                            @if($board->writer_id == Auth::id() || Auth::id() == 202798 || Auth::id() == 5269 || $comunity_admin != null)
                                <!-- 글쓴이일 경우 -->
                                @if($board->writer_id == Auth::id())
                                <button type="button" class="outline_btn" onclick="location.href='{{route('comunity.edit', $board->id).'?board_name='.$board_name}}'">{{ __('support.modi') }}</button>
                                @endif
                                <button type="button" class="outline_btn" onClick="custom_alert_popup_open('#modal_popup_del_posting')">{{ __('support.delete') }}</button>
                                <form action="{{route('comunity.destroy', $board->id).'?board_name='.$board_name.'&kind=board'}}" method="post" id="delete_board_form" class="hide">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE" />
                                </form>
                            @endif
                            <!-- 모든 유저일 경우 -->
                            @if($board->notice != 1)
                            <button type="button" class="outline_btn" onclick="location.href='{{route('comunity.create').'?board_name='.$board_name.'&re_id='.$board->id}}'">{{ __('support.reple') }}</button>
                            @endif
                        @endauth
                        <button type="button" class="solid_btn" onclick="location.href='{{route('comunity.index').'?board_name='.$board_name}}'">{{ __('support.list') }}</button>

                    </div>
                </div>

                <!-- 게시판 뷰 -->
                <div class="cs_table_view ios-scroll comunity_table_view">
                    <input type="hidden" name="board_name" value="{{$board_name}}">
                    <input type="hidden" name="board_id" value="{{$board_id}}">
                    <div class="panel_subject">
                        <span class="subjt">{{ $board->title }}</span>
                        <div class="comunity_board_dates">
                            <span class="reporting_date rpt_author">{{ __('support.writer') }} <span class="pl-2">{{ $board->writer_nickname }}</span></span>
                            <span class="reporting_date">{{ __('support.date_created') }} <span class="pl-2">{{date("Y-m-d H:i", strtotime($board->created_at))}}</span></span>
                            <span class="reporting_date">{{ __('support.hits') }} <span class="pl-2">{{ sprintf('%02d', $board->hit) }}</span></span>
                        </div>
                    </div>

                    <!-- 게시글 내용 -->
                    <div class="panel_content board_content">
                        {!! $board->content !!}
                    </div>
                    <!-- //게시글 내용 -->

                    <!-- bottom_container -->
                    <div class="comunity_board_view_bt_container">
                        @auth
                            @if($board->recomend_uid == NULL)
                                <div class="this_posting_recommned_btn"  data-id="{{$board_id}}">
                                    <i class="_icon"></i>
                                    <span class="_text">{{ __('support.recom') }}</span>
                                    <em class="_num">{{ $board->recomend }}</em>
                                </div>
                            @else
                                <div class="this_posting_recommned_btn {{(array_key_exists($uid, json_decode($board->recomend_uid)))?'active':''}}"  data-id="{{$board_id}}">
                                    <i class="_icon"></i>
                                    <span class="_text">{{ __('support.recom') }}</span>
                                    <em class="_num">{{ $board->recomend }}</em>
                                </div>
                            @endif
                        @else
                            <div class="this_posting_recommned_btn">
                                <i class="_icon"></i>
                                <span class="_text">{{ __('support.recom') }}</span>
                                <em class="_num">{{ $board->recomend }}</em>
                            </div>
                        @endauth

                        <!-- 댓글 -->
                        <div class="reply_container">
                            
                            <div class="reply_con_hd">
                                <span class="_total_amt">{{ __('support.all') }}<em>0</em></span>
                                <select id="comment_orderby" class="reply_sorting_slt">
                                    <option value="id" selected="selected">{{ __('support.order_new') }}</option>
                                    <option value="recomend">{{ __('support.order_recom') }}</option>
                                    <option value="past" >과거순</option>
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
                                            <input type="file" id="reply_file" style="display: none;">
                                            <label for="reply_file" class="self_input_file_btn">{{ __('support.select_input_file') }}</label>
                                            <input type="text" placeholder="{{ __('support.nothing_input_file') }}" class="self_input_file_text" readonly>
                                        </span>
                                    </span>
                                </div>
                            @endauth

                        </div>
                        <!-- //댓글 -->

                    </div>
                    <!-- //bottom_container -->

                    <!-- panel_footer -->
                    <div class="panel_footer mt-4">
                        @if($before_board)
                            
                            <div class="panel_ft_list">
                                <span class="ft_label">{{ __('support.before') }}</span>
                                <span class="ft_subjt"><a href="{{route('comunity.show',$before_board->id).'?board_name='.$board_name}}">{{$before_board->title}}</a></span>
                            </div>
                        @endif

                        @if($after_board)
                            <div class="panel_ft_list">
                                <span class="ft_label">{{ __('support.next') }}</span>
                                <span class="ft_subjt"><a href="{{route('comunity.show',$after_board->id).'?board_name='.$board_name}}">{{$after_board->title}}</a></span>
                            </div>		
                        @endif
                    </div>
                    <!--// panel_footer -->

                </div>
                <!-- // 게시판 뷰 -->

            </div>
            <!-- // right_con -->

        </div>
        <!-- // board_st_con -->

    </div>
    <!-- // board_st_inner -->
    
    <!-- 모달팝업 (게시글삭제확인) -->
    <div class="custom_alert_popup" id="modal_popup_del_posting">
        <h5 class="custom_alert_popup_tit">게시글을 삭제하시겠습니까?</h5>
        <span class="custom_alert_popup_desc">작성하신 게시글이 삭제됩니다.</span>
        <div class="double_btn">
            <button type="button" class="custom_alert_popup_btn outline_gray" onClick="custom_alert_popup_close(this)">취소</button>
            <button type="button" id="board_delete_btn" class="custom_alert_popup_btn outline_blue">확인</button>
        </div>
    </div>
    <!-- // 모달팝업 (게시글삭제확인) -->
    
    <!-- 모달팝업 (댓글삭제확인) -->
    <div class="custom_alert_popup" id="modal_popup_del_comment">
        <h5 class="custom_alert_popup_tit">댓글을 삭제하시겠습니까?</h5>
        <span class="custom_alert_popup_desc">작성하신 댓글이 삭제됩니다.</span>
        <div class="double_btn">
            <button type="button" class="custom_alert_popup_btn outline_gray" onClick="custom_alert_popup_close(this)">취소</button>
            <button type="button" id="comment_delete_btn" class="custom_alert_popup_btn outline_blue">확인</button>
        </div>
    </div>
    <!-- // 모달팝업 (댓글삭제확인) -->

    <!-- 모달팝업 (댓글삭제확인) -->
    <div class="custom_alert_popup" id="modal_popup_del_re_comment">
        <h5 class="custom_alert_popup_tit">댓글을 삭제하시겠습니까?</h5>
        <span class="custom_alert_popup_desc">작성하신 댓글이 삭제됩니다.</span>
        <div class="double_btn">
            <button type="button" class="custom_alert_popup_btn outline_gray" onClick="custom_alert_popup_close(this)">취소</button>
            <button type="button" id="re_comment_delete_btn" class="custom_alert_popup_btn outline_blue">확인</button>
        </div>
    </div>
    <!-- // 모달팝업 (댓글삭제확인) -->
    
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
    
    <!-- 모달팝업 (댓글편집) -->
    <div class="custom_alert_popup" id="modal_popup_edit_reple">
        <h5 class="custom_alert_popup_tit">댓글 편집</h5>
        <input type="hidden" name="recomment" value="0">
        <textarea class="default_textarea">댓글내용 예시입니다.</textarea>
        <div class="double_btn_02">
            <button type="button" class="custom_alert_popup_btn outline_gray" onClick="custom_alert_popup_close(this)">취소</button>
            <button type="button" class="custom_alert_popup_btn comment_edit_btn outline_blue" data-id="">확인</button>
        </div>
    </div>
    <!-- // 모달팝업 (댓글편집) -->

</div>
<!-- // board_st_wrap -->

<template id="reply_con_contents" class="hide">
    <div class="reply_con_contents">
        <div class="reply_list">
            <div class="hd">
                <h5 class="_nickname"></h5>
                <span class="_date"></span>
                <div class="hd_btns">
                    <button type="button" class="del" onClick="comment_delete('#modal_popup_del_comment')">{{ __('support.delete') }}</button>
                    <button type="button" class="edit" data-id="">{{ __('support.edit') }}</button>
                    <button type="button" class="reple" data-id="">{{ __('support.edit_reple') }}</button>
                </div> 
            </div>
            <div class="contents" data-id="">
                <p id="contents"></p>
            </div>
            <div class="btns">
                <button type="button" class="like_type_btn like_btn" data-id="">
                    <span>{{ __('support.like') }}</span>
                    <em class="recomend_cnt">0</em>
                </button>
                <button type="button" class="like_type_btn hate_btn" data-id="">
                    <span>{{ __('support.hate') }}</span>
                    <em class="unrecomend_cnt">0</em>
                </button>
            </div>
        </div>
        <div class="in_comment_wrap"  style="display:none;"></div>
        <div class="comment_write_wrap reply_con_self_mode" style="display:none;"></div>
    </div>
</template>

<template id="in_reply_con_contents" class="hide">
    <div class="reply_con_contents">
        <i class="fal fa-reply"></i>
        <div class="reply_list">
            <div class="hd">
                <h5 class="_nickname"></h5>
                <span class="_date"></span>
                <div class="hd_btns">
                    <button type="button" class="del" onClick="comment_delete('#modal_popup_del_re_comment')">{{ __('support.delete') }}</button>
                    <button type="button" class="edit" data-id="">{{ __('support.edit') }}</button>
                </div> 
            </div>
            <div class="contents" data-id="">
                <p id="contents"></p>
            </div>
            <div class="btns">
                <button type="button" class="like_type_btn like_btn" data-id="">
                    <span>{{ __('support.like') }}</span>
                    <em class="recomend_cnt">0</em>
                </button>
                <button type="button" class="like_type_btn hate_btn" data-id="">
                    <span>{{ __('support.hate') }}</span>
                    <em class="unrecomend_cnt">0</em>
                </button>
            </div>
        </div>
    </div>
</template>

<template id="comment_inp_con" class="hide">
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
            <input type="file" id="reply_file" style="display: none;">
            <label for="reply_file" class="self_input_file_btn">{{ __('support.select_input_file') }}</label>
            <input type="text" placeholder="{{ __('support.nothing_input_file') }}" class="self_input_file_text" readonly>
        </span>
    </span>
</template>

<script src="{{ asset('/js/pc/comunity_view.js') }}"></script>

<!-- end 임시 팝업소스 -->
@endsection
