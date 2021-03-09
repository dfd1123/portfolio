@extends(session('theme').'.mobile.layouts.app') 
@section('content')
<link rel="stylesheet" href="{{asset('vendor/ckeditor5-image/theme/textalternativeform.css')}}">
<div class="mobile_comunity_wrap">

    <div class="m_hd_title">
        <div class="inner">
            {{__('head.community')}}
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

    <!-- scrl_wrap -->
    <div class="scrl_wrap m_cs_wrap comunity_table_view_wrap">
        <form method="POST" action="{{route('comunity.store')}}" id="board_form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="kind" value="board" />
            <input type="hidden" name="board_name" value="{{$board_name}}" />
            <input type="hidden" name="re_id" {{($re_id != NULL)?'value='.$re_id:''}} />
            <div class="cs_table_view ios-scroll comunity_table_view">
                <div class="date_input_divs">
                    
                    <div class="date_input_list">
                        <span class="_label">{{ __('support.title') }}</span>
                        <span class="_subjt">
                            <input type="text" name="title" class="_subjt_input" placeholder="{{ __('support.please_input_title') }}" required="required">
                        </span>
                    </div>

                    <div class="date_input_list">
                        <span class="_label">{{ __('support.option') }}</span>
                        <span class="_subjt">
                            <label class="check_secret_label">
                                <input type="checkbox" class="checkbox_style hide" id="check_secret">
                                <label for="check_secret" class="checkbox_label"></label> {{ __('support.secret_board') }}
                            </label>
                        </span>
                        @if(!empty($comunity_admin))
                        <span class="_label">공지글</span>
                        <span class="_subjt">
                            <label>
                                <input type="checkbox" name="notice" id="check_notice" class="checkbox_style hide"> 
                                <label for="check_notice" class="checkbox_label"></label> 공지글
                            </label>
                        </span>
                        @endif
                    </div>		
                    
                </div>

                <div class="comunity_board_write_container" style="padding:0 0;border:none;">
                    <!-- TODO: 글작성할 수 있는 공간 -->
                    <textarea name="content" id="textarea" style="width:100%;border:none;resize:none; padding:10px;"></textarea>
                </div>

                <div class="date_input_divs mt-4">
                    
                    <div class="date_input_list" style="display:none;">
                        <span class="_subjt filebox bs3-primary preview-image">
                            <input class="upload-name" value="첨부파일 1" disabled="disabled" style="width: 80%">
                            <label for="file1">업로드</label> 
                            <input type="file" name="files[]" id="file1" class="upload-hidden"> 
                        </span>
                    </div>

                    <div class="date_input_list" style="display:none;">
                        <span class="_subjt filebox bs3-primary preview-image">
                            <input class="upload-name" value="첨부파일 2" disabled="disabled" style="width: 80%">
                            <label for="file2">업로드</label> 
                            <input type="file" name="files[]" id="file2" class="upload-hidden"> 
                        </span>
                    </div>

                    <div class="date_input_list hide">
                        <span class="_label">{{ __('support.all_search') }}</span>
                        <span class="_subjt">
                            <select class="_select">
                                <option value="제목과 내용 검색허용">{{ __('support.all_search_option_01') }}</option>
                                <option value="제목만 검색허용(비밀글)">{{ __('support.all_search_option_02') }}</option>
                                <option value="통합검색 제외">{{ __('support.all_search_option_03') }}</option>
                            </select>
                        </span>
                    </div>		
                    
                </div>

                <button type="submit" class="btn_style_next btn_fix solid_btn">{{ __('support.registration') }}</button>

            </div>
        </form>
    </div>
    <!-- //scrl_wrap -->
    

    <!-- 모달팝업 (작성완료) -->
    <div class="custom_alert_popup" id="modal_popup_confirm_posting">
        <img src="/images/icon/icon_modal_complete.svg" alt="icon_modal_complete" class="modal_icon">
        <h5 class="custom_alert_popup_tit">게시글 등록 완료</h5>
        <button type="button" class="custom_alert_popup_btn outline_blue one_button" onClick="location.href='{{ route('comunity.index') }}'">확인</button>
    </div>
    <!-- // 모달팝업 (작성완료) -->

</div>

<script type="text/javascript" src="{{ asset('vendor/ckeditor5/ckeditor.js') }}"></script>
<script>
    var My_editor;

    ClassicEditor
    .create( document.querySelector( '#textarea' ), {
        ckfinder: {
            uploadUrl: '/Ckfinder/image_upload'
        },
        //plugins: [ Image, ImageToolbar, ImageCaption, ImageStyle, ImageResize ],
        image: {
            // You need to configure the image toolbar, too, so it uses the new style buttons.
            toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],

            styles: [
                // This option is equal to a situation where no style is applied.
                'full',

                // This represents an image aligned to the left.
                'alignLeft',

                // This represents an image aligned to the right.
                'alignRight'
            ]
        },
    })
    .then( 
        editor => {
            My_editor = editor;
        } 
    )
    .catch( error => {
        //console.error( error );
    } );

    // 임시 팝업소스
    function custom_alert_popup_open(name){
        $(name).show().addClass('active');
    }
    function custom_alert_popup_close(name){
        $(name).parents('.custom_alert_popup').fadeOut(200).removeClass('active');
    }
    // end 임시 팝업소스

    // 비밀글 체크하면 비밀번호 입력하는거 나옴
    $('#check_secret').click(function(){

        var str = "<input type='password' class='_pw_input' placeholder='{{ __('support.please_input_pw') }}' name='secret_key' required>";
        
        if( $('#check_secret').is(':checked') == true ){

            $('.check_secret_label').append(str);

        }else if ( $('#check_secret').is(':checked') == false ){

            $('._pw_input').remove();

        }

    })
    // end 비밀글 체크하면 비밀번호 입력하는거 나옴

    $('#board_form').on('submit', function(){
        $('textarea[name="content"]').val(My_editor.getData());
        console.log(My_editor.getData());
        return true;
    });

</script>


@endsection
