@extends('layouts.app')
@section('content')
<div class="container ex_padding ex_container pg_wrapper com_info_write">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default box_style">
				<div class="panel-body">
					<h3>보안 정보 인증하기</h3>
					
                    {{-- 이메일 인증~휴대폰 인증 그룹 --}}
        
                    <div class="lv_certifi_before">
                        @if($status == 0)
                        <h3>이메일 인증</h3>
                        @elseif($status == 1)
                        <h3>휴대폰 인증</h3>
                        @elseif($status == 2)
                        <h3>신분증 인증</h3>
                        @elseif($status == 3)
                        <h3>계좌 인증</h3>
                        @elseif($status == 3.5)
                        <h3>심사 대기중</h3>
                        @endif
                    
                        <div class="security_set_group">
                        
                            <!--디자인 그룹-->
                            <div class="boan_level_wrap mb-5">
                                <div class="boan_level_con mr-4 {{($status == 0)?'active':''}}">
                                    <span>이메일 인증</span>
                                </div>
                                <div class="boan_level_con mr-4 {{($status == 1)?'active':''}}">
                                    <span>휴대폰 인증</span>
                                </div>
                                <div class="boan_level_con mr-4 {{($status == 2)?'active':''}}">
                                    <span>신분증 인증</span>
                                </div>
                                <div class="boan_level_con {{($status == 2.5)?'active':''}}">
                                    <span>계좌 인증</span>
                                </div>
                            </div>                
                            <!--//디자인 그룹-->
                        
                            @if($status == 0)
                                @if (session('resent'))
                                    
                                    <p class="ment s_ment" style="text-align: center;">
                                    인증메일을 못받으셨다면? 
                                        
                                    </p>
                                    
                                    <div class="form-group mb-0 mt-4">
                                        <a class="resend_btn" href="{{ route('verification.resend') }}">
                                        재전송
                                        </a>
                                    </div>
                                    <div class="form-group mb-0 mt-4" style="text-align: center;">
                                        <a class="btn_style" href="{{ route('security') }}">인증 완료</a>
                                    </div>
                                @else
                                
                                    <p class="ment mb-3" style="text-align: center;">
                                    서비스 이용을 위해서는 이메일 인증이 필요합니다.		
                                    </p>
                                    
                                    <div class="form-group mb-0 mt-4" style="text-align: center;">
                                        <a id="verification_resend" class="btn_style vrf_send_btn" href="{{ route('verification.resend') }}">
                                        인증메일 전송
                                        </a>
                                    </div>

                                    
                                    
                                @endif
                            @elseif($status == 1)
                                <!--휴대폰 인증 폼 그룹-->
                                
                                
                                <form name="form_chk" method="post">
                                    <input type="hidden" name="m" value="checkplusSerivce">	
                                    <input type="hidden" name="EncodeData" value="{{ $enc_data }}">
                                </form>
                                
                                <div class="lv2_certifi_form_wrap" style="text-align: center;">
                                    <button type="button" id="sms_certify_confirm" class="btn certify_btn" onclick="fnPopup();">
                                    인증하기
                                    </button>
                                </div>
                                <!--//휴대폰 인증 폼 그룹-->
                            @elseif($status == 2)
                                <!--신분증 인증 -->
                                <div class="lv2_certifi_form_wrap">
                                    <span style="color:red;">거절 사유 : {{ $security->document_reject }}</span>
                                    <div class="form-group mb-2">
                                        <div class="certifi_form_group">
                                            <input type="text" class="mr-1 auth_input form-control" value="{{ Auth::user()->fullname }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-2">
                                        <div class="certifi_form_group">
                                            <input type="text" class="mr-1 auth_input form-control" value="{{ Auth::user()->mobile_number }}" readonly>
                                        </div>
                                    </div>
                                    <form method="post" enctype="multipart/form-data" action="{{ route('security_setting_document') }}" id="security_setting_document" >
                                        @csrf
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <div class="mobile_certify_code_wrap">
                                                    <input type="text" placeholder="신분증 사진" class="certifi_form_input auth_input form-control filename_input" readonly>
                                                    <input type="file" name="file1" id="thum_file" class="hide img_up">
                                                </div>
                                                <label class="btn certify_btn" for="thum_file">이미지 첨부</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <div class="mobile_certify_code_wrap">
                                                    <input type="text" placeholder="신분증 들고있는 사진" class="certifi_form_input auth_input form-control filename_input" readonly>
                                                    <input type="file" name="file2" id="thum_file2" class="hide img_up">
                                                </div>
                                                <label class="btn certify_btn" for="thum_file2">이미지 첨부</label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <button type="submit" id="document_certify_confirm" class="btn certify_btn">
                                                    인증하기
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </div>
                                
                                <!--//신분증 인증 -->
                            @elseif($status == 2.5)
                                <!--계좌인증-->
                                <div class="lv2_certifi_form_wrap">
                                    <span style="color:red;">거절 사유 : {{ $security->account_reject }}</span>
                                    <form method="post" enctype="multipart/form-data" action="{{ route('security_setting_account') }}" id="security_setting_account" >
                                    @csrf
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <input type="text" class="mr-1 auth_input form-control" value="{{ Auth::user()->fullname }}" readonly>
                                            </div>
                                            
                                        </div>
                                        
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <input type="text" name="account_num" id="account_num" class="mr-1 auth_input form-control" placeholder="계좌번호">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <input type="text" name="account_bank" id="account_bank" class="mr-1 auth_input form-control" placeholder="은행이름을 입력해주세요.">
                                            </div>
                                        </div>
                                    
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <div class="mobile_certify_code_wrap">
                                                    <input type="text" placeholder="통장 사본 사진" class="certifi_form_input auth_input form-control filename_input" readonly>
                                                    <input type="file" name="file1" id="thum_file" class="hide img_up">
                                                </div>
                                                <label class="btn certify_btn" for="thum_file">이미지 첨부</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <div class="mobile_certify_code_wrap">
                                                    <input type="text" placeholder="통장을 들고있는 사진" class="certifi_form_input auth_input form-control filename_input" readonly>
                                                    <input type="file" name="file2" id="thum_file2" class="hide img_up">
                                                </div>
                                                <label class="btn certify_btn" for="thum_file2">이미지 첨부</label>
                                            </div>
                                        </div>
                                        <div class="form-group mb-2">
                                            <div class="certifi_form_group">
                                                <button type="submit" id="document_certify_confirm" class="btn certify_btn">
                                                    인증하기
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    
                                </div>
                                <!--//계좌인증-->
                            @elseif($status == 3.5)
                                <!--계좌 및 신분증 제출 확인중-->
                                <div class="lv2_certifi_form_wrap">
                                    신분증과 계좌 확인중에 있습니다.
                                </div>
                                <!--//계좌 및 신분증 제출 확인중-->
                            @elseif($status >= 4)
                                <div class="lv2_certifi_form_wrap">
                                    모든 보안인증이 완료되었습니다. 사업자 등록을 마치셨다면 문제없이 페이 작동합니다.
                                </div>
                            @endif

					
				</div>
			</div>
		</div>
	</div>
