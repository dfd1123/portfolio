@extends(session('theme').'.pc.layouts.app') 
@section('content')
<link rel="stylesheet" href="{{asset('vendor/ckeditor5-image/theme/textalternativeform.css')}}">
<div class="board_st_wrap cs_wrap">

	<div class="board_st_inner">

		<div class="board_st_con">
			
			@include(session('theme').'.pc.comunity.include.sub_menu')

			<div class="right_con ">

				<h1 class="cs_main_tit">
                @if($board_name != 'free')
                    {{ __('head.coin_board') }}
                @else
                    {{ __('head.board') }}
                @endif
                </h1>

                <div class="cs_table_view ios-scroll comunity_table_view">
                    <form method="POST" action="{{route('comunity.store').'?board_name='.$board_name}}" id="board_form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="kind" value="board" />
                        <input type="hidden" name="board_name" value="{{$board_name}}" />
                        <input type="hidden" name="re_id" {{($re_id != NULL)?'value='.$re_id:''}} />

                        <div class="date_input_divs">
                            
                            <!-- 글작성 제목  -->
                            <div class="date_input_list">
                                <span class="_label">{{ __('support.title') }}</span>
                                <span class="_subjt">
                                    <input type="text" class="_subjt_input" name="title" placeholder="{{ __('support.please_input_title') }}" required="required">
                                </span>
                            </div>
                            <!-- // 글작성 제목 -->

                            <!-- 글작성 옵션설정 (비밀글) -->
                            <div class="date_input_list">
                                <span class="_label">{{ __('support.option') }}</span>
                                    <span class="_subjt">
                                    <label class="check_secret_label">
                                        <input type="checkbox" id="check_secret" class="checkbox_style hide"> 
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
                            <!-- // 글작성 옵션설정 (비밀글) -->                            
                        </div>

                        <div class="comunity_board_write_container mt-4">
                            <!-- TODO: 글작성할 수 있는 공간 -->
                            <textarea name="content" id="textarea" style="width:100%;min-height: 600px;border:none;resize:none; padding:10px;"></textarea>
                        </div>

                        <!-- 첨부파일 및 검색설정 -->
                        <div class="date_input_divs mt-4">
                            <div class="date_input_list" style="display:none;">
                                <span class="_label">첨부파일1</span>
                                <span class="_subjt filebox bs3-primary preview-image">
                                    <input class="upload-name" value="파일선택" disabled="disabled" style="width: 300px;">
                                    <label for="file1">업로드</label> 
                                    <input type="file" name="files[]" id="file1" class="upload-hidden"> 
                                </span>
                            </div>

                            <div class="date_input_list" style="display:none;">
                                <span class="_label">첨부파일2</span>
                                <span class="_subjt filebox bs3-primary preview-image">
                                    <input class="upload-name" value="파일선택" disabled="disabled" style="width: 300px;">
                                    <label for="file2">업로드</label> 
                                    <input type="file" name="files[]" id="file2" class="upload-hidden"> 
                                </span>
                            </div>

                            <div class="date_input_list hide">
                                <span class="_label">{{ __('support.all_search') }}</span>
                                <span class="_subjt">
                                    <select class="_select" name="search_permit">
                                        <option value="1">{{ __('support.all_search_option_01') }}</option>
                                        <option value="2">{{ __('support.all_search_option_02') }}</option>
                                        <option value="3">{{ __('support.all_search_option_03') }}</option>
                                    </select>
                                </span>
                            </div>		
                            
                        </div>
                        <!-- // 첨부파일 및 검색설정 -->

                        <!-- 등록하기 -->
                        <button type="submit" class="solid_btn bt_right_btn">{{ __('support.registration') }}</button>
                    </form>
                </div>
                <!-- // comunity_table_view -->

            </div>
            <!-- // right_con -->

        </div>
        <!-- // board_st_con -->

    </div>
    <!-- // board_st_inner -->

    <!-- 모달팝업 (작성완료) -->
    <div class="custom_alert_popup" id="modal_popup_confirm_posting">
        <img src="/images/icon/icon_modal_complete.svg" alt="icon_modal_complete" class="modal_icon">
        <h5 class="custom_alert_popup_tit">게시글 등록 완료</h5>
        <button type="button" class="custom_alert_popup_btn outline_blue one_button" onClick="location.href='{{ route('comunity.index') }}'">확인</button>
    </div>
    <!-- // 모달팝업 (작성완료) -->

</div>
<!-- // board_st_wrap -->
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

        var str = '<input type="password" class="_pw_input" placeholder="{{ __('support.please_input_pw') }}" name="secret_key" required>';
        
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
