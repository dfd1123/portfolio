@extends(session('theme').'.mobile.layouts.app') 
@section('content')

<div class="mobile_comunity_wrap">
    <input type="hidden" name="board_name" value="{{$board_name}}">
    <input type="hidden" name="board_id" value="{{$board_id}}">
    <div class="m_hd_title">
        <div class="inner">
            {{__('head.community')}}
            <span class="write_btn_st write_btn" onClick="location.href='{{ route('comunity.create') }}?board_name={{$board_name}}'">
                <img src="{{ asset('/storage/image/homepage/mobile_icon/m_btn_write.svg')}}" alt="tab">
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
                <a href="{{ route('comunity.index') }}?board_name=btc"> 
                    {{ __('head.coin_board')}}
                </a>
            </li>
        </ul>
    </div>

    <div class="m_cs_wrap m_cs_wrap-view">

        <div class="cs_table_view ios-scroll comunity_table_view scrl_wrap">

            <div class="panel_subject">
                <span class="subjt">{{ $board->title }}</span>
                <div class="comunity_board_dates">
                    <span class="reporting_date rpt_author"><span>{{ $board->writer_nickname }}</span></span>
                    <span class="reporting_date"><span>{{date("Y-m-d H:i", strtotime($board->created_at))}}</span></span>
                    <span class="reporting_date">{{ __('support.hits') }} <span>{{ sprintf('%02d', $board->hit) }}</span></span>
                    <span class="reporting_date">{{ __('support.recom') }} <span id="recom_cnt">{{ $board->recomend }}</span></span>
                </div>
                @auth
                <div class="option_btns">
                    <label class="option_btns_img" for="option_btn_view"></label>
                    <input type="checkbox" id="option_btn_view" style="display: none;">
                    <div class="option_btn_list">
                        @if($board->writer_id == Auth::id() || Auth::id() == 202798 || Auth::id() == 5269 || $comunity_admin != null)
                        <!-- 글쓴이일 경우 -->
                        @if($board->writer_id == Auth::id())
                        <button type="button" class="option_btn" onclick="location.href='{{route('comunity.edit', $board->id).'?board_name='.$board_name}}'">{{ __('support.modi') }}</button>
                        @endif
                        <button type="button" class="option_btn" onClick="custom_alert_popup_open('#modal_popup_del_posting')">{{ __('support.delete') }}</button>
                        <form action="{{route('comunity.destroy', $board->id).'?board_name='.$board_name.'&kind=board'}}" method="post" id="delete_board_form" class="hide">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE" />
                        </form>
                        @endif
                        <!-- 모든 유저일 경우 -->
                        @if($board->notice != 1)
                        <button type="button" class="option_btn" onclick="location.href='{{route('comunity.create').'?board_name='.$board_name.'&re_id='.$board->id}}'">{{ __('support.reple') }}</button>
                        @endif
                    </div>
                </div>
                @endauth
            </div>

            <div class="panel_content board_content">
                {!! $board->content !!}
            </div>

            <div class="comunity_board_view_bt_container">
                @if($board->recomend_uid == NULL)        
                <div class="this_posting_recommned_btn" data-id="{{$board_id}}">
                    <i class="_icon"></i>
                    <span class="_text">{{ __('support.recom') }}</span>
                    <em class="_num">0</em>
                </div>
                @else
                <div class="this_posting_recommned_btn {{(array_key_exists($uid, json_decode($board->recomend_uid)))?'active':''}}"  data-id="{{$board_id}}">
                    <i class="_icon"></i>
                    <span class="_text">{{ __('support.recom') }}</span>
                    <em class="_num">{{ $board->recomend }}</em>
                </div>
                @endif

                <div class="reply_container">
                    
                    <div class="reply_con_hd">
                        <span class="_total_amt">{{ __('support.all') }}<em>{{$board->comment_cnt}}</em></span>
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

            <div class="panel_footer">
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

            <button class="btn_style_next btn_fix" onclick="location.href='{{ route('comunity.index').'?board_name='.$board_name }}'">{{ __('support.gotolist') }}</button>

        </div>

    </div>

    
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
    <div class="custom_alert_popup" id="modal_popup_change_pw">
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
            <button type="button" class="custom_alert_popup_btn comment_edit_btn outline_blue">확인</button>
        </div>
    </div>
    <!-- // 모달팝업 (댓글편집) -->

</div>

<template id="reply_con_contents" class="hide">
    <div class="reply_con_contents">
        <div class="reply_list">
            <div class="hd">
                <h5 class="_nickname"></h5>
                <span class="_date"></span>
                <div class="option_btns reply_option_btn">
                    <label class="option_btns_img"></label>
                    <div class="option_btn_list">
                        <button type="button" class="option_btn del" onClick="custom_alert_popup_open('#modal_popup_del_comment')">{{ __('support.delete') }}</button>
                        <button type="button" class="option_btn edit" data-id="">{{ __('support.edit') }}</button>
                        <button type="button" class="option_btn reple" data-id="">{{ __('support.edit_reple') }}</button>
                    </div>
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
                <div class="option_btns reply_option_btn">
                    <label class="option_btns_img"></label>
                    <div class="option_btn_list re_re_option_btn">
                        <button type="button" class="option_btn del"  onClick="comment_delete('#modal_popup_del_re_comment')">{{ __('support.delete') }}</button>
                        <button type="button" class="option_btn edit"  data-id="">{{ __('support.edit') }}</button>
                    </div>
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
            <input type="file" id="reply_picture" style="display: none;">
            <label for="reply_picture" class="self_input_file_btn">{{ __('support.select_input_file') }}</label>
            <input type="text" placeholder="{{ __('support.nothing_input_file') }}" class="self_input_file_text" readonly>
        </span>
    </span>
</template>

<script src="{{ asset('/js/mobile/comunity_view.js') }}"></script>

@endsection