</div>
@if (!session('resent'))
	<div class="overlay" style="display:none;"></div>
	<div class="sending_progress_wrap" style="display:none;">
		<div class="box">
			<div class="border one"></div>
			<div class="border two"></div>
			<div class="border three"></div>
			<div class="border four"></div>

			<div class="line one"></div>
			<div class="line two"></div>
			<div class="line three"></div>
		</div>
	</div>
@endif

@endsection

@section('script')

    @if (!session('resent'))
        <script>
            $(document).ready(function(){
                @if (session('status'))
                    alert('메일 발송 완료');
                @endif
            });

            $('#verification_resend').click(function(){
                $('.overlay').show();
                $('.sending_progress_wrap').show();

                return true;
            });
            
            function fnPopup(){
                window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
                document.form_chk.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
                document.form_chk.target = "popupChk";
                document.form_chk.submit();
            }

            function nicecheck_alert(status,messages){
                if(status == 0){
                    alert(messages);
                }else{
                    alert('휴대폰 인증 완료!');
                }
                
                location.reload();
            }

            $('#security_setting_document').on('submit',function(){
                if($('#thum_file').val() == ''){
                    alert('신분증 사진을 첨부해 주세요.');
                    return false;
                }else if($('#thum_file2').val() == ''){
                    alert('신분증 들고있는 사진을 첨부해 주세요.');
                    return false;
                }
                return true;
            });

            $('#security_setting_account').on('submit',function(){
                if($('#account_num').val() == ''){
                    alert('계좌번호를 입력해 주세요.');
                    return false;
                }else if($('#account_bank').val() == ''){
                    alert('계좌 은행을 입력해 주세요.');
                    return false;
                }else if($('#thum_file').val() == ''){
                    alert('통장이나 통장사본 사진을 첨부해 주세요.');
                    return false;
                }else if($('#thum_file2').val() == ''){
                    alert('통장을 들고있는 사진을 첨부해 주세요.');
                    return false;
                }
                return true;
            });

            // 파일 첨부됐을 때 파일명 보이게하기
            $('input[type="file"]').change(function(){
            
            FileName(this,0);
            
            });

            function FileName(x,y){
                
                if(window.FileReader){ // modern browser 
                    
                    var thisVal = $(x)[y].files[y].name; 
                    
                }else { // old IE 
                    
                    var thisVal = $(x).val().split('/').pop().split('\\').pop(); // 파일명만 추출 
                    
                }
                
                $(x).siblings('.filename_input').val(thisVal);
            }
        </script>
    @else
        <script>
            $(document).ready(function(){
                @if (session('status'))
                    alert('전송 완료. 가입시 등록한 이메일로 전송된 메일을 확인하시고 인증버튼을 눌러주세요.');
                @endif
            });
        </script>
    @endif
@endsection