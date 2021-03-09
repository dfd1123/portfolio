@extends('pc.layouts.app')

@section('content')
	<div class="sub_spot mypage" @if($banner->bn_file != NULL) style="background:url('{{asset('/storage/image/banner/'.$banner->bn_file)}}');" @endif>
		<h2>마이페이지</h2>
	</div>
	<div id="container">
		@include('pc.mypage.include.my_common')
		<div class="orderbox">
			<div class="cartbox">
				<h3 class="mytit">계좌번호 수정</h3>
			</div> 
			<!-- 비밀번호 변경 -->


                <div class="modifybox">
                    <h3 class="yellow_tit"><i class="fas fa-chevron-circle-right"></i> 계좌번호 수정</h3>
                    <form method="post" id="account_change_form" action="{{route('mypage.account_update')}}">
                        @csrf
                            <ul id="password_edit">
                                <li>
                                	<select id="account_bank" name="account_bank" class="required kr" required="required" style="width:100%;border-radius: 5px;padding:10px;margin-bottom:3px">
                                		<option value="0">기존 은행 : {{Auth::user()->account_bank}}</option>
                                		<option value="농협은행">농협은행</option>
                                		<option value="대구은행">대구은행</option>
                                		<option value="부산은행">부산은행</option>
                                		<option value="국민은행">국민은행</option>
                                		<option value="기업은행">기업은행</option>
                                		<option value="우리은행">우리은행</option>
                                	</select>
                                </li>
                                <li><input type="number" id="account_number" name="account_number" title="계좌번호" class="required kr" placeholder="계좌번호('-'제외)"  required="required" value="{{Auth::user()->account_number}}" /></li>
                                <li><input type="text" id="account_user" name="account_user" title="예금주 명" class="required kr" placeholder="예금주 명"  required="required" value="{{Auth::user()->account_user}}"/></li>
                                
                            </ul>
                            <button type="submit" class="joinbt">변경하기</button>
                    </form>
                </div>
			
            <!-- //비밀번호 변경 -->
            
		</div>
	</div>
@endsection